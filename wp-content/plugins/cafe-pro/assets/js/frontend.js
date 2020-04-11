// webpackModules
(function(modules) {
    // The module cache
    var $ = jQuery,
        installedModules = {};

    // The require function
    function __webpack_require__(moduleId) {

        // Check if module is in cache
        if (installedModules[moduleId]) {
            return installedModules[moduleId].exports;
        }
        // Create a new module (and put it into the cache)
        var module = installedModules[moduleId] = {
            i: moduleId,
            l: false,
            exports: {}
        };

        // Execute the module function
        modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

        // Flag the module as loaded
        module.l = true;

        // Return the exports of the module
        return module.exports;
    }

    // expose the modules object (__webpack_modules__)
    __webpack_require__.m = modules;

    // expose the module cache
    __webpack_require__.c = installedModules;

    // define getter function for harmony exports
    __webpack_require__.d = function(exports, name, getter) {
        if (!__webpack_require__.o(exports, name)) {
            Object.defineProperty(exports, name, {
                enumerable: true,
                get: getter
            });
        }
    };

    // define __esModule on exports
    __webpack_require__.r = function(exports) {
        if (typeof Symbol !== 'undefined' && Symbol.toStringTag) {
            Object.defineProperty(exports, Symbol.toStringTag, {
                value: 'Module'
            });
        }
        Object.defineProperty(exports, '__esModule', {
            value: true
        });
    };

    // create a fake namespace object
    // mode & 1: value is a module id, require it
    // mode & 2: merge all properties of value into the ns
    // mode & 4: return value when already ns object
    // mode & 8|1: behave like require
    __webpack_require__.t = function(value, mode) {
        if (mode & 1) value = __webpack_require__(value);
        if (mode & 8) return value;
        if ((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
        var ns = Object.create(null);
        __webpack_require__.r(ns);
        Object.defineProperty(ns, 'default', {
            enumerable: true,
            value: value
        });
        if (mode & 2 && typeof value != 'string')
            for (var key in value) __webpack_require__.d(ns, key, function(key) {
                return value[key];
            }.bind(null, key));
        return ns;
    };

    // getDefaultExport function for compatibility with non-harmony modules
    __webpack_require__.n = function(module) {
        var getter = module && module.__esModule ?
            function getDefault() {
                return module['default'];
            } :
            function getModuleExports() {
                return module;
            };
        __webpack_require__.d(getter, 'a', getter);
        return getter;
    };

    // Object.prototype.hasOwnProperty.call
    __webpack_require__.o = function(object, property) {
        return Object.prototype.hasOwnProperty.call(object, property);
    };

    // __webpack_public_path__
    __webpack_require__.p = '';

    // Load entry module and return exports
    return __webpack_require__(__webpack_require__.s = 0);
})
([
    /* 0 */
    (function(module, exports, __webpack_require__) {
        'use strict';

        jQuery(window).on('elementor/frontend/init', function() {
            var self = this,
                modules = {
                    slider: __webpack_require__(1)
                };
            self.modules = {};
            jQuery.each(modules, function(moduleName) {
                self.modules[moduleName] = new this(elementorFrontend);
            });
        });
    }),
    /* 1 */
    (function(module, exports, __webpack_require__) {
        'use strict';

        module.exports = function(elementor) {
            elementor.hooks.addAction('frontend/element_ready/clever-slider-pro.default', __webpack_require__(2));
        };
    }),
    /* 2 */
    (function(module, exports, __webpack_require__) {
        'use strict';

        var CleverSliderPro = elementorModules.frontend.handlers.Base.extend({
            getDefaultSettings: function() {
                return {
                    selectors: {
                        slider: '.cafe-slider-pro-slides',
                        slideBg: '.slick-slide-bg',
                        slideContent: '.cafe-slide-content'
                    },
                    classes: {
                        animated: 'animated'
                    },
                    attributes: {
                        dataOptions: 'slider_options',
                        dataAnimation: 'animation'
                    }
                };
            },

            getDefaultElements: function() {
                var selectors = this.getSettings('selectors');
                return {
                    $slider: this.$element.find(selectors.slider)
                };
            },

            /*Resize iframe fit to slider size*/
            resizeIframe: function($slider) {
                let slide_res = $slider.width() / $slider.height();
                $slider.find('.cafe-viewer-video iframe').each(function() {
                    let frame_res = jQuery(this).attr('width') / jQuery(this).attr('height');
                    if (frame_res > slide_res) {
                        jQuery(this).attr('width', frame_res * $slider.height());
                        jQuery(this).width(frame_res * $slider.height());
                        jQuery(this).attr('height', $slider.height());
                    } else {
                        jQuery(this).attr('width', $slider.width());
                        jQuery(this).width($slider.width());
                        jQuery(this).attr('height', $slider.width() / frame_res);
                    }
                })
            },

            animate: function(el) {
                const settings = el.data('settings'),
                    animation = settings._animation,
                    animationDelay = settings._animation_delay || settings.animation_delay || 0;

                if ('none' === animation) {
                    el.removeClass('elementor-invisible');
                    return;
                }

                el.removeClass(animation);

                if (this.currentAnimation) {
                    el.removeClass(this.currentAnimation);
                }

                this.currentAnimation = animation;

                setTimeout(() => el.removeClass('elementor-invisible').addClass('animated ' + animation), animationDelay);
            },

            initSlider: function() {
                var $slider = this.elements.$slider,
                    settings = this.getSettings();

                if (!$slider.length) {
                    return;
                }

                $slider.slick($slider.data(this.getSettings('attributes.dataOptions')));

                /*JS for slider video*/
                this.resizeIframe($slider);

                $slider.find('.cafe-viewer-video.vimeo-embed').each(function() {
                    var options = {
                        background: true
                    };
                    if (typeof Vimeo != 'undefined') {
                        var player = new Vimeo.Player(jQuery(this).find('iframe'), options);
                        player.setLoop(true);
                        var arg = jQuery(this).data('cafe-config');
                        if (arg.sound != 'yes') {
                            player.setVolume(0);
                        }
                        player.disableTextTrack();
                        jQuery(this).data('player', player);
                    }
                });

                if ($slider.find('.slick-active .cafe-viewer-video.vimeo-embed')[0]) {
                    let player = $slider.find('.slick-active .cafe-viewer-video.vimeo-embed').data('player');
                    player.play();
                }

                if ($slider.find('.slick-active .cafe-viewer-video.youtube-embed')[0]) {
                    let player = $slider.find('.slick-active .cafe-viewer-video.youtube-embed').data('player');
                    player.playVideo();
                }
                /*End JS for slider video*/
            },

            goToActiveSlide: function() {
                this.elements.$slider.slick('slickGoTo', this.getEditSettings('activeItemIndex') - 1);
            },

            onPanelShow: function() {
                var $slider = this.elements.$slider;

                $slider.slick('slickPause');

                // On switch between slides while editing. stop again.
                $slider.on('afterChange', () => $slider.slick('slickPause'));
            },

            bindEvents: function() {
                const self = this,
                    $slider = this.elements.$slider;

                if (elementorFrontend.isEditMode()) {
                    elementor.hooks.addAction('panel/open_editor/widget/clever-slider-pro', this.onPanelShow);
                }

                // Slick slider events.
                $slider.on({
                    init: function(event, slick) {

                    },
                    beforeChange: function(event, slick, currentSlideIndex, nextSlideIndex) {
                        const slide = jQuery('.slick-slide[data-slick-index="' + nextSlideIndex + '"]');
                        if (slide.length) {
                            const animatedElements = slide.find('.animated');
                            if (animatedElements.length) {
                                animatedElements.each(function(i) {
                                    self.animate(jQuery(this));
                                });
                            }
                        }
                    },
                    afterChange: function(event, slick, currentSlideIndex) {

                    }
                });

                // Window resize.
                jQuery(window).on('resize', e => this.resizeIframe($slider));
            },

            onInit: function() {
                elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

                this.initSlider();

                this.bindEvents();

                if (this.isEdit) {
                    this.goToActiveSlide();
                }
            },

            onEditSettingsChange: function(propertyName) {
                if ('activeItemIndex' === propertyName) {
                    this.goToActiveSlide();
                }
            }
        });

        module.exports = function($scope) {
            new CleverSliderPro({
                $element: $scope
            });
        };
    })
])
