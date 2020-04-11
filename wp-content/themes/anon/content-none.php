<?php
/**
 * The template part for displaying a message that posts cannot be found
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 
 */
?>

<section class="no-results not-found col-12">
    <h1 class="page-title the-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'anon' ); ?></h1>
	<div class="result-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
			<p><?php esc_html_e( 'Ready to publish your first post? ','anon');?>
			<a href="<?php echo admin_url( 'post-new.php' ) ?>"><?php esc_html_e('Get started here','anon');?></a></p>
		<?php elseif ( is_search() ) : ?>
			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'anon' ); ?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'anon' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</section>
