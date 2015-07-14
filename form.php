<?php
class zm_form{
	
	public $options;
	
	function __construct($options = ""){
		global $zm_sh, $zm_sh_default_options;
		$this->zm_sh	= &$zm_sh;
		$this->iconsets	= &$zm_sh->iconsets;
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
	
	function textArea($id, $label, $name = false, $value = false){
		$name	= $name		? $name		: "zm_shbt_fld[$id]";
		$value	= $value	? $value	: $this->options[$id];
		echo "<div class='row'>";
			echo "<label for='$id' style='width:140px;line-height: 37px;'>$label</label>";
			echo "<textarea type='text' id='$id' name='$name' style='width: 278px;background-color: #ffffff;border: 1.2px solid #B8B8B8;' placeholder='Exclude by Page ID, Page Title or Page Slug' >$value</textarea>";
		echo "</div>";
	}
	
	
	function checkbox($id, $label, $name = '', $selected=null, $class = '', $yes = "", $no = "", $id_prefix="",$description=''){
		$yes = $yes?$yes:__("Yes", "zm_sh");
		$no = $no?$no:__("No", "zm_sh");
		$class	= $class?$class:'toggle-check';
		$saved_val = isset($this->options[$id])?$this->options[$id]:false;
		$chk = $selected===null?checked($saved_val, true, false):$selected;
		$name = $name ? $name : "zm_shbt_fld[$id]";
		echo "<div class='row'>";
			echo "<label for='$id_prefix$id' title='$description'>$label</label>";
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
		$iconset = $this->iconsets->get_iconset($options['iconset']);
		$yes	= $yes?$yes:__("Yes", "zm_sh");
		$no		= $no?$no:__("No", "zm_sh");
		$name	= "zm_shbt_fld[show_in][$id]";
		$name_1	= "zm_shbt_fld[$id]";
		if(isset($options['show_in'][$id]) and $options['show_in'][$id] )
			$chk	= "checked='checked'";
		else
			$chk	= '';
		//if($chk and !$circle and !$square)
			//$circle = 'checked="checked"';
		echo "<div class='row toggle'>";
			echo "<label for='$id'>$label</label>";
			echo "<input id='$id' $chk type='checkbox' name='{$name}' value='1'/>";
			echo "<span class='for_label'>";
				echo "<label for='$id' class='$class' data-on='$yes' data-off='$no'></label>";
				
				echo "<div class='row show_on' style='margin-top:50px'>";
					foreach($iconset->types as $type){
						if(isset($options[$id]) and $options[$id])
							$selected = checked($options[$id], $type, false);
						else
							$selected = '';
						echo "<input type='radio' id='$id-$type' name='$name_1' value='$type' $selected >";
						echo "<label for='$id-$type'><img src='". zm_sh_url_assets_img . $id . "-$type.png'></label>";
					}
				echo "</div>";
			echo "</span>";
			//print_r($options);
		echo "</div>";
	}
	
	function icon_fields($label, $label_prefix, $class = 'toggle-check', $yes = "", $no = ""){
		$icons = $this->iconsets->get_iconset($this->options['iconset'])->get_icons();
		echo "<div class='row' style='margin-bottom:20px'>";
			echo "<h2>$label</h2>";
		echo "</div>";
		foreach($icons as $icon){
			$id		= $icon['id'];
			$name	= $icon['name'];
			$c		= isset($this->options['icons'][$id])?$this->options['icons'][$id]:false;
			$chk	= checked($c, true, false);
			$this->checkbox($id, $label_prefix.' '.$name, "zm_shbt_fld[icons][$id]", $chk, $class, $yes, $no, 'icon_');
		}
	}
	
	function icon_fields_widget($id, $name, $selected_icons, $label, $label_prefix, $iconset){
		$icons = $this->iconsets->get_iconset($iconset)->get_icons();
		//print_r(func_get_args());
		echo "<div class='row' style='margin-bottom:20px'>";
			echo "<h2>$label</h2>";
		echo "</div>";
		$selected_icons = $selected_icons?$selected_icons:array();
		foreach($icons as $icon){
			$_id	= $icon['id'];
			$_name	= $icon['name'];
			//echo $_id. '||' .$selected_icons[$_id]."\n";
			if(isset($selected_icons[$_id]))
				$chk	= checked($selected_icons[$_id], true, false);
			else
				$chk	= false;
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
	
	function _select_iconset($id, $label, $items, $name=false, $selected='default'){
		$name = $name ? $name : "zm_shbt_fld[$id]";
		$selected = $selected ? $selected : $this->options[$id];
		//$iconset = $this->iconsets->get_iconset($selected);
		echo "<div class='row'>";
			echo "<label for='$id'>$label</label>";
			echo "<select id='$id' name='$name'>";
			foreach($items as $i_id=>$i_name){
				$selec = selected($selected, $i_id, false);
				echo "<option value='{$i_id}' $selec>{$i_name}</option>";
			}
			echo "</select>";
			//print_r($this->zm_sh->iconsets->get_iconset($selected));
			?>
			<div class="button-style-img">
				<img src="<?php echo $this->iconsets->get_iconset($selected)->get_iconset_preview(); ?>" alt="" class="" />
			</div>
			<?php
		echo "</div>";
	}
	
	function select_iconset($id, $label, $name=false, $selected=false ){
		$iconsets = $this->iconsets->get_iconset_list();
		$this->_select_iconset($id, $label, $iconsets, $name, $selected );
	}
	
	
	
}