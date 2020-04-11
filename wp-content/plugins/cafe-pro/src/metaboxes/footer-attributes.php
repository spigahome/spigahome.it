<?php
/**
 * Header Attributes Metabox
 */
/**
 * Add to admin screen
 *
 * @see  https://developer.wordpress.org/reference/hooks/add_meta_boxes_post_type/
 */
function clever_elementor_footer_attributes_metabox()
{
	global $post;

	if ('page' !== $post->post_type) return;

	$current = get_post_meta($post->ID, 'clever_elementor_footer_template', true) ? : 'inherit';
	$options = [];
	$global  = get_theme_mod('clever_global_elementor_footer_template');
    if ($global) {
        $options[$global] = __('Default Global', 'cafe-pro');
    }

    $headers = get_posts([
        'post_type' => 'elementor_library',
        'post_status' => 'publish',
        'meta_key' => '_elementor_template_type',
        'meta_value' => 'site_footer',
        'ignore_sticky_posts' => true,
        'nopaging' => true,
        'no_found_rows' => true,
        'posts_per_page' => -1,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false
    ]);

    if ($headers) {
        foreach ($headers as $header) {
            $options[$header->post_name] = $header->post_title;
        }
    }
	?>

    <p class="post-attributes-label-wrapper">
        <label class="post-attributes-label" for="clever_elementor_footer_template">
			<?php esc_html_e( 'Footer Template', 'cafe-pro' ); ?>
        </label>
    </p>
    <select name="clever_elementor_footer_template" id="clever_elementor_footer_template" class="widefat">
        <option value="inherit"><?php esc_html_e('Inherit', 'cafe-pro') ?></option>
		<?php foreach ($options as $id => $name) : ?>
			<?php $selected = ($id === $current) ? ' selected' : ''; ?>
            <option value="<?php echo esc_attr($id) ?>"<?php echo $selected ?>>
                <?php echo $name ?>
            </option>
		<?php endforeach; ?>
    </select>
	<?php
}

add_action('add_meta_boxes_page', function($post)
{
	add_meta_box('page-elementor-footer-attributes', esc_html__('Footer Attributes', 'cafe-pro' ), 'clever_elementor_footer_attributes_metabox', 'page');
});

/**
 * Handle save action.
 *
 * @see  https://developer.wordpress.org/reference/hooks/save_post_post-post_type/
 */
add_action('save_post_page', function($id, $post, $update)
{
	if (defined('XMLRPC_REQUEST') || defined('WP_IMPORTING') || (defined('DOING_AJAX') && DOING_AJAX)) {
		return;
	}
	if (!current_user_can('edit_post', $id) || wp_is_post_revision($id)) {
		return;
	}
	$tpl = !empty($_POST['clever_elementor_footer_template']) ? esc_attr($_POST['clever_elementor_footer_template']) : false;
	if ($tpl) {
		update_post_meta($id, 'clever_elementor_footer_template', $tpl);
	}
}, 10, 3);
