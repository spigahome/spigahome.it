//**All config js of theme
(function ($) {
    'use strict';
    jQuery(document).ready(function ($) {
        //Full width media for block
        MediaFullWidth($('.media-block iframe'));
        //Comment form js
        zoo_comment_form_field();

        //Back to Top
        zoo_back_to_top();

        // Fixes rows in RTL
        function zoo_fix_vc_full_width_row() {
            var $elements = jQuery('[data-vc-full-width="true"]');
            jQuery.each($elements, function () {
                var $el = jQuery(this);
                $el.css('right', $el.css('left')).css('left', '');
            });
        }

        jQuery(document).on('vc-full-width-row', function () {
            if ($('body.rtl')[0]) {
                zoo_fix_vc_full_width_row();
            }
        });
        // Run one time because it was not firing in Mac/Firefox and Windows/Edge some times
        zoo_fix_vc_full_width_row();

        $(document).on('click','.nav-button',function () {
           $(this).parent().toggleClass('menu-active');
        });
        $(document).on('click','.button-close-nav, .close-nav',function () {
           $('.menu-active').removeClass('menu-active');
        });
        //Mobile Menu
        $(document).on('click', '.menu-item:not(.is-mega-menu) .zoo-icon-down', function (e) {
            if($(window).width()<1024.98) {
                if (e.target !== this)
                    return;
                else {
                    e.preventDefault();
                    $(this).toggleClass('active');
                    $(this).closest('.menu-item:not(.is-mega-menu)').children('ul').slideToggle();
                    if (!$(this).hasClass('active')) {
                        $(this).closest('.menu-item:not(.is-mega-menu)').find('ul').slideUp();
                        $(this).closest('.menu-item:not(.is-mega-menu)').find('.active').removeClass('active');
                    }
                }
            }
        });
        $(document).on('click', '.is-mega-menu>a>.zoo-icon-down', function (e) {
            if($(window).width()<1024.98) {
                if (e.target !== this)
                    return;
                else {
                    e.preventDefault();
                    $(this).toggleClass('active');
                    $(this).closest('.menu-item').children('.mega-menu-content').slideToggle();
                    if (!$(this).hasClass('active')) {
                        $(this).closest('.menu-item').find('.mega-menu-content').slideUp();
                        $(this).closest('.menu-item').find('.active').removeClass('active');
                    }
                }
            }
        });
        $(document).on('click', '.menu-item-mega .zoo-icon-down', function (e) {
            if($(window).width()<1024.98) {
                if (e.target !== this)
                    return;
                else {
                    e.preventDefault();
                    $(this).toggleClass('active');
                    $(this).closest('.menu-item-mega').children('.mega-menu-submenu').slideToggle();
                    if (!$(this).hasClass('active')) {
                        $(this).closest('.menu-item-mega').find('.mega-menu-submenu').slideUp();
                        $(this).closest('.menu-item-mega').find('.active').removeClass('active');
                    }
                }
            }
        });
        var current_width = 0;
        $(window).resize(function () {
            if (current_width != $(window).width()) {
                current_width = $(window).width();
                //Full width embed
                $('.single .post-detail iframe, .wp-block-embed__wrapper iframe').each(function () {
                    let width,height;
                    width=!!$(this).attr('width')?$(this).attr('width'):$(this).width();
                    height=!!$(this).attr('height')?$(this).attr('height'):$(this).height();
                    let ration = height / width;
                    let wrap_width = $(this).parent().width();
                    $(this).width(wrap_width);
                    $(this).height(wrap_width * ration);
                });
                //Fix menu out of screen.
                $('.pos-left').removeClass('pos-left');
                $('.primary-menu .sub-menu, .primary-menu .dropdown-submenu, .primary-menu .cmm-sub-container').each(function () {
                    if (current_width < parseInt($(this).offset()['left'] + $(this).width())) {
                        $(this).addClass('pos-left');
                    }
                });
                //Remove class active of off canvas when window resize.
                $('.mask-off-canvas').removeClass('active');
                $('.header-off-canvas-sidebar').removeClass('active');

            }
        }).resize();
        if (typeof  $.fn.slick != 'undefined') {
            $('.post-slider').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                rows: 0,
                rtl: $('body.rtl')[0] ? true : false,
                swipe: true,
                centerPadding: '120px',
                prevArrow: '<span class="zoo-carousel-btn prev-item"><i class="cs-font clever-icon-arrow-left-5"></i></span>',
                nextArrow: '<span class="zoo-carousel-btn next-item "><i class="cs-font clever-icon-arrow-right-5"></i></span>',
                centerMode: true,
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            centerPadding: '40px',
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            arrows: false,
                            centerPadding: '0',
                        }
                    }
                ]
            });
        }
        //Add arrow toggle for Widget categories
        if ($('.widget_categories, .widget_nav_menu, .widget_pages')[0]) {
            $('.widget_categories, .widget_nav_menu, .widget_pages').each(function () {
                $(this).find('.children, .sub-menu').parent().append('<span class="cs-font clever-icon-down toggle-view"></span>');
            });
            $(document).on('click', 'span.toggle-view', function () {
                $(this).toggleClass('active');
                $(this).parent().find(' > .children').slideToggle();
                $(this).parent().find('>.sub-menu').slideToggle();
            });
        }

        // Append text direction switcher button
        if (zooThemeSettings.enable_dev_mode === '1') {
            var $body = $(document.body),
                queryMark = null,
                currentUrl = window.location.href.replace(/[&|\?]text-direction=\S{3}/g, '');

            if (!$('#zoo-theme-dev-actions').length) {
                $body.append('<div id="zoo-theme-dev-actions"></div>');
            }

            if (currentUrl.indexOf('?') > -1) {
                queryMark = '&';
            } else {
                queryMark = '?';
            }

            if (zooThemeSettings.isRtl || $body.hasClass('rtl')) {
                $('#zoo-theme-dev-actions').append('<a class="button action-link" href="' + currentUrl + queryMark + 'text-direction=ltr">LTR</a>');
            } else {
                $('#zoo-theme-dev-actions').append('<a class="button action-link" href="' + currentUrl + queryMark + 'text-direction=rtl">RTL</a>');
            }
        }

        // Sticky Author Bio of Author template
        $(window).on('load', function () {
            if (typeof  $.fn.stick_in_parent != 'undefined') {
                var window_width = '';
                $(window).resize(function () {
                    if (window_width != $(window).width()) {
                        window_width = $(window).width();
                        if (window_width > 768) {
                            $('.wrap-post-author .post-author').stick_in_parent({offset_top: 50});
                        } else {
                            $('.wrap-post-author .post-author').trigger('sticky_kit:detach');
                        }
                    }
                }).resize();
            }
        });
    });

    //Comment form effect when focus
    function zoo_comment_form_field() {
        //Comment form effect
        $(document).on('focusin', '.wrap-text-field input, .wrap-text-field textarea', function () {
            $(this).parents('.wrap-text-field').addClass('focus');
        });
        $(document).on('focusout', '.wrap-text-field input, .wrap-text-field textarea', function () {
            if ($(this).val() == '')
                $(this).parents('.wrap-text-field').removeClass('focus');
        });
        $('.wrap-text-field input, .wrap-text-field textarea').each(function () {
            if ($(this).val() != '')
                $(this).parents('.wrap-text-field').addClass('focus');
        });
    }

    $(window).on('load', function () {
        $('#page-load').addClass('deactive');
        $('.lazy-img').zoo_lazyImg();
    });

    jQuery.fn.extend({
        //Lazy Img Config
        zoo_lazyImg: function () {
            if ($(this)[0]) {
                $(this).not('.sec-img, .loaded').parent().addClass('loading');
                if(typeof deferimg !="undefined"){
                    deferimg(this.selector+':not(.sec-img)', 0, 'loaded',
                        function () {
                        $(this).closest('.loading').removeClass('loading');
                    });
                }
            }
        },
        ActiveScreen: function () {
            var itemtop, windowH, scrolltop;
            itemtop = $(this).offset().top;
            windowH = $(window).height();
            scrolltop = $(window).scrollTop();
            if (itemtop < scrolltop + windowH * 2 / 3) {
                return true;
            }
            else {
                return false;
            }
        },

    });
    var MediaFullWidth = function (selector) {
        var current_width = 0;
        $(window).resize(function () {
            if (current_width != $(window).width()) {
                current_width = $(window).width();
                selector.each(function () {
                    let aspectRatio = $(this).height() / $(this).width();
                    $(this).removeAttr('height').removeAttr('width');
                    let newWidth = jQuery(this).parent().width();
                    $(this).width(newWidth).height(newWidth * aspectRatio);
                });
            }
        }).resize();
    };

    //Back to top function
    function zoo_back_to_top() {
        $(window).on('load',function () {
            if ($('#zoo-back-to-top')[0]) {
                let $toTopButton = $('#zoo-back-to-top');
                jQuery(window).on("scroll", function () {
                    if ($(window).scrollTop() > 100) {
                        $toTopButton.addClass('active')
                    } else {
                        $toTopButton.removeClass('active')
                    }
                });
                $(document).on('click', '#zoo-back-to-top', function (e) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: 0
                    }, 700);
                });
            }
        });
    }


    $(document).on('click', '.cvca-button', function (e) {
        let target = $(this).attr('href');
        if ($(target)[0]) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $(target).offset().top - $('.site-header').height() / 2
            }, 800);
        }
    });
})(jQuery);