<?php
new zm_sh_settings;
class zm_sh_settings{
	private $options;
	function __construct(){
		global $zm_sh_default_options;
		$this->options = get_option("zm_shbt_fld", $zm_sh_default_options);
		//adding menu item and page on admin
		add_action('admin_menu', array($this,'reg_admn_menu'));
		//registering settings/options for admin page
		add_action('admin_init', array($this,'zm_reg_sett'));
		//registering scripts and styles for admin
		add_action( 'admin_enqueue_scripts', array($this,'admin_scripts') );
	}
	
	//registering menu item and page on admin
	function reg_admn_menu(){
		add_menu_page("Html Social Share","Html Social Share","administrator", "zm_shbt_opt",array($this,"zm_sh_opt"),"","59.679861");
		//add_submenu_page("zm_shbt_opt", "Add new shocial buttons", "Add new", "administrator", "add_new", array($this,"add_new"));
		//add_submenu_page("zm_shbt_opt", "All shocial buttons", "All shocial buttons", "administrator", "all", array($this,"show_all"));
	}
	
	//registering scripts and styles for admin
	function admin_scripts($hook) {
		if ( 'toplevel_page_zm_shbt_opt' == $hook ) {
			wp_enqueue_style( 'zm_sh_admin_styles_scripts', plugin_dir_url( __FILE__ ) . 'admin.css' );
		}
		elseif ( 'widgets.php' == $hook ) {
			wp_enqueue_style( 'zm_sh_admin_styles_scripts', plugin_dir_url( __FILE__ ) . 'admin-widget.css' );
		}
	}
	
	function add_new(){
		
		
	}
	
	function show_all(){
		
		
	}
	//option page content
	function zm_sh_opt(){
		?>
        <div class="wrap">
            <h2>Html Social Share button</h2>
            <form class="zm_settings" method="post" action="options.php"> 
            <?php
			settings_fields( 'zm_shbt_opt' );
			do_settings_sections("zm_shbt_floting_opt");
			do_settings_sections("zm_shbt_opt");
			?>
            
            <?php submit_button(); ?>
            <a href="#TB_inline?width=600&height=150&inlineId=zm-sh-thick-box" class="get_shortcode thickbox button button-default"><?php _e("Get Shortcode", "zm_sh");?></a>
            <a href="#TB_inline?width=600&height=250&inlineId=zm-sh-thick-box" class="get_phpcode thickbox button button-default"><?php _e("Get Shortcode", "zm_sh");?></a>
            <script>

			
			jQuery(document).ready(function($) {
                $('.get_shortcode').on('click',function(e){
					$iconset = $("#iconset").val();
					$shortcode = "[zm_sh_btn class='widget' iconset='" + $iconset + "' icons='";
					e.preventDefault();
					$icons = [];
					$( ".zm_settings input[name^='zm_shbt_fld[icons]']:checked").each(function(index, element) {
                        $icons.push( $(element).attr("id"));
                    });
					$shortcode += $icons.join();
					$shortcode += "']";
					$("#copy_shortcode").html($shortcode);
					$("#copy_shortcode").select();
					//console.log($shortcode);
				});
                $('.get_phpcode').on('click',function(e){
					e.preventDefault();
					$iconset = $("#iconset").val();
					$phpcode = "\<\?php\n if(function_exists('zm_sh_opt')){\n\t$options['iconset'] = '" + $iconset + "';\n\t$options['icons'] = array(\n\t\t'";
					$icons = [];
					$( ".zm_settings input[name^='zm_shbt_fld[icons]']:checked").each(function(index, element) {
                        $icons.push( $(element).attr("id"));
                    });
					$phpcode += $icons.join("',\n\t\t'");
					$phpcode += "'\n\t);\n";
					$phpcode += "\techo zm_sh_btn($options);";
					$phpcode += "\n}\n?>";
					$("#copy_shortcode").html($phpcode);
					$("#copy_shortcode").select();
					//console.log($shortcode);
				});
            });
			
			</script>
			</form>
            <?php add_thickbox(); ?>
            <div id="zm-sh-thick-box" style="display:none;">
                 <p>
                      <textarea id="copy_shortcode" style="width:100%;height:200px"></textarea>
                 </p>
            </div>
        </div>
        <?php
	}
	
	
	function zm_reg_sett(){
		register_setting( 'zm_shbt_opt', 'zm_shbt_fld',array($this,"sanitize"));
		
		add_settings_section( "zm_shbt_floting", "", array($this,"zm_floating_sec_cb"), "zm_shbt_floting_opt" );
		add_settings_section( "zm_shbt_sett", "", array($this,"zm_sec_cb"), "zm_shbt_opt" );
		
		
		
		add_settings_field( "iconset", __("Select button set", "zm_sh"), array($this,"fld_dropdown"),"zm_shbt_opt", "zm_shbt_sett", array('label_for' => 'iconset' ) );
		
		add_settings_field( "enable_floating", __("Enable Floating bars", "zm_sh"), array($this,"zm_sett_field"),"zm_shbt_floting_opt", "zm_shbt_floting", array( 'label_for' => 'enable_floating' ) );		
		add_settings_field( "show_left", __("Show on left side", "zm_sh"), array($this,"left_right_field"),"zm_shbt_floting_opt", "zm_shbt_floting", array( 'label_for' => 'left_side' ) );
		add_settings_field( "show_right", __("Show on right side", "zm_sh"), array($this,"left_right_field"),"zm_shbt_floting_opt", "zm_shbt_floting", array( 'label_for' => 'right_side' ) );
		
		$iconset = zm_sh_get_current_iconset();
		$icons =  $iconset['icons'];
		if(is_array($icons))
		foreach($icons as $id=>$icon){
			extract($icon);
			add_settings_field( $id,  __("Enable ", "zm_sh")  . $name, array($this,"icon_fields"), "zm_shbt_opt", "zm_shbt_sett",  array( 'label_for' => "$id" ) );
		}
		
	}
	
	function sanitize( $input ){
        $new_input = array();
		foreach($input as $key =>$value){
			if( $key == "iconset" )
				$new_input[$key] = $value;
			elseif( $key == "icons" )
				$new_input[$key] = $value;
			elseif( $key == "show_on" )
				$new_input[$key] = $value;
			elseif(isset( $input[$key] ) and $input[$key] )
				$new_input[$key] = true;
			
		}
        return $new_input;
    }
	
	function zm_floating_sec_cb(){
		_e( "<h3>Set floating share buttons</h3>", "zm_sh");
	}
	function zm_sec_cb(){
		_e( "<h3>Select theme and enable or disable buttons</h3>", "zm_sh");
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
		echo "
		<div class='toggle-check'>
			<input name='zm_shbt_fld[".$id."]' id='".$id."' ".$chk." type='checkbox'/>
			<label for='".$id."' data-on='".__("Yes", "zm_sh")."' data-off='".__("No", "zm_sh")."'></label>
		</div>
		";
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
				$chk="checked";
			echo "
			<div class='toggle-check'>
				<input name='$name"."[$id]' id='$uid' $chk type='checkbox'/>
				<label for='$uid' data-on='Yes' data-off='No'></label>
			</div>
			";
		}
		else{
			if(isset($icons[$id]) and $icons[$id])
				$chk="checked";
			echo "
			<div class='toggle-check'>
				<input name='zm_shbt_fld[icons][".$id."]' id='".$id."' ".$chk." type='checkbox'/>
				<label for='".$id."' data-on='Yes' data-off='No'></label>
			</div>
			";
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