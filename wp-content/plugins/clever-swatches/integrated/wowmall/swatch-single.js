//swatches gallery js
//Unminify file wc-single-product-gallery.min.js on folder \themes\wowmall\assets\js\min by copy content file wc-single-product-gallery.js
// Put js below after line 142
$(document).bind('cleverswatch_update_gallery', function (event, response) {
    thumbsSwiper.destroy();
    imagesSwiper.destroy();
    setTimeout(function () {
        thumbsSwiper = new Swiper( '#gallery-thumbs', {
            direction:                    'vertical',
            slidesPerView:                'auto',
            nextButton:                   '#next-thumbs',
            prevButton:                   '#prev-thumbs',
            preloadImages:                true,
            lazyLoading:                  true,
            lazyLoadingInPrevNext:        true,
            lazyLoadingOnTransitionStart: true,
            lazyLoadingInPrevNextAmount:  9999,
            spaceBetween:                 11
        } ),
            effect       = 'undefined' !== typeof singleSwipeEffect ? singleSwipeEffect : 'slide';
        imagesSwiper = new Swiper( '#gallery-images > .swiper-container', {
            nextButton:                   '#next-images',
            prevButton:                   '#prev-images',
            effect:                       effect,
            preloadImages:                false,
            lazyLoading:                  true,
            lazyLoadingInPrevNext:        true,
            lazyLoadingOnTransitionStart: true,
            cube:                         {
                shadow:       false,
                slideShadows: true,
                shadowOffset: 20,
                shadowScale:  0.94
            },
            onInit:                       function ( swiper ) {
                change_slide( swiper );
            },
            onSlideChangeStart:           function ( swiper ) {
                change_slide( swiper );
            }
        } );
    },300);
});
/* Swatches lightbox gallery
// Unminify file single-product-lightbox.min.js on folder \themes\wowmall\assets\js\min by copy content file single-product-lightbox.js
// Put js below after line 329
**/
$(document).bind('cleverswatch_update_gallery', function (event, response) {
    setTimeout(function () {
        singleProductLightbox=cw_singleProductLightbox;
        init_lightbox();
    }, 500);
});