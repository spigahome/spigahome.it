jQuery(document).bind('cleverswatch_update_gallery', function (event, response) {
    jQuery('#product-' + response.product_id+' .product-thumbnails.thumbnails').remove();
    setTimeout(function () {
        if(!jQuery('#product-' + response.product_id+' .product-thumbnails.thumbnails')[0]){
            jQuery('#product-' + response.product_id+' .images').parent().append(jQuery(response.content).next('.product-thumbnails.thumbnails'));
        }
        window.theme.WooProductImageSlider.initialize();
    },300);
});