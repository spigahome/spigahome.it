<?php
/**
 * View template for Clever Image 360 View widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

if (isset($settings['images'])) {
    $cafe_content = $settings['images'];
    $css_class = 'cafe-image-360-view';
    $css_class .= ' ' . $settings['css_class'];
    $width = $settings['width'] != '' ? $settings['width'] : '100%';
    $height = $settings['height'] != '' ? $settings['height'] : '100%';

    $imgs = array();
    foreach ($cafe_content as $img) {
        if ($img['url'] != '')
            $imgs[] = $img['url'];
    }
    $imgs = implode(",", $imgs);
    $cafe_json_config = '{"width":"' . $width . '","height":"' . $height . '","source":"' . $imgs . '"}';

    ?>
    <div class="<?php echo esc_attr($css_class) ?>" data-cafe-config='<?php echo esc_attr($cafe_json_config) ?>'>
        <div class="cafe-wrap-img-360-view">
            <div class="cafe-wrap-content-view">
            </div>
            <ul class="cafe-wrap-control-view">
                <li class="cafe-control-view cafe-prev-item"><i class="cs-font clever-icon-arrow-left-5"></i></li>
                <li class="cafe-control-view cafe-center"><i class="cs-font clever-icon-360-2"></i></li>
                <li class="cafe-control-view cafe-next-item"><i class="cs-font clever-icon-arrow-right-5"></i></li>
            </ul>
        </div>
        <?php
        printf('<div class="cafe-description" %s>%s</div>', $this->get_render_attribute_string('des'), $settings['des']);
        ?>
    </div>
    <?php
    if (is_admin()) {
        ?>
        <script>
            if (typeof window.cafe != "undefined") {
                window.cafe.init();
            }</script>
    <?php }
} ?>

