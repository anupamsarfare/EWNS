<?php 
/* Template Name: Register Page
 * The template for displaying the Register page.
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */

if ( is_user_logged_in() ) {
	wp_redirect( get_home_url() );
	exit;
}

global $enc_key;

$override_wp_login = of_get_option('override_wp_login', 0);
$add_captcha_to_forms = of_get_option('add_captcha_to_forms', 1);
$let_users_set_pass = of_get_option('let_users_set_pass', 0);

$login_page_url_id = get_current_language_page_id(of_get_option('login_page_url', ''));
$login_page_url = get_permalink($login_page_url_id);
if (!$login_page_url || !$override_wp_login)
	$login_page_url = get_home_url() . '/wp-login.php';

$reset_password_page_url_id = get_current_language_page_id(of_get_option('reset_password_page_url', ''));
$reset_password_page_url = get_permalink($reset_password_page_url_id);
if (!$reset_password_page_url || !$override_wp_login)
	$reset_password_page_url = get_home_url() . '/wp-login.php?action=lostpassword';
	
$terms_page_url_id = get_current_language_page_id(of_get_option('terms_page_url', ''));
$terms_page_url = get_permalink($terms_page_url_id);

global $post;
$page_id = $post->ID;
$can_frontend_submit = get_post_meta($page_id, 'user_register_can_frontend_submit', true);

$errors = array();

// login
if( isset( $_POST['user_login'] ) &&  isset( $_POST['user_email'] ) && isset($_POST['bookyourtravel_register_form_nonce']) && wp_verify_nonce( $_POST['bookyourtravel_register_form_nonce'], 'bookyourtravel_register_form' ) ){

	// user data array
	$register_userdata = array(
		'user_login' => wp_kses( $_POST['user_login'], '' ),
		'user_email' => wp_kses( $_POST['user_email'], '' ),
		'first_name' => '',
		'last_name' => '',
		'user_url' => '',
		'description' => '',
		'email' => wp_kses( $_POST['user_email'], '' )
	);
	
	if ($let_users_set_pass) {
		$register_userdata['user_pass'] = wp_kses( $_POST['password'], '' );
		$register_userdata['confirm_pass'] = wp_kses( $_POST['repeat_password'], '' );
	} else {
		$register_userdata['user_pass'] = wp_generate_password( 10, false );
		$register_userdata['confirm_pass'] = $register_userdata['user_pass'];
	}
	
	// custom user meta array
	$register_usermeta = array(
		'agree' =>( ( isset( $_POST['checkboxagree'] ) && !empty( $_POST['checkboxagree'] ) ) ? '1' : '0' ),
		'user_activation_key' => wp_generate_password( 20, false )
	);
	
	// validation

	// validate username
	if ( trim( $register_userdata['user_login'] ) == '' ) {
		$errors['user_login'] = __( 'Username is required.', 'bookyourtravel' );
	}
	else if ( strlen( $register_userdata['user_login'] ) < 6 ) {
		$errors['user_login'] = __( 'Sorry, username must be 6 characters or more.', 'bookyourtravel' );
	}
	else if ( !validate_username( $register_userdata['user_login'] ) ) {
		$errors['user_login'] = __( 'Sorry, the username you provided is invalid.', 'bookyourtravel' );
	}
	else if ( username_exists( $register_userdata['user_login'] ) ) {
		$errors['user_login'] = __( 'Sorry, that username already exists.', 'bookyourtravel' );
	}

	$let_users_set_pass = of_get_option('let_users_set_pass', 0);
	if ($let_users_set_pass) {
		// validate password
		if ( trim( $register_userdata['user_pass'] ) == '' ) {
			$errors['user_pass'] = __( 'Password is required.', 'bookyourtravel' );
		}
		else if ( strlen( $register_userdata['user_pass'] ) < 6 ) {
			$errors['user_pass'] = __( 'Sorry, password must be 6 characters or more.', 'bookyourtravel' );
		}
		else if ( $register_userdata['user_pass'] !== $register_userdata['confirm_pass'] ) {
			$errors['confirm_pass'] = __( 'Password and confirm password fields must match.', 'bookyourtravel' );
		}
	}
	
	// validate user_email
	if ( !is_email( $register_userdata['user_email'] ) ) {
		$errors['user_email'] = __( 'You must enter a valid email address.', 'bookyourtravel' );
	}
	else if ( email_exists( $register_userdata['user_email'] ) ) {
		$errors['user_email'] = __( 'Sorry, that email address is already in use.', 'bookyourtravel' );
	}

	// validate agree
	if( $register_usermeta['agree'] == '0' ){
		$errors['agree'] = __( 'You must agree to our terms &amp; conditions to sign up.', 'bookyourtravel' );
	}

	// validate captcha
	if ($add_captcha_to_forms) {
		if (isset($_POST['c_val_s_reg']) && isset($_POST['c_val_1_reg']) && isset($_POST['c_val_2_reg'])) {
			$c_val_s = intval(wp_kses($_POST['c_val_s_reg'], ''));
			$c_val_1 = intval(contact_decrypt(wp_kses($_POST['c_val_1_reg'], ''), $enc_key));
			$c_val_2 = intval(contact_decrypt(wp_kses($_POST['c_val_2_reg'], ''), $enc_key));
			
			if ($c_val_s != ($c_val_1 + $c_val_2)) {
				$errors['captcha'] = __( 'You must input the correct captcha answer.', 'bookyourtravel' );
			}
		} else {
			$errors['captcha'] = __( 'You must input a valid captcha answer.', 'bookyourtravel' );
		}
	}

	if( empty( $errors ) ){
		
		// insert new user
		$new_user_id = wp_insert_user( $register_userdata );
		
		$new_user = get_userdata( $new_user_id );
		
		$user_obj = new WP_User($new_user_id);		
	
		if ($can_frontend_submit) {
			$user_obj->set_role( BOOKYOURTRAVEL_FRONTEND_SUBMIT_ROLE );
		} else {
			$user_obj->set_role( "Subscriber" );
		}		

		// update custom user meta
		foreach ( $register_usermeta as $key => $value ) {
			update_user_meta( $new_user_id, $key, $value );
		}

		// send notification
		byt_activation_notification( $new_user_id );

		// refresh
		wp_redirect( add_query_arg( array( 'action' => 'registered' ), get_permalink() ) );
		exit;
	}
} else {

}

