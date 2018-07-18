<?php
/*
Plugin Name: Fatherly Srcset Plugin
Version: 1.0
Description: Wordpress plugin to replace all img tags to use srcset attribute.
Author: Marcelo Acevedo
Plugin URI: https://github.com/mjacevedo28/fatherly-plugin
*/

// Disable Wordpress native responsive image ability
add_filter('max_srcset_image_width', create_function('', 'return 1;'));

/**
 * Get all image tags and add srcset attribute
 * @param  $the_content 
 * @return updated Document with new HTML attributes
 */
function add_srcset_attr($the_content){

	// Get all img tags
	$document = new DOMDocument();
    $document->loadHTML($the_content);
    $images = $document->getElementsByTagName('img');
    
    // Array to hold resulting HTML tags with srcset/sizes
    $srcset = array();
    $sizes = array();
    
    // Get all available image sizes plus custom sizes   
    $imageSizes = get_img_sizes();
    
    // Full size not found so manually adding it
	$imageSizes[sizeof($imageSizes)] = array("width"=>'0', "height"=>'0', "size"=>'full');
	
	// Loop through all images, then through image sizes to add each size to each image's srcset
	foreach ($images as $image) {   
	    
	    $imgID = url_to_id($image->getAttribute('src'));  
	    $allWidths = array();
	    
	    foreach( $imageSizes as $available_size => $size ) {  
				
			$imageSrc = wp_get_attachment_image_src( $imgID, $size['size'] );
			$imageMeta = wp_get_attachment_metadata( $imgID );
			$imageUrl = $imageSrc[0];
	
			// Full size not found in metadata so grabbing from imageSrc info
			if ($size['size'] == "full"){
				$width = $imageSrc[1];
			} else {
				$width = $imageMeta['sizes'][$size['size']]['width'];
			}

			$srcset[ $available_size ] = $imageUrl . ' ' . $width . 'w';
			
			// Add all widths to a new array
			array_push($allWidths, $width);
		}
		
		// Go through array in reverse order to add sizes attribute;
		$allWidths = array_reverse($allWidths);
		for ($i = 0; $i < sizeof($allWidths); $i++){
			if ($i < sizeof($allWidths)-1){
				$sizes[$i] = "(min-width: " . $allWidths[$i+1] . "px) " . $allWidths[$i] . "px";
			}
			else {
				$sizes[$i] = $allWidths[$i] . "px";
			}
		}
	
		if ($srcset){
			$image->setAttribute('srcset', implode(', ', $srcset));
			$image->setAttribute('sizes', implode(', ', $sizes));
			$image->removeAttribute('src');
		}
    }

	return $document->saveHTML();   
}
add_filter('the_content', 'add_srcset_attr');

/**
 * Get image id from a given image URL
 * @param  $image_url 
 * @return array with image ID
 */
function url_to_id ($image_url) {
	global $wpdb;
	
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
    
    return $attachment[0];
}

/**
 * Get all available image sizes and custom sizes
 * @return array with widths, heights, size names
 */
function get_img_sizes() {
	
	global $_wp_additional_image_sizes;
	$sizes = array();

	// If not one of default sizes, add custom sizes
	foreach ( get_intermediate_image_sizes() as $_size ) {
		if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
			$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
			$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
			);
		}
		// Add size name
		$sizes[$_size]['size'] = $_size;
	}	
	
	// Sort images by width
	usort($sizes, function($img1, $img2){
	    return $img1['width'] < $img2['width'] ? -1 : 1;
    });
	
	return $sizes;
}

?>