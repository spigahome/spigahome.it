<?php
/**
 * Custom Edd function for override hook template of Edd plugin
 * User: NTK
 * Date: 26/01/2019
 * Time: 11:15 SA
 */

remove_action( 'edd_purchase_form_after_user_info', 'edd_user_info_fields' );
remove_action( 'edd_register_fields_before', 'edd_user_info_fields' );
function zoo_edd_user_info_fields() {

	$customer = EDD()->session->get( 'customer' );
	$customer = wp_parse_args( $customer, array( 'first_name' => '', 'last_name' => '', 'email' => '' ) );

	if ( is_user_logged_in() ) {
		$user_data = get_userdata( get_current_user_id() );
		foreach ( $customer as $key => $field ) {

			if ( 'email' == $key && empty( $field ) ) {
				$customer[ $key ] = $user_data->user_email;
			} elseif ( empty( $field ) ) {
				$customer[ $key ] = $user_data->$key;
			}

		}
	}

	$customer = array_map( 'sanitize_text_field', $customer );
	?>
    <fieldset id="edd_checkout_user_info">
        <h3><?php echo apply_filters( 'edd_checkout_personal_info_text', esc_html__( 'Personal Info', 'anon' ) ); ?></h3>
		<?php do_action( 'edd_purchase_form_before_email' ); ?>
        <p id="edd-email-wrap">
            <label class="edd-label" for="edd-email">
				<?php esc_html_e( 'Email Address', 'anon' ); ?>
				<?php if ( edd_field_is_required( 'edd_email' ) ) { ?>
                    <span class="edd-required-indicator">*</span>
				<?php } ?>
            </label>
            <span class="edd-description"
                  id="edd-email-description"><?php esc_html_e( 'We will send the purchase receipt to this address.', 'anon' ); ?></span>
            <input class="edd-input required" type="email" name="edd_email" id="edd-email"
                   value="<?php echo esc_attr( $customer['email'] ); ?>"
                   aria-describedby="edd-email-description"<?php if ( edd_field_is_required( 'edd_email' ) ) {
				echo ' required ';
			} ?>/>
        </p>
		<?php do_action( 'edd_purchase_form_after_email' ); ?>
        <p id="edd-first-name-wrap">
            <label class="edd-label" for="edd-first">
				<?php esc_html_e( 'First Name', 'anon' ); ?>
				<?php if ( edd_field_is_required( 'edd_first' ) ) { ?>
                    <span class="edd-required-indicator">*</span>
				<?php } ?>
            </label>
            <input class="edd-input required" type="text" name="edd_first" id="edd-first"
                   value="<?php echo esc_attr( $customer['first_name'] ); ?>"<?php if ( edd_field_is_required( 'edd_first' ) ) {
				echo ' required ';
			} ?> aria-describedby="edd-first-description"/>
        </p>
        <p id="edd-last-name-wrap">
            <label class="edd-label" for="edd-last">
				<?php esc_html_e( 'Last Name', 'anon' ); ?>
				<?php if ( edd_field_is_required( 'edd_last' ) ) { ?>
                    <span class="edd-required-indicator">*</span>
				<?php } ?>
            </label>
            <input class="edd-input<?php if ( edd_field_is_required( 'edd_last' ) ) {
				echo ' required';
			} ?>" type="text" name="edd_last" id="edd-last"
                   value="<?php echo esc_attr( $customer['last_name'] ); ?>"<?php if ( edd_field_is_required( 'edd_last' ) ) {
				echo ' required ';
			} ?> aria-describedby="edd-last-description"/>
        </p>
		<?php do_action( 'edd_purchase_form_user_info' ); ?>
		<?php do_action( 'edd_purchase_form_user_info_fields' ); ?>
    </fieldset>
	<?php
}

add_action( 'edd_purchase_form_after_user_info', 'zoo_edd_user_info_fields' );
add_action( 'edd_register_fields_before', 'zoo_edd_user_info_fields' );

/**
 * Renders the user registration fields. If the user is logged in, a login
 * form is displayed other a registration form is provided for the user to
 * create an account.
 *
 * @since 1.0
 * @return string
 */
