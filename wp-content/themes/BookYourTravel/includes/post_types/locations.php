<?php

global $wpdb, $byt_multi_language_count;

function bookyourtravel_register_location_post_type() {
	
	$locations_permalink_slug = of_get_option('locations_permalink_slug', 'locations');
	
	$labels = array(
		'name'                => _x( 'Locations', 'Post Type General Name', 'bookyourtravel' ),
		'singular_name'       => _x( 'Location', 'Post Type Singular Name', 'bookyourtravel' ),
		'menu_name'           => __( 'Locations', 'bookyourtravel' ),
		'all_items'           => __( 'All Locations', 'bookyourtravel' ),
		'view_item'           => __( 'View Location', 'bookyourtravel' ),
		'add_new_item'        => __( 'Add New Location', 'bookyourtravel' ),
		'add_new'             => __( 'New Location', 'bookyourtravel' ),
		'edit_item'           => __( 'Edit Location', 'bookyourtravel' ),
		'update_item'         => __( 'Update Location', 'bookyourtravel' ),
		'search_items'        => __( 'Search locations', 'bookyourtravel' ),
		'not_found'           => __( 'No locations found', 'bookyourtravel' ),
		'not_found_in_trash'  => __( 'No locations found in Trash', 'bookyourtravel' ),
	);
	$args = array(
		'label'               => __( 'location', 'bookyourtravel' ),
		'description'         => __( 'Location information pages', 'bookyourtravel' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'author', 'page-attributes' ),
		'taxonomies'          => array( ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
		'rewrite' => array('slug' => $locations_permalink_slug)
	);
	register_post_type( 'location', $args );
	
}
	
function list_locations($location_id = 0, $paged = 0, $per_page = -1, $orderby = '', $order = '', $featured_only = false) {

	$location_ids = array();
	
	if ($location_id > 0) {
		$location_ids[] = $location_id;
		$location_descendants = byt_get_post_descendants($location_id, 'location');
		foreach ($location_descendants as $location) {
			$location_ids[] = $location->ID;
		}
	}
	
	$args = array(
		'post_type'         => 'location',
		'post_status'       => array('publish'),
		'posts_per_page'    => $per_page,
		'paged' 			=> $paged, 
		'orderby'           => $orderby,
		'suppress_filters' 	=> false,
		'order'				=> $order,
		'meta_query'        => array('relation' => 'AND')
	);
		
	if (count($location_ids) > 0) {
		$args['meta_query'][] = array(
			'key'       => 'location_location_post_id',
			'value'     => $location_ids,
			'compare'   => 'IN'
		);
	}
	
	if (isset($featured_only) && $featured_only) {
		$args['meta_query'][] = array(
			'key'       => 'location_is_featured',
			'value'     => 1,
			'compare'   => '=',
			'type' => 'numeric'
		);
	}

	$posts_query = new WP_Query($args);	
	$locations = array();
		
	if ($posts_query->have_posts() ) {
		while ( $posts_query->have_posts() ) {
			global $post;
			$posts_query->the_post(); 
			$locations[] = $post;
		}
	}
	
	$results = array(
		'total' => $posts_query->found_posts,
		'results' => $locations
	);
	
	wp_reset_postdata();
	
	return $results;
}
