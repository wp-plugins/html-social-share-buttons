<?php
class zm_form{
	
	public $options;
	
	function __construct($options){
		$this->options		= $options;
	}
	
	
	function toogle_check($id, $name = "", $checked = ""){
		if(empty($name))
			$name = "zm_shbt_fld[$id]";
		if(empty($checked)){
			$checked = $this->options[$id];
			$checked = $selected ? "selected " : "";
		}
		echo "
		<div class='toggle-check'>
			<input name='$name' id='$id' $checked  type='checkbox'/>
			<label for='$id' data-on='".__("Yes", "zm_sh")."' data-off='".__("No", "zm_sh")."'></label>
		</div>
		";
	}
	
	function left_right_field($id){
		$id=$id['label_for'];
		$chk="";
		if(isset($this->options["show_on"]) and $this->options["show_on"] == $id)
			$chk="checked";
		echo "
		<div class='toggle-check'>
			<input name='zm_shbt_fld[show_on]' id='$id' $chk type='radio' value='$id'/>
			<label for='$id' data-on='".__("Yes", "zm_sh")."' data-off='".__("No", "zm_sh")."'></label>
		</div>
		";
	}
	
	function zm_sett_field($id){
		$id=$id['label_for'];
		$chk="";
		if(isset($this->options["$id"]))
			if($this->options["$id"])
				$chk="checked";
		$this->toogle_check($id);
	}
	
	
	function icon_fields($id){
		global $zm_sh_in_widget;
		$id=$id['label_for'];
		$chk="";
		$icons = $this->options["icons"];
		if($zm_sh_in_widget){
			$obj = $zm_sh_in_widget['obj'];
			$intstance = $zm_sh_in_widget['intstance'];
			$icons = $intstance['icons'];
			$uid = $obj->get_field_id($id);
			$name = $obj->get_field_name('icons');
			
			if(isset($icons[$id]) and $icons[$id])
				$chk = "checked";
			$this->toogle_check($uid, $name . "[$id]", $chk);
		}
		else{
			if(isset($icons[$id]) and $icons[$id])
				$chk = "checked";
			$name = "zm_shbt_fld[icons][$id]";
			$this->toogle_check($id, $name, $chk);
		}
	}
	
	function zm_sett_field_radio($id){
		$id=$id['label_for'];
		$chk=" checked";
		$val = 0;
		if(isset($this->options["$id"]))
			$val = $this->options["$id"];
		?>
          
            <input type="radio" name="zm_shbt_fld[show_on]" value="0" <?php if(!$val) echo $chk;?> id="show_on_0" />
            <label for="show_on_0"> <?php _e("Left", "zm_sh");?></label>
         
            <input type="radio" name="zm_shbt_fld[show_on]" value="1" <?php if($val) echo $chk;?> id="show_on_1" />
            <label for="show_on_1"><?php _e("Right", "zm_sh");?></label>
		
		<?php
	}
	
	function fld_dropdown($id){
		global $zm_sh_in_widget;
		$id=$id['label_for'];
		if($zm_sh_in_widget){
			$obj = $zm_sh_in_widget['obj'];
			$instance = $zm_sh_in_widget['intstance'];
			$curr_iconset = $instance[$id];
			if($curr_iconset == "")
				$curr_iconset = 'default';
			
			$uid = $obj->get_field_id($id);
			$name = $obj->get_field_name($id);
			
			echo "<select id='".$uid."' name='$name'>";
			
			$iconsets = zm_sh_get_iconsets();
			$iconset_default = $iconsets['default'];
			unset( $iconsets['default']);
			$iconsets = array_merge( array('default' => $iconset_default), $iconsets);
			
			foreach($iconsets as $iconset){
				$selected = "";
				if($iconset['id'] == $curr_iconset)
					$selected = "selected";
				echo "<option value='{$iconset['id']}' $selected>{$iconset['name']}</option>";
			}
			echo "</select>";
		}
		else{
			echo "<select id='".$id."' name='zm_shbt_fld[".$id."]'>";
			foreach(zm_sh_get_iconsets() as $iconset){
				$selected = "";
				if($iconset['id'] == $this->options[$id])
					$selected = "selected";
				echo "<option value='{$iconset['id']}' $selected>{$iconset['name']}</option>";
			}
			echo "</select>";
		}
	}
	
	
}