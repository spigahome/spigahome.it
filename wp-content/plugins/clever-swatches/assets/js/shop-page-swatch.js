(function ($) {
    'use strict';
    $(document).ready(function () {
        /*Tooltip*/
        if ($('.allow-tooltip')[0] && typeof tippy !== 'undefined') {
            tippy('.allow-tooltip.zoo-cw-wrap-shop .zoo-cw-type-color .zoo-cw-attr-item, .allow-tooltip.zoo-cw-wrap-shop .zoo-cw-type-image .zoo-cw-attr-item', {
                arrow: true,
                animation: 'fade',
                dynamicTitle: true
            });
        }


        $(document).on('click', '.products .product.product-type-variable>.woocommerce-loop-product__link', function (event) {
            if($(event.target).hasClass('zoo-cw-attr-item') || $(event.target).parents(".zoo-cw-attr-item").length){
                event.preventDefault();
            }
        });
        var cntrlIsPressed = false;

        $(document).keydown(function (event) {
            if (event.which == "17" || event.metaKey)
                cntrlIsPressed = true;
        });
        $(document).keyup(function () {
            cntrlIsPressed = false;
        });

        $(document).on('click', '.products .product>.woocommerce-loop-product__link img:not(.zoo-cw-label),.woocommerce-loop-product__title', function (event) {
            let url = $(this).parents('.woocommerce-loop-product__link').attr('href');
            if (cntrlIsPressed) {
                window.open(url, "_blank");
            } else {
                window.location.href = url;
            }
        });

        function cw_checkValidOptions(product_variations, check_options) {
            var is_valid = false;
            $.each(product_variations, function (key, value) {
                var product_variation = value;
                //if (check_purchasable) check_purchasable = (product_variation.is_in_stock && product_variation.is_purchasable);
                //else check_purchasable = true;
                //if (check_purchasable) { //not in_stock or don't have price will be out
                var attribute_case = product_variation.attributes;
                var count_check = Object.keys(check_options).length;

                $.each(check_options, function (key, value) {
                    if ((attribute_case[key] == "") || (value == attribute_case[key])) {
                        count_check--;
                    }
                });
                if (count_check <= 0) {
                    is_valid = value;
                    // break jquery each loop
                    return false;
                }
                //}
            });
            return is_valid;
        }

        function cw_checkStock(product_variations, selected_options) {

            var is_valid = false;
            $.each(product_variations, function (key, value) {
                var product_variation = value;
                var attribute_case = product_variation.attributes;
                var count_check = Object.keys(selected_options).length;

                $.each(selected_options, function (key, value) {
                    if ((attribute_case[key] == "") || (value == attribute_case[key])) {
                        count_check--;
                    }
                });
                if (count_check <= 0) {
                    if (value.is_in_stock && value.is_purchasable) {
                        is_valid = true;
                    }
                }
            });

            return is_valid;
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
                                if (cw_checkValidOptions(product_variations, check_options)) {
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
                        if (cw_checkValidOptions(product_variations, check_options)) {
                            option.addClass(enable_class).removeClass(disable_class);
                        } else {
                            option.addClass(disable_class).removeClass(enable_class);
                        }
                    });
                }
            });
        }

        function cw_processShopSelectedOption(product_variations, selected_options, product_li) {
            var old_variation_id = product_li.find('input[name=variation_id]').val();
            var swatch_wrap = product_li.find('.zoo-cw-wrap-shop');
            var allow_add_to_cart = swatch_wrap.data('allow-add-to-cart');
            var variation_id = 0;

            $.each(product_variations, function (key, value) {
                var product_variation = value;
                var attribute_case = product_variation.attributes;
                var count_check = Object.keys(selected_options).length;

                $.each(selected_options, function (key, value) {
                    if ((attribute_case[key] == "") || (value == attribute_case[key])) {
                        count_check--;
                    }
                });
                if (count_check <= 0) {
                    variation_id = product_variation.variation_id;
                    // break jquery each loop
                    return false;
                }
            });

            if (variation_id != old_variation_id) {
                var add_to_cart_button = product_li.find('a.add_to_cart_button');
                if (variation_id != 0) {
                    var product_id = product_li.find('.zoo-cw-wrap-shop').data('product_id');
                    product_li.addClass('zoo-cw-loading');
                    cw_updateProductImage(product_li, product_id, selected_options, variation_id);
                    if (allow_add_to_cart) {
                        if (cw_checkValidOptions(product_variations, selected_options) && cw_checkStock(product_variations, selected_options)) {
                            //add to cart process
                            add_to_cart_button.data('variation_id', variation_id);
                            add_to_cart_button.data('selected_options', selected_options);
                            $(document).trigger('cleverswatch_button_add_cart', {
                                "selector": add_to_cart_button
                            });
                        } else {
                            //add to cart process
                            add_to_cart_button.data('variation_id', 0);
                            add_to_cart_button.data('selected_options', '');
                            $(document).trigger('cleverswatch_button_out_stock', {
                                "selector": add_to_cart_button
                            });
                        }
                    }
                } else {
                    //add to cart process
                    if (allow_add_to_cart) {
                        add_to_cart_button.data('variation_id', 0);
                        add_to_cart_button.data('selected_options', '');
                        $(document).trigger('cleverswatch_button_out_stock', {
                            "selector": add_to_cart_button
                        });
                    }
                }
            }
        }

        function cw_updateProductImage(product_li, product_id, selected_options, variation_id) {
            var ajax_url = zoo_cw_params.ajax_url;
            jQuery.ajax({
                url: ajax_url,
                cache: false,
                type: "POST",
                data: {
                    'action': 'zoo_cw_shop_page_swatch',
                    'selected_options': selected_options,
                    'product_id': product_id,
                    'variation_id': variation_id
                }, success: function (response) {
                    product_li.removeClass('zoo-cw-loading');
                    var data = response;
                    if (data['result'] == 'done') {
                        $(document).trigger('cleverswatch_shop_update_image', {
                            "selector": product_li,
                            'data': data
                        });
                    }
                }
            });
        }

        //click function for image/text/color types option
        $(document).on('click', '.zoo-cw-wrap-shop .zoo-cw-attribute-option', function () {
            var swatch_wrap = $(this).parents('.zoo-cw-wrap-shop');
            var product_id = swatch_wrap.data('product_id');
            var product_li = swatch_wrap.closest('.product.post-' + product_id);
            var allow_add_to_cart = swatch_wrap.data('allow-add-to-cart');

            var attribute_rows = product_li.find('.zoo-cw-group-attribute');
            var current_attribute_row = $(this).parents('.zoo-cw-group-attribute');
            var attribute_name = current_attribute_row.data('group-attribute');
            var product_variations = swatch_wrap.data('product_variations');
            var selected_option_name = $(this).data('attribute-name');
            var option_value = $(this).data('attribute-option');
            var selected_options = swatch_wrap.data('selected_options');
            if (selected_options === undefined) {
                selected_options = {};
            }
            if ($(this).hasClass('zoo-cw-active')) {
                //visual
                $(this).removeClass('zoo-cw-active');
                current_attribute_row.parents('.zoo-cw-attr-row').find('.zoo-cw-name').text('');
                //process
                current_attribute_row.find('input[name=' + attribute_name + ']').val('');
                delete selected_options[attribute_name];
                swatch_wrap.data('selected_options', selected_options);
                cw_runFilter(product_variations, selected_options, attribute_rows, 'unavailable', "");

                //disable add to cart button
                if (allow_add_to_cart) {
                    var add_to_cart_button = product_li.find('a.add_to_cart_button');
                    add_to_cart_button.data('variation_id', 0);
                    $(document).trigger('cleverswatch_button_select_option', {
                        "selector": add_to_cart_button
                    });
                }
            } else {
                var options = current_attribute_row.find('.zoo-cw-attribute-option');
                //visual
                options.removeClass('zoo-cw-active');
                $(this).addClass('zoo-cw-active');
                current_attribute_row.parents('.zoo-cw-attr-row').find('.zoo-cw-name').text(selected_option_name);
                //process
                current_attribute_row.find('input[name=' + attribute_name + ']').val(option_value);
                selected_options[attribute_name] = option_value;
                swatch_wrap.data('selected_options', selected_options);
                cw_runFilter(product_variations, selected_options, attribute_rows, 'unavailable', "");
                //apply to cart
                var attributes_count = swatch_wrap.data('attributes_count');
                if (Object.keys(selected_options).length == attributes_count) {
                    cw_processShopSelectedOption(product_variations, selected_options, product_li);
                } else {
                    //disable add to cart function
                    if (allow_add_to_cart) {
                        var add_to_cart_button = product_li.find('a.add_to_cart_button');
                        add_to_cart_button.data('variation_id', 0);
                        $(document).trigger('cleverswatch_button_select_option', {
                            "selector": add_to_cart_button
                        });
                    }
                }
            }
        });

        //hover function for image/text/color types option
        $(window).resize(function () {

            if ($(window).width() > 769 && $('.zoo-cw-is-desktop')[0]) {
                $(document).on({
                    mouseenter: function () {
                        var swatch_wrap = $(this).parents('.zoo-cw-wrap-shop');
                        var product_id = swatch_wrap.data('product_id');
                        var product_li = swatch_wrap.closest('.product.post-' + product_id);

                        var attribute_rows = product_li.find('.zoo-cw-group-attribute');
                        var current_attribute_row = $(this).parents('.zoo-cw-group-attribute');
                        var attribute_name = current_attribute_row.data('group-attribute');
                        var product_variations = swatch_wrap.data('product_variations');
                        var option_value = $(this).data('attribute-option');
                        var selected_options = swatch_wrap.data('selected_options');
                        if (selected_options === undefined) {
                            selected_options = {};
                        }
                        //visual
                        var new_option_name = $(this).data('attribute-name');
                        current_attribute_row.parents('.zoo-cw-attr-row').find('.zoo-cw-name').text(new_option_name);
                        //process
                        var check_options = {};
                        check_options = $.extend({}, selected_options);
                        check_options[attribute_name] = option_value;
                        cw_runFilter(product_variations, check_options, attribute_rows, 'temp-unavailable', 'temp-available');

                        //add to cart button effect
                        var allow_add_to_cart = swatch_wrap.data('allow-add-to-cart');
                        if (allow_add_to_cart) {
                            var add_to_cart_button = product_li.find('.add_to_cart_button');
                            var attributes_count = swatch_wrap.data('attributes_count');
                            if (Object.keys(check_options).length == attributes_count) {
                                if (cw_checkValidOptions(product_variations, check_options) && cw_checkStock(product_variations, selected_options)) {
                                    $(document).trigger('cleverswatch_button_add_cart', {
                                        "selector": add_to_cart_button
                                    });
                                } else {
                                    $(document).trigger('cleverswatch_button_out_stock', {
                                        "selector": add_to_cart_button
                                    });
                                }
                            } else {
                                $(document).trigger('cleverswatch_button_select_option', {
                                    "selector": add_to_cart_button
                                });
                            }
                        }
                    },
                    mouseleave: function () {
                        var swatch_wrap = $(this).parents('.zoo-cw-wrap-shop');
                        var product_id = swatch_wrap.data('product_id');
                        var product_li = swatch_wrap.closest('.product.post-' + product_id);

                        var attribute_rows = product_li.find('.zoo-cw-group-attribute');
                        var current_attribute_row = $(this).parents('.zoo-cw-group-attribute');
                        //visual
                        attribute_rows.find('.zoo-cw-attribute-option').removeClass('temp-unavailable').removeClass('temp-available');
                        var selected_option_name = current_attribute_row.find('.zoo-cw-attribute-option.zoo-cw-active').data('attribute-name');
                        if (selected_option_name === undefined) {
                            selected_option_name = '';
                        }
                        current_attribute_row.parents('.zoo-cw-attr-row').find('.zoo-cw-name').text(selected_option_name);

                        //add to cart button effect
                        var allow_add_to_cart = swatch_wrap.data('allow-add-to-cart');
                        if (allow_add_to_cart) {
                            var product_variations = swatch_wrap.data('product_variations');
                            var selected_options = swatch_wrap.data('selected_options');
                            if (selected_options === undefined) {
                                selected_options = {};
                            }
                            var add_to_cart_button = product_li.find('.add_to_cart_button');
                            var attributes_count = swatch_wrap.data('attributes_count');
                            if (Object.keys(selected_options).length == attributes_count) {
                                if (cw_checkValidOptions(product_variations, selected_options) && cw_checkStock(product_variations, selected_options)) {
                                    $(document).trigger('cleverswatch_button_add_cart', {
                                        "selector": add_to_cart_button
                                    });
                                } else {
                                    $(document).trigger('cleverswatch_button_out_stock', {
                                        "selector": add_to_cart_button
                                    });
                                }
                            } else {
                                $(document).trigger('cleverswatch_button_select_option', {
                                    "selector": add_to_cart_button
                                });
                            }
                        }
                    }
                }, '.zoo-cw-wrap-shop .zoo-cw-attribute-option'); //pass the element as an argument to .on
            }
        }).resize();
        //change function for select type option
        $(document).on('change', '.zoo-cw-group-attribute select.zoo-cw-attribute-select', function () {
            var swatch_wrap = $(this).parents('.zoo-cw-wrap-shop');
            var product_id = swatch_wrap.data('product_id');
            var product_li = swatch_wrap.closest('.product.post-' + product_id);
            var attribute_rows = product_li.find('.zoo-cw-group-attribute');
            var current_attribute_row = $(this).parents('.zoo-cw-group-attribute');
            var attribute_name = current_attribute_row.data('group-attribute');
            var product_variations = swatch_wrap.data('product_variations');
            var selected_options = swatch_wrap.data('selected_options')
            if (selected_options === undefined) {
                selected_options = {};
            }
            var selected_option_name = '';
            var option_value = $(this).val();
            if (option_value == "") {
                selected_option_name = "";
            } else {
                selected_option_name = $(this).find('option:selected').text();
            }
            //visual
            current_attribute_row.parents('.zoo-cw-attr-row').find('.zoo-cw-name').text(selected_option_name);
            //process
            current_attribute_row.find('input[name=' + attribute_name + ']').val(option_value);
            selected_options[attribute_name] = option_value;
            swatch_wrap.data('selected_options', selected_options);
            cw_runFilter(product_variations, selected_options, attribute_rows, 'unavailable', "");
            //apply to cart
            var add_to_cart_button = product_li.find('a.add_to_cart_button');
            var attributes_count = swatch_wrap.data('attributes_count');
            if (Object.keys(selected_options).length == attributes_count) {
                cw_processShopSelectedOption(product_variations, selected_options, product_li);
            } else {
                //disable add to cart function
                add_to_cart_button.data('variation_id', 0);
                $(document).trigger('cleverswatch_button_select_option', {
                    "selector": add_to_cart_button
                });
            }

            if (option_value == '') {
                $(document).trigger('cleverswatch_button_select_option', {
                    "selector": add_to_cart_button
                });
            }
        });
        //add to cart button function
        $(document).on('click', '.product-type-variable .add_to_cart_button', function (event) {

            var product_id = $(this).data('product_id');
            var product_li = $(this).closest('.product.post-' + product_id);
            var swatch_wrap = product_li.find('.zoo-cw-wrap-shop');
            var allow_add_to_cart = swatch_wrap.data('allow-add-to-cart');

            if (allow_add_to_cart) {

                var variation_id = $(this).data('variation_id');
                if (variation_id !== undefined && variation_id != 0) {
                    event.preventDefault();
                    $(document).trigger('cleverswatch_before_add_to_cart');
                    var product_id = $(this).data('product_id');
                    var variations = $(this).data('selected_options');
                    var data = {
                        'action': 'zoo_cw_ajax_cart_variation',
                        'product_id': product_id,
                        'quantity': 1,
                        'variations': variations,
                        'variation_id': variation_id,
                    };

                    var product_li = $(this).parents('.product.post-' + product_id);
                    product_li.addClass('zoo-cw-loading');

                    var ajax_url = zoo_cw_params.ajax_url;

                    $.post(ajax_url, data)
                        .done(function (data) {

                            $(document).trigger('cleverswatch_after_add_to_cart', {
                                "status": 'done', "result": data
                            });
                            $(document).trigger('added_to_cart', [data]);
                            product_li.removeClass('zoo-cw-loading');
                            var result = data;
                            $.each(result.fragments, function (key, value) {
                                $(key).replaceWith(value);
                            });
                        });
                }
            }
        });
        //Bind for Shop update image
        $(document).bind('cleverswatch_shop_update_image', function (event, response) {
            var product_li = response.selector;
            var data = response.data;
            product_li.find('img:not(.zoo-cw-label)')
                .attr('src', data['image_src'])
                .attr('srcset', data['image_srcset'] ? data['image_srcset'] : '');
        });
        //Bind for button if button is select option
        $(document).bind('cleverswatch_button_select_option', function (event, response) {
            var add_to_cart_button = response.selector;
            var icon = add_to_cart_button.find('i').clone();
            add_to_cart_button.html(wp.template('add-to-cart-button-select-option')).addClass('disabled');
            add_to_cart_button.prepend(icon);
        });
        //Bind for button if button is Add to Cart
        $(document).bind('cleverswatch_button_add_cart', function (event, response) {
            var add_to_cart_button = response.selector;
            var icon = add_to_cart_button.find('i').clone();
            add_to_cart_button.html(wp.template('add-to-cart-button')).removeClass('disabled');
            add_to_cart_button.prepend(icon);
        });
        //Bind for button if button is Out of stock
        $(document).bind('cleverswatch_button_out_stock', function (event, response) {
            var add_to_cart_button = response.selector;
            var icon = add_to_cart_button.find('i').clone();
            add_to_cart_button.html(wp.template('add-to-cart-button-out-stock')).addClass('disabled');
            add_to_cart_button.prepend(icon);
        });
    });
})(jQuery);
