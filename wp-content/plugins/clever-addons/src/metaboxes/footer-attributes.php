<?php
/**
 * Footer Template Metabox
 */

/**
 * Add to admin screen
 *
 * @see  https://developer.wordpress.org/reference/hooks/add_meta_boxes_post_type/
 */
function zoo_render_footer_attributes_metabox()
{
	global $post;

	if ('page' !== $post->post_type) return;

	$current_template          = get_post_meta($post->ID, 'zoo_meta_page_footer_template', true) ? : 'inherit';
	$header_template_options   = ['inherit' => esc_html__('Inherit', 'clever-addons')];
	$saved_footer_templates    = get_option(get_option('template', 'zootemplate').'_footer_saved_templates', []);
	$prebuilt_footer_templates = get_option(get_option('template', 'zootemplate').'_footer_prebuilt_templates', []);


	if ($prebuilt_footer_templates && is_array($prebuilt_footer_templates)) {
		foreach ($prebuilt_footer_templates as $id => $data) {
			$header_template_options[$id] = $data['name'];
		}
	}
	?>

    <p class="post-attributes-label-wrapper">
        <label class="post-attributes-label" for="zoo_meta_page_footer_template">
			<?php esc_html_e( 'Footer Template', 'clever-addons' ); ?>
        </label>
    </p>
    <select name="zoo_meta_page_footer_template" id="zoo_meta_page_footer_template" class="widefat">
        <option value="inherit"><?php esc_html_e('Inherit', 'clever-addons') ?></option>
		<?php if ($saved_footer_templates && is_array($saved_footer_templates)) : ?>
            <optgroup label="<?php esc_attr_e('Saved Templates', 'clever-addons') ?>">
				<?php foreach ($saved_footer_templates as $id => $data) : ?>
					<?php $selected = ($id === $current_template) ? ' selected' : ''; ?>
                    <option value="<?php echo esc_attr($id) ?>"<?php echo esc_attr($selected) ?>>
						<?php echo esc_attr($data['name']) ?>
                    </option>
				<?php endforeach; ?>
            </optgroup>
		<?php endif; ?>
		<?php if ($prebuilt_footer_templates && is_array($prebuilt_footer_templates)) : ?>
            <optgroup label="<?php esc_attr_e('Prebuilt Templates', 'clever-addons') ?>">
				<?php foreach ($prebuilt_footer_templates as $id => $data) : ?>
					<?php $selected = ($id === $current_template) ? ' selected' : ''; ?>
                    <option value="<?php echo esc_attr($id) ?>"<?php echo esc_attr($selected) ?>>
						<?php echo esc_attr($data['name']) ?>
                    </option>
				<?php endforeach; ?>
            </optgroup>
		<?php endif; ?>
    </select>
	<?php
}

add_action('add_meta_boxes_page', function($post)
{
	add_meta_box('page-footer-attributes', esc_html__('Footer Attributes', 'clever-addons' ), 'zoo_render_footer_attributes_metabox', 'page');
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

	$tpl = !empty($_POST['zoo_meta_page_footer_template']) ? esc_attr($_POST['zoo_meta_page_footer_template']) : false;

	if ($tpl) {
		update_post_meta($id, 'zoo_meta_page_footer_template', $tpl);
	}
}, 10, 3);
