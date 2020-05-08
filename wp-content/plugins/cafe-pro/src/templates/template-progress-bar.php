<?php
/**
 * View template for Clever Progress Bar widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */


$style = $settings['style'];
$wrap_class = $style . ' ' . $settings['percentage_location'];
if ($style == 'default') {
    printf('<%s %s>%s</%s>', $settings['title_tag'], $this->get_render_attribute_string('title'), $settings['title'], $settings['title_tag']);
}
$percent_count_html = '<span class="percent-count">
                <span class="value">0</span>
                <span class="symbol">%</span></span>';
?>
<div class="cafe-wrap-progress-bar <?php echo esc_attr($wrap_class) ?>"
     data-cafe-config='{"percent":<?php echo esc_attr($settings['percent']) ?>,"duration":<?php echo esc_attr($settings['duration']) ?>}'>
    <div class="base-bg">
        <div class="cafe-progress-bar">
            <?php
            if ($style == 'default') {
                if ($settings['percentage_location'] != 'stuck-left' && $settings['percentage_location'] != 'stuck-right') { ?>
                    <div class="cafe-progress-dot">
                        <?php echo $percent_count_html; ?>
                    </div>
                <?php }
            } ?>
        </div>

    </div>
    <?php
    if ($style == 'grouped') {
        ?>
        <div class="cafe-grouped-content">
            <?php
            printf('<%s %s>%s</%s>', $settings['title_tag'], $this->get_render_attribute_string('title'), $settings['title'], $settings['title_tag']);
            echo $percent_count_html;
            ?>
        </div>
        <?php
    } else {
        if ($settings['percentage_location'] == 'stuck-left' || $settings['percentage_location'] == 'stuck-right') { ?>
            <?php echo $percent_count_html; ?>
        <?php }
    }
    ?>

</div>
