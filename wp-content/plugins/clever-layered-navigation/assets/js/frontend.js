import Utils from './modules/utils';

/**
 * CLN Frontend
 *
 * @author CleverSoft <hello@cleversoft.co>
 * @license ISC
 */
class CleverLayeredNav {
    constructor(el) {
        if (!(el instanceof jQuery) || !el.hasClass('zoo-layer-nav')) {
            console.error('Invalid CLN form container!');
        }

        this.el = el;
        this.$el = { // Maybe cache child elements
            form: el.find('.zoo-ln-filter-form')
        };
        this.config = JSON.parse(this.$el.form.attr('data-ln-config'));
        this.isPagination = false;

        this.init();
    }

    init() {
        this.fetchUI();
        this.bindEvents();
    }

    getEl(selector) {
        if (this.$el[selector]) {
            return this.$el[selector];
        }

        const el = jQuery(selector, this.el);

        if (el.length) {
            this.$el[selector] = el;
            return el;
        } else {
            return false;
        }
    }

    fetchUI() {
        this.renderPriceFilters();
        this.renderRangeFilters();

        // Tippy tooltips. Entire page!!!
        if (typeof tippy !== 'undefined') {
            tippy('.cw-type-color.inline .zoo-filter-item, .cw-type-image.inline .zoo-filter-item', {
                arrow: true,
                animation: 'fade'
            });
        }

        // Scrollbar
        // if (this.el.hasClass('zoo-ln-set-max-height')) {
        //     this.getEl('.zoo-list-filter-item').scrollbar();
        // }

        // Make sure pagination work with filtered data.
        jQuery(this.config.jquery_selector_paging).attr('data-ln-preset', this.config.filter_preset);

        // Show active child options.
        if (this.getEl('.zoo-filter-has-child .selected')) {
            this.$el['.zoo-filter-has-child .selected'].each(function() {
                jQuery(this).parents('ul.zoo-wrap-child-item').slideDown().prev('.zoo-ln-toggle-view').addClass('active');
            });
        }
    }

    bindEvents() {
        this.el.on('click', '.zoo-toggle-filter-visible', function() {
            jQuery(this).parents('.zoo-ln-filter-form').find('.zoo-ln-wrap-col').slideToggle();
        });

        this.el.on('click', '.zoo-ln-toggle-view', function() {
            jQuery(this).next('ul.zoo-wrap-child-item').slideToggle();
            jQuery(this).toggleClass('active');
        });

        this.el.on('click', '.zoo-ln-toggle-block-view', function() {
            jQuery(this).parents('.zoo-filter-block').toggleClass('visible');
            jQuery(this).parents('.zoo-filter-block').find('.zoo-list-filter-item').slideToggle();
        });

        this.el.on('click', '.zoo-ln-rating-item span', function() {
            let parent = jQuery(this).parents('.zoo-filter-block');
            parent.find('.selected').removeClass('selected');
            if (jQuery(this).attr('data-zoo-ln-star') != parent.find('input').val()) {
                jQuery(this).parent().addClass('selected');
                parent.find('input').val(jQuery(this).attr('data-zoo-ln-star')).trigger('change');
            } else {
                parent.find('input').val(0).trigger('change');
            }
        });

        this.el.find('input,select').not('.zoo_ln_uneffective').on('change', function() {
            jQuery(this).parent().parent('.zoo-filter-item').toggleClass('selected');
        });

        if (this.$el.form.hasClass('instant_filtering')) {
            this.el.find('input,select').not('.zoo_ln_uneffective').on('change', (e) => {
                e.preventDefault();
                this.filter();
            });
        }

        this.el.on('click', '.zoo-ln-filter-form input[type=submit]', (e) => {
            e.preventDefault();
            this.filter();
        });

        this.el.on('click', '.zoo-ln-remove-filter-item', (e) => {
            e.preventDefault();
            this.onRemoveFilter(jQuery(e.currentTarget));
        });
    }

