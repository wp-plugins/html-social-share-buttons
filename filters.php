<?php

new zm_sh_filters;
class zm_sh_filters{
	
	function __construct(){
		add_filter("zm_sh_placeholder", array($this, "zm_sh_placeholder"));
		add_filter("zm_sh_ico_link", array($this, "ico_link"));
	}
	
	function zm_sh_placeholder($item){
		$title			= get_the_title();
		$parmalink		= zm_sh_curentPageURL();
		$description	= get_bloginfo ( 'description' );
		$image_url		= $this->image_url($parmalink);
		$item 			= str_replace( "%%permalink%%",		urlencode($parmalink),		$item);
		$item 			= str_replace( "%%title%%",			urlencode($title),			$item);
		$item 			= str_replace( "%%description%%",	urlencode($description),	$item);
		$item 			= str_replace( "%%imageurl%%",		urlencode($image_url),		$item);
		return $item;
	}
	
	function ico_link($ico_link){
		
		
		return $ico_link;
	}
	
	function image_url($url) {
		global $post;
		$thumb_id	= get_post_thumbnail_id($post->ID);
		$attachmetn_url	= wp_get_attachment_url( $thumb_id);
		$imageurl = urlencode( $attachmetn_url );
		
		if(!$imageurl){
			$postid = url_to_postid( $url );
			$post = get_post( $postid, "OBJECT" );
			$content	= $post->post_content;
			$content	= str_replace('zm_sh_btn', '', $content);
			$content	= do_shortcode($content);
			$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
			if(isset($matches[1][0]))
				$imageurl = $matches[1][0];
		}
		return $imageurl;
	}
}


