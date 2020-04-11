<?php
/**
 * Import customize style
 *
 * @return Css inline at header.
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 
 */

// Render css customize
add_action('wp_enqueue_scripts', 'zoo_enqueue_render', 1000);
// Enqueue scripts for theme.
function zoo_enqueue_render()
{
    //Load font
    $zoo_fonts = array();
    $zoo_local_font = array();
    $zoo_google_font = array();
    if(get_theme_mod('zoo_use_font','google') != 'custom') {
        if (get_theme_mod('zoo_typo_base', '') == '' || get_theme_mod('zoo_typo_base')['font'] == '') {
            $zoo_fonts[] = array('font-family' => 'futura-pt', 'variant' => '400');
        }
        if (get_theme_mod('zoo_typo_heading', '') == '' || get_theme_mod('zoo_typo_heading')['font'] == '') {
            $zoo_fonts[] = array('font-family' => 'futura-pt', 'variant' => '500');
        }
        if (get_theme_mod('zoo_typo_woo', '') == '' || get_theme_mod('zoo_typo_woo')['font'] == '') {
            $zoo_fonts[] = array('font-family' => 'futura-pt', 'variant' => '500');
        }
        foreach ($zoo_fonts as $font) {
            if ($font) {
                if (in_array('futura-pt', $font)) {
                    $zoo_local_font[] = 'futura-pt';
                } else {
                    $zoo_google_font[] = $font;
                }
            }
        }
        if (!empty(array_filter($zoo_google_font))) {
            $zoo_google_font = zoo_import_google_fonts($zoo_google_font);
            wp_enqueue_style('zoo-font', $zoo_google_font, false, '');
        }
    }
    // Load custom style
    wp_add_inline_style('zoo-styles', zoo_customize_style($zoo_local_font));
    if (get_theme_mod('zoo_custom_js') != '') {
        wp_add_inline_script('zoo-scripts', zoo_customize_js());
    }
}

