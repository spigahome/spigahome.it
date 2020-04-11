<?php namespace Cafe\Controls;

use Elementor\Base_Data_Control;

/**
 * CleverIcon
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE\Controls
 */
final class CleverIcon extends Base_Data_Control
{
    /**
     * Type
     *
     * @string
     */
    const TYPE = 'clevericon';

    /**
     * Get clevericon one area control type.
     *
     * Retrieve the control type, in this case `clevericon`.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function get_type()
    {
        return self::TYPE;
    }

    /**
     * Enqueue clever font one area control scripts and styles.
     *
     * Used to register and enqueue custom scripts and styles used by the clever font one
     * area control.
     *
     * @since 1.0.0
     * @access public
     */
    public function enqueue()
    {
        wp_register_style('cleverfont', CAFE_URI.'assets/vendor/cleverfont/style.css', [], '7834y238');
        wp_register_script('cafe-control', CAFE_URI.'assets/js/control.min.js', ['jquery'], '');
        // Styles
        wp_enqueue_style('cleverfont');
        wp_enqueue_script('cafe-control');
    }

    /**
     * Get icons control default settings.
     *
     * Retrieve the default settings of the icons control. Used to return the default
     * settings while initializing the icons control.
     *
     * @since 1.0.0
     * @access protected
     *
     * @return array Control default settings.
     */
    protected function get_default_settings()
    {
        return [
            'include' => '',
            'exclude' => '',
            'options' => ["cs-font clever-icon-layout-2"=>" icon-layout-2","cs-font clever-icon-button"=>" icon-button","cs-font clever-icon-quote-3"=>" icon-quote-3","cs-font clever-icon-page-builder"=>" icon-page-builder","cs-font clever-icon-carousel"=>" icon-carousel","cs-font clever-icon-banner"=>" icon-banner","cs-font clever-icon-divider"=>" icon-divider","cs-font clever-icon-click"=>" icon-click","cs-font clever-icon-cookie"=>" icon-cookie","cs-font clever-icon-tab"=>" icon-tab","cs-font clever-icon-slider"=>" icon-slider","cs-font clever-icon-recent-blog"=>" icon-recent-blog","cs-font clever-icon-blog"=>" icon-blog","cs-font clever-icon-wallet-1"=>" icon-wallet-1","cs-font clever-icon-handshake"=>" icon-handshake","cs-font clever-icon-undo-1"=>" icon-undo-1","cs-font clever-icon-plane-3"=>" icon-plane-3","cs-font clever-icon-plane-2"=>" icon-plane-2","cs-font clever-icon-clock-4"=>" icon-clock-4","cs-font clever-icon-play-4"=>" icon-play-4","cs-font clever-icon-play-3"=>" icon-play-3","cs-font clever-icon-face-1"=>" icon-face-1","cs-font clever-icon-comment-1"=>" icon-comment-1","cs-font clever-icon-comment-2"=>" icon-comment-2","cs-font clever-icon-comment-3"=>" icon-comment-3","cs-font clever-icon-comment-4"=>" icon-comment-4","cs-font clever-icon-360-2"=>" icon-360-2","cs-font clever-icon-360-1"=>" icon-360-1","cs-font clever-icon-heart-6"=>" icon-heart-6","cs-font clever-icon-heart-5"=>" icon-heart-5","cs-font clever-icon-filter-3"=>" icon-filter-3","cs-font clever-icon-refresh-5"=>" icon-refresh-5","cs-font clever-icon-heart-4"=>" icon-heart-4","cs-font clever-icon-heart-3"=>" icon-heart-3","cs-font clever-icon-ruler"=>" icon-ruler","cs-font clever-icon-help"=>" icon-help","cs-font clever-icon-hand-up"=>" icon-hand-up","cs-font clever-icon-hand-down"=>" icon-hand-down","cs-font clever-icon-arrow-up"=>" icon-arrow-up","cs-font clever-icon-arrow-down"=>" icon-arrow-down","cs-font clever-icon-arrow-left-4"=>" icon-arrow-left-4","cs-font clever-icon-arrow-right-4"=>" icon-arrow-right-4","cs-font clever-icon-refresh-4"=>" icon-refresh-4","cs-font clever-icon-refresh-3"=>" icon-refresh-3","cs-font clever-icon-quote-2"=>" icon-quote-2","cs-font clever-icon-pause"=>" icon-pause","cs-font clever-icon-check"=>" icon-check","cs-font clever-icon-caret-down"=>" icon-caret-down","cs-font clever-icon-caret-left"=>" icon-caret-left","cs-font clever-icon-caret-right"=>" icon-caret-right","cs-font clever-icon-caret-up"=>" icon-caret-up","cs-font clever-icon-caret-square-dow"=>" icon-caret-square-dow","cs-font clever-icon-caret-square-left"=>" icon-caret-square-left","cs-font clever-icon-caret-square-right"=>" icon-caret-square-right","cs-font clever-icon-caret-square-up"=>" icon-caret-square-up","cs-font clever-icon-check-circle-o"=>" icon-check-circle-o","cs-font clever-icon-check-circle"=>" icon-check-circle","cs-font clever-icon-check-square-o"=>" icon-check-square-o","cs-font clever-icon-check-square"=>" icon-check-square","cs-font clever-icon-circle-o"=>" icon-circle-o","cs-font clever-icon-circle"=>" icon-circle","cs-font clever-icon-dribbble"=>" icon-dribbble","cs-font clever-icon-flickr"=>" icon-flickr","cs-font clever-icon-foursquare"=>" icon-foursquare","cs-font clever-icon-github"=>" icon-github","cs-font clever-icon-linkedin"=>" icon-linkedin","cs-font clever-icon-rss"=>" icon-rss","cs-font clever-icon-square-o"=>" icon-square-o","cs-font clever-icon-square"=>" icon-square","cs-font clever-icon-star-o"=>" icon-star-o","cs-font clever-icon-star"=>" icon-star","cs-font clever-icon-tumblr"=>" icon-tumblr","cs-font clever-icon-xing"=>" icon-xing","cs-font clever-icon-twitter"=>" icon-twitter","cs-font clever-icon-cart-16"=>" icon-cart-16","cs-font clever-icon-heart-2"=>" icon-heart-2","cs-font clever-icon-eye-5"=>" icon-eye-5","cs-font clever-icon-facebook"=>" icon-facebook","cs-font clever-icon-googleplus"=>" icon-googleplus","cs-font clever-icon-instagram"=>" icon-instagram","cs-font clever-icon-pinterest"=>" icon-pinterest","cs-font clever-icon-skype"=>" icon-skype","cs-font clever-icon-vimeo"=>" icon-vimeo","cs-font clever-icon-youtube-1"=>" icon-youtube-1","cs-font clever-icon-award-1"=>" icon-award-1","cs-font clever-icon-clock-3"=>" icon-clock-3","cs-font clever-icon-three-dots"=>" icon-three-dots","cs-font clever-icon-share-2"=>" icon-share-2","cs-font clever-icon-building"=>" icon-building","cs-font clever-icon-faucet"=>" icon-faucet","cs-font clever-icon-flower"=>" icon-flower","cs-font clever-icon-house-1"=>" icon-house-1","cs-font clever-icon-house"=>" icon-house","cs-font clever-icon-pines"=>" icon-pines","cs-font clever-icon-plant"=>" icon-plant","cs-font clever-icon-sprout-1"=>" icon-sprout-1","cs-font clever-icon-sprout"=>" icon-sprout","cs-font clever-icon-trees"=>" icon-trees","cs-font clever-icon-close-1"=>" icon-close-1","cs-font clever-icon-list-2"=>" icon-list-2","cs-font clever-icon-grid-5"=>" icon-grid-5","cs-font clever-icon-menu-6"=>" icon-menu-6","cs-font clever-icon-three-dots-o"=>" icon-three-dots-o","cs-font clever-icon-list-1"=>" icon-list-1","cs-font clever-icon-menu-5"=>" icon-menu-5","cs-font clever-icon-menu-4"=>" icon-menu-4","cs-font clever-icon-heart-1"=>" icon-heart-1","cs-font clever-icon-user-6"=>" icon-user-6","cs-font clever-icon-attachment"=>" icon-attachment","cs-font clever-icon-cart-18"=>" icon-cart-18","cs-font clever-icon-ball"=>" icon-ball","cs-font clever-icon-battery"=>" icon-battery","cs-font clever-icon-briefcase"=>" icon-briefcase","cs-font clever-icon-car"=>" icon-car","cs-font clever-icon-cpu-2"=>" icon-cpu-2","cs-font clever-icon-cpu-1"=>" icon-cpu-1","cs-font clever-icon-dress-woman"=>" icon-dress-woman","cs-font clever-icon-drill-tool"=>" icon-drill-tool","cs-font clever-icon-feeding-bottle"=>" icon-feeding-bottle","cs-font clever-icon-fruit"=>" icon-fruit","cs-font clever-icon-furniture-2"=>" icon-furniture-2","cs-font clever-icon-furniture-1"=>" icon-furniture-1","cs-font clever-icon-shoes-woman-2"=>" icon-shoes-woman-2","cs-font clever-icon-shoes-woman-1"=>" icon-shoes-woman-1","cs-font clever-icon-horse"=>" icon-horse","cs-font clever-icon-laptop"=>" icon-laptop","cs-font clever-icon-lipstick"=>" icon-lipstick","cs-font clever-icon-iron"=>" icon-iron","cs-font clever-icon-perfume"=>" icon-perfume","cs-font clever-icon-baby-toy-2"=>" icon-baby-toy-2","cs-font clever-icon-baby-toy-1"=>" icon-baby-toy-1","cs-font clever-icon-paint-roller"=>" icon-paint-roller","cs-font clever-icon-shirt"=>" icon-shirt","cs-font clever-icon-shoe-man-2"=>" icon-shoe-man-2","cs-font clever-icon-small-diamond"=>" icon-small-diamond","cs-font clever-icon-tivi"=>" icon-tivi","cs-font clever-icon-smartphone"=>" icon-smartphone","cs-font clever-icon-lights"=>" icon-lights","cs-font clever-icon-microwave"=>" icon-microwave","cs-font clever-icon-wardrobe"=>" icon-wardrobe","cs-font clever-icon-washing-machine"=>" icon-washing-machine","cs-font clever-icon-watch-2"=>" icon-watch-2","cs-font clever-icon-watch-1"=>" icon-watch-1","cs-font clever-icon-slider-3"=>" icon-slider-3","cs-font clever-icon-slider-2"=>" icon-slider-2","cs-font clever-icon-slider-1"=>" icon-slider-1","cs-font clever-icon-cart-15"=>" icon-cart-15","cs-font clever-icon-cart-14"=>" icon-cart-14","cs-font clever-icon-cart-13"=>" icon-cart-13","cs-font clever-icon-cart-12"=>" icon-cart-12","cs-font clever-icon-cart-11"=>" icon-cart-11","cs-font clever-icon-cart-10"=>" icon-cart-10","cs-font clever-icon-cart-9"=>" icon-cart-9","cs-font clever-icon-cart-8"=>" icon-cart-8","cs-font clever-icon-pause-1"=>" icon-pause-1","cs-font clever-icon-arrow-left"=>" icon-arrow-left","cs-font clever-icon-arrow-left-1"=>" icon-arrow-left-1","cs-font clever-icon-arrow-left-2"=>" icon-arrow-left-2","cs-font clever-icon-arrow-left-3"=>" icon-arrow-left-3","cs-font clever-icon-arrow-right"=>" icon-arrow-right","cs-font clever-icon-arrow-right-1"=>" icon-arrow-right-1","cs-font clever-icon-arrow-right-2"=>" icon-arrow-right-2","cs-font clever-icon-arrow-right-3"=>" icon-arrow-right-3","cs-font clever-icon-plane-1"=>" icon-plane-1","cs-font clever-icon-cart-17"=>" icon-cart-17","cs-font clever-icon-filter-2"=>" icon-filter-2","cs-font clever-icon-filter-1"=>" icon-filter-1","cs-font clever-icon-grid-1"=>" icon-grid-1","cs-font clever-icon-contract"=>" icon-contract","cs-font clever-icon-expand"=>" icon-expand","cs-font clever-icon-cart-7"=>" icon-cart-7","cs-font clever-icon-quote-1"=>" icon-quote-1","cs-font clever-icon-arrow-right-5"=>" icon-arrow-right-5","cs-font clever-icon-arrow-left-5"=>" icon-arrow-left-5","cs-font clever-icon-refresh-2"=>" icon-refresh-2","cs-font clever-icon-truck"=>" icon-truck","cs-font clever-icon-wallet"=>" icon-wallet","cs-font clever-icon-electric-1"=>" icon-electric-1","cs-font clever-icon-electric-2"=>" icon-electric-2","cs-font clever-icon-lock"=>" icon-lock","cs-font clever-icon-share-1"=>" icon-share-1","cs-font clever-icon-check-box"=>" icon-check-box","cs-font clever-icon-clock-2"=>" icon-clock-2","cs-font clever-icon-analytics-laptop"=>" icon-analytics-laptop","cs-font clever-icon-code-design"=>" icon-code-design","cs-font clever-icon-competitive-chart"=>" icon-competitive-chart","cs-font clever-icon-computer-monitor-and-cellphone"=>" icon-computer-monitor-and-cellphone","cs-font clever-icon-consulting-message"=>" icon-consulting-message","cs-font clever-icon-creative-process"=>" icon-creative-process","cs-font clever-icon-customer-reviews"=>" icon-customer-reviews","cs-font clever-icon-data-visualization"=>" icon-data-visualization","cs-font clever-icon-document"=>" icon-document","cs-font clever-icon-download-2"=>" icon-download-2","cs-font clever-icon-download-1"=>" icon-download-1","cs-font clever-icon-mail-6"=>" icon-mail-6","cs-font clever-icon-file-sharing"=>" icon-file-sharing","cs-font clever-icon-finger-touch-screen"=>" icon-finger-touch-screen","cs-font clever-icon-horizontal-tablet-with-pencil"=>" icon-horizontal-tablet-with-pencil","cs-font clever-icon-illustration-tool"=>" icon-illustration-tool","cs-font clever-icon-keyboard-and-hands"=>" icon-keyboard-and-hands","cs-font clever-icon-landscape-image"=>" icon-landscape-image","cs-font clever-icon-layout-squares"=>" icon-layout-squares","cs-font clever-icon-mobile-app-developing"=>" icon-mobile-app-developing","cs-font clever-icon-online-purchase"=>" icon-online-purchase","cs-font clever-icon-online-shopping"=>" icon-online-shopping","cs-font clever-icon-online-video"=>" icon-online-video","cs-font clever-icon-clock-1"=>" icon-clock-1","cs-font clever-icon-padlock-key"=>" icon-padlock-key","cs-font clever-icon-pc-monitor"=>" icon-pc-monitor","cs-font clever-icon-place-localizer"=>" icon-place-localizer","cs-font clever-icon-search-results"=>" icon-search-results","cs-font clever-icon-search-tool"=>" icon-search-tool","cs-font clever-icon-settings-tools"=>" icon-settings-tools","cs-font clever-icon-sharing-symbol"=>" icon-sharing-symbol","cs-font clever-icon-site-map"=>" icon-site-map","cs-font clever-icon-smartphone-2"=>" icon-smartphone-2","cs-font clever-icon-tablet-2"=>" icon-tablet-2","cs-font clever-icon-thin-expand-arrows"=>" icon-thin-expand-arrows","cs-font clever-icon-upload-2"=>" icon-upload-2","cs-font clever-icon-upload-1"=>" icon-upload-1","cs-font clever-icon-volume-off"=>" icon-volume-off","cs-font clever-icon-volume-on"=>" icon-volume-on","cs-font clever-icon-news-list"=>" icon-news-list","cs-font clever-icon-desktop"=>" icon-desktop","cs-font clever-icon-news-grid"=>" icon-news-grid","cs-font clever-icon-setting"=>" icon-setting","cs-font clever-icon-web-home"=>" icon-web-home","cs-font clever-icon-web-link"=>" icon-web-link","cs-font clever-icon-web-links"=>" icon-web-links","cs-font clever-icon-website-protection"=>" icon-website-protection","cs-font clever-icon-team"=>" icon-team","cs-font clever-icon-zoom-in"=>" icon-zoom-in","cs-font clever-icon-zoom-out"=>" icon-zoom-out","cs-font clever-icon-arrow-1"=>" icon-arrow-1","cs-font clever-icon-arrow-bold"=>" icon-arrow-bold","cs-font clever-icon-arrow-light"=>" icon-arrow-light","cs-font clever-icon-arrow-regular"=>" icon-arrow-regular","cs-font clever-icon-cart-1"=>" icon-cart-1","cs-font clever-icon-cart-2"=>" icon-cart-2","cs-font clever-icon-cart-3"=>" icon-cart-3","cs-font clever-icon-cart-4"=>" icon-cart-4","cs-font clever-icon-cart-5"=>" icon-cart-5","cs-font clever-icon-cart-6"=>" icon-cart-6","cs-font clever-icon-chart"=>" icon-chart","cs-font clever-icon-close"=>" icon-close","cs-font clever-icon-compare-1"=>" icon-compare-1","cs-font clever-icon-compare-2"=>" icon-compare-2","cs-font clever-icon-compare-3"=>" icon-compare-3","cs-font clever-icon-compare-4"=>" icon-compare-4","cs-font clever-icon-compare-5"=>" icon-compare-5","cs-font clever-icon-compare-6"=>" icon-compare-6","cs-font clever-icon-compare-7"=>" icon-compare-7","cs-font clever-icon-down"=>" icon-down","cs-font clever-icon-grid"=>" icon-grid","cs-font clever-icon-hand"=>" icon-hand","cs-font clever-icon-layout-1"=>" icon-layout-1","cs-font clever-icon-layout"=>" icon-layout","cs-font clever-icon-light"=>" icon-light","cs-font clever-icon-play-1"=>" icon-play-1","cs-font clever-icon-list"=>" icon-list","cs-font clever-icon-mail-1"=>" icon-mail-1","cs-font clever-icon-mail-2"=>" icon-mail-2","cs-font clever-icon-mail-3"=>" icon-mail-3","cs-font clever-icon-mail-4"=>" icon-mail-4","cs-font clever-icon-mail-5"=>" icon-mail-5","cs-font clever-icon-map-1"=>" icon-map-1","cs-font clever-icon-map-2"=>" icon-map-2","cs-font clever-icon-map-3"=>" icon-map-3","cs-font clever-icon-map-4"=>" icon-map-4","cs-font clever-icon-map-5"=>" icon-map-5","cs-font clever-icon-menu-1"=>" icon-menu-1","cs-font clever-icon-menu-2"=>" icon-menu-2","cs-font clever-icon-grid-3"=>" icon-grid-3","cs-font clever-icon-grid-4"=>" icon-grid-4","cs-font clever-icon-menu-3"=>" icon-menu-3","cs-font clever-icon-grid-2"=>" icon-grid-2","cs-font clever-icon-minus"=>" icon-minus","cs-font clever-icon-next"=>" icon-next","cs-font clever-icon-phone-1"=>" icon-phone-1","cs-font clever-icon-phone-2"=>" icon-phone-2","cs-font clever-icon-phone-3"=>" icon-phone-3","cs-font clever-icon-phone-4"=>" icon-phone-4","cs-font clever-icon-phone-5"=>" icon-phone-5","cs-font clever-icon-phone-6"=>" icon-phone-6","cs-font clever-icon-picture"=>" icon-picture","cs-font clever-icon-pin"=>" icon-pin","cs-font clever-icon-plus"=>" icon-plus","cs-font clever-icon-prev"=>" icon-prev","cs-font clever-icon-eye-4"=>" icon-eye-4","cs-font clever-icon-eye-3"=>" icon-eye-3","cs-font clever-icon-eye-2"=>" icon-eye-2","cs-font clever-icon-eye-1"=>" icon-eye-1","cs-font clever-icon-refresh-1"=>" icon-refresh-1","cs-font clever-icon-youtube-2"=>" icon-youtube-2","cs-font clever-icon-search-1"=>" icon-search-1","cs-font clever-icon-search-2"=>" icon-search-2","cs-font clever-icon-search-3"=>" icon-search-3","cs-font clever-icon-search-4"=>" icon-search-4","cs-font clever-icon-search-5"=>" icon-search-5","cs-font clever-icon-support"=>" icon-support","cs-font clever-icon-tablet-1"=>" icon-tablet-1","cs-font clever-icon-play-2"=>" icon-play-2","cs-font clever-icon-up"=>" icon-up","cs-font clever-icon-user-1"=>" icon-user-1","cs-font clever-icon-user-2"=>" icon-user-2","cs-font clever-icon-user-3"=>" icon-user-3","cs-font clever-icon-user-4"=>" icon-user-4","cs-font clever-icon-user-5"=>" icon-user-5","cs-font clever-icon-user"=>" icon-user","cs-font clever-icon-vector1"=>" icon-vector1","cs-font clever-icon-wishlist1"=>" icon-wishlist1"]
        ];
    }

    /**
     * Render icons control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.0.0
     * @access public
     */
    public function content_template()
    {
        $control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<select id="<?php echo $control_uid; ?>" class="elementor-control-icon" data-setting="{{ data.name }}" data-placeholder="<?php echo esc_html__('Select Icon', 'cafe-lite' ); ?>">
					<option value=""><?php echo esc_html__('Select Icon', 'cafe-lite' ); ?></option>
					<# _.each( data.options, function( option_title, option_value ) { #>
					<option value="{{ option_value }}">{{{ option_title }}}</option>
					<# } ); #>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{ data.description }}</div>
		<# } #>
		<?php
    }
}
