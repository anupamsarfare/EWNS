<?php
global $wpdb;

/*-----------------------------------------------------------------------------------*/
/*	Load Post Type Files
/*-----------------------------------------------------------------------------------*/
require_once get_byt_file_path( '/includes/post_types/search.php' );
require_once get_byt_file_path( '/includes/post_types/locations.php' );
require_once get_byt_file_path( '/includes/post_types/reviews.php' );
require_once get_byt_file_path( '/includes/post_types/car_rentals.php' );
require_once get_byt_file_path( '/includes/post_types/accommodations.php' );
require_once get_byt_file_path( '/includes/post_types/tours.php' );
require_once get_byt_file_path( '/includes/post_types/cruises.php' );

function custom_posts_per_page( $query ) { 
	$accommodations_archive_posts_per_page = of_get_option('accommodations_archive_posts_per_page', 12);
	$locations_archive_posts_per_page = of_get_option('locations_archive_posts_per_page', 12);
	$tours_archive_posts_per_page = of_get_option('tours_archive_posts_per_page', 12);
	$car_rentals_archive_posts_per_page = of_get_option('car_rentals_archive_posts_per_page', 12);
	$cruises_archive_posts_per_page = of_get_option('cruises_archive_posts_per_page', 12);
	
    if ( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'accommodation' && is_page() && !is_home() && !is_front_page() ) 
		$query->query_vars['posts_per_page'] = $accommodations_archive_posts_per_page;  
	else if ( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'location' && is_post_type_archive('location') && !is_home() && !is_front_page() ) 
		$query->query_vars['posts_per_page'] = $locations_archive_posts_per_page;  
	else if ( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'tour' && is_page() && !is_home() && !is_front_page() ) 
		$query->query_vars['posts_per_page'] = $tours_archive_posts_per_page;  
	else if ( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'car_rental' && is_page() && !is_home() && !is_front_page()) 
		$query->query_vars['posts_per_page'] = $car_rentals_archive_posts_per_page;  
	else if ( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'cruise' && is_page() && !is_home() && !is_front_page() ) 
		$query->query_vars['posts_per_page'] = $cruises_archive_posts_per_page;  
		
    return $query;  
}  
if ( !is_admin() ) 
	add_filter( 'pre_get_posts', 'custom_posts_per_page' ); 

function remove_unnecessary_meta_boxes() {
    remove_meta_box('tagsdiv-facility', 'accommodation', 'side');
	remove_meta_box('tagsdiv-facility', 'room_type', 'side');
    remove_meta_box('tagsdiv-accommodation_type', 'accommodation', 'side');
    remove_meta_box('tagsdiv-car_type', 'car_rental', 'side');
    remove_meta_box('tagsdiv-tour_type', 'tour', 'side');
    remove_meta_box('tagsdiv-facility', 'cruise', 'side');
	remove_meta_box('tagsdiv-facility', 'cabin_type', 'side');
    remove_meta_box('tagsdiv-cruise_type', 'cruise', 'side');
}

add_action( 'manage_posts_custom_column', 'populate_columns' );
function populate_columns( $column ) {

	$enable_accommodations = of_get_option('enable_accommodations', 1);
	$enable_reviews = of_get_option('enable_reviews', 1);
	$enable_car_rentals = of_get_option('enable_car_rentals', 1);

    if ( 'location_country' == $column ) {
        $location_country = get_post_meta( get_the_ID(), 'location_country', true ) ;
		echo $location_country;		
    } elseif ( 'accommodation_location_post_id' == $column && $enable_accommodations ) {
        $location_post_id = get_post_meta( get_the_ID(), 'accommodation_location_post_id', true );
		$location = get_post($location_post_id);
		if ($location)
			echo $location->post_title;
    } elseif ( 'car_rental_location_post_id' == $column && $enable_car_rentals) {
        $location_post_id = get_post_meta( get_the_ID(), 'car_rental_location_post_id', true );
		$location = get_post($location_post_id);
		if ($location)
			echo $location->post_title;
	} elseif ( 'review_post_id' == $column && $enable_reviews) {
		$review_post_id = get_post_meta( get_the_ID(), 'review_post_id', true );
		$reviewed_post = get_post($review_post_id);
		if ($reviewed_post)
			echo $reviewed_post->post_title;
	}
}

function initialize_post_types() {

	global $wpdb;

	$enable_accommodations = of_get_option('enable_accommodations', 1);
	$enable_car_rentals = of_get_option('enable_car_rentals', 1);
	$enable_tours = of_get_option('enable_tours', 1);
	$enable_cruises = of_get_option('enable_cruises', 1);
	$enable_reviews = of_get_option('enable_reviews', 1);
	
	$installed_version = get_option('bookyourtravel_version', 0);

	if ($installed_version == 0)
		add_option("bookyourtravel_version", BOOKYOURTRAVEL_VERSION);
	else
		update_option("bookyourtravel_version", BOOKYOURTRAVEL_VERSION);

	bookyourtravel_register_location_post_type();
	
	if ($enable_reviews) {
		bookyourtravel_register_review_post_type();
	}
	
	bookyourtravel_create_currencies_tables($installed_version);
	
	if ($enable_tours) {
		bookyourtravel_register_tour_post_type();
		bookyourtravel_register_tour_type_taxonomy();
		bookyourtravel_create_tour_extra_tables($installed_version);
	}
	
	if ($enable_cruises) {
		bookyourtravel_register_cruise_post_type();
		bookyourtravel_register_cruise_type_taxonomy();
		bookyourtravel_register_cabin_type_post_type();
		bookyourtravel_register_facility_taxonomy();
		bookyourtravel_create_cruise_extra_tables($installed_version);
	}
	
	if ($enable_car_rentals) {
		bookyourtravel_register_car_type_taxonomy();
		bookyourtravel_register_car_rental_post_type();
		bookyourtravel_create_car_rental_extra_tables($installed_version);
	}
		
	if ($enable_accommodations) {
		bookyourtravel_register_accommodation_post_type();
		bookyourtravel_register_room_type_post_type();
		bookyourtravel_register_facility_taxonomy();
		bookyourtravel_register_accommodation_type_taxonomy();
		bookyourtravel_create_accommodation_extra_tables($installed_version);
	}
}

