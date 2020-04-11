<?php
/**
 * Pagination functionality
 */

/**
 * Default pagination
 */
if (!function_exists('zoo_ajax_pagination'))
{
    function zoo_ajax_pagination(WP_Query $query = null, array $args = array())
    {
        $query = $query ? : $GLOBALS['wp_query'];

        if (1 >= $query->max_num_pages) {
            return;
        }

        $paged = !empty($query->query_vars['paged']) ? intval($query->query_vars['paged']) : 1;

        $args = array(
            'type'                 => !empty($args['type']) ? $args['type'] : 'infinity',
            'delay'                => !empty($args['delay']) ? $args['delay'] : 500,
            'container_selector'   => !empty($args['container_selector']) ? $args['container_selector'] : '.zoo-container',
            'item_selector'        => !empty($args['item_selector']) ? $args['item_selector'] : '.post',
            'layout_mode'          => !empty($args['layout_mode']) ? $args['layout_mode'] : 'vertical',
            'images_loaded'        => !empty($args['images_loaded']) ? $args['images_loaded'] : false,
            'prev_text'            => !empty($args['prev_text']) ? $args['prev_text'] : esc_html__('Prev Posts', 'anon'),
            'next_text'            => !empty($args['next_text']) ? $args['next_text'] : esc_html__('Next Posts', 'anon'),
            'horizontal_alignment' => '',
            'gutter'               => '',
            'column_width'         => '',
            'more_text'            => !empty($args['more_text']) ? $args['more_text'] : apply_filters('zoo_ajax_pagination_more_text', esc_html__('Load More', 'anon')),
            'no_more_text'         => !empty($args['no_more_text']) ? $args['no_more_text'] : apply_filters('zoo_ajax_pagination_no_more_text', esc_html__('No More Posts', 'anon')),
        );

        $uid = crc32(serialize($args));

        $prev_link = get_pagenum_link($paged - 1);
        $next_link = get_pagenum_link($paged + 1);

        echo '<div id="spinner-'.esc_attr($uid).'" class="align-center hidden">' . apply_filters('zoo_ajax_pagination_spinner', '<img aria-hidden="true" role="presentation" src="'.ZOO_THEME_URI.'core/assets/icons/spinner.svg'.'" width="48" height="48">') . '</div>';

        ?><div id="zoo-pagination-<?php echo esc_attr($uid) ?>" class="zoo-pagination"><?php
            if ($paged > 1 && $paged <= $query->max_num_pages) {
                echo '<a id="prev-page-link-'.esc_attr($uid).'" class="prev-page-link" href="'.esc_url($prev_link).'">' . $args['prev_text'] . '</a>';
            }
            if ($paged < $query->max_num_pages) {
                echo '<a id="next-page-link-'.esc_attr($uid).'" class="next-page-link" href="'.esc_url($next_link).'">' . $args['next_text'] . '</a>';
            }
        ?></div><?php

        $layout_mode_options = '';

        if ('masonry' === $args['layout_mode']) {
            $layout_mode_options .= !empty($args['gutter']) ? 'gutter:'.$args['gutter'] : 'gutter:20';
            $layout_mode_options .= !empty($args['column_width']) ? ',columnWidth:'.$args['column_width'] : ',columnWidth:'.$args['item_selector'];
        } elseif ('fitRows' === $args['layout_mode']) {
            $layout_mode_options .= !empty($args['gutter']) ? 'gutter:'.$args['gutter'] : 'gutter:20';
        } elseif ('vertical' === $args['layout_mode']) {
            $layout_mode_options .= !empty($args['horizontal_alignment']) ? 'horizontalAlignment:'.$args['horizontal_alignment'] : 'horizontalAlignment:0';
        }

        $inline_scripts = 'jQuery(document).ready( function($) {
    		var spinner = $("#spinner-'.esc_attr($uid).'");
    		var container = $("'.$args['container_selector'].'");
    		var ias = $.ias({
                container:  "'.$args['container_selector'].'",
                item:       "'.$args['item_selector'].'",
                pagination: "#zoo-pagination-'.esc_attr($uid).'",
                next:       "#next-page-link-'.esc_attr($uid).'",
                delay:      '.$args['delay'].'
            });

            var iso_init = container.data("isotope");
            if ( !iso_init ) {
              var iso = container.isotope({
          			percentPosition: true,
          			itemSelector: "'.$args['item_selector'].'",
          			layoutMode: "'.$args['layout_mode'].'",
          			'.$args['layout_mode'].': {'.$layout_mode_options.'}
          		});';

              if ($args['images_loaded']) {
                  if (!wp_script_is('imagesloaded', 'enqueued')) {
                      $inline_scripts .= '
                      iso.imagesLoaded().progress(function(){
                          iso.isotope("layout");
                      });
                      ';
                      wp_enqueue_script('imagesloaded');
                  }
              }
            $inline_scripts .= '}';

            $inline_scripts .= '
            ias.on("load", function(e) {
    			spinner.show();
    		});

	    ias.on("render", function(items) {
	      	$(items).css({
	            opacity: 0
	        });
	    });

	    ias.on("rendered", function(items) {
			    spinner.hide();
          container.isotope("appended", $(items));
	    });

  		ias.on("noneLeft", function(){
  			spinner.empty().append("<span>'. $args['no_more_text'] .'</span>").show();
  		});';

        if ('ajaxload' === $args['type']) {
            $inline_scripts .= '
            ias.extension(new IASTriggerExtension({
                text: "'. $args['more_text'] .'",
                offset: 1
            }));';
        }

        $inline_scripts .= '});';

        if (!wp_script_is('jquery-ias', 'enqueued')) {
            wp_add_inline_script('jquery-ias', $inline_scripts);
            wp_enqueue_script('jquery-ias');
        }
    }
}
