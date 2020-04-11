//**All js for woocommerce of theme

(function ($) {
    'use strict';
    $(document).ready(function () {
        //Sidebar control
        $(document).on('click', '.zoo-sidebar-control', function (e) {
            e.preventDefault();
            $(this).toggleClass('active');
            $('.product-sidebar').toggleClass('active');
            if (!$(this).closest('.top-sidebar-layout')[0] || $(window).width() < 992) {
                $('.zoo-mask-close').addClass('sidebar-active active');
            }
            if ($(this).closest('.top-sidebar-layout')[0] && $(window).width() > 992) {
                $('.zoo-top-sidebar.product-sidebar').slideToggle("slow");
            }
        });
        $(document).on('click', '.sidebar-active.active, .close-btn.close-sidebar', function (e) {
            e.preventDefault();
            $('.product-sidebar, .zoo-sidebar-control').removeClass('active');
            $('.zoo-mask-close').removeClass('sidebar-active active');
        });

        $(document).on('click', '.heading-toggle-block', function () {
            $(this).zoo_toggleBlock();
        });
        //Toggle Shop Layout
        $(document).on('click', '.toggle-products-layout-button:not(.active)', function (e) {
            e.preventDefault();
            $('.toggle-products-layout-button.active').removeClass('active');
            $(this).addClass('active');
            if ($(this).data('layout') == 'list') {
                setCookie('zoo_product_layout', 'list');
                $('.zoo-products-shop-loop .products').addClass('list-layout');
                $('.zoo-products-shop-loop .products').removeClass('grid-layout');
            } else {
                deleteCookie('zoo_product_layout');
                $('.zoo-products-shop-loop .products').removeClass('list-layout');
                $('.zoo-products-shop-loop .products').addClass('grid-layout');
            }
        });
        //Scroll effect for Review link when click.
        $(document).on('click', '.woocommerce-review-link', function (e) {
            if ($('.zoo-product-data-tabs.accordion-layout')[0]) {
                if ($('#tab-reviews').closest('.zoo-group-accordion:not(.accordion-active)')[0]) {
                    $('.zoo-group-accordion.accordion-active .wc-tab').hide();
                }
                $('#tab-reviews').closest('.zoo-group-accordion:not(.accordion-active)').find('.tab-heading').trigger('click');
                $('html, body').animate({
                    scrollTop: $('#tab-reviews').offset().top - $('.site-header').height()
                }, 500);
            }
        });

        //Product qty control
        $(document).zoo_CartQuantity('.quantity .qty-nav');
        //Sticky product layout
        zoo_product_sticky_layout();
        //Product Gallery
        zoo_product_gallery();
        //Gallery light box
        zoo_gallery_light_box();
        //Product tabs
        zoo_product_tabs_block();
        //Products carousel
        zoo_products_carousel();
        //Product variation
        zoo_product_variable();
        //Alternative image, change to alt image when hover to product.
        zoo_alternate_image();
        //Js control display form login/register
        zoo_toggle_myacount_form();
        //Coupon code for cart page
        zoo_add_coupon_cart();
        //Sticky add to cart button
        zoo_sticky_add_to_cart();
        //Clever Swatches extend function for theme
        zoo_cleverswatches_function();
        //Count down to end of day
        zoo_countdown_end_day();
        //Extend cart info lightbox.
        zoo_extend_cart_info();
        // Call back count down timer for variable product
        zoo_call_back_countdown();
    });

    // Call back count down timer for variable product
    function zoo_call_back_countdown() {
        $(document).on("show_variation", ".variations_form:not(.cw-form-data)", function () {
            var $this = $('.variations_form [data-countdown="countdown"]');
            if ($this[0]) {
                var $date = $this.data('date').split("-");
                $this.lofCountDown({
                    TargetDate: $date[0] + "/" + $date[1] + "/" + $date[2] + " " + $date[3] + ":" + $date[4] + ":" + $date[5],
                    DisplayFormat: "<div class=\"countdown-times\"><div class=\"day\">%%D%% Days </div><div class=\"hours\">%%H%% Hours </div><div class=\"minutes\">%%M%% Mins </div><div class=\"seconds\">%%S%% Secs </div></div>",
                    FinishMessage: "Expired"
                });
            }
        });
    }

    //Binding Ajax for Ajax event cart and check out
    $(document).on('updated_checkout updated_cart_totals zoo_single_product_cart_added added_to_cart wc_fragment_refresh', function () {
        setTimeout(function () {
            $('.lazy-img').zoo_lazyImg();
        }, 100);
    });
    $(window).on('load', function () {
        $('.widget_shopping_cart .lazy-img').each(function () {
            $(this).attr('src', $(this).data('src'));
        });
    });
    //Binding Ajax for CLN
    if ($('.zoo-ln-filter-form.apply_ajax')[0]) {
        $(document).on('click', '.wrap-next-prev-page a', function (e) {
            e.preventDefault();
            if ($(this).parents('.prev-page')[0]) {
                $('.wrap-drop-down-pagination a.prev').trigger('click');
            } else {
                $('.wrap-drop-down-pagination a.next').trigger('click');
            }
        });
    }
    $(document).on('zoo_ln_before_filter', function (event, response) {
        $('.products').addClass('zoo-ln-loading');
    });
    $(document).on('zoo_ln_after_filter', function (event, response) {
        if ($('.shop-title .total-product').length) {
            $('.shop-title .total-product').html('(' + response.result.total_text + ')');
        }
        var $pages = response.result.html_pagination_content;
        if (!$pages) {
            $('.top-page-pagination .current-page').html('1');
            $('.top-page-pagination .total-page').html('1');
            $('.top-page-pagination .wrap-next-prev-page span').hide();
        } else {
            var current_page = $($pages).find('.current').html();
            $('.top-page-pagination .current-page').html(current_page);
            var total_page = $($pages).find('li:last-child a').html();
            if ($($pages).find('.next ')[0]) {
                total_page = $($pages).find('.next ').parent().prev().find('a').html();
                if (!$('.wrap-next-prev-page .next-page a')[0]) {
                    $('.wrap-next-prev-page .next-page').html($($pages).find('.next').clone());
                }
                $('.wrap-next-prev-page .next-page').show();
            } else {
                $('.wrap-next-prev-page .next-page').hide();
            }
            if ($($pages).find('.prev')[0]) {
                if (!$('.wrap-next-prev-page .prev-page a')[0]) {
                    $('.wrap-next-prev-page .prev-page').html($($pages).find('.prev').clone());
                }
                $('.wrap-next-prev-page .prev-page').show();
            } else {
                $('.wrap-next-prev-page .prev-page').hide();
            }
            $('.top-page-pagination .current-page').html(current_page);
            $('.top-page-pagination .total-page').html(total_page);
        }
        $('.grid-layout.products').zoo_WooSmartLayout();
        setTimeout(function () {
            $('.product-sidebar, .zoo-sidebar-control').removeClass('active');
            $('.zoo-mask-close').removeClass('sidebar-active active');
        }, 100);
        setTimeout(function () {
            //Ajax change layout
            if (typeof $.fn.isotope != 'undefined')
                $('.grid-layout.products.highlight-featured').isotope('reloadItems');
        }, 350);
        if ($(window).width() > 992) {
            $('.zoo-top-sidebar.product-sidebar').slideToggle("slow");
        } else {
            $('html, body').animate({
                scrollTop: $('.zoo-products-shop-loop .products').offset().top
            }, 500);
        }
        $('.products.zoo-ln-loading').removeClass('zoo-ln-loading');
    });

    //After all resource loaded
    $(window).on('load', function () {
        $('.products.highlight-featured .product.featured img').attr('sizes', '');
        var current_width = 0;
        $(window).resize(function () {
            if (current_width != $(window).width()) {
                current_width = $(window).width();
                setTimeout(function () {
                    $('.grid-layout.products').zoo_WooSmartLayout();
                    $('.cvca-products-wrap.advanced-filter .grid-layout.products').zoo_WooSmartLayout();
                }, 300);

                if ($(window).width() < 769) {
                    let this_to_top = 0;
                    $('#site-header .sticker:visible').each(function () {
                        if (!!$(this).data('sticky-height')) {
                            this_to_top = $(this).data('sticky-height') + this_to_top;
                        } else {
                            this_to_top = $(this).height() + this_to_top;
                        }
                    });
                    $('.wrap-top-shop-loop').stick_in_parent({
                        bottoming: false,
                        sticky_class: 'is-sticky',
                        offset_top: this_to_top
                    });
                } else {
                    $('.wrap-top-shop-loop').trigger("sticky_kit:detach");
                }
            }
        }).resize();
        //360 product view
        zoo_product_360_view();
    });

    //After screen resize
    $(window).on('resize', function () {
        if ($(window).width() > 769) {
            if ($('.sticky.zoo-single-product')[0] || $('.images-center.zoo-single-product')[0]) {
                var offset = 0;
                if ($('#site-header .sticky-wrapper')[0]) {
                    offset = $('#site-header .sticky-wrapper').height() + 30;
                }
                $('.wrap-right-single-product').stick_in_parent({offset_top: offset});
                $('.images-center.zoo-single-product .zoo-woo-tabs.zoo-accordion').stick_in_parent();
            }
        }
    }).resize();

    /**
     * Set a cookie
     * @param {String} cname, cookie name, {String} cookie value
     */
    function setCookie(cname, cvalue) {
        document.cookie = cname + "=" + cvalue + "; ";
    }

    /**
     * Delete a cookie
     * @param {String} cname, cookie name
     */
    function deleteCookie(cname) {
        var d = new Date(); //Create an date object
        d.setTime(d.getTime() - (1000 * 60 * 60 * 24)); //Set the time to the past. 1000 milliseonds = 1 second
        var expires = "expires=" + d.toGMTString(); //Compose the expirartion date
        window.document.cookie = cname + "=" + "; " + expires;//Set the cookie with name and the expiration date

    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }


    //Clever Swatches extend Function
    function zoo_cleverswatches_function() {
        $(document).on('cleverswatch_button_select_option', function (event, response) {
            var add_to_cart_button = response.selector;
            add_to_cart_button.closest('.variations_form').find('.zoo-buy-now').addClass('disabled');
        });
        //Bind for button if button is Add to Cart
        $(document).on('cleverswatch_button_add_cart', function (event, response) {
            var add_to_cart_button = response.selector;
            add_to_cart_button.closest('.variations_form').find('.zoo-buy-now').removeClass('disabled');
        });
        //Bind for button if button is Out of stock
        $(document).on('cleverswatch_button_out_stock', function (event, response) {
            var add_to_cart_button = response.selector;
            add_to_cart_button.closest('.variations_form').find('.zoo-buy-now').addClass('disabled');
        });

        $(document).on('cleverswatch_update_gallery', function (event, response) {
            if (!$('#zoo-quickview-lb')[0]) {
                if ($(response.content).find('.wrap-main-product-gallery .woocommerce-product-gallery__image').length > 1) {
                    zoo_product_gallery();
                }
            }
            var $this = $('.variations_form [data-countdown="countdown"]');
            if ($this[0]) {
                var $date = $this.data('date').split("-");
                $this.lofCountDown({
                    TargetDate: $date[0] + "/" + $date[1] + "/" + $date[2] + " " + $date[3] + ":" + $date[4] + ":" + $date[5],
                    DisplayFormat: "<div class=\"countdown-times\"><div class=\"day\">%%D%% Days </div><div class=\"hours\">%%H%% Hours </div><div class=\"minutes\">%%M%% Mins </div><div class=\"seconds\">%%S%% Secs </div></div>",
                    FinishMessage: "Expired"
                });
            }
        });
        $(window).resize(function () {
            if ($(window).width() > 769 && $('.zoo-cw-is-desktop')[0]) {
                $(document).on('mouseenter mouseleave click', '.variations_form .zoo-cw-attribute-option',
                    function () {
                        var $this = $('.variations_form [data-countdown="countdown"]');
                        if ($this[0]) {
                            var $date = $this.data('date').split("-");
                            $this.lofCountDown({
                                TargetDate: $date[0] + "/" + $date[1] + "/" + $date[2] + " " + $date[3] + ":" + $date[4] + ":" + $date[5],
                                DisplayFormat: "<div class=\"countdown-times\"><div class=\"day\">%%D%% Days </div><div class=\"hours\">%%H%% Hours </div><div class=\"minutes\">%%M%% Mins </div><div class=\"seconds\">%%S%% Secs </div></div>",
                                FinishMessage: "Expired"
                            });
                        }
                    });
            }
        }).resize();
    }

    //Sticky product layout
    function zoo_product_sticky_layout() {
        $(window).on('load', function () {
            if (typeof  $.fn.stick_in_parent != 'undefined') {
                var window_width = '';
                $(window).resize(function () {
                    if (window_width != $(window).width()) {
                        window_width = $(window).width();
                        let $stick_content = $('.sticky-content-layout .wrap-single-product-images, .sticky-content-layout .product .wrap-sticky-content-block>.summary');
                        if (window_width > 992) {
                            $stick_content.stick_in_parent({offset_top: $('.site-header').height()});
                        } else {
                            $stick_content.trigger('sticky_kit:detach');
                        }
                    }
                }).resize();
            }
        });
    }

    /**
     * Variable product Form control gallery
     * Apply for case CW not active.
     * */
    function zoo_product_variable() {
        var orginal_image = '';
        var orginal_large_image = '';
        jQuery(document).on("show_variation", "form.variations_form", function (event, variation) {
            if (!$('.zoo-cw-page')[0]) {
                orginal_image = $('.wrap-main-product-gallery .slick-current img').attr('src');
                orginal_large_image = $('.wrap-main-product-gallery .slick-current img').data('src');
                if (variation.image.full_src != '') {
                    var newimg = variation.image.src;
                    var newurl = variation.image.url;
                    zoo_product_variable_gallery(newimg, newurl);
                }
            }
        });
        jQuery(".reset_variations").on("click", function (event) {
            if (!$('.zoo-cw-page')[0]) {
                event.preventDefault();
                zoo_product_variable_gallery(orginal_image, orginal_large_image);
            } else {
                return;
            }
        });
    }

    /**
     * Variable product gallery
     * Apply for case CW not active.
     * Call back when form change.
     * */
    function zoo_product_variable_gallery(orginal_image, orginal_large_image) {
        $('.zoo-product-gallery .slick-current img:not(.zoomImg)').attr('src', orginal_image);
        $('.zoo-product-gallery .slick-current img:not(.zoomImg)').attr('data-src', orginal_image);
        $('.zoo-product-gallery .slick-current img.zoomImg').attr('src', orginal_large_image);
        $('.zoo-product-gallery .slick-current img:not(.zoomImg)').attr('srcset', orginal_image);
        $('.zoo-product-gallery .slick-current  a').attr('href', orginal_large_image);

        if (typeof  $.fn.zoom != 'undefined') {
            $('.wrap-main-product-gallery .woocommerce-product-gallery__image a').trigger('zoom.destroy');
            if ($(window).width() > 768) {
                $('.wrap-main-product-gallery .woocommerce-product-gallery__image a').zoom();
            }
        }
    }

    //Product carousel
    function zoo_products_carousel() {
        $('.products.carousel').each(function () {
            let data = $(this).data('zoo-config');
            if (!!data) {
                let cols = data.cols;
                $(this).slick({
                    slidesToShow: cols,
                    slidesToScroll: 1,
                    rows: 0,
                    rtl: $('body.rtl')[0] ? true : false,
                    swipe: true,
                    prevArrow: '<span class="zoo-carousel-btn prev-item"><i class="zoo-icon-arrow-left"></i></span>',
                    nextArrow: '<span class="zoo-carousel-btn next-item "><i class="zoo-icon-arrow-right"></i></span>',
                    responsive: [
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: cols > 3 ? 3 : cols
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: cols > 2 ? 2 : cols
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 1
                            }
                        }
                    ]
                })
            }
        })
    }

    //Product tabs
    function zoo_product_tabs_block() {
        $('.zoo-product-data-tabs .accordion-active .wc-tab').show();
        $(document).on('click', '.tabs li a', function () {
            $('.zoo-group-accordion.accordion-active').removeClass('accordion-active');
            $($(this).attr('href')).closest('.zoo-group-accordion').addClass('accordion-active');
        });
        $(document).on('click', '.zoo-group-accordion .tab-heading', function () {
            let tab_is_activated = $(this).closest('.accordion-active')[0];
            $('.zoo-product-data-tabs .accordion-active .wc-tab').slideUp();
            $('.zoo-product-data-tabs .accordion-active').removeClass('accordion-active');
            if (!tab_is_activated) {
                $(this).closest('.zoo-group-accordion').toggleClass('accordion-active');
                $(this).next('.wc-tab').slideToggle();

                if ($('.zoo-product-data-tabs .tabs.wc-tabs')[0]) {
                    let id = $(this).next('.wc-tab').attr('id');
                    $('.zoo-product-data-tabs .tabs.wc-tabs .active').removeClass('active');
                    $('.zoo-product-data-tabs .tabs.wc-tabs a[href="#' + id + '"]').parent().addClass('active');
                }
            }
        });
    }

    //Gallery js
    function zoo_product_gallery() {
        //Product gallery slider
        if (typeof  $.fn.slick != 'undefined') {
            let thumb_num = $('.zoo-product-gallery.images').data('columns');
            var window_width = '';
            $(window).resize(function () {
                if (window_width != $(window).width()) {
                    if ($('.zoo-product-gallery:not(.none-slider) .wrap-main-product-gallery:not(.slick-slider)')[0]) {
                        $('.zoo-product-gallery:not(.none-slider) .wrap-main-product-gallery').slick({
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            rows: 0,
                            rtl: $('body.rtl')[0] ? true : false,
                            swipe: true,
                            dots: !$('.wrap-list-thumbnail')[0] ? true : false,
                            asNavFor: $('.wrap-list-thumbnail')[0] ? '.zoo-product-gallery .wrap-list-thumbnail' : '',
                            prevArrow: '<span class="zoo-carousel-btn prev-item"><i class="zoo-icon-arrow-left"></i></span>',
                            nextArrow: '<span class="zoo-carousel-btn next-item "><i class="zoo-icon-arrow-right"></i></span>',
                        });
                    }
                    $('.zoo-product-gallery:not(.none-slider) .wrap-list-thumbnail.slick-slider').slick('unslick');

                    if ($(window).width() > 576) {
                        $('.zoo-product-gallery:not(.none-slider) .wrap-list-thumbnail:not(.slick-slider)').slick({
                            slidesToShow: thumb_num,
                            rows: 0,
                            slidesToScroll: 1,
                            vertical: $('.zoo-product-gallery.vertical-gallery')[0] ? true : false,
                            verticalSwiping: $('.zoo-product-gallery.vertical-gallery')[0] ? true : false,
                            focusOnSelect: true,
                            rtl: $('body.rtl')[0] ? true : false,
                            asNavFor: '.zoo-product-gallery .wrap-main-product-gallery',
                            prevArrow: $('.zoo-product-gallery.vertical-gallery')[0] ? '<span class="zoo-carousel-btn prev-item"><i class="zoo-icon-up"></i></span>' : '<span class="zoo-carousel-btn prev-item"><i class="zoo-icon-arrow-left"></i></span>',
                            nextArrow: $('.zoo-product-gallery.vertical-gallery')[0] ? '<span class="zoo-carousel-btn next-item "><i class="zoo-icon-down"></i></span>' : '<span class="zoo-carousel-btn next-item"><i class="zoo-icon-arrow-right"></i></span>',
                        });
                    } else {
                        $('.zoo-product-gallery:not(.none-slider) .wrap-list-thumbnail:not(.slick-slider)').slick({
                            slidesToShow: thumb_num,
                            rows: 0,
                            slidesToScroll: 1,
                            focusOnSelect: true,
                            rtl: $('body.rtl')[0] ? true : false,
                            asNavFor: '.zoo-product-gallery .wrap-main-product-gallery',
                            prevArrow: '<span class="zoo-carousel-btn prev-item"><i class="zoo-icon-arrow-left"></i></span>',
                            nextArrow: '<span class="zoo-carousel-btn next-item"><i class="zoo-icon-arrow-right"></i></span>',
                        });
                    }

                    if ($(window).width() > 992) {
                        if ($('.zoo-product-gallery.none-slider .wrap-main-product-gallery.slick-initialized')[0]) {
                            $('.zoo-product-gallery.none-slider .wrap-main-product-gallery.slick-initialized').slick('unslick');
                            $('.zoo-product-gallery.none-slider .wrap-list-thumbnail.slick-initialized').slick('unslick');
                        }
                    } else {
                        $('.zoo-product-gallery.none-slider .wrap-main-product-gallery:not(.slick-slider)').slick({
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            rows: 0,
                            rtl: $('body.rtl')[0] ? true : false,
                            swipe: true,
                            asNavFor: $('.wrap-list-thumbnail')[0] ? '.zoo-product-gallery.none-slider .wrap-list-thumbnail' : '',
                            prevArrow: '<span class="zoo-carousel-btn prev-item"><i class="zoo-icon-arrow-left"></i></span>',
                            nextArrow: '<span class="zoo-carousel-btn next-item "><i class="zoo-icon-arrow-right"></i></span>',
                        });
                        $('.zoo-product-gallery.none-slider .wrap-list-thumbnail:not(.slick-slider)').slick({
                            slidesToShow: thumb_num,
                            rows: 0,
                            slidesToScroll: 1,
                            focusOnSelect: true,
                            rtl: $('body.rtl')[0] ? true : false,
                            asNavFor: '.zoo-product-gallery.none-slider .wrap-main-product-gallery',
                            prevArrow: '<span class="zoo-carousel-btn prev-item"><i class="zoo-icon-arrow-left"></i></span>',
                            nextArrow: '<span class="zoo-carousel-btn next-item "><i class="zoo-icon-arrow-right"></i></span>',
                        });
                    }

                    window_width = $(window).width();
                }
            }).resize();
            $(document).on('click', '.wrap-list-thumbnail a', function (e) {
                e.preventDefault();
            });
            $('.zoo-product-gallery.slider-gallery .wrap-main-product-gallery').slick('slickSetOption', 'slidesToShow', thumb_num, false);
            let responsive = [{breakpoint: 768, settings: {slidesToShow: 2}}, {
                breakpoint: 576,
                settings: {slidesToShow: 1}
            }];
            $('.zoo-product-gallery.slider-gallery .wrap-main-product-gallery').slick('slickSetOption', 'responsive', responsive, true);
        }
        //Gallery Zoom
        var w_width = '';
        $(window).resize(function () {
            if (w_width != $(window).width()) {
                if (typeof  $.fn.zoom != 'undefined') {
                    $('.wrap-main-product-gallery .woocommerce-product-gallery__image a').trigger('zoom.destroy');
                    if ($(window).width() > 768) {
                        $('.wrap-main-product-gallery .woocommerce-product-gallery__image a').zoom();
                    }
                }
                w_width = $(window).width();
            }
        }).resize();
    }

    //Gallery light box
    function zoo_gallery_light_box() {
        $(document).on('click', '.wrap-main-product-gallery .woocommerce-product-gallery__image a', function (e) {
            e.preventDefault();
            if (typeof PhotoSwipe != 'undefined') {
                var pswpElement = $('.pswp')[0],
                    items = $(this).zooGetGalleryItems(),
                    c_index = $(this).parent().index();
                if ($(this).closest('.slick-slide')[0]) {
                    if ($('.carousel.zoo-single-product')[0]) {
                        var total_sl_active = $('.wrap-single-image .slick-active').length;
                        if (total_sl_active == 0) {
                            c_index = $(this).closest('.slick-slide').index();
                        }
                        else {
                            c_index = $(this).closest('.slick-slide').index() - total_sl_active - 1;
                        }
                    }
                    else {
                        c_index = $(this).closest('.slick-slide').index() - 1;
                    }

                }
                var options = {
                    index: c_index,
                    shareEl: false,
                    closeOnScroll: false,
                    history: false,
                    hideAnimationDuration: 0,
                    showAnimationDuration: 0
                };
                // Initializes and opens PhotoSwipe.
                var photoswipe = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
                photoswipe.init();
            }
        });
    }

    //Alternative Image
    function zoo_alternate_image() {
        $(document).on('mouseenter', '.products .product', function () {
            let $this = $(this).find('.sec-img');
            if (!!$this.data('src') && $this.attr('src') != $this.data('src')) {
                $this.attr('src', $this.data('src'));
                $this.attr('srcset', $this.data('srcset'));
            }
        });
    }

    //Toggle display my aocount form
    function zoo_toggle_myacount_form() {
        $(document).on('click', '.toggle-register-form, .toggle-form-button.toggle-register', function (e) {
            e.preventDefault();
            $('#customer_login .u-column2').slideDown();
            $('#customer_login .u-column1').slideUp();
            $('.toggle-form-block-register').slideUp();
            $('.toggle-form-block-login').slideDown();
        });
        $(document).on('click', '.toggle-login-form, .toggle-form-button.toggle-login', function (e) {
            e.preventDefault();
            $('#customer_login .u-column2').slideUp();
            $('#customer_login .u-column1').slideDown();
            $('.toggle-form-block-register').slideDown();
            $('.toggle-form-block-login').slideUp();
        });

    }

    //Coupon code for cart page
    function zoo_add_coupon_cart() {
        $(document).on('click', '.content-toggle-block .button.apply-coupon', function () {
            $('.woocommerce-cart-form #coupon_code').attr('value', $('.content-toggle-block input[name*=coupon_code]').val());
            $('.woocommerce-cart-form .button[name*=apply_coupon]').trigger('click');
        });
    }

    //Stick add to cart button
    function zoo_sticky_add_to_cart() {
        $(window).on('load', function () {
            if ($('.zoo-sticky-add-to-cart')[0] && $('.summary form.cart')[0]) {
                jQuery(window).on("scroll", function () {
                    var footer = 0;
                    if ($('footer.site-footer')[0]) {
                        footer = $('footer.site-footer').offset().top;
                    } else if ($('footer.cafe-site-footer')[0]) {
                        footer = $('footer.cafe-site-footer').offset().top;
                    }
                    if ($(window).scrollTop() > $('.summary form.cart').height() + $('.summary form.cart').offset().top && $(window).scrollTop() + $(window).height() < footer) {
                        $('.zoo-sticky-add-to-cart:not(.active)').addClass('active')
                    } else {
                        $('.zoo-sticky-add-to-cart.active').removeClass('active')
                    }
                });
                $(document).on('click', '.zoo-sticky-add-to-cart .button-sticky-add-to-cart', function (e) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: $('.summary').offset().top
                    }, 700);
                });
            }
        });
    }

    //360 product view
    function zoo_product_360_view() {
        if ($('.product-image-360-view')[0]) {
            let $wrap = $('.product-image-360-view .zoo-wrap-img-360-view');
            let width = $wrap.find('.zoo-wrap-content-view').width();
            let height = $wrap.find('.zoo-wrap-content-view').height();

            if (parseInt(width) > $wrap.find('.zoo-wrap-content-view').width()) {
                let res = parseInt(width / height);
                width = $(this).find('.zoo-wrap-img-360-view').width();
                height = parseInt(parseInt(width) / res);
            }
            $(document).on('click', '.product-360-view-control', function () {
                $('.product-image-360-view, .product-extended-button').toggleClass('active');
            });
            $(document).on('click', '.mask-product-360-view', function () {
                $('.product-image-360-view, .product-extended-button').removeClass('active');
            });
            $wrap.find('.zoo-wrap-content-view').spritespin({
                // path to the source images.
                source: $('.product-image-360-view').attr('data-zoo-config').split(','),
                width: width,  // width in pixels of the window/frame
                height: height,  // height in pixels of the window/frame
                animate: false
            });

            var api = $wrap.find('.zoo-wrap-content-view').spritespin("api");
            // call function next/previous frame when click to button next/previous
            $wrap.find('.zoo-control-view:not(.zoo-center)').on('click', function () {
                if ($wrap.hasClass('zoo-prev-item')) {
                    api.prevFrame();
                } else {
                    api.nextFrame();
                }
            });
        }
    }

    function zoo_countdown_end_day() {
        if ($('.zoo-get-order-notice .end-of-day')[0]) {
            var timezone = $('.end-of-day').data('timezone');
            var day = new Date();
            var utc = day.getTime() + (day.getTimezoneOffset() * 60000);
            let d = new Date(utc + (3600000 * timezone)),
                duration = 60 * (60 - d.getMinutes());
            let timer = duration, minutes;
            let hours = (23 - d.getHours());
            hours = hours < 10 ? '0' + hours : hours;
            let label_h = $('.zoo-get-order-notice .end-of-day').data('hours');
            let label_m = $('.zoo-get-order-notice .end-of-day').data('minutes');
            setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                minutes = minutes < 10 ? "0" + minutes : minutes;
                $('.zoo-get-order-notice .end-of-day').text(hours + ' ' + label_h + ' ' + minutes + ' ' + label_m);
                if (--timer < 0) {
                    timer = duration;
                }
            }, 1000);
        }
    }


    //Extend cart info lightbox
    function zoo_extend_cart_info() {
        if ($('.summary>.product_title').text() != '') {
            $('.content-popup-page .wpcf7-hidden[name="product"]').val($('.summary>.product_title').text());
        }

        $(document).on('click', '.zoo-extend-cart-info-item >a', function (e) {
            e.preventDefault();
            $('.wrap-content-popup-page').addClass('active');
            $('.close-zoo-extend-cart-info').addClass('active');
            $('.' + $(this).data('target')).addClass('active');
        });
        $(document).on('click', '.close-zoo-extend-cart-info, .wrap-content-popup-page .close-popup-page', function () {
            $('.wrap-content-popup-page.active').removeClass('active');
            $('.close-zoo-extend-cart-info.active').removeClass('active');
            $('.content-popup-page.active').removeClass('active');
        });
    }

    jQuery.fn.extend({
        zoo_WooSmartLayout: function (default_cols) {
            if (jQuery(this)[0]) {
                jQuery(this).each(function () {
                    if (!!$(this).data('zoo-config')) {
                        //Prepare all default config;
                        var $this = $(this);
                        var data, cols, table_cols, mobile_cols, active_featured, wrap_width, res, product_width, img_w;
                        data = $this.data('zoo-config');
                        cols = parseInt(data.cols);
                        active_featured = parseInt(!!data.highlight_featured ? data.highlight_featured : 0);
                        if (!!cols && !!active_featured) {
                            wrap_width = Math.floor($this.width());
                            if (!!default_cols) {
                                cols = default_cols;
                            } else {
                                table_cols = parseInt(!!data.tablet ? data.tablet : 2);
                                mobile_cols = parseInt(!!data.mobile ? data.mobile : 1);
                                //*End Prepare all default config;
                                //Reset column layout and control grid follow screen size
                                if ($(window).width() <= 577) {
                                    cols = mobile_cols;
                                } else if ($(window).width() < 993 && $(window).width() > 577) {
                                    cols = table_cols;
                                }
                            }
                            //* End Reset column layout and control grid follow screen size
                            product_width = Math.floor(wrap_width / cols);

                            if (cols > 1) {
                                $this.find('.product.featured').outerWidth(product_width * 2);
                                $this.find('.product:not(.featured)').outerWidth(product_width);
                            }
                            setTimeout(function () {
                                if (cols > 1) {
                                    var height = 0;
                                    $this.find('.product:not(.featured)').each(function () {
                                        if ($(this).outerHeight(true) > height) {
                                            height = $(this).outerHeight(true);
                                        }
                                    });
                                    $this.find('.product:not(.featured)').outerHeight(height);
                                    if ($(window).width() > 768) {
                                        $this.find('.product.featured').outerHeight(height * 2);
                                    } else {
                                        $this.find('.product.featured').outerHeight('auto');
                                    }
                                    $this.isotope({
                                        layoutMode: 'masonry',
                                        itemSelector: '.product',
                                        percentPosition: true,
                                        masonry: {
                                            columnWidth: '.product:not(.featured)'
                                        }
                                    });
                                }
                            }, 700);
                        }
                    }
                });
            }
        },
        zoo_toggleBlock: function () {
            $(this).next('.content-toggle-block').slideToggle();
        },
        //Product Qty
        zoo_CartQuantity: function (target) {
            if ($(this)[0]) {
                $(this).on("click", target, function () {
                    let qty = jQuery(this).parents('.quantity').find('input.qty');
                    let val = parseInt(qty.val());
                    if ($(this).hasClass('increase')) {
                        qty.val(val + 1);
                    }
                    else {
                        if ($(this).closest('.woocommerce-grouped-product-list')[0]) {
                            if (val > 0) {
                                qty.val(val - 1);
                            }
                        } else {
                            if (val > 1) {
                                qty.val(val - 1);
                            }
                        }
                    }
                    qty.trigger('change');
                });
            }
        },
        //Push product images to list
        zooGetGalleryItems: function () {
            var $slides, items = [];
            if ($('.zoo-product-gallery.none-slider')[0]) {
                $slides = this.parents('.wrap-main-product-gallery').find('.woocommerce-product-gallery__image');
            }
            else {
                $slides = this.parents('.wrap-main-product-gallery').find('.woocommerce-product-gallery__image:not(.slick-cloned)');
            }
            if ($slides.length > 0) {
                $slides.each(function (i, el) {
                    var img = $(el).find('img'),
                        large_image_src = img.attr('data-large_image'),
                        large_image_w = img.attr('data-large_image_width'),
                        large_image_h = img.attr('data-large_image_height'),
                        item = {
                            src: large_image_src,
                            w: large_image_w,
                            h: large_image_h,
                            title: img.attr('title')
                        };
                    items.push(item);
                });
            }
            return items;
        }
    });
})
(jQuery);