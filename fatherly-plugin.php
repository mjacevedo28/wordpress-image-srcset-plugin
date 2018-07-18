<?php
/*
Plugin Name: Fatherly Srcset Plugin
Version: 1.0
Description: Wordpress plugin to replace all img tags to use srcset attribute.
Author: Marcelo Acevedo
Plugin URI: https://github.com/mjacevedo28/fatherly-plugin
*/

add_filter('max_srcset_image_width', create_function('', 'return 1;'));

?>