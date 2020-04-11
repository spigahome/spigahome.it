//Put this js to bottom file app.min.js of theme

jQuery(document).bind('cleverswatch_update_gallery', function (event, response) {
    jQuery('#product-' + response.product_id).find('#product-thumbnails').remove();
    setTimeout(function () {
        SITE.init();
        $('#product-' + response.product_id).find('.images').wc_product_gallery();
    }, 300);
});