<?php

 /**
 * Sets up theme defaults and registers the various WordPress features that
 * Book Your Travel supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Book Your Travel 1.0
 */
function bookyourtravel_setup() {
	/*
	 * Book Your Travel available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Book Your Travel, use a find and replace
	 * to change 'bookyourtravel' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'bookyourtravel', get_template_directory() . '/languages' );
	
	// This theme uses wp_nav_menu() in three locations.
	register_nav_menus( array(
		'primary-menu' => __( 'Primary Menu', 'bookyourtravel' ),
		'footer-menu' => __( 'Footer Menu', 'bookyourtravel' ),
		'customer-support-menu' => __( 'Customer Support Menu', 'bookyourtravel' )
	) );	
	
	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	
	// This theme is woocommerce compatible
	add_theme_support( 'woocommerce' );
	
	add_theme_support( 'automatic-feed-links' );
	
	if ( ! isset( $content_width ) ) {
		$content_width = 815;
	}
	
	set_post_thumbnail_size( 200, 200, true );
	add_image_size( 'related', 180, 120, true ); //related
	add_image_size( 'featured', 815, 459, true ); //Featured
	
	//Left Sidebar Widget area
	register_sidebar(array(
		'name'=> __('Left Sidebar', 'bookyourtravel'),
		'id'=>'left',
		'description' => __('This Widget area is used for the left sidebar', 'bookyourtravel'),
		'before_widget' => '<li class="widget widget-sidebar">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	
	// Right Sidebar Widget area
	register_sidebar(array(
		'name'=> __('Right Sidebar', 'bookyourtravel'),
		'id'=>'right',
		'description' => __('This Widget area is used for the right sidebar', 'bookyourtravel'),
		'before_widget' => '<li class="widget widget-sidebar">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	
	// Under Header Sidebar Widget area
	register_sidebar(array(
		'name'=> __('Under Header Sidebar', 'bookyourtravel'),
		'id'=>'under-header',
		'description' => __('This Widget area is placed under the website header', 'bookyourtravel'),
		'before_widget' => '<li class="widget widget-sidebar">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	
	// Under Header Sidebar Widget area
	register_sidebar(array(
		'name'=> __('Above Footer Sidebar', 'bookyourtravel'),
		'id'=>'above-footer',
		'description' => __('This Widget area is placed above the website footer', 'bookyourtravel'),
		'before_widget' => '<li class="widget widget-sidebar">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	
	// Right Sidebar Widget area for Accommodation
	register_sidebar(array(
		'name'=> __('Right Sidebar Accommodation', 'bookyourtravel'),
		'id'=>'right-accommodation',
		'description' => __('This Widget area is used for the right sidebar of the single accommodation screen', 'bookyourtravel'),
		'before_widget' => '<li class="widget widget-sidebar">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	
	// Right Sidebar Widget area for Tour
	register_sidebar(array(
		'name'=> __('Right Sidebar Tour', 'bookyourtravel'),
		'id'=>'right-tour',
		'description' => __('This Widget area is used for the right sidebar of the single tour screen', 'bookyourtravel'),
		'before_widget' => '<li class="widget widget-sidebar">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	
	// Right Sidebar Widget area for Custom search results
	register_sidebar(array(
		'name'=> __('Left Sidebar Search', 'bookyourtravel'),
		'id'=>'left-search',
		'description' => __('This Widget area is used for the left sidebar of the custom search results screen', 'bookyourtravel'),
		'before_widget' => '<li class="widget widget-sidebar">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	
	// Right Sidebar Widget area for Car rental
	register_sidebar(array(
		'name'=> __('Right Sidebar Car rental', 'bookyourtravel'),
		'id'=>'right-car_rental',
		'description' => __('This Widget area is used for the right sidebar of the single car rental screen', 'bookyourtravel'),
		'before_widget' => '<li class="widget widget-sidebar">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	
	// Right Sidebar Widget area for Tour
	register_sidebar(array(
		'name'=> __('Right Sidebar Cruise', 'bookyourtravel'),
		'id'=>'right-cruise',
		'description' => __('This Widget area is used for the right sidebar of the single cruise screen', 'bookyourtravel'),
		'before_widget' => '<li class="widget widget-sidebar">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	
	// Footer Sidebar Widget area
	register_sidebar(array(
		'name'=> __('Footer Sidebar', 'bookyourtravel'),
		'id'=>'footer',
		'description' => __('This Widget area is used for the footer area', 'bookyourtravel'),
		'before_widget' => '<li class="widget widget-sidebar">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	
	// Header Sidebar Widget area
	register_sidebar(array(
		'name'=> __('Header Sidebar', 'bookyourtravel'),
		'id'=>'header',
		'description' => __('This Widget area is used for the header area (usually for purposes of displaying WPML language switcher widget)', 'bookyourtravel'),
		'before_widget' => '',
		'after_widget' => '',
		'class'	=> 'lang-nav',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	
	// Home Footer Sidebar Widget area
	register_sidebar(array(
		'name'=> __('Home Footer Widget Area', 'bookyourtravel'),
		'id'=>'home-footer',
		'description' => __('This Widget area is used for the home page footer area above the regular footer', 'bookyourtravel'),
		'before_widget' => '<li class="widget widget-sidebar">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	
	register_sidebar(array(
		'name'=> __('Home Above Slider Widget Area', 'bookyourtravel'),
		'id'=>'home-above-slider',
		'description' => __('This Widget area is used for the home page area above the slider', 'bookyourtravel'),
		'before_widget' => '<li class="widget widget-sidebar">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

	register_sidebar(array(
		'name'=> __('Home Below Slider Widget Area', 'bookyourtravel'),
		'id'=>'home-below-slider',
		'description' => __('This Widget area is used for the home page area immediately below the slider', 'bookyourtravel'),
		'before_widget' => '<li class="widget widget-sidebar">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	
	// create new frontend submit role custom to BYT if it's not already created
	$frontend_submit_role = get_role(BOOKYOURTRAVEL_FRONTEND_SUBMIT_ROLE);
	if ($frontend_submit_role == null) {
		$frontend_submit_role = add_role(
			BOOKYOURTRAVEL_FRONTEND_SUBMIT_ROLE,
			__( 'BYT Frontend Submit Role', 'bookyourtravel' ),
			array(
				'read'         => true,  // true allows this capability
			)
		);
	}
	
}
add_action( 'after_setup_theme', 'bookyourtravel_setup' );

function bookyourtravel_init() {

	global $site_url, $current_user, $currency_symbol, $current_currency, $enabled_currencies, $default_currency, $slider_speed, $use_woocommerce_for_checkout, $woo_cart_page_uri;
	global $logo_src, $my_account_page, $cart_page, $custom_search_results_page, $enable_hotel_search, $enable_tour_search, $enable_self_catered_search, $enable_car_rental_search;
	global $enable_cruise_search, $login_page_url, $register_page_url, $override_wp_login;
	
	$override_wp_login = of_get_option('override_wp_login', 0);
	
	$enable_hotel_search = of_get_option('enable_hotel_search', 1);
	$enable_tour_search = of_get_option('enable_tour_search', 1);
	$enable_self_catered_search = of_get_option('enable_self_catered_search', 1);
	$enable_car_rental_search = of_get_option('enable_car_rental_search', 1);
	$enable_cruise_search = of_get_option('enable_cruise_search', 1);
	
	$site_url = site_url();
	
	$use_woocommerce_for_checkout = 0;
	$woo_cart_page_uri = '';
	if (function_exists('wc_get_page_id') && byt_is_woocommerce_active()) {
		$use_woocommerce_for_checkout = of_get_option('use_woocommerce_for_checkout', 0);
		$use_woocommerce_for_checkout = $use_woocommerce_for_checkout ? 1 : 0;
		$cart_page_id = wc_get_page_id( 'cart' );
		$woo_cart_page_uri = get_permalink($cart_page_id);
	}

	$my_account_page_id = get_current_language_page_id(of_get_option('my_account_page', ''));
	$my_account_page = get_permalink($my_account_page_id);
	
	$login_page_url_id = get_current_language_page_id(of_get_option('login_page_url', ''));
	$login_page_url = get_permalink($login_page_url_id);
	if (!$login_page_url || !$override_wp_login)
		$login_page_url = get_home_url() . '/wp-login.php';
		
	$register_page_url_id = get_current_language_page_id(of_get_option('register_page_url', ''));
	$register_page_url = get_permalink($register_page_url_id);
	if (!$register_page_url || !$override_wp_login)
		$register_page_url = get_home_url() . '/wp-login.php?action=register';
	
	$color_scheme_style_sheet = of_get_option('color_scheme_select', 'style');
	$logo_src = of_get_option('website_logo_upload', '');
	
	if (empty($logo_src)) {
		if (empty($color_scheme_style_sheet)) 
			$logo_src = get_byt_file_uri('/images/txt/logo.png');
		else if ($color_scheme_style_sheet == 'theme-strawberry')
			$logo_src = get_byt_file_uri('/images/themes/strawberry/txt/logo.png');
		else if ($color_scheme_style_sheet == 'theme-black')
			$logo_src = get_byt_file_uri('/images/themes/black/txt/logo.png');
		else if ($color_scheme_style_sheet == 'theme-blue')
			$logo_src = get_byt_file_uri('/images/themes/blue/txt/logo.png');
		else if ($color_scheme_style_sheet == 'theme-orange')
			$logo_src = get_byt_file_uri('/images/themes/orange/txt/logo.png');
		else if ($color_scheme_style_sheet == 'theme-pink')
			$logo_src = get_byt_file_uri('/images/themes/pink/txt/logo.png');
		else if ($color_scheme_style_sheet == 'theme-yellow')
			$logo_src = get_byt_file_uri('/images/themes/yellow/txt/logo.png');
		else if ($color_scheme_style_sheet == 'theme-navy')
			$logo_src = get_byt_file_uri('/images/themes/navy/txt/logo.png');
		else 
			$logo_src = get_byt_file_uri('/images/txt/logo.png');
	}
	
	$cart_page = '';
	$cart_page_id = 0;
	if (function_exists('wc_get_page_id'))
		$cart_page_id = wc_get_page_id( 'cart' );
	if ($cart_page_id > 0)
		$cart_page = get_permalink($cart_page_id);
		
	$custom_search_results_page_id = get_current_language_page_id(of_get_option('redirect_to_search_results', ''));
	$custom_search_results_page = get_permalink($custom_search_results_page_id);

	bookyourtravel_init_currency();
}
add_action('init', 'bookyourtravel_init');

function bookyourtravel_init_currency() {

	global $current_user, $currency_symbol, $current_currency, $default_currency, $enabled_currencies;

	$current_user = wp_get_current_user();

	$default_currency = strtoupper(of_get_option('default_currency_select', 'USD'));
	$current_currency = $default_currency;
	
	$default_currency_list = array(
		'usd'=> '1',
		'gbp'=> '1',
		'eur'=> '1'
	);
	
	$possible_currencies = of_get_option('enabled_currencies', $default_currency_list);
	if (!$possible_currencies)
		$possible_currencies = $default_currency_list;
	$enabled_currencies = array();
	foreach ($possible_currencies as $currency => $enabled) {
		if ($enabled == '1')
			$enabled_currencies[] = $currency;
	}

	if ($current_user->ID > 0 && !byt_is_woocommerce_active()){
		$user_currency = get_user_meta($current_user->ID, 'user_currency', true);

		if (!empty($user_currency) && in_array(strtolower($user_currency), $enabled_currencies))
			$current_currency = $user_currency;
	}
	
	$currency_obj = find_currency_object($current_currency);
	if ($currency_obj)
		$currency_symbol = $currency_obj->currency_symbol;
	
	// now that we have determined our currency currency, we should make sure woocommerce is in sync.
	$woocommerce_currency = get_option('woocommerce_currency');
	if ($woocommerce_currency != $current_currency) {
		update_option('woocommerce_currency', $current_currency);
	}
	
}

/**
 * Enqueues scripts and styles for front-end.
 *
 * @since Book Your Travel 1.0
 */
function bookyourtravel_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/*
	 * Adds JavaScript for various theme features
	 */
	 
	wp_enqueue_script('jquery');

	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-slider');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('jquery-ui-droppable');
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-selectable');
	wp_enqueue_script('jquery-ui-autocomplete');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_script('jquery-ui-spinner');
	
	wp_enqueue_script('jquery-effects-core');
	
	wp_enqueue_script( 'bookyourtravel-jquery-validate', get_byt_file_uri ('/js/jquery.validate.min.js'), array('jquery'), '1.0', true );

	$page_object = get_queried_object();
	$page_id     = get_queried_object_id();

	if (is_single()) {
		wp_register_script( 'jquery-lightSlider', get_byt_file_uri ('/plugins/lightSlider/js/jquery.lightSlider.js'), 'jquery', '1.0', true	);
		wp_enqueue_script( 'jquery-lightSlider' );	

		wp_enqueue_style( 'bookyourtravel-lightSlider-style', get_byt_file_uri('/plugins/lightSlider/css/lightSlider.css') );
	}
	
	if (is_single() && get_post_type() == 'accommodation') {
	
		wp_register_script( 'google-maps', '//maps.google.com/maps/api/js?sensor=false', 'jquery', '1.0', true	);
		wp_enqueue_script( 'google-maps' );

		wp_register_script( 'infobox', get_byt_file_uri ('/js/infobox.js'),'jquery', '1.0', true );
		wp_enqueue_script( 'infobox' );
	
		wp_register_script(	'tablesorter', get_byt_file_uri ('/js/jquery.tablesorter.min.js'), 'jquery','1.0', true );
		wp_enqueue_script( 'tablesorter' );	
	
		wp_register_script(	'accommodations', get_byt_file_uri ('/js/accommodations.js'), 'jquery', '1.0', true );
		wp_enqueue_script( 'accommodations' );	

		wp_register_script( 'reviews', get_byt_file_uri ('/js/reviews.js'), 'jquery', '1.0', true );
		wp_enqueue_script( 'reviews' );
		
		wp_register_script( 'inquiry', get_byt_file_uri ('/js/inquiry.js'), 'jquery', '1.0', true );
		wp_enqueue_script( 'inquiry' );
		
	} else if (is_single() && get_post_type() == 'tour') {
	
		wp_register_script( 'google-maps', '//maps.google.com/maps/api/js?sensor=false', 'jquery',	'1.0', true );
		wp_enqueue_script( 'google-maps' );
	
		wp_register_script( 'tours', get_byt_file_uri ('/js/tours.js'), 'jquery', '1.0', true );
		wp_enqueue_script( 'tours' );
		
		wp_register_script( 'reviews', get_byt_file_uri ('/js/reviews.js'), 'jquery', '1.0', true );
		wp_enqueue_script( 'reviews' );
		
		wp_register_script( 'inquiry', get_byt_file_uri ('/js/inquiry.js'), 'jquery', '1.0', true );
		wp_enqueue_script( 'inquiry' );

	} else if (is_single() && get_post_type() == 'cruise') {
	
		wp_register_script( 'google-maps', '//maps.google.com/maps/api/js?sensor=false', 'jquery',	'1.0', true );
		wp_enqueue_script( 'google-maps' );
	
		wp_register_script( 'cruises', get_byt_file_uri ('/js/cruises.js'), 'jquery', '1.0', true );
		wp_enqueue_script( 'cruises' );	

		wp_register_script( 'reviews', get_byt_file_uri ('/js/reviews.js'), 'jquery', '1.0', true );
		wp_enqueue_script( 'reviews' );
		
		wp_register_script( 'inquiry', get_byt_file_uri ('/js/inquiry.js'), 'jquery', '1.0', true );
		wp_enqueue_script( 'inquiry' );
		
	} else if (is_single() && get_post_type() == 'car_rental') {	
		
		wp_register_script( 'car_rentals', get_byt_file_uri ('/js/car_rentals.js'), 'jquery', '1.0', true );
		wp_enqueue_script( 'car_rentals' );	
		
		wp_register_script( 'inquiry', get_byt_file_uri ('/js/inquiry.js'), 'jquery', '1.0', true );
		wp_enqueue_script( 'inquiry' );
		
	} else if (is_single() && get_post_type() == 'location') {	
		
		wp_register_script( 'locations', get_byt_file_uri ('/js/locations.js'), 'jquery', '1.0', true );
		wp_enqueue_script( 'locations' );	
		
	} else if (is_page() ) {
		wp_register_script( 'frontend-submit', get_byt_file_uri ('/plugins/frontend-submit/frontend-submit.js'), array( 'jquery', 'bookyourtravel-jquery-validate' ), '1.0', true );
		wp_enqueue_script( 'frontend-submit' );	
	
	} 

	if (is_page_template('byt_home.php')) {

		wp_register_script( 'bookyourtravel-search', get_byt_file_uri ('/js/search.js'), array('jquery', 'bookyourtravel-jquery-uniform'), '1.0', true );	
		wp_enqueue_script( 'bookyourtravel-search' );	
		
		wp_localize_script( 'bookyourtravel-search', 'BYTAjax', array( 
		   'ajaxurl'        => admin_url( 'admin-ajax.php' ),
		   'nonce'   => wp_create_nonce('byt-ajax-nonce') 
		) );
		
		wp_enqueue_script( 'custom-suggest', get_byt_file_uri ('/js/custom-suggest.js'), array('jquery'), '', true );
		
	}

	wp_enqueue_script( 'bookyourtravel-mediaqueries', get_byt_file_uri ('/js/respond.js'), array('jquery'), '1.0', true );
	wp_enqueue_script( 'bookyourtravel-jquery-uniform', get_byt_file_uri ('/js/jquery.uniform.min.js'), array('jquery', 'jquery-ui-core'), '1.0', true );
	wp_enqueue_script( 'bookyourtravel-jquery-prettyPhoto', get_byt_file_uri ('/js/jquery.prettyPhoto.js'), array('jquery'), '1.0', true );
	wp_enqueue_script( 'bookyourtravel-jquery-raty', get_byt_file_uri ('/js/jquery.raty.min.js'), array('jquery'), '1.0', true );
	wp_enqueue_script( 'bookyourtravel-selectnav', get_byt_file_uri ('/js/selectnav.js'), array('jquery'), '1.0', true );
	wp_enqueue_script( 'bookyourtravel-scripts', get_byt_file_uri ('/js/scripts.js'), array('jquery', 'bookyourtravel-jquery-uniform'), '1.0', true );
	
	wp_localize_script( 'bookyourtravel-scripts', 'BYTAjax', array( 
		   'ajaxurl'        => admin_url( 'admin-ajax.php' ),
		   'nonce'   => wp_create_nonce('byt-ajax-nonce') 
		) );

	/*
	 * Loads our main stylesheets.
	 */
	wp_enqueue_style( 'bookyourtravel-style-main', get_byt_file_uri('/css/style.css'), array(), '1.0', "screen,projection,print");
	wp_enqueue_style( 'bookyourtravel-style', get_stylesheet_uri() );
	
	$enable_rtl = of_get_option('enable_rtl', 0);
	if ($enable_rtl) {
		wp_enqueue_style( 'bookyourtravel-style-rtl', get_byt_file_uri('/css/style-rtl.css'), array(), '1.0', "screen,projection,print");
	}
	
	/*
	 * Load the color scheme sheet if set in set in options.
	 */	 
	$color_scheme_style_sheet = of_get_option('color_scheme_select', 'style');
	if (!empty($color_scheme_style_sheet)) {
		wp_enqueue_style('bookyourtravel-style-color',  get_byt_file_uri('/css/' . $color_scheme_style_sheet . '.css'), array(), '1.0', "screen,projection,print");
	}
	
	wp_enqueue_style('bookyourtravel-style-pp',  get_byt_file_uri('/css/prettyPhoto.css'), array(), '1.0', "screen");
	 
}
add_action( 'wp_enqueue_scripts', 'bookyourtravel_scripts_styles' );

