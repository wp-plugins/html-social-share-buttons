<?php
class zm_html_share_widget extends WP_Widget {
	
	function __construct() {
		// Instantiate the parent object
		$widget_ops = array( 'description' => __("Html share button. It show lite share button only with html. It's not using any javascript whats anothers do.") );
		parent::__construct( "html_share_button_widget", "Html share button widget", $widget_ops );
	}

	function widget( $args, $instance ) {
		global $zm_sh;
		extract( $args );
		//$instance = shortcode_atts($zm_sh_default_options, $instance);
		//if($instance[
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		$instance['class'] = "in_widget";
		echo $zm_sh->zm_sh_btn($instance);
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = $new_instance['title'];
		$instance['icons'] = $new_instance['icons'];
		$instance['iconset'] = $new_instance['iconset'];
		return $instance;
	}

	function form( $instance ) {
		global $zm_sh_in_widget ,$zm_sh_default_options;
		if(empty($instance))
			$instance = $zm_sh_default_options;
		$zm_sh_in_widget = array(
								'obj' => $this,
								'intstance' => $instance,
							);
							
		settings_fields( 'zm_shbt_opt' );
		do_settings_sections("zm_shbt_opt");
		
		$zm_sh_in_widget = false;
	}
}