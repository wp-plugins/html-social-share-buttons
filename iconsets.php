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
	
	public function remove_iconset($id){
		unset($this->iconsets[$id]);
		return $id;
	}
	
	
}

function zm_sh_get_current_iconset(){
	$obj_iconset = zm_sh_iconset::getInstance();
	return $obj_iconset->get_current_iconset();
}

function zm_sh_get_iconset($iconset = "default"){
	$obj_iconset = zm_sh_iconset::getInstance();
	return $obj_iconset->get_iconset($iconset);
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
