<?php
/**
 * Default site footer
 *
 * @package  Zoo_Theme\Templates
 * @author   zootemplate
 * @link     https://www.zootemplate.com/
 *
 */

?>
<div class="zoo-mask-close"></div>
<footer id="site-footer" class="site-footer">
    <div class="container">
        <?php
        echo esc_html(get_theme_mod('zoo_footer_copy_right',sprintf(esc_html__( '© %s ZooTemplate. All rights reserved.', 'anon' ),date("Y"))));
        ?>
    </div>
</footer>
<?php
wp_footer();
?>
</body>
</html>
