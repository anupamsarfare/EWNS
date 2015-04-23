<?php

function bookyourtravel_register_accommodation_post_type() {
	
	$accommodations_permalink_slug = of_get_option('accommodations_permalink_slug', 'hotels');
	$slug = _x( $accommodations_permalink_slug, 'URL slug2', 'bookyourtravel' );
		
	$labels = array(
		'name'                => _x( 'Accommodations', 'Post Type General Name', 'bookyourtravel' ),
		'singular_name'       => _x( 'Accommodation', 'Post Type Singular Name', 'bookyourtravel' ),
		'menu_name'           => __( 'Accommodations', 'bookyourtravel' ),
		'all_items'           => __( 'All Accommodations', 'bookyourtravel' ),
		'view_item'           => __( 'View Accommodation', 'bookyourtravel' ),
		'add_new_item'        => __( 'Add New Accommodation', 'bookyourtravel' ),
		'add_new'             => __( 'New Accommodation', 'bookyourtravel' ),
		'edit_item'           => __( 'Edit Accommodation', 'bookyourtravel' ),
		'update_item'         => __( 'Update Accommodation', 'bookyourtravel' ),
		'search_items'        => __( 'Search Accommodations', 'bookyourtravel' ),
		'not_found'           => __( 'No Accommodations found', 'bookyourtravel' ),
		'not_found_in_trash'  => __( 'No Accommodations found in Trash', 'bookyourtravel' ),
	);
	$args = array(
		'label'               => __( 'accommodation', 'bookyourtravel' ),
		'description'         => __( 'Accommodation information pages', 'bookyourtravel' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'author' ),
		'taxonomies'          => array( ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
 		'rewrite' => array('slug' => $slug),
	);
	register_post_type( 'accommodation', $args );	
}

function bookyourtravel_register_room_type_post_type() {
	
	$labels = array(
		'name'                => _x( 'Room types', 'Post Type General Name', 'bookyourtravel' ),
		'singular_name'       => _x( 'Room type', 'Post Type Singular Name', 'bookyourtravel' ),
		'menu_name'           => __( 'Room types', 'bookyourtravel' ),
		'all_items'           => __( 'Room types', 'bookyourtravel' ),
		'view_item'           => __( 'View Room type', 'bookyourtravel' ),
		'add_new_item'        => __( 'Add New Room type', 'bookyourtravel' ),
		'add_new'             => __( 'New Room type', 'bookyourtravel' ),
		'edit_item'           => __( 'Edit Room type', 'bookyourtravel' ),
		'update_item'         => __( 'Update Room type', 'bookyourtravel' ),
		'search_items'        => __( 'Search room_types', 'bookyourtravel' ),
		'not_found'           => __( 'No room types found', 'bookyourtravel' ),
		'not_found_in_trash'  => __( 'No room types found in Trash', 'bookyourtravel' ),
	);
	$args = array(
		'label'               => __( 'room type', 'bookyourtravel' ),
		'description'         => __( 'Room type information pages', 'bookyourtravel' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'author' ),
		'taxonomies'          => array( ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => 'edit.php?post_type=accommodation',
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'page',
		'rewrite' => false,
	);
	register_post_type( 'room_type', $args );	
}

function bookyourtravel_create_accommodation_extra_tables($installed_version) {

	if ($installed_version != BOOKYOURTRAVEL_VERSION) {
	
		global $wpdb;
		
		$table_name = BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE;
		$sql = "CREATE TABLE " . $table_name . " (
					Id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					start_date datetime NOT NULL,
					end_date datetime NOT NULL,
					accommodation_id bigint(20) unsigned NOT NULL,
					room_type_id bigint(20) unsigned NOT NULL DEFAULT '0',
					room_count int(11) NOT NULL,
					price_per_day decimal(16,2) NOT NULL,
					price_per_day_child decimal(16,2) NOT NULL,
					PRIMARY KEY  (Id)
				);";

		// we do not execute sql directly
		// we are calling dbDelta which cant migrate database
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		
		global $EZSQL_ERROR;
		$EZSQL_ERROR = array();
		
		$table_name = BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE;
		$sql = "CREATE TABLE " . $table_name . " (
					Id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					first_name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
					last_name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
					email varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
					phone varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					address varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					town varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					zip varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					country varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					special_requirements text CHARACTER SET utf8 COLLATE utf8_bin,
					room_count int(11) NOT NULL DEFAULT '0',
					adults int(11) NOT NULL DEFAULT '0',
					children int(11) NOT NULL DEFAULT '0',
					total_price decimal(16,2) NOT NULL DEFAULT '0.00',
					accommodation_id bigint(20) unsigned NOT NULL,
					room_type_id bigint(20) unsigned NOT NULL,
					date_from datetime NOT NULL,
					date_to datetime NOT NULL,
					user_id bigint(20) unsigned DEFAULT NULL,
					created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					woo_order_id bigint(20) NULL,
					cart_key VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' NOT NULL,
					currency_code VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
					PRIMARY KEY  (Id)
				);";
		dbDelta($sql);
		
		$EZSQL_ERROR = array();
	}
}

