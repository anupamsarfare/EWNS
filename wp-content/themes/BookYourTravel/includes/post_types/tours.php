<?php

global $wpdb, $byt_multi_language_count;

function bookyourtravel_register_tour_post_type() {
	
	$tours_permalink_slug = of_get_option('tours_permalink_slug', 'tours');
	
	$labels = array(
		'name'                => _x( 'Tours', 'Post Type General Name', 'bookyourtravel' ),
		'singular_name'       => _x( 'Tour', 'Post Type Singular Name', 'bookyourtravel' ),
		'menu_name'           => __( 'Tours', 'bookyourtravel' ),
		'all_items'           => __( 'All Tours', 'bookyourtravel' ),
		'view_item'           => __( 'View Tour', 'bookyourtravel' ),
		'add_new_item'        => __( 'Add New Tour', 'bookyourtravel' ),
		'add_new'             => __( 'New Tour', 'bookyourtravel' ),
		'edit_item'           => __( 'Edit Tour', 'bookyourtravel' ),
		'update_item'         => __( 'Update Tour', 'bookyourtravel' ),
		'search_items'        => __( 'Search Tours', 'bookyourtravel' ),
		'not_found'           => __( 'No Tours found', 'bookyourtravel' ),
		'not_found_in_trash'  => __( 'No Tours found in Trash', 'bookyourtravel' ),
	);
	$args = array(
		'label'               => __( 'tour', 'bookyourtravel' ),
		'description'         => __( 'Tour information pages', 'bookyourtravel' ),
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
 		'rewrite' => array('slug' => $tours_permalink_slug),
	);
	register_post_type( 'tour', $args );	
}

function bookyourtravel_register_tour_type_taxonomy(){
	$labels = array(
			'name'              => _x( 'Tour types', 'taxonomy general name', 'bookyourtravel' ),
			'singular_name'     => _x( 'Tour type', 'taxonomy singular name', 'bookyourtravel' ),
			'search_items'      => __( 'Search Tour types', 'bookyourtravel' ),
			'all_items'         => __( 'All Tour types', 'bookyourtravel' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'         => __( 'Edit Tour type', 'bookyourtravel' ),
			'update_item'       => __( 'Update Tour type', 'bookyourtravel' ),
			'add_new_item'      => __( 'Add New Tour type', 'bookyourtravel' ),
			'new_item_name'     => __( 'New Tour Type Name', 'bookyourtravel' ),
			'separate_items_with_commas' => __( 'Separate Tour types with commas', 'bookyourtravel' ),
			'add_or_remove_items'        => __( 'Add or remove Tour types', 'bookyourtravel' ),
			'choose_from_most_used'      => __( 'Choose from the most used Tour types', 'bookyourtravel' ),
			'not_found'                  => __( 'No Tour types found.', 'bookyourtravel' ),
			'menu_name'         => __( 'Tour types', 'bookyourtravel' ),
		);
		
	$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => false,
			'update_count_callback' => '_update_post_term_count',
			'rewrite'           => false,
		);
	
	$enable_tours = of_get_option('enable_tours', 1);

	if ($enable_tours) {
		register_taxonomy( 'tour_type', 'tour', $args );
	}
}

function bookyourtravel_create_tour_extra_tables($installed_version) {

	global $wpdb;

	if ($installed_version != BOOKYOURTRAVEL_VERSION) {
	
		// we do not execute sql directly
		// we are calling dbDelta which cant migrate database
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');		
		
		$table_name = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
		$sql = "CREATE TABLE " . $table_name . " (
					Id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					tour_id bigint(20) NOT NULL,
					start_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					duration_days int NOT NULL DEFAULT 0,
					price decimal(16, 2) NOT NULL DEFAULT 0,
					price_child decimal(16, 2) NOT NULL DEFAULT 0, 
					max_people int(11) NOT NULL DEFAULT 0,
					created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					end_date datetime NULL,
					PRIMARY KEY  (Id)
				);";

		dbDelta($sql);
		
		$table_name = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;
		$sql = "CREATE TABLE " . $table_name . " (
					Id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					tour_schedule_id bigint(20) NOT NULL,
					tour_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL, 
					first_name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
					last_name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
					email varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
					phone varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					address varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					town varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					zip varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					country varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
					special_requirements text CHARACTER SET utf8 COLLATE utf8_bin NULL,
					adults bigint(20) NOT NULL,
					children bigint(20) NOT NULL,
					user_id bigint(20) NOT NULL DEFAULT 0,
					total_price_adults decimal(16, 2) NOT NULL,
					total_price_children decimal(16, 2) NOT NULL,
					total_price decimal(16, 2) NOT NULL,
					created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					woo_order_id bigint(20) NULL,
					cart_key VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' NOT NULL,
					currency_code VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
					PRIMARY KEY  (Id)
				);";

		dbDelta($sql);
		
		global $EZSQL_ERROR;
		$EZSQL_ERROR = array();
		
	}
}

