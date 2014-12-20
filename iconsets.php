<?php


class zm_sh_iconset{
	public $iconset;
	public $iconsets;
	public $options;
	
	static function getInstance() {
		static $instance;
		if ($instance === null){
			$instance = new self;
			do_action( "zm_sh_add_iconset");
		}
		return $instance;
	}
	
	protected function __construct(){
		global $zm_sh_default_options;
		$this->options = get_option("zm_shbt_fld", $zm_sh_default_options);
	}
	
	function add_iconset($iconset){
		$id = $iconset['id'];
		$this->iconsets[$id] = $iconset;
		return $this->iconsets;
	}
	
	function get_current_iconset(){
		$iconset = $this->options['iconset'];
		$this->iconset = $this->get_iconset($iconset);
		return $this->iconset;
	}
	
	function get_iconset($iconset = "default"){
		return $this->iconsets[$iconset];
	}
	
	function get_iconsets(){
		return $this->iconsets;
	}
	function get_iconset_list(){
		$iocnsets = array();
		foreach($this->iconsets as $iconset){
			$id = $iconset['id'];
			$iocnsets[$id] = $iconset['name'];
		}
		return $iocnsets;
	}
	
	public function remove_iconset($id){
		unset($this->iconsets[$id]);
		return $id;
	}
	
	
}

function get_iconset_list(){
	$obj_iconset = zm_sh_iconset::getInstance();
	return $obj_iconset->get_iconset_list();
}

function zm_sh_get_current_iconset(){
	$obj_iconset = zm_sh_iconset::getInstance();
	return $obj_iconset->get_current_iconset();
}

function zm_sh_get_iconset($iconset = "default"){
	$obj_iconset = zm_sh_iconset::getInstance();
	return $obj_iconset->get_iconset($iconset);
}

add_action( 'wp_ajax_get_iconset_details', 'prefix_ajax_add_foobar' );

function prefix_ajax_add_foobar() {
	$icons = zm_sh_get_icons($_POST['data']);
	echo json_encode($icons);
	die();
}

function zm_sh_get_icons($iconset = "default"){
	$obj_iconset = zm_sh_iconset::getInstance();
	$icons = array();
	$iconset = $obj_iconset->get_iconset($iconset);
	foreach($iconset['icons'] as $icon){
		$icons[$icon['name']] = $icon['id'];
	}
	return $icons;
}

function zm_sh_get_iconsets(){
	$obj_iconset = zm_sh_iconset::getInstance();
	return $obj_iconset->get_iconsets();
}

function zm_sh_add_iconset($iconset){
	$obj_iconset = zm_sh_iconset::getInstance();
	return $obj_iconset->add_iconset($iconset);
}

function zm_sh_remove_iconset($id){
	$obj_iconset = zm_sh_iconset::getInstance();
	return $obj_iconset->remove_iconset($id);
}
