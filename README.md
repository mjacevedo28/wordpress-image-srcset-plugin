# Fatherly Plugin

## Description
This plugin replaces all standard img tags to use the srcset attribute

## Instructions

Simply download and add to plugins folder, then activate in your Wordpress backend. 

## Content

This will replace all the``<img>`` tags that lie within ``the_content`` with responsive image tags.

I.e. if you have:



````html
	<img src="image.jpg" alt="Image description" class="some-class">
````

this plugin will convert that to:

````html
	<img srcset="thumbnail.jpg 150w, medium.jpg 300w, medium_large.jpg 768w large.jpg 1024w, full-size.jpg 2000w" sizes="(min-width: 1024px) 2000px, (min-width: 768px) 1024px, (min-width: 300px) 768px, (min-width: 150px) 300px, 150px"  alt="Image description" class="some-class">
````
