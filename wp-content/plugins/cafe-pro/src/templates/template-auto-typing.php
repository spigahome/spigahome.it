<?php
/**
 * View template for Auto typing widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

$cafe_content = $settings['text'];

if ($cafe_content != '') {
    $css_class = 'cafe-auto-typing';
    $css_class .= ' ' . $settings['css_class'];
    $show_cursor = $settings['show_cursor'] == 'yes' ? 1 : 0;
    $loop = $settings['loop'] == 'yes' ? 1 : 0;

    $list_text = array();
    foreach ($cafe_content as $text) {
        if ($text['text_item'] != '')
            $list_text[] = $text['text_item'];
    }
    $cafe_json_config = '{"text":' . json_encode($list_text) . ',"speed":' . $settings['typeSpeed'] . ', "delay":' . $settings['delay_time'] . ',"cursor":' . $show_cursor . ',"loop":' . $loop . '}';
    ?>
    <div class="<?php echo esc_attr($css_class) ?>" data-cafe-config='<?php echo esc_attr($cafe_json_config); ?>'>
        <?php
        if($settings['fixed_text']!='')
        printf('<span %s>%s</span>', $this->get_render_attribute_string('fixed_text'), $settings['fixed_text']);
        ?>
        <span class="cafe-content-auto-typing"></span>
    </div>
<?php } ?>