function list_tours_count($paged = 0, $per_page = 0, $orderby = '', $order = '', $location_id = 0, $tour_types_array = array(), $search_args = array(), $featured_only = false, $author_id = null, $include_private = false) {
	return list_tours($paged, $per_page, $orderby, $order, $location_id, $tour_types_array, $search_args, $featured_only, $author_id, $include_private, true);
}

function tours_search_fields( $fields, &$wp_query ) {

	global $wpdb;

	if ( isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'tour' ) {
		
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
							SELECT IFNULL(SUM(max_people), 0) places_available FROM " . BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE . "
							WHERE tour_id = {$wpdb->posts}.ID ";
							
			if ($date_from != null && $date_to != null) {
				$fields .= $wpdb->prepare(" AND (%s > start_date AND (%s < end_date OR end_date IS NULL)) ", $date_from, $date_to);
			} else if ($date_from != null) {
				$fields .= $wpdb->prepare(" AND %s > start_date ", $date_from);
			} else if ($date_to != null) {
				$fields .= $wpdb->prepare(" AND (%s < end_date OR end_date IS NULL) ", $date_to);
			}						
			
			$fields .= " ) places_available ";
			
			$fields .= ", (
							SELECT (IFNULL(SUM(adults), 0) + IFNULL(SUM(children), 0)) places_booked 
							FROM " . BOOKYOURTRAVEL_TOUR_BOOKING_TABLE . " bookings
							INNER JOIN " . BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE . " schedule ON bookings.tour_schedule_id = schedule.Id
							WHERE schedule.tour_id = {$wpdb->posts}.ID ";
							
			if ($date_from != null && $date_to != null) {
				$fields .= $wpdb->prepare(" AND (%s > start_date AND (%s < end_date OR end_date IS NULL)) ", $date_from, $date_to);
			} else if ($date_from != null) {
				$fields .= $wpdb->prepare(" AND %s > start_date ", $date_from);
			} else if ($date_to != null) {
				$fields .= $wpdb->prepare(" AND (%s < end_date OR end_date IS NULL) ", $date_to);
			}						
			
			$fields .= " ) places_booked ";
		}
						
	}

	return $fields;
}

function tours_search_where( $where, &$wp_query ) {
	
	global $wpdb;
	
	if ( isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'tour' ) {
		if ( isset($wp_query->query_vars['s']) && !empty($wp_query->query_vars['s']) && isset($wp_query->query_vars['byt_location_ids']) && isset($wp_query->query_vars['s']) ) {
			$needed_where_part = '';
			$where_array = explode('AND', $where);
			foreach ($where_array as $where_part) {
				if (strpos($where_part,"meta_key = 'tour_location_post_id'") !== false) {
					$needed_where_part = $where_part;
					break;
				}
			}
			
			if (!empty($needed_where_part)) {
				$prefix = str_replace("meta_key = 'tour_location_post_id'","",$needed_where_part);
				$prefix = str_replace(")", "", $prefix);
				$prefix = str_replace("(", "", $prefix);
				$prefix = trim($prefix);

				$location_ids = $wp_query->query_vars['byt_location_ids'];
				$location_ids_str = "'".implode("','", $location_ids)."'";				
				$location_search_param_part = "{$prefix}meta_key = 'tour_location_post_id' AND CAST({$prefix}meta_value AS CHAR) IN ($location_ids_str)";							
			
				$where = str_replace($location_search_param_part, "1=1", $where);
				
				$post_content_part = "OR ($wpdb->posts.post_content LIKE '%" . $wp_query->get('s') . "%')";
				$where = str_replace($post_content_part, $post_content_part . " OR ($location_search_param_part) ", $where);				
			}
		}
	}
	
	return $where;
}