/**
 * Enqueues scripts and styles for admin.
 *
 * @since Book Your Travel 1.0
 */
function bookyourtravel_admin_scripts_styles() {

	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-effects-core');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-slider');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('jquery-ui-droppable');
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-selectable');
	wp_enqueue_script('jquery-ui-autocomplete');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_script('jquery-ui-spinner');
	
	wp_register_script('byt-admin', get_byt_file_uri('/includes/admin/admin.js'), false, '1.0.0');
	wp_enqueue_script('byt-admin');
	
	wp_enqueue_style('byt-admin-ui-css', get_byt_file_uri('/css/jquery-ui.min.css'), false);
	
	wp_enqueue_style('byt-admin-css', get_byt_file_uri('/css/admin-custom.css'), false);
}
add_action( 'admin_enqueue_scripts', 'bookyourtravel_admin_scripts_styles' );

/**
 * Add password fields to wordpress registration form if option for users to set their own password is enabled in Theme settings.
 */
add_action( 'register_form', 'bookyourtravel_password_register_fields', 10, 1 );
function bookyourtravel_password_register_fields($includeRow=false){
	$let_users_set_pass = of_get_option('let_users_set_pass', 0);
	if ($includeRow && $let_users_set_pass)
		echo '<div class="row twins">';
		
	if ($let_users_set_pass) {
?>
	<div class="f-item">
		<label for="password"><?php _e('Password', 'bookyourtravel'); ?></label>
		<input id="password" class="input" type="password" tabindex="30" size="25" value="" name="password" />
	</div>
	<div class="f-item">
		<label for="repeat_password"><?php _e('Repeat password', 'bookyourtravel'); ?></label>
		<input id="repeat_password" class="input" type="password" tabindex="40" size="25" value="" name="repeat_password" />
	</div>
<?php
	}
	
	if ($includeRow && $let_users_set_pass)
		echo '</div>';
}

