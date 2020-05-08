(function ($) {
    'use strict';
    $( window ).on( 'elementor:init', function() {
        var ControlCleverIconItemView = elementor.modules.controls.BaseData.extend( {
            iconsList: function iconsList(icon) {
                if (!icon.id) {
                    return icon.text;
                }

                return jQuery('<span><i class="' + icon.id + '"></i> ' + icon.text + '</span>');
            },

            getSelect2Options: function getSelect2Options() {
                return {
                    allowClear: true,
                    templateResult: this.iconsList.bind(this),
                    templateSelection: this.iconsList.bind(this)
                };
            },
            onReady: function onReady() {
                this.ui.select.select2(this.getSelect2Options());
            },

            onBeforeDestroy: function onBeforeDestroy() {
                if (this.ui.select.data('select2')) {
                    this.ui.select.select2('destroy');
                }

                this.$el.remove();
            }
        } );
        elementor.addControlView( 'clevericon', ControlCleverIconItemView );
    } );
})
(jQuery);
