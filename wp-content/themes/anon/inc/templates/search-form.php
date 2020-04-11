<?php
/**
 * Search form
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 
 */
?>
<div class="header-search-block">
    <form method="get" class="clearfix" action="<?php echo esc_url(home_url('/')); ?>">
        <input type="text" class="ipt text-field body-font" name="s"
               placeholder="<?php echo esc_attr__('Type & hit enter...', 'anon'); ?>" autocomplete="off"/>
        <i class="cs-font clever-icon-search-5"></i>
    </form>
</div>