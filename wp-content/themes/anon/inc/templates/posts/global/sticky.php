<?php
/**
 * Sticky label template
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 
 */

if (is_sticky()) {
	?>
    <span class="sticky-post-label"><i class="cs-font clever-icon-light"></i> <?php echo esc_html__('Featured', 'anon') ?></span>
	<?php
}