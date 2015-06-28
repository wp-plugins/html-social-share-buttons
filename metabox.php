<?php

/**
 * Calls the class on the post edit screen.
 */
function zm_sh_metabox_new() {
    new zm_sh_metabox();
}

if ( is_admin() ) {
    add_action( 'load-post.php', 'zm_sh_metabox_new' );
    add_action( 'load-post-new.php', 'zm_sh_metabox_new' );
}

/** 
 * The Class.
 */
class zm_sh_metabox {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {
            $post_types = array('post', 'page');     //limit meta box to certain post types
            if ( in_array( $post_type, $post_types )) {
		add_meta_box(
			'zm_sh_metabox'
			,'Html Social Share'
			,array( $this, 'render_meta_box_content' )
			,$post_type
			,'side'
			,'high'
		);
            }
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['zm_sh_mtbox'] ) )
			return $post_id;

		$nonce = $_POST['zm_sh_mtbox'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'zm_sh_metabox' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		/* OK, its safe for us to save the data now. */

		// Sanitize the user input.
		$mydata = isset($_POST['_zm_sh_disable_share'])?sanitize_text_field( $_POST['_zm_sh_disable_share'] ):false;
		// Update the meta field.
		update_post_meta( $post_id, '_zm_sh_disable_share', $mydata );
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'zm_sh_metabox', 'zm_sh_mtbox' );
		
		// Use get_post_meta to retrieve an existing value from the database.
		$value = get_post_meta( $post->ID, '_zm_sh_disable_share', true );
		$checked	= checked($value, 'on', false);
		// Display the form, using the current value.
		echo '<input type="checkbox" id="_zm_sh_disable_share" name="_zm_sh_disable_share" '.$checked.' />';
		echo '<label for="_zm_sh_disable_share">';
		_e( 'Disable Social share for this page', 'zm-sh' );
		echo '</label> ';
	}
}