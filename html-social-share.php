<?php
/*
Plugin Name: Html Social share buttons
Plugin URI: http://wordpress.org/plugins/html-social-share-buttons/
Description: Html share button. It show lite share button only with html. It's not using any javascript whats anothers do. It's load only extra 10-11 kb total on your site.
Author: Alimuzzaman Alim
Version: 2.0.6.2
Author URI: http://www.zm-tech.net
Text Domain: zm-sh
Domain Path: /languages
*/

// Iconset dir where to search for iconsets.
define("zm_sh_dir", plugin_dir_path(__FILE__));
define("zm_sh_url_iconset", zm_sh_dir . "iconset");

define("zm_sh_url", plugin_dir_url(__FILE__));
define("zm_sh_url_iconset", zm_sh_url . "iconset/");
define("zm_sh_url_assets", zm_sh_url . "assets/");
define("zm_sh_url_assets_img", zm_sh_url_assets . "image/");

$dir_iconset = plugin_dir_path(__FILE__) . "iconset";

$zm_sh_default_options = array(
						"title"				=> "Share this with your friends",
						"iconset"			=> "default",
						
						"show_left"			=> true,
						"show_right"		=> false,
						"show_before_post"	=> false,
						"show_after_post"	=> true,
						
						"icons" => array(
							"facebook"		=> "on",
							"twitter"		=> "on",
							"linkedin"		=> "on",
							"googlepluse"	=> "on",
							"bookmark"		=> "on",
							"pinterest"		=> "on",
							"mail"			=> "on",
							)
						
					);
// Searching for iconsets
// This will search for ssb.php if found then includ that.
$dir = scandir($dir_iconset);
foreach ($dir as $subdir) {
	if ($subdir === '.' or $subdir === '..') continue;
	$iconset_file = $dir_iconset . '/' . $subdir . "/ssb.php";
	if (file_exists($iconset_file)) {
		include $iconset_file;
	}
}

//include iconsets.php
//it's contains all function to add, remove, get iconsets
include("iconsets.php");

//include filters.php
//it's contain filters
include("filters.php");

//include widget.php
//it's register widget
include("widget.php");

require_once("form.php");

include("settings_page.php");

// make variable globaly accessable
global $zm_sh;

//new instance of class zm_social_share
$zm_sh = zm_social_share::getInstance();
function zm_sh_btn($options){
	$zm_sh = zm_social_share::getInstance();
	$zm_sh->zm_sh_btn($options);
}

class zm_social_share{
	
	public $options;
	private $schemas;
	public $iconset;
	private $icons;
	private $printed_icons;
	private $stylesheets;
	
	public static function getInstance() {
		static $instance;
		if ($instance === null){
			$instance = new self;
			do_action( "zm_sh_add_iconset");
		}
		return $instance;
	}
	
	protected function __construct(){
		global $zm_sh_default_options;
		// getting options form database
		$this->options	= get_option("zm_shbt_fld", $zm_sh_default_options);
		// getting the current iconset
		$this->iconset = zm_sh_get_current_iconset();
		
		//print styles and floating buttons 
		add_action('wp_print_footer_scripts',  array($this,'footer'));
		//register stylesheets from theme
		//add_action( 'wp_enqueue_scripts', array($this,'register_styles') );
		
		//registering widget
		add_action( 'widgets_init', array($this,'register_widgets') );
		
		add_shortcode("zm_sh_btn", array($this, 'shortcode_cb'));
		
		add_action('plugins_loaded', array($this, 'ap_action_init'));
        add_action( 'vc_before_init', array( $this, 'integrateWithVC' ) );
		add_action('wp', array($this, 'wp'));

	}
	
	function wp(){
		global $post;
		echo $post->ID;
		//print_r($post);
		$excludes		= $this->options['excludes'];
		$excludes		= (array) explode(',', $excludes);
		//print_r($excludes);
		if(in_array($post->ID, $excludes))
			$this->excluded	= true;
	}
	
