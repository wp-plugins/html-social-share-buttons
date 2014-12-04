<?php
/*
Plugin Name: Html Social share buttons
Plugin URI: http://wordpress.org/plugins/html-social-share-buttons/
Description: Html share button. It show lite share button only with html. It's not using any javascript whats anothers do. It's load only extra 10-11 kb total on your site.
Author: Alimuzzaman Alim
Version: 2.0.0
Author URI: http://www.zm-tech.net
*/





// Iconset dir where to search for iconsets.
$dir_iconset = plugin_dir_path(__FILE__) . "iconset";
$zm_sh_default_options = array(
						"enable_floating" => 1,
						"show_on" => "left_side",
						"iconset" => "default",
						"icons" => array
							(
								"facebook" => "on",
								"twitter" => "on",
								"linkedin" => "on",
								"googlepluse" => "on",
								"bookmark" => "on",
								"pinterest" => "on",
								"mail" => "on",
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
	
	public $permalink;
	public $title;
	public $imageurl;
	
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
		$this->options = get_option("zm_shbt_fld", $zm_sh_default_options);
		// getting the current iconset
		$this->iconset = zm_sh_get_current_iconset();
		
		//print styles and floating buttons 
		add_action('wp_print_footer_scripts',  array($this,'footer'));
		//register stylesheets from theme
		//add_action( 'wp_enqueue_scripts', array($this,'register_styles') );
		
		//registering widget
		add_action( 'widgets_init', array($this,'register_widgets') );
		
		add_shortcode("zm_sh_btn", array($this, 'shortcode_cb'));
		
		add_action('loop_start', array($this,'loop_start'));
		add_action( 'plugins_loaded', 'load_textdomain' );
	}
	/**
	 * Load plugin textdomain.
	 *
	 * @since 1.0.0
	 */
	function load_textdomain() {
		load_plugin_textdomain( 'zm_sh', false, dirname( plugin_basename( __FILE__ ) ) . '/langs' ); 
	}
	function shortcode_cb($atts){
		$atts = shortcode_atts(array(
									'iconset'	=> "",
									'icons'		=> "",
									'class'		=> "",
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
		register_widget( 'zm_html_share_widget' );
	}
	
	//print styles and floating buttons 
	function footer(){
		$options = $this->options;
		if(isset($options['enable_floating']) and ($options['show_on'] == 'left_side' or $options['show_on'] == 'right_side'))
			echo $this->zm_sh_btn();
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
			.zmshbt.$iconset_id .$class {
					background-image:url('$iconset_url" . "$image');
			}
			";
		}
		echo "</style>";
	}
	
	//the button generator function
	function zm_sh_btn($instance = ""){
		$permalink = $this->permalink;
		
		if($instance){
			$iconset_id = $instance['iconset'];
			$selected_icons = $instance['icons'];
			$class = $instance['class'];
		}
		else{
			$options = $this->options;
			$iconset_id = $options['iconset'];
			$selected_icons = $options['icons'];
			if($options['show_on'] == 'left_side')
				$class = "left";
			elseif($options['show_on'] == 'right_side')
				$class = "right";
		}
		
		$iconset = zm_sh_get_iconset($iconset_id);
		$this->stylesheets[$iconset['id']] = $iconset['url'] . $iconset['stylesheet'];
		$icons = $iconset['icons'];
		//print_r($iconset);
		
        $output = "<div class='zmshbt $class $iconset_id'>";
        
			foreach($icons as $icon){
				extract($icon);
				$icon['iconset_id'] = $iconset['id'];
				$icon['iconset_url'] = $iconset['url'];
				if(!array_key_exists($id, (array)$selected_icons) and !in_array($id, (array)$selected_icons)) continue;
				$this->printed_icons[$iconset['id']."_".$id] =$icon;
				$url= apply_filters("zm_sh_placeholder", $url);
				$output .= "<a id='$id' class='$class'	target='_blank' href='$url'></a>\n";
			}
        $output .= "</div>";
		return $output;
	}
	
	function loop_start(){
		$this->permalink = $this->curentPageURL();
		$this->title = get_the_title();
		$this->imageurl = urlencode( wp_get_attachment_url( get_post_thumbnail_id($post->ID) ) );
		if(!$this->imageurl)
			$this->imageurl = $this->first_image($this->permalink);
		if(is_home()){
			$this->title = get_option( "blogname");
		}
		elseif (is_category())  
			$this->title = single_cat_title("",false)." Archive";
		elseif (is_tag())  
			$this->title = single_tag_title("",false)." Archive";
		elseif (is_search()) 
			$this->title = "Search Results for : ".the_search_query("",false);
		elseif (is_author()) 
			$this->title = "Author Archive";
		elseif (is_day()) 	
			$this->title = "Daily Archive : ".get_the_time('l, F j, Y');
		elseif (is_month())  
			$this->title = "Monthly Archive : ".get_the_time('F Y');  
		elseif (is_year()) 
			$this->title = "Yearly Archive : ".get_the_time('Y');  
	}
	
	function first_image($url) {
		$postid = url_to_postid( $url );
		$post = get_post( $postid, "OBJECT" );
		$first_img = '';
		$output = preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		$first_img = $matches[1][0];
		return $first_img;
	}
	
	function curentPageURL() {
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
}