/**
 * Disable WP login if option enabled in Theme settings
 */
function bookyourtravel_disable_wp_login(){
	$override_wp_login = of_get_option('override_wp_login', 0);
	if ($override_wp_login) {
	
		$redirect_to_after_logout_id = get_current_language_page_id(of_get_option('redirect_to_after_logout', ''));
		$redirect_to_after_logout = get_permalink($redirect_to_after_logout_id);
			
		$login_page_url_id = get_current_language_page_id(of_get_option('login_page_url', ''));
		$login_page_url = get_permalink($login_page_url_id);
			
		if (!empty($login_page_url) && !empty($redirect_to_after_logout)) {
			if( isset( $_GET['loggedout'] ) ){
				wp_redirect( $redirect_to_after_logout );
				exit;
			} else{
				wp_redirect( $login_page_url );
				exit;
			}
		}
	}
}
add_action( 'login_form_login', 'bookyourtravel_disable_wp_login' );

function bookyourtravel_get_days_of_week() {

 	$days_of_week = array();
	$days_of_week[0] = __('Monday', 'bookyourtravel');
	$days_of_week[1] = __('Tuesday', 'bookyourtravel');
	$days_of_week[2] = __('Wednesday', 'bookyourtravel');
	$days_of_week[3] = __('Thursday', 'bookyourtravel');
	$days_of_week[4] = __('Friday', 'bookyourtravel');
	$days_of_week[5] = __('Saturday', 'bookyourtravel');
	$days_of_week[6] = __('Sunday', 'bookyourtravel'); 
	
	return $days_of_week;
}

