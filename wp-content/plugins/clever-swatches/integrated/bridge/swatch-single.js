/*Add bottom file js/woocommerce.min.js*/
jQuery(document).bind('cleverswatch_update_gallery', function (event, response) {
    setTimeout(function () {
        jQuery('.woocommerce-product-gallery.images').css('opacity','1');
        qodeInitSingleProductLightbox();
    }, 500);
});