function bookyourtravel_register_accommodation_type_taxonomy(){
	$labels = array(
			'name'              => _x( 'Accommodation types', 'taxonomy general name', 'bookyourtravel' ),
			'singular_name'     => _x( 'Accommodation type', 'taxonomy singular name', 'bookyourtravel' ),
			'search_items'      => __( 'Search Accommodation types', 'bookyourtravel' ),
			'all_items'         => __( 'All Accommodation types', 'bookyourtravel' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'         => __( 'Edit Accommodation type', 'bookyourtravel' ),
			'update_item'       => __( 'Update Accommodation type', 'bookyourtravel' ),
			'add_new_item'      => __( 'Add New Accommodation type', 'bookyourtravel' ),
			'new_item_name'     => __( 'New Accommodation type Name', 'bookyourtravel' ),
			'separate_items_with_commas' => __( 'Separate accommodation types with commas', 'bookyourtravel' ),
			'add_or_remove_items'        => __( 'Add or remove accommodation types', 'bookyourtravel' ),
			'choose_from_most_used'      => __( 'Choose from the most used accommodation types', 'bookyourtravel' ),
			'not_found'                  => __( 'No accommodation types found.', 'bookyourtravel' ),
			'menu_name'         => __( 'Accommodation types', 'bookyourtravel' ),
		);
		
	$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'update_count_callback' => '_update_post_term_count',
			'rewrite'           => false,
		);
		
	register_taxonomy( 'accommodation_type', array( 'accommodation' ), $args );
}

function list_room_types( $author_id = null, $statuses = array('publish') ) {

	$args = array(
	   'post_type' => 'room_type',
	   'post_status' => $statuses,
	   'posts_per_page' => -1,
	   'suppress_filters' => 0
	);
	
	if (isset($author_id) && $author_id > 0) {
		$args['author'] = intval($author_id);
	}
	
	$query = new WP_Query($args);

	return $query;
}

function list_accommodations_count ( $paged = 0, $per_page = 0, $orderby = '', $order = '', $location_id = 0, $accommodation_types_array = array(), $search_args = array(), $featured_only = false, $is_self_catered = null, $author_id = null, $include_private = false ) {
	return list_accommodations ($paged, $per_page, $orderby, $order, $location_id, $accommodation_types_array, $search_args, $featured_only, $is_self_catered, $author_id, $include_private, true);
}

function accommodations_search_fields( $fields, &$wp_query ) {

	global $wpdb;

	if ( isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'accommodation' ) {
		
		$date_from = null;
		if ( isset($wp_query->query_vars['byt_date_from']) )
			$date_from = date('Y-m-d', strtotime($wp_query->get('byt_date_from')));
		
		$date_to = null;		
		if ( isset($wp_query->query_vars['byt_date_to']) )
			$date_to = date('Y-m-d', strtotime($wp_query->get('byt_date_to') . ' -1 day'));
		
		if (isset($date_from) && $date_from == $date_to)
			$date_to = date('Y-m-d', strtotime($wp_query->get('byt_date_from') . ' +7 day'));
		
		$search_only_available = false;
		if (isset($wp_query->query_vars['search_only_available']))
			$search_only_available = $wp_query->get('search_only_available');
		
		if ($search_only_available && (isset($date_from) || isset($date_to))) {
		
			$fields .= ", (
							SELECT IFNULL(SUM(room_count), 0) rooms_available FROM " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . "
							WHERE accommodation_id = {$wpdb->posts}.ID ";
							
			if ($date_from != null && $date_to != null) {
				$fields .= $wpdb->prepare(" AND (%s BETWEEN start_date AND end_date OR %s BETWEEN start_date AND end_date) ", $date_from, $date_to);
			} else if ($date_from != null) {
				$fields .= $wpdb->prepare(" AND %s BETWEEN start_date AND end_date ", $date_from);
			} else if ($date_to != null) {
				$fields .= $wpdb->prepare(" AND %s BETWEEN start_date AND end_date ", $date_to);
			}						
			
			$fields .= " ) rooms_available ";
			
			$fields .= ", (
							SELECT IFNULL(SUM(room_count), 0) rooms_booked FROM " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . "
							WHERE accommodation_id = {$wpdb->posts}.ID ";
							
			if ($date_from != null && $date_to != null) {
				$fields .= $wpdb->prepare(" AND (%s BETWEEN date_from AND date_to OR %s BETWEEN date_from AND date_to) ", $date_from, $date_to);
			} else if ($date_from != null) {
				$fields .= $wpdb->prepare(" AND %s BETWEEN date_from AND date_to ", $date_from);
			} else if ($date_to != null) {
				$fields .= $wpdb->prepare(" AND %s BETWEEN date_from AND date_to ", $date_to);
			}						
			
			$fields .= " ) rooms_booked ";
		}
						
	}

	return $fields;
}