add_action('init','initialize_post_types');
add_action('admin_init','remove_unnecessary_meta_boxes');

add_action( 'before_delete_post', 'byt_handle_post_delete' );
function byt_handle_post_delete( $post_id ){

	global $wpdb;

    $post = get_post($post_id);
    if ($post->post_type == 'review') {
        
		$reviewed_post_id = get_post_meta($post_id, 'review_post_id', true);
		$review_post = get_post($reviewed_post_id);
		
		$old_review_score = floatval(get_post_meta($reviewed_post_id, 'review_score', true));
		$old_review_score = $old_review_score ? $old_review_score : 0;

		$old_review_sum_score = floatval(get_post_meta($reviewed_post_id, 'review_sum_score', true));
		$old_review_sum_score = $old_review_sum_score ? $old_review_sum_score : 0;

		$old_review_count = intval(get_post_meta($reviewed_post_id, 'review_count', true));
		$old_review_count = $old_review_count ? $old_review_count : 0;					
		
		$new_review_count = $old_review_count - 1;
		$new_review_count = $new_review_count < 0 ? 0 : $new_review_count;
		
		$new_score_sum = 0;
		$review_fields = list_review_fields($review_post->post_type);
		foreach ($review_fields as $field) {
			$field_id = $field['id'];
			$field_value = get_post_meta($post_id, $field_id, true);
			$new_score_sum += intval($field_value);
		}
			
		$new_score_sum = $old_review_sum_score - $new_score_sum;
		$new_score_sum = $new_score_sum > 0 ? $new_score_sum : 0;
		
		if ($new_review_count > 0) {
			$new_review_score = $new_score_sum / (count($review_fields) * 10);
			$new_review_score = ($old_review_score - $new_review_score) / $new_review_count;
		} else
			$new_review_score = 0;
					
		update_post_meta($reviewed_post_id, 'review_sum_score', $new_score_sum);
		update_post_meta($reviewed_post_id, 'review_score', $new_review_score);					
		update_post_meta($reviewed_post_id, 'review_count', $new_review_count);	
    }
}

add_filter( 'request', 'post_columns_orderby' ); 
function post_columns_orderby ( $vars ) {
    if ( !is_admin() )
        return $vars;
    if ( isset( $vars['orderby'] ) && 'location_country' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array( 'meta_key' => 'location_country', 'orderby' => 'meta_value' ) );
    }
    return $vars;
}

function get_dates_from_range($start, $end){
	$dates = array($start);
	while(end($dates) < $end){
		$dates[] = date('Y-m-d', strtotime(end($dates).' +1 day'));
	}
	return $dates;
}

function bookyourtravel_register_facility_taxonomy(){
	$labels = array(
			'name'              => _x( 'Facilities', 'taxonomy general name', 'bookyourtravel' ),
			'singular_name'     => _x( 'Facility', 'taxonomy singular name', 'bookyourtravel' ),
			'search_items'      => __( 'Search Facilities', 'bookyourtravel' ),
			'all_items'         => __( 'All Facilities', 'bookyourtravel' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'         => __( 'Edit Facility', 'bookyourtravel' ),
			'update_item'       => __( 'Update Facility', 'bookyourtravel' ),
			'add_new_item'      => __( 'Add New Facility', 'bookyourtravel' ),
			'new_item_name'     => __( 'New Facility Name', 'bookyourtravel' ),
			'separate_items_with_commas' => __( 'Separate facilities with commas', 'bookyourtravel' ),
			'add_or_remove_items'        => __( 'Add or remove facilities', 'bookyourtravel' ),
			'choose_from_most_used'      => __( 'Choose from the most used facilities', 'bookyourtravel' ),
			'not_found'                  => __( 'No facilities found.', 'bookyourtravel' ),
			'menu_name'         => __( 'Facilities', 'bookyourtravel' ),
		);
		
	$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'update_count_callback' => '_update_post_term_count',
			'rewrite'           => array( 'slug' => 'facility' ),
		);
	
	$enable_accommodations = of_get_option('enable_accommodations', 1);
	$enable_cruises = of_get_option('enable_cruises', 1);

	$types_for_facility = array();
	if ($enable_accommodations) {
		$types_for_facility[] = 'accommodation';
		$types_for_facility[] = 'room_type';
	}
	if ($enable_cruises) {
		$types_for_facility[] = 'cruise';
		$types_for_facility[] = 'cabin_type';
	}
		
	if (count($types_for_facility) > 0)
		register_taxonomy( 'facility', $types_for_facility, $args );
}