/*
 * Override optionsframework sanitization for 'textarea' sanitization and $allowedposttags + embed and script.
 */
add_action('admin_init','optionscheck_change_santiziation', 100);
function optionscheck_change_santiziation() {
    remove_filter( 'of_sanitize_textarea', 'of_sanitize_textarea' );
    add_filter( 'of_sanitize_textarea', 'bookyourtravel_sanitize_textarea' );
}
function bookyourtravel_sanitize_textarea($input) {
    global $allowedposttags;
	$custom_allowedtags["iframe"] = array();	
    $custom_allowedtags["script"] = array();
    $custom_allowedtags = array_merge($custom_allowedtags, $allowedposttags);
    $output = wp_kses( $input, $custom_allowedtags);
    return $output;
}

function bookyourtravel_tour_type_add_new_meta_fields() {
	// this will add the custom meta fields to the add new term page	
 	$days_of_week = bookyourtravel_get_days_of_week();
?>
	<div class="form-field">
		<label for="term_meta[tour_type_is_repeated]"><?php _e( 'Is tour repeated?', 'bookyourtravel' ); ?></label>
		<select id="term_meta[tour_type_is_repeated]" name="term_meta[tour_type_is_repeated]" onchange="isTourTypeRepeatedChanged('block')">
			<option value="0"><?php _e('No', 'bookyourtravel') ?></option>
			<option value="1"><?php _e('Daily', 'bookyourtravel') ?></option>
			<option value="2"><?php _e('Weekdays', 'bookyourtravel') ?></option>
			<option value="3"><?php _e('Weekly', 'bookyourtravel') ?></option>
		</select>
		<p class="description"><?php _e( 'Do tours belonging to this tour type repeat on a daily or weekly basis?','bookyourtravel' ); ?></p>
	</div>
	<div id="tr_tour_type_day_of_week" class="form-field" style="display:none">
		<label for="term_meta[tour_type_day_of_week]"><?php _e( 'Start day (if weekly)', 'bookyourtravel' ); ?></label>
		<select id="term_meta[tour_type_day_of_week]" name="term_meta[tour_type_day_of_week]">
		  <?php 
			for ($i=0; $i<count($days_of_week); $i++) { 
				$day_of_week = $days_of_week[$i]; ?>
		  <option value="<?php echo $i; ?>"><?php echo $day_of_week; ?></option>
		  <?php } ?>
		</select>		
		<p class="description"><?php _e( 'Select a start day of the week for weekly tour','bookyourtravel' ); ?></p>
	</div>
<?php
}

