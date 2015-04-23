<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7 ]>    <html class="ie7 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 	 ]>    <html class="ie" <?php language_attributes(); ?>> <![endif]-->
<!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title><?php wp_title(''); ?></title>
	<link rel="shortcut icon" href="<?php echo get_byt_file_uri('/images/favicon.ico'); ?>" />	
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<script type="text/javascript">
		window.themePath = '<?php echo get_template_directory_uri(); ?>';
	</script>
	<script type="text/javascript">
<?php
	global $site_url, $current_user, $currency_symbol, $current_currency, $default_currency, $enabled_currencies, $use_woocommerce_for_checkout, $woo_cart_page_uri;
	global $logo_src, $my_account_page, $cart_page, $custom_search_results_page, $enable_hotel_search, $enable_tour_search, $enable_self_catered_search, $enable_car_rental_search;
	global $price_decimal_places, $add_captcha_to_forms, $enable_reviews, $frontpage_show_slider, $business_address_longitude, $business_address_latitude;
	global $login_page_url, $register_page_url, $override_wp_login;

	if (!isset($current_user)) {
		$current_user = wp_get_current_user();
	}
	
	if (!isset($price_decimal_places)) {
		$price_decimal_places = (int)of_get_option('price_decimal_places', 0);
	}
	
	if (!isset($add_captcha_to_forms)) {
		$add_captcha_to_forms = (int)of_get_option('add_captcha_to_forms', 1);
	}

	if (!isset($enable_reviews)) {
		$enable_reviews = (int)of_get_option('enable_reviews', 1);
	}	
	
	$hide_header_ribbon = (int)of_get_option('hide_header_ribbon', 0);

	if ($current_user->ID > 0){	?>
		window.currentUserId = '<?php echo $current_user->ID;?>';
	<?php } else { ?>	
		window.currentUserId = 0;
	<?php } ?>
		window.site_url = '<?php echo $site_url; ?>';
		window.useWoocommerceForCheckout = <?php echo (isset($use_woocommerce_for_checkout) ? $use_woocommerce_for_checkout : 0); ?>;

		window.wooCartPageUri = '<?php echo $woo_cart_page_uri; ?>';
		window.currentCurrency = '<?php echo $current_currency;?>';
		window.defaultCurrency = '<?php echo $default_currency; ?>';
		window.currencySymbol = '<?php echo $currency_symbol; ?>';
<?php if ( defined( 'ICL_LANGUAGE_CODE' ) ) { ?>
		window.currentLanguage = '<?php echo ICL_LANGUAGE_CODE; ?>';
<?php } ?>
	</script>
<?php
	$body_class = '';
	$content_class = '';
	if (is_page_template('byt_home.php')) {
		$frontpage_show_slider = of_get_option('frontpage_show_slider', '1');
		if (!$frontpage_show_slider)
			$body_class = 'noslider';
	} elseif (is_page_template('page-contact.php') || is_page_template('page-contact-form-7.php')) {
		byt_contact_form_js();
		$content_class = (!empty($business_address_longitude) && !empty($business_address_latitude) ? '' : 'empty');
	}
	
	wp_head(); ?>
