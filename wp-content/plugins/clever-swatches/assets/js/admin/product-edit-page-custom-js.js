var ajax_url = zoo_cw_params.ajax_url;

jQuery(document).ready(function(){
    /**
     *  script for swatch images..
     */
    jQuery(document.body).on('click','#zoo-cw-accordion .zoo-cw-panel-heading',function(){
        var k = jQuery(this).next().slideToggle('slow');
        jQuery('.zoo-cw-collapse').not(k).slideUp('slow');
    });
    jQuery(document.body).on('click','.zoo-cw-sub-accordion .zoo-cw-sub-panel-heading',function(){
        var m = jQuery(this).next().slideToggle('slow');
        jQuery('.zoo-cw-sub-collapse').not(m).slideUp('slow');
    });

    // adding the color picker for attributes swatches..
    jQuery('.zoo-cw-colorpicker').wpColorPicker();

    // using media library for selecting attributes swatch images..
    jQuery(document).on('click','.zoo-cw-scimage-upload',function(e){
        var attrName = jQuery(this).attr('data-attrname');
        e.preventDefault();
        var image = wp.media({
            title: 'Upload Image',
            multiple: false
        }).open()
            .on('select', function(e){

                var uploaded_image = image.state().get('selection').first();
                var image_url='';
                if(typeof uploaded_image.toJSON().sizes.thumbnail === 'undefined') {
                    image_url=uploaded_image.toJSON().url;
                }else{
                    image_url = uploaded_image.toJSON().sizes.thumbnail.url;
                }
                jQuery('.zoo-cw-scimage_'+attrName).attr('src',image_url);
                jQuery('.zoo-cw-input-scimg-'+attrName).val(image_url);
            });
    });

    // toggle the colorpicker and image select option based on the swatch type select.
    jQuery(document).on('change','.zoo-cw-dtslct',function(){

        var slctdOption = jQuery(this).val();
        if(slctdOption==0){
            jQuery(this).closest('tr').parent().find('.zoo-cw-scc').show();
            jQuery(this).closest('tr').parent().find('.zoo-cw-sci').hide();
        }else{
            jQuery(this).closest('tr').parent().find('.zoo-cw-scc').hide();
            jQuery(this).closest('tr').parent().find('.zoo-cw-sci').show();
        }
    });

    jQuery(document).on('change','.zoo-cw-display-type',function(){
        var display_type = jQuery(this).find('select').val();
        var tab = jQuery(this).parents('.zoo-cw-collapse');
        if (display_type == 'default') {
            tab.find('.zoo_cw_product_swatch_display_size').hide();
            tab.find('.zoo_cw_product_swatch_display_shape').hide();
            tab.find('.zoo_cw_product_swatch_display_name').hide();
            tab.find('.zoo-cw-sub-accordion').hide();
        } else{
            tab.find('.zoo_cw_product_swatch_display_size').show();
            tab.find('.zoo_cw_product_swatch_display_shape').show();
            if (display_type == 'text') {
                tab.find('.zoo_cw_product_swatch_display_name').hide();
                tab.find('.zoo-cw-sub-accordion').hide();
            } else if(display_type == 'image') {
                tab.find('.zoo_cw_product_swatch_display_name').show();
                tab.find('.zoo-cw-sub-accordion').show();
                tab.find('.zoo-cw-scc').hide();
                tab.find('.zoo-cw-sci').show();
            } else if(display_type == 'color') {
                tab.find('.zoo_cw_product_swatch_display_name').show();
                tab.find('.zoo-cw-sub-accordion').show();
                tab.find('.zoo-cw-scc').show();
                tab.find('.zoo-cw-sci').hide();
            }
        }
    });

    //----------End of swatch images script ---------------------//

    // always load updated attributes terms..
    jQuery(".zoo-cw-term-swatches a").on('click',function(){

        var post_id = jQuery("#post_ID").val();
        var wrapper_div = jQuery("#zoo-cw-variation-swatch-data");

        jQuery( '#woocommerce-product-data' ).block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });

        jQuery.ajax({
            url: ajax_url,
            cache: false,
            type: "POST",
            headers : { "cache-control": "no-cache" },
            data: {
                'action': 'zoo_cw_update_term_data',
                'post_id' : post_id,
            },
            success:function(response) {

                wrapper_div.empty().replaceWith( response );
                jQuery('.zoo-cw-colorpicker').wpColorPicker();
                jQuery( '#woocommerce-product-data' ).unblock();
            }
        });
    });


    /**
     * variation gallery images script start..
     */
    //adding gallery images for each variation.
    jQuery(document).on('click','.add-variation-gallery-image',function(event){

        event.preventDefault();

        var $el = jQuery( this ).find('a');
        var loop = jQuery(this).attr('data-loop');

        // Product gallery file uploads
        var product_gallery_frame;
        var $image_gallery_ids = jQuery(this).parent().find('.zoo-cw-variation-gallery');
        var $product_images    = jQuery(this).parent().find('.zoo-cw-variation-gallery-container').find('ul.product_images');

        // If the media frame already exists, reopen it.
        if ( product_gallery_frame ) {
            product_gallery_frame.open();
            return;
        }

        // Create the media frame.
        product_gallery_frame = wp.media.frames.product_gallery = wp.media({
            // Set the title of the modal.
            title: $el.data( 'choose' ),
            button: {
                text: $el.data( 'update' )
            },
            states: [
                new wp.media.controller.Library({
                    title: $el.data( 'choose' ),
                    filterable: 'all',
                    multiple: true
                })
            ]
        });

        // When an image is selected, run a callback.
        product_gallery_frame.on( 'select', function() {
            var selection = product_gallery_frame.state().get( 'selection' );
            var attachment_ids = $image_gallery_ids.val();

            selection.map( function( attachment ) {
                attachment = attachment.toJSON();

                if ( attachment.id ) {
                    attachment_ids   = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
                    var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

                    $product_images.append( '<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" /><ul class="actions"><li><a href="javascript:void(0)" class="zoo-cw-delete-gallery-image" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li></ul></li>' );
                }
            });

            $image_gallery_ids.val( attachment_ids );
        });

        // Finally, open the modal.
        product_gallery_frame.open();

        jQuery( this ).closest( '#variable_product_options' ).find( '.woocommerce_variation' ).addClass( 'variation-needs-update' );

        jQuery( 'button.cancel-variation-changes, button.save-variation-changes' ).removeAttr( 'disabled' );

        jQuery( '#variable_product_options' ).trigger( 'woocommerce_variations_defaults_changed' );

    });

    //deleting product gallery image.
    jQuery(document).on('click',".zoo-cw-delete-gallery-image",function(e){

        e.preventDefault();

        var $imageToDelete = jQuery(this).parent().parent().parent();
        var attchmntId = jQuery($imageToDelete).attr('data-attachment_id');

        jQuery($imageToDelete).fadeOut();

        var attrValues = jQuery($imageToDelete).parent().parent().find(".zoo-cw-variation-gallery").val();
        var attrArray = attrValues.split(',');

        attrArray = jQuery.grep(attrArray, function(value) {
            return value != attchmntId;
        });

        jQuery($imageToDelete).parent().parent().find(".zoo-cw-variation-gallery").val(attrArray);

        jQuery( this ).closest( '#variable_product_options' ).find( '.woocommerce_variation' ).addClass( 'variation-needs-update' );

        jQuery( 'button.cancel-variation-changes, button.save-variation-changes' ).removeAttr( 'disabled' );

        jQuery( '#variable_product_options' ).trigger( 'woocommerce_variations_defaults_changed' );

    });
});