function accommodations_search_where( $where, &$wp_query ) {
	
	global $wpdb;
	
	if ( isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'accommodation' ) {
		if ( isset($wp_query->query_vars['byt_is_self_catered']) ) {
			$needed_where_part = '';
			$where_array = explode('AND', $where);
			foreach ($where_array as $where_part) {
				if (strpos($where_part,'post_id IS NULL') !== false) {
					// found where part where is_self_catered is checked for NULL
					$needed_where_part = $where_part;
					break;
				}
			}
			
			if (!empty($needed_where_part)) {
				$prefix = str_replace("post_id IS NULL","",$needed_where_part);
				$prefix = str_replace(")", "", $prefix);
				$prefix = str_replace("(", "", $prefix);
				$prefix = trim($prefix);
				$where = str_replace("{$prefix}post_id IS NULL", "({$prefix}post_id IS NULL OR CAST({$prefix}meta_value AS SIGNED) = '0')", $where);
			}
		}
		if (isset($wp_query->query_vars['s']) && !empty($wp_query->query_vars['s']) && isset($wp_query->query_vars['byt_location_ids']) && isset($wp_query->query_vars['s']) ) {
			$needed_where_part = '';
			$where_array = explode('AND', $where);
			foreach ($where_array as $where_part) {
				if (strpos($where_part,"meta_key = 'accommodation_location_post_id'") !== false) {
					// found where part where is_self_catered is checked for NULL
					$needed_where_part = $where_part;
					break;
				}
			}
			
			if (!empty($needed_where_part)) {
				$prefix = str_replace("meta_key = 'accommodation_location_post_id'","",$needed_where_part);
				$prefix = str_replace(")", "", $prefix);
				$prefix = str_replace("(", "", $prefix);
				$prefix = trim($prefix);

				$location_ids = $wp_query->query_vars['byt_location_ids'];
				$location_ids_str = "'".implode("','", $location_ids)."'";				
				$location_search_param_part = "{$prefix}meta_key = 'accommodation_location_post_id' AND CAST({$prefix}meta_value AS CHAR) IN ($location_ids_str)";							
			
				$where = str_replace($location_search_param_part, "1=1", $where);
				
				$post_content_part = "OR ($wpdb->posts.post_content LIKE '%" . $wp_query->get('s') . "%')";
				$where = str_replace($post_content_part, $post_content_part . " OR ($location_search_param_part) ", $where);
			}
		}
	}
	
	return $where;
}

function accommodations_search_groupby( $groupby, &$wp_query ) {

	global $wpdb;
	
	if ( isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'accommodation' ) {
		
		$date_from = null;
		if ( isset($wp_query->query_vars['byt_date_from']) )
			$date_from = date('Y-m-d', strtotime($wp_query->get('byt_date_from')));
		
		$date_to = null;		
		if ( isset($wp_query->query_vars['byt_date_to']) )
			$date_to = date('Y-m-d', strtotime($wp_query->get('byt_date_to') . ' -1 day'));
		
		if (isset($date_from) && $date_from == $date_to)
			$date_to = date('Y-m-d', strtotime($wp_query->get('byt_date_from') . ' +7 day'));
		
		$search_only_available = false;
		if (isset($wp_query->query_vars['search_only_available']))
			$search_only_available = $wp_query->get('search_only_available');
		
		if ($search_only_available && (isset($date_from) || isset($date_to))) {				
			$groupby .= ' HAVING rooms_available > rooms_booked ';		
		}
	}
	
	return $groupby;
}
	
function list_accommodations ( $paged = 0, $per_page = -1, $orderby = '', $order = '', $location_id = 0, $accommodation_types_array = array(), $search_args = array(), $featured_only = false, $is_self_catered = null, $author_id = null, $include_private = false, $count_only = false ) {

	$location_ids = array();
	
	if ($location_id > 0) {
		$location_ids[] = $location_id;
		$location_descendants = byt_get_post_descendants($location_id, 'location');
		foreach ($location_descendants as $location) {
			$location_ids[] = $location->ID;
		}
	}

	if (isset($search_args['keyword']) && strlen($search_args['keyword']) > 0) {
		$args = array(
			's' => $search_args['keyword'],
			'post_type' => 'location',
			'posts_per_page' => -1, 
			'post_status' => 'publish',
			'suppress_filters' => false
		);
		
		$location_posts = get_posts($args);
		foreach ($location_posts as $location) {
			$location_ids[] = $location->ID;		
		}

		$descendant_location_ids = array();		
		foreach ($location_ids as $temp_location_id) {
			$location_descendants = byt_get_post_descendants($temp_location_id, 'location');
			foreach ($location_descendants as $location) {
				$descendant_location_ids[] = $location->ID;
			}
		}
		
		$location_ids = array_merge($descendant_location_ids,$location_ids);
	}
	
	$args = array(
		'post_type'         => 'accommodation',
		'post_status'       => array('publish'),
		'posts_per_page'    => $per_page,
		'paged'				=> $paged,
		'orderby'           => $orderby,
		'suppress_filters' 	=> false,
		'order'				=> $order
	);
	
	if ($orderby == 'star_count') {
		$args['meta_key'] = 'accommodation_star_count';
		$args['orderby'] = 'meta_value_num';
	} else if ($orderby == 'review_score') {
		$args['meta_key'] = 'review_score';
		$args['orderby'] = 'meta_value_num';
	} else if ($orderby == 'min_price') {
		$args['meta_key'] = '_accommodation_min_price';
		$args['orderby'] = 'meta_value_num';
	}
	
	if (isset($search_args['keyword']) && strlen($search_args['keyword']) > 0) {
		$args['s'] = $search_args['keyword'];
	}
	
	if ($include_private) {
		$args['post_status'][] = 'private';
	}
	
	$meta_query = array('relation' => 'AND');
	
	if ( isset($search_args['stars']) && strlen($search_args['stars']) > 0 ) {
		$stars = intval($search_args['stars']);
		if ($stars > 0 & $stars <=5) {
			$meta_query[] = array(
				'key'       => 'accommodation_star_count',
				'value'     => $stars,
				'compare'   => '>=',
				'type' => 'numeric'
			);
		}
	}
	
	if ( isset($search_args['rating']) && strlen($search_args['rating']) > 0 ) {
		$rating = intval($search_args['rating']);			
		if ($rating > 0 & $rating <=10) {
			$meta_query[] = array(
				'key'       => 'review_score',
				'value'     => $rating,
				'compare'   => '>=',
				'type' => 'numeric'
			);
		}
	}

	if (isset($is_self_catered)) {
		$args['byt_is_self_catered'] = $is_self_catered;
		if ($is_self_catered) {
			$meta_query[] = array(
				'key'       => 'accommodation_is_self_catered',
				'value'     => '1',
				'compare'   => '=',
				'type' => 'numeric'
			);
		} else {
			$meta_query[] = array(
				'key'       => 'accommodation_is_self_catered',
				'compare'   => 'NOT EXISTS'
			);
		}		
	}
	
	if (isset($featured_only) && $featured_only) {
		$meta_query[] = array(
			'key'       => 'accommodation_is_featured',
			'value'     => 1,
			'compare'   => '=',
			'type' => 'numeric'
		);
	}

	if (isset($author_id)) {
		$author_id = intval($author_id);
		if ($author_id > 0) {
			$args['author'] = $author_id;
		}
	}

	if (count($location_ids) > 0) {
		$meta_query[] = array(
			'key'       => 'accommodation_location_post_id',
			'value'     => $location_ids,
			'compare'   => 'IN'
		);
		$args['byt_location_ids'] = $location_ids;
	}
	
	if (!empty($accommodation_types_array)) {
		$args['tax_query'][] = 	array(
				'taxonomy' => 'accommodation_type',
				'field' => 'id',
				'terms' => $accommodation_types_array,
				'operator'=> 'IN'
		);
	}
	
	$search_only_available = false;
	if ( isset($search_args['search_only_available'])) {				
		$search_only_available = $search_args['search_only_available'];
	}

	if ( isset($search_args['date_from']) )
		$args['byt_date_from'] = $search_args['date_from'];
	if ( isset($search_args['date_to']) )
		$args['byt_date_to'] =  $search_args['date_to'];
		
	$args['search_only_available'] = $search_only_available;

	add_filter('posts_where', 'accommodations_search_where', 10, 2 );
	
	if ($search_only_available) {
		add_filter('posts_fields', 'accommodations_search_fields', 10, 2 );
		add_filter('posts_groupby', 'accommodations_search_groupby', 10, 2 );
	}
		
	$args['meta_query'] = $meta_query;
	
	$posts_query = new WP_Query($args);
	
	if ($count_only) {
		$results = array(
			'total' => $posts_query->found_posts,
			'results' => null
		);	
	} else {
		$results = array();
		
		if ($posts_query->have_posts() ) {
			while ( $posts_query->have_posts() ) {
				global $post;
				$posts_query->the_post(); 
				$results[] = $post;
			}
		}
	
		$results = array(
			'total' => $posts_query->found_posts,
			'results' => $results
		);
	}
	
	wp_reset_postdata();

	remove_filter('posts_where', 'accommodations_search_where' );
	
	if ($search_only_available) {
		remove_filter('posts_fields', 'accommodations_search_fields' );
		remove_filter('posts_groupby', 'accommodations_search_groupby');
	}
	
	return $results;
}

