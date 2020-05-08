jQuery(document).bind('cleverswatch_update_gallery', function (event, response) {
    if(jQuery('.cd-quick-view .swiper-container')[0]){
        var html = jQuery(response.content).find('.swiper-container').html();
        jQuery('.cd-quick-view .swiper-container').html(html);
        var t = new Swiper(".cd-quick-view .swiper-container", {
            pagination: ".swiper-pagination",
            nextButton: ".swiper-button-next",
            prevButton: ".swiper-button-prev",
            preventClick: !0,
            preventClicksPropagation: !0,
            grabCursor: !0,
            onTouchStart: function () {
                o = !1;
            },
            onTouchMove: function () {
                o = !1;
            },
            onTouchEnd: function () {
                setTimeout(function () {
                    o = !0;
                }, 300);
            }
        })
    }
    if(jQuery('.single.single-product').length){
        var thumb = jQuery(response.content).next('.product_thumbnails').clone();
        if(thumb.length>1){
            thumb=thumb[0];
        }
        jQuery('#product-' + response.product_id).find('.product_summary_thumbnails_wrapper').html(thumb);
        jQuery('#product-' + response.product_id).find('.product_summary_thumbnails_wrapper .product_thumbnails').show();
        jQuery('#product-' + response.product_id).find('.product-images-wrapper .product_thumbnails').remove();
        setTimeout(function () {
            if (jQuery(".product-images-carousel").length) {
                var product_images = new Swiper ('.product-images-carousel', {

                    autoHeight: true,
                    preloadImages: true,
                    lazyPreloaderClass: 'swiper-lazy-preloader',
                    updateOnImagesReady: true,
                    lazyLoading: true,
                    preventClicks : ( getbowtied_scripts_vars.product_lightbox != 1 ) ? 'true' : 'false',
                    preventClicksPropagation :  getbowtied_scripts_vars.product_lightbox != 1 ? 'true' : 'false',
                    onSlideChangeEnd : function() {
                        activate_slide(product_images.activeIndex);
                    }

                });
                var $product_thumbnails = jQuery('.product_thumbnails');

                /**
                 * Move slider on thumbnail click
                 */
                jQuery(document).on('click', '.product_thumbnails .carousel-cell', function(event) {
                    var index = jQuery(event.currentTarget).index();
                    activate_slide(index);
                });

                /**
                 * Link between thumbs & slider
                 */
                function activate_slide(index) {
                    product_images.slideTo(index, 300, false);

                    var $product_thumbnails_cells 			= $product_thumbnails.find('.carousel-cell');
                    var $product_thumbnails_height 			= $product_thumbnails.height();
                    var $product_thumbnails_cells_height 	= $product_thumbnails_cells.outerHeight();

                    $product_thumbnails.find('.is-nav-selected').removeClass('is-nav-selected');

                    var $selected_cell = $product_thumbnails_cells.eq(product_images.activeIndex).addClass('is-nav-selected');

                    var $scrollY = (product_images.activeIndex * $product_thumbnails_cells_height) - ( ($product_thumbnails_height - $product_thumbnails_cells_height) / 2) - 10;

                    $product_thumbnails.animate({
                        scrollTop: $scrollY
                    }, 300);
                }

            }
            if (jQuery(".easyzoom").length ) {

                if ( jQuery(window).width() > 1024 ) {

                    var $easyzoom = jQuery(".easyzoom").easyZoom({
                        loadingNotice: '',
                        errorNotice: '',
                        preventClicks: false,
                        linkAttribute: "href",
                    });

                    var easyzoom_api = $easyzoom.data('easyZoom');
                } else {

                    jQuery(".easyzoom a").click(function(event) {
                        event.preventDefault();
                    });

                }

            }
        },100);
    }
});