jQuery(document).bind('cleverswatch_update_gallery', function (event, response) {
    setTimeout(function () {
        etTheme.init();
        jQuery('.images.zoom-on').wc_product_gallery();
    }, 500);
});