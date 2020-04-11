<?php
/**
 * The pagination for content post, page
 * Used for both single and page. Page Break / <!--nextpage-->
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 
 */

wp_link_pages( array(
    'before'      => '<div class="wrap-pagination inpost-pagination"><div class="pagination clearfix"> ',
    'after'       => '</div></div>',
    'link_before' => '<span>',
    'link_after'  => '</span>',
    'pagelink'    => '%',
    'separator'   => '',
) );
