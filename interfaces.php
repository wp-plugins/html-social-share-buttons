<?php

/*
Name: Iconset Interface
Description: This describe the iconset class

*/
interface interface_iconset{
	/*
	Set the icon set dir and iconset url.
	*/
	function set_dir_and_url($__FILE__);
	/*
	Add icon to current iconset
	*/
	public function push_icon($icon);
	/*
	Get full url for iconset preview image
	*/
	public function get_iconset_preview();
}