function get_accommodation_total_price($accommodation_id, $date_from, $date_to, $room_type_id, $room_count, $adults, $children) {

	global $wpdb;
	
	$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type');

	$accommodation_is_price_per_person = get_post_meta($accommodation_id, 'accommodation_is_price_per_person', true);
	$accommodation_count_children_stay_free = get_post_meta($accommodation_id, 'accommodation_count_children_stay_free', true );
	if (!isset($accommodation_count_children_stay_free))
		$accommodation_count_children_stay_free = 0;
	$accommodation_count_children_stay_free = intval($accommodation_count_children_stay_free);
	
	$children = $children - $accommodation_count_children_stay_free;
	$children = $children >= 0 ? $children : 0;

	// we are actually (in terms of db data) looking for date 1 day before the to date
	// e.g. when you look to book a room from 19.12. to 20.12 you will be staying 1 night, not 2
	$date_to = date('Y-m-d', strtotime($date_to.' -1 day'));
	
	$dates = get_dates_from_range($date_from, $date_to);

	$total_price = 0;
	
	foreach ($dates as $date) {
	
		$date = date('Y-m-d 12:00:01', strtotime($date));
	
		$price_per_day = get_accommodation_price($date, $accommodation_id, $room_type_id, false);
		$child_price_per_day = get_accommodation_price($date, $accommodation_id, $room_type_id, true);
		
		if ($accommodation_is_price_per_person) {
			$total_price += (($adults * $price_per_day) + ($children * $child_price_per_day)) * $room_count;
		} else {
			$total_price += ($price_per_day * $room_count);
		}
	}
	
	$total_price = $total_price * $room_count;

	return $total_price;
}

