<?php
/**
 * Template for Fullpage Scrolling widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

if (empty($settings['sections'])) {
    return;
}

$button_class='cafe-button section-item-btn ';

if (! empty($settings['button_size'])) {
    $button_class .= 'elementor-size-' . $settings['button_size'].' ';
}

$sections = [];
$section_count = 0;

foreach ($settings['sections'] as $section) {
    $section_html = '';
    $btn_attributes = '';
    $section_attributes = '';
    $section_element = 'div';
    $btn_element = 'span';
    $section_url = $section['link']['url'];
    if (!empty($section_url)) {
        $this->add_render_attribute('slide_link' . $section_count, 'href', $section_url);
        if ($section['link']['is_external']) {
            $this->add_render_attribute('slide_link' . $section_count, 'target', '_blank');
        }
        if ('button' === $section['link_click']) {
            $btn_element = 'a';
            $btn_attributes = $this->get_render_attribute_string('slide_link' . $section_count);
        } else {
            $section_element = 'a';
            $section_attributes = $this->get_render_attribute_string('slide_link' . $section_count);
        }
    }

    if ('yes' === $section['background_overlay']) {
        $section_html .= '<div class="section-item-bg-overlay"></div>';
    }

    $section_html .= '<div class="section-item-content">';

    if ($section['heading']) {
        $section_html .= '<div class="section-item-heading">' . $section['heading'] . '</div>';
    }

    if ($section['description']) {
        $section_html .= '<div class="section-item-desc">' . $section['description'] . '</div>';
    }

    if ($section['button_text']) {
        $section_html .= '<' . $btn_element . ' ' . $btn_attributes . ' class="' .$button_class . $section['button_style'].'">' . $section['button_text'] . '</' . $btn_element . '>';
    }

    $section_html .= '</div>';
    $section_html = '<div class="section-item-bg' . $ken_class . '"></div><div' . ' ' . $section_attributes . ' class="section-item-inner">' . $section_html . '</div>';
    $sections[] = '<section class="elementor-repeater-item-' . $section['_id'] . ' section-item">' . $section_html . '</section>';
    $section_count++;
}
?>
<div class="cafe-fullpage-scrolling-wrapper">
    <div class="cafe-fullpage-scrolling-sections">
        <?php echo implode('', $sections); ?>
    </div>
    <?php
    if ($settings['show_navbar']) {
        ?>
        <div class="cafe-fullpage-navbar cafe-fullpage-navbar-<?php echo $settings['navbar_position'] ?>">
            <ul>
            <?php
            foreach ($settings['sections'] as $section) {
                echo '<li><a href="#elementor-repeater-item-' . $section['_id'] . '"><span class="screen-reader-text">' . $section['heading'] . '</span></a></li>';
            }
            ?>
            </ul>
        </div>
        <?php
    }
    ?>
</div>
