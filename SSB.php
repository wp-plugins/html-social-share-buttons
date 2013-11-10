<?php
/*
Plugin Name: Html Social share buttons
Plugin URI: http://www.zm-tech.net/simple-html-social-share-buttons/
Description: Html share button. It show lite share button only with html. It's not using any javascript whats anothers do. It's load only extra 10-11 kb total on your site.
Author: Alimuzzaman Alim
Version: 1.0
Author URI: http://www.zm-tech.net
*/

class zm_html_widget extends WP_Widget {

	function zm_html_widget() {
		// Instantiate the parent object
		$widget_ops = array( 'description' => __("Html share button. It show lite share button only with html. It's not using any javascript whats anothers do.") );
		parent::__construct( "01714900245", "Html share button.",$widget_ops );
	}

	function widget( $args, $instance ) {

		$purl = plugin_dir_url( __FILE__ );
		global $zm_sh_permalink;
		global $zm_sh_title;
?>
<li class="widget widget-sidebar" id="zmshbt" >

<h3>Share This If you like</h3>
<ul>
<a href="http://www.facebook.com/sharer.php?u=<?php echo $zm_sh_permalink; ?>&t=<?php echo $zm_sh_title; ?>" target="_blank" title="Share This on Facebook">
</a>
<a href="http://twitter.com/share?url=<?php echo $zm_sh_permalink; ?>&text=<?php echo $zm_sh_title; ?>" target="_blank" alt="Tweet This!" title="Tweet This!" style="background-position:-31.5px 0px ">
</a>
<a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $zm_sh_permalink; ?>&title=<?php echo $zm_sh_title; ?>" target="_blank" title="Share This on LinkedIn" style="background-position:-63.5px 0px ">
</a>
<a href="https://plus.google.com/share?url=<?php echo $zm_sh_permalink; ?>" target="_blank" title="Post it on Google+" style="background-position:64.5px 0px ">
</a>
<a href="http://www.google.com/bookmarks/mark?op=edit&bkmk=<?php echo $zm_sh_permalink; ?>&title=<?php echo $zm_sh_title; ?>&annotation=<?php bloginfo('description'); ?>" target="_blank" title="Post it on Google Blogger" style="background-position:32.5px 0px ">
</a>
</ul>

</li>
<?php 
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

function zm_register_widgets() {
	register_widget( 'zm_html_widget' );
}

add_action( 'widgets_init', 'zm_register_widgets' );

function zm_sh_style() {	
		 $purl = plugin_dir_url( __FILE__ );
?><style>#zmshbt a{background-image:url("<?php echo $purl;?>share.png");width:32px;height:32px;float:left;margin:5px;zoom:1.2;}#zmshbt a:hover{-webkit-transform:scale(1.6);-moz-transform:scale(1.6);-ms-transform:scale(1.6);-o-transform:scale(1.6);transform:scale(1.6);z-index:222;}#zmshbt{overflow:visible !important;margin-bottom:100px;}</style><?php
}
add_action('wp_enqueue_scripts', 'zm_sh_style');
add_action('loop_start','zm_sh_in_loop');

function zm_sh_in_loop(){
	global $zm_sh_permalink;
	global $zm_sh_title;
	$zm_sh_permalink=get_permalink();
	$zm_sh_title=get_the_title();	
} 