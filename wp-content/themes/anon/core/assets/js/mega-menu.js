var zooMegaMenu;

(function ($, _) {
	'use strict';

	var api;
	api = zooMegaMenu = {
		init: function () {
			api.$body = $('body');
			api.$modal = $('#zoo-settings');
			api.itemData = {};
			api.templates = {
				menus     : _.template($('#zoo-tmpl-menus').html()),
				title     : _.template($('#zoo-tmpl-title').html()),
				mega      : _.template($('#zoo-tmpl-mega').html()),
				background: _.template($('#zoo-tmpl-background').html()),
				content   : _.template($('#zoo-tmpl-content').html())
			};

			api.frame = wp.media({
				library: {
					type: 'image'
				}
			});

            $('.menu-item .menu-item-handle').append('<span class="menu-item-edit"><a href="#" class="button button-primary menu-item-edit-button"><i class="dashicons dashicons-edit"></i> Edit</a>');

            for (var id in cleverMenuItems) {
                if (cleverMenuItems.hasOwnProperty(id)) {
                    var item = cleverMenuItems[id];
                    if (item.mega === '1') {
                        $('#menu-item-' + id).addClass('mega-menu-item');
                    } else {
                        $('#menu-item-' + id).removeClass('mega-menu-item');
                    }
                }
            }

			this.initActions();
		},

		initActions: function () {
			api.$body.on('click', '.menu-item-edit .menu-item-edit-button', this.openModal);
			api.$body.on('click', '.zoo-modal-backdrop, .zoo-modal-close, .zoo-button-cancel', this.closeModal);
            api.$body.on('change', 'input.zoo-enable-mega-menu', function(e)
            {
                var menuItem = $('#' + $(this).data('forMenuItem'));

                if ($(this).prop('checked')) {
                    menuItem.addClass('mega-menu-item');
                } else {
                    menuItem.removeClass('mega-menu-item');
                }
            });

			api.$modal
				.on('click', '.zoo-menu a', this.switchPanel)
				.on('click', '.zoo-column-handle', this.resizeMegaColumn)
				.on('click', '.zoo-button-save', this.saveChanges);
		},

		openModal: function () {
			api.getItemData(this);

			api.$modal.show();
			api.$body.addClass('modal-open');
			api.render();

			return false;
		},

		closeModal: function () {
			api.$modal.hide().find('.zoo-content').html('');
			api.$body.removeClass('modal-open');
			return false;
		},

		switchPanel: function (e) {
			e.preventDefault();

			var $el = $(this),
				panel = $el.data('panel');

			$el.addClass('active').siblings('.active').removeClass('active');
			api.openSettings(panel);
		},

		render: function () {
			// Render menu
			api.$modal.find('.zoo-frame-menu .zoo-menu').html(api.templates.menus(api.itemData));

			var $activeMenu = api.$modal.find('.zoo-menu a.active');

			// Render content
			this.openSettings($activeMenu.data('panel'));
		},

		openSettings: function (panel) {
			var $content = api.$modal.find('.zoo-frame-content .zoo-content'),
				$panel = $content.children('#zoo-panel-' + panel);

			if ($panel.length) {
				$panel.addClass('active').siblings().removeClass('active');
			} else {
				if (typeof api.templates[panel] !== undefined) {
                    $content.append(api.templates[panel](api.itemData));
                }
				$content.children('#zoo-panel-' + panel).addClass('active').siblings().removeClass('active');

				if ('mega' == panel) {
					api.initMegaColumns();
				}
				if ('background' == panel) {
					api.initBackgroundFields();
				}
				if ('content' == panel) {
					api.initContentImageFields();
				}
			}

			// Render title
			var title = api.$modal.find('.zoo-frame-menu .zoo-menu a[data-panel=' + panel + ']').data('title');
			api.$modal.find('.zoo-frame-title').html(api.templates.title({title: title}));
		},

		resizeMegaColumn: function (e) {
			e.preventDefault();

			var steps = ['25.00%', '33.33%', '50.00%', '66.66%', '75.00%', '100.00%'],
				$el = $(this),
				$column = $el.closest('.zoo-submenu-column'),
				width = $column.data('width'),
				current = _.indexOf(steps, width),
				next;

			if (-1 === current) {
				return;
			}

			if ($el.hasClass('zoo-resizable-w')) {
				next = current == steps.length ? current : current + 1;
			} else {
				next = current == 0 ? current : current - 1;
			}

			$column[0].style.width = steps[next];
			$column.data('width', steps[next]);
			$column.find('.menu-item-depth-0 .menu-item-width').val(steps[next]);
		},

		initMegaColumns: function () {
			var $columns = api.$modal.find('#zoo-panel-mega .zoo-submenu-column'),
				defaultWidth = '25.00%';

			if (!$columns.length) {
				return;
			}

			// Support maximum 4 columns
			if ($columns.length < 4) {
				defaultWidth = String(( 100 / $columns.length ).toFixed(2)) + '%';
			}

			_.each($columns, function (column) {
				var width = column.dataset.width;

				width = width || defaultWidth;

				column.style.width = width;
				column.dataset.width = width;
				$(column).find('.menu-item-depth-0 .menu-item-width').val(width);
			});
		},

		initBackgroundFields: function () {
			api.$modal.find('.background-color-picker').wpColorPicker();

			// Background image
			api.$modal.on('click', '.background-image .upload-button', function (e) {
				e.preventDefault();

				var $el = $(this);

				// Remove all attached 'select' event
				api.frame.off('select');

				// Update inputs when select image
				api.frame.on('select', function () {
					// Update input value for single image selection
					var url = api.frame.state().get('selection').first().toJSON().url;

					$el.siblings('.background-image-preview').html('<img src="' + url + '">');
					$el.siblings('input').val(url);
					$el.siblings('.remove-button').removeClass('hidden');
				});

				api.frame.open();
			}).on('click', '.background-image .remove-button', function (e) {
				e.preventDefault();

				var $el = $(this);

				$el.siblings('.background-image-preview').html('');
				$el.siblings('input').val('');
				$el.addClass('hidden');
			});

			// Background position
			api.$modal.on('change', '.background-position select', function () {
				var $el = $(this);

				if ('custom' == $el.val()) {
					$el.next('input').removeClass('hidden');
				} else {
					$el.next('input').addClass('hidden');
				}
			});
		},

		initContentImageFields: function () {
			// Background image
			api.$modal.on('click', '.content-image .upload-button', function (e) {
				e.preventDefault();

				var $el = $(this);

				// Remove all attached 'select' event
				api.frame.off('select');

				// Update inputs when select image
				api.frame.on('select', function () {
					// Update input value for single image selection
					var url = api.frame.state().get('selection').first().toJSON().url;

					$el.siblings('.content-image-preview').html('<img src="' + url + '">');
					$el.siblings('input').val(url);
					$el.siblings('.remove-button').removeClass('hidden');
				});

				api.frame.open();
			}).on('click', '.content-image .remove-button', function (e) {
				e.preventDefault();

				var $el = $(this);

				$el.siblings('.content-image-preview').html('');
				$el.siblings('input').val('');
				$el.addClass('hidden');
			});
		},

		getItemData: function (menuItem) {
			var $menuItem = $(menuItem).closest('li.menu-item'),
				$menuData = cleverMenuItems[$menuItem.attr('id').replace(/menu-item-/, '')],
				children = $menuItem.childMenuItems();

			api.itemData = {
				depth          : $menuItem.menuItemDepth(),
				megaData       : {
					mega         : $menuData.mega,
					mega_width   : $menuData.megaWidth,
					width        : $menuData.width,
					background   : $menuData.background,
					contentImage : $menuData.contentImage,
					hideText     : $menuData.hideText,
					hot          : $menuData.hot,
					new          : $menuData.new,
					trending     : $menuData.trending,
					disableLink  : $menuData.disableLink,
					content      : $menuData.content
				},
				data           : $menuItem.getItemData(),
				children       : [],
				originalElement: $menuItem.get(0)
			};

			if (!_.isEmpty(children)) {
				_.each(children, function (item) {
					var $item = $(item),
						$itemData = cleverMenuItems[$item.attr('id').replace(/menu-item-/, '')],
						depth = $item.menuItemDepth();

					api.itemData.children.push({
						depth          : depth,
						subDepth       : depth - api.itemData.depth - 1,
						data           : $item.getItemData(),
						megaData       : {
							mega         : $itemData.mega,
							mega_width   : $itemData.megaWidth,
							width        : $itemData.width,
							background   : $itemData.background,
							hideText     : $itemData.hideText,
							hot          : $itemData.hot,
							new          : $itemData.new,
							trending     : $itemData.trending,
							disableLink  : $itemData.disableLink,
							content      : $itemData.content
						},
						originalElement: item
					});
				});
			}

		},

		setItemData: function (item, data, depth) {
			if (!_.has(data, 'mega')) {
				data.mega = false;
			}


			if (depth == 0) {
				if (!_.has(data, 'hideText')) {
					data.hideText = false;
				}

				if (!_.has(data, 'hot')) {
					data.hot = false;
				}

				if (!_.has(data, 'trending')) {
					data.trending = false;
				}

				if (!_.has(data, 'new')) {
					data.new = false;
				}
			}

			var $dataHolder = cleverMenuItems[$(item).attr('id').replace(/menu-item-/, '')];

			if (_.has(data, 'content')) {
				delete data.content;
			}

			cleverMenuItems[$(item).attr('id').replace(/menu-item-/, '')] = data;

		},

		getFieldName: function (name, id) {
			name = name.split('.');
			name = '[' + name.join('][') + ']';

			return 'menu-item[' + id + ']' + name;
		},

		saveChanges: function () {
			var data = $('#menu, .zoo-content :input').serialize(),
				$spinner = api.$modal.find('.zoo-toolbar .spinner');

			$spinner.addClass('is-active');

			$.post(ajaxurl, {
				action: 'zoo_save_menu_item_data',
				data  : data
			}, function (res) {
				if (!res.success) {
					return;
				}


				var data = res.data['menu-item'];

				// Update parent menu item
				if (_.has(data, api.itemData.data['menu-item-db-id'])) {
					api.setItemData(api.itemData.originalElement, data[api.itemData.data['menu-item-db-id']], 0);
				}

				_.each(api.itemData.children, function (menuItem) {
					if (!_.has(data, menuItem.data['menu-item-db-id'])) {
						return;
					}

					api.setItemData(menuItem.originalElement, data[menuItem.data['menu-item-db-id']], 1);
				});

				$spinner.removeClass('is-active');
				api.closeModal();
			});
		}
	};

	$(function () {
		zooMegaMenu.init();
	});
})(jQuery, _);
