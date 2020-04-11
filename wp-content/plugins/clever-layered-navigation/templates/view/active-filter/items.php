<?php
/**
 */
foreach ($selected_filter_option as $key => $value) :
    if ($key == 'price') {
        $from = $selected_filter_option['price']['min_price'];
        $to = $selected_filter_option['price']['max_price'];
        ?>
        <li class="zoo-ln-group-activated-filter zoo-ln-activated-price">
            <span><?php esc_html_e('Price', 'clever-layered-navigation'); ?>:</span>
            <button type="submit" class="zoo-ln-remove-filter-item" name="zoo_ln_remove[price]"
                    value="1"><i class="cs-font clever-icon-close"></i><?php \Zoo\Frontend\Hook\render_price($from); echo (" - ");\Zoo\Frontend\Hook\render_price($to) ?></button>
        </li>
        <?php
    } elseif ($key === 'range') {
        foreach ($value as $active_filter_tax => $active_filter_range) :
            $range_taxonomy = get_taxonomy($active_filter_tax);
            if (!$range_taxonomy) {
                continue;
            }
            ?>
            <li class="zoo-ln-group-activated-filter zoo-ln-activated-range">
                <span><?php echo $range_taxonomy->labels->singular_name; ?>:</span>
                <button type="submit" class="zoo-ln-remove-filter-item" name="zoo_ln_remove[range_<?php echo $active_filter_tax ?>]" value="1"><i class="cs-font clever-icon-close"></i><?php echo $active_filter_range['min'] . ' - ' . $active_filter_range['max'] ?></button>
            </li>
            <?php
        endforeach;
    } else if ($key == 'attribute') {
        $attributes = $value;
        foreach ($attributes as $attribute_slug => $option_slugs) {
            ?>
            <li class="zoo-ln-group-activated-filter zoo-ln-activated-attribute">
                <span><?php echo(wc_attribute_label($attribute_slug)) ?>:</span>
                <?php
                foreach ($option_slugs as $slug) {
                    $str = '';
                    $term = get_term_by('slug', $slug, $attribute_slug);
                    if ($term instanceof WP_Term && !empty($term->name)) {
                        $str .= $term->name;
                    } else {
                        $str .= ucwords($slug);
                    }
                    ?>
                    <button type="submit" class="zoo-ln-remove-filter-item"
                            name="zoo_ln_remove[attribute][<?php echo($attribute_slug); ?>]"
                            value="<?php echo($slug); ?>"><i
                                class="cs-font clever-icon-close"></i><?php echo esc_html($str); ?></button>

                    <?php
                }
                ?>
            </li>
            <?php
        }
    } elseif ($key == 'tags') {
        $tags = $value;
        ?>
        <li class="zoo-ln-group-activated-filter zoo-ln-activated-attribute">
            <span><?php esc_html_e('Tags', 'clever-layered-navigation') ?>:</span>
            <?php
            foreach ($tags as $tag_slug) :
            $term = get_term_by('slug', $tag_slug, 'product_tag');
            ?>
            <button type="submit" class="zoo-ln-remove-filter-item" name="zoo_ln_remove[tags][<?php echo($tag_slug); ?>]" value="<?php echo $slug; ?>">
                <i class="cs-font clever-icon-close"></i>
                <?php echo esc_html($term->name); ?>
            </button>
            <?php endforeach; ?>
        </li>
        <?php
    } else if ($key == 'categories') {
        $category_slugs = $value ?>
        <li class="zoo-ln-group-activated-filter  zoo-ln-activated-categories">
                <span><?php
                    if (count($category_slugs) > 1) {
                        esc_html_e('Categories', 'clever-layered-navigation');
                    } else {
                        esc_html_e('Category', 'clever-layered-navigation');
                    }
                    ?>:</span>
            <?php
            foreach ($category_slugs as $category_slug) {
                $cat = get_term_by('slug', $category_slug, 'product_cat');
                ?>
                <button type="submit" class="zoo-ln-remove-filter-item" name="zoo_ln_remove[categories]"
                        value="<?php echo($category_slug); ?>"><i
                            class="cs-font clever-icon-close"></i><?php echo esc_html($cat->name) ?></button>
                <?php
            }
            ?>
        </li>
        <?php
    } else if ($key == 'on-sale' && $value == '1') {
        ?>
        <li class="zoo-ln-group-activated-filter zoo-ln-activated-on-sale">
            <span><?php esc_html_e('On Sale Product Only', 'clever-layered-navigation') ?></span>
            <button type="submit" class="zoo-ln-remove-filter-item" name="zoo_ln_remove[on-sale]" value="1">
                <i
                        class="cs-font clever-icon-close"></i></button>
        </li>
        <?php
    } else if ($key == 'in-stock' && $value == '1') {
        ?>
        <li class="zoo-ln-group-activated-filter zoo-ln-activated-in-stock">
            <span><?php esc_html_e('In Stock Product Only', 'clever-layered-navigation') ?></span>
            <button type="submit" class="zoo-ln-remove-filter-item" name="zoo_ln_remove[in-stock]"
                    value="1"><i
                        class="cs-font clever-icon-close"></i></button>
        </li>
        <?php
    } else if ($key == 'review-from') {
        ?>
        <li class="zoo-ln-group-activated-filter zoo-ln-activated-review">
            <span><?php esc_html_e('Review From', 'clever-layered-navigation') ?>: </span>
            <button type="submit" class="zoo-ln-remove-filter-item" name="zoo_ln_remove[review-from]"
                    value="1"><i
                        class="cs-font clever-icon-close"></i><?php echo($value); ?></button>
        </li>
        <?php
    } else if ($key == 'rating-from') {
        ?>
        <li class="zoo-ln-group-activated-filter zoo-ln-activated-rating">
            <span><?php esc_html_e('Rating From', 'clever-layered-navigation') ?>: </span>
            <button type="submit" class="zoo-ln-remove-filter-item" name="zoo_ln_remove[rating-from]"
                    value="1"><i
                        class="cs-font clever-icon-close"></i><?php echo($value); ?></button>
        </li>
        <?php
    }

endforeach;
?>
<li class="zoo-ln-group-activated-filter zoo-ln-activated-clear-all">
    <button type="submit" class="zoo-ln-clear-all zoo-ln-remove-filter-item" name="zoo_ln_remove_all" value="1">
        <?php esc_html_e('Clear All', 'clever-layered-navigation'); ?>
    </button>
</li>
