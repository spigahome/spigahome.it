/**
 * Module for handling products compare related actions.
 *
 * @author  hellocleversoft@gmail.com  CleverSoft
 * @supported  IE10+, Chrome, Safari
 */
(function (exports, $) {
    'use strict';

    if (!exports.Cookies) {
        console.error('WooCommerce Cookies Jar is not available.');
    }

    /**
     * ZooProductsCompareModel
     */
    var ZooProductsCompareModel = (function () {
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
            value: 'zooProductsCompareItems',
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
        Constructor.prototype.add = function (itemID) {
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
        Constructor.prototype.exists = function (itemID) {
            var items = this.list();

            if (-1 !== items.indexOf(itemID)) {
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
        Constructor.prototype.remove = function (itemID) {
            var items = this.list(),
                itemIndex = items.indexOf(itemID);

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
        Constructor.prototype.list = function () {
            return JSON.parse(window.localStorage.getItem(this.STORAGE_KEY));
        };

        /**
         * Remove all items
         */
        Constructor.prototype.clear = function () {
            window.localStorage.setItem(this.STORAGE_KEY, '[]');
            Cookies.remove(this.STORAGE_KEY);
        };

        return Constructor;
    }());

    /**
     * ZooProductsCompareView
     */
    var ZooProductsCompareView = (function () {
        function Constructor() {
            /** Cache DOM elements */
            this.panel = $('.products-compare-panel');
            this.counter = $('.cafe-wlcp-counter.compare-counter');
            this.addBtn = '.add-to-products-compare';
            this.removeBtn = '.remove-from-products-compare';
            this.browseBtn = '.browse-products-compare:not(.redirect-link)';

            // If no panel available, pre-fetch it.
            if (!this.panel.length) {
                this.renderPanel();
                this.panel = $('.products-compare-panel');
            }
        }

        /**
         * Render wishlist panel
         *
         * @param  {string}  Panel content.
         */
        Constructor.prototype.renderPanel = function (content) {
            content = !!content ? content : '<p>' + zooProductsCompareCDATA.compareIsEmpty + '</p>';

            if (this.panel.length) {
                this.panel.find('.products-compare-panel-inner').html(content);
            } else {
                $('body').append('<div class="products-compare-panel zoo-popup-panel" style="display:none"><div class="popup-mask-close loading"></div><button class="products-compare-panel-close-btn popup-close-btn" type="button" name="close-button"><i class="zoo-icon-close"></i></button><div class="products-compare-panel-inner zoo-popup-inner">' + content + '</div></div>');
            }
        }

        /**
         * Display add-to-products-compare button
         *
         * @param  {object}  jQuery DOM collection.
         */
        Constructor.prototype.renderAddButton = function (btn) {
            if (!btn instanceof jQuery)
                throw new TypeError('Factoring wishlist button is not an instance of jQuery.');

            if (btn.hasClass('browse-products-compare'))
                btn.removeClass('browse-products-compare');

            if (!btn.hasClass('add-to-products-compare'))
                btn.addClass('add-to-products-compare');

            btn.attr('title', zooProductsCompareCDATA.addToCompare);
            btn.html(zooProductsCompareCDATA.addToCompareIcon + zooProductsCompareCDATA.addToCompare);
        }

        /**
         * Display browse-products-compare button
         *
         * @param  {object}  jQuery DOM collection.
         */
        Constructor.prototype.renderBrowseButton = function (btn) {
            if (!btn instanceof jQuery)
                throw new TypeError('Factoring wishlist button is not an instance of jQuery.');

            btn = $('.zoo-compare-button[data-id='+btn.data('id')+']');

            btn.removeClass('add-to-products-compare');
            btn.not('.browse-products-compare').addClass('browse-products-compare');

            btn.attr('title', zooProductsCompareCDATA.browseCompare);
            btn.html(zooProductsCompareCDATA.browseCompareIcon + zooProductsCompareCDATA.browseCompare);
        }

        return Constructor;
    }());

    /**
     * ZooProductsCompareController
     */
    var ZooProductsCompareController = (function () {
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
            }else{
                this.view.counter.removeClass('zoo-hidden');
            }

            // Update add-to-products-compare buttons' actions.
            this.updateAddButtonsActions();

            // Observe wishlist buttons' actions.
            this.listen();
        }

        /**
         * Update view whenever model change
         */
        Constructor.prototype.sync = function (changeEvent) {
            // module has not been globally exported yet, just use the fixed key.
            if ('zooProductsCompareItems' === changeEvent.key) {
                var items = JSON.parse(window.localStorage.getItem('zooProductsCompareItems'));

                // update counter.
                $('.products-compare-counter').text(items.length);
                if (items.length < 1) {
                    $('.products-compare-counter').addClass('zoo-hidden');
                }
            }
        };

        /**
         * Update wishlist buttons' actions
         */
        Constructor.prototype.updateAddButtonsActions = function () {
            var self = this,
                addedItems = this.model.list();

            $(this.view.addBtn).each(function (index) {
                if (!this instanceof HTMLElement)
                    return;
                var itemID = parseInt(this.dataset.id);
                if (-1 !== addedItems.indexOf(itemID))
                    self.view.renderBrowseButton($(this));
            });
        };

        /**
         * Handle add event
         *
         * @param  {object}  addEvent  Emitted event object.
         */
        Constructor.prototype.addAction = function (addEvent) {
            addEvent.preventDefault();

            var target = $(addEvent.currentTarget),
                itemID = target.data('id');
            if (target.hasClass('browse-products-compare'))
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
                    if($(document).find('.zoo-product-popup-notice')[0]){
                        $(document).find('.zoo-product-popup-notice').addClass('active');
                    }else{
                        $('body').append('<div class="zoo-product-popup-notice">'+target.data("label-added")+'</div>');
                        setTimeout(function () {
                            $(document).find('.zoo-product-popup-notice').addClass('active');
                        },100);
                    }
                    setTimeout(function () {
                        $(document).find('.zoo-product-popup-notice').removeClass('active');
                    },1200);
                } else {
                    return this.browseAction(addEvent);
                }
            } else {
                alert(zooProductsCompareCDATA.addToCompareErr);
            }
        };

        /**
         * Handle remove event
         *
         * @param  {object}  removeEvent  Emitted event object.
         */
        Constructor.prototype.removeAction = function (removeEvent) {
            removeEvent.preventDefault();
            var itemID = $(removeEvent.currentTarget).data('id'),
                removed = this.model.remove(itemID);

            if (removed) {
                var itemsCount = this.model.list().length;
                if (itemsCount) {
                    this.view.panel.find('.products-compare-row-' + itemID).remove();
                } else {
                    this.view.renderPanel('<p>' + zooProductsCompareCDATA.compareIsEmpty + '</p>');
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
        Constructor.prototype.browseAction = function (browseEvent) {
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
                        action: 'zoo_get_compare_products',
                        compareItems: JSON.stringify(items)
                    }
                }).done(function (response) {
                    self.doingAJAX = false;

                    self.view.renderPanel(response.html);

                    // Update newly added remove buttons.
                    self.view.removeBtn = '.remove-from-products-compare';

                    // Observe newly added remove buttons.
                    $(document).on('click', self.view.removeBtn, function(removeEvent){self.removeAction(removeEvent)});

                    self.view.panel.find('.zoo-popup-inner').fadeIn();

                });
            } else {
                self.view.panel.find('.zoo-popup-inner').fadeIn()
            }
            $(document).trigger('zoo_browse_compare');
        };

        /**
         * Events' observer
         */
        Constructor.prototype.listen = function () {
            var $this=this;
            /** Listen for adding event */
            $(document).on('click', this.view.addBtn, function (addEvent){$this.addAction(addEvent)});

            /** Listen for removing event */
            if ($(this.view.removeBtn).length) {
                $(document).on('click', this.view.removeBtn, function(removeEvent){$this.removeAction(removeEvent)});
            }

            /** Listen for browsing event */
            $(document).on('click', this.view.browseBtn, function(browseEvent){$this.browseAction(browseEvent)});
            this.view.panel.find('.products-compare-panel-close-btn').on('click', function(closeEvent){$this.view.panel.fadeOut()});
            this.view.panel.find('.popup-mask-close').on('click', function(closeEvent){$this.view.panel.fadeOut()});

            /** Sync added items across all pages */
            window.addEventListener('storage', this.sync);
        }

        return Constructor;
    }());

    /** Load module when document already loaded. */
    $(document).ready(function () {
        exports.zooProductsCompare = new ZooProductsCompareController(new ZooProductsCompareModel(), new ZooProductsCompareView())
    })
}(window, jQuery))
