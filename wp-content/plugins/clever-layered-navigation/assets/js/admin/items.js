/*global ajaxurl, isRtl */
var wpWidgets;
(function($) {
    var $document = $(document);

    wpWidgets = {
        /**
         * A closed Sidebar that gets a Widget dragged over it.
         *
         * @var element|null
         */
        hoveredSidebar: null,

        init: function() {
            var rem, the_id,
                self = this,
                chooser = $('.items-chooser'),
                selectSidebar = chooser.find('.items-chooser-sidebars'),
                sidebars = $('div.items-sortables'),
                isRTL = !!('undefined' !== typeof isRtl && isRtl);

            $('#items-right .sidebar-name').click(function() {
                var $this = $(this),
                    $wrap = $this.closest('.items-holder-wrap');

                if ($wrap.hasClass('closed')) {
                    $wrap.removeClass('closed');
                    $this.parent().sortable('refresh');
                } else {
                    $wrap.addClass('closed');
                }

                $document.triggerHandler('wp-pin-menu');
            });

            $('#items-left .sidebar-name').click(function() {
                $(this).closest('.items-holder-wrap').toggleClass('closed');
                $document.triggerHandler('wp-pin-menu');
            });

            // Conditional display swatch size.
            $('#items-right .display-style-selectbox').change(function(e) {
                var swatchSizeContainerId = '#' + this.id.replace('display-style', 'swatch-size-wrapper');

                if (this.value === 'inline') {
                    $(swatchSizeContainerId).show();
                } else {
                    $(swatchSizeContainerId).hide();
                }
            });

            $(document.body).bind('click.items-toggle', function(e) {
                var target = $(e.target),
                    css = {
                        'z-index': 100
                    },
                    item, inside, targetWidth, itemWidth, margin,
                    toggleBtn = target.closest('.item').find('.item-top button.item-action');

                if (target.parents('.item-top').length && !target.parents('#available-items').length) {
                    item = target.closest('div.item');
                    inside = item.children('.item-inside');
                    targetWidth = parseInt(item.find('input.item-width').val(), 10),
                        itemWidth = item.parent().width();

                    if (inside.is(':hidden')) {
                        if (targetWidth > 250 && (targetWidth + 30 > itemWidth) && item.closest('div.items-sortables').length) {
                            if (item.closest('div.item-liquid-right').length) {
                                margin = isRTL ? 'margin-right' : 'margin-left';
                            } else {
                                margin = isRTL ? 'margin-left' : 'margin-right';
                            }

                            css[margin] = itemWidth - (targetWidth + 30) + 'px';
                            item.css(css);
                        }
                        /*
                         * Don't change the order of attributes changes and animation:
                         * it's important for screen readers, see ticket #31476.
                         */
                        toggleBtn.attr('aria-expanded', 'true');
                        inside.slideDown('fast', function() {
                            item.addClass('open');
                        });
                    } else {
                        /*
                         * Don't change the order of attributes changes and animation:
                         * it's important for screen readers, see ticket #31476.
                         */
                        toggleBtn.attr('aria-expanded', 'false');
                        inside.slideUp('fast', function() {
                            item.attr('style', '');
                            item.removeClass('open');
                        });
                    }
                    e.preventDefault();
                } else if (target.hasClass('item-control-save')) {
                    wpWidgets.save(target.closest('div.item'), 0, 1, 0);
                    e.preventDefault();
                } else if (target.hasClass('item-control-remove')) {
                    wpWidgets.save(target.closest('div.item'), 1, 1, 0);
                    e.preventDefault();
                } else if (target.hasClass('item-control-close')) {
                    item = target.closest('div.item');
                    item.removeClass('open');
                    toggleBtn.attr('aria-expanded', 'false');
                    wpWidgets.close(item);
                    e.preventDefault();
                } else if (target.attr('id') === 'inactive-items-control-remove') {
                    wpWidgets.removeInactiveWidgets();
                    e.preventDefault();
                }
            });

            sidebars.children('.item').each(function() {
                var $this = $(this);

                wpWidgets.appendTitle(this);

                if ($this.find('p.item-error').length) {
                    $this.find('.item-action').trigger('click').attr('aria-expanded', 'true');
                }
            });

            $('#item-list').children('.item').draggable({
                connectToSortable: 'div.items-sortables',
                handle: '> .item-top > .item-title',
                distance: 2,
                helper: 'clone',
                zIndex: 100,
                containment: '#wpwrap',
                refreshPositions: true,
                start: function(event, ui) {
                    var chooser = $(this).find('.items-chooser');

                    ui.helper.find('div.item-description').hide();
                    the_id = this.id;

                    if (chooser.length) {
                        // Hide the chooser and move it out of the item
                        $('#wpbody-content').append(chooser.hide());
                        // Delete the cloned chooser from the drag helper
                        ui.helper.find('.items-chooser').remove();
                        self.clearWidgetSelection();
                    }
                },
                stop: function() {
                    if (rem) {
                        $(rem).hide();
                    }

                    rem = '';
                }
            });

            /**
             * Opens and closes previously closed Sidebars when Widgets are dragged over/out of them.
             */
            sidebars.droppable({
                tolerance: 'intersect',

                /**
                 * Open Sidebar when a Widget gets dragged over it.
                 *
                 * @param event
                 */
                over: function(event) {
                    var $wrap = $(event.target).parent();

                    if (wpWidgets.hoveredSidebar && !$wrap.is(wpWidgets.hoveredSidebar)) {
                        // Close the previous Sidebar as the Widget has been dragged onto another Sidebar.
                        wpWidgets.closeSidebar(event);
                    }

                    if ($wrap.hasClass('closed')) {
                        wpWidgets.hoveredSidebar = $wrap;
                        $wrap.removeClass('closed');
                    }

                    $(this).sortable('refresh');
                },

                /**
                 * Close Sidebar when the Widget gets dragged out of it.
                 *
                 * @param event
                 */
                out: function(event) {
                    if (wpWidgets.hoveredSidebar) {
                        wpWidgets.closeSidebar(event);
                    }
                }
            });

            sidebars.sortable({
                placeholder: 'item-placeholder',
                items: '> .item',
                handle: '> .item-top > .item-title',
                cursor: 'move',
                distance: 2,
                containment: '#wpwrap',
                tolerance: 'pointer',
                refreshPositions: true,
                start: function(event, ui) {
                    var height, $this = $(this),
                        $wrap = $this.parent(),
                        inside = ui.item.children('.item-inside');

                    if (inside.css('display') === 'block') {
                        ui.item.removeClass('open');
                        ui.item.find('.item-top button.item-action').attr('aria-expanded', 'false');
                        inside.hide();
                        $(this).sortable('refreshPositions');
                    }

                    if (!$wrap.hasClass('closed')) {
                        // Lock all open sidebars min-height when starting to drag.
                        // Prevents jumping when dragging a item from an open sidebar to a closed sidebar below.
                        height = ui.item.hasClass('ui-draggable') ? $this.height() : 1 + $this.height();
                        $this.css('min-height', height + 'px');
                    }
                },

                stop: function(event, ui) {
                    var addNew, itemNumber, $sidebar, $children, child, item,
                        $item = ui.item,
                        id = the_id;

                    // Reset the var to hold a previously closed sidebar.
                    wpWidgets.hoveredSidebar = null;

                    if ($item.hasClass('deleting')) {
                        wpWidgets.save($item, 1, 0, 1); // delete item
                        $item.remove();
                        return;
                    }

                    addNew = $item.find('input.add_new').val();
                    itemNumber = $item.find('input.multi_number').val();

                    $item.attr('style', '').removeClass('ui-draggable');
                    the_id = '';

                    if (addNew) {
                        if ('multi' === addNew) {
                            $item.html(
                                $item.html().replace(/<[^<>]+>/g, function(tag) {
                                    return tag.replace(/0|%i%/g, itemNumber);
                                })
                            );

                            $item.attr('id', id.replace('0', itemNumber));
                            itemNumber++;

                            $('div#' + id).find('input.multi_number').val(itemNumber);
                        } else if ('single' === addNew) {
                            $item.attr('id', 'new-' + id);
                            rem = 'div#' + id;
                        }

                        wpWidgets.save($item, 0, 0, 1);
                        $item.find('input.add_new').val('');
                        $document.trigger('item-added', [$item]);
                    }

                    $sidebar = $item.parent();

                    if ($sidebar.parent().hasClass('closed')) {
                        $sidebar.parent().removeClass('closed');
                        $children = $sidebar.children('.item');

                        // Make sure the dropped item is at the top
                        if ($children.length > 1) {
                            child = $children.get(0);
                            item = $item.get(0);

                            if (child.id && item.id && child.id !== item.id) {
                                $(child).before($item);
                            }
                        }
                    }

                    if (addNew) {
                        $item.find('.item-action').trigger('click');
                    } else {
                        wpWidgets.saveOrder($sidebar.attr('id'));
                    }
                },

                activate: function() {
                    $(this).parent().addClass('item-hover');
                },

                deactivate: function() {
                    // Remove all min-height added on "start"
                    $(this).css('min-height', '').parent().removeClass('item-hover');
                },

                receive: function(event, ui) {
                    var $sender = $(ui.sender);

                    // Don't add more items to orphaned sidebars
                    if (this.id.indexOf('orphaned_items') > -1) {
                        $sender.sortable('cancel');
                        return;
                    }

                    // If the last item was moved out of an orphaned sidebar, close and remove it.
                    if ($sender.attr('id').indexOf('orphaned_items') > -1 && !$sender.children('.item').length) {
                        $sender.parents('.orphan-sidebar').slideUp(400, function() {
                            $(this).remove();
                        });
                    }
                }
            }).sortable('option', 'connectWith', 'div.items-sortables');

            $('#available-items').droppable({
                tolerance: 'pointer',
                accept: function(o) {
                    return $(o).parent().attr('id') !== 'item-list';
                },
                drop: function(e, ui) {
                    ui.draggable.addClass('deleting');
                    $('#removing-item').hide().children('span').empty();
                },
                over: function(e, ui) {
                    ui.draggable.addClass('deleting');
                    $('div.item-placeholder').hide();

                    if (ui.draggable.hasClass('ui-sortable-helper')) {
                        $('#removing-item').show().children('span')
                            .html(ui.draggable.find('div.item-title').children('h3').html());
                    }
                },
                out: function(e, ui) {
                    ui.draggable.removeClass('deleting');
                    $('div.item-placeholder').show();
                    $('#removing-item').hide().children('span').empty();
                }
            });

            // Area Chooser
            $('#items-right .items-holder-wrap').each(function(index, element) {
                var $element = $(element),
                    name = $element.find('.sidebar-name h2').text(),
                    id = $element.find('.items-sortables').attr('id'),
                    li = $('<li tabindex="0">').text($.trim(name));

                if (index === 0) {
                    li.addClass('items-chooser-selected');
                }

                selectSidebar.append(li);
                li.data('sidebarId', id);
            });

            $('#available-items .item .item-title').on('click.items-chooser', function() {
                var $item = $(this).closest('.item');

                if ($item.hasClass('item-in-question') || $('#items-left').hasClass('chooser')) {
                    self.closeChooser();
                } else {
                    // Open the chooser
                    self.clearWidgetSelection();
                    $('#items-left').addClass('chooser');
                    $item.addClass('item-in-question').children('.item-description').after(chooser);

                    chooser.slideDown(300, function() {
                        selectSidebar.find('.items-chooser-selected').focus();
                    });

                    selectSidebar.find('li').on('focusin.items-chooser', function() {
                        selectSidebar.find('.items-chooser-selected').removeClass('items-chooser-selected');
                        $(this).addClass('items-chooser-selected');
                    });
                }
            });

            // Add event handlers
            chooser.on('click.items-chooser', function(event) {
                var $target = $(event.target);

                if ($target.hasClass('button-primary')) {
                    self.addWidget(chooser);
                    self.closeChooser();
                } else if ($target.hasClass('items-chooser-cancel')) {
                    self.closeChooser();
                }
            }).on('keyup.items-chooser', function(event) {
                if (event.which === $.ui.keyCode.ENTER) {
                    if ($(event.target).hasClass('items-chooser-cancel')) {
                        // Close instead of adding when pressing Enter on the Cancel button
                        self.closeChooser();
                    } else {
                        self.addWidget(chooser);
                        self.closeChooser();
                    }
                } else if (event.which === $.ui.keyCode.ESCAPE) {
                    self.closeChooser();
                }
            });
        },

        saveOrder: function(sidebarId) {
            var data = {
                action: 'zoo_ln_save_filter_order',
                zoo_ln_nonce_setting: $('#zoo_ln_nonce_setting').val(),
                item_list_id: $('#item_list_id').val(),
                sidebars: []
            };

            if (sidebarId) {
                $('#' + sidebarId).find('.spinner:first').addClass('is-active');
            }

            $('div.items-sortables').each(function() {

                if ($(this).sortable) {
                    data['sidebars[' + $(this).attr('id') + ']'] = $(this).sortable('toArray').join(',');
                }
            });
            $.post(ajaxurl, data, function(result) {
                $('#inactive-items-control-remove').prop('disabled', !$('#wp_inactive_items .item').length);
                $('.spinner').removeClass('is-active');
            });
        },

        save: function(item, del, animate, order) {
            var sidebarId = item.closest('div.items-sortables').attr('id'),
                data = item.find('form').serialize(),
                a;

            item = $(item);


            $('.spinner', item).addClass('is-active');

            a = {
                action: 'zoo_ln_save_filter',
                zoo_ln_nonce_setting: $('#zoo_ln_nonce_setting').val(),
                item_list_id: $('#item_list_id').val(),
                sidebar: sidebarId
            };

            if (del) {
                a.delete_item = 1;
            }

            data += '&' + $.param(a);

            $.post(ajaxurl, data, function(r) {
                var id;

                if (del) {
                    if (!$('input.item_number', item).val()) {
                        id = $('input.item-id', item).val();
                        $('#available-items').find('input.item-id').each(function() {
                            if ($(this).val() === id) {
                                $(this).closest('div.item').show();
                            }
                        });
                    }

                    if (animate) {
                        order = 0;
                        item.slideUp('fast', function() {
                            $(this).remove();
                            wpWidgets.saveOrder();
                        });
                    } else {
                        item.remove();

                        if (sidebarId === 'wp_inactive_items') {
                            $('#inactive-items-control-remove').prop('disabled', !$('#wp_inactive_items .item').length);
                        }
                    }
                } else {
                    item.attr('id', r['item-id']);
                    item.find('.item-inside input.item-id').val(r['item-id']);

                    $('.spinner').removeClass('is-active');


                    if (r && r.length > 2) {
                        $('div.item-content', item).html(r);
                        wpWidgets.appendTitle(item);
                        $document.trigger('item-updated', [item]);

                        if (sidebarId === 'wp_inactive_items') {
                            $('#inactive-items-control-remove').prop('disabled', !$('#wp_inactive_items .item').length);
                        }
                    }
                }

                if (order) {
                    wpWidgets.saveOrder();
                }
            });
        },

        removeInactiveWidgets: function() {
            var $element = $('.remove-inactive-items'),
                a, data;

            $('.spinner', $element).addClass('is-active');

            a = {
                action: 'delete-inactive-items',
                removeinactiveitems: $('#_wpnonce_remove_inactive_items').val()
            };

            data = $.param(a);

            $.post(ajaxurl, data, function() {
                $('#wp_inactive_items .item').remove();
                $('#inactive-items-control-remove').prop('disabled', true);
                $('.spinner', $element).removeClass('is-active');
            });
        },

        appendTitle: function(item) {
            var title = $('input[id*="-title"]', item).val() || '';

            if (title) {
                title = ': ' + title.replace(/<[^<>]+>/g, '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            }

            $(item).children('.item-top').children('.item-title').children()
                .children('.in-item-title').html(title);

        },

        close: function(item) {
            item.children('.item-inside').slideUp('fast', function() {
                item.attr('style', '')
                    .find('.item-top button.item-action')
                    .attr('aria-expanded', 'false')
                    .focus();
            });
        },

        addWidget: function(chooser) {
            var item, itemId, add, n, viewportTop, viewportBottom, sidebarBounds,
                sidebarId = chooser.find('.items-chooser-selected').data('sidebarId'),
                sidebar = $('#' + sidebarId);

            item = $('#available-items').find('.item-in-question').clone();
            itemId = item.attr('id');
            add = item.find('input.add_new').val();
            n = item.find('input.multi_number').val();

            // Remove the cloned chooser from the item
            item.find('.items-chooser').remove();

            if ('multi' === add) {
                item.html(
                    item.html().replace(/<[^<>]+>/g, function(m) {
                        return m.replace(/__i__|%i%/g, n);
                    })
                );

                item.attr('id', itemId.replace('__i__', n));
                n++;
                $('#' + itemId).find('input.multi_number').val(n);
            } else if ('single' === add) {
                item.attr('id', 'new-' + itemId);
                $('#' + itemId).hide();
            }

            // Open the items container
            sidebar.closest('.items-holder-wrap').removeClass('closed');

            sidebar.append(item);
            sidebar.sortable('refresh');

            wpWidgets.save(item, 0, 0, 1);
            // No longer "new" item
            item.find('input.add_new').val('');

            $document.trigger('item-added', [item]);

            /*
             * Check if any part of the sidebar is visible in the viewport. If it is, don't scroll.
             * Otherwise, scroll up to so the sidebar is in view.
             *
             * We do this by comparing the top and bottom, of the sidebar so see if they are within
             * the bounds of the viewport.
             */
            viewportTop = $(window).scrollTop();
            viewportBottom = viewportTop + $(window).height();
            sidebarBounds = sidebar.offset();

            sidebarBounds.bottom = sidebarBounds.top + sidebar.outerHeight();

            if (viewportTop > sidebarBounds.bottom || viewportBottom < sidebarBounds.top) {
                $('html, body').animate({
                    scrollTop: sidebarBounds.top - 130
                }, 200);
            }

            window.setTimeout(function() {
                // Cannot use a callback in the animation above as it fires twice,
                // have to queue this "by hand".
                item.find('.item-title').trigger('click');
            }, 250);
        },

        closeChooser: function() {
            var self = this;

            $('.items-chooser').slideUp(200, function() {
                $('#wpbody-content').append(this);
                self.clearWidgetSelection();
            });
        },

        clearWidgetSelection: function() {
            $('#items-left').removeClass('chooser');
            $('.item-in-question').removeClass('item-in-question');
        },

        /**
         * Closes a Sidebar that was previously closed, but opened by dragging a Widget over it.
         *
         * Used when a Widget gets dragged in/out of the Sidebar and never dropped.
         *
         * @param sidebar
         */
        closeSidebar: function(sidebar) {
            this.hoveredSidebar.addClass('closed');
            $(sidebar.target).css('min-height', '');
            this.hoveredSidebar = null;
        }
    };

    $document.ready(function() {
        wpWidgets.init();
    });
})(jQuery);