function list_accommodation_vacancies($date, $accommodation_id, $room_type_id=0, $is_child_price=false) {
	
	global $wpdb;
	
	$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 
	
	$sql = "SELECT " . ($is_child_price ? "vacancies.price_per_day_child" : "vacancies.price_per_day") . " price, vacancies.room_count, 
			(
				SELECT IFNULL(SUM(bookings.room_count), 0)
				FROM " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . " bookings
				WHERE bookings.accommodation_id=vacancies.accommodation_id ";

	if ($room_type_id > 0) 
		$sql .= $wpdb->prepare(" AND bookings.room_type_id=%d ", $room_type_id);

	$sql .= $wpdb->prepare(" AND %s BETWEEN bookings.date_from AND bookings.date_to ", $date);
		
	$sql .= ") booked_rooms 
			FROM " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . " vacancies 
			WHERE 1=1 ";

	$sql .= $wpdb->prepare(" 	AND vacancies.accommodation_id=%d 
								AND (%s BETWEEN vacancies.start_date AND vacancies.end_date) ", $accommodation_id, $date);

	if ($room_type_id > 0) 
		$sql .= $wpdb->prepare(" AND vacancies.room_type_id=%d ", $room_type_id);

	$sql .= " ORDER BY " . ($is_child_price ? "vacancies.price_per_day_child" : "vacancies.price_per_day");

	return $wpdb->get_results($sql);
}

function list_accommodation_vacancy_start_dates($accommodation_id, $room_type_id=0, $month=0, $year=0) {

	global $wpdb;
	
	$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 
	
	$current_date = date('Y-m-d', time());
	$yesterday = date('Y-m-d 12:00:01',strtotime("-1 days"));
	
	$end_date = null;
	if ($month == 0 && $year == 0)
		$end_date = date('Y-m-d', strtotime($current_date . ' + 365 days'));

	$sql = $wpdb->prepare("
	SELECT dates.single_date
	FROM 
	(
		SELECT ADDDATE(%s,t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) single_date 
		FROM
		(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
		(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
		(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
		(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
		(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4 
		HAVING", $yesterday);
	
	if (isset($end_date))
		$sql .= $wpdb->prepare(" single_date BETWEEN %s AND %s ", $current_date, $end_date);
	else
		$sql .= $wpdb->prepare(" single_date >= %s ", $current_date);
		
	$sql .= "				
	) dates, " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . " vacancies 
	WHERE 1=1 ";

	if ($month > 0 && $year > 0)
		$sql .=  $wpdb->prepare(" AND MONTH(dates.single_date) = %d AND YEAR(dates.single_date) = %d  ", $month, $year);

	$sql .= $wpdb->prepare(" AND vacancies.accommodation_id=%d AND dates.single_date BETWEEN vacancies.start_date AND vacancies.end_date ", $accommodation_id);

	if ($room_type_id > 0) 
		$sql .= $wpdb->prepare(" AND vacancies.room_type_id=%d ", $room_type_id);

	$sql .= " GROUP BY dates.single_date ";
	
	$date_results = $wpdb->get_results($sql);
	
	$available_dates = array();
	
	foreach ($date_results as $date_result) {
	
		$vacancy_results = list_accommodation_vacancies($date_result->single_date, $accommodation_id, $room_type_id);
		$room_count = 0;
		$booked_rooms = 0;
		foreach($vacancy_results as $vacancy_result) {
			$room_count += $vacancy_result->room_count;
			if ($booked_rooms == 0)
				$booked_rooms = $vacancy_result->booked_rooms;
		}
		
		if ($room_count > $booked_rooms) {
			$date_result->single_date = date('Y-m-d', strtotime($date_result->single_date));
			$available_dates[] = $date_result;
		}
	}
	
	return $available_dates;
}

function list_accommodation_vacancy_end_dates($accommodation_id, $room_type_id=0, $month=0, $year=0, $day=0) {

	global $wpdb;
	
	$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 
	
	$current_date = date('Y-m-d', time());
	$yesterday = date('Y-m-d 00:00:00',strtotime("-1 days"));
	
	$end_date = null;
	if ($month == 0 && $year == 0 && $day == 0)
		$end_date = date('Y-m-d', strtotime($current_date . ' + 365 days'));

	$sql = $wpdb->prepare("
	SELECT dates.single_date
	FROM 
	(
		SELECT ADDDATE(%s,t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) single_date 
		FROM
		(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
		(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
		(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
		(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
		(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4 
		HAVING", $yesterday);
	
	if (isset($end_date))
		$sql .= $wpdb->prepare(" single_date BETWEEN %s AND %s ", $current_date, $end_date);
	else
		$sql .= $wpdb->prepare(" single_date >= %s ", $current_date);
		
	$sql .= "				
	) dates, " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . " vacancies 
	WHERE 1=1 ";

	if ($month > 0 && $year > 0 && $day > 0)
		$sql .=  $wpdb->prepare(" AND YEAR(dates.single_date) = %d AND MONTH(dates.single_date) = %d AND DAY(dates.single_date) > %d  ", $year, $month, $day);

	$sql .= $wpdb->prepare(" AND vacancies.accommodation_id=%d AND dates.single_date BETWEEN vacancies.start_date AND vacancies.end_date ", $accommodation_id);

	if ($room_type_id > 0) 
		$sql .= $wpdb->prepare(" AND vacancies.room_type_id=%d ", $room_type_id);

	$sql .= " GROUP BY dates.single_date ";
	
	$date_results = $wpdb->get_results($sql);
	
	$available_dates = array();
	
	foreach ($date_results as $date_result) {
	
		$vacancy_results = list_accommodation_vacancies($date_result->single_date, $accommodation_id, $room_type_id);
		$room_count = 0;
		$booked_rooms = 0;
		foreach($vacancy_results as $vacancy_result) {
			$room_count += $vacancy_result->room_count;
			if ($booked_rooms == 0)
				$booked_rooms = $vacancy_result->booked_rooms;
		}
		
		if ($room_count > $booked_rooms) {
			$date_result->single_date = date('Y-m-d', strtotime($date_result->single_date));
			$available_dates[] = $date_result;
		} else
			break;
	}
	
	return $available_dates;
}

function get_accommodation_price($date, $accommodation_id, $room_type_id=0, $is_child_price=false) {

	$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 
	
	$accommodation_is_self_catered = get_post_meta($accommodation_id, 'accommodation_is_self_catered', true);

	$price = 0;
	$min_price = 0;
	
	$date = date('Y-m-d 12:00:01',strtotime($date));
	
	$vacancy_results = list_accommodation_vacancies($date, $accommodation_id, $room_type_id, $is_child_price);
	
	$room_count = 0;
	foreach($vacancy_results as $vacancy_result) {
		$room_count += $vacancy_result->room_count;
		if ($vacancy_result->booked_rooms < $room_count) {
			if ($min_price == 0 || $min_price > $vacancy_result->price) {
				$min_price = $vacancy_result->price;
				break;
			}
		}
	}
	$price = $min_price;
	
	global $current_user, $currency_symbol, $current_currency, $enabled_currencies, $default_currency;
	
	if ($price > 0) { 
		if ($current_currency && $current_currency != $default_currency)
			$price = currency_conversion($min_price, $default_currency, $current_currency);
	}

	return $price;
}

function get_accommodation_min_price($accommodation_id=0, $room_type_id=0, $location_id=0, $ignore_meta=false) {

	if ($accommodation_id > 0)
		$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 
		
	$min_price = 0;
	
	$accommodation_ids = array();
	if ($accommodation_id > 0) {
		$accommodation_ids[] = $accommodation_id;
	} else if ($location_id > 0) {
		$accommodation_results = list_accommodations(0, 0, '', '', $location_id);
		
		if ( count($accommodation_results) > 0 && $accommodation_results['total'] > 0 ) {
			foreach ($accommodation_results['results'] as $accommodation_result) {
				$accommodation_ids[] = $accommodation_result->ID;
			}
		}
	}
	
	if (count($accommodation_ids) > 0) {
		foreach ($accommodation_ids as $accommodation_id) {			

			$temp_price = 0;
			if (!$ignore_meta && $room_type_id == 0)
				$temp_price = get_post_meta( (int) $accommodation_id, '_accommodation_min_price', true );
			
			if (empty($temp_price)) {
				$date_results = list_accommodation_vacancy_start_dates($accommodation_id, $room_type_id, 0, 0);
				foreach ($date_results as $date_result) {
					$vacancy_results = list_accommodation_vacancies($date_result->single_date, $accommodation_id, $room_type_id);				
					$room_count = 0;
					foreach($vacancy_results as $vacancy_result) {				
						$room_count += $vacancy_result->room_count;

						if ($vacancy_result->booked_rooms < $room_count) {
							if ($min_price == 0 || ($min_price > $vacancy_result->price && $vacancy_result->price > 0)) {
								$min_price = $vacancy_result->price;
								break;
							}
						}
					}
				}
				
				if ($room_type_id == 0) 
					sync_accommodation_min_price($accommodation_id, $min_price);
			} else {
				$temp_price = (float)$temp_price;
				if ($min_price == 0 || ($min_price > $temp_price && $temp_price > 0)) {
					$min_price = $temp_price;
				}
			}
		}
	}
	
	global $current_user, $currency_symbol, $current_currency, $enabled_currencies, $default_currency;
	
	if ($min_price > 0) { 
		if ($current_currency && $current_currency != $default_currency)
			$min_price = currency_conversion($min_price, $default_currency, $current_currency);
	}

	return $min_price;
}

function delete_all_accommodation_vacancies() {

	global $wpdb;
	$table_name = BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE;
	$sql = "DELETE FROM $table_name";
	$wpdb->query($sql);

	delete_post_meta_by_key('_accommodation_min_price');
	
}

function get_accommodation_vacancy($vacancy_id ) {
	global $wpdb;

	$table_name = BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE;
	$sql = "SELECT vacancies.*, accommodations.post_title accommodation_name, room_types.post_title room_type
			FROM " . $table_name . " vacancies 
			INNER JOIN $wpdb->posts accommodations ON accommodations.ID = vacancies.accommodation_id 
			LEFT JOIN $wpdb->posts room_types ON room_types.ID = vacancies.room_type_id 
			WHERE vacancies.Id=%d AND accommodations.post_status = 'publish' AND 
					(room_types.post_status IS NULL OR room_types.post_status = 'publish') ";

	return $wpdb->get_row($wpdb->prepare($sql, $vacancy_id));
}

function list_all_accommodation_vacancies($accommodation_id, $room_type_id, $orderby = 'Id', $order = 'ASC', $paged = null, $per_page = 0 ) {

	global $wpdb;

	$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 
	
	$table_name = BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE;
	$sql = "SELECT DISTINCT vacancies.*, accommodations.post_title accommodation_name, room_types.post_title room_type, IFNULL(accommodation_meta_is_per_person.meta_value, 0) accommodation_is_per_person, IFNULL(accommodation_meta_is_self_catered.meta_value, 0) accommodation_is_self_catered
			FROM " . $table_name . " vacancies 
			INNER JOIN $wpdb->posts accommodations ON accommodations.ID = vacancies.accommodation_id 
			LEFT JOIN $wpdb->postmeta accommodation_meta_is_per_person ON accommodations.ID=accommodation_meta_is_per_person.post_id AND accommodation_meta_is_per_person.meta_key='accommodation_is_price_per_person'
			LEFT JOIN $wpdb->postmeta accommodation_meta_is_self_catered ON accommodations.ID=accommodation_meta_is_self_catered.post_id AND accommodation_meta_is_self_catered.meta_key='accommodation_is_self_catered'
			LEFT JOIN $wpdb->posts room_types ON room_types.ID = vacancies.room_type_id 
			WHERE 	accommodations.post_status = 'publish' AND 
					(room_types.post_status IS NULL OR room_types.post_status = 'publish') ";
			
	if ($accommodation_id > 0) {
		$sql .= $wpdb->prepare(" AND vacancies.accommodation_id=%d ", $accommodation_id);
	}
	
	if ($room_type_id > 0) {
		$sql .= $wpdb->prepare(" AND vacancies.room_type_id=%d ", $room_type_id);
	}

	if(!empty($orderby) & !empty($order)){ 
		$sql.=' ORDER BY ' . $orderby . ' ' . $order; 
	}
	
	$sql_count = $sql;
	
	if(!empty($paged) && !empty($per_page)){
		$offset=($paged-1)*$per_page;
		$sql .= $wpdb->prepare(" LIMIT %d, %d ", $offset, $per_page); 
	}

	$results = array(
		'total' => $wpdb->query($sql_count),
		'results' => $wpdb->get_results($sql)
	);

	return $results;
}

function get_accommodation_booking($booking_id) {
	global $wpdb, $byt_multi_language_count;

	$sql = "SELECT DISTINCT bookings.*, accommodations.post_title accommodation_name, room_types.post_title room_type
			FROM " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . " bookings 
			INNER JOIN $wpdb->posts accommodations ON accommodations.ID = bookings.accommodation_id ";
			
	if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_accommodation' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = accommodations.ID ";
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_accommodation' AND translations_default.language_code='" . get_default_language() . "' AND translations_default.trid = translations.trid ";
	}
			
	$sql .= " LEFT JOIN $wpdb->posts room_types ON room_types.ID = bookings.room_type_id 
			WHERE accommodations.post_status = 'publish' AND (room_types.post_status IS NULL OR room_types.post_status = 'publish') 
			AND bookings.Id = $booking_id ";

	return $wpdb->get_row($sql);
}

function list_accommodation_bookings($paged = null, $per_page = 0, $orderby = 'Id', $order = 'ASC', $search_term = null, $user_id = 0) {
	global $wpdb, $byt_multi_language_count;

	$table_name = BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE;
	$sql = "SELECT DISTINCT bookings.*, accommodations.post_title accommodation_name, room_types.post_title room_type
			FROM " . $table_name . " bookings 
			INNER JOIN $wpdb->posts accommodations ON accommodations.ID = bookings.accommodation_id ";
			
	if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_accommodation' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = accommodations.ID ";
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_accommodation' AND translations_default.language_code='" . get_default_language() . "' AND translations_default.trid = translations.trid ";
	}
			
	$sql .= " LEFT JOIN $wpdb->posts room_types ON room_types.ID = bookings.room_type_id ";
	
	$sql .= " WHERE accommodations.post_status = 'publish' AND (room_types.post_status IS NULL OR room_types.post_status = 'publish') ";
	
	
	if ($user_id > 0) {
		$sql .= $wpdb->prepare(" AND bookings.user_id = %d ", $user_id) ;
	}
	
	if ($search_term != null && !empty($search_term)) {
		$search_term = "%" . $search_term . "%";
		$sql .= $wpdb->prepare(" AND 1=1 AND (bookings.first_name LIKE '%s' OR bookings.last_name LIKE '%s') ", $search_term, $search_term);
	}
	
	if(!empty($orderby) & !empty($order)){ 
		$sql.=' ORDER BY '.$orderby.' '.$order; 
	}
	
	$sql_count = $sql;
	
	if(!empty($paged) && !empty($per_page)){
		$offset=($paged-1)*$per_page;
		$sql .= $wpdb->prepare(" LIMIT %d, %d ", $offset, $per_page); 
	}

	$results = array(
		'total' => $wpdb->query($sql_count),
		'results' => $wpdb->get_results($sql)
	);
	
	return $results;
}

function create_accommodation_booking($first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $room_count, $date_from, $date_to, $accommodation_id, $room_type_id, $user_id, $is_self_catered, $total_price, $adults, $children) {

	global $wpdb;
	
	$date_from = date('Y-m-d 12:00:00',strtotime($date_from));
	$date_to = date('Y-m-d 12:00:00',strtotime($date_to));
	
	$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 
	
	$errors = array();

	$sql = "INSERT INTO " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . "
			(first_name, last_name, email, phone, address, town, zip, country, special_requirements, room_count, user_id, total_price, adults, children, date_from, date_to, accommodation_id, room_type_id)
			VALUES 
			(%s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d, %d, %d, %d, %s, %s, %d, %d);";

	$result = $wpdb->query($wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $room_count, $user_id, $total_price, $adults, $children, $date_from, $date_to, $accommodation_id, $room_type_id));

	if (is_wp_error($result))
		$errors[] = $result;

	$booking_id = $wpdb->insert_id;

	$min_price = get_accommodation_min_price ($accommodation_id, 0, 0, true);	
	sync_accommodation_min_price($accommodation_id, $min_price);
	
	if (count($errors) > 0)
		return $errors;
	return $booking_id;
}

function sync_accommodation_min_price($accommodation_id, $min_price) {
	update_post_meta($accommodation_id, '_accommodation_min_price', $min_price);
}

function update_accommodation_booking($booking_id, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $room_count, $date_from, $date_to, $accommodation_id, $room_type_id, $user_id, $is_self_catered, $total_price, $adults, $children) {

	global $wpdb;
	
	$date_from = date('Y-m-d 12:00:00',strtotime($date_from));
	$date_to = date('Y-m-d 12:00:00',strtotime($date_to));
	
	$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 

	$sql = "UPDATE " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . "
			SET
				first_name = %s, 
				last_name = %s, 
				email = %s, 
				phone = %s, 
				address = %s, 
				town = %s, 
				zip = %s, 
				country = %s, 
				special_requirements = %s, 
				room_count = %d, 
				user_id = %d, 
				total_price = %f, 
				adults = %d, 
				children = %d, 
				date_from = %s, 
				date_to = %s, 
				accommodation_id = %d, 
				room_type_id = %d
			WHERE Id = %d;";

	$result = $wpdb->query($wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $room_count, $user_id, $total_price, $adults, $children, $date_from, $date_to, $accommodation_id, $room_type_id, $booking_id));

	$min_price = get_accommodation_min_price ($accommodation_id, 0, 0, true);	
	sync_accommodation_min_price($accommodation_id, $min_price);
	
	return $result;
}

function create_accommodation_vacancy($start_date, $end_date, $accommodation_id, $room_type_id, $room_count, $price_per_day, $price_per_day_child) {

	global $wpdb;
	
	$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 
	
	$sql = "INSERT INTO " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . "
			(start_date, end_date, accommodation_id, room_type_id, room_count, price_per_day, price_per_day_child)
			VALUES
			(%s, %s, %d, %d, %d, %f, %f);";
	
	$wpdb->query($wpdb->prepare($sql, $start_date, $end_date, $accommodation_id, $room_type_id, $room_count, $price_per_day, $price_per_day_child));	
	
	$min_price = get_accommodation_min_price ($accommodation_id, 0, 0, true);	
	sync_accommodation_min_price($accommodation_id, $min_price);
	
	return $wpdb->insert_id;
}

function update_accommodation_vacancy($vacancy_id, $start_date, $end_date, $accommodation_id, $room_type_id, $room_count, $price_per_day, $price_per_day_child) {

	global $wpdb;
	
	$accommodation_id = get_default_language_post_id($accommodation_id, 'accommodation');
	if ($room_type_id > 0)
		$room_type_id = get_default_language_post_id($room_type_id, 'room_type'); 
	
	$sql = "UPDATE " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . "
			SET start_date=%s, end_date=%s, accommodation_id=%d, room_type_id=%d, room_count=%d, price_per_day=%f, price_per_day_child=%f
			WHERE Id=%d";
	
	$wpdb->query($wpdb->prepare($sql, $start_date, $end_date, $accommodation_id, $room_type_id, $room_count, $price_per_day, $price_per_day_child, $vacancy_id));	
	
	$min_price = get_accommodation_min_price ($accommodation_id, 0, 0, true);	
	sync_accommodation_min_price($accommodation_id, $min_price);
}

function delete_accommodation_vacancy($vacancy_id) {
	
	global $wpdb;
	
	$sql = "DELETE FROM " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . "
			WHERE Id = %d";

	$vacancy = get_accommodation_vacancy($vacancy_id);
	
	$wpdb->query($wpdb->prepare($sql, $vacancy_id));
	
	$min_price = get_accommodation_min_price ($vacancy->accommodation_id, 0, 0, true);	
	sync_accommodation_min_price($vacancy->accommodation_id, $min_price);
	
}

function delete_accommodation_booking($booking_id) {
	global $wpdb;
	
	$sql = "DELETE FROM " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . "
			WHERE Id = %d";
	
	$booking = get_accommodation_booking($booking_id);
	
	$wpdb->query($wpdb->prepare($sql, $booking_id));
	
	$min_price = get_accommodation_min_price ($booking->accommodation_id, 0, 0, true);	
	sync_accommodation_min_price($booking->accommodation_id, $min_price);
}

function get_count_bookings_with_unfixed_dates() {
	global $wpdb;
	
	$sql = "SELECT COUNT(*) ct 
			FROM " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . "
			WHERE HOUR(date_from) != 12 OR HOUR(date_to) != 12";
			
	return $wpdb->get_var($sql);
}

function fix_accommodation_booking_dates() {
	
	global $wpdb;
	
	$sql = "UPDATE " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . "
			SET date_from = DATE_ADD(DATE(date_from), INTERVAL 12 HOUR)
			WHERE HOUR(date_from) != 12;";

	$wpdb->query($sql);			
			
	$sql = "UPDATE " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . "
			SET date_to = DATE_ADD(DATE(date_to), INTERVAL 12 HOUR)
			WHERE HOUR(date_to) != 12;";

	$wpdb->query($sql);
}

	
	// if ( count($search_args) > 0) {
		
		// if ( isset($search_args['prices']) ) {
		
			// $prices = (array)$search_args['prices'];
			
			// if (count($prices) > 0) {
			
				// $price_range_bottom = ( isset($search_args['price_range_bottom']) ) ? intval($search_args['price_range_bottom']) : 0;
				// $price_range_increment = ( isset($search_args['price_range_increment']) ) ? intval($search_args['price_range_increment']) : 50;
				// $price_range_count = ( isset($search_args['price_range_count']) ) ? intval($search_args['price_range_count']) : 5;

				// $having_sql .= " AND ( 1!=1 ";
				
				// $bottom = 0;
				// $top = 0;
				
				// for ( $i = 0; $i < $price_range_count; $i++ ) { 
					// $bottom = ($i * $price_range_increment) + $price_range_bottom;
					// $top = ( ( $i+1 ) * $price_range_increment ) + $price_range_bottom - 1;	

					// if ( $i < ( $price_range_count ) ) {
						// if ( in_array( $i + 1, $prices ) )
							// $having_sql .= " OR (price >= $bottom AND price <= $top ) ";
					// } else {
						// $having_sql .= " OR (price >= $bottom ) ";
					// }
				// }
				
				// $having_sql .= ")";
			// }
			
		// }	
				
	// }