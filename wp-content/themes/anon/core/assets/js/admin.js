(function ($, window, wp) {
    'use strict';

    var DOC = $(window.document);

    jQuery.fn.extend({
        media_upload: function() {
            var _custom_media = true,
                _orig_send_attachment = wp.media.editor.send.attachment;
            $(this).live('click', function (e) {
                var button_id = '#' + $(this).attr('id');
                var wrapper_id = '#' + $(this).parent('p').attr('id');
                var button = $(button_id);
                var id = button.attr('id').replace('_button', '');
                _custom_media = true;
                wp.media.editor.send.attachment = function (props, attachment) {
                    if (_custom_media) {
                        $(wrapper_id).find('.custom_media_url').val(attachment.url);
                        $(wrapper_id).find('.custom_media_image').attr('src', attachment.url).css('display', 'block');
                    } else {
                        return _orig_send_attachment.apply(button_id, [props, attachment]);
                    }
                }
                wp.media.editor.open(button);
                return false;
            });
        }
    });

    jQuery(document).ready(function () {
       $('.custom_media_button.button').media_upload();
    });

    // Switch theme advanced options tabs
    $(".zoo-theme-settings .nav-item").on("click", function(e)
    {
        e.preventDefault();

        var items, tabs, activeItem, activeTab;

        activeItem = $(this);
        items = $(".zoo-theme-settings .nav-item");
        tabs = $(".zoo-theme-settings .tab-table");
        activeTab = $(activeItem.attr("href"));

        items.removeClass("active-item");
        activeItem.addClass("active-item");
        tabs.hide();
        tabs.removeClass("active-tab");
        activeTab.addClass("active-tab");
        activeTab.show();
    });

    /**
     * Handle demo actions
     */
    DOC.on('click', '.zoo-theme-setup-site-demo .demo-import', function(e) {
        var btn = $(this),
            demo = btn.data('style'),
            action = btn.data('action'),
            parent = btn.parent(),
            spinner = parent.find('.demo-spinner'),
            previewBtn = parent.find('.demo-preview'),
            sep = parent.find('.sep'),
            installedItem = $('.demo-item.current'),
            currentItem = parent.parents('.demo-item');

        spinner.css({display: 'inline-block'});
        previewBtn.hide();
        sep.hide();

        if ('install' === action && btn.text() === zooAdminL10n.install) {
            btn.text(zooAdminL10n.installing);
            $.post(ajaxurl, {
                action: 'zoo_setup_demo_content',
                zoo_demo_slug: demo
            }).done(function(r){
                var r = JSON.parse(r);
                if (r.success) {
                    installedItem.removeClass('current');
                    installedItem.find('.demo-label').fadeOut();
                    installedItem.find('.demo-import').text(zooAdminL10n.install);
                    currentItem.addClass('current');
                    currentItem.find('.demo-content').append($('<span class="demo-label">'+zooAdminL10n.installed+'</span>').hide().fadeIn(500));
                    spinner.hide();
                    previewBtn.show();
                    sep.show();
                    btn.text(zooAdminL10n.uninstall);
                    btn.attr('data-action', 'uninstall');
                } else {
                    console.log(r);
                    spinner.hide();
                    previewBtn.show();
                    btn.text(zooAdminL10n.install);
                    sep.show();
                }
            }).fail(function(r){
                console.log(r);
            });
        } else {
            btn.text(zooAdminL10n.uninstalling);
            $.post(ajaxurl, {
                action: 'zoo_uninstall_demo_content',
                zoo_demo_slug: demo
            }).done(function(r){
                var r = JSON.parse(r);
                if (r.success) {
                    currentItem.removeClass('current');
                    currentItem.find('.demo-label').fadeOut();
                    spinner.hide();
                    previewBtn.show();
                    sep.show();
                    btn.text(zooAdminL10n.install);
                    btn.attr('data-action', 'install');
                    btn.removeClass('install').addClass('uninstall');
                }
            }).fail(function(r){
                console.log(r);
            });
        }
    });
})(jQuery, window, wp)
