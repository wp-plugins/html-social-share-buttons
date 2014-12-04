<?php

new zm_sh_filters;
class zm_sh_filters{
	
	function __construct(){
		add_filter("zm_sh_placeholder", array($this, "zm_sh_placeholder"));
	}
	
	function zm_sh_placeholder($item){
		$zm_sh = zm_social_share::getInstance();
		$description = get_bloginfo ( 'description' );
		$item = str_replace( "%%permalink%%",	$zm_sh->permalink,		$item);
		$item = str_replace( "%%title%%",		$zm_sh->title,			$item);
		$item = str_replace( "%%description%%",	$description,			$item);
		return $item;
	}
}


