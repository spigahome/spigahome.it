<?php
/** Contact
 *  Display contact button when active.
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.anon.com/
 * @copyright   Copyright (c) 2020 Zootemplate
 */
if (get_theme_mod('zoo_contact_type', 'none') != 'none' && get_theme_mod('zoo_contact_id', '') != '') {

    ?>
    <span class="zoo-contact-button <?php echo esc_attr(get_theme_mod('zoo_contact_pos', 'left'))?>">
    <?php
    switch (get_theme_mod('zoo_contact_type', 'none')) {
        case 'phone':
            ?>
            <a href="tel:<?php echo esc_attr(get_theme_mod('zoo_contact_id', '')) ?>" target="blank" class="zoo-contact-link phone-button" title="<?php esc_attr_e('Call to:', 'anon'); echo esc_attr(get_theme_mod('zoo_contact_id', ''))?>"><i class="cs-font clever-icon-phone-6"></i></a>
            <?php
            break;
        case 'email':
            ?>
            <a href="mailto:<?php echo esc_attr(get_theme_mod('zoo_contact_id', '')) ?>" target="blank" class="zoo-contact-link  email-button" title="<?php esc_attr_e('Mail to:', 'anon');echo esc_attr(get_theme_mod('zoo_contact_id', ''))?>"><i class="cs-font clever-icon-mail-5"></i></a>
            <?php
            break;
        case 'skype':?>
            <a href="skype:<?php echo esc_attr(get_theme_mod('zoo_contact_id', '')) ?>?chat" target="blank" class="zoo-contact-link  skype-button" title="<?php esc_attr_e('Chat with:', 'anon');echo esc_attr(get_theme_mod('zoo_contact_id', ''))?>"><i class="cs-font clever-icon-skype"></i></a>
            <?php
            break;
        case 'messenger':
            ?>
            <a href="http://m.me/<?php echo esc_attr(get_theme_mod('zoo_contact_id', '')) ?>" target="blank" class="zoo-contact-link messenger-button" title="<?php esc_attr_e('Chat with:', 'anon');echo esc_attr(get_theme_mod('zoo_contact_id', ''))?>"><i class="cs-font clever-icon-messenger-filled"></i></a>
            <?php
            break;
        case 'whatsapp':?>
            <a href="whatsapp://send?abid=<?php echo esc_attr(get_theme_mod('zoo_contact_id', '')) ?>" target="blank" class="zoo-contact-link whatsapp-button" title="<?php esc_attr_e('Chat with:', 'anon');echo esc_attr(get_theme_mod('zoo_contact_id', ''))?>"><i class="cs-font clever-icon-whatsapp-filled"></i></a>
            <?php
            break;
    }
    ?>
    </span>
    <?php
}