if (!function_exists('zoo_customize_js')) {
    function zoo_customize_js()
    {
        $zoo_script = '';
        if (get_theme_mod('zoo_custom_js') != '') {
            $zoo_script = get_theme_mod('zoo_custom_js');
        }
        return $zoo_script;
    }
}
if (!function_exists('zoo_customize_style')) {
    function zoo_customize_style($zoo_local_font = array())
    {
        /* ----------------------------------------------------------
                                    Responsive control
                            Control Breakpoint of header Layout
                            Don't remove this section
        ---------------------------------------------------------- */
        $css = '';
        $theme_settings = get_option(ZOO_SETTINGS_KEY, []);
        $mobile_breakpoint = !empty($theme_settings['mobile_breakpoint_width']) ? strval(intval($theme_settings['mobile_breakpoint_width'])) : '992';
        $css .= '@media(min-width: ' . $mobile_breakpoint . 'px) {
          .wrap-site-header-mobile {
            display: none;
          }
          .show-on-mobile {
            display: none;
          }
        }
        
        @media(max-width: ' . $mobile_breakpoint . 'px) {
          .wrap-site-header-desktop {
            display: none;
          }
          .show-on-desktop {
            display: none;
          }
        }';
        $css .= '@media(min-width:1500px){.container{max-width:' . zoo_site_width() . ';width:100%}}';
        /* ----------------------------------------------------------
                            End Responsive control
                    Control Breakpoint of header Layout
                    Don't remove this section
        ---------------------------------------------------------- */
        /* ----------------------------------------------------------
                                    Typography
                            All typography must add here
        ---------------------------------------------------------- */
        if (!empty($zoo_local_font)) {
            if (in_array('futura-pt', $zoo_local_font)) {
                $css .= '@font-face {
                    font-family: \'futura-pt\';
                    src: url(\'' . ZOO_THEME_URI.'assets/fonts/futurapt/futurapt-light.woff\') format(\'woff\');
                    font-weight: 300;
                    font-style: normal;
                    font-display: auto;
                }@font-face {
                    font-family: \'futura-pt\';
                    src: url(\'' . ZOO_THEME_URI.'assets/fonts/futurapt/futurapt-light-italic.woff\') format(\'woff\');
                    font-weight: 300;
                    font-style: italic;
                    font-display: auto;
                }
                
                @font-face {
                    font-family: \'futura-pt\';
                    src: url(\'' . ZOO_THEME_URI.'assets/fonts/futurapt/futurapt-book.woff\') format(\'woff\');
                    font-weight: 400;
                    font-style: normal;
                    font-display: auto;
                }@font-face {
                    font-family: \'futura-pt\';
                    src: url(\'' . ZOO_THEME_URI.'assets/fonts/futurapt/futurapt-book-italic.woff\') format(\'woff\');
                    font-weight: 400;
                    font-style: italic;
                    font-display: auto;
                }
               
                @font-face {
                    font-family: \'futura-pt\';
                    src: url(\'' . ZOO_THEME_URI.'assets/fonts/futurapt/futurapt-medium.woff\') format(\'woff\');
                    font-weight: 500;
                    font-style: normal;
                    font-display: auto;
                }@font-face {
                    font-family: \'futura-pt\';
                    src: url(\'' . ZOO_THEME_URI.'assets/fonts/futurapt/futurapt-medium-italic.woff\') format(\'woff\');
                    font-weight: 500;
                    font-style: italic;
                    font-display: auto;
                }
                ';
            }
        }
        if(get_theme_mod('zoo_use_font','google') == 'custom'){

            /* ----------------------------------------------------------
                                  Load Font Custom by User
           ---------------------------------------------------------- */

            $font_name = get_theme_mod('zoo_typo_new_font_family');
            $font_arr = get_theme_mod('zoo_font_items');
            foreach ($font_arr as $key => $font) {
                $css .= '@font-face {';
                $css .= 'font-family: '.$font_name.';';
                $css .= 'font-weight: '.$font['weight'].';';
                $css .= 'font-style:  '.$font['style'].';';

                $css .= 'src:';
                $arr  = array();
                if ( $font['woff2'] ) {
                    $arr[] = 'url(' . esc_url( $font['woff2'] ) . ") format('woff2')";
                }
                if ( $font['woff'] ) {
                    $arr[] = 'url(' . esc_url( $font['woff'] ) . ") format('woff')";
                }
                if ( $font['ttf'] ) {
                    $arr[] = 'url(' . esc_url( $font['ttf'] ) . ") format('truetype')";
                }
                if ( $font['svg'] ) {
                    $arr[] = 'url(' . esc_url( $font['svg'] ) . '#' . esc_attr( strtolower( str_replace( ' ', '_', $font_name ) ) ) . ") format('svg')";
                }
                $css .= join( ', ', $arr );
                $css .= ';';
                $css .= '}';
            }
            //display font
            $css .= 'body{';
            $css .= 'font-family: "'.$font_name.'", sans-serif;;';
            $css .= 'font-weight: normal;';
            $css .= 'font-style:  normal;';
            $css .= '}';
        }else {
            /* ----------------------------------------------------------
                                   Load Font Default or Google
            ---------------------------------------------------------- */
            $body_font = get_theme_mod('zoo_typo_base', '');
            if (isset($body_font['font-size'])) {
                $css .= "html{";
                $css .= "font-size:" . $body_font['font-size'];
                $css .= "}";
            }

            /*Typography generate Css*/
            if ($body_font == '' || $body_font['font'] == '') {
                $css .= "html{";
                $css .= "font-size: 18px;";
                $css .= "}";
                $css .= zoo_generate_css_font('body', array('font-family' => 'futura-pt', 'variant' => '400'));
            }
            if (get_theme_mod('zoo_typo_heading', '') == '' || get_theme_mod('zoo_typo_heading')['font'] == '') {
                $css .= zoo_generate_css_font('h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6', array('font-family' => 'futura-pt', 'variant' => '500'));
            }
            if (get_theme_mod('zoo_typo_woo', '') == '' || get_theme_mod('zoo_typo_woo')['font'] == '') {
                $css .= zoo_generate_css_font('.product-loop-title,  .product_title', array('font-family' => 'futura-pt', 'variant' => '400'));
            }
        }
        /*Preset Color*/
        if (zoo_theme_preset() != '') {
            //put all css class follow accent color to $accent_class
            $accent_class = '.accent-class';
            $css .= $accent_class . '{color:' . zoo_theme_preset() . '}';
        }
        if (class_exists('WooCommerce')) {
            $gutter = zoo_product_gutter();
            $css .= '.products .product{padding-left:' . $gutter . 'px;padding-right:' . $gutter . 'px}';
            $css .= 'ul.products, .woocommerce ul.products{margin-left:-' . $gutter . 'px !important;margin-right:-' . $gutter . 'px !important;width:calc(100% + ' . ($gutter * 2) . 'px)}';
        }
        if (get_theme_mod('zoo_custom_css') != '') {
            $css .= get_theme_mod('zoo_custom_css');
        }
        return $css;
    }
}