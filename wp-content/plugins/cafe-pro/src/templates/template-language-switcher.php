<?php
/**
 * View template for Clever Language Switcher
 *
 * @package CAFE\Templates
 * @copyright 2020 CleverSoft. All rights reserved.
 */

$languages = [];
$text = $settings['label'];
$icon = $settings['icon'];
$icon_html='';
if ($settings['show_icon'] == 'true') {
    $icon_html = '<i class="' . esc_attr($icon) . '"></i> ';
}

if (function_exists('pll_the_languages')) { // Polylang active.
    $languages = pll_the_languages(['raw' => 1]);
    foreach ($languages as $lang) {
        if ($lang['current_lang']) {
            $text = $lang['name'];
        }
    }
} elseif (function_exists('icl_get_languages')) { // WPML active.
    $languages = icl_get_languages();
    foreach ($languages as $lang) {
        if ($lang['active']) {
            $text = $lang['native_name'];
        }
    }
}

$allow_html=array('i'=>array('class'=>array()),'img'=>array('src'=>array(),'alt'=>array()));
?>
    <div id="cafe-language-switcher" class="cafe-language-switcher">
        <span class="cafe-language-options">
            <?php
            echo wp_kses($icon_html, $allow_html) . esc_html($text);
            ?>
        </span>
        <ul class="cafe-langs">
            <?php
            if ($languages && function_exists('pll_the_languages')) {
                foreach ($languages as $lang) { ?>
                    <li>
                        <a href="<?php esc_url($lang['url']) ?>" hreflang="<?php esc_url($lang['slug']) ?>"
                           title="<?php esc_attr($lang['name']) ?>">
                            <?php if ($settings['show_flag'] == 'true') { ?>
                                <img src="<?php esc_url($lang['flag']) ?>" alt="<?php esc_attr($lang['name']) ?>"/>

                            <?php }
                            if ($settings['show_language']) {
                                echo esc_html($lang['name']);
                            } ?>
                        </a>
                    </li>
                    <?php
                }
            } elseif ($languages && function_exists('icl_get_languages')) {
                foreach ($languages as $lang) {
                    ?>
                    <li>
                        <a href="<?php esc_url($lang['url']) ?>" hreflang="<?php esc_url($lang['language_code']) ?>"
                           title="<?php esc_attr($lang['native_name']) ?>">
                            <?php if ($settings['show_flag'] == 'true') { ?>
                                <img src="<?php esc_url($lang['country_flag_url']) ?>"
                                     alt="<?php esc_attr($lang['native_name']) ?>"/>

                            <?php }
                            if ($settings['show_language']) {
                                echo esc_html($lang['native_name']);
                            } ?>
                        </a>
                    </li>
                    <?php
                }
            }
            if (!function_exists('pll_the_languages') && !function_exists('icl_get_languages')) {
                ?>
                <li>
                    <?php esc_html_e('Please activate Polylang or WPML plugin to show available languages.', 'cafe-pro'); ?>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
<?php