</head>
<body <?php body_class($body_class); ?>>
	<!--header-->
	<header>
		<div class="wrap clearfix">
			<!--logo-->
			<?php $logo_title = get_bloginfo('name') . ' | ' . ( is_home() || is_front_page() ? get_bloginfo('description') : wp_title('', false)); ?>
			<h1 class="logo"><a href="<?php echo get_home_url(); ?>" title="<?php echo $logo_title; ?>"><img src="<?php echo $logo_src; ?>" alt="<?php echo $logo_title; ?>" /></a></h1>
			<!--//logo-->
			<?php if (!$hide_header_ribbon) { ?>
			<!--ribbon-->
			<div class="ribbon">
				<nav>
					<ul class="profile-nav">
						<?php if (!is_user_logged_in() && (!empty($login_page_url) || !empty($register_page_url) || !empty($cart_page))) { ?>
						<li class="active"><a href="#" title="<?php _e('My Account', 'bookyourtravel'); ?>"><?php _e('My Account', 'bookyourtravel'); ?></a></li>
						<?php if (!empty($login_page_url)) { ?>
						<li><a class="fn" onclick="toggleLightbox('login_lightbox');" href="javascript:void(0);" title="<?php _e('Login', 'bookyourtravel'); ?>"><?php _e('Login', 'bookyourtravel'); ?></a></li>
						<?php } ?>
						<?php if (!empty($register_page_url)) { ?>
						<li><a class="fn" onclick="toggleLightbox('register_lightbox');" href="javascript:void(0);" title="<?php _e('Register', 'bookyourtravel'); ?>"><?php _e('Register', 'bookyourtravel'); ?></a></li>
						<?php } ?>
						<?php
						if (!empty($cart_page)) { ?>
							<li><a class="fn" href="<?php echo $cart_page; ?>"><?php _e('Cart', 'bookyourtravel'); ?></a></li>	
						<?php } ?>
						<?php } else {?>
						<li class="active"><a href="#" title="<?php _e('My Account', 'bookyourtravel'); ?>"><?php _e('My Account', 'bookyourtravel'); ?></a></li>
						<?php if ((!empty($my_account_page) || !empty($cart_page))) { ?>
						<li><a class="fn" href="<?php echo $my_account_page; ?>" title="<?php _e('Dashboard', 'bookyourtravel'); ?>"><?php _e('Dashboard', 'bookyourtravel'); ?></a></li>
						<?php						
						if (!empty($cart_page)) { ?>
							<li><a class="fn" href="<?php echo $cart_page; ?>"><?php _e('Cart', 'bookyourtravel'); ?></a></li>	
						<?php } ?>
						<?php } // (!empty($my_account_page) || !empty($cart_page)) ?>
						<li><a class="fn" href="<?php echo wp_logout_url(home_url()); ?>"><?php _e('Logout', 'bookyourtravel'); ?></a></li>
						<?php } ?>
					</ul>
					<?php if (!byt_is_woocommerce_active()) {?>
					<ul class="currency-nav">
					<?php
						foreach ($enabled_currencies as $key => $value) {
							$currency_obj = find_currency_object($value);
							$currency_label = $currency_obj->currency_label;
					?>
						<li <?php if (strtolower($current_currency) == strtolower($value)) echo 'class="active"'; ?>><a class="currency <?php echo $value; ?>" href="#" title="<?php echo $currency_label; ?>"><?php echo $currency_label; ?></a></li>
					<?php } ?>
					</ul>
					<?php } ?>
					<?php get_sidebar('header'); ?>
				</nav>
			</div>
			<!--//ribbon-->
			<?php } // endif (!$hide_header_ribbon) ?>
			<!--search-->
			<div class="search">
				<form id="searchform" method="get" action="<?php echo home_url(); ?>">
					<input type="search" placeholder="<?php _e('Search entire site here', 'bookyourtravel'); ?>" name="s" id="search" /> 
					<input type="submit" id="searchsubmit" value="" name="searchsubmit"/>
				</form>
			</div>
			<!--//search-->		
			<!--contact-->
			<div class="contact">
				<span><?php _e('24/7 Support number', 'bookyourtravel'); ?></span>
				<span class="number"><?php echo of_get_option('contact_phone_number', ''); ?></span>
			</div>
			<!--//contact-->
		</div>
		<!--primary navigation-->
		<?php  if ( has_nav_menu( 'primary-menu' ) ) {
			wp_nav_menu( array( 'theme_location' => 'primary-menu', 'container' => 'nav', 'container_class' => 'main-nav', 'container_id' => 'nav', 'menu_class' => 'wrap') );
		} else { ?>
		<nav class="main-nav">
			<ul class="wrap">
				<li class="menu-item"><a href="<?php echo home_url(); ?>"><?php _e('Home', "bookyourtravel"); ?></a></li>
				<li class="menu-item"><a href="<?php echo admin_url('nav-menus.php'); ?>"><?php _e('Configure', "bookyourtravel"); ?></a></li>
			</ul>
		</nav>
		<?php } ?>
		<!--//primary navigation-->
	</header>
	<!--//header-->
	<?php 
	if (is_page_template('byt_home.php')) {
		get_template_part('includes/parts/home-page-header', 'latest'); 
	}
	?>
	<!--main-->
	<div class="main" role="main" id="primary">		
		<div class="wrap clearfix">
			<!--main content-->
			<div class="content clearfix <?php echo $content_class; ?>" id="content">