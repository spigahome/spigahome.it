(function($) {
    'use strict';

    function conditialToggle(control, target, expectedValue) {
        const value = control.val();

        if (value == expectedValue) {
            target.show();
        } else {
            target.hide();
        }

        control.on('change', function(e) {
            const change = $(this).val();

            if (change == expectedValue) {
                target.show();
            } else {
                target.hide();
            }
        });
    }

    $(function() {
        $('#filter_view_style').on('change', function() {
            if ($(this).find(":selected").val() == 'horizontal') {
                $('.horizontal-options').removeClass('hidden')
            } else {
                $('.horizontal-options').addClass('hidden')
            }
        });
    });
})(jQuery);
