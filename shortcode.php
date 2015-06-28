<?php
	
	
	
	add_shortcode("zm_sh_btn", 'zm_sh_shortcode_cb');
	
	function zm_sh_shortcode_cb($atts){
		global $zm_sh;
		if(isset($zm_sh->excluded) and $zm_sh->excluded == true) return;
		$atts = shortcode_atts(array(
									'title'			=> '',
									'iconset'		=> "default",
									'icons'			=> array(
															"facebook"		=> "on",
															"twitter"		=> "on",
															"linkedin"		=> "on",
															"googlepluse"	=> "on",
															"bookmark"		=> "on",
															"pinterest"		=> "on",
															"mail"			=> "on",
															),
									'iconset_type'	=> "square",
									'class'			=> "in_shortcode",
								),
								$atts,
								'zm_sh_btn'
							);
		$icons = $atts['icons'];
		$icons = explode(",", $icons);
		$atts['icons'] = array_flip($icons);
		return $zm_sh->zm_sh_btn($atts);
	}