function bookyourtravel_tour_type_edit_meta_fields($term) {
 
 	$days_of_week = bookyourtravel_get_days_of_week();
 
	// put the term ID into a variable
	$t_id = $term->term_id;
 
	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option( "taxonomy_$t_id" ); ?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[tour_type_is_repeated]"><?php _e( 'Is tour repeated?', 'bookyourtravel' ); ?></label></th>
		<td>
			<select id="term_meta[tour_type_is_repeated]" name="term_meta[tour_type_is_repeated]" onchange="isTourTypeRepeatedChanged('table-row')">
				<option <?php echo (int) $term_meta['tour_type_is_repeated'] == 0 ? 'selected' : '' ?> value="0"><?php _e('No', 'bookyourtravel') ?></option>
				<option <?php echo (int) $term_meta['tour_type_is_repeated'] == 1 ? 'selected' : '' ?> value="1"><?php _e('Daily', 'bookyourtravel') ?></option>
				<option <?php echo (int) $term_meta['tour_type_is_repeated'] == 2 ? 'selected' : '' ?> value="2"><?php _e('Weekdays', 'bookyourtravel') ?></option>
				<option <?php echo (int) $term_meta['tour_type_is_repeated'] == 3 ? 'selected' : '' ?> value="3"><?php _e('Weekly', 'bookyourtravel') ?></option>
			</select>
			<p class="description"><?php _e( 'Do tours belonging to this tour type repeat on a daily or weekly basis?','bookyourtravel' ); ?></p>
		</td>
	</tr>
	<tr id="tr_tour_type_day_of_week" class="form-field" style="<?php echo (int)$term_meta['tour_type_is_repeated'] < 3 ? 'display:none' : ''; ?>">
		<th scope="row" valign="top"><label for="term_meta[tour_type_day_of_week]"><?php _e( 'Start day (if weekly)', 'bookyourtravel' ); ?></label></th>
		<td>
			<select id="term_meta[tour_type_day_of_week]" name="term_meta[tour_type_day_of_week]">
			  <?php 
				for ($i=0; $i<count($days_of_week); $i++) { 
					$day_of_week = $days_of_week[$i]; ?>
			  <option <?php echo (int)$term_meta['tour_type_day_of_week'] == $i ? 'selected' : '' ?> value="<?php echo $i; ?>"><?php echo $day_of_week; ?></option>
			  <?php } ?>
			</select>	
			<p class="description"><?php _e( 'Select a start day of the week for weekly tour','bookyourtravel' ); ?></p>
		</td>
	</tr>
<?php
}

