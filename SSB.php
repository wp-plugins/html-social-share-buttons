<?php
/*
Plugin Name: Html Social share buttons
Plugin URI: http://wordpress.org/plugins/html-social-share-buttons/
Description: Html share button. It show lite share button only with html. It's not using any javascript whats anothers do. It's load only extra 10-11 kb total on your site.
Author: Alimuzzaman Alim
Version: 1.0.8
Author URI: http://www.zm-tech.net
*/


global $zm_sh;
$zm_sh=new zm_social_share;
class zm_social_share{
	
	public $default_iconset = "default";
	public $iconset;
	public $iconset_url = "";
	
	private $cmnstyled;
	
	function __construct($iconset){
		$this->iconset = $iconset;
		if(!file_exists("iconset/" . $iconset))
			$this->iconset = $default_iconset;
		
		$this->iconset_url = plugins_url( 'iconset/' . $this->iconset, __FILE__ ) ;
		
		add_action('wp_print_styles',  array($this,'zm_sh_style_lside')); 
		add_action('wp_print_footer_scripts',  array($this,'zm_footer_script'));
		
		
	}
	
	
	function zm_footer_script(){
		$this->zm_sh_btn();
	}
	
	
	function zm_sh_btn($id="zmshbt"){
		$options=get_option("zm_shbt_fld");
		
		$permalink = curentPageURL();
		global $zm_sh_title,$imageurl;
		?>
<div id="<?php echo $id?>" class="zmshbt">

    <a href="http://www.facebook.com/sharer.php?u=<?php echo $permalink; ?>&amp;t=<?php echo $zm_sh_title; ?>" target="_blank" class="facebook" title="Share This on Facebook"></a>
    

    <a href="http://twitter.com/share?url=<?php echo $permalink; ?>&amp;text=<?php echo $zm_sh_title; ?>" target="_blank" alt="Tweet This!" title="Tweet This!" class="twitter" ></a>

    <a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $permalink; ?>&amp;title=<?php echo $zm_sh_title; ?>" target="_blank" title="Share This on LinkedIn" class="linkedin" ></a>
    
    
    <a href="https://plus.google.com/share?url=<?php echo $permalink; ?>" target="_blank" title="Post it on Google+" class="googlepluse" ></a>


    <a href="http://www.google.com/bookmarks/mark?op=edit&bkmk=<?php echo $permalink; ?>&amp;title=<?php echo $zm_sh_title; ?>&annotation=<?php bloginfo('description'); ?>" target="_blank" title="Add to Google bookmark" class="bookmark" ></a>


    <a href="mailto:?subject=I wanted you to see this site&amp;body=This is about <?php echo $zm_sh_title; ?> <?php echo $permalink; ?> " title="Share by Email" target="_blank" class="mail"></a>


    <a target="blank" href="http://pinterest.com/pin/create/button/?url=<?php echo $permalink ?>&amp;media=<?php echo $imageurl ?>&amp;description=<?php echo $zm_sh_title; ?>" title="Pin This Post" class="pinterest"></a>


</div>
<?php 
	}
	
	function zm_sh_style_cmn() {	
		$iconset_url = $this->iconset_url;
		if(!$this->cmnstyled){
?>
<style>
.facebook{
	background-image: url("<?php echo $iconset_url;?>/facebook.png");
}
.twitter{
	background-image: url("<?php echo $iconset_url;?>/twitter.png");
}
.linkedin{
	background-image: url("<?php echo $iconset_url;?>/linkedin.png");
}
.googlepluse{
	background-image: url("<?php echo $iconset_url;?>/google.png");
}
.bookmark{
	background-image: url("<?php echo $iconset_url;?>/rss.png");
}
.pinterest{
	background-image: url("<?php echo $iconset_url;?>/pinterest.png");
}
.mail{
	background-image: url("<?php echo $iconset_url;?>/mail.png");
}

.zmshbt{
	z-index:9999;
	-moz-box-shadow:0 0 10px 7px rgba(205, 65, 224, 0.42) inset, 0 0 4px 3px rgba(242, 181, 245, 1);
	-webkit-box-shadow:0 0 10px 7px rgba(205, 65, 224, 0.42) inset, 0 0 4px 3px rgba(242, 181, 245, 1);
	box-shadow:0 0 10px 7px rgba(205, 65, 224, 0.42) inset, 0 0 4px 3px rgba(242, 181, 245, 1);
}
.zmshbt a {
	-moz-transition: all .25s linear;
	-webkit-transition: all .25s linear;
	transition: all .25s linear;
	width: 30px;
	height: 30px;
	margin: 10px
}
.zmshbt a:hover {
	-moz-transform: scale(1.5);
	-ms-transform: scale(1.5);
	-o-transform: scale(1.5);
	-webkit-transform: scale(1.5);
	transform: scale(1.5)
}
.zmshbt a:active {
	-moz-transform: scale(1.3);
	-ms-transform: scale(1.3);
	-o-transform: scale(1.3);
	-webkit-transform: scale(1.3);
	transform: scale(1.3)
}
</style>
<?php
		}
		$this->cmnstyled=true;
	}
	
