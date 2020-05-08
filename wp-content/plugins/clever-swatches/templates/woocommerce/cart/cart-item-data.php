<?php
/**
 * CleverSwatches image
 * @description: use for display image swatch
 **/
if (!defined('ABSPATH')) {
    exit;
}
?>
<dl class="variation">
    <?php foreach ($item_data as $data) : ?>
        <dt class="variation-<?php echo sanitize_html_class($data['key']); ?>"><?php echo wp_kses_post($data['key']); ?>
            :
        </dt>
        <dd class="variation-<?php echo sanitize_html_class($data['key']); ?>">
            <div class="zoo-cw-attribute-option">
                <div class="zoo-cw-attr-item  <?php if (isset($data['display_class'])) echo($data['display_class']); ?>" <?php if (isset($data['custom_style'])) echo($data['custom_style']); ?>>
                    <?php
                    if (isset($data['custom_style'])):
                        if ($data['display_type'] == 'color'): ?>
                            <span style="background-color: <?php echo($data['swatch_value']); ?>;"
                                  class="zoo-cw-label-color">
                                </span>
                        <?php elseif ($data['display_type'] == 'image'): ?>
                            <img src="<?php echo($data['swatch_value']); ?>">

                        <?php else: ?>
                            <span class="zoo-cw-label-text">
                                    <?php echo($data['display']); ?>
                                </span>
                        <?php endif;
                    else: ?>
                        <span class="zoo-cw-label-text">
                                    <?php echo($data['display']); ?>
                                </span>
                    <?php endif; ?>
                </div>
            </div>
        </dd>
    <?php endforeach; ?>
</dl>
