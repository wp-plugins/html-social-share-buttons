<?php

class zm_sh_iconset{
	public	$options;
	private	$iconsets;
	private	$iconsetId;
	private	$curr_iconset;
	
	function __get($var){
		if($var == 'curr_iconset')
			return $this->get_current_iconset();
		if($var == 'private')
			return $this->get_current_iconset();
		elseif(isset($this->iconsets->$var))
			return $this->iconsets->$var;
	}
	
	function __construct(){
		global $zm_sh, $zm_sh_default_options;
		global $zm_sh_iconset_classes;
		$this->iconsets	= new stdClass;
		//var_dump($this->iconsets);
		$this->options = get_option("zm_shbt_fld", $zm_sh_default_options);
		//print_r($zm_sh_iconset_classes);
		foreach($zm_sh_iconset_classes as $iconset)
			$this->add_iconset(new $iconset);
		do_action( "zm_sh_add_iconset");
		add_action( 'wp_ajax_get_iconset', array($this, 'wp_ajax_get_iconset') );
		add_action( 'wp_ajax_get_iconset_preview', array($this, 'wp_ajax_get_iconset_preview') );
	}
	
	function add_iconset($iconset){
		$id = $iconset->id;
		if(empty($id)) return;
		$this->iconsets->$id = $iconset;
		$class	= get_class($iconset);
		if(isset($zm_sh_iconset_classes[$class]))
			unset($zm_sh_iconset_classes[$class]);
		return $this->iconsets->$id;
	}
	
	function get_current_iconset(){
		$this->iconsetId = $this->options['iconset'];
		$this->curr_iconset = $this->get_iconset($this->iconsetId);
		return $this->curr_iconset;
	}
	
	function set_current_iconset($iconset_name){
		$this->curr_iconset = $iconset_name;
		return true;
	}
	
	function get_iconset($iconset = "default", $setAsCurrent = false){
		//print_r(debug_backtrace());
		//if(empty($iconset)) return false;
		//echo '"><pre>';
		//print_r(debug_backtrace ());
		if($setAsCurrent)
			$this->curr_iconset = $this->iconsets->$iconset;
		if(isset($this->iconsets->$iconset))
			return $this->iconsets->$iconset;
		else
			return  $this->iconsets->default;
	}
	
	function get_iconsets(){
		return $this->iconsets;
	}
	
	function get_iconset_list(){
		$iocnsets = array();
		foreach($this->iconsets as $iconset){
			$id = $iconset->id;
			$iocnsets[$id] = $iconset->name;
		}
		return $iocnsets;
	}
	
	public function remove_iconset($id){
		unset($this->iconsets->$id);
		return $id;
	}
	
	
	function wp_ajax_get_iconset_preview(){
		$iconset_id	= $_POST['iconsetId'];
		$preview	= $this->get_iconset($iconset_id)->get_iconset_preview();
		echo $preview;
		die();
	}
	
	function wp_ajax_get_iconset(){
		$iconset_id	= $_POST['iconsetId'];
		$iconset	= $this->get_iconset($iconset_id);
		echo json_encode($iconset);
		die();
	}
	
}


abstract class __iconset_parent_class implements interface_iconset{
	public		$__FILE__;
	public		$id;
	public		$name;
	public		$types;
	public		$icons;
	public		$inTheme	= false;
	
	public		$stylesheet		= "style.css";
	public		$preview_img	= "preview.png";
	
	function __construct(){
		$this->set_dir_and_url($this->__FILE__);
		
	}
	
	
	function set_dir_and_url($__FILE__){
		if(isset($this->inTheme) and $this->inTheme){
			$this->dir				= get_template_directory(). $this->inTheme;
			$this->url				= get_template_directory_uri(). $this->inTheme;
		}
		elseif(isset($this->inChildTheme) and $this->inChildTheme){
			$this->dir				= get_stylesheet_directory(). $this->inChildTheme;
			$this->url				= get_stylesheet_directory_uri(). $this->inChildTheme;
		}
		else{
			$this->dir				= plugin_dir_path( $__FILE__ );
			$this->url				= plugins_url( "/", $__FILE__ );
		}
		$this->stylesheet_url	= $this->url . $this->stylesheet;
		$this->preview_img_url	= $this->url . $this->preview_img;
		$this->preview_img_dir	= $this->dir . $this->preview_img;
	}
	
	public function get_icons(){
		return $this->icons;
	}
	
	public function get_icons_id_name(){
		$new	= array();
		foreach( $this->icons as $id=>$icon)
			$new[$id]	= $icon['name'];
		return $new;
	}
	
	public function push_icon($icon){
		$this->icons[]	= $icon;
	}
	public function get_iconset_preview(){
		return $this->url . $this->preview_img;
	}
	
	
}

// Searching for iconsets
// This will search for ssb.php if found then includ that.
$dir = scandir($dir_iconset);
foreach ($dir as $subdir) {
	if ($subdir === '.' or $subdir === '..') continue;
	$iconset_file = $dir_iconset . '/' . $subdir . "/ssb.php";
	if (file_exists($iconset_file)) {
		require_once $iconset_file;
		$class_name	= 'zm_sh_iconset_' . $subdir;
		if(class_exists($class_name))
			$zm_sh_iconset_classes[$class_name]	= $class_name;
	}
}


add_action( 'wp_ajax_get_iconset_details', 'wp_ajax_get_iconset_details' );

function wp_ajax_get_iconset_details() {
	$iconset_class	= new zm_sh_iconset;
	$iconset = $iconset_class->get_iconset($_POST['iconset']);
	echo json_encode($iconset->get_icons_id_name());
	die();
}
