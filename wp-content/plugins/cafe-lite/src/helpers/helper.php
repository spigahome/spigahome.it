<?php
/**
 * Helpers function of CAFE
 **/

/**
 * Correct WordPress excerpt
 *
 * @param  object $post \WP_Post
 * @param  int $length Expected excerpt length.
 * @param  string $more Read more string.
 *
 * @see https://developer.wordpress.org/reference/functions/get_post/
 *
 * @return  string
 */
if (!function_exists('cafe_get_excerpt')) {
    function cafe_get_excerpt($length = 55)
    {
        $post = get_post(null);
        $text = $post->post_excerpt ?: $post->post_content;
        $text = do_shortcode($text);
        $text = strip_shortcodes($text);
        $text = str_replace(']]>', ']]&gt;', $text);
        $text = wp_trim_words($text, $length, false);

        return $text . '...';
    }
}

/**
 * Numeric pagination for CAFE widget
 *
 * @range  int number pagination display.
 * @current_query  current query of widget.
 * @pages  maximum pages want display.
 *
 * @see https://developer.wordpress.org/reference/functions/get_post/
 *
 * @return  string
 */
if( ! function_exists( 'cafe_pagination' ) ) {
    function cafe_pagination(  $range = 2, $current_query = '', $pages = '', $prev_icon='<i class="cs-font clever-icon-arrow-left"></i>', $next_icon='<i class="cs-font clever-icon-arrow-right"></i>' ) {
        $showitems = ($range * 2)+1;

        if( $current_query == '' ) {
            global $paged;
            if( empty( $paged ) ) $paged = 1;
        } else {
            $paged = $current_query->query_vars['paged'];
        }

        if( $pages == '' ) {
            if( $current_query == '' ) {
                global $wp_query;
                $pages = $wp_query->max_num_pages;
                if(!$pages) {
                    $pages = 1;
                }
            } else {
                $pages = $current_query->max_num_pages;
            }
        }

        if(1 != $pages) { ?>
            <div class="cafe-pagination clearfix">
                <?php if ( $paged > 1 ) { ?>
                    <a class="cafe-pagination-prev cafe_pagination-item" href="<?php echo esc_url(get_pagenum_link($paged - 1)) ?>"><?php echo $prev_icon?></a>
                <?php }

                for ($i=1; $i <= $pages; $i++) {
                    if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
                        if ($paged == $i) { ?>
                            <span class="current cafe_pagination-item"><?php echo esc_html($i) ?></span>
                        <?php } else { ?>
                            <a href="<?php echo esc_url(get_pagenum_link($i)) ?>" class="inactive cafe_pagination-item"><?php echo esc_html($i) ?></a>
                            <?php
                        }
                    }
                }
                if ($paged < $pages) { ?>
                    <a class="cafe-pagination-next cafe_pagination-item" href="<?php echo esc_url(get_pagenum_link($paged + 1)) ?>"><?php echo $next_icon?></a>
                <?php } ?>
            </div>
            <?php
        }
    }
}

/**
 * Instagram
 *
 * @return  string
 */

if ( ! function_exists( 'cafe_scrape_instagram' ) ) {
    function cafe_scrape_instagram( $username ) {

        $username = trim( strtolower( $username ) );

        switch ( substr( $username, 0, 1 ) ) {
            case '#':
                $url              = 'https://instagram.com/explore/tags/' . str_replace( '#', '', $username );
                $transient_prefix = 'h';
                break;

            default:
                $url              = 'https://instagram.com/' . str_replace( '@', '', $username );
                $transient_prefix = 'u';
                break;
        }

        if ( false === ( $instagram = get_transient( 'insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ) ) ) ) {

            $remote = wp_remote_get( $url );

            if ( is_wp_error( $remote ) ) {
                return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'wp-instagram-widget' ) );
            }

            if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
                return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'wp-instagram-widget' ) );
            }

            $shards      = explode( 'window._sharedData = ', $remote['body'] );
            $insta_json  = explode( ';</script>', $shards[1] );
            $insta_array = json_decode( $insta_json[0], true );

            if ( ! $insta_array ) {
                return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'wp-instagram-widget' ) );
            }

            if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
                $images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
            } elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
                $images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
            } else {
                return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'wp-instagram-widget' ) );
            }

            if ( ! is_array( $images ) ) {
                return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'wp-instagram-widget' ) );
            }

            $instagram = array();

            foreach ( $images as $image ) {
                if ( true === $image['node']['is_video'] ) {
                    $type = 'video';
                } else {
                    $type = 'image';
                }

                $caption = esc_html__('Instagram Image', 'wp-instagram-widget' );
                if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
                    $caption = wp_kses( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'], array() );
                }

                $instagram[] = array(
                    'description' => $caption,
                    'link'        => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
                    'time'        => $image['node']['taken_at_timestamp'],
                    'comments'    => $image['node']['edge_media_to_comment']['count'],
                    'likes'       => $image['node']['edge_liked_by']['count'],
                    'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
                    'small'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
                    'large'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
                    'original'    => preg_replace( '/^https?\:/i', '', $image['node']['display_url'] ),
                    'type'        => $type,
                );
            } // End foreach().

            // do not set an empty transient - should help catch private or empty accounts.
            if ( ! empty( $instagram ) ) {
                $instagram = base64_encode( serialize( $instagram ) );
                set_transient( 'insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
            }
        }

        if ( ! empty( $instagram ) ) {

            return unserialize( base64_decode( $instagram ) );

        } else {

            return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'wp-instagram-widget' ) );

        }
    }
}
if ( ! function_exists( 'cafe_images_only' ) ) {
    function cafe_images_only( $media_item ) {

        if ( $media_item['type'] == 'image' )
            return true;

        return false;
    }
}
if ( ! function_exists( 'cafe_abbreviate_total_count' ) ) {
    function cafe_abbreviate_total_count( $value, $floor = 0 ) {
        if ( $value >= $floor ) {
            $abbreviations = array(12 => 'T', 9 => 'B', 6 => 'M', 3 => 'K', 0 => '');

            foreach ( $abbreviations as $exponent => $abbreviation ) {
                if ( $value >= pow(10, $exponent) ) {
                    return round(floatval($value / pow(10, $exponent)),1).$abbreviation;
                }
            }
        } else {
            return $value;
        }
    }
}

if ( ! function_exists( 'cafe_time_elapsed_string' ) ) {
    function cafe_time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}

/**
 * cafe_oembed_dataparse
 * normally video iframe for video frame.
 * disable if Zoo theme is activated or override by user.
 * @uses Use hook for add to oembed_dataparse
 * @return parameter of video iframe.
 */
if (!function_exists('zoo_oembed_dataparse')||!function_exists('cafe_oembed_dataparse')) {
	function cafe_oembed_dataparse($return, $data, $url)
	{
		if (false === strpos($return, 'youtube.com'))
			return $return;
		$id = explode('watch?v=', $url);
		$add_id = str_replace('allowfullscreen>', 'allowfullscreen id="yt-' . $id[1] . '">', $return);
		$add_id = str_replace('?feature=oembed', '?enablejsapi=1', $add_id);
		return $add_id;
	}
}
add_filter('oembed_dataparse', 'cafe_oembed_dataparse', 10, 3);