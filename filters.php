<?php

new zm_sh_filters;
class zm_sh_filters{
	
	function __construct(){
		add_filter("zm_sh_placeholder", array($this, "zm_sh_placeholder"));
	}
	
	function zm_sh_placeholder($item){
		$title = get_the_title();
		$parmalink = zm_sh_curentPageURL();
		$description = get_bloginfo ( 'description' );
		$item = str_replace( "%%permalink%%",	$parmalink,		$item);
		$item = str_replace( "%%title%%",		$title,			$item);
		$item = str_replace( "%%description%%",	$description,	$item);
		return $item;
	}
}


