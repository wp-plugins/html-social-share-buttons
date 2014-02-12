<?php
/*
Plugin Name: Html Social share buttons
Plugin URI: http://www.zm-tech.net/simple-html-social-share-buttons/
Description: Html share button. It show lite share button only with html. It's not using any javascript whats anothers do. It's load only extra 10-11 kb total on your site.
Author: Alimuzzaman Alim
Version: 1.0.2
Author URI: http://www.zm-tech.net
*/
add_action( 'widgets_init', 'zm_register_widgets' );
add_action('wp_print_styles', 'zm_sh_style');
add_action('loop_start','zm_sh_in_loop');

function zm_register_widgets() {
	register_widget( 'zm_html_widget' );
}

class zm_html_widget extends WP_Widget {

	function zm_html_widget() {
		// Instantiate the parent object
		$widget_ops = array( 'description' => __("Html share button. It show lite share button only with html. It's not using any javascript whats anothers do.") );
		parent::__construct( "01197360491", "Html share button.",$widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
			
		$purl = plugin_dir_url( __FILE__ );
		global $zm_sh_permalink;
		global $zm_sh_title;
?>

<ul id="zmshbt">
<a href="http://www.facebook.com/sharer.php?u=<?php echo $zm_sh_permalink; ?>&t=<?php echo $zm_sh_title; ?>" target="_blank" title="Share This on Facebook">
</a>
<a href="http://twitter.com/share?url=<?php echo $zm_sh_permalink; ?>&text=<?php echo $zm_sh_title; ?>" target="_blank" alt="Tweet This!" title="Tweet This!" style="background-position:-31.5px 0px ">
</a>
<a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $zm_sh_permalink; ?>&title=<?php echo $zm_sh_title; ?>" target="_blank" title="Share This on LinkedIn" style="background-position:-63.5px 0px ">
</a>
<a href="https://plus.google.com/share?url=<?php echo $zm_sh_permalink; ?>" target="_blank" title="Post it on Google+" style="background-position:64.5px 0px ">
</a>
<a href="http://www.google.com/bookmarks/mark?op=edit&bkmk=<?php echo $zm_sh_permalink; ?>&title=<?php echo $zm_sh_title; ?>&annotation=<?php bloginfo('description'); ?>" target="_blank" title="Add to Google bookmark" style="background-position:32.5px 0px ">
</a>
</ul>
<?php 
echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
	}

	function form( $instance ) {
?>
		<li class="widget widget-sidebar" >
		<h3>Share This</h3>
		Html share button. It show lite 
		share button only with html. It's not using any javascript whats anothers do.
		No option's available due to simpling the widget for faster expreance<br />
		</li>
<?php 
	}
}



function zm_sh_style() {	
		 $purl = plugin_dir_url( __FILE__ );
?><style>#zmshbt a{background-image:url("<?php echo $purl;?>share.png");width:32px;height:32px;float:left;margin:5px;zoom:1.2;}#zmshbt a:hover{-webkit-transform:scale(1.6);-moz-transform:scale(1.6);-ms-transform:scale(1.6);-o-transform:scale(1.6);transform:scale(1.6);z-index:222;}#zmshbt{overflow:hidden !important;}#zmshbt ul{height:50px}</style><?php
}

function zm_sh_in_loop(){
	global $zm_sh_permalink;
	global $zm_sh_title;
	$zm_sh_permalink=curPageURL();
	$zm_sh_title=get_the_title();
	if(is_home()){
		$zm_sh_permalink=home_url();
		$zm_sh_title= get_option( "blogname");
	}
	elseif (is_category())  
	$zm_sh_title=single_cat_title("",false)." Archive";
elseif (is_tag())  
	$zm_sh_title=single_tag_title("",false)." Archive";
elseif (is_search()) 
	$zm_sh_title="Search Results for : ".the_search_query("",false);
elseif (is_author()) 
	$zm_sh_title="Author Archive";
elseif (is_day()) 	
	$zm_sh_title="Daily Archive : ".get_the_time('l, F j, Y');
elseif (is_month())  
	$zm_sh_title="Monthly Archive : ".get_the_time('F Y');  
elseif (is_year()) 
	$zm_sh_title="Yearly Archive : ".get_the_time('Y');  
}


function curPageURL() {
 $pageURL = 'http';
 if(isset($_SERVER["HTTPS"])) if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}



 

 