function bookyourtravel_save_tour_type_custom_meta( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		// Save the option array.
		update_option( "taxonomy_$t_id", $term_meta );
	}
}  
add_action( 'edited_tour_type', 'bookyourtravel_save_tour_type_custom_meta', 10, 2 );  
add_action( 'create_tour_type', 'bookyourtravel_save_tour_type_custom_meta', 10, 2 );

$enable_tours = of_get_option('enable_tours', 1);
if ($enable_tours) {
	add_action( 'tour_type_add_form_fields', 'bookyourtravel_tour_type_add_new_meta_fields', 10, 2 );
	add_action( 'tour_type_edit_form_fields', 'bookyourtravel_tour_type_edit_meta_fields', 10, 2 );
}


function bookyourtravel_cruise_type_add_new_meta_fields() {
	// this will add the custom meta fields to the add new term page	
 	$days_of_week = bookyourtravel_get_days_of_week();
?>
	<div class="form-field">
		<label for="term_meta[cruise_type_is_repeated]"><?php _e( 'Is cruise repeated?', 'bookyourtravel' ); ?></label>
		<select id="term_meta[cruise_type_is_repeated]" name="term_meta[cruise_type_is_repeated]" onchange="isCruiseTypeRepeatedChanged('block')">
			<option value="0"><?php _e('No', 'bookyourtravel') ?></option>
			<option value="1"><?php _e('Daily', 'bookyourtravel') ?></option>
			<option value="2"><?php _e('Weekdays', 'bookyourtravel') ?></option>
			<option value="3"><?php _e('Weekly', 'bookyourtravel') ?></option>
		</select>
		<p class="description"><?php _e( 'Do cruises belonging to this cruise type repeat on a daily, weekly, weekday or monthly basis?','bookyourtravel' ); ?></p>
	</div>
	<div id="tr_cruise_type_day_of_week" class="form-field" style="display:none">
		<label for="term_meta[cruise_type_day_of_week]"><?php _e( 'Start day (if weekly or monthly)', 'bookyourtravel' ); ?></label>
		<select id="term_meta[cruise_type_day_of_week]" name="term_meta[cruise_type_day_of_week]">
		  <?php 
			for ($i=0; $i<count($days_of_week); $i++) { 
				$day_of_week = $days_of_week[$i]; ?>
		  <option value="<?php echo $i; ?>"><?php echo $day_of_week; ?></option>
		  <?php } ?>
		</select>		
		<p class="description"><?php _e( 'Select a start day of the week for weekly cruise','bookyourtravel' ); ?></p>
	</div>
<?php
}

