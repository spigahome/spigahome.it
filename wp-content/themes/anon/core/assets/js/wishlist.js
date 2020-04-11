/**
 * Module for handling wishlist-related actions.
 *
 * @author  hellocleversoft@gmail.com  CleverSoft
 * @supported  IE10+, Chrome, Safari
 */
(function(exports, $) {
    'use strict';

    if (!exports.Cookies) {
        console.error('WooCommerce Cookies Jar is not available.');
    }

    /**
     * ZooWishlistModel
     */
    var ZooWishlistModel = (function() {
        /**
         * Shared contructor
         */
        function Constructor() {
            if (null === window.localStorage.getItem(this.STORAGE_KEY))
                window.localStorage.setItem(this.STORAGE_KEY, '[]');
        }

        /**
         * Key to retrieve saved wishlist items from localStorage.
         *
         * @const
         */
        Object.defineProperty(Constructor.prototype, 'STORAGE_KEY', {
            value: 'zooWishlistItems',
            writable: false,
            enumerable: false,
            configurable: false
        });

        /**
         * Add an item
         *
         * @param  {int}  itemID  Wishlist item ID.
         *
         * @return  {bool}  True if success, false otherwise.
         */
        Constructor.prototype.add = function(itemID) {
            var items = this.list();

            itemID = parseInt(itemID, 10);

            if (-1 === items.indexOf(itemID)) {
                items.push(itemID);
                var stringifiedItems = JSON.stringify(items);
                window.localStorage.setItem(this.STORAGE_KEY, stringifiedItems);
                Cookies.remove(this.STORAGE_KEY);
                Cookies.set(this.STORAGE_KEY, stringifiedItems);
                return true;
            } else {
                return false;
            }
        }

        /**
         * Check if an item already exists
         *
         * @param  object  item  Wishlist item with slug and id property.
         *
         * @return  {bool}  True if exists, false otherwise.
         */
        Constructor.prototype.exists = function(itemID) {
            var items = this.list();

            if (-1 !== items.indexOf(parseInt(itemID, 10))) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Remove an item
         *
         * @param  {int}  itemID
         *
         * @return  {bool}  true on success, false otherwise.
         */
        Constructor.prototype.remove = function(itemID) {
            var item = parseInt(itemID, 10),
                items = this.list(),
                itemIndex = items.indexOf(item);

            if (-1 !== itemIndex) {
                items.splice(itemIndex, 1);
                var stringifiedItems = JSON.stringify(items);
                window.localStorage.setItem(this.STORAGE_KEY, stringifiedItems);
                Cookies.remove(this.STORAGE_KEY);
                Cookies.set(this.STORAGE_KEY, stringifiedItems);
                return true;
            }

            return false;
        }

        /**
         * List all items
         *
         * @return  {array}
         */
        Constructor.prototype.list = function() {
            return JSON.parse(window.localStorage.getItem(this.STORAGE_KEY));
        };

        /**
         * Remove all items
         */
        Constructor.prototype.clear = function() {
            window.localStorage.setItem(this.STORAGE_KEY, '[]');
            Cookies.remove(this.STORAGE_KEY);
        };

        return Constructor;
    }());

    /**
     * ZooWishlistView
     */
    var ZooWishlistView = (function() {
        function Constructor() {
            /** Cache DOM elements */
            this.panel = $('.zoo-wishlist-panel');
            this.counter = $('.cafe-wlcp-counter');
            this.addBtn = '.add-to-wishlist';
            this.removeBtn = '.remove-from-wishlist';
            this.browseBtn = '.browse-wishlist:not(.redirect-link)';

            // If no panel available, pre-fetch it.
            if (!this.panel.length) {
                this.renderPanel();
                this.panel = $('.zoo-wishlist-panel');
            }
        }

        /**
         * Render wishlist panel
         *
         * @param  {string}  Panel content.
         */
        Constructor.prototype.renderPanel = function(content) {
            content = !!content ? content : '<p>' + zooWishlistCDATA.wishlistIsEmpty + '</p>';

            if (this.panel.length) {
                this.panel.find('.zoo-wishlist-panel-inner').html(content);
            } else {
                $('body').append('<div class="zoo-wishlist-panel zoo-popup-panel" style="display:none"><div class="loading popup-mask-close"></div><button class="zoo-wishlist-panel-close-btn popup-close-btn" type="button" name="close-button"><i class="zoo-icon-close"></i> </button><div class="zoo-wishlist-panel-inner zoo-popup-inner" style="display: none">' + content + '</div></div></div>');
            }
        }

        /**
         * Display add-to-wishlist button
         *
         * @param  {object}  jQuery DOM collection.
         */
        Constructor.prototype.renderAddButton = function(btn) {
            if (!btn instanceof jQuery || !btn.attr('data-id'))
                throw new TypeError('Invalid wishlist button. Product ID not found.');

            if (btn.hasClass('browse-wishlist'))
                btn.removeClass('browse-wishlist');

            if (!btn.hasClass('add-to-wishlist'))
                btn.addClass('add-to-wishlist');

            btn.attr('title', zooWishlistCDATA.addToWishlist);
            btn.html(zooWishlistCDATA.addToWishlistIcon + zooWishlistCDATA.addToWishlist);
        }

        /**
         * Display browse-wishlist button
         *
         * @param  {object}  jQuery DOM collection.
         */
        Constructor.prototype.renderBrowseButton = function(btn) {
            if (!btn instanceof jQuery || !btn.attr('data-id'))
                throw new TypeError('Invalid wishlist button. Product ID not found.');

            btn = $(document).find('.zoo-wishlist-button[data-id='+btn.attr('data-id')+']');

            btn.removeClass('add-to-wishlist');
            btn.not('.browse-wishlist').addClass('browse-wishlist');

            btn.attr('title', zooWishlistCDATA.browseWishlist);
            btn.html(zooWishlistCDATA.browseWishlistIcon + zooWishlistCDATA.browseWishlist);
        }

        return Constructor;
    }());

    /**
     * ZooWishlistController
     */
    var ZooWishlistController = (function() {
        /**
         * Shared contructor
         */
        function Constructor(model, view) {
            this.view = view;
            this.model = model;
            this.doingAJAX = false;

            // Pre-fetch added items count.
            var itemsCount = this.model.list().length;
            this.view.counter.text(itemsCount);
            if (itemsCount < 1) {
                this.view.counter.addClass('zoo-hidden');
            } else {
                this.view.counter.removeClass('zoo-hidden');
            }

            // Update add-to-wishlist buttons' actions.
            this.updateAddButtonsActions();

            // Observe wishlist buttons' actions.
            this.listen();
        }

        /**
         * Update view whenever model change
         */
        Constructor.prototype.sync = function(changeEvent) {
            // module has not been globally exported yet, just use the fixed key.
            if ('zooWishlistItems' === changeEvent.key) {
                var items = JSON.parse(window.localStorage.getItem('zooWishlistItems'));

                // update counter.
                $('.wishlist-counter').text(items.length);
                if (items.length < 1) {
                    $('.wishlist-counter').addClass('zoo-hidden');
                }
            }
        };

        /**
         * Fetch variation ID
         */
        Constructor.prototype.maybeFetchVariationId = function() {
            var self = this,
                variationInput = $('input[name="variation_id"]');

            if (variationInput.length) {
                variationInput.on('change', function(changeEvent) {
                    var items = self.model.list(),
                        variationId = parseInt(variationInput.val()),
                        btn = $(this).parent().find('.zoo-wishlist-button');
                    if (!variationId) return;
                    btn.attr('data-id', variationId);
                    if (-1 !== items.indexOf(variationId)) {
                        self.view.renderBrowseButton(btn);
                    } else {
                        if (!btn.hasClass(self.view.addBtn)) {
                            self.view.renderAddButton(btn);
                        }
                    }
                })
            }
        }

        /**
         * Update wishlist buttons' actions
         */
        Constructor.prototype.updateAddButtonsActions = function() {
            var self = this,
                addedItems = this.model.list();

            if ($('body').hasClass('single-product')) {
                self.maybeFetchVariationId();
            }

            $('.add-to-wishlist').each(function(index) {
                if (!this instanceof HTMLElement)
                    return;
                var itemID = parseInt($(this).attr('data-id'));
                if (-1 !== addedItems.indexOf(itemID))
                    self.view.renderBrowseButton($(this));
            });
        };

        /**
         * Handle add event
         *
         * @param  {object}  addEvent  Emitted event object.
         */
        Constructor.prototype.addAction = function(addEvent) {
            var target = $(addEvent.currentTarget),
                itemID = target.attr('data-id');

            if (target.hasClass('browse-wishlist') && target.hasClass('redirect-link')) {
                return;
            } else {
                addEvent.preventDefault();
                if (target.hasClass('browse-wishlist'))
                    return this.browseAction(addEvent);

                if (this.model.exists(itemID)) {
                    this.view.renderBrowseButton(target);
                    return;
                }

                if (this.model.add(itemID)) {
                    this.view.renderBrowseButton(target);
                    this.view.counter.removeClass('zoo-hidden');
                    this.view.counter.text(this.model.list().length);
                    if (target.hasClass('redirect-link')) {
                        if ($(document).find('.zoo-product-popup-notice')[0]) {
                            $(document).find('.zoo-product-popup-notice').addClass('active');
                        } else {
                            $('body').append('<div class="zoo-product-popup-notice">' + target.data("label-added") + '</div>');
                            setTimeout(function() {
                                $(document).find('.zoo-product-popup-notice').addClass('active');
                            }, 100);
                        }
                        setTimeout(function() {
                            $(document).find('.zoo-product-popup-notice').removeClass('active');
                        }, 1200);
                    } else {
                        return this.browseAction(addEvent);
                    }
                } else {
                    alert(zooWishlistCDATA.addToWishlistErr);
                }
            }
        };

        /**
         * Handle remove event
         *
         * @param  {object}  removeEvent  Emitted event object.
         */
        Constructor.prototype.removeAction = function(removeEvent) {
            removeEvent.preventDefault();

            var itemID = $(removeEvent.currentTarget).attr('data-id'),
                button = $('.zoo-wishlist-button[data-id="' + itemID + '"]'),
                removed = this.model.remove(itemID);

            if (button.length) {
                this.view.renderAddButton(button);
            }

            if (removed) {
                var itemsCount = this.model.list().length;
                if (itemsCount) {
                    this.view.panel.find('#wishlist-item-row-' + itemID).remove();
                } else {
                    this.view.renderPanel('<p>' + zooWishlistCDATA.wishlistIsEmpty + '</p>');
                }
                this.view.counter.text(itemsCount);
                if (itemsCount < 1) {
                    this.view.counter.addClass('zoo-hidden');
                }
            }
        };

        /**
         * Handle browse event
         *
         * @param  {object}  browseEvent  Event object emitted when "click" action is triggred on `browseBtn`.
         */
        Constructor.prototype.browseAction = function(browseEvent) {
            browseEvent.preventDefault();

            var self = this,
                items = this.model.list();
            self.view.panel.fadeIn();
            if (items.length && !this.doingAJAX) {
                this.doingAJAX = true;
                $.ajax({
                    method: 'POST',
                    url: ajaxurl,
                    data: {
                        action: 'zoo_get_wishlist_products',
                        wishlistItems: JSON.stringify(items)
                    }
                }).done(function(response) {
                    self.doingAJAX = false;

                    self.view.renderPanel(response.html);

                    // Update newly added remove buttons.
                    self.view.removeBtn = '.remove-from-wishlist';

                    // Observe newly added remove buttons.
                    $(document).on('click', self.view.removeBtn, function(removeEvent) {
                        self.removeAction(removeEvent)
                    });

                    self.view.panel.find('.zoo-wishlist-panel-inner').fadeIn();
                });
            } else {
                self.view.panel.find('.zoo-wishlist-panel-inner').fadeIn();
            }
            $(document).trigger('zoo_browse_wishlist');
        };

        /**
         * Events' observer
         */
        Constructor.prototype.listen = function() {
            var $this = this;
            /** Listen for adding event */
            $(document).on('click', this.view.addBtn, function(addEvent) {
                $this.addAction(addEvent)
            });

            /** Listen for removing event */
            if ($(this.view.removeBtn).length) {
                $(document).on('click', this.view.removeBtn, function(removeEvent) {
                    $this.removeAction(removeEvent)
                });
            }

            /** Listen for browsing event */
            $(document).on('click', this.view.browseBtn, function(browseEvent) {
                $this.browseAction(browseEvent)
            });
            this.view.panel.find('.zoo-wishlist-panel-close-btn').on('click', function(closeEvent) {
                $this.view.panel.fadeOut()
            });
            this.view.panel.find('.popup-mask-close').on('click', function(closeEvent) {
                $this.view.panel.fadeOut()
            });
            /** Sync added items across all pages */
            window.addEventListener('storage', this.sync);
        }

        return Constructor;
    }());

    /** Load module when document already loaded. */
    $(document).ready(function() {
        exports.zooWishlist = new ZooWishlistController(new ZooWishlistModel(), new ZooWishlistView());
    })
}(window, jQuery))
