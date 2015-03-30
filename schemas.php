<?php
class zm_sh_schema{
	private $schemas;
	
	static function getInstance() {
		static $instance;
		if ($instance === null){
			$instance = new self;
			do_action("zm_sh_add_schema");
		}
		return $instance;
	}
	
	protected function __construct() {
		
	}
		
	public function get_schema($id){
		return $this->schemas[$id];
	}
	
	public function get_schemas(){
		return $this->schemas;
	}
	
	public function add_schema($schema){
		if(is_array($schema)){
			foreach($schema as $sch){
				$id = $sch['id'];
				$this->schemas[$id] = $sch;
			}
		}
		else{
			$id = $schema['id'];
			$this->schemas[$id] = $schema;
		}
		return $this->schemas;
	}
	
	public function remove_schema($id){
		unset($this->schemas[$id]);
		return $id;
	}
	
}

function zm_sh_get_schema($id){
	$zm_sh_schema = zm_sh_schema::getInstance();
	return $zm_sh_schema->get_schema($id);
}

function zm_sh_get_schemas(){
	$zm_sh_schema = zm_sh_schema::getInstance();
 	return $zm_sh_schema->get_schemas();
}

function zm_sh_add_schema($schema){
	$zm_sh_schema = zm_sh_schema::getInstance();
	return $zm_sh_schema->add_schema($schema);
}

function zm_sh_remove_schema($id){
	$zm_sh_schema = zm_sh_schema::getInstance();
	return $zm_sh_schema->remove_schema($id);
}
