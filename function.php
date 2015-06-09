<?php

/*
function name: zm_sh_add_attribute
Add html attribute to a single html tag.

Parameters:
	$html		: A single html tag to add attribute.
	$attr_name	: Name of the attribute to add.
	$attr_value	: The value of the attribute.

*/
wp_die(zm_sh_add_attribute('<a href="vdx" type="ddd" ></a>', 'rel', 'nofollow'));

function zm_sh_add_attribute($html, $attr_name, $attr_value){
	$xml = new SimpleXMLElement($html);
	$xml->addAttribute($attr_name, $attr_value);
	return $xml->asXML();
}