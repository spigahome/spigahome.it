jQuery(document).ready(function(){
	jQuery('.zoo-cw-colorpicker').wpColorPicker();
	
	jQuery(document).on('click','.zoo-cw-image-picker',function(e){
		e.preventDefault();
		var image = wp.media({ 
            title: 'Upload Image',
            multiple: false
        }).open()
        .on('select', function(e){
            var uploaded_image = image.state().get('selection').first();
            var image_url;
            if(typeof uploaded_image.toJSON().sizes.thumbnail === 'undefined') {
                image_url=uploaded_image.toJSON().url;
            }else{
                image_url = uploaded_image.toJSON().sizes.thumbnail.url;
            }
            jQuery('.zoo-cw-slctd-img').attr('src',image_url);
            jQuery('.zoo-cw-selected-attr-img').val(image_url);
        });
	});
	
	jQuery(document).on('change','#zoo-cw-display-type',function(){
		
		var slctd = jQuery(this).val();
		zoo_cw_show_option(slctd);
	});
});

function zoo_cw_show_option(value){
	if(value == 1){
		jQuery(".zoo-cw-attr-image-uploader").show();
		jQuery(".zoo-cw-attr-colorpickerdiv").hide();
	}else if(value == 2){
		jQuery(".zoo-cw-attr-image-uploader").hide();
		jQuery(".zoo-cw-attr-colorpickerdiv").show();
	}else{
		jQuery(".zoo-cw-attr-image-uploader").hide();
		jQuery(".zoo-cw-attr-colorpickerdiv").hide();
	}
}