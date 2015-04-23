<?php
/**
 * Book Your Travel functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
 
/* 
 * Loads the Options Panel
 *
 * If you're loading from a child theme use stylesheet_directory
 * instead of template_directory
 */
 
if ( ! defined( 'BOOKYOURTRAVEL_VERSION' ) )
    define( 'BOOKYOURTRAVEL_VERSION', '5.32' );

if ( ! defined( 'BOOKYOURTRAVEL_FRONTEND_SUBMIT_ROLE' ) )
    define( 'BOOKYOURTRAVEL_FRONTEND_SUBMIT_ROLE', 'byt_frontend_contributor' );
	
if ( ! defined( 'BOOKYOURTRAVEL_WOOCOMMERCE_SETUP_COMPLETE' ) )
    define( 'BOOKYOURTRAVEL_WOOCOMMERCE_SETUP_COMPLETE', 'byt_woocommerce_setup_complete' );
	   
if ( ! defined( 'BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE' ) )
    define( 'BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE', $wpdb->prefix . 'byt_accommodation_vacancies' );

if ( ! defined( 'BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE' ) )
    define( 'BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE', $wpdb->prefix . 'byt_accommodation_bookings' );	

if ( ! defined( 'BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE' ) )
    define( 'BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE', $wpdb->prefix . 'byt_car_rental_bookings' );	
	
if ( ! defined( 'BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE' ) )
    define( 'BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE', $wpdb->prefix . 'byt_car_rental_booking_days' );
	
if ( ! defined( 'BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE' ) )
    define( 'BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE', $wpdb->prefix . 'byt_tour_schedule' );
	
if ( ! defined( 'BOOKYOURTRAVEL_TOUR_BOOKING_TABLE' ) )
    define( 'BOOKYOURTRAVEL_TOUR_BOOKING_TABLE', $wpdb->prefix . 'byt_tour_booking' );

if ( ! defined( 'BOOKYOURTRAVEL_CURRENCIES_TABLE' ) )
    define( 'BOOKYOURTRAVEL_CURRENCIES_TABLE', $wpdb->prefix . 'byt_currencies' );
	
if ( ! defined( 'BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE' ) )
    define( 'BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE', $wpdb->prefix . 'byt_cruise_schedule' );

if ( ! defined( 'BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE' ) )
    define( 'BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE', $wpdb->prefix . 'byt_cruise_booking' );
	
require_once dirname( __FILE__ ) . '/plugins/urlify/URLify.php';

require_once dirname( __FILE__ ) . '/includes/theme_utils.php';
	
global $currencies, $wpdb, $byt_multi_language_count;

$table_name = BOOKYOURTRAVEL_CURRENCIES_TABLE;
if (table_exists($table_name)) {
	$currencies = $wpdb->get_results("SELECT * FROM $table_name ORDER BY currency_label");
} else {
	bookyourtravel_create_currencies_tables(0);
	$currencies = $wpdb->get_results("SELECT * FROM $table_name ORDER BY currency_label");
}

$byt_multi_language_count = 1;
global $sitepress;
if ($sitepress) {
	$active_languages = $sitepress->get_active_languages();
	$sitepress_settings = $sitepress->get_settings();
	$hidden_languages = array();
	if (isset($sitepress_settings['hidden_languages'])) 
		$hidden_languages = $sitepress_settings['hidden_languages'];
	$byt_multi_language_count = count($active_languages) + count($hidden_languages);
}

$installed_version = get_option('bookyourtravel_version', 0);

if ($installed_version < 4.3) {
	global $wpdb;
	$sql = "UPDATE $wpdb->options 
			SET option_name='optionsframework' 
			WHERE option_name='byt_options'";
	$wpdb->query($sql);
}	

require_once get_byt_file_path('/includes/theme_of_default_fields.php');

if ( !function_exists( 'optionsframework_init' ) ) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/framework/' );
	require_once dirname( __FILE__ ) . '/framework/options-framework.php';
} 

/*-----------------------------------------------------------------------------------*/
/*	Load Options Framework custom fields
/*-----------------------------------------------------------------------------------*/
require_once dirname( __FILE__ ) . '/includes/theme_of_custom.php';