    filter() {
        if (!this.isPagination) {
            this.getEl('input[name="paged"]').val('');
        }

        const actionURL = this.getActionURL();

        window.history.pushState({
            path: actionURL
        }, '', actionURL);

        if (this.$el.form.hasClass('apply_ajax')) {
            this.ajaxFilter()
        } else {
            this.$el.form.attr('action', actionURL);
            this.$el.form.submit();
        }
    }

    ajaxFilter() {
        var cln = this,
            formParams = this.$el.form.serialize(),
            filterId = this.$el.form.find('input[name="filter_list_id"]').val();

        if (zoo_ln_params.isTax) {
            formParams = jQuery.param(zoo_ln_params.queriedTerm) + '&' + formParams;
        }

        var data = {
            action: 'zoo_ln_get_product_list',
            zoo_ln_form_data: formParams
        };

        jQuery(document).trigger('zoo_ln_before_filter', {
            "form": this.$el.form,
            "selector": this.config.jquery_selector_products,
            "selector_count": this.config.jquery_selector_products_count,
            "selector_paging": this.config.jquery_selector_paging
        });

        jQuery.post(zoo_ln_params.ajax_url, data, function(result) {
            jQuery(cln.config.jquery_selector_products).html(result['html_ul_products_content']);
            jQuery(cln.config.jquery_selector_products_count).replaceWith(result['html_result_count_content']);
            if (!cln.isPagination) { // Unnecessary to reinitialize entire form while doing pagination.
                jQuery('#cln-filter-preset-' + filterId).replaceWith(result['html_filter_panel']);
                new CleverLayeredNav(jQuery('#cln-filter-preset-' + filterId)); // Reinitialize newly form.
            }
            var html_pagination_content = result['html_pagination_content'];
            if (typeof html_pagination_content != undefined) {
                if (html_pagination_content.length == 0) {
                    jQuery(cln.config.jquery_selector_paging).hide();
                } else {
                    var wcPagiNav = jQuery(cln.config.jquery_selector_paging);
                    if (wcPagiNav.length) {
                        wcPagiNav.show().html(html_pagination_content);
                        wcPagiNav.addClass('zoo_ln_ajax_pagination');
                        wcPagiNav.attr('data-ln-preset', cln.config.filter_preset);
                    } else {
                        jQuery(cln.config.jquery_selector_products).after('<nav class="woocommerce-pagination zoo_ln_ajax_pagination" data-ln-preset="' + cln.config.filter_preset + '">' + html_pagination_content + '</nav>');
                    }
                }
            }

            // window.history.pushState(cln.config.shop_page_info.title, "Title");
            // document.title = cln.config.shop_page_info.title;

            jQuery(document).trigger('zoo_ln_after_filter', {
                "form": cln.$el.form,
                "selector": cln.config.jquery_selector_products,
                "selector_count": cln.config.jquery_selector_products_count,
                "selector_paging": cln.config.jquery_selector_paging,
                "result": result
            });
        });
    }

    getActionURL() {
        let queries, currentUrl = window.location.href;

        queries = this.parseQueries();

        if (-1 !== currentUrl.indexOf('?')) {
            if (-1 !== currentUrl.indexOf('post_type') || -1 !== currentUrl.indexOf('product_cat') || -1 !== currentUrl.indexOf('product_tag')) {
                currentUrl = currentUrl.replace(/&.+/, '');
                queries = queries.replace('?', '&');
            } else {
                currentUrl = currentUrl.replace(/\/\?.+/, '/');
            }
        }

        // Remove `relation` param if there's less than 2 queries, including itself.
        if (queries.split('?').length <= 2 && queries.split(',').length < 2 && queries.split('&').length < 2) {
            queries = queries.replace(/[&|\?]relation=[^&]+/, '');
        }

        return currentUrl + queries;
    }

