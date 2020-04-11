jQuery(document).ready(function(){
    jQuery('.zoo-cw-settings-wrapper .zoo_cw_product_swatch_display_size').on('change',function(){
        if (jQuery(this).val() == 'custom') {
            jQuery('.zoo-cw-settings-wrapper #zoo_cw_product_swatch_display_size_width').show();
            jQuery('.zoo-cw-settings-wrapper #zoo_cw_product_swatch_display_size_height').show();
        } else {
            jQuery('.zoo-cw-settings-wrapper #zoo_cw_product_swatch_display_size_width').hide();
            jQuery('.zoo-cw-settings-wrapper #zoo_cw_product_swatch_display_size_height').hide();
        }
    });

    jQuery('.zoo-cw-settings-wrapper .zoo_cw_shop_swatch_display_size').on('change',function(){
        if (jQuery(this).val() == 'custom') {
            jQuery('.zoo-cw-settings-wrapper #zoo_cw_shop_swatch_display_size_width').show();
            jQuery('.zoo-cw-settings-wrapper #zoo_cw_shop_swatch_display_size_height').show();
        } else {
            jQuery('.zoo-cw-settings-wrapper #zoo_cw_shop_swatch_display_size_width').hide();
            jQuery('.zoo-cw-settings-wrapper #zoo_cw_shop_swatch_display_size_height').hide();
        }
    });

    jQuery('.zoo-cw-settings-wrapper .zoo_cw_cart_swatch_display_size').on('change',function(){
        if (jQuery(this).val() == 'custom') {
            jQuery('.zoo-cw-settings-wrapper #zoo_cw_cart_swatch_display_size_width').show();
            jQuery('.zoo-cw-settings-wrapper #zoo_cw_cart_swatch_display_size_height').show();
        } else {
            jQuery('.zoo-cw-settings-wrapper #zoo_cw_cart_swatch_display_size_width').hide();
            jQuery('.zoo-cw-settings-wrapper #zoo_cw_cart_swatch_display_size_height').hide();
        }
    });

    jQuery('.zoo-cw-settings-wrapper .zoo_cw_display_shop_page').on('change',function(){
        if (jQuery(this).val() == 1) {
            jQuery('.zoo-cw-settings-wrapper .zoo_cw_display_shop_page_hook').parents('tr').show();
            jQuery('.zoo-cw-settings-wrapper .zoo_cw_shop_swatch_display_shape').parents('tr').show();
            jQuery('.zoo-cw-settings-wrapper .zoo_cw_shop_swatch_display_size').parents('tr').show();
            jQuery('.zoo-cw-settings-wrapper .zoo_cw_display_shop_page_add_to_cart').parents('tr').show();
        } else {
            jQuery('.zoo-cw-settings-wrapper .zoo_cw_display_shop_page_hook').parents('tr').hide();
            jQuery('.zoo-cw-settings-wrapper .zoo_cw_shop_swatch_display_shape').parents('tr').hide();
            jQuery('.zoo-cw-settings-wrapper .zoo_cw_shop_swatch_display_size').parents('tr').hide();
            jQuery('.zoo-cw-settings-wrapper .zoo_cw_display_shop_page_add_to_cart').parents('tr').hide();
        }
    });

    jQuery('.zoo-cw-settings-wrapper .zoo_cw_display_shop_page_hook').on('change',function(){
        if (jQuery(this).val() == 'shortcode') {
            jQuery(this).next('.zoo_cw_display_shop_page_hook_description').show();
        } else {
            jQuery(this).next('.zoo_cw_display_shop_page_hook_description').hide();
        }
    });
});