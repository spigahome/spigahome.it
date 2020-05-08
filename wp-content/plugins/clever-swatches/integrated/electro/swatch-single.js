jQuery(document).bind('cleverswatch_update_gallery', function (event, response) {
    setTimeout(function() {
        jQuery('#product-' + response.product_id).find('.images').each(function () {
            jQuery(this).wc_product_gallery();
        });
    },300);
});