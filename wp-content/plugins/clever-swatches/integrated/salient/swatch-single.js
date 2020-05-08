//Add this js to bottom file salient/js/nectar-single-product.js before close tag }); line 791

jQuery(document).bind('cleverswatch_update_gallery', function (event, response) {
    var imagesDiv = jQuery('#product-' + response.product_id).find('.single-product-main-image');
    if (jQuery(imagesDiv).length) {
        imagesDiv.html(response.content);
    }
    setTimeout(function () {
        //init easyZoom
        if(!$('body').hasClass('mobile') && !$().zoom ){
            var $easyzoom = $(".easyzoom").easyZoom({
                preventClicks: true,
                loadingNotice: ' ',
                errorNotice: ' '
            });

            if($('.easyzoom').length > 0) {
                var easyzoom_api = $easyzoom.data('easyZoom');

                $("table.variations").on('change', 'select', function() {
                    easyzoom_api.teardown();
                    easyzoom_api._init();
                });
            }
        }

        if($('[data-gallery-style="left_thumb_sticky"]').length > 0) {
            nectarWooProdSliderLiveOrDie();
            leftAlignedRelationsInit();
            $(window).on('resize',nectarWooProdSliderLiveOrDie);
            leftAlignedZoomIcon();
        } else if($('[data-gallery-style="ios_slider"]').length > 0) {
            nectarWooProdSliderInit();
        }

        if($('.slider').length > 0) {

            $startingImage = ($('.slide div a:first > img').length > 0) ? $('.slide div a:first > img').attr('src') : '';
            $startingImageLink = ($('.slide div a:first').length > 0) ? $('.slide div a:first').attr('href') : '';
            $startingImageThumb = ($('.slider > .thumb:first .thumb-inner img').length > 0) ? $('.slider > .thumb:first .thumb-inner img').attr('src') : $startingImage;

            $('select[name*="attribute_"]').blur(function(){

                var $that = $(this);
                var attr_data = $('.variations_form').data('product_variations');

                if($that.val().length > 0) {

                    //give woo time to update img
                    setTimeout(function(){

                        $(attr_data).each(function(i, el){

                            if(el.image && el.image.src) {

                                if(el.image.src == $('.slide div a:first > img').attr('src')){

                                    if(el.image.url){
                                        $('.slide div a:first').attr('href',el.image.url);
                                        $('.slide div a:first > img').attr('src',el.image.src);

                                        if(el.image.gallery_thumbnail_src) {
                                            $('.product-thumbs .flickity-slider > .thumb:first-child img, .product-thumbs .slider > .thumb:first-child img').attr('src',el.image.gallery_thumbnail_src).removeAttr('srcset');

                                            //left aligned
                                            if($('[data-gallery-style="left_thumb_sticky"]').length > 0 && $('body.mobile').length == 0) {
                                                nectar_scrollToY(0, 700, 'easeInOutQuint');
                                            }
                                            //non left aligned
                                            else {
                                                $thumbProdSlider.flickity( 'selectCell', 0 );
                                                $mainProdSlider.flickity( 'selectCell', 0 );
                                                $mainProdSlider.flickity( 'resize' );
                                            }

                                            //update zoom
                                            if($().zoom) {
                                                if( $('[data-gallery-style="left_thumb_sticky"]').length > 0 && $('body.mobile').length == 0) {
                                                    initZoomForTarget($('.product-slider .slider > .slide:first-child'));
                                                }
                                                else if( $('[data-gallery-style="ios_slider"]').length > 0 ) {
                                                    initZoomForTarget($('.product-slider .flickity-slider > .slide:first-child'));
                                                }
                                            }

                                        }

                                    } // if found img url

                                } // if the sources match

                            } else {

                                //pre 3.0
                                if(el.image_src == $('.slide div a:first > img').attr('src')){

                                    if(el.image_link){
                                        $('.slide div a:first').attr('href',el.image_link);
                                        $('.slide div a:first > img').attr('src',el.image_src);
                                        $('.slider > .thumb:first .thumb-inner img').attr('src',el.image_src).removeAttr('srcset');


                                    }
                                }

                            }


                        });

                    },30);

                } else {

                    $('.slide div a:first').attr('href',$startingImageLink);
                    $('.slide div a:first > img').attr('src',$startingImage);
                    $('.product-thumbs .flickity-slider > .thumb:first-child img, .product-thumbs .slider > .thumb:first-child img').attr('src',$startingImageThumb).removeAttr('srcset');

                    //resize
                    if($('[data-gallery-style="left_thumb_sticky"]').length > 0 && $('body.mobile').length == 0) {

                    } else {
                        $mainProdSlider.flickity( 'resize' );
                    }

                    //update zoom
                    if($().zoom) {
                        if( $('[data-gallery-style="left_thumb_sticky"]').length > 0 && $('body.mobile').length == 0) {
                            initZoomForTarget($('.product-slider .slider > .slide:first-child'));
                        }
                        else if( $('[data-gallery-style="ios_slider"]').length > 0 ) {
                            initZoomForTarget($('.product-slider .flickity-slider > .slide:first-child'));
                        }
                    }

                }

            });


        }

        if( $().theiaStickySidebar && $('.product[data-gallery-style="left_thumb_sticky"] .product-slider .slide').length > 0 ) {

            //wrap for sticky
            var prodImg = $('.single-product .product[data-gallery-style="left_thumb_sticky"] > .single-product-main-image').detach();
            if($('.product[data-gallery-style="left_thumb_sticky"][data-tab-pos="in_sidebar"]').length > 0) {
                var prodDesc = $('.single-product .product[data-gallery-style="left_thumb_sticky"] > .single-product-summary').detach();
            } else {
                var prodDesc = $('.single-product .product[data-gallery-style="left_thumb_sticky"] > .summary.entry-summary').detach();
            }

            $('.single-product .product[data-gallery-style="left_thumb_sticky"]').prepend('<div class="nectar-sticky-prod-wrap" />');
            $('.single-product .product[data-gallery-style="left_thumb_sticky"] .nectar-sticky-prod-wrap').append(prodImg).append(prodDesc);

            //init sticky
            productStickySS();
        }
    },300);
});