    parseQueries() {
        let filteredOptions = [],
            selectedOptions = this.$el.form.serializeArray();

        //merger value filter
        selectedOptions.forEach(function(value) {
            var existing = filteredOptions.filter(function(v, i) {
                return v.name == value.name;
            });
            if (existing.length) {
                var existingIndex = filteredOptions.indexOf(existing[0]);
                filteredOptions[existingIndex].value = filteredOptions[existingIndex].value.concat(value.value);
            } else {
                if (typeof value.value == 'string')
                    value.value = [value.value];
                filteredOptions.push(value);
            }
        });

        var queries = '',
            arg_name = '',
            arg_type = '',
            arg_val = '',
            temp;

        filteredOptions.forEach(function(data) {
            arg_name = data.name;
            arg_type = Utils.stringMatches(arg_name, /^.*?(?=\[)/g);
            if (arg_type == null) {
                arg_type = arg_name;
            }
            arg_val = data.value.toString();
            if (queries == '') {
                temp = '?';
            } else {
                temp = '&';
            }
            if (!!arg_val && !!arg_type) {
                const q = arg_type[0][0];
                if (q == 'categories') {
                    queries += temp + 'categories=' + arg_val;
                } else if (q == 'tags') {
                    queries += temp + 'tags=' + arg_val;
                } else if (q == 'paged') {
                    queries += temp + 'paged=' + arg_val;
                } else if (q == 'attribute') {
                    let att_q = Utils.stringMatches(arg_name, /\[(.*?)\]/g)[0][1];
                    if (att_q === 'pa_color') {
                        att_q = 'color';
                    }
                    queries += temp + att_q + '=' + arg_val;
                } else if (q == 'price') {
                    queries += temp + Utils.stringMatches(arg_name, /\[(.*?)\]/g)[0][1] + '=' + arg_val;
                } else if (-1 !== arg_type.indexOf('range-m')) {
                    const rangeVal = parseFloat(arg_val);
                    if (jQuery.isNumeric(rangeVal)) {
                        queries += temp + arg_name + '=' + rangeVal;
                    }
                } else {
                    if (arg_val != '0') {
                        if (arg_type != 'zoo_ln_nonce_setting' && arg_type != 'filter_list_id' && arg_type != '_wp_http_referer' && arg_type != 'posts_per_page' && arg_type != 'pagination_link') {
                            queries += temp + arg_name + '=' + arg_val.split(' ');
                        }
                    }
                }
            }
        });

        return queries;
    }

    onRemoveFilter(filter) {
        const val = filter.val(),
            name = Utils.stringMatches(filter.attr('name'), /\[(.*?)\]/g);

        if (!!name) {
            //For case remove filter
            if (name[0][1] == 'categories') {
                if (this.getEl('.zoo-filter-by-categories input')) {
                    const selected_item = this.$el['.zoo-filter-by-categories input'].filter(function() {
                        return this.value == val
                    });
                    selected_item.prop('checked', false);
                    selected_item.closest('.selected').removeClass('selected');
                } else {
                    if (this.getEl('.zoo-filter-by-categories option')) {
                        this.$el['.zoo-filter-by-categories option'].filter(function() {
                            return this.value == val
                        }).prop('selected', false);
                    }
                }
            } else if (name[0][1] == 'attribute') {
                const selected_item = this.getEl('.zoo-filter-by-' + name[1][1] + ' input:checked').filter(function() {
                    return this.value == val
                });
                selected_item.prop('checked', false);
                selected_item.closest('.selected').removeClass('selected');
            } else if (name[0][1] == 'tags') {
                const removingTag = this.getEl('.zoo-filter-by-tags input.filter-tag-' + name[1][1]);
                removingTag.prop('checked', false);
                removingTag.closest('.zoo-filter-item').removeClass('selected');
            } else if (name[0][1] == 'price') {
                this.getEl('input.price-to, input.price-from').attr('value', '');
                this.getEl(".zoo-ln-slider-range").slider("values", [this.getEl(".zoo-filter-by-price.slider-price .price-min").val(), this.getEl(".zoo-filter-by-price.slider-price .price-max").val()]);
                this.getEl(".zoo-filter-by-price.slider-price .zoo-price-form .price.amount").text(this.getEl(".zoo-filter-by-price.slider-price .price-min").val());
                this.getEl(".zoo-filter-by-price.slider-price .zoo-price-to .price.amount").text(this.getEl(".zoo-filter-by-price.slider-price .price-max").val());
            } else if (0 === name[0][1].indexOf('range_')) {
                const currentTax = name[0][1].replace('range_', ''),
                    minField = 'input[name=range-min-' + currentTax + ']',
                    maxField = 'input[name=range-max-' + currentTax + ']';

                this.getEl(minField).attr('value', '');
                this.getEl(maxField).attr('value', '');
                let control = this.getEl(".range-slider-" + currentTax + " .zoo-ln-range-slider-control"),
                    config = control.data('config');

                control.slider({
                    values: [config.min, config.max]
                }).slider('pips', {
                    first: 'pip',
                    last: 'pip'
                }).slider('float');
            } else if (name[0][1] == 'on-sale') {
                this.getEl('input[name*=on-sale]').prop('checked', false);
            } else if (name[0][1] == 'in-stock') {
                this.getEl('input[name*=in-stock]').prop('checked', false);
            } else if (name[0][1] == 'rating-from') {
                this.getEl('input[name*=rating-from]').attr('value', 0);
                this.getEl('.zoo-list-rating .selected').removeClass('selected');
            }
        } else { // Reset everything.
            window.location.href = window.location.href.split('?')[0];
            return;
            this.getEl('input').prop('checked', false);
            this.getEl('input[name="relation"]').val('');
            this.getEl('input[name="cln_do_filter"]').val('');
            if (this.getEl('input[name="paged"]')) {
                this.$el['input[name="paged"]'].val('');
            }
            if (this.getEl('input[name="rating-from"]')) {
                this.$el['input[name="rating-from"]'].val(0);
            }
            if (this.getEl('input.attr-from, input.attr-to')) {
                this.$el['input.attr-from, input.attr-to'].val('');
            }
            if (this.getEl('.selected')) {
                this.$el['.selected'].removeClass('selected');
            }

            // Reset all range sliders.
            if (this.getEl('.zoo_ln_range_slider')) {
                jQuery.each(this.$el['.zoo_ln_range_slider'], function(index, slider) {
                    const control = jQuery('.zoo-ln-range-slider-control', slider),
                        config = control.data('config');

                    control.slider({
                        values: [config.min, config.max]
                    }).slider('pips', {
                        first: 'pip',
                        last: 'pip'
                    }).slider('float');
                });
            }

            // Reset price slider.
            if (this.getEl('input.price-to, input.price-from')) {
                this.$el['input.price-to, input.price-from'].attr('value', '');
            }
            if (this.getEl(".zoo-ln-slider-range") && this.getEl(".zoo-filter-by-price.slider-price .price-min") && this.getEl(".zoo-filter-by-price.slider-price .price-max")) {
                this.$el[".zoo-ln-slider-range"].slider("values", [this.$el[".zoo-filter-by-price.slider-price .price-min"].val(), this.$el[".zoo-filter-by-price.slider-price .price-max"].val()]);
            }
            if (this.getEl(".zoo-filter-by-price.slider-price .zoo-price-form .price.amount") && this.getEl(".zoo-filter-by-price.slider-price .price-min")) {
                this.$el[".zoo-filter-by-price.slider-price .zoo-price-form .price.amount"].text(this.$el[".zoo-filter-by-price.slider-price .price-min"].val());
            }
            if (this.getEl(".zoo-filter-by-price.slider-price .zoo-price-to .price.amount") && this.getEl(".zoo-filter-by-price.slider-price .price-max")) {
                this.$el[".zoo-filter-by-price.slider-price .zoo-price-to .price.amount"].text(this.$el[".zoo-filter-by-price.slider-price .price-max"].val());
            }
        }

        this.filter();
    }

    renderPriceFilters() {
        if (this.getEl('.zoo_ln_price.slider-price')) {
            const cln = this;
            this.$el['.zoo_ln_price.slider-price'].each(function() {
                const $this = jQuery(this),
                    min = parseInt(jQuery(this).find('.price-min').val()),
                    max = parseInt(jQuery(this).find('.price-max').val());
                let from = min,
                    to = max;
                if ($this.find('.price-from').val() != '') {
                    from = parseInt($this.find('.price-from').val());
                }
                if ($this.find('.price-to').val() != '') {
                    to = parseInt($this.find('.price-to').val());
                }

                $this.find(".zoo-ln-slider-range").slider({
                    range: true,
                    min: min,
                    max: max,
                    values: [from, to],
                    slide: function(event, ui) {
                        $this.find(".price-from").val(ui.values[0]);
                        $this.find('.zoo-price-form .price.amount').html(ui.values[0]);
                        $this.find(".price-to").val(ui.values[1]);
                        $this.find('.zoo-price-to .price.amount').html(ui.values[1]);
                    },
                    stop: function(event, ui) {
                        if (ui.values[0] == min && ui.values[1] == max) {
                            $this.find(".price-from").val('');
                            $this.find(".price-to").val('');
                        }
                        cln.getEl('input[name="paged"]').val('');
                        if (cln.$el.form.hasClass('instant_filtering')) {
                            cln.filter();
                        }
                    }
                });

                jQuery("input.amount").val("$" + jQuery(".slider-range").slider("values", 0) +
                    " - $" + jQuery(".slider-range").slider("values", 1));
            });
        }
    }

    renderRangeFilters() {
        if (this.getEl('.zoo_ln_range_slider')) {
            const cln = this;
            this.$el['.zoo_ln_range_slider'].each(function() {
                const $this = jQuery(this),
                    data = JSON.parse($this.find('.zoo-ln-range-slider-control').attr('data-config')),
                    min = parseFloat(data.min),
                    max = parseFloat(data.max);
                var from, to;
                from = $this.find('.attr-from').val();
                from = from == '' ? min : from;
                to = $this.find('.attr-to').val();
                to = to == '' ? max : to;
                var list_attr = data.val.split(",");
                $this.find('.zoo-ln-range-slider-control').slider({
                    range: true,
                    min: min,
                    max: max,
                    values: [from, to],
                    slide: function(event, ui) {
                        $this.find(".attr-from").val(ui.values[0]);
                        $this.find(".attr-to").val(ui.values[1]);
                    },
                    stop: function(event, ui) {
                        cln.getEl('input[name="paged"]').val('');
                        if (cln.$el.form.hasClass('instant_filtering')) {
                            cln.filter();
                        }
                    }
                }).slider("pips", {
                    first: "pip",
                    last: "pip"
                }).slider("float");
            });
        }
    }
}

jQuery(() => {
    jQuery('.zoo-layer-nav').each(function() {
        return new CleverLayeredNav(jQuery(this));
    });

    jQuery(document).on('click', '.woocommerce-pagination a', (e) => {
        e.preventDefault();

        const target = jQuery(e.currentTarget),
            pagination = target.closest('.woocommerce-pagination'),
            filterPreset = jQuery('#' + pagination.attr('data-ln-preset'));

        if (filterPreset.length !== 1) {
            console.log('Multiple filter forms detected. Unable to do pagination.');
            return;
        }

        let paged;

        if (target.hasClass('next')) {
            paged = parseInt(pagination.find('.page-numbers.current').text()) + 1;
        } else if (target.hasClass('prev')) {
            paged = parseInt(pagination.find('.page-numbers.current').text()) - 1;
        } else {
            paged = parseInt(target.text());
        }

        filterPreset.find('input[name="paged"]').val(paged);

        const cln = new CleverLayeredNav(filterPreset);

        cln.isPagination = true;

        cln.filter();

        cln.isPagination = false;
    });
})