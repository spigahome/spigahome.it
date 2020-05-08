(function ($) {
    'use strict';
    $(document).ready(function () {
        /*Clever Swatches gallery*/
        cw_product_gallery();

        function cw_product_gallery() {
            var icon_up = '<svg height="40px" tyle="enable-background:new 0 0 40 40;" version="1.1" viewBox="0 0 512 512" width="40px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><polygon points="396.6,352 416,331.3 256,160 96,331.3 115.3,352 256,201.5 "/></svg>';
            var icon_down = '<svg height="40px" tyle="enable-background:new 0 0 40 40;" version="1.1" viewBox="0 0 512 512" width="40px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><polygon points="396.6,160 416,180.7 256,352 96,180.7 115.3,160 256,310.5 "/></svg>';
            var icon_prev = '<svg height="40px" tyle="enable-background:new 0 0 40 40;" version="1.1" viewBox="0 0 512 512" width="40px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><polygon points="352,115.4 331.3,96 160,256 331.3,416 352,396.7 201.5,256 "/></svg>';
            var icon_next = '<svg height="40px" tyle="enable-background:new 0 0 40 40;" version="1.1" viewBox="0 0 512 512" width="40px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><polygon points="160,115.4 180.7,96 352,256 180.7,416 160,396.7 310.5,256 "/></svg>';
            if ($('.cw-product-gallery')[0]) {
                $('.cw-product-gallery .cw-product-gallery-main').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    rtl: $('body.rtl')[0] ? true : false,
                    rows: 0,
                    swipe: true,
                    asNavFor: '.cw-product-gallery .cw-product-gallery-thumbs',
                    prevArrow: '<span class="cw-product-gallery-btn prev-item">' + icon_prev + '</span>',
                    nextArrow: '<span class="cw-product-gallery-btn next-item ">' + icon_next + '</span>',
                });
                var columns = $('.cw-product-gallery .cw-product-gallery-thumbs').data('thumb-columns');
                var vertical = $('.cw-product-gallery.vertical')[0] ? true : false;
                let next = !vertical ? icon_prev : icon_up;
                let prev = !vertical ? icon_next : icon_down;
                $('.cw-product-gallery .cw-product-gallery-thumbs').slick({
                    slidesToShow: columns,
                    slidesToScroll: 1,
                    rtl: $('body.rtl')[0] ? true : false,
                    rows: 0,
                    focusOnSelect: true,
                    vertical: vertical,
                    verticalSwiping: vertical,
                    asNavFor: '.cw-product-gallery .cw-product-gallery-main',
                    prevArrow: '<span class="cw-product-gallery-btn prev-item">' + next + '</span>',
                    nextArrow: '<span class="cw-product-gallery-btn next-item ">' + prev + '</span>',
                });
                //Gallery Zoom
                var w_width = '';
                $(window).resize(function () {
                    if (w_width != $(window).width()) {
                        if (typeof  $.fn.zoom != 'undefined') {
                            $('.cw-product-gallery .cw-product-gallery-main .woocommerce-product-gallery__image a').trigger('zoom.destroy');
                            if ($(window).width() > 768) {
                                $('.cw-product-gallery .cw-product-gallery-main .woocommerce-product-gallery__image a').zoom();
                            }
                        }
                        w_width = $(window).width();
                    }
                }).resize();
            }
        }

        /*Tooltip*/
        if ($('.allow-tooltip')[0] && typeof tippy !== 'undefined') {
            tippy('.allow-tooltip.variations_form .zoo-cw-type-color .zoo-cw-attr-item, .allow-tooltip.variations_form .zoo-cw-type-image .zoo-cw-attr-item', {
                arrow: true,
                animation: 'fade',
                dynamicTitle: true
            });
        }

        /*End Clever Swatches gallery*/
        function cw_findMatchVariation(product_variations, check_options) {
            var match_variation = false;
            $.each(product_variations, function (key, value) {
                var product_variation = value;
                var attribute_case = product_variation.attributes;
                var count_check = Object.keys(check_options).length;

                $.each(check_options, function (key, value) {
                    if ((attribute_case[key] == "") || (value == attribute_case[key])) {
                        count_check--;
                    }
                });
                if (count_check <= 0) {
                    match_variation = value;
                    // break jquery each loop
                    return false;
                }
                //}
            });
            return match_variation;
        }

        function cw_runFilter(product_variations, selected_options, attribute_rows, disable_class, enable_class) {
            attribute_rows.each(function () {
                var current_attribute_row = $(this);
                var attribute_name = current_attribute_row.data('group-attribute');
                var check_options = {};
                check_options = $.extend({}, selected_options);
                var attribute_display_type = $(this).data('attribute-display-type');
                if (attribute_display_type == 'default') {
                    if (disable_class == 'unavailable') { //only run filter on click event
                        //this for select type option
                        $(this).find('select.zoo-cw-attribute-select option').each(function () {
                            var option_value = $(this).val();
                            if (option_value != '') {
                                var option_text = $(this).text();
                                var new_text = option_text.replace(' * (Not suitable)', '');
                                check_options[attribute_name] = option_value;
                                if (cw_findMatchVariation(product_variations, check_options, true)) {
                                    $(this).text(new_text);
                                } else {
                                    $(this).text(new_text + ' * (Not suitable)');
                                }
                            }
                        });
                    }
                } else {
                    //this for other type option
                    var options = $(this).find('.zoo-cw-attribute-option');
                    options.each(function () {
                        var option = $(this);
                        var option_value = $(this).data('attribute-option');
                        check_options[attribute_name] = option_value;
                        if (cw_findMatchVariation(product_variations, check_options, true)) {
                            option.addClass(enable_class).removeClass(disable_class);
                        } else {
                            option.addClass(disable_class).removeClass(enable_class);
                        }
                    });
                }
            });
        }

        function cw_processSelectedOption(product_variations, selected_options, form_add_to_cart) {
            var old_variation_id = form_add_to_cart.find('input[name=old_variation_id]').val();
            var gallery_enabled = form_add_to_cart.data('gallery_enabled');
            var add_to_cart_button = form_add_to_cart.find('.single_add_to_cart_button:not(.zoo-buy-now)');
            var variation_id = 0;

            var match_variation = cw_findMatchVariation(product_variations, selected_options);
            if (match_variation) {
                variation_id = match_variation.variation_id;
            }
            //trigger default function of woocommerce
            if (gallery_enabled) {
                if (variation_id != old_variation_id) {
                    form_add_to_cart.find('input[name=variation_id]').val(variation_id);
                    form_add_to_cart.find('input[name=old_variation_id]').val(variation_id);

                    if (variation_id != 0) {
                        if (match_variation.is_in_stock && match_variation.is_purchasable) {
                            $(document).trigger('cleverswatch_button_add_cart', {
                                "selector": add_to_cart_button
                            });
                        } else {
                            $(document).trigger('cleverswatch_button_out_stock', {
                                "selector": add_to_cart_button
                            });
                        }
                        var template = wp.template('variation-template');
                        var template_html = template({
                            variation: match_variation
                        });
                        form_add_to_cart.find('.woocommerce-variation.single_variation').html(template_html).show();

                        var product_id = form_add_to_cart.data('product_id');
                        $('.product_meta .sku').html(match_variation.sku);

                        cw_updateGallery(form_add_to_cart, product_id, selected_options, variation_id);
                    } else {
                        add_to_cart_button.html(wp.template('add-to-cart-button-out-stock')).addClass('disabled wc-variation-selection-needed');
                        var template_html = wp.template('unavailable-variation-template');
                        form_add_to_cart.find('.woocommerce-variation.single_variation').html(template_html).show();
                    }
                }
            } else {
                $('.variations_form').trigger('found_variation', [match_variation]);
            }
        }

        function cw_ResetOptions(form_add_to_cart) {
            var product_id = form_add_to_cart.data('product_id');
            var gallery_enabled = form_add_to_cart.data('gallery_enabled');
            $(document).trigger('.reset_variations');
            //trigger default function of woocommerce
            if (gallery_enabled) {
                if (product_id != '') {
                    cw_updateGallery(form_add_to_cart, product_id, '', '');
                }
            }
        }

        var wrap_class = !!zoo_cw_params.product_image_custom_class ? zoo_cw_params.product_image_custom_class : 'div.woocommerce-product-gallery,div.images';

        function cw_updateGallery(form_add_to_cart, product_id, selected_options, variation_id) {
            $(document).trigger('cleverswatch_before_update_gallery', {
                "product_id": product_id,
                "selected_options": selected_options
            });
            var ajax_url = zoo_cw_params.ajax_url;
            var $gallery = $('#product-' + product_id).find(wrap_class).parent();
            if ($('.elementor-type-product.post-' + product_id + ' ' + wrap_class)[0]) {
                $gallery = $('.elementor-type-product.post-' + product_id + ' ' + wrap_class).parent();
            } else if ($('.elementor.product.post-' + product_id + ' ' + wrap_class)[0]) {
                $gallery = $('.elementor.product.post-' + product_id + ' ' + wrap_class).parent();
            }
            if ($('.cw-product-gallery')[0]) {
                $gallery = $('#product-' + product_id).find('.cw-product-gallery').parent();
                if ($('.elementor-type-product.post-' + product_id + ' .cw-product-gallery')[0]) {
                    $gallery = $('.elementor-type-product.post-' + product_id + ' .cw-product-gallery').parent();
                }
                else if ($('.elementor.product.post-' + product_id + ' .cw-product-gallery')[0]) {
                    $gallery = $('.elementor.product.post-' + product_id + ' .cw-product-gallery').parent();
                }
            }
            $gallery.addClass('zoo-cw-gallery-loading');

            $.ajax({
                url: ajax_url,
                cache: false,
                type: "POST",
                data: {
                    'action': 'clever_swatch_action',
                    'product_id': product_id,
                    'selected_options': selected_options,
                    'variation_id': variation_id
                }, success: function (response) {
                    if (!!response) {
                        if (response.cw_gallery_enabled) {
                            //trigger for update CW gallery
                            $(document).trigger('cleverswatch_update_cw_gallery', {
                                "content": response.html_content,
                                "product_id": product_id,
                                "form_add_to_cart": form_add_to_cart,
                                "variation_id": response.variation_id,
                                "variation_data": response.variation_data,
                            });
                        } else {
                            //trigger for update integrated gallery
                            $(document).trigger('cleverswatch_update_gallery', {
                                "content": response.html_content,
                                "product_id": product_id,
                                "form_add_to_cart": form_add_to_cart,
                                "variation_id": response.variation_id,
                                "variation_data": response.variation_data,
                            });
                        }
                    }
                }, error: function (jqXHR, textStatus) {
                    console.log(jqXHR);
                }
            });
        }

        function iOS() {

            var iDevices = [
                'iPad Simulator',
                'iPhone Simulator',
                'iPod Simulator',
                'iPad',
                'iPhone',
                'iPod'
            ];

            if (!!navigator.platform) {
                while (iDevices.length) {
                    if (navigator.platform === iDevices.pop()){ return true; }
                }
            }

            return false;
        }

        if (!$('.variations_form.no-cw-data')[0]) {
            //click function for image/text/color types option
            $(document).on('click', '.variations_form .zoo-cw-attribute-option', function () {
                var form_add_to_cart = $(this).parents('.variations_form');
                var attribute_rows = form_add_to_cart.find('.zoo-cw-group-attribute');
                var current_attribute_row = $(this).parents('.zoo-cw-group-attribute');
                var attribute_name = current_attribute_row.data('group-attribute');
                var product_variations = form_add_to_cart.data('product_variations');
                var selected_option_name = $(this).data('attribute-name');
                var option_value = $(this).data('attribute-option');
                var selected_options = form_add_to_cart.data('selected_options');
                if (selected_options === undefined) {
                    selected_options = {};
                }
                if ($(this).hasClass('zoo-cw-active')) {
                    //visual
                    $(this).removeClass('zoo-cw-active');
                    current_attribute_row.parents('.zoo-cw-attr-row').find('.zoo-cw-name').text('');
                    //process
                    current_attribute_row.find('input[name="' + attribute_name + '"]').val('');
                    delete selected_options[attribute_name];
                    form_add_to_cart.data('selected_options', selected_options);
                    cw_runFilter(product_variations, selected_options, attribute_rows, 'unavailable', "");
                    //disable add to cart button
                    form_add_to_cart.find('input[name=variation_id]').val('');
                    form_add_to_cart.find('button.single_add_to_cart_button').addClass('disabled wc-variation-selection-needed');
                    form_add_to_cart.find('.woocommerce-variation.single_variation').hide();
                    current_attribute_row.find('select option:selected').attr('selected', '');
                    if (!form_add_to_cart.find('.zoo-cw-attribute-option.zoo-cw-active')[0]) {
                        cw_ResetOptions(form_add_to_cart);
                    }
                } else {
                    var options = current_attribute_row.find('.zoo-cw-attribute-option');
                    //visual
                    options.removeClass('zoo-cw-active');
                    $(this).addClass('zoo-cw-active');
                    current_attribute_row.parents('.zoo-cw-attr-row').find('.zoo-cw-name').text(selected_option_name);
                    //process
                    current_attribute_row.find('input[name="' + attribute_name + '"]').val(option_value);
                    current_attribute_row.find('select option:selected').attr('selected', '');
                    current_attribute_row.find("select option[value='" + option_value + "']").attr('selected', 'selected');

                    selected_options[attribute_name] = option_value;
                    form_add_to_cart.data('selected_options', selected_options);
                    cw_runFilter(product_variations, selected_options, attribute_rows, 'unavailable', "");
                    //apply to cart
                    var attributes_count = form_add_to_cart.data('attributes_count');
                    if (Object.keys(selected_options).length == attributes_count) {
                        cw_processSelectedOption(product_variations, selected_options, form_add_to_cart);
                    } else {
                        form_add_to_cart.find('button.single_add_to_cart_button').addClass('disabled wc-variation-selection-needed');
                        form_add_to_cart.find('.woocommerce-variation.single_variation').hide();
                    }
                }
                current_attribute_row.find('select').trigger('change');
                $(".variations_form").trigger("woocommerce_variation_select_change");
            });

            //hover function for image/text/color types option

            var temp_option_value = '';
            $(window).resize(function () {
                if(!iOS()) {
                    if ($(window).width() > 769 && $('.zoo-cw-is-desktop')[0]) {
                        $(document).on({
                            mouseenter: function () {
                                var form_add_to_cart = $(this).parents('.variations_form');
                                var attribute_rows = form_add_to_cart.find('.zoo-cw-group-attribute');
                                var current_attribute_row = $(this).parents('.zoo-cw-group-attribute');
                                var attribute_name = current_attribute_row.data('group-attribute');
                                var product_variations = form_add_to_cart.data('product_variations');
                                var option_value = $(this).data('attribute-option');
                                if (option_value != temp_option_value) {
                                    temp_option_value = option_value;
                                    var selected_options = form_add_to_cart.data('selected_options');
                                    if (selected_options === undefined) {
                                        selected_options = {};
                                    }
                                    //visual
                                    var new_option_name = $(this).data('attribute-name');
                                    current_attribute_row.parents('.zoo-cw-attr-row').find('.zoo-cw-name').text(new_option_name);
                                    //process
                                    var check_options = $.extend({}, selected_options);
                                    check_options[attribute_name] = option_value;
                                    cw_runFilter(product_variations, check_options, attribute_rows, 'temp-unavailable', 'temp-available');

                                    //add to cart button effect
                                    var add_to_cart_button = form_add_to_cart.find('.single_add_to_cart_button:not(.zoo-buy-now)');
                                    var attributes_count = form_add_to_cart.data('attributes_count');
                                    if (Object.keys(check_options).length == attributes_count) {
                                        var match_variation = cw_findMatchVariation(product_variations, check_options);
                                        if (match_variation) {
                                            if (match_variation.is_in_stock && match_variation.is_purchasable) {
                                                $(document).trigger('cleverswatch_button_add_cart', {
                                                    "selector": add_to_cart_button
                                                });
                                            } else {
                                                $(document).trigger('cleverswatch_button_out_stock', {
                                                    "selector": add_to_cart_button
                                                });
                                            }

                                            var template = wp.template('variation-template');
                                            var template_html = template({
                                                variation: match_variation
                                            });
                                            form_add_to_cart.find('.woocommerce-variation.single_variation').html(template_html);
                                            if ($('.single_variation_wrap .single_variation').css('display') == 'none') {
                                                form_add_to_cart.find('.woocommerce-variation.single_variation').slideDown()
                                            }
                                        } else {
                                            $(document).trigger('cleverswatch_button_out_stock', {
                                                "selector": add_to_cart_button
                                            });
                                            var template = wp.template('unavailable-variation-template');
                                            form_add_to_cart.find('.woocommerce-variation.single_variation').html(template);
                                            if ($('.single_variation_wrap .single_variation').css('display') == 'none') {
                                                form_add_to_cart.find('.woocommerce-variation.single_variation').slideDown()
                                            }
                                        }

                                    } else {
                                        $(document).trigger('cleverswatch_button_select_option', {
                                            "selector": add_to_cart_button
                                        });
                                        form_add_to_cart.find('.woocommerce-variation.single_variation').slideUp();
                                    }
                                }
                            },
                            mouseleave: function () {
                                if (temp_option_value != '') {
                                    temp_option_value = '';
                                    var form_add_to_cart = $(this).parents('.variations_form');
                                    var attribute_rows = form_add_to_cart.find('.zoo-cw-group-attribute');
                                    var current_attribute_row = $(this).parents('.zoo-cw-group-attribute');
                                    //visual
                                    attribute_rows.find('.zoo-cw-attribute-option').removeClass('temp-unavailable').removeClass('temp-available');
                                    var selected_option_name = current_attribute_row.find('.zoo-cw-attribute-option.zoo-cw-active').data('attribute-name');
                                    if (selected_option_name === undefined) {
                                        selected_option_name = '';
                                    }
                                    current_attribute_row.parents('.zoo-cw-attr-row').find('.zoo-cw-name').text(selected_option_name);

                                    //add to cart button effect
                                    var add_to_cart_button = form_add_to_cart.find('.single_add_to_cart_button:not(.zoo-buy-now)');
                                    var product_variations = form_add_to_cart.data('product_variations');


                                    var selected_options = form_add_to_cart.data('selected_options');
                                    if (selected_options === undefined) {
                                        selected_options = {};
                                    }
                                    var attributes_count = form_add_to_cart.data('attributes_count');
                                    if (Object.keys(selected_options).length == attributes_count) {
                                        var match_variation = cw_findMatchVariation(product_variations, selected_options);
                                        if (match_variation) {
                                            if (match_variation.is_in_stock && match_variation.is_purchasable) {
                                                $(document).trigger('cleverswatch_button_add_cart', {
                                                    "selector": add_to_cart_button
                                                });
                                            } else {
                                                $(document).trigger('cleverswatch_button_out_stock', {
                                                    "selector": add_to_cart_button
                                                });
                                            }
                                            var template = wp.template('variation-template');
                                            var template_html = template({
                                                variation: match_variation
                                            });
                                            form_add_to_cart.find('.woocommerce-variation.single_variation').html(template_html);
                                            if ($('.single_variation_wrap .single_variation').css('display') == 'none') {
                                                form_add_to_cart.find('.woocommerce-variation.single_variation').slideDown()
                                            }
                                        } else {
                                            $(document).trigger('cleverswatch_button_out_stock', {
                                                "selector": add_to_cart_button
                                            });
                                            var template = wp.template('unavailable-variation-template');
                                            form_add_to_cart.find('.woocommerce-variation.single_variation').html(template);
                                            if ($('.single_variation_wrap .single_variation').css('display') == 'none') {
                                                form_add_to_cart.find('.woocommerce-variation.single_variation').slideDown()
                                            }
                                        }
                                    } else {
                                        $(document).trigger('cleverswatch_button_select_option', {
                                            "selector": add_to_cart_button
                                        });
                                    }
                                }
                            }
                        }, '.variations_form .zoo-cw-attribute-option');
                        $(document).on({
                            mouseleave: function () {
                                if (!$(this).find('.zoo-cw-active')[0]) {
                                    $(this).parents('.variations_form').find('.woocommerce-variation.single_variation').slideUp();
                                }
                            }
                        }, '.variations_form .zoo-cw-group-attribute:not(.zoo-cw-type-default)');
                    }
                }
            }).resize();
            //pass the element as an argument to .on
            //change function for select type option
            $(document).on('change', '.variations_form .zoo-cw-group-attribute select.zoo-cw-attribute-select', function () {
                var form_add_to_cart = $(this).parents('.variations_form');
                var attribute_rows = form_add_to_cart.find('.zoo-cw-group-attribute');
                var current_attribute_row = $(this).parents('.zoo-cw-group-attribute');
                var attribute_name = current_attribute_row.data('group-attribute');
                var product_variations = form_add_to_cart.data('product_variations');
                var selected_options = form_add_to_cart.data('selected_options');
                if (selected_options === undefined) {
                    selected_options = {};
                }
                var selected_option_name = '';
                var option_value = $(this).val();
                var single_variation = $(this).parents('.variations_form').find('.woocommerce-variation.single_variation');
                if (option_value == "") {
                    selected_option_name = "";
                    single_variation.slideUp();
                } else {
                    selected_option_name = $(this).find('option:selected').text();
                    single_variation.slideDown();
                }
                //visual
                current_attribute_row.parents('.zoo-cw-attr-row').find('.zoo-cw-name').text(selected_option_name);
                //process
                current_attribute_row.find('input[name="' + attribute_name + '"]').val(option_value);
                selected_options[attribute_name] = option_value;
                form_add_to_cart.data('selected_options', selected_options);
                cw_runFilter(product_variations, selected_options, attribute_rows, 'unavailable', "");
                //apply to cart
                var attributes_count = form_add_to_cart.data('attributes_count');
                if (Object.keys(selected_options).length == attributes_count) {
                    cw_processSelectedOption(product_variations, selected_options, form_add_to_cart);
                } else {
                    form_add_to_cart.find('button.single_add_to_cart_button').addClass('disabled wc-variation-selection-needed');
                }
                if (option_value == '') {
                    form_add_to_cart.find('button.single_add_to_cart_button').addClass('disabled wc-variation-selection-needed');
                }
                $(".variations_form").trigger("woocommerce_variation_select_change");
            });
        }

        //binding update cw gallery
        $(document).bind('cleverswatch_update_cw_gallery', function (event, response) {
            var cw_gallery = $('#product-' + response.product_id).find('.cw-product-gallery');
            if ($('.elementor-type-product.post-' + response.product_id + ' .cw-product-gallery')[0]) {
                cw_gallery = $('.elementor-type-product.post-' + response.product_id + ' .cw-product-gallery');
            }
            if ($('.elementor.product.post-' + response.product_id + ' .cw-product-gallery')[0]) {
                cw_gallery = $('.elementor.product.post-' + response.product_id + ' .cw-product-gallery');
            }
            if (cw_gallery.length) {
                cw_gallery.replaceWith(response.content);
                setTimeout(function () {
                    cw_product_gallery();
                    cw_gallery.wc_product_gallery();
                }, 300);
            }
            setTimeout(function () {
                $('.zoo-cw-gallery-loading').removeClass('zoo-cw-gallery-loading');
            }, 300);
        });
        //binding update gallery
        $(document).bind('cleverswatch_update_gallery', function (event, response) {
            var imagesDiv = $('#product-' + response.product_id).find(wrap_class);
            if ($('.elementor-type-product.post-' + response.product_id + ' ' + wrap_class)[0]) {
                imagesDiv = $('.elementor-type-product.post-' + response.product_id + ' ' + wrap_class);
            }
            if ($('.elementor.product.post-' + response.product_id + ' ' + wrap_class)[0]) {
                imagesDiv = $('.elementor.product.post-' + response.product_id + ' ' + wrap_class);
            }
            if (jQuery(imagesDiv).length) {
                imagesDiv.replaceWith(response.content);
                setTimeout(function () {
                    if (!!zoo_cw_params.slider_support && typeof $.fn.wc_product_gallery !== "undefined") {
                        imagesDiv.wc_product_gallery();
                    }
                }, 100)
            }
            setTimeout(function () {
                $('.zoo-cw-gallery-loading').removeClass('zoo-cw-gallery-loading');
            }, 100)
        });
        //Bind for button if button is select option
        $(document).bind('cleverswatch_button_select_option', function (event, response) {
            var add_to_cart_button = response.selector;
            var icon = add_to_cart_button.find('i').clone();
            add_to_cart_button.html(wp.template('add-to-cart-button-select-option')).addClass('disabled wc-variation-selection-needed');
            add_to_cart_button.prepend(icon);
        });
        //Bind for button if button is Add to Cart
        $(document).bind('cleverswatch_button_add_cart', function (event, response) {
            var add_to_cart_button = response.selector;
            var icon = add_to_cart_button.find('i').clone();
            add_to_cart_button.html(wp.template('add-to-cart-button')).removeClass('disabled wc-variation-selection-needed wc-variation-is-unavailable');
            add_to_cart_button.prepend(icon);
        });
        //Bind for button if button is Out of stock
        $(document).bind('cleverswatch_button_out_stock', function (event, response) {
            var add_to_cart_button = response.selector;
            var icon = add_to_cart_button.find('i').clone();
            add_to_cart_button.html(wp.template('add-to-cart-button-out-stock')).addClass('disabled wc-variation-is-unavailable');
            add_to_cart_button.prepend(icon);
        });

        //product lightbox
        $(document).on('click', '.cw-product-gallery-main a', function (e) {
            e.preventDefault();
            if (!$('.pswp--visible')[0] && typeof PhotoSwipe !== 'undefined') {
                var pswpElement = $('.pswp')[0],
                    items = $(this).cwgetGalleryItems(),
                    c_index = $(this).parent().index();
                if ($(this).parent().hasClass('slick-slide')) {
                    if ($('.cw-product-gallery-main')[0]) {
                        var total_sl_active = $('.cw-product-gallery-main .slick-active').length;
                        if (total_sl_active == 0) {
                            c_index = $(this).parent().index();
                        }
                        else {
                            c_index = $(this).parent().index() - total_sl_active - 1;
                        }
                    }
                    else {
                        c_index = $(this).parent().index() - 1;
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
        jQuery.fn.extend({
            //Push product images to list
            cwgetGalleryItems: function () {
                var $slides = this.parents('.cw-product-gallery-main').find('.cw-product-gallery-item:not(.slick-cloned)'),
                    items = [];
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
    });
})(jQuery);