function bookyourtravel_cruise_type_edit_meta_fields($term) {
 
 	$days_of_week = bookyourtravel_get_days_of_week();
 
	// put the term ID into a variable
	$t_id = $term->term_id;
 
	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option( "taxonomy_$t_id" ); ?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[cruise_type_is_repeated]"><?php _e( 'Is cruise repeated?', 'bookyourtravel' ); ?></label></th>
		<td>
			<select id="term_meta[cruise_type_is_repeated]" name="term_meta[cruise_type_is_repeated]" onchange="isCruiseTypeRepeatedChanged('table-row')">
				<option <?php echo (int) $term_meta['cruise_type_is_repeated'] == 0 ? 'selected' : '' ?> value="0"><?php _e('No', 'bookyourtravel') ?></option>
				<option <?php echo (int) $term_meta['cruise_type_is_repeated'] == 1 ? 'selected' : '' ?> value="1"><?php _e('Daily', 'bookyourtravel') ?></option>
				<option <?php echo (int) $term_meta['cruise_type_is_repeated'] == 2 ? 'selected' : '' ?> value="2"><?php _e('Weekdays', 'bookyourtravel') ?></option>
				<option <?php echo (int) $term_meta['cruise_type_is_repeated'] == 3 ? 'selected' : '' ?> value="3"><?php _e('Weekly', 'bookyourtravel') ?></option>
			</select>
			<p class="description"><?php _e( 'Do cruises belonging to this cruise type repeat on a daily or weekly basis?','bookyourtravel' ); ?></p>
		</td>
	</tr>
	<tr id="tr_cruise_type_day_of_week" class="form-field" style="<?php echo (int)$term_meta['cruise_type_is_repeated'] < 3 ? 'display:none' : ''; ?>">
		<th scope="row" valign="top"><label for="term_meta[cruise_type_day_of_week]"><?php _e( 'Start day (if weekly)', 'bookyourtravel' ); ?></label></th>
		<td>
			<select id="term_meta[cruise_type_day_of_week]" name="term_meta[cruise_type_day_of_week]">
			  <?php 
				for ($i=0; $i<count($days_of_week); $i++) { 
					$day_of_week = $days_of_week[$i]; ?>
			  <option <?php echo (int)$term_meta['cruise_type_day_of_week'] == $i ? 'selected' : '' ?> value="<?php echo $i; ?>"><?php echo $day_of_week; ?></option>
			  <?php } ?>
			</select>	
			<p class="description"><?php _e( 'Select a start day of the week for weekly cruise','bookyourtravel' ); ?></p>
		</td>
	</tr>
<?php
}

function bookyourtravel_save_cruise_type_custom_meta( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		// Save the option array.
		update_option( "taxonomy_$t_id", $term_meta );
	}
}  
add_action( 'edited_cruise_type', 'bookyourtravel_save_cruise_type_custom_meta', 10, 2 );  
add_action( 'create_cruise_type', 'bookyourtravel_save_cruise_type_custom_meta', 10, 2 );

$enable_cruises = of_get_option('enable_cruises', 1);
if ($enable_cruises) {
	add_action( 'cruise_type_add_form_fields', 'bookyourtravel_cruise_type_add_new_meta_fields', 10, 2 );
	add_action( 'cruise_type_edit_form_fields', 'bookyourtravel_cruise_type_edit_meta_fields', 10, 2 );
}