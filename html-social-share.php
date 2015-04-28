<?php
/*
Plugin Name: Html Social share buttons
Plugin URI: http://wordpress.org/plugins/html-social-share-buttons/
Description: Html share button. It show lite share button only with html. It's not using any javascript whats anothers do. It's load only extra 10-11 kb total on your site.
Author: Alimuzzaman Alim
Version: 2.1.2
Author URI: http://www.zm-tech.net
Text Domain: zm-sh
Domain Path: /languages
*/

// Iconset dir where to search for iconsets.
define("zm_sh_dir", plugin_dir_path(__FILE__));
//define("zm_sh_url_iconset", zm_sh_dir . "iconset");

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
						'iconset_type'	=> "square",
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

//include interfaces.php
//it's contains all interfaces
include("interfaces.php");

//include iconsets.php
//it's contains all function to add, remove, get iconsets
include("iconsets.php");

//include filters.php
//it's contain filters
include("filters.php");

//include widget.php
//it's register widget
include("widget.php");

include('vc-integration.php');
include('shortcode.php');

require_once("form.php");

include("metabox.php");
include("settings_page.php");

// make variable globaly accessable
global $zm_sh_iconset_classes;

//new instance of class zm_social_share
add_action('init', function(){
	global $zm_sh;
	$zm_sh = new zm_social_share;
	//echo 'xxxxx init done';
}, 1);

function zm_sh_btn($options){
	global $zm_sh;
	//print_r($zm_sh);
	//wp_die();
	if(!is_object($zm_sh))
		trigger_error('Please call after init hook');
	$options['icons']	= array_flip($options['icons']);
	return $zm_sh->zm_sh_btn($options);
}

class zm_social_share{
	public	$iconset;
	public	$iconsets;
	
	public	$options;
	private	$schemas;
	private	$icons;
	private	$printed_icons;
	private	$stylesheets;
	/*
	public static function getInstance() {
		static $instance;
		if ($instance === null){
			$instance = new self;
			do_action( "zm_sh_add_iconset");
		}
		return $instance;
	}*/
	
	function __construct(){
		global $zm_sh_default_options;
		
		$this->options = get_option("zm_shbt_fld", $zm_sh_default_options);
		$this->iconsets	= new zm_sh_iconset;
		//print_r($this->iconsets);
		// getting options form database
		// getting the current iconset
		$this->iconset = $this->iconsets->get_current_iconset();
		
		//print styles and floating buttons 
		add_action('wp_print_footer_scripts',  array($this,'footer'));
		//register stylesheets from theme
		//add_action( 'wp_enqueue_scripts', array($this,'register_styles') );
		
		
		
		add_action('plugins_loaded', array($this, 'plugins_loaded'));

		if(isset($this->options['show_after_post']) and $this->options['show_after_post'] or $this->options['show_before_post'])
			add_filter( 'the_content', array($this, 'filter_the_content') );
			
		add_action('wp', array($this, 'wp'));
	}
	/*
	
	
	
	*/
	function wp(){
		global $post;
		//echo $post->ID;
		//print_r($post);
		
		$excludes		= $this->options['excludes'];
		$excludes		= (array) explode(',', $excludes);
		//print_r($excludes);
		if(in_array($post->ID, $excludes)){
			$this->excluded	= true;
			return;
		}
			
		$disable_share = get_post_meta( $post->ID, '_zm_sh_disable_share', true );
		if($disable_share=='on'){
			$this->excluded	= true;
			return;
		}
	}
	
	function plugins_loaded(){
		// Localization
		load_plugin_textdomain('zm-sh', false, dirname(plugin_basename(__FILE__)) . '/languages' );
		
		
	}
	
	function filter_the_content($content){
		//if(isset($this->excluded) and $this->excluded == true) return;
		//print_r($this->options);
		$options = $this->options;
		$options['class'] = "in_widget";
		if( is_singular() && isset($options['show_in']['show_before_post']) and  $options['show_in']['show_before_post']) {
			$options['show_on'] = 'show_before_post';
			$content = $this->zm_sh_btn($options).$content;
		}
		if( is_singular() && isset($options['show_in']['show_after_post']) and $options['show_in']['show_after_post']) {
			$options['show_on'] = 'show_after_post';
			$content = $content . $this->zm_sh_btn($options);
		}
		return $content;
	}
	
	
	
	//print styles and floating buttons 
	function footer(){
		if(isset($this->excluded) and $this->excluded == true) return;
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
		if(isset($options['show_in']['show_left']) and $options['show_in']['show_left']){
			$options['class'] = 'left';
			$options['show_on'] = 'show_left';
			echo $this->zm_sh_btn($options);
		}
		if(isset($options['show_in']['show_right']) and $options['show_in']['show_right']){
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
		if(!is_array($this->printed_icons))
			return;
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
		$output	= '';
		if(isset($this->excluded) and $this->excluded == true) return;
		$options = $instance?$instance:$this->options;
		
		$__class = $options['class']?$options['class']:"left";
		$iconset_id = $options['iconset'];
		$selected_icons = $options['icons'];
		
		$iconset = $this->iconsets->get_iconset($iconset_id);
		$this->stylesheets[$iconset->id] = $iconset->url . $iconset->stylesheet;
		$icons = $iconset->icons;
		if(!isset($options['show_on']))
			$options['show_on']	= 'show_left';
		if(isset($options['iconset_type']) and $options['iconset_type'])
			$iconset_type = $options['iconset_type'];
		else
			$iconset_type = $options[$options['show_on']];
		//print_r($options);
		if(
			(
				isset($options['show_on']) and 
				($options['show_on'] == 'show_after_post' or $options['show_on'] == 'show_before_post')
			)
			or
			( isset($options['title']) and $options['class'] == 'in_shortcode' )
		)
			$output = "<h3>".$options['title']."</h3>";
		//print_r($options);
		//echo "\n\n\n\n\n\n\n\n\n\n\n\n";
        $output .= "<div class='zmshbt $__class $iconset_id $iconset_type'>";
		//print_r($icons);
        if(is_array($selected_icons))
			foreach($selected_icons as $id => $ico){
				$icon = $icons[$id];
				if(!$icon) continue;
				extract($icon);
				$icon['iconset_id']		= $iconset->id;
				$icon['iconset_url']	= $iconset->url;
				$icon['iconset_type']	= $iconset_type;
				if(!array_key_exists($id, (array)$selected_icons) and !in_array($id, (array)$selected_icons)) continue;
				$this->printed_icons[$iconset->id."_$iconset_type\0_".$id] = $icon;
				$url= apply_filters("zm_sh_placeholder", $url);
				$output .= "<a class='$class'	target='_blank' href='$url'></a>\n";
			}
        $output .= "</div>";
		return $output;
	}
	
	
	
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