remove_action( 'edd_purchase_form_register_fields', 'edd_get_register_fields' );
function zoo_edd_get_register_fields() {
	$show_register_form = edd_get_option( 'show_register_form', 'none' );

	ob_start(); ?>
    <fieldset id="edd_register_fields">

		<?php do_action( 'edd_register_fields_before' ); ?>

        <fieldset id="edd_register_account_fields">
            <h3><?php _e( 'Create an account', 'anon' );
				if ( ! edd_no_guest_checkout() ) {
					echo ' ' . __( '(optional)', 'anon' );
				} ?></h3>
			<?php if ( $show_register_form == 'both' ) { ?>
                <p id="edd-login-account-wrap">
					<?php _e( 'Already have an account?', 'anon' ); ?>
                    <a href="<?php echo esc_url( add_query_arg( 'login', 1 ) ); ?>" class="edd_checkout_register_login"
                       data-action="checkout_login" data-nonce="<?php echo wp_create_nonce( 'edd_checkout_login' ); ?>">
						<?php _e( 'Login', 'anon' ); ?>
                    </a>
                </p>
			<?php } ?>
			<?php do_action( 'edd_register_account_fields_before' ); ?>
            <p id="edd-user-login-wrap">
                <label for="edd_user_login">
					<?php _e( 'Username', 'anon' ); ?>
					<?php if ( edd_no_guest_checkout() ) { ?>
                        <span class="edd-required-indicator">*</span>
					<?php } ?>
                </label>
                <span class="edd-description"><?php _e( 'The username you will use to log into your account.', 'anon' ); ?></span>
                <input name="edd_user_login" id="edd_user_login" class="<?php if ( edd_no_guest_checkout() ) {
					echo 'required ';
				} ?>edd-input" type="text"/>
            </p>
            <p id="edd-user-pass-wrap">
                <label for="edd_user_pass">
					<?php _e( 'Password', 'anon' ); ?>
					<?php if ( edd_no_guest_checkout() ) { ?>
                        <span class="edd-required-indicator">*</span>
					<?php } ?>
                </label>
                <input name="edd_user_pass" id="edd_user_pass" class="<?php if ( edd_no_guest_checkout() ) {
					echo 'required ';
				} ?>edd-input" type="password"/>
            </p>
            <p id="edd-user-pass-confirm-wrap" class="edd_register_password">
                <label for="edd_user_pass_confirm">
					<?php _e( 'Password Again', 'anon' ); ?>
					<?php if ( edd_no_guest_checkout() ) { ?>
                        <span class="edd-required-indicator">*</span>
					<?php } ?>
                </label>
                <input name="edd_user_pass_confirm" id="edd_user_pass_confirm"
                       class="<?php if ( edd_no_guest_checkout() ) {
					       echo 'required ';
				       } ?>edd-input"
                       type="password"/>
            </p>
			<?php do_action( 'edd_register_account_fields_after' ); ?>
        </fieldset>

		<?php do_action( 'edd_register_fields_after' ); ?>

        <input type="hidden" name="edd-purchase-var" value="needs-to-register"/>

		<?php do_action( 'edd_purchase_form_user_info' ); ?>
		<?php do_action( 'edd_purchase_form_user_register_fields' ); ?>

    </fieldset>
	<?php
	echo ob_get_clean();
}

add_action( 'edd_purchase_form_register_fields', 'zoo_edd_get_register_fields' );

/**
 * Gets the login fields for the login form on the checkout. This function hooks
 * on the edd_purchase_form_login_fields to display the login form if a user already
 * had an account.
 *
 * @since 1.0
 * @return string
 */