	public function integrateWithVC() {
		if($this->excluded == true) return;
 		$iconsets = zm_sh_get_iconset_list();
		$iconsets = array_flip($iconsets);
		
		$iconset	= zm_sh_get_iconset();
		$icons		= zm_sh_get_icons();
		//print_r($iconsets);
        /*
        Add your Visual Composer logic here.
        Lets call vc_map function to "register" our custom shortcode within Visual Composer interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
						"name" => __("Html Social Share", 'vc_extend'),
						"description" => __("Html Social Share", 'vc_extend'),
						"base" => "zm_sh_btn",
						"class" => "zm_sh_btn",
						"controls" => "full",
						"category" => __('Content', 'js_composer'),
						'admin_enqueue_js' => array( plugins_url( '/assets', __FILE__) .'/vc_scripts.js'),
						'admin_enqueue_css' => array(plugins_url( '/assets', __FILE__) .'/admin-widget.css'),
						"params" => array(
							array(
							  "type"		=> "textfield",
							  "holder"		=> "div",
							  "class"		=> "",
							  "heading"		=> __("Title", 'zm-sh'),
							  "param_name"	=> "title",
							  "value"		=> __("Share this page", 'zm-sh'),
							  "description"	=> __("Add social share button", 'zm-sh')
						  ),
							array(
							  "type"		=> "dropdown",
							  "holder"		=> "div",
							  "class"		=> "",
							  "heading"		=> __("Iconset", 'zm-sh'),
							  "param_name"	=> "iconset",
							  "value"		=> $iconsets,
							  "description"	=> __("Select iconset to use", 'zm-sh'),
						  ),
							array(
							  "type"		=> "checkbox",
							  "holder"		=> "div",
							  "class"		=> "",
							  "heading"		=> __("Icons", 'zm-sh'),
							  "param_name"	=> "icons",
							  "value"		=> $icons,
							  "description"	=> __("Select icons", 'zm-sh'),
							  //"dependency"	=> array("element"=>"iconset", "callback" => "zm_sh_get_icons"),

						  ),
					)
        ) );
    }
    
	function ap_action_init(){
		if($this->excluded == true) return;
		// Localization
		load_plugin_textdomain('zm-sh', false, dirname(plugin_basename(__FILE__)) . '/languages' );
		
		if($this->options['show_after_post'] or $this->options['show_before_post'])
			add_filter( 'the_content', array($this, 'filter_the_content') );
		
			
		
	}
	
	function filter_the_content($content){
		$options = $this->options;
		$options['class'] = "in_widget";
		if( is_singular() and is_main_query() && $options['show_in']['show_before_post']) {
			$options['show_on'] = 'show_before_post';
			$content = $this->zm_sh_btn($options).$content;
		}
		if( is_singular() and is_main_query() && $options['show_in']['show_after_post']) {
			$options['show_on'] = 'show_after_post';
			$content = $content . $this->zm_sh_btn($options);
		}
		return $content;
	}
	
	function shortcode_cb($atts){
		if($this->excluded == true) return;
		$atts = shortcode_atts(array(
									'iconset'	=> "",
									'icons'		=> "",
									'iconset_type'		=> "",
									'class'		=> "in_widget",
								),
								$atts,
								'zm_sh_btn'
							);
		$icons = $atts['icons'];
		$icons = explode(",", $icons);
		$atts['icons'] = array_flip($icons);
		return $this->zm_sh_btn($atts);
	}
	
	//registering widget
	function register_widgets() {
		if($this->excluded == true) return;
		register_widget( 'zm_html_share_widget' );
	}
	
	//print styles and floating buttons 
	function footer(){
		if($this->excluded == true) return;
		$options = $this->options;
		
		if($options['g_analytics']){
			echo "
				<script>
				jQuery(document).ready(function($){
					var _gaq = _gaq || [];
					jQuery('.zmshbt a').click(function(event){
						var _gaq = _gaq || [];
						switch(this.className){
							case 'googlepluse':
								action = '+1';
							case 'twitter':
								action = 'Tweet';
							case 'mail':
								action = 'Mail';
							default :
								action = 'Share';
						}
						_gaq.push(['_trackSocial', this.className, action]);
						conlole.log(action);
					});
				}); 
				</script>
			";
		}		
		if($options['show_in']['show_left']){
			$options['class'] = 'left';
			$options['show_on'] = 'show_left';
			echo $this->zm_sh_btn($options);
		}
		if($options['show_in']['show_right']){
			$options['class'] = 'right';
			$options['show_on'] = 'show_right';
			echo $this->zm_sh_btn($options);
		}
		
		$this->register_styles();
		$this->icon_styles();
	}
	//register stylesheets from theme
	function register_styles(){
		if(is_array($this->stylesheets)){
			foreach($this->stylesheets as $id=>$stylesheet){
				echo "<link rel='stylesheet' id='social-share-$id'  href='$stylesheet' type='text/css' media='all' />\n";
			}
		}
		else
			wp_enqueue_style("social-share-default", plugins_url( 'iconset/default/', __FILE__) . 'style.css');
	}
	
	//print styles for each icons in footer
	function icon_styles() {
		echo "<style>";
		//print_r($this->printed_icons);
		foreach($this->printed_icons as $id=>$iconset){
			extract($iconset);
			echo "
			.zmshbt.$iconset_id.$iconset_type .$class {
					background-image:url('$iconset_url$iconset_type/$image');
			}
			";
		}
		echo "</style>";
	}
	
	//the button generator function
	function zm_sh_btn($instance = ""){
		if($this->excluded == true) return;
		$options = $instance?$instance:$this->options;
		
		$__class = $options['class']?$options['class']:"left";
		$iconset_id = $options['iconset'];
		$selected_icons = $options['icons'];
		
		$iconset = zm_sh_get_iconset($iconset_id);
		$this->stylesheets[$iconset['id']] = $iconset['url'] . $iconset['stylesheet'];
		$icons = $iconset['icons'];
		$iconset_type = $options['iconset_type']?$options['iconset_type']:$options[$options['show_on']];
		//print_r($options);
		if($options['show_on'] == 'show_after_post' or $options['show_on'] == 'show_before_post')
			$output = "<h3>".$options['title']."</h3>";
        $output .= "<div class='zmshbt $__class $iconset_id $iconset_type'>";
        if(is_array($selected_icons))
			foreach($selected_icons as $id => $ico){
				$icon = $icons[$id];
				if(!$icon) continue;
				extract($icon);
				$icon['iconset_id']		= $iconset['id'];
				$icon['iconset_url']	= $iconset['url'];
				$icon['iconset_type']	= $iconset_type;
				if(!array_key_exists($id, (array)$selected_icons) and !in_array($id, (array)$selected_icons)) continue;
				$this->printed_icons[$iconset['id']."_$iconset_type\0_".$id] = $icon;
				$url= apply_filters("zm_sh_placeholder", $url);
				$output .= "<a class='$class'	target='_blank' href='$url'></a>\n";
			}
        $output .= "</div>";
		return $output;
	}
	
	
	
	/*
	function zm_sh_icons($icons){
		$default = $this->default_icons;
		if(has_filter("zm_sh_default_icons"))
			$default = apply_filters("zm_sh_default_icons",$default);
		$icons = array_merge($default, $icons);
		return $icons;
	}*/
	
	function curentPageURL() {
		return zm_sh_curentPageURL();
	}
}

function zm_sh_curentPageURL() {
	$pageURL = 'http';
	if(isset($_SERVER["HTTPS"])) if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

