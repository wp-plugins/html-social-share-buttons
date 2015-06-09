// JavaScript Document
/*
jQuery(document).ready(function($) {
    jQuery('#iconset').change(function(){
		var iconsetId	= jQuery(this).val();
		console.log(iconsetId);
		jQuery.post(
			ajaxurl, 
			{
				'action'	: 'get_iconset_preview',
				'iconsetId'	: iconsetId,
				'type'		: 'iconset_preview'
			}, 
			function(url){
				jQuery('.button-style-img img').attr('src', url);
			}
		);
		
		
	});
});
*/
var iconset;
jQuery(document).ready(function($) {
    jQuery('#iconset').change(function(){
		var image_preview	= zm_sh_url_assets_img+'preview.gif';
		change_preview_img(image_preview);
		var iconsetId	= jQuery(this).val();
		jQuery.post(
			ajaxurl, 
			{
				'action'	: 'get_iconset',
				'iconsetId'	: iconsetId,
			}, 
			function(iconset_1){
				$iconset	= $.parseJSON(iconset_1);
				on_iconset_chane($iconset);
			}
		);
		
		
	});
});


function on_iconset_chane(iconset){
	$image_preview	= iconset.url + iconset.preview_img
	change_preview_img($image_preview);
	
}

function change_preview_img(url){
	jQuery('.button-style-img img').attr('src', url);
}

jQuery(document).ready(function($) {
    $('#get_php, #get_short').click(function(event){
		event.preventDefault();
		$('#zm-sh-thick-box').show().find('.title').html($(this).attr('title'));
		$('body').css({
			overflow	: 'hidden',
			height		: '100%'
		});
		
	});
	$('#zm-sh-thick-box .backdrop, #zm-sh-thick-box .close').click(function(){
		$('#zm-sh-thick-box').hide();
		$('body').css({
			overflow	: 'initial',
			height		: 'initial'
		});
	});
});

function zm_thick_box(id){
	$(id).css({
			display: block,
	});
	
	
	
	
	
}