var ajax_url = zoo_cw_params.ajax_url;

jQuery(document).ready(function(){
	jQuery('.zoo-cw-colorpicker').wpColorPicker();
	
	var SelectedVal = jQuery('.zoo_cw_atds').val();
	var SelectedValue = jQuery('#zoo_cw_atds2').val();
	if(SelectedVal==4 && SelectedValue == 3){
		
		jQuery('#zoo_cw_atds_cv_w').show();
	}else{
		
		jQuery('#zoo_cw_atds_cv_w').hide();
	}
	
	//custom size..
	jQuery('.zoo_cw_atds').on('click',function(){
		var SelectedVal = jQuery(this).val();
		var SelectedValue = jQuery('#zoo_cw_atds2').val();
		if(SelectedVal==4){
			jQuery('#zoo_cw_atds_cv').show();
			if(SelectedValue == 3)
				jQuery('#zoo_cw_atds_cv_w').show();
			
		}else{
			jQuery('#zoo_cw_atds_cv').hide();
			if(SelectedValue == 3)
				jQuery('#zoo_cw_atds_cv_w').hide();
			
		}
	});
	
	jQuery('#zoo_cw_atds2').on('change',function(){
		var SelectedVal = jQuery('.zoo_cw_atds').val();
		var SelectedValue = jQuery(this).val();
		if(SelectedVal==4 && SelectedValue == 3){
			
			jQuery('#zoo_cw_atds_cv_w').show();
		}else{
			
			jQuery('#zoo_cw_atds_cv_w').hide();
		}
	});
});
