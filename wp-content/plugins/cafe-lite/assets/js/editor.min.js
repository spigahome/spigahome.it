(function($) {

    'use strict';

    var TemplateKitConf = window.TemplateKitConf || {},
        TemplateKitEditor,
        TemplateKitViews;

    TemplateKitViews = {

        LibraryLayoutView: null,
        LibraryHeaderView: null,
        LibraryLoadingView: null,
        LibraryErrorView: null,
        LibraryBodyView: null,
        LibraryCollectionView: null,
        FiltersCollectionView: null,
        LibraryTabsCollectionView: null,
        LibraryTabsItemView: null,
        FiltersItemView: null,
        LibraryTemplateItemView: null,
        LibraryInsertTemplateBehavior: null,
        LibraryTabsCollection: null,
        LibraryCollection: null,
        CategoriesCollection: null,
        LibraryTemplateModel: null,
        CategoryModel: null,
        TabModel: null,
        KeywordsModel: null,
        KeywordsView: null,
        LibraryPreviewView: null,
        LibraryHeaderBack: null,
        LibraryHeaderInsertButton: null,

        init: function() {

            var self = this;

            self.LibraryTemplateModel = Backbone.Model.extend({
                defaults: {
                    template_id: 0,
                    name: '',
                    title: '',
                    thumbnail: '',
                    preview: '',
                    source: '',
                    categories: [],
                    keywords: []
                }
            });

            self.CategoryModel = Backbone.Model.extend({
                defaults: {
                    slug: '',
                    title: ''
                }
            });

            self.CategoryModel = Backbone.Model.extend({
                defaults: {
                    slug: '',
                    title: ''
                }
            });

            self.TabModel = Backbone.Model.extend({
                defaults: {
                    slug: '',
                    title: ''
                }
            });

            self.KeywordsModel = Backbone.Model.extend({
                defaults: {
                    keywords: {}
                }
            });

            self.LibraryCollection = Backbone.Collection.extend({
                model: self.LibraryTemplateModel
            });

            self.CategoriesCollection = Backbone.Collection.extend({
                model: self.CategoryModel
            });

            self.LibraryTabsCollection = Backbone.Collection.extend({
                model: self.TabModel
            });

            self.LibraryLoadingView = Marionette.ItemView.extend({
                id: 'cafe-templatekit-loading',
                template: '#tmpl-template-kit-loading'
            });

            self.LibraryErrorView = Marionette.ItemView.extend({
                id: 'cafe-templatekit-error',
                template: '#tmpl-template-kit-error'
            });

            self.KeywordsView = Marionette.ItemView.extend({
                id: 'cafe-templatekit-keywords',
                template: '#tmpl-template-kit-keywords',
                ui: {
                    keywords: '.cafe-tplkit-library-keywords'
                },

                events: {
                    'change @ui.keywords': 'onSelectKeyword'
                },

                onSelectKeyword: function(event) {
                    var selected = event.currentTarget.selectedOptions[0].value;
                    TemplateKitEditor.setFilter('keyword', selected);
                }
            });

            self.LibraryHeaderView = Marionette.LayoutView.extend({

                id: 'cafe-templatekit-header',
                template: '#tmpl-template-kit-header',

                ui: {
                    closeModal: '#cafe-templatekit-header-close-modal'
                },

                events: {
                    'click @ui.closeModal': 'onCloseModalClick'
                },

                regions: {
                    headerTabs: '#cafe-templatekit-header-tabs',
                    headerActions: '#cafe-templatekit-header-actions'
                },

                onCloseModalClick: function() {
                    TemplateKitEditor.closeModal();
                }

            });

            self.LibraryPreviewView = Marionette.ItemView.extend({

                template: '#tmpl-template-kit-preview',

                id: 'cafe-templatekit-preview',

                ui: {
                    img: 'img'
                },

                onRender: function() {
                    this.ui.img.attr('src', this.getOption('preview'));
                }
            });

            self.LibraryHeaderBack = Marionette.ItemView.extend({

                template: '#tmpl-template-kit-header-back',

                id: 'cafe-templatekit-header-back',

                ui: {
                    button: 'button'
                },

                events: {
                    'click @ui.button': 'onBackClick',
                },

                onBackClick: function() {
                    TemplateKitEditor.setPreview('back');
                }

            });

            self.LibraryInsertTemplateBehavior = Marionette.Behavior.extend({
                ui: {
                    insertButton: '.cafe-templatekit-template-insert'
                },

                events: {
                    'click @ui.insertButton': 'onInsertButtonClick'
                },

                onInsertButtonClick: function() {

                    var templateModel = this.view.model,
                        options = {};

                    var source = templateModel.get('source');

                    TemplateKitEditor.layout.showLoadingView();

                    elementor.templates.requestTemplateContent(
                        source,
                        templateModel.get('template_id'), {
                            data: {
                                tab: TemplateKitEditor.getTab(),
                                page_settings: true
                            },
                            success: function(data) {

                                if (data.licenseError) {
                                    TemplateKitEditor.layout.showLicenseError();
                                    return;
                                }

                                TemplateKitEditor.closeModal();

                                elementor.channels.data.trigger('template:before:insert', templateModel);

                                if (null !== TemplateKitEditor.atIndex) {
                                    options.at = TemplateKitEditor.atIndex;
                                }

                                elementor.sections.currentView.addChildModel(data.content, options);

                                if (data.page_settings) {
                                    elementor.settings.page.model.set(data.page_settings);
                                }

                                elementor.channels.data.trigger('template:after:insert', templateModel);

                                TemplateKitEditor.atIndex = null;

                            }
                        }
                    );
                }
            });

            self.LibraryHeaderInsertButton = Marionette.ItemView.extend({

                template: '#tmpl-template-kit-insert-button',

                id: 'cafe-templatekit-insert-button',

                behaviors: {
                    insertTemplate: {
                        behaviorClass: self.LibraryInsertTemplateBehavior
                    }
                }

            });

            self.LibraryTemplateItemView = Marionette.ItemView.extend({

                template: '#tmpl-template-kit-item',

                className: function() {

                    var urlClass = ' cafe-tplkit-template-has-url',
                        sourceClass = ' elementor-template-library-template-';

                    if ('' === this.model.get('preview')) {
                        urlClass = ' cafe-tplkit-template-no-url';
                    }

                    if ('cafe-local-template' === this.model.get('source')) {
                        sourceClass += 'local';
                    } else {
                        sourceClass += 'remote';
                    }

                    return 'elementor-template-library-template' + sourceClass + urlClass;
                },

                ui: function() {
                    return {
                        previewButton: '.elementor-template-library-template-preview',
                        cloneButton: '.cafe-tplkit-clone-to-library',
                    };
                },

                events: function() {
                    return {
                        'click @ui.previewButton': 'onPreviewButtonClick'
                    };
                },

                onPreviewButtonClick: function() {
                    let slug = this.model.get('slug');

                    if (!slug) {
                        return;
                    }

                    window.open(TemplateKitConf.previewBaseUrl + slug);

                    return;

                    // TemplateKitEditor.setPreview(this.model);
                },

                behaviors: {
                    insertTemplate: {
                        behaviorClass: self.LibraryInsertTemplateBehavior
                    }
                }
            });

            self.FiltersItemView = Marionette.ItemView.extend({

                template: '#tmpl-template-kit-filters-item',

                className: function() {
                    return 'cafe-tplkit-filter-item';
                },

                ui: function() {
                    return {
                        filterLabels: '.cafe-templatekit-filter-label'
                    };
                },

                events: function() {
                    return {
                        'click @ui.filterLabels': 'onFilterClick'
                    };
                },

                onFilterClick: function(event) {

                    var $clickedInput = jQuery(event.target);

                    TemplateKitEditor.setFilter('category', $clickedInput.val());
                }

            });

            self.LibraryTabsItemView = Marionette.ItemView.extend({

                template: '#tmpl-template-kit-tabs-item',

                className: function() {
                    return 'elementor-template-library-menu-item';
                },

                ui: function() {
                    return {
                        tabsLabels: 'label',
                        tabsInput: 'input'
                    };
                },

                events: function() {
                    return {
                        'click @ui.tabsLabels': 'onTabClick'
                    };
                },

                onRender: function() {
                    if (this.model.get('slug') === TemplateKitEditor.getTab()) {
                        this.ui.tabsInput.attr('checked', 'checked');
                    }
                },

                onTabClick: function(event) {

                    var $clickedInput = jQuery(event.target);
                    TemplateKitEditor.setTab($clickedInput.val());
                    TemplateKitEditor.setFilter('keyword', '');
                }

            });

            self.LibraryCollectionView = Marionette.CompositeView.extend({

                template: '#tmpl-template-kit-templates',

                id: 'cafe-templatekit-templates',

                childViewContainer: '#cafe-templatekit-templates-container',

                initialize: function() {
                    this.listenTo(TemplateKitEditor.channels.templates, 'filter:change', this._renderChildren);
                },

                filter: function(childModel) {

                    var filter = TemplateKitEditor.getFilter('category'),
                        keyword = TemplateKitEditor.getFilter('keyword');

                    if (!filter && !keyword) {
                        return true;
                    }

                    if (keyword && !filter) {
                        return _.contains(childModel.get('keywords'), keyword);
                    }

                    if (filter && !keyword) {
                        return _.contains(childModel.get('categories'), filter);
                    }

                    return _.contains(childModel.get('categories'), filter) && _.contains(childModel.get('keywords'), keyword);

                },

                getChildView: function(childModel) {
                    return self.LibraryTemplateItemView;
                },

                onRenderCollection: function() {

                    var container = this.$childViewContainer,
                        items = this.$childViewContainer.children(),
                        tab = TemplateKitEditor.getTab();

                    if ('page' === tab || 'cafe-local-template' === tab) {
                        return;
                    }

                    setTimeout(function() {
                        self.masonry.init({
                            container: container,
                            items: items,
                        });
                    }, 200);

                }

            });

            self.LibraryTabsCollectionView = Marionette.CompositeView.extend({

                template: '#tmpl-template-kit-tabs',

                childViewContainer: '#cafe-templatekit-tabs-items',

                initialize: function() {
                    this.listenTo(TemplateKitEditor.channels.layout, 'tamplate:cloned', this._renderChildren);
                },

                getChildView: function(childModel) {
                    return self.LibraryTabsItemView;
                }

            });

            self.FiltersCollectionView = Marionette.CompositeView.extend({

                id: 'cafe-templatekit-filters',

                template: '#tmpl-template-kit-filters',

                childViewContainer: '#cafe-templatekit-filters-container',

                getChildView: function(childModel) {
                    return self.FiltersItemView;
                }

            });

            self.LibraryBodyView = Marionette.LayoutView.extend({

                id: 'cafe-templatekit-content',

                className: function() {
                    return 'library-tab-' + TemplateKitEditor.getTab();
                },

                template: '#tmpl-template-kit-content',

                regions: {
                    contentTemplates: '.cafe-tplkit-templates-list',
                    contentFilters: '.cafe-tplkit-filters-list',
                    contentKeywords: '.cafe-tplkit-keywords-list'
                }

            });

            self.LibraryLayoutView = Marionette.LayoutView.extend({

                el: '#cafe-templatekit-modal',

                regions: TemplateKitConf.modalRegions,

                initialize: function() {

                    this.getRegion('modalHeader').show(new self.LibraryHeaderView());
                    this.listenTo(TemplateKitEditor.channels.tabs, 'filter:change', this.switchTabs);
                    this.listenTo(TemplateKitEditor.channels.layout, 'preview:change', this.switchPreview);

                },

                switchTabs: function() {
                    this.showLoadingView();
                    TemplateKitEditor.setFilter('keyword', '');
                    TemplateKitEditor.requestTemplates(TemplateKitEditor.getTab());
                },

                switchPreview: function() {

                    var header = this.getHeaderView(),
                        preview = TemplateKitEditor.getPreview();

                    if ('back' === preview) {

                        header.headerTabs.show(new self.LibraryTabsCollectionView({
                            collection: TemplateKitEditor.collections.tabs
                        }));

                        header.headerActions.empty();

                        TemplateKitEditor.setTab(TemplateKitEditor.getTab());
                        return;
                    }

                    if ('initial' === preview) {
                        header.headerActions.empty();
                        return;
                    }

                    this.getRegion('modalContent').show(new self.LibraryPreviewView({
                        'preview': preview.get('preview')
                    }));

                    header.headerTabs.show(new self.LibraryHeaderBack());
                    header.headerActions.show(new self.LibraryHeaderInsertButton({
                        model: preview
                    }));

                },

                getHeaderView: function() {
                    return this.getRegion('modalHeader').currentView;
                },

                getContentView: function() {
                    return this.getRegion('modalContent').currentView;
                },

                showLoadingView: function() {
                    this.modalContent.show(new self.LibraryLoadingView());
                },

                showLicenseError: function() {
                    this.modalContent.show(new self.LibraryErrorView());
                },

                showTemplatesView: function(templatesCollection, categoriesCollection, keywords) {

                    this.getRegion('modalContent').show(new self.LibraryBodyView());

                    var contentView = this.getContentView(),
                        header = this.getHeaderView(),
                        keywordsModel = new self.KeywordsModel({
                            keywords: keywords
                        });

                    TemplateKitEditor.collections.tabs = new self.LibraryTabsCollection(TemplateKitEditor.getTabs());

                    header.headerTabs.show(new self.LibraryTabsCollectionView({
                        collection: TemplateKitEditor.collections.tabs
                    }));

                    contentView.contentTemplates.show(new self.LibraryCollectionView({
                        collection: templatesCollection
                    }));

                    contentView.contentFilters.show(new self.FiltersCollectionView({
                        collection: categoriesCollection
                    }));

                    contentView.contentKeywords.show(new self.KeywordsView({
                        model: keywordsModel
                    }));

                }

            });
        },

        masonry: {

            self: {},
            elements: {},

            init: function(settings) {

                var self = this;
                self.settings = $.extend(self.getDefaultSettings(), settings);
                self.elements = self.getDefaultElements();

                self.run();
            },

            getSettings: function(key) {
                if (key) {
                    return this.settings[key];
                } else {
                    return this.settings;
                }
            },

            getDefaultSettings: function() {
                return {
                    container: null,
                    items: null,
                    columnsCount: 3,
                    verticalSpaceBetween: 30
                };
            },

            getDefaultElements: function() {
                return {
                    $container: jQuery(this.getSettings('container')),
                    $items: jQuery(this.getSettings('items'))
                };
            },

            run: function() {
                var heights = [],
                    distanceFromTop = this.elements.$container.position().top,
                    settings = this.getSettings(),
                    columnsCount = settings.columnsCount;

                distanceFromTop += parseInt(this.elements.$container.css('margin-top'), 10);

                this.elements.$container.height('');

                this.elements.$items.each(function(index) {
                    var row = Math.floor(index / columnsCount),
                        indexAtRow = index % columnsCount,
                        $item = jQuery(this),
                        itemPosition = $item.position(),
                        itemHeight = $item[0].getBoundingClientRect().height + settings.verticalSpaceBetween;

                    if (row) {
                        var pullHeight = itemPosition.top - distanceFromTop - heights[indexAtRow];
                        pullHeight -= parseInt($item.css('margin-top'), 10);
                        pullHeight *= -1;
                        $item.css('margin-top', pullHeight + 'px');
                        heights[indexAtRow] += itemHeight;
                    } else {
                        heights.push(itemHeight);
                    }
                });

                this.elements.$container.height(Math.max.apply(Math, heights));
            }
        }

    };

    TemplateKitEditor = {

        modal: false,
        layout: false,
        collections: {},
        tabs: {},
        defaultTab: '',
        channels: {},
        atIndex: null,

        init: function() {

            window.elementor.on(
                'preview:loaded',
                window._.bind(TemplateKitEditor.onPreviewLoaded, TemplateKitEditor)
            );

            TemplateKitViews.init();

        },

        onPreviewLoaded: function() {

            setTimeout(() => {
                this.initMagicButton();

                window.elementor.$previewContents.on(
                    'click.addTemplateKitTemplate',
                    '.add-cafe-tplkit-template',
                    _.bind(this.showTemplatesModal, this)
                );
            }, 500)

            this.channels = {
                templates: Backbone.Radio.channel('TEMPLATEKIT_THEME_EDITOR:templates'),
                tabs: Backbone.Radio.channel('TEMPLATEKIT_THEME_EDITOR:tabs'),
                layout: Backbone.Radio.channel('TEMPLATEKIT_THEME_EDITOR:layout'),
            };

            this.tabs = TemplateKitConf.tabs;
            this.defaultTab = TemplateKitConf.defaultTab;

        },

        initMagicButton: function() {

            var addTemplateBtn = window.elementor.$previewContents.find('.elementor-add-new-section .elementor-add-template-button'),
                addTemplateKitTemplate = $('#tmpl-template-kit-add-button').html(),
                $addTemplateKitTemplate;

            if (addTemplateBtn.length) {
                $addTemplateKitTemplate = $(addTemplateKitTemplate).insertAfter(addTemplateBtn);
            }

            window.elementor.$previewContents.on(
                'click.addTemplateKitTemplate',
                '.elementor-editor-section-settings .elementor-editor-element-add',
                function() {

                    var $this = $(this),
                        $section = $this.closest('.elementor-top-section'),
                        modelID = $section.data('model-cid');

                    if (window.elementor.sections.currentView.collection.length) {
                        $.each(window.elementor.sections.currentView.collection.models, function(index, model) {
                            if (modelID === model.cid) {
                                TemplateKitEditor.atIndex = index;
                            }
                        });
                    }

                    if (TemplateKitConf.libraryButton) {
                        setTimeout(function() {
                            var $addNew = $section.prev('.elementor-add-section').find('.elementor-add-new-section');
                            $addNew.prepend(addTemplateKitTemplate);
                        }, 100);
                    }

                }
            );
        },

        getFilter: function(name) {
            return this.channels.templates.request('filter:' + name);
        },

        setFilter: function(name, value) {
            this.channels.templates.reply('filter:' + name, value);
            this.channels.templates.trigger('filter:change');
        },

        getTab: function() {
            return this.channels.tabs.request('filter:tabs');
        },

        setTab: function(value, silent) {

            this.channels.tabs.reply('filter:tabs', value);

            if (!silent) {
                this.channels.tabs.trigger('filter:change');
            }

        },

        getTabs: function() {

            var tabs = [];

            _.each(this.tabs, function(item, slug) {
                tabs.push({
                    slug: slug,
                    title: item.title
                });
            });

            return tabs;
        },

        getPreview: function(name) {
            return this.channels.layout.request('preview');
        },

        setPreview: function(value, silent) {

            this.channels.layout.reply('preview', value);

            if (!silent) {
                this.channels.layout.trigger('preview:change');
            }
        },

        getKeywords: function() {

            var keywords = [];

            _.each(this.keywords, function(title, slug) {
                tabs.push({
                    slug: slug,
                    title: title
                });
            });

            return keywords;
        },

        showTemplatesModal: function() {

            this.getModal().show();

            if (!this.layout) {
                this.layout = new TemplateKitViews.LibraryLayoutView();
                this.layout.showLoadingView();
            }

            this.setTab(this.defaultTab, true);
            this.requestTemplates(this.defaultTab);
            this.setPreview('initial');

        },

        requestTemplates: function(tabName) {

            var self = this,
                tab = self.tabs[tabName];

            self.setFilter('category', false);

            if (tab.data.templates && tab.data.categories) {
                self.layout.showTemplatesView(tab.data.templates, tab.data.categories, tab.data.keywords);
            } else {
                $.ajax({
                    url: ajaxurl,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        action: 'templatekit_get_templates',
                        tab: tabName,
                    },
                    success: function(response) {

                        var templates = new TemplateKitViews.LibraryCollection(response.data.templates),
                            categories = new TemplateKitViews.CategoriesCollection(response.data.categories);

                        self.tabs[tabName].data = {
                            templates: templates,
                            categories: categories,
                            keywords: response.data.keywords
                        };

                        self.layout.showTemplatesView(templates, categories, response.data.keywords);
                    }
                });
            }

        },

        closeModal: function() {
            this.getModal().hide();
        },

        getModal: function() {

            if (!this.modal) {
                this.modal = elementor.dialogsManager.createWidget('lightbox', {
                    id: 'cafe-templatekit-modal',
                    closeButton: false
                });
            }

            return this.modal;

        }

    };

    $(window).on('elementor:init', TemplateKitEditor.init);

})(jQuery);