remove_action( 'edd_purchase_form_login_fields', 'edd_get_login_fields' );
function zoo_edd_get_login_fields() {
	$color = edd_get_option( 'checkout_color', 'gray' );
	$color = ( $color == 'inherit' ) ? '' : $color;
	$style = edd_get_option( 'button_style', 'button' );

	$show_register_form = edd_get_option( 'show_register_form', 'none' );

	ob_start(); ?>
    <fieldset id="edd_login_fields">
        <h3><?php _e( 'Log into Your Account', 'anon' ); ?></h3>
		<?php if ( $show_register_form == 'both' ) { ?>
            <p id="edd-new-account-wrap">
				<?php _e( 'Need to create an account?', 'anon' ); ?>
                <a href="<?php echo esc_url( remove_query_arg( 'login' ) ); ?>" class="edd_checkout_register_login"
                   data-action="checkout_register"
                   data-nonce="<?php echo wp_create_nonce( 'edd_checkout_register' ); ?>">
					<?php _e( 'Register', 'anon' );
					if ( ! edd_no_guest_checkout() ) {
						echo ' ' . __( 'or checkout as a guest.', 'anon' );
					} ?>
                </a>
            </p>
		<?php } ?>
		<?php do_action( 'edd_checkout_login_fields_before' ); ?>
        <p id="edd-user-login-wrap">
            <label class="edd-label" for="edd_user_login">
		        <?php _e( 'Username or Email', 'anon' ); ?>
		        <?php if( edd_no_guest_checkout() ) { ?>
                    <span class="edd-required-indicator">*</span>
		        <?php } ?>
            </label>
            <input class="<?php if ( edd_no_guest_checkout() ) {
				echo 'required ';
			} ?>edd-input" type="text" name="edd_user_login" id="edd_user_login" value="">
        </p>
        <p id="edd-user-pass-wrap" class="edd_login_password">
            <label class="edd-label" for="edd_user_pass">
		        <?php _e( 'Password', 'anon' ); ?>
		        <?php if( edd_no_guest_checkout() ) { ?>
                    <span class="edd-required-indicator">*</span>
		        <?php } ?>
            </label>
            <input class="<?php if ( edd_no_guest_checkout() ) {
				echo 'required ';
			} ?>edd-input" type="password" name="edd_user_pass" id="edd_user_pass"
                  />
			<?php if ( edd_no_guest_checkout() ) : ?>
                <input type="hidden" name="edd-purchase-var" value="needs-to-login"/>
			<?php endif; ?>
        </p>
        <p id="edd-user-login-submit">
            <input type="submit" class="edd-submit button <?php echo esc_attr($color); ?>" name="edd_login_submit"
                   value="<?php _e( 'Login', 'anon' ); ?>"/>
			<?php wp_nonce_field( 'edd-login-form', 'edd_login_nonce', false, true ); ?>
        </p>
		<?php do_action( 'edd_checkout_login_fields_after' ); ?>
    </fieldset><!--end #edd_login_fields-->
	<?php
	echo ob_get_clean();
}

add_action( 'edd_purchase_form_login_fields', 'zoo_edd_get_login_fields' );

/**
 *
 * =================
 * ADD HARD CODE
 * =================
 *
 *
 * Renders the Checkout Submit section
 *
 * @since 1.3.3
 * @return void
 */

remove_action( 'edd_purchase_form_after_cc_form', 'edd_checkout_submit', 9999 );
function zoo_edd_checkout_submit() {
	?>
    <fieldset id="edd_purchase_submit">
		<?php do_action( 'edd_purchase_form_before_submit' ); ?>

		<?php edd_checkout_hidden_fields(); ?>

		<?php echo edd_checkout_button_purchase(); ?>

		<?php do_action( 'edd_purchase_form_after_submit' ); ?>
        <div class="edd-agree-to-privacy-policy">By clicking on the purchase button, you agree to our <a href="https://cleveraddon.com/terms/">Terms of Service</a> and <a rel="noreferrer noopener" aria-label=" (opens in a new tab)" href="https://cleveraddon.com/privacy-policy/" target="_blank">Privacy Policy</a>.</div>
		<?php if ( edd_is_ajax_disabled() ) { ?>
            <p class="edd-cancel"><a href="<?php echo edd_get_checkout_uri(); ?>"><?php _e( 'Go back', 'anon' ); ?></a></p>
		<?php } ?>
    </fieldset>
	<?php
}
add_action( 'edd_purchase_form_after_cc_form', 'zoo_edd_checkout_submit', 9999 );