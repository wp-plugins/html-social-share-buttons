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
		add_action( 'admin_enqueue_scripts', array($this,'admin_scripts'),20 );
	}
	
	//registering menu item and page on admin
	function reg_admn_menu(){
		add_menu_page(__("Html Social Share", "zm-sh"), __("Html Social Share", "zm-sh"),"administrator", "zm_shbt_opt",array($this,"zm_sh_opt"),"","59.679861");
	}
	
	//registering scripts and styles for admin
	function admin_scripts($hook) {
		if ( 'toplevel_page_zm_shbt_opt' == $hook ) {
			wp_enqueue_style( 'zm_sh_admin_styles_scripts', plugin_dir_url( __FILE__ ) . 'assets/admin.css' );
		}
		elseif ( 'widgets.php' == $hook ) {
			wp_enqueue_style( 'zm_sh_admin_styles_scripts', plugin_dir_url( __FILE__ ) . 'assets/admin-widget.css' );
		}
	}
	
	function add_new(){
		
		
	}
	
	function show_all(){
		
		
	}
	//option page content
	function zm_sh_opt(){
		$zm_form = new zm_form;
		$options = $this->options;
		?>
        <div class="wrap">
            <h2 class="zm_options_page_heading"><?php _e("Html Social Share button", "zm-sh");?></h2>
            <form class="zm_settings" method="post" action="options.php"> 
            <?php settings_fields( 'zm_shbt_opt' ); ?>
            <h3>Select theme and Icon Style</h3>
            <?php $zm_form->text("title", "Enter a Title");?>
 
            <?php $zm_form->select_iconset("iconset", "Select Button Style");?>
            <?php $zm_form->show_on("show_left", "Show on Left Side");?>
            <?php $zm_form->show_on("show_right", "Show on Right Side");?>
            <?php $zm_form->show_on("show_before_post", "Show Before Post");?>
            <?php $zm_form->show_on("show_after_post", "Show After Post");?>
            
            <?php $zm_form->icon_fields("Select Buttons", "Enable");?>
            
            <?php submit_button(); ?>
            <a href="#TB_inline?width=600&height=600&inlineId=zm-sh-thick-box" class="get_phpcode thickbox  button button-default" title="<?php _e("<\?> Get PHP Code", "zm-sh");?>"><?php _e("<\?> Get PHP Code", "zm-sh");?></a>
            <a href="#TB_inline?width=600&height=600&inlineId=zm-sh-thick-box" class="get_shortcode thickbox button button-default" title="<?php _e("[] Get Shortcode", "zm-sh");?>"><?php _e("[] Get Shortcode", "zm-sh");?></a>
            <script>

			
			jQuery(document).ready(function($) {
                $('.get_shortcode,[name=shortcode-iconset-type]').on('click',function(e){
					$iconset		= $("#iconset").val();
					$iconset_type	= $("[name=shortcode-iconset-type]:checked").val();
					$shortcode = "[zm_sh_btn ";
					$shortcode += "iconset='" + $iconset + "' ";
					$shortcode += "iconset_type='" + $iconset_type + "' ";
					$shortcode += "icons='";
					//e.preventDefault();
					$icons = [];
					$( ".zm_settings input[name^='zm_shbt_fld[icons]']:checked").each(function(index, element) {
                        $icons.push( $(element).data("id"));
                    });
					$shortcode += $icons.join();
					$shortcode += "']";
					$("#copy_shortcode").html($shortcode);
					$("#copy_shortcode").select();
					//console.log($shortcode);
				});
                $('.get_phpcode,[name=shortcode-iconset-type]').on('click',function(e){
					$iconset = $("#iconset").val();
					$iconset_type	= $("[name=shortcode-iconset-type]:checked").val();
					$phpcode = "\<\?php\n if(function_exists('zm_sh_opt')){\n\t";
					$phpcode += "$options['iconset']		= '" + $iconset + "';\n\t";
					$phpcode += "$options['iconset_type']	= '" + $iconset_type + "';\n\t";
					$phpcode += "$options['class']			= 'in_php_function';\n\t";
					$phpcode += "$options['icons']			= array( '";
					$icons = [];
					$( ".zm_settings input[name^='zm_shbt_fld[icons]']:checked").each(function(index, element) {
                        $icons.push( $(element).data("id"));
                    });
					$phpcode += $icons.join("', '");
					$phpcode += "' );\n";
					$phpcode += "\techo zm_sh_btn($options);";
					$phpcode += "\n}\n?>";
					$("#copy_shortcode").html($phpcode);
					$("#copy_shortcode").select();
					//console.log($shortcode);
				});
            });
			
			</script>
            <p class="desin_by">
            	Designed By Hakan Ertan <a target="_blank" href="https://hakan-ertan.com/">www.hakan-ertan.com</a>
            </p>
			</form>
            <?php add_thickbox(); ?>
            <div id="zm-sh-thick-box" style="display:none;">
            	<div class="tabs">
                	<div id="tab-1" class="tab">
                    <?php
						$iconset = zm_sh_get_iconset($options['iconset']);
                    	echo "<div class='row show_on' style='margin-top:20px;margin-bottom:50px'>";
                            foreach($iconset['types'] as $type){
								$selected = "";
								if($i==0)
									$selected = "checked='checked'";
                                echo "<input type='radio' id='shortcode-$type' name='shortcode-iconset-type' value='$type' $selected>";
                                echo "<label for='shortcode-$type'><img src='". zm_sh_url_assets_img . "show_after_post-$type.png'></label>";
								$i++;
                            }
                        echo "</div>";
					?>
                    </div>
                	<div id="tab-2" class="tab"> 
						<textarea id="copy_shortcode" style="width:100%;height:200px"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <pre>
        <?php
		//print_r($this->options);
		//print_r(zm_sh_get_iconset($this->options['iconset']));
		?>
        </pre>
        <?php
	}
	
	function zm_reg_sett(){
		register_setting( 'zm_shbt_opt', 'zm_shbt_fld',array($this,"sanitize"));
	}
	
	function sanitize( $input ){
        $new_input = array(); //get_option("zm_shbt_fld", $zm_sh_default_options);
		$keep_as_is = array( "title", "iconset", "icons", "show_in", "show_left", "show_right", "show_before_post", "show_after_post", );
		
		foreach($input as $key =>$value){
			if( $key == "show_in")
				foreach($value as $key_1=>$value_1)
					$new_input["show_in"][$key_1] = $input[$key_1];
			elseif( in_array($key, $keep_as_is))
				$new_input[$key] = $value;
			elseif(isset( $input[$key] ) and $input[$key] )
				$new_input[$key] = true;
			
		}
        return $new_input;
    }

}