	function zm_sh_style_lside() {
		$this->zm_sh_style_cmn();
		$options=get_option("zm_shbt_fld");
?>
<style>
#zmshbt {
	position: fixed;
	left: -25px;
	top: 30%;
	background-color: rgba(223, 153, 226, 0.36);
	-moz-transition: all .25s linear .5s;
	-webkit-transition: all .25s linear .5s;
	transition: all .25s linear .5s;
}
#zmshbt:hover {
	left: 0;
}
#zmshbt a {
	display: block;
}
#zmshbt:hover a{
	margin-right:10px
}
</style>
<?php
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





/*


class zm_shbtn_class{
	public $options;
	private $cmnstyled;
	
	function __construct(){
		$options=get_option("zm_shbt_fld");
		add_action( 'widgets_init', array($this,'zm_register_widgets') );
		
		add_action('wp_print_styles',  array($this,'zm_sh_style_widget'));   //For Social share button in widget.
		
		if(isset($options["enable"]))	
			add_action('wp_print_styles',  array($this,'zm_sh_style_lside'));   //For Social share button in left side.
		
		add_action('wp_print_footer_scripts',  array($this,'zm_footer_script'));
		add_action('loop_start', array($this,'zm_sh_in_loop'));
		add_action('admin_menu', array($this,'reg_admn_menu'));
		add_action('admin_init', array($this,'zm_reg_sett'));
	}
	
	function zm_footer_script(){
		$options=get_option("zm_shbt_fld");
		if(isset($options["enable"]))	
			$this->zm_sh_btn();
	}
	
	function zm_register_widgets() {
		register_widget( 'zm_html_widget' );
	}
	
	function zm_sh_btn($id="zmshbt"){
		$options=get_option("zm_shbt_fld");
		$purl = plugin_dir_url( __FILE__ );
		global $zm_sh_permalink;
		global $zm_sh_title,$imageurl;
		?>
<div id="<?php echo $id?>" class="zmshbt">

<?php if( isset( $options['show_facebook'])){ ?>
    <a href="http://www.facebook.com/sharer.php?u=<?php echo $zm_sh_permalink; ?>&amp;t=<?php echo $zm_sh_title; ?>" target="_blank" class="facebook" title="Share This on Facebook"></a>
<?php }?>

<?php if( isset( $options['show_tweet'])) {?>
    <a href="http://twitter.com/share?url=<?php echo $zm_sh_permalink; ?>&amp;text=<?php echo $zm_sh_title; ?>" target="_blank" alt="Tweet This!" title="Tweet This!" class="twitter" ></a>
<?php }?>

<?php if( isset( $options['show_linkedin'])){ ?>
    <a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $zm_sh_permalink; ?>&amp;title=<?php echo $zm_sh_title; ?>" target="_blank" title="Share This on LinkedIn" class="linkedin" ></a>
<?php }?>

<?php if( isset( $options['show_googlepluse'])){ ?>
    <a href="https://plus.google.com/share?url=<?php echo $zm_sh_permalink; ?>" target="_blank" title="Post it on Google+" class="googlepluse" ></a>
<?php }?>

<?php if( isset( $options['show_bookmark'])){ ?>
    <a href="http://www.google.com/bookmarks/mark?op=edit&bkmk=<?php echo $zm_sh_permalink; ?>&amp;title=<?php echo $zm_sh_title; ?>&annotation=<?php bloginfo('description'); ?>" target="_blank" title="Add to Google bookmark" class="bookmark" ></a>
<?php }?>

<?php if( isset( $options['show_mail'])){ ?>
    <a href="mailto:?subject=I wanted you to see this site&amp;body=This is about <?php echo $zm_sh_title; ?> <?php echo $zm_sh_permalink; ?> " title="Share by Email" target="_blank" class="mail"></a>
<?php }?>

<?php if( isset( $options['show_pint'])){ ?>
    <a target="blank" href="http://pinterest.com/pin/create/button/?url=<?php echo $zm_sh_permalink ?>&amp;media=<?php echo $imageurl ?>&amp;description=<?php echo $zm_sh_title; ?>" title="Pin This Post" class="pinterest"></a>
<?php }?>

</div>
<?php 
	}
	
	function zm_curPageURL() {
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
	
	function zm_sh_style_widget() {	
		$this->zm_sh_style_cmn();
		?><style> #zmshbt_wd {overflow: hidden;}#zmshbt_wd a {float:left} </style><?php
	}
	
	function zm_sh_style_lside() {
		$this->zm_sh_style_cmn();
		$options=get_option("zm_shbt_fld");
?>
<style>
#zmshbt {
	position: fixed;
	<?php
	if(isset($options["show_on"]))
		echo "Right";
	else
		echo "left";
	?>: -25px;
	top: 30%;
	background-color: rgba(223, 153, 226, 0.36);
	-moz-transition: all .25s linear .5s;
	-webkit-transition: all .25s linear .5s;
	transition: all .25s linear .5s;
}
#zmshbt:hover {
	<?php
	if(isset($options["show_on"]))
		echo "Right";
	else
		echo "left";
	?>: 0;
}
#zmshbt a {
	display: block;
}
#zmshbt:hover a{
	margin-right:10px
}
</style>
<?php
	}
	

	function zm_sh_in_loop(){
		global $zm_sh_permalink;
		global $zm_sh_title,$imageurl,$post;
		$zm_sh_permalink=$this->zm_curPageURL();
		$zm_sh_title=get_the_title();
		$imageurl = urlencode( wp_get_attachment_url( get_post_thumbnail_id($post->ID) ) );
		if(!$imageurl)
			$imageurl = $this->first_image($zm_sh_permalink);
		if(is_home()){
			$zm_sh_permalink=home_url();
			$zm_sh_title= get_option( "blogname");
		}
		elseif (is_category())  
			$zm_sh_title=single_cat_title("",false)." Archive";
		elseif (is_tag())  
			$zm_sh_title=single_tag_title("",false)." Archive";
		elseif (is_search()) 
			$zm_sh_title="Search Results for : ".the_search_query("",false);
		elseif (is_author()) 
			$zm_sh_title="Author Archive";
		elseif (is_day()) 	
			$zm_sh_title="Daily Archive : ".get_the_time('l, F j, Y');
		elseif (is_month())  
			$zm_sh_title="Monthly Archive : ".get_the_time('F Y');  
		elseif (is_year()) 
			$zm_sh_title="Yearly Archive : ".get_the_time('Y');  
	}
	
	function reg_admn_menu(){
		add_theme_page("Html Social Share","Html Social Share","administrator","zm_shbt_opt",array($this,"zm_sh_opt"));
	}
	
	function zm_sh_opt(){
		$this->options=get_option("zm_shbt_fld");
		?>
        <div class="wrap">
            <h2>Html Social Share button</h2>
            <form method="post" action="options.php"> 
            <?php 
			settings_fields( 'zm_shbt_opt' );
			do_settings_sections("zm_shbt_opt");?>
            
            <?php submit_button(); ?>
			</form>
        </div>
        <?php		
	}
	
	function zm_reg_sett(){
		register_setting( 'zm_shbt_opt', 'zm_shbt_fld',array($this,"sanitize"));
		add_settings_section( "zm_shbt_sett", "", array($this,"zm_sec_cb"),"zm_shbt_opt" );
		
		add_settings_field( "enable","Enable Share Button", array($this,"zm_sett_field"),"zm_shbt_opt", "zm_shbt_sett",  array( 'label_for' => 'enable' ) );
		
		add_settings_field( "show_on","Show Share Button on", array($this,"zm_sett_field_radio"),"zm_shbt_opt", "zm_shbt_sett",  array( 'label_for' => 'show_on' ) );
		
		
				
		add_settings_field( "show_facebook","Enable Facebook", array($this,"zm_sett_field"),"zm_shbt_opt", "zm_shbt_sett",  array( 'label_for' => 'show_facebook' ) );
		add_settings_field( "show_tweet","Enable Twitter", array($this,"zm_sett_field"),"zm_shbt_opt", "zm_shbt_sett",  array( 'label_for' => 'show_tweet' ) );
		add_settings_field( "show_linkedin","Enable LinkedIN", array($this,"zm_sett_field"),"zm_shbt_opt", "zm_shbt_sett",  array( 'label_for' => 'show_linkedin' ) );
		add_settings_field( "show_googlepluse","Enable Google Pluse", array($this,"zm_sett_field"),"zm_shbt_opt", "zm_shbt_sett",  array( 'label_for' => 'show_googlepluse' ) );
		add_settings_field( "show_bookmark","Enable Google Bookmark", array($this,"zm_sett_field"),"zm_shbt_opt", "zm_shbt_sett",  array( 'label_for' => 'show_bookmark' ) );
		add_settings_field( "show_mail","Enable Mail", array($this,"zm_sett_field"),"zm_shbt_opt", "zm_shbt_sett",  array( 'label_for' => 'show_mail' ) );
		add_settings_field( "show_pint","Show Pinterest", array($this,"zm_sett_field"),"zm_shbt_opt", "zm_shbt_sett",  array( 'label_for' => 'show_pint' ) );
		
	}
	
	function sanitize( $input ){
        $new_input = array();
		
		foreach($input as $key =>$value)
        if( isset( $input[$key] ) and $input[$key] )
            $new_input["$key"] = true;
        return $new_input;
    }
	
	function zm_sec_cb(){
		
	}
	
	function zm_sett_field($id){
		$id=$id['label_for'];
		$chk="";
		if(isset($this->options["$id"]))
		if($this->options["$id"])
			$chk="checked";
		echo "<input name='zm_shbt_fld[".$id."]' id='".$id."' ".$chk." type='checkbox'/>";
	}
	
	function zm_sett_field_radio($id){
		$id=$id['label_for'];
		$chk=" checked";
		$val = 0;
		if(isset($this->options["$id"]))
			$val = $this->options["$id"];
		?>
          
            <input type="radio" name="zm_shbt_fld[show_on]" value="0" <?php if(!$val) echo $chk;?> id="show_on_0" />
            <label for="show_on_0">Left</label>
         
            <input type="radio" name="zm_shbt_fld[show_on]" value="1" <?php if($val) echo $chk;?> id="show_on_1" />
            <label for="show_on_1">Right</label>
		
		<?php
	}
	
	function first_image($url) {
	  $postid = url_to_postid( $url );
	  $post = get_post( $postid, "OBJECT" );
	  $first_img = '';
	  ob_start();
	  ob_end_clean();
	  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	  $first_img = $matches[1][0];
	  return $first_img;
	}
	
}


class zm_html_widget extends WP_Widget {

	function zm_html_widget() {
		// Instantiate the parent object
		$widget_ops = array( 'description' => __("Html share button. It show lite share button only with html. It's not using any javascript whats anothers do.") );
		parent::__construct( "01197360491", "Html share button.",$widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		global $zm_sh;
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		$zm_sh->zm_sh_btn("zmshbt_wd");
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
	}

	function form( $instance ) {
		?>
        
		<li class="widget widget-sidebar" >
		<h3>Share This</h3>
		Html share button. It show lite 
		share button only with html. It's not using any javascript whats anothers do.
		No option's available due to simpling the widget for faster expreance<br />
		</li>
		<?php 
	}
}