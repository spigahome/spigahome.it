//Using Custom js plugin, add add this script to Footer.
// In CleverSwatches settings page add this class  .product-images to CSS class Wrapper jQuery Selector

(function (t,e,i) {
    "use strict";
    t(document).ready(function () {
        t(document).bind('cleverswatch_update_gallery', function (event, response) {
            t(".product-images-container").each(function (i, o) {
                var n = t(o), r = n.hasClass("thumbnails-vertical"), s = n.hasClass("zoom-enabled"),
                    a = n.hasClass("auto-rotate") ? parseInt(n.data("autorotate"), 10) : 0,
                    l = n.find(".product-images--main"), c = l.children(), u = c.length, d = {
                        slide: ".woocommerce-product-gallery__image",
                        arrows: !0,
                        infinite: !1,
                        adaptiveHeight: !0,
                        rtl: isRTL(),
                        autoplay: !!a,
                        autoplaySpeed: 1e3 * a,
                        responsive: [{breakpoint: 768, settings: {vertical: !1}}]
                    };
                l.slick(d);
                var h = 0, p = !0, f = !1;
                l.on("afterChange", function (t, e, i) {
                    p = f = !1, u == i + 1 ? f = !0 : 0 == i && (p = !0)
                }), l.on("click", ".slick-arrow", function () {
                    f ? (l.slick("slickGoTo", 0), f = !1) : p && (l.slick("slickGoTo", u - 1), p = !1)
                });
                var m = n.find(".product-images--thumbnails"), v = function () {
                    return m.find(".woocommerce-product-gallery__image")
                }, g = {
                    slide: ".woocommerce-product-gallery__image",
                    arrows: !1,
                    customPaging: function (t, e) {
                        return t.slideCount > t.options.slidesToShow ? '<button type="button" role="tab">' + (e + 1) + "</button>" : ""
                    },
                    dots: !r,
                    infinite: !1,
                    adaptiveHeight: !0,
                    swipeToSlide: !0,
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    vertical: r,
                    verticalSwiping: r,
                    responsive: [{breakpoint: 576, settings: {vertical: !1, verticalSwiping: !1}}]
                };
                t.each([2, 3, 4, 5, 6], function (t, e) {
                    if (m.hasClass("columns-" + e)) return g.slidesToShow = e, !1
                }), m.slick(g), v().first().addClass("active"), m.on("click", ".woocommerce-product-gallery__image a", function (e) {
                    e.preventDefault();
                    var i = t(this).parent(".woocommerce-product-gallery__image");
                    l.slick("slickGoTo", parseInt(i.attr("data-slick-index"), 10));
                    var o = v().filter(".slick-active");
                    o.last().get(0) == i.get(0) ? m.slick("slickGoTo", m.slick("slickCurrentSlide") + 1) : o.first().get(0) == i.get(0) && m.slick("slickGoTo", m.slick("slickCurrentSlide") - 1)
                }), l.on("beforeChange", function (t, e, i, o) {
                    var n;
                    v().removeClass("active").eq(o).addClass("active"), v().eq(o).hasClass("slick-active") || m.slick("slickGoTo", o - (m.slick("slickGetOption", "slidesToShow") - 1))
                });
                var b = function (i) {
                    if (s && wc_single_product_params && wc_single_product_params.zoom_enabled) {
                        var o = t(this).find("a");
                        if (t(i).find("img").hasClass("product-placeholder-image")) return;
                        var n = t.extend({touch: !1, url: o.attr("href")}, wc_single_product_params.zoom_options);
                        i.trigger("zoom.destroy"), i.zoom(n);
                        //n.url || (n.url = i.find("[href]").attr("href")), "ontouchstart" in e && (n.on = "click"), i.trigger("zoom.destroy"), i.zoom(n)
                    }
                };
                c.each(function (e, i) {
                    b(t(i))
                }), l.on("image-zoom-setup", function (t, e) {
                    b(e)
                });
                var y = function (e) {
                    var i = [];
                    l.find(".woocommerce-product-gallery__image").each(function (e, o) {
                        var n, r = (n = t(o)).find("> a:has(img)"), n = r.find("img");
                        i.push({
                            src: r.attr("href"),
                            w: n.data("large_image_width"),
                            h: n.data("large_image_height"),
                            title: n.attr("data-caption") ? n.attr("data-caption") : n.attr("title")
                        })
                    });
                    var o = t.extend({index: e}, wc_single_product_params.photoswipe_options), n;
                    new PhotoSwipe(t(".pswp")[0], PhotoSwipeUI_Default, i, o).init()
                };
                l.on("click", ".product-gallery-lightbox-trigger", function (e) {
                    var i = parseInt(t(this).closest(".woocommerce-product-gallery__image").attr("data-slick-index"), 10);
                    y(i)
                });
                var w = n.closest(".single-product");
                s || l.on("click", ".woocommerce-product-gallery__image:has(.product-gallery-lightbox-trigger) > a", function (e) {
                    e.preventDefault();
                    var i = parseInt(t(this).closest(".woocommerce-product-gallery__image").attr("data-slick-index"), 10);
                    y(i)
                })
            });

        });
    })
})(jQuery);