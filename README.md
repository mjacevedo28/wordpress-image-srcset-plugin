# Fatherly Plugin

## Description
This plugin replaces all standard img tags to use the srcset attribute

## Instructions

Simply download and add to plugins folder, then activate in your Wordpress backend. 

## Content

This will replace all the``<img>`` tags that lie within ``the_content`` with responsive image tags.

I.e. if you have:
**1.** Download the .zip file

**2.** Unarchive the .zip file in the wp-content/plugins folder of your Wordpress directory

**3.** Log in to the backend of your Wordpress site
**4.** Navigate to the plugins page and find the "Fatherly Srcet Plugin"
**5.** Click "Activate" and you're all set!


````html
	<img src="image.jpg" alt="Image description" class="some-class">
````

this plugin will convert that to:

````html
	<img srcset="thumbnail.jpg 150w, medium.jpg 300w, 
	medium_large.jpg 768w large.jpg 1024w, 
	full-size.jpg 2000w" 
	sizes="(min-width: 1024px) 2000px, (min-width: 768px) 1024px, 
	(min-width: 300px) 768px, (min-width: 150px) 300px, 150px"  
	alt="Image description" class="some-class">
````
