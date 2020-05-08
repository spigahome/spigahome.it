<?php
/**
 * @template: Swatches Filter attributes template
 * @package clever-layered-navigation/templates/view
 * @author cleversoft.co <hello.cleversoft@gmail.com>
 * @since: 1.0.1
 */
if(class_exists( 'Zoo_Clever_Swatch_Helper' )) {
    $attribute_taxonomy_name = wc_attribute_taxonomy_name($content_data['attribute-ids']);
    $terms = get_terms([
        'taxonomy' => $attribute_taxonomy_name
    ]);

    if (is_wp_error($terms)) {
        return;
    }

    $terms = cln_maybe_remove_unsatisfied_terms($terms, $attribute_taxonomy_name);

    $zoo_cw_helper = new Zoo_Clever_Swatch_Helper();
    $cw_display_type = $zoo_cw_helper->get_display_type_by_attribute_taxonomy_name($attribute_taxonomy_name);
    $display_style = 'inline';
    if (isset($content_data['display-style'])) {
        if (($cw_display_type == 'color' || $cw_display_type == 'image') && $content_data['display-style'] == 'inline') {
            wp_enqueue_script('tippy');
        }
        $display_style = $content_data['display-style'];
    }

    if (count($terms) > 0) {
        if (isset($selected_filter_option['attribute'][$attribute_taxonomy_name])) {
            $checked_slug = $selected_filter_option['attribute'][$attribute_taxonomy_name];
        } else $checked_slug = array();
        $filter_id = mt_rand();
        $class = 'cw-type-' . $cw_display_type . ' zoo-cw-filter zoo-filter-block zoo-filter-by-' . $attribute_taxonomy_name . ' ' . $display_style;
        ?>
        <div id="cln-filter-item-<?php echo esc_attr($filter_id) ?>" class="<?php echo esc_attr($class); ?>">
            <h4 class="zoo-title-filter-block"><?php echo esc_html($content_data['title']); ?>
                <?php
                if (!isset($content_data['vertical-always-visible'])) {
                    ?><span class="zoo-ln-toggle-block-view"><i class="cs-font clever-icon-caret-up"></i></span><?php
                }
                ?></h4>
            <?php if ($display_style == 'inline') { ?>
                <style>
                    #cln-filter-item-<?php echo esc_attr($filter_id)?> .zoo-filter-item {
                        width: <?php echo !empty($content_data['swatch-width']) ? $content_data['swatch-width'] : '30'; ?>px;
                        min-width: <?php echo !empty($content_data['swatch-width']) ? $content_data['swatch-width'] : '30'; ?>px;
                        height: <?php echo !empty($content_data['swatch-height']) ? $content_data['swatch-height'] : '30' ?>px;
                        line-height: <?php echo !empty($content_data['swatch-height']) ? ($content_data['swatch-height']-4).'px' : '1' ?>;
                    }
                </style>
            <?php } ?>
            <ul class="zoo-list-cw-attribute <?php if ($cw_display_type == 'select' || $display_style == 'list') {
                echo esc_attr('zoo-list-filter-item');
            } ?>">
                <?php
                foreach ($terms as $term) {

                    $item_class = '';
                    $term_meta = get_term_meta($term->term_id);

                    if (in_array($term->slug, $checked_slug)) {
                        $checked = ' checked';
                        $item_class = ' selected';
                    } else  $checked = '';
                    $tooltip = '';
                    if ($cw_display_type == 'color' || $cw_display_type == 'image') {
                        $tooltip = 'title="' . $term->name . '"';
                    }
                    $html = '';
                    $html .= '<li class="zoo-filter-item' . $item_class . '" ' . $tooltip . '>';
                    $html .= '<label>';
                    $html .= '<input type="checkbox" value="' . $term->slug . '" name="attribute[' . $attribute_taxonomy_name . '][]" ' . $checked . '/>';

                    if ($cw_display_type == 'color') {
                        //Color
                        $color = !empty($term_meta['slctd_clr'][0]) ? $term_meta['slctd_clr'][0] : '';
                        $html .= '<span class="cw-type swatch-type-color" style="background:' . $color . '">';
                        if ($display_style != 'list') {
                            $html .= $term->name;
                        }
                        $html .= '</span>';
                        if ($display_style == 'list') {
                            $html .= $term->name;
                        }
                    } elseif ($cw_display_type == 'image') {
                        //Image
                        $image = !empty($term_meta['slctd_img'][0]) ? $term_meta['slctd_img'][0] : '';
                        $html .= '<img src="' . $image . '" alt="' . $term->name . '" class="cw-type swatch-type-image"/>';
                        if ($display_style == 'list') {
                            $html .= $term->name;
                        }
                    } else {
                        $html .= $term->name;
                    }
                    if (isset($content_data['show-product-count']) && $content_data['show-product-count'] == 1) {
                        $html .= '<span class="count">' . $term->count . '</span>';
                    }
                    $html .= '</label>';
                    $html .= '</li>';

                    echo($html);
                }
                ?>
            </ul>
        </div>
        <?php
    }

}
