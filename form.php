<?php
class zm_form{
	
	public $options;
	
	function __construct($options = ""){
		global $zm_sh_default_options;
		$this->options = $options ? $options : get_option("zm_shbt_fld", $zm_sh_default_options);
	}
	
	function text($id, $label, $name = false, $value = false){
		$name	= $name		? $name		: "zm_shbt_fld[$id]";
		$value	= $value	? $value	: $this->options[$id];
		echo "<div class='row'>";
			echo "<label for='$id' style='width:140px;line-height: 37px;'>$label</label>";
			echo "<input type='text' id='$id' name='$name' value='$value' style='width: 278px;height: 33px;	background-color: #ffffff;border: 1.2px solid #B8B8B8;' >";
		echo "</div>";
	}
	
	
	function checkbox($id, $label, $name = '', $selected=null, $class = '', $yes = "", $no = "",$description='', $id_prefix = ''){
		$yes = $yes?$yes:__("Yes", "zm_sh");
		$no = $no?$no:__("No", "zm_sh");
		$class	= $class?$class:'toggle-check';
		$chk = $selected===null?checked($this->options[$id], true, false):$selected;
		$name = $name ? $name : "zm_shbt_fld[$id]";
		echo "<div class='row'>";
			echo "<label for='$id_prefix$id'>$label</label>";
			echo "<input name='$name' id='$id_prefix$id' $chk type='checkbox' value='1' data-id='$id' />";
			echo "<span class='for_label'>";
				echo "<label for='$id_prefix$id' class='$class' data-on='$yes' data-off='$no'></label>";
			echo "</span>";
		echo "</div>";
			if($description)
			echo "<p>$description</p>";
	}
	
	function show_on($id, $label, $selected=false, $class = 'toggle-check', $yes = "", $no = ""){
		$options = $this->options;
		$iconset = zm_sh_get_iconset($options['iconset']);
		$yes	= $yes?$yes:__("Yes", "zm_sh");
		$no		= $no?$no:__("No", "zm_sh");
		$name	= "zm_shbt_fld[show_in][$id]";
		$name_1	= "zm_shbt_fld[$id]";
		$chk	= $options['show_in'][$id]?'checked="checked"':'';
		if($chk and !$circle and !$square)
			$circle = 'checked="checked"';
		echo "<div class='row toggle'>";
			echo "<label for='$id'>$label</label>";
			echo "<input id='$id' $chk type='checkbox' name='{$name}' value='1'/>";
			echo "<span class='for_label'>";
				echo "<label for='$id' class='$class' data-on='$yes' data-off='$no'></label>";
				
				echo "<div class='row show_on' style='margin-top:50px'>";
					foreach($iconset['types'] as $type){
						$selected = checked($options[$id], $type, false);
						echo "<input type='radio' id='$id-$type' name='$name_1' value='$type' $selected >";
						echo "<label for='$id-$type'><img src='". zm_sh_url_assets_img . $id . "-$type.png'></label>";
					}
				echo "</div>";
			echo "</span>";
			//print_r($options);
		echo "</div>";
	}
	
	function icon_fields($label, $label_prefix, $class = 'toggle-check', $yes = "", $no = ""){
		$icons = zm_sh_get_icons($this->options['iconset']);
		echo "<div class='row' style='margin-bottom:20px'>";
			echo "<h2>$label</h2>";
		echo "</div>";
		foreach($icons as $name=>$id){
			$chk = checked($this->options['icons'][$id], true, false);
			$this->checkbox($id, $label_prefix.' '.$name, "zm_shbt_fld[icons][$id]", $chk, $class, $yes, $no, '', 'icon_');
		}
	}
	
	function icon_fields_widget($id, $name, $selected_icons, $label, $label_prefix){
		$icons = zm_sh_get_icons($this->options['iconset']);
		echo "<div class='row' style='margin-bottom:20px'>";
			echo "<h2>$label</h2>";
		echo "</div>";
		$selected_icons = $selected_icons?array_keys($selected_icons):array();
		foreach($icons as $_name=>$_id){
			if(is_array($selected_icons)){
				$chk = in_array($_id, $selected_icons)?"checked='checked'":"";
			}
			$this->checkbox($id.'_'.$_id, $label_prefix.' '.$_name, $name . "[$_id]", $chk);
		}
	}
	
	function dropdown($id, $label, $items, $name=false, $selected=false){
		echo "<div class='row'>";
			echo "<label for='$id'>$label</label>";
			echo "<select id='$id' name='$name'>";
			foreach($items as $item){
				$selec = selected($selected, $item, false);
				echo "<option value='$item' $selec>".ucwords($item)."</option>";
			}
			echo "</select>";
		echo "</div>";
	}
	
	function _select_iconset($id, $label, $items, $name=false, $selected=false){
		$name = $name ? $name : "zm_shbt_fld[$id]";
		$selected = $selected ? $selected : $this->options[$id];
		//$iconset = zm_sh_get_iconset($selected);
		echo "<div class='row'>";
			echo "<label for='$id'>$label</label>";
			echo "<select id='$id' name='$name'>";
			foreach($items as $i_id=>$i_name){
				$selec = selected($selected, $i_id, false);
				echo "<option value='{$i_id}' $selec>{$i_name}</option>";
			}
			echo "</select>";
			?>
			<div class="button-style-img">
				<img src="<?php echo zm_get_iconset_preview($selected); ?>" alt="" class="" />
			</div>
			<?php
		echo "</div>";
	}
	
	function select_iconset($id, $label, $name=false, $selected=false ){
		$iconsets = zm_sh_get_iconset_list();
		$this->_select_iconset($id, $label, $iconsets, $name, $selected );
	}
	
	
	
}