<?php



//registering widget
add_action( 'widgets_init', 'zm_sh_register_widgets' );
function zm_sh_register_widgets() {
	global $zm_sh;
	if(isset($zm_sh->excluded) and $zm_sh->excluded == true) return;
	register_widget( 'zm_html_share_widget' );
}



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
		$options['show_on'] = 'widget';
		echo $zm_sh->zm_sh_btn($instance);
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = $new_instance['title'];
		$instance['icons'] = $new_instance['icons'];
		$instance['iconset_type'] = $new_instance['iconset_type'];
		$instance['iconset'] = $new_instance['iconset'];
		return $instance;
	}

	function form( $instance ) {
		global $zm_sh_default_options;
		//print_r($instance);
		if(empty($instance))
			$instance = $zm_sh_default_options;
		//print_r($instance);
		$zm_form	= new zm_form;
		//$instance	= !empty($instance) ? $instance : $zm_sh_default_options;
		//print_r($zm_form->iconsets);
		?>
		<div class="wrap HSSWidget">
				<?php settings_fields( 'zm_shbt_opt' ); ?>
                <h3>Select theme and Icon Style</h3>
                <?php $zm_form->text($this->get_field_id( 'title' ), "Enter a Title", $this->get_field_name( 'title' ), $instance['title']);?>
				
                <?php $zm_form->select_iconset($this->get_field_id("iconset"), "Select Button Style", $this->get_field_name( 'iconset' ), $instance['iconset'] );?>
                
                <?php $zm_form->dropdown($this->get_field_id("iconset_type"), "Select Type", $zm_form->iconsets->get_iconset($instance['iconset'])->types, $this->get_field_name( 'iconset_type' ), $instance['iconset_type']);?>
                
                <?php $zm_form->icon_fields_widget($this->get_field_id("icons"), $this->get_field_name( 'icons' ), $instance['icons'], "Select Buttons", "Enable", $instance['iconset']);?>
                
        </div>
		<?php
	}
}