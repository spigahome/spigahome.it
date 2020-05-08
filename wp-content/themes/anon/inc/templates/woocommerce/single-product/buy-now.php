<?php
/**
 * Product buy now button template
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 *
 */

global $product;
if ( $product->get_type() == 'external' ) {
    return false;
}
$open_link=$close_link='';
if(get_privacy_policy_url()!=''){
    $open_link=' <a href='.get_privacy_policy_url().'> ';
    $close_link='</a>';
}
?>
<div class="wrap-buy-now">
    <?php
    if(get_theme_mod('zoo_enable_term_buy_now',1)==1){
        ?>
        <p class="zoo-product-term">
            <input id="zoo-agree-term" type="checkbox" name="agree-term"  onchange="document.getElementById('zoo-buy-now').disabled = !this.checked;"/><label
                    for="zoo-agree-term"><?php printf(esc_html__( 'I agree with %s terms and conditions %s', 'anon' ),$open_link,$close_link) ?></label>
        </p>
    <?php }?>
    <button id="zoo-buy-now" type="submit"
            class="button zoo-buy-now single_add_to_cart_button" <?php
            if(get_theme_mod('zoo_enable_term_buy_now',1)==1){
            ?>disabled <?php }?>><?php esc_html_e( "Buy now", 'anon' ); ?></button>

</div>
