<?php
/**
 * CSS Functionality
 *
 * @package  Zoo_Theme\Core\Functions
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */

/**
 * Minify
 *
 * @param  string  $css
 *
 * @return  string
 */
function zoo_css__minify($css)
{
    if (!trim($css))
        return '';

    return preg_replace(
        [
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
        ],
        ['$1', '$1$2$3$4$5$6$7'],
        $css
    );
}

/**
 * Convert HEX to RGBA
 *
 * @param  string  $hex      HEX color
 * @param  float   $opacity  CSS opacity value for the RGBA color.
 *
 * @return  string  RGBA color
 */
function zoo_hex2rgba($hex, $opacity = 1)
{
    $hex = str_replace("#", "", $hex);

    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }

    $rgba = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $opacity . ')';

    return $rgba;
}

/**
 * Convert PX to REM
 *
 * The returned value is relative to the body font-size of the current theme in the theme mod.
 *
 * @param  int  $var
 *
 * @return  string
 */
function zoo_px2rem($var)
{
    $body_font = get_theme_mod('zoo_typo_body_font');
    $base = apply_filters( 'zoo_body_font_size', 14 );

    if (isset($body_font['font-size'])) {
        $base = (int)str_replace(' ', 'px', $body_font['font-size']);
    }

    $var = $var / $base;

    return $var . 'rem';
}

/**
 * Generate CSS color
 *
 * @param  string  $class  CSS class selector.
 * @param  string  $var    CSS valid color value.
 *
 * @return  string
 */
function zoo_generate_css_color($class, $var)
{
    if ($var != '') {
        $color = $class . '{';
        $color .= "color: " . $var . ";}";
        return $color;
    } else {
        return '';
    }
}

function zoo_generate_css_font_size($class, $var)
{
    $font = $class . '{';
    $font .= "font-size: " . zoo_px2rem((int)str_replace(' ', 'px', $var)) . ";}";
    return $font;
}

/**
 * Generate CSS font
 *
 * @param  string  $class  CSS class selector.
 * @param  string  $var    CSS valid font attributes.
 *
 * @return  string
 */
function zoo_generate_css_font($class, $var)
{
    $font = '';

    if (isset($var['font-family']))
        $font .= "font-family: '{$var['font-family']}', sans-serif;";

    if (isset($var['font-size']))
        $font .= "font-size: " . zoo_px2rem((int)str_replace(' ', 'px', $var['font-size'])) . ";";

    if (isset($var['variant'])) {
        $font_weight = ($var['variant'] == 'regular') ? 'normal' : $var['variant'];
        $font .= "font-weight: {$font_weight};";
    }

    if (isset($var['line-height']))
        $font .= "line-height: {$var['line-height']};";

    if (isset($var['letter-spacing']))
        $font .= "letter-spacing: {$var['letter-spacing']};";

    if (isset($var['text-transform']))
        $font .= "text-transform: {$var['text-transform']};";

    if (isset($var['color']))
        $font .= "color: {$var['color']};";

    if ($font != '')
        $font = $class . '{' . $font . '}';

    return $font;
}

/**
 * Import Google fonts
 *
 * @param  array  $list_fonts
 * @param  bool   $load         Whether to directly load fonts or not.
 *
 * @return  string
 */
