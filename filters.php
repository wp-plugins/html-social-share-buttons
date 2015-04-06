<?php

new zm_sh_filters;
class zm_sh_filters{
	
	function __construct(){
		add_filter("zm_sh_placeholder", array($this, "zm_sh_placeholder"));
	}
	
	function zm_sh_placeholder($item){
		$title		= get_the_title();
		$parmalink	= zm_sh_curentPageURL();
		$description = get_bloginfo ( 'description' );
		$image_url	= $this->image_url($parmalink);
		$item 		= str_replace( "%%permalink%%",		urlencode($parmalink),	$item);
		$item 		= str_replace( "%%title%%",			urlencode($title),		$item);
		$item 		= str_replace( "%%description%%",	urlencode($description),$item);
		$item 		= str_replace( "%%imageurl%%",		urlencode($image_url),	$item);
		return $item;
	}
	
	function image_url($url) {
		$imageurl = urlencode( wp_get_attachment_url( get_post_thumbnail_id($post->ID) ) );
		
		if(!$imageurl){
			$postid = url_to_postid( $url );
			$post = get_post( $postid, "OBJECT" );
			$first_img = '';
			ob_start();
			ob_end_clean();
			$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', do_shortcode($post->post_content), $matches);
			$imageurl = $matches[1][0];
		}
		return $imageurl;
	}
}


