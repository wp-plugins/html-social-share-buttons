<?php

new		zm_sh_iconset_The_Pink_Group;
class	zm_sh_iconset_The_Pink_Group{
	
	function __construct(){
		//add_action("zm_sh_add_schema", array($this,"add_schema")); // no needed because all are included in default
		add_action("zm_sh_add_iconset", array($this,"add_iconset"));
	}
	
	function add_iconset(){
		$iconset = array();
		$iconset['id'] = 'The_Pink_Group';
		$iconset['name'] = 'The Pink Group';
		$iconset['dir'] = plugin_dir_path( __FILE__ );
		$iconset['url'] = plugins_url( "/", __FILE__ );
		$iconset['stylesheet'] = "style.css";
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
				'pinterest'=>array(
								'id' => 'pinterest',
								'name' => "Pinterest",
								'class' => 'pinterest',
								'image' => 'pinterest.png',
								'url' => "http://pinterest.com/pin/create/button/?url=%%permalink%%&amp;media=%%imageurl%%&amp;description=%%title%%",
							),
				'mail'=>array(
								'id' => 'mail',
								'name' => "Email",
								'class' => 'mail',
								'image' => 'mail.png',
								'url' => "mailto:?subject=I wanted you to see this site&amp;body=This is about %%title%% %%permalink%%",
							),
				);
		zm_sh_add_iconset($iconset);
	}
	
	function add_schema(){
		
		$schemas[] = array(
						'id' => 'facebook',
						'name' => "Facebook",
						'class' => 'facebook',
						'image' => 'facebook.png',
						'url' => "http://www.facebook.com/sharer.php?u=%%permalink%%&amp;t=%%title%%",
					);
		
		$schemas[] = array(
						'id' => 'twitter',
						'name' => "Twitter",
						'class' => 'twitter',
						'image' => 'twitter.png',
						'url' => "http://twitter.com/share?url=%%permalink%%&amp;text=%%title%%",
					);
		
		zm_sh_add_schema($schemas);
	}
	
	
	
	
	
}