function tours_search_groupby( $groupby, &$wp_query ) {

	global $wpdb;
	
	if ( isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'tour' ) {
		
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
		
		if (empty($groupby))
			$groupby .=	"$wpdb->posts.ID";
		if ($search_only_available && (isset($date_from) || isset($date_to))) {				
			$groupby .= ' HAVING places_available > places_booked ';		
		}
	}
	
	return $groupby;
}

function list_tours($paged = 0, $per_page = -1, $orderby = '', $order = '', $location_id = 0, $tour_types_array = array(), $search_args = array(), $featured_only = false, $author_id = null, $include_private = false, $count_only = false ) {

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
		'post_type'         => 'tour',
		'post_status'       => array('publish'),
		'posts_per_page'    => $per_page,
		'paged' 			=> $paged, 
		'orderby'           => $orderby,
		'suppress_filters' 	=> false,
		'order'				=> $order,
		'meta_query'        => array('relation' => 'AND')
	);

	if ($orderby == 'review_score') {
		$args['meta_key'] = 'review_score';
		$args['orderby'] = 'meta_value_num';
	} else if ($orderby == 'min_price') {
		$args['meta_key'] = '_tour_min_price';
		$args['orderby'] = 'meta_value_num';
	}
	
	$guests = (isset($search_args['guests']) && isset($search_args['guests'])) ? intval($search_args['guests']) : 0;
	
	if (isset($search_args['keyword']) && strlen($search_args['keyword']) > 0) {
		$args['s'] = $search_args['keyword'];
	}
	
	if ($include_private) {
		$args['post_status'][] = 'private';
	}
	
	if ( isset($search_args['rating']) && strlen($search_args['rating']) > 0 ) {
		$rating = intval($search_args['rating']);			
		if ($rating >= 0 & $rating <=10) {
			$args['meta_query'][] = array(
				'key'       => 'review_score',
				'value'     => $rating,
				'compare'   => '>=',
				'type' => 'numeric'
			);
		}
	}
	
	if (isset($featured_only) && $featured_only) {
		$args['meta_query'][] = array(
			'key'       => 'tour_is_featured',
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
		$args['meta_query'][] = array(
			'key'       => 'tour_location_post_id',
			'value'     => $location_ids,
			'compare'   => 'IN'
		);
		$args['byt_location_ids'] = $location_ids;
	}
	
	if (!empty($tour_types_array)) {
		$args['tax_query'][] = 	array(
				'taxonomy' => 'tour_type',
				'field' => 'id',
				'terms' => $tour_types_array,
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
	
	add_filter('posts_where', 'tours_search_where', 10, 2 );
	
	if ($search_only_available) {
		add_filter('posts_fields', 'tours_search_fields', 10, 2 );
		add_filter('posts_groupby', 'tours_search_groupby', 10, 2 );
	}
	
	$posts_query = new WP_Query($args);
	
	// echo $posts_query->request;
	
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
	
	remove_filter('posts_where', 'tours_search_where');
	
	if ($search_only_available) {
		remove_filter('posts_fields', 'tours_search_fields' );
		remove_filter('posts_groupby', 'tours_search_groupby');
	}
	
	return $results;
	
}

function list_available_tour_schedule_entries($tour_id, $from_date, $from_year, $from_month, $tour_type_is_repeated, $tour_type_day_of_week) {

	global $wpdb;

	$tour_id = get_default_language_post_id($tour_id, 'tour');
	
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;

	$yesterday = date('Y-m-d',strtotime("-1 days"));

	if ($tour_type_is_repeated == 0) {
		// oneoff tours, must have start date in future in order for people to attend
		$sql = "
			SELECT *, schedule.start_date tour_date, 0 num
			FROM $table_name_schedule schedule 
			WHERE tour_id=%d AND start_date >= %s 
			HAVING max_people > (SELECT COUNT(*) ct FROM $table_name_bookings bookings WHERE bookings.tour_schedule_id = schedule.Id) ";
			
		$sql = $wpdb->prepare($sql, $tour_id, $from_date);
	} else if ($tour_type_is_repeated == 1) {		
		// daily tours
		$sql = $wpdb->prepare("
			SELECT schedule.Id, schedule.price, schedule.price_child, schedule.duration_days, schedule.max_people, date_range.single_date tour_date, num
			FROM $table_name_schedule schedule
			LEFT JOIN 
			(
				SELECT ADDDATE(%s,t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) single_date, (t1.i*10 + t0.i) num ", $yesterday);
				
		$sql .= "
				FROM
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
				HAVING  YEAR(single_date) = %d AND MONTH(single_date) = %d
			) date_range ON date_range.single_date >= %s
			WHERE tour_id=%d AND ( (schedule.end_date IS NULL OR schedule.end_date = '0000-00-00 00:00:00') OR date_range.single_date < schedule.end_date )
			HAVING schedule.max_people > (SELECT COUNT(*) ct FROM $table_name_bookings bookings WHERE bookings.tour_schedule_id = schedule.Id AND bookings.tour_date = date_range.single_date) ";
		
		$sql = $wpdb->prepare($sql, $from_year, $from_month, $from_date, $tour_id);

	} else if ($tour_type_is_repeated == 2) {
	
		// weekday tours
		$sql = $wpdb->prepare("
			SELECT schedule.Id, schedule.price, schedule.price_child, schedule.duration_days, schedule.max_people, date_range.single_date tour_date, num
			FROM $table_name_schedule schedule
			LEFT JOIN 
			(
				SELECT ADDDATE(%s,t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) single_date, (t1.i*10 + t0.i) num ", $yesterday);
		
		$sql .= "
				FROM
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
				HAVING WEEKDAY(single_date) BETWEEN 0 AND 4 AND YEAR(single_date) = %d AND MONTH(single_date) = %d
			) date_range ON date_range.single_date >= %s
			WHERE tour_id=%d AND ( (schedule.end_date IS NULL OR schedule.end_date = '0000-00-00 00:00:00') OR date_range.single_date < schedule.end_date )	
			HAVING schedule.max_people > (SELECT COUNT(*) ct FROM $table_name_bookings bookings WHERE bookings.tour_schedule_id = schedule.Id AND bookings.tour_date = date_range.single_date) ";
		
		$sql = $wpdb->prepare($sql, $from_year, $from_month, $from_date, $tour_id);
	} else if ($tour_type_is_repeated == 3) {
		
		// weekly tours
		$sql = $wpdb->prepare("
			SELECT schedule.Id, schedule.price, schedule.price_child, schedule.duration_days, schedule.max_people, date_range.single_date tour_date, num
			FROM $table_name_schedule schedule
			LEFT JOIN 
			(
				SELECT ADDDATE(%s,t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) single_date, (t1.i*10 + t0.i) num ", $yesterday);
				
		$sql .= "
				FROM
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
				(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
				HAVING WEEKDAY(single_date) = %d AND YEAR(single_date) = %d AND MONTH(single_date) = %d
			) date_range ON date_range.single_date >= %s 
			WHERE tour_id=%d AND ( (schedule.end_date IS NULL OR schedule.end_date = '0000-00-00 00:00:00') OR date_range.single_date < schedule.end_date ) 			
			HAVING schedule.max_people > (SELECT COUNT(*) ct FROM $table_name_bookings bookings WHERE bookings.tour_schedule_id = schedule.Id AND bookings.tour_date = date_range.single_date) ";
		
		$sql = $wpdb->prepare($sql, $tour_type_day_of_week, $from_year, $from_month, $from_date, $tour_id);		
	}

	return $wpdb->get_results($sql);
}

function get_tour_booking($booking_id) {

	global $wpdb, $byt_multi_language_count;

	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;
	
	$sql = "SELECT 	DISTINCT bookings.*, 
					tours.post_title tour_name, 
					schedule.duration_days,
					bookings.total_price,
					schedule.tour_id
			FROM $table_name_bookings bookings 
			INNER JOIN $table_name_schedule schedule ON schedule.Id = bookings.tour_schedule_id
			INNER JOIN $wpdb->posts tours ON tours.ID = schedule.tour_id ";
			
	if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_tour' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = tours.ID ";
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_tour' AND translations_default.language_code='" . get_default_language() . "' AND translations_default.trid = translations.trid ";
	}
			
	$sql .= " WHERE tours.post_status = 'publish' AND bookings.Id = %d ";

	$sql = $wpdb->prepare($sql, $booking_id);
	return $wpdb->get_row($sql);
}

function create_tour_booking($first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $tour_schedule_id, $user_id, $total_price_adults, $total_price_children, $total_price, $start_date) {

	global $wpdb;
	
	$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;

	$sql = "INSERT INTO $table_name_bookings
			(first_name, last_name, email, phone, address, town, zip, country, special_requirements, adults, children, tour_schedule_id, user_id, total_price_adults, total_price_children, total_price, tour_date)
			VALUES 
			(%s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d, %d, %d, %f, %f, %f, %s);";
	$sql = $wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $tour_schedule_id, $user_id, (float)$total_price_adults, (float)$total_price_children, (float)$total_price, $start_date);
	
	$wpdb->query($sql);
	
	$booking_id = $wpdb->insert_id;
	
	$booking = get_tour_booking($booking_id);

	$current_date = date('Y-m-d', time());
	$min_price = get_tour_min_price ($booking->tour_id, $current_date, true);	
	sync_tour_min_price($booking->tour_id, $min_price);
	
	return $booking_id;
}

function update_tour_booking($booking_id, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $tour_schedule_id, $user_id, $total_price_adults, $total_price_children, $total_price, $start_date) {
	
	global $wpdb;
	
	$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;

	$sql = "UPDATE $table_name_bookings
			SET first_name = %s,
				last_name = %s, 
				email = %s, 
				phone = %s, 
				address = %s, 
				town = %s, 
				zip = %s, 
				country = %s, 
				special_requirements = %s,
				adults = %d, 
				children = %d, 
				tour_schedule_id = %d, 
				user_id = %d, 
				total_price_adults = %f, 
				total_price_children = %f, 
				total_price = %f, 
				tour_date = %s
			WHERE Id=%d";
			
	$sql = $wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $tour_schedule_id, $user_id, (float)$total_price_adults, (float)$total_price_children, (float)$total_price, $start_date, $booking_id);
	
	$wpdb->query($sql);
	
	$booking = get_tour_booking($booking_id);
	$current_date = date('Y-m-d', time());
	$min_price = get_tour_min_price ($booking->tour_id, $current_date, true);	
	sync_tour_min_price($booking->tour_id, $min_price);
}

function delete_tour_booking($booking_id) {

	global $wpdb;
	
	$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;
	
	$sql = "DELETE FROM $table_name_bookings
			WHERE Id = %d";
			
	$booking = get_tour_booking($booking_id);
	
	$wpdb->query($wpdb->prepare($sql, $booking_id));
	
	$current_date = date('Y-m-d', time());
	$min_price = get_tour_min_price ($booking->tour_id, $current_date, true);	
	sync_tour_min_price($booking->tour_id, $min_price);
	
}

function list_tour_bookings($paged = null, $per_page = 0, $orderby = 'Id', $order = 'ASC', $search_term = null ) {

	global $wpdb, $byt_multi_language_count;
	
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;

	$sql = "SELECT 	DISTINCT bookings.*, 
					tours.post_title tour_name, 
					schedule.start_date,
					schedule.duration_days,
					bookings.total_price
			FROM $table_name_bookings bookings 
			INNER JOIN $table_name_schedule schedule ON schedule.Id = bookings.tour_schedule_id
			INNER JOIN $wpdb->posts tours ON tours.ID = schedule.tour_id ";
			
	if(defined('ICL_LANGUAGE_CODE') && (get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_tour' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = tours.ID ";
		$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_tour' AND translations_default.language_code='" . get_default_language() . "' AND translations_default.trid = translations.trid ";
	}
	
	$sql .= " WHERE tours.post_status = 'publish' ";
	
	if ($search_term != null && !empty($search_term)) {
		$search_term = "%" . $search_term . "%";
		$sql .= $wpdb->prepare(" AND (bookings.first_name LIKE '%s' OR bookings.last_name LIKE '%s') ", $search_term, $search_term);
	}
	
	if(!empty($orderby) && !empty($order)){ 
		$sql.= "ORDER BY $orderby $order"; 
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

function create_tour_schedule($tour_id, $start_date, $duration_days, $price, $price_child, $max_people, $end_date) {

	global $wpdb;
	
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	
	$tour_id = get_default_language_post_id($tour_id, 'tour');
	
	if ($end_date == null) {
		$sql = "INSERT INTO $table_name_schedule
				(tour_id, start_date, duration_days, price, price_child, max_people)
				VALUES
				(%d, %s, %d, %f, %f, %d);";
		$sql = $wpdb->prepare($sql, $tour_id, $start_date, $duration_days, $price, $price_child, $max_people);
	} else {
		$sql = "INSERT INTO $table_name_schedule
				(tour_id, start_date, duration_days, price, price_child, max_people, end_date)
				VALUES
				(%d, %s, %d, %f, %f, %d, %s);";
		$sql = $wpdb->prepare($sql, $tour_id, $start_date, $duration_days, $price, $price_child, $max_people, $end_date);
	}
	
	$wpdb->query($sql);
	
	$current_date = date('Y-m-d', time());
	$min_price = get_tour_min_price ($tour_id, $current_date, true);	
	sync_tour_min_price($tour_id, $min_price);
}

function update_tour_schedule($schedule_id, $start_date, $duration_days, $tour_id, $price, $price_child, $max_people, $end_date) {

	global $wpdb;
	
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	
	$tour_id = get_default_language_post_id($tour_id, 'tour');

	if ($end_date == null) {
		$sql = "UPDATE " . BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE . "
				SET start_date=%s, duration_days=%d, tour_id=%d, price=%f, price_child=%f, max_people=%d
				WHERE Id=%d";
		$sql = $wpdb->prepare($sql, $start_date, $duration_days, $tour_id, $price, $price_child, $max_people, $schedule_id);
	} else {
		$sql = "UPDATE " . BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE . "
				SET start_date=%s, duration_days=%d, tour_id=%d, price=%f, price_child=%f, max_people=%d, end_date=%s
				WHERE Id=%d";
		$sql = $wpdb->prepare($sql, $start_date, $duration_days, $tour_id, $price, $price_child, $max_people, $end_date, $schedule_id);
	}
	
	$current_date = date('Y-m-d', time());
	$min_price = get_tour_min_price ($tour_id, $current_date, true);	
	sync_tour_min_price($tour_id, $min_price);
	
	$wpdb->query($sql);	
}

function delete_tour_schedule($schedule_id) {

	global $wpdb;
	
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	
	$sql = "DELETE FROM $table_name_schedule
			WHERE Id = %d";
	
	$schedule = get_tour_schedule($schedule_id);
	
	$wpdb->query($wpdb->prepare($sql, $schedule_id));	
	
	$current_date = date('Y-m-d', time());
	$min_price = get_tour_min_price ($schedule->tour_id, $current_date, true);
	sync_tour_min_price($schedule->tour_id, $min_price);

}

function get_tour_schedule($tour_schedule_id) {

	global $wpdb;
		
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;
		
	$sql = "SELECT 	schedule.*, tours.post_title tour_name, 
					(
						SELECT COUNT(*) ct 
						FROM $table_name_bookings bookings 
						WHERE bookings.tour_schedule_id = schedule.Id 
					) has_bookings,
					IFNULL(tour_price_meta.meta_value, 0) tour_is_price_per_group
			FROM $table_name_schedule schedule 
			INNER JOIN $wpdb->posts tours ON tours.ID = schedule.tour_id 
			LEFT JOIN $wpdb->postmeta tour_price_meta ON tours.ID = tour_price_meta.post_id AND tour_price_meta.meta_key = 'tour_is_price_per_group'
			WHERE schedule.Id=%d ";
	
	$sql = $wpdb->prepare($sql, $tour_schedule_id);
	return $wpdb->get_row($sql);
}

function delete_all_tour_schedules() {

	global $wpdb;
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	$sql = "DELETE FROM $table_name_schedule";
	$wpdb->query($sql);	
	
	delete_post_meta_by_key('_tour_min_price');
}

function list_tour_schedules ($paged = null, $per_page = 0, $orderby = 'Id', $order = 'ASC', $day = 0, $month = 0, $year = 0, $tour_id = 0, $search_term = '') {

	global $wpdb;
	
	$tour_id = get_default_language_post_id($tour_id, 'tour');
	
	$filter_date = '';
	if ($day > 0 || $month > 0 || $year) { 
		$filter_date .= ' AND ( 1=1 ';
		if ($day > 0)
			$filter_date .= $wpdb->prepare(" AND DAY(start_date) = %d ", $day);			
		if ($month > 0)
			$filter_date .= $wpdb->prepare(" AND MONTH(start_date) = %d ", $month);			
		if ($year > 0)
			$filter_date .= $wpdb->prepare(" AND YEAR(start_date) = %d ", $year);			
		$filter_date .= ')';		
	}

	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;
	
	$sql = "SELECT 	schedule.*, tours.post_title tour_name, 
					(
						SELECT COUNT(*) ct 
						FROM $table_name_bookings bookings 
						WHERE bookings.tour_schedule_id = schedule.Id 
					) has_bookings,
					IFNULL(tour_price_meta.meta_value, 0) tour_is_price_per_group
			FROM $table_name_schedule schedule 
			INNER JOIN $wpdb->posts tours ON tours.ID = schedule.tour_id 
			LEFT JOIN $wpdb->postmeta tour_price_meta ON tours.ID = tour_price_meta.post_id AND tour_price_meta.meta_key = 'tour_is_price_per_group'
			WHERE tours.post_status = 'publish' ";
			
	if ($tour_id > 0) {
		$sql .= $wpdb->prepare(" AND schedule.tour_id=%d ", $tour_id);
	}

	if ($filter_date != null && !empty($filter_date)) {
		$sql .= $filter_date;
	}
	
	if(!empty($orderby) & !empty($order)){ 
		$sql .= $wpdb->prepare(" ORDER BY %s %s ", $orderby, $order); 
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

function get_tour_schedule_price($schedule_id, $is_child_price) {

	global $wpdb;
	
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;

	$sql = "SELECT " . ($is_child_price ? "schedule.price_child" : "schedule.price") . "
			FROM $table_name_schedule schedule 
			WHERE id=%d ";	
			
	$price = $wpdb->get_var($wpdb->prepare($sql, $schedule_id));
	
	global $current_currency, $default_currency;
	if ($current_currency && $current_currency != $default_currency)
		$price = currency_conversion($price, $default_currency, $current_currency);
	
	return $price;
}

function get_tour_available_schedule_id($tour_id, $date) {

	global $wpdb;
	
	$tour_obj = new byt_tour(intval($tour_id));

	$tour_id = $tour_obj->get_base_id();
	
	$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
	$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;

	$sql = "SELECT MIN(id) schedule_id
			FROM $table_name_schedule schedule 
			WHERE tour_id=%d AND schedule.max_people > (
				SELECT COUNT(*) ct 
				FROM $table_name_bookings bookings 
				WHERE bookings.tour_schedule_id = schedule.Id AND bookings.tour_date = %s
			) 
			";	
			
	if ($tour_obj->get_type_is_repeated() == 0) {
		$sql .= " AND schedule.start_date = %s ";
	}	

	$schedule_id = $wpdb->get_var($wpdb->prepare($sql, $tour_id, $date, $date));
	
	return $schedule_id;
}

function get_tour_min_price($tour_id, $date, $ignore_meta=false) {

	global $wpdb;
	
	$min_price = 0;
	if (!$ignore_meta)
		$min_price = get_post_meta( (int) $tour_id, '_tour_min_price', true );

	if (empty($min_price)) {
	
		$tour_obj = new byt_tour(intval($tour_id));

		$tour_id = $tour_obj->get_base_id();
		
		$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;

		$sql = "SELECT MIN(schedule.price) 
				FROM $table_name_schedule schedule 
				WHERE tour_id=%d ";	
				
		if ($tour_obj->get_type_is_repeated() == 0) {
			// this tour is a one off and is not repeated. If start date is missed, person cannot participate.
			$sql .= $wpdb->prepare(" AND start_date > %s ", $date);
		} else {
			// daily, weekly, weekdays tours are recurring which means start date is important only in the sense that tour needs to have become valid before we can get min price.
		}

		$sql = $wpdb->prepare($sql, $tour_id);
		$min_price = $wpdb->get_var($sql);
		if (!$min_price)
			$min_price = 0;
		
		sync_tour_min_price($tour_id, $min_price);
	} else {
		$min_price = (float)$min_price;
	}
	
	global $current_currency, $default_currency;
	if ($current_currency && $current_currency != $default_currency)
		$min_price = currency_conversion($min_price, $default_currency, $current_currency);
	
	return $min_price;
}

function sync_tour_min_price($tour_id, $min_price) {
	update_post_meta($tour_id, '_tour_min_price', $min_price);
}