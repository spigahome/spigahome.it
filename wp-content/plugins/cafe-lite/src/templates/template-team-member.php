<?php
/**
 * View template for Team member widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

$css_class = 'cafe-team-member ' . $settings['style'];
if ($settings['member_social'] != '') {
    $css_class .= ' has-social';
}
if ($settings['member_bio'] != '') {
    $css_class .= ' has-member-bio';
}

$social_html = '';
foreach ($settings['member_social'] as $social) {
    if($social['url']["url"]!='') {
        $social_html .= sprintf('<li class="cafe-member-social-item"><a href="%s" title="%s"><i class="%s"></i></a></li>', $social['url']["url"], $social['social_title'], $social['social']);
    }
}
if($social_html!='') {
    $social_html = '<ul class="cafe-member-social">' . $social_html . '</ul>';
}else{
    $css_class.=' without-social';
}
?>
<div class="<?php echo esc_attr($css_class) ?>">
    <?php
    if ($settings['member_ava'] != '') {
        ?>
        <div class="cafe-member-ava">
            <?php
            printf('<div class="mask-color"></div><img src="%s" alt="%s"/>', $settings['member_ava']['url'], $settings['member_name']);
            if ($settings['member_social'] != '' && $settings['style'] == 'style-2') {
                ?>
                <div class="cafe-wrap-team-member">
                    <?php
                    echo $social_html;
                    ?>
                </div>
                <?php
            }
            if ($settings['style'] == 'style-4') {
                echo $social_html;
            }
            ?>
        </div>
        <?php
    }
    if ($settings['style'] == 'style-1') :
        ?>
        <div class="cafe-wrap-team-member">
            <?php
            printf('<h3 %s>%s</h3>', $this->get_render_attribute_string('member_name'), $settings['member_name']);
            printf('<div class="cafe-member-des" %s>%s</div>', $this->get_render_attribute_string('member_des'), $settings['member_des']);
            printf('<div class="cafe-member-bio" %s>%s</div>', $this->get_render_attribute_string('member_bio'), $settings['member_bio']);
            if ($settings['member_social'] != '') {
                echo $social_html;
            }
            ?>
        </div>
    <?php
    else:
        if ($settings['member_social'] != '' && $settings['style'] == 'style-3') {
            $social_html_p = $social_html;
        }
        printf('<h3 class="cafe-member-name" %s>%s</h3>', $this->get_render_attribute_string('member_name'), $settings['member_name']);
        printf('<div class="cafe-member-des" %s>%s</div>', $this->get_render_attribute_string('member_des'), $settings['member_des']);
        if ($settings['member_social'] != '' && $settings['style'] == 'style-3') {
            ?>
            <div class="wrap-member-bio">
            <?php
        }
        printf('<div class="cafe-member-bio" %s>%s</div>', $this->get_render_attribute_string('member_bio'), $settings['member_bio']);
        if ($settings['member_social'] != '' && $settings['style'] == 'style-3') {
            echo $social_html;
            ?>
            </div>
            <?php
        }
    endif;
    ?>
</div>

