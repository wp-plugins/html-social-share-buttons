// JavaScript Document
jQuery(document).ready(function($) {
    $(document).on("change", ".iconset", function(e){
		$value = $(this).val();
		jQuery.post(
			ajaxurl, 
			{
				'action': 'get_iconset_details',
				'iconset':   $value
			}, 
			function(response){
				$icons = $.parseJSON(response);
				$html = "";
				$.each($icons,function(index, value){
					$html += '<input id="icons-'+ index +'" value="'+ index +'" class="wpb_vc_param_value icons checkbox" type="checkbox" name="icons">' + value + "\n";
				});
				$(".wpb_el_type_checkbox .edit_form_line").html($html);
				console.log($html);
			}
		);
	});
	
});