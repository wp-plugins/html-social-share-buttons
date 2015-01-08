<?php

new		zm_sh_iconset_Prajin;
class	zm_sh_iconset_Prajin{
	
	function __construct(){
		//add_action("zm_sh_add_schema", array($this,"add_schema")); // no needed because all are included in default
		add_action("zm_sh_add_iconset", array($this,"add_iconset"));
	}
	
	function add_iconset(){
		$iconset = array();
		$iconset['id'] = 'Prajin';
		$iconset['name'] = 'Prajin';
		$iconset['dir'] = plugin_dir_path( __FILE__ );
		$iconset['url'] = plugins_url( "/", __FILE__ );
		$iconset['stylesheet'] = "style.css";
		//$iconset['preview_img'] = "preview.png";
		$iconset['types'] = array("square", "circle");
		$iconset['icons'] = array(
				'facebook'=>array(
								'id' => 'facebook',
								'name' => "Facebook",
								'class' => 'facebook',
								'image' => 'facebook.png',
								'url' => "http://www.facebook.com/sharer.php?u=%%permalink%%&amp;t=%%title%%",
							),
				'twitter'=>array(
								'id' => 'twitter',
								'name' => "Twitter",
								'class' => 'twitter',
								'image' => 'twitter.png',
								'url' => "http://twitter.com/share?url=%%permalink%%&amp;text=%%title%%",
							),
				'linkedin'=>array(
								'id' => 'linkedin',
								'name' => "Linkedin",
								'class' => 'linkedin',
								'image' => 'linkedin.png',
								'url' => "http://www.linkedin.com/shareArticle?mini=true&url=%%permalink%%&amp;title=%%title%%",
							),
				'googlepluse'=>array(
								'id' => 'googlepluse',
								'name' => "Google Plus",
								'class' => 'googlepluse',
								'image' => 'googlepluse.png',
								'url' => "https://plus.google.com/share?url=%%permalink%%",
							),
				'bookmark'=>array(
								'id' => 'bookmark',
								'name' => "Google Bookmarks",
								'class' => 'bookmark',
								'image' => 'bookmark.png',
								'url' => "http://www.google.com/bookmarks/mark?op=edit&bkmk=%%permalink%%&amp;title=%%title%%&annotation=%%description%%",
							),
				'pinterest'=>array(
								'id' => 'pinterest',
								'name' => "Pinterest",
								'class' => 'pinterest',
								'image' => 'pinterest.png',
								'url' => "http://pinterest.com/pin/create/button/?url=%%permalink%%&amp;media=%%imageurl%%&amp;description=%%title%%",
							),
				);
		zm_sh_add_iconset($iconset);
	}
	
	function add_schema(){
		zm_sh_add_schema($schemas);
	}
	
	
	
	
	
}


