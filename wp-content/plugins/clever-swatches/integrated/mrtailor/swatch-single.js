jQuery(document).bind('cleverswatch_update_gallery', function (event, response) {
    jQuery('.images .product_thumbnails').remove();
    var thumb=jQuery(response.content).find('.product_thumbnails');
    jQuery('.product_summary_thumbnails_wrapper .product_thumbnails').replaceWith(thumb);
    setTimeout(function() {
        jQuery("#product-images-carousel").owlCarousel({
            singleItem : true,
            autoHeight : true,
            transitionStyle:"fade",
            lazyLoad : true,
            afterMove: function(event){
                if ( control !== true ) {
                    activate_slide(this.currentItem	);
                }

            }

        });
        if (jQuery(".easyzoom").length ) {
            if ( jQuery(window).width() > 1024 ) {
                var $easyzoom = jQuery(".easyzoom").easyZoom({
                    loadingNotice: '',
                    errorNotice: '',
                    preventClicks: false,
                });
            }

        }
        owl                                 = jQuery("#product-images-carousel").data('owlCarousel');
        $product_thumbnails                 = jQuery(document).find('.product_thumbnails');
        $product_thumbnails_cells 			= $product_thumbnails.find('.carousel-cell');
        $product_thumbnails_height 			= $product_thumbnails.height();
        $product_thumbnails_width 			= $product_thumbnails.width();
        if ( $("#product-images-carousel").length ) {
            jQuery(document).on('click', '.carousel-cell', function(event) {
                var index = jQuery(event.currentTarget).index();
                activate_slide(index);
                control = true;
                owl.goTo(index);
                control = false;
            });
        }
    },300);
});