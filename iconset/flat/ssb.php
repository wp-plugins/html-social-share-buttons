<?php

class	zm_sh_iconset_flat extends __iconset_parent_class{
		public $id = 'flat';
		public $name = 'Flat';
		public $__FILE__	= __FILE__;					//Required, the path of file.
		//public $stylesheet	= "style.css";			//Optional, Use if that not named as style.css
		//public $preview_img	= "preview.png";		//Optional, Use if that not named as preview.png
		public $types = array("square", "circle");
		public $icons = array(
				'facebook'=>array(
								'id' => 'facebook',
								'name' => "Facebook",
								'class' => 'facebook',
								'image' => 'Facebook.png',
								'url' => "http://www.facebook.com/sharer.php?u=%%permalink%%&amp;t=%%title%%",
							),
				'twitter'=>array(
								'id' => 'twitter',
								'name' => "Twitter",
								'class' => 'twitter',
								'image' => 'Twitter.png',
								'url' => "http://twitter.com/share?url=%%permalink%%&amp;text=%%title%%",
							),
				'linkedin'=>array(
								'id' => 'linkedin',
								'name' => "Linkedin",
								'class' => 'linkedin',
								'image' => 'Linkedin.png',
								'url' => "http://www.linkedin.com/shareArticle?mini=true&url=%%permalink%%&amp;title=%%title%%",
							),
				'googlepluse'=>array(
								'id' => 'googlepluse',
								'name' => "Google Plus",
								'class' => 'googlepluse',
								'image' => 'Google Plus.png',
								'url' => "https://plus.google.com/share?url=%%permalink%%",
							),
				'bookmark'=>array(
								'id' => 'bookmark',
								'name' => "Google Bookmarks",
								'class' => 'bookmark',
								'image' => 'RSS.png',
								'url' => "http://www.google.com/bookmarks/mark?op=edit&bkmk=%%permalink%%&amp;title=%%title%%&annotation=%%description%%",
							),
				'pinterest'=>array(
								'id' => 'pinterest',
								'name' => "Pinterest",
								'class' => 'pinterest',
								'image' => 'Pinterest.png',
								'url' => "http://pinterest.com/pin/create/button/?url=%%permalink%%&amp;media=%%imageurl%%&amp;description=%%title%%",
							),
				'mail'=>array(
								'id' => 'mail',
								'name' => "Email",
								'class' => 'mail',
								'image' => 'Mail.png',
								'url' => "mailto:?subject=I wanted you to see this site&amp;body=This is about %%title%% %%permalink%%",
							),
				);
	
}