function zoo_import_google_fonts(array $list_fonts, $load = true)
{
    $fonts_url = '';
    $subsets   = $list_variant = $fonts = array();
    $subsets[] = 'latin';
    $subsets[] = 'latin-ext';
    $subset    = _x('no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'anon');

    if (!empty($list_fonts)) {
        foreach ($list_fonts as $font) {
            if ($font!= '') {
                if (is_array($font)) {
                    if ('cyrillic' == $subset) {
                        $subsets[] = 'cyrillic';
                        $subsets[] = 'cyrillic-ext';
                    } elseif ('greek' == $subset) {
                        $subsets[] = 'greek';
                        $subsets[] = 'greek-ext';
                    } elseif ('devanagari' == $subset) {
                        $subsets[] = 'devanagari';
                    } elseif ('vietnamese' == $subset) {
                        $subsets[] = 'vietnamese';
                    }
                    if($font['font-family']!=' ') {
                        if (!isset($fonts[$font['font-family']])) {
                            $fonts[$font['font-family']] = str_replace(' ', '+', $font['font-family']) . (isset($font['variant']) ? ':' . $font['variant'] : '');
                            if (isset($font['variant'])) {
                                $list_variant[$font['font-family']][] = $font['variant'];
                            }
                        } else {
                            if (isset($font['variant'])) {
                                if (isset($list_variant[$font['font-family']])) {
                                    if (!in_array($font['variant'], $list_variant[$font['font-family']])) {
                                        $list_variant[$font['font-family']][] = $font['variant'];
                                        $fonts[$font['font-family']] .= ',' . $font['variant'];
                                    }
                                } else {
                                    $list_variant[$font['font-family']][] = $font['variant'];
                                    $fonts[$font['font-family']] .= ':' . $font['variant'];
                                }

                            }
                        }
                    }
                } else {
                    $fonts[] = $font;
                }
            }
        }

    }

    if ($fonts) {
        if ($load) {
            $fonts_url = add_query_arg(array(
                'family' => implode('|', $fonts),
                'subset' => implode(',', $subsets),
            ), 'https://fonts.googleapis.com/css');
        } else {
            $fonts_url = add_query_arg(array(
                'family' => urlencode(implode('|', $fonts)),
                'subset' => urlencode(implode(',', $subsets)),
            ), 'https://fonts.googleapis.com/css');
        }

    }

    return $fonts_url;
}


/**
 * Get font icons by prefix
 *
 * @param  string  $prefix  Font's prefix. E.g. `fa`, `cs-font`...
 *
 * @throw  InvalidArgumentException
 *
 * @return array
 */
function zoo_get_font_icons($prefix = '')
{
    $fonts = [
        'cs-font' => [" clever-icon-comment-1"," clever-icon-comment-2"," clever-icon-comment-3"," clever-icon-comment-4"," clever-icon-360-2"," clever-icon-360-1"," clever-icon-heart-6"," clever-icon-heart-5"," clever-icon-filter-3"," clever-icon-refresh-5"," clever-icon-heart-4"," clever-icon-heart-3"," clever-icon-ruler"," clever-icon-help"," clever-icon-hand-up"," clever-icon-hand-down"," clever-icon-arrow-up"," clever-icon-arrow-down"," clever-icon-arrow-left-4"," clever-icon-arrow-right-4"," clever-icon-refresh-4"," clever-icon-refresh-3"," clever-icon-quote-2"," clever-icon-pause"," clever-icon-check"," clever-icon-caret-down"," clever-icon-caret-left"," clever-icon-caret-right"," clever-icon-caret-up"," clever-icon-caret-square-dow"," clever-icon-caret-square-left"," clever-icon-caret-square-right"," clever-icon-caret-square-up"," clever-icon-check-circle-o"," clever-icon-check-circle"," clever-icon-check-square-o"," clever-icon-check-square"," clever-icon-circle-o"," clever-icon-circle"," clever-icon-dribbble"," clever-icon-flickr"," clever-icon-foursquare"," clever-icon-github"," clever-icon-linkedin"," clever-icon-rss"," clever-icon-square-o"," clever-icon-square"," clever-icon-star-o"," clever-icon-star"," clever-icon-tumblr"," clever-icon-xing"," clever-icon-twitter"," clever-icon-cart-16"," clever-icon-heart-2"," clever-icon-eye-5"," clever-icon-facebook"," clever-icon-googleplus"," clever-icon-instagram"," clever-icon-pinterest"," clever-icon-skype"," clever-icon-vimeo"," clever-icon-youtube-1"," clever-icon-award-1"," clever-icon-clock-1"," clever-icon-three-dots"," clever-icon-share-2"," clever-icon-building"," clever-icon-faucet"," clever-icon-flower"," clever-icon-house-1"," clever-icon-house"," clever-icon-pines"," clever-icon-plant"," clever-icon-sprout-1"," clever-icon-sprout"," clever-icon-trees"," clever-icon-close-1"," clever-icon-list-2"," clever-icon-grid-5"," clever-icon-menu-6"," clever-icon-three-dots-o"," clever-icon-list-1"," clever-icon-menu-5"," clever-icon-menu-4"," clever-icon-heart-1"," clever-icon-user-6"," clever-icon-attachment"," clever-icon-cart-18"," clever-icon-ball"," clever-icon-battery"," clever-icon-briefcase"," clever-icon-car"," clever-icon-cpu-2"," clever-icon-cpu-1"," clever-icon-dress-woman"," clever-icon-drill-tool"," clever-icon-feeding-bottle"," clever-icon-fruit"," clever-icon-furniture-2"," clever-icon-furniture-1"," clever-icon-shoes-woman-2"," clever-icon-shoes-woman-1"," clever-icon-horse"," clever-icon-laptop"," clever-icon-lipstick"," clever-icon-iron"," clever-icon-perfume"," clever-icon-baby-toy-2"," clever-icon-baby-toy-1"," clever-icon-paint-roller"," clever-icon-shirt"," clever-icon-shoe-man-2"," clever-icon-small-diamond"," clever-icon-tivi"," clever-icon-smartphone"," clever-icon-lights"," clever-icon-microwave"," clever-icon-wardrobe"," clever-icon-washing-machine"," clever-icon-watch-2"," clever-icon-watch-1"," clever-icon-slider-3"," clever-icon-slider-2"," clever-icon-slider-1"," clever-icon-cart-15"," clever-icon-cart-14"," clever-icon-cart-13"," clever-icon-cart-12"," clever-icon-cart-11"," clever-icon-cart-10"," clever-icon-cart-9"," clever-icon-cart-8"," clever-icon-pause-1"," clever-icon-arrow-left"," clever-icon-arrow-left-1"," clever-icon-arrow-left-2"," clever-icon-arrow-left-3"," clever-icon-arrow-right"," clever-icon-arrow-right-1"," clever-icon-arrow-right-2"," clever-icon-arrow-right-3"," clever-icon-plane-1"," clever-icon-cart-17"," clever-icon-filter-2"," clever-icon-filter-1"," clever-icon-grid-1"," clever-icon-contract"," clever-icon-expand"," clever-icon-cart-7"," clever-icon-quote-1"," clever-icon-arrow-right-5"," clever-icon-arrow-left-5"," clever-icon-refresh-2"," clever-icon-truck"," clever-icon-wallet"," clever-icon-electric-1"," clever-icon-electric-2"," clever-icon-lock"," clever-icon-share-1"," clever-icon-check-box"," clever-icon-clock"," clever-icon-analytics-laptop"," clever-icon-code-design"," clever-icon-competitive-chart"," clever-icon-computer-monitor-and-cellphone"," clever-icon-consulting-message"," clever-icon-creative-process"," clever-icon-customer-reviews"," clever-icon-data-visualization"," clever-icon-document-storage"," clever-icon-download-2"," clever-icon-download-1"," clever-icon-email-envelope"," clever-icon-file-sharing"," clever-icon-finger-touch-screen"," clever-icon-horizontal-tablet-with-pencil"," clever-icon-illustration-tool"," clever-icon-keyboard-and-hands"," clever-icon-landscape-image"," clever-icon-layout-squares"," clever-icon-mobile-app-developing"," clever-icon-online-purchase"," clever-icon-online-shopping"," clever-icon-online-video"," clever-icon-optimization-clock"," clever-icon-padlock-key"," clever-icon-pc-monitor"," clever-icon-place-localizer"," clever-icon-search-results"," clever-icon-search-tool"," clever-icon-settings-tools"," clever-icon-sharing-symbol"," clever-icon-site-map"," clever-icon-smartphone-2"," clever-icon-tablet-2"," clever-icon-thin-expand-arrows"," clever-icon-upload-2"," clever-icon-upload-1"," clever-icon-volume-off"," clever-icon-volume-on"," clever-icon-news-list"," clever-icon-desktop"," clever-icon-news-grid"," clever-icon-setting"," clever-icon-web-home"," clever-icon-web-link"," clever-icon-web-links"," clever-icon-website-protection"," clever-icon-team"," clever-icon-zoom-in"," clever-icon-zoom-out"," clever-icon-arrow-1"," clever-icon-arrow-bold"," clever-icon-arrow-light"," clever-icon-arrow-regular"," clever-icon-cart-1"," clever-icon-cart-2"," clever-icon-cart-3"," clever-icon-cart-4"," clever-icon-cart-5"," clever-icon-cart-6"," clever-icon-chart"," clever-icon-close"," clever-icon-compare-1"," clever-icon-compare-2"," clever-icon-compare-3"," clever-icon-compare-4"," clever-icon-compare-5"," clever-icon-compare-6"," clever-icon-compare-7"," clever-icon-down"," clever-icon-grid"," clever-icon-hand"," clever-icon-layout-1"," clever-icon-layout"," clever-icon-light"," clever-icon-play"," clever-icon-list"," clever-icon-mail-1"," clever-icon-mail-2"," clever-icon-mail-3"," clever-icon-mail-4"," clever-icon-mail-5"," clever-icon-map-1"," clever-icon-map-2"," clever-icon-map-3"," clever-icon-map-4"," clever-icon-map-5"," clever-icon-menu-1"," clever-icon-menu-2"," clever-icon-grid-3"," clever-icon-grid-4"," clever-icon-menu-3"," clever-icon-grid-2"," clever-icon-minus"," clever-icon-next"," clever-icon-phone-1"," clever-icon-phone-2"," clever-icon-phone-3"," clever-icon-phone-4"," clever-icon-phone-5"," clever-icon-phone-6"," clever-icon-picture"," clever-icon-pin"," clever-icon-plus"," clever-icon-prev"," clever-icon-eye-4"," clever-icon-eye-3"," clever-icon-eye-2"," clever-icon-eye-1"," clever-icon-refresh-1"," clever-icon-youtube-2"," clever-icon-search-1"," clever-icon-search-2"," clever-icon-search-3"," clever-icon-search-4"," clever-icon-search-5"," clever-icon-support"," clever-icon-tablet-1"," clever-icon-play-2"," clever-icon-up"," clever-icon-user-1"," clever-icon-user-2"," clever-icon-user-3"," clever-icon-user-4"," clever-icon-user-5"," clever-icon-user"," clever-icon-vector"," clever-icon-wishlist"],
        'zoo-icon' => ['zoo-icon-menu','zoo-icon-cart','zoo-icon-heart','zoo-icon-heart-o','zoo-icon-refresh','zoo-icon-user','zoo-icon-search','zoo-icon-filter','zoo-icon-close','zoo-icon-plus','zoo-icon-minus','zoo-icon-up','zoo-icon-down','zoo-icon-caret-up','zoo-icon-caret-down','zoo-icon-arrow-up','zoo-icon-arrow-down','zoo-icon-arrow-left','zoo-icon-arrow-right'],
    ];

    if ('' === $prefix) {
        return $fonts;
    } elseif (isset($fonts[$prefix])) {
        return $fonts[$prefix];
    } else {
        throw new Exception(sprintf(esc_html__('Font with the prefix "%s" not found!', 'anon'), $prefix));
    }
}
