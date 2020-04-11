/**
 * Product embed js
 * Allow control light box video on single product page
 */
(function ($) {
    'use strict';
    var player;
    if ($('.wrap-product-video.vimeo-embed')[0]) {
        player = new Vimeo.Player($('.wrap-product-video.vimeo-embed iframe'));
    }
    $(document).on('click', '.video-lb-control', function (e) {
        e.preventDefault();
        $('.wrap-product-video').addClass('video-active');
        $('.product-extended-button').addClass('active');
        let window_w;
        window_w = $(window).width();
        let frame_w, frame_h;
        frame_w = window_w > 1170 ? 1170 : window_w - 60;
        frame_h = frame_w * 9 / 16;
        $('.wrap-product-video iframe').width(frame_w);
        $('.wrap-product-video iframe').height(frame_h);
        if ($('.wrap-product-video.vimeo-embed')[0]) {
            player.play();
        }
    });
    $(document).on('click', '.mask-product-video', function () {
        $('.video-active').removeClass('video-active');
        $('.product-extended-button').removeClass('active');
        if ($('.wrap-product-video.vimeo-embed')[0]) {
            player.pause();
        }
    });
    $(window).resize(function () {
        if ($('.video-active')[0]) {
            let window_w;
            window_w = $(window).width();
            let frame_w, frame_h;
            frame_w = window_w > 1170 ? 1170 : window_w - 60;
            frame_h = frame_w * 9 / 16;
            $('.wrap-product-video iframe').width(frame_w);
            $('.wrap-product-video iframe').height(frame_h);
        }
    });

})(jQuery);
var player;
function onYouTubePlayerAPIReady() {
    // create the global player from the specific iframe (#video)
    player = new YT.Player(jQuery('.wrap-product-video.youtube-embed iframe').attr('id'), {
        events:
            {
                // call this function when player is ready to use
                'onReady':onPlayerReady
            }
    });
}

function onPlayerReady(event) {
    jQuery(document).on('click', '.video-lb-control', function (e) {
        player.playVideo();
    });
    jQuery(document).on('click', '.mask-product-video', function () {
        player.pauseVideo();
    });
}