$c_val_1_reg = mt_rand(1, 20);
$c_val_2_reg = mt_rand(1, 20);

$c_val_1_reg_str = contact_encrypt($c_val_1_reg, $enc_key);
$c_val_2_reg_str = contact_encrypt($c_val_2_reg, $enc_key);

get_header(); 
byt_breadcrumbs();
get_sidebar('under-header');
?>
	<section class="three-fourth">
		<form id="register_form" method="post" action="<?php echo get_permalink(); ?>" class="booking">
			<fieldset>
<?php
			/**
			 * Registration completed
			 */
			if( isset( $_GET['action'] ) && $_GET['action'] == 'registered'){ 
				?>
				<p class="success">
					<?php _e( 'Account was successfully created. Please click the activation link in the email we just sent you to complete the registration process.', 'bookyourtravel' ) ?>
				</p>
			<?php
			} else if( isset( $_GET['action'] ) && $_GET['action'] == 'activate' && isset( $_GET['user_id'] ) && isset( $_GET['activation_key'] ) ){
			/**
			 * User activation
			 */
				if( byt_activate_user( wp_kses( $_GET['user_id'], '' ), wp_kses( $_GET['activation_key'], '' ) ) ){

					?>
					<p class="success">
						<?php _e( 'User account successfully activated.', 'bookyourtravel' ) ?>
					</p>
					<?php

				} else{
					?>
					<p class="error">
						<?php _e( 'An error was encountered when attempting to activate your account.', 'bookyourtravel' ) ?>
					</p>
					<?php
				}
			} else if( isset( $_GET['action'] ) && $_GET['action'] == 'sendactivation' && isset( $_GET['user_id'] ) ){
				/**
				 * Resend activation notification
				 */
				if( byt_activation_notification( wp_kses( $_GET['user_id'], '', '' ) ) ){
					?>
					<p class="success">
						<?php _e( 'Activation link was successfully sent.', 'bookyourtravel' ) ?>
					</p>
					<?php
				} else { ?>
					<p class="error">
						<?php _e( 'An error was encountered when attempting to send the activation link. Please try again later.', 'bookyourtravel' ) ?>
					</p>
					<?php
				}
			} else {
			?>
				<h3><?php _e('Register', 'bookyourtravel'); ?></h3>
				<p class="row">	
				<?php _e('Already a member?', 'bookyourtravel'); ?> <?php echo sprintf(__('Proceed to <a href="%s">login</a> page', 'bookyourtravel'), $login_page_url); ?>. <?php _e('Forgotten your password?', 'bookyourtravel'); ?> <a href="<?php echo $reset_password_page_url; ?>"><?php _e('Reset it here', 'bookyourtravel'); ?></a>.
				</p>
				<?php
				if (count($errors) > 0) {
					?>
					<div class="error">
						<p><?php _e( 'Errors were encountered when processing your registration request.', 'bookyourtravel' ) ?></p>
						<?php foreach ($errors as $error) {
							echo '<p>' . $error . '</p>';
						} ?>
					</div>
					<?php
				}
				?>
				<div class="row twins">
					<div class="f-item">
						<label for="user_login"><?php _e('Username', 'bookyourtravel'); ?></label>
						<input tabindex="1" type="text" id="user_login" name="user_login" value="<?php echo isset($register_userdata) ? $register_userdata['user_login'] : ''; ?>" />
						<?php if( isset( $errors['user_login'] ) ){ ?>
							<span class="input_error"><?php echo $errors['user_login']; ?></span>
						<?php } ?>
					</div>
					<div class="f-item">
						<label for="user_email"><?php _e('Email', 'bookyourtravel'); ?></label>
						<input tabindex="2" type="email" id="user_email" name="user_email" value="<?php echo isset($register_userdata) ? $register_userdata['user_email'] : ''; ?>" />
						<?php if( isset( $errors['user_email'] ) ){ ?>
							<span class="input_error"><?php echo $errors['user_email']; ?></span>
						<?php } ?>
						<input type="hidden" name="email" id="email" value="" />
						<input type="hidden" name="password" id="password" value="" />
					</div>
				</div>
				<?php if ($add_captcha_to_forms) { ?>
				<div class="row captcha">
					<div class="f-item">
						<label><?php echo sprintf(__('How much is %d + %d', 'bookyourtravel'), $c_val_1_reg, $c_val_2_reg) ?>?</label>
						<input tabindex="3" type="text" required="required" id="c_val_s_reg" name="c_val_s_reg" />
						<input type="hidden" name="c_val_1_reg" id="c_val_1_reg" value="<?php echo $c_val_1_reg_str; ?>" />
						<input type="hidden" name="c_val_2_reg" id="c_val_2_reg" value="<?php echo $c_val_2_reg_str; ?>" />
					</div>
				</div>
				<?php } ?>
				<?php do_action('register_form', true); ?>  				
				<div class="row">					
					<div class="f-item checkbox">
						<div class="checker" id="uniform-check"><span><input type="checkbox" value="ch1" id="checkboxagree" name="checkboxagree" style="opacity: 0;"></span></div>
						<label><?php echo sprintf(__('I agree to the <a href="%s">terms &amp; conditions</a>.', 'bookyourtravel'), $terms_page_url); ?></label>
						<?php if( isset( $errors['agree'] ) ){ ?>
							<div class="error"><p><?php echo $errors['agree']; ?></p></div>
						<?php } ?>
					</div>
				</div>
				<?php wp_nonce_field( 'bookyourtravel_register_form', 'bookyourtravel_register_form_nonce' ) ?>
				<input type="submit" id="register" name="register" value="<?php _e('Register', 'bookyourtravel'); ?>" class="gradient-button"/>
			<?php } ?>
			</fieldset>
		</form>
	</section>
	
<?php get_footer(); ?>