/*-----------------------------------------------------------------------------------*/
/*	Load Actions & Filters
/*-----------------------------------------------------------------------------------*/
require_once get_byt_file_path('/includes/theme_filters.php');
require_once get_byt_file_path('/includes/theme_actions.php');

require_once get_byt_file_path('/plugins/frontend-submit/frontend-submit.php');

/*-----------------------------------------------------------------------------------*/
/*	Load Object Classes
/*-----------------------------------------------------------------------------------*/
require_once get_byt_file_path('/includes/classes/abstracts/byt-entity.php');
require_once get_byt_file_path('/includes/classes/location.class.php');
require_once get_byt_file_path('/includes/classes/car_rental.class.php');
require_once get_byt_file_path('/includes/classes/tour.class.php');
require_once get_byt_file_path('/includes/classes/room_type.class.php');
require_once get_byt_file_path('/includes/classes/accommodation.class.php');
require_once get_byt_file_path('/includes/classes/cruise.class.php');
require_once get_byt_file_path('/includes/classes/cabin_type.class.php');
 
/*-----------------------------------------------------------------------------------*/
/*	Load Widgets, Shortcodes, Metaboxes & Plugins
/*-----------------------------------------------------------------------------------*/
require_once get_byt_file_path('/plugins/widgets/widget-search.php');
require_once get_byt_file_path('/plugins/widgets/widget-address.php');
require_once get_byt_file_path('/plugins/widgets/widget-social.php');
require_once get_byt_file_path('/plugins/widgets/widget-home-feature.php');
require_once get_byt_file_path('/plugins/metaboxes/meta_box.php');
require_once get_byt_file_path('/plugins/class-tgm-plugin-activation.php');

add_action( 'tgmpa_register', 'bookyourtravel_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function bookyourtravel_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        // This is an example of how to include a plugin pre-packaged with a theme.
        array(
            'name'               => 'Revolution slider', // The plugin name.
            'slug'               => 'revslider', // The plugin slug (typically the folder name).
            'source'             => get_byt_file_path('/plugins/revslider/revslider.zip'), // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '4.5.95', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        ),

    );

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => false,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', 'bookyourtravel' ),
            'menu_title'                      => __( 'Install Plugins', 'bookyourtravel' ),
            'installing'                      => __( 'Installing Plugin: %s', 'bookyourtravel' ), // %s = plugin name.
            'oops'                            => __( 'Something went wrong with the plugin API.', 'bookyourtravel' ),
            'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'bookyourtravel' ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'bookyourtravel' ), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'bookyourtravel' ), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'bookyourtravel' ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'bookyourtravel' ), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'bookyourtravel' ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'bookyourtravel' ), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'bookyourtravel' ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'bookyourtravel' ),
            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'bookyourtravel' ),
            'return'                          => __( 'Return to Required Plugins Installer', 'bookyourtravel' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'bookyourtravel' ),
            'complete'                        => __( 'All plugins installed and activated successfully. %s', 'bookyourtravel' ), // %s = dashboard link.
            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        )
    );

    tgmpa( $plugins, $config );

}

/*-----------------------------------------------------------------------------------*/
/*	Load Utilities & Ajax & Custom Post Types & metaboxes
/*-----------------------------------------------------------------------------------*/
require_once get_byt_file_path('/includes/theme_ajax.php');
require_once get_byt_file_path('/includes/theme_post_types.php');
require_once get_byt_file_path('/includes/theme_meta_boxes.php');
require_once get_byt_file_path('/includes/admin/theme_accommodation_vacancy_admin.php');
require_once get_byt_file_path('/includes/admin/theme_currency_admin.php');
require_once get_byt_file_path('/includes/admin/theme_accommodation_booking_admin.php');
require_once get_byt_file_path('/includes/admin/theme_car_rental_booking_admin.php');
require_once get_byt_file_path('/includes/admin/theme_tour_schedule_admin.php');
require_once get_byt_file_path('/includes/admin/theme_tour_schedule_booking_admin.php');
require_once get_byt_file_path('/includes/admin/theme_cruise_schedule_admin.php');
require_once get_byt_file_path('/includes/admin/theme_cruise_schedule_booking_admin.php');
require_once get_byt_file_path('/includes/theme_woocommerce.php');

?>