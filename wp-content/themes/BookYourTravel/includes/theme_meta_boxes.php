<?php

$enable_accommodations = of_get_option('enable_accommodations', 1);
$enable_tours = of_get_option('enable_tours', 1);
$enable_car_rentals = of_get_option('enable_car_rentals', 1);
$enable_cruises = of_get_option('enable_cruises', 1);
$enable_reviews = of_get_option('enable_reviews', 1);

$enabled_post_types = array();
$enabled_frontend_content_types = array();
if ($enable_accommodations) {
	$enabled_frontend_content_types[] = array('value' => 'accommodation', 'label' => __('Accommodation', 'bookyourtravel'));
	$enabled_frontend_content_types[] = array('value' => 'room_type', 'label' => __('Room type', 'bookyourtravel'));
	$enabled_frontend_content_types[] = array('value' => 'vacancy', 'label' => __('Vacancy', 'bookyourtravel'));
}

$prefix = 'user_register_';

$user_register_custom_meta_fields = array(
	array( // Post ID select box
		'label'	=> __('Users can front-end submit?', 'bookyourtravel'), // <label>
		'desc'	=> __('Check this box if users registering through this form can use the frontend submit pages to submit content.', 'bookyourtravel'), // description
		'id'	=> $prefix.'can_frontend_submit', // field id and name
		'type'	=> 'checkbox', // type of field
	)
);

$prefix = 'frontend_submit_';

$frontend_submit_custom_meta_fields = array(
	array( // Taxonomy Select box
		'label'	=> __('Content type', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> $prefix.'content_type', // field id and name, needs to be the exact name of the taxonomy
		'type'	=> 'select', // type of field
		'options' => $enabled_frontend_content_types
	)
);

$prefix = 'self_catered_list_';

$self_catered_list_custom_meta_fields = array(
	array( // Taxonomy Select box
		'label'	=> __('Accomodation type', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> 'accommodation_type', // field id and name, needs to be the exact name of the taxonomy
		'type'	=> 'tax_select' // type of field
	),
	array( // Taxonomy Select box
		'label'	=> __('Location', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> $prefix.'location_post_id', // field id and name
		'type'	=> 'post_select', // type of field
		'post_type' => array('location') // post types to display, options are prefixed with their post type
	)
);

$prefix = 'hotel_list_';

$hotel_list_custom_meta_fields = array(
	array( // Taxonomy Select box
		'label'	=> __('Accomodation type', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> 'accommodation_type', // field id and name, needs to be the exact name of the taxonomy
		'type'	=> 'tax_select' // type of field
	),
	array( // Taxonomy Select box
		'label'	=> __('Location', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> $prefix.'location_post_id', // field id and name
		'type'	=> 'post_select', // type of field
		'post_type' => array('location') // post types to display, options are prefixed with their post type
	)
);

$prefix = 'accommodation_list_';

$accommodation_list_custom_meta_fields = array(
	array( // Taxonomy Select box
		'label'	=> __('Accomodation type', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> 'accommodation_type', // field id and name, needs to be the exact name of the taxonomy
		'type'	=> 'tax_select' // type of field
	),
	array( // Taxonomy Select box
		'label'	=> __('Location', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> $prefix.'location_post_id', // field id and name
		'type'	=> 'post_select', // type of field
		'post_type' => array('location') // post types to display, options are prefixed with their post type
	)
);

$prefix = 'cruise_list_';

$cruise_list_custom_meta_fields = array(
	array( // Taxonomy Select box
		'label'	=> __('Cruise type', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> 'cruise_type', // field id and name, needs to be the exact name of the taxonomy
		'type'	=> 'tax_select' // type of field
	)
);

$prefix = 'tour_list_';

$tour_list_custom_meta_fields = array(
	array( // Taxonomy Select box
		'label'	=> __('Tour type', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> 'tour_type', // field id and name, needs to be the exact name of the taxonomy
		'type'	=> 'tax_select' // type of field
	),
	array( // Taxonomy Select box
		'label'	=> __('Location', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> $prefix.'location_post_id', // field id and name
		'type'	=> 'post_select', // type of field
		'post_type' => array('location') // post types to display, options are prefixed with their post type
	)
);

$prefix = 'car_list_';

$car_list_custom_meta_fields = array(
	array( // Taxonomy Select box
		'label'	=> __('Car type', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> 'car_type', // field id and name, needs to be the exact name of the taxonomy
		'type'	=> 'tax_select' // type of field
	)
);

$prefix = 'location_list_';

$location_list_custom_meta_fields = array(
	array( // Taxonomy Select box
		'label'	=> __('Location', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> $prefix.'location_post_id', // field id and name
		'type'	=> 'post_select', // type of field
		'post_type' => array('location') // post types to display, options are prefixed with their post type
	)
);

$prefix = 'tour_';
$tour_custom_meta_fields = array(
	array( // Post ID select box
		'label'	=> __('Is Featured', 'bookyourtravel'), // <label>
		'desc'	=> __('Show in lists where only featured items are shown.', 'bookyourtravel'), // description
		'id'	=> $prefix.'is_featured', // field id and name
		'type'	=> 'checkbox', // type of field
	),
	array( 
		'label'	=> __('Price per group?', 'bookyourtravel'), // <label>
		'desc'	=> __('Is price calculated per group? If not then calculations are done per person.', 'bookyourtravel'), // description
		'id'	=> $prefix.'is_price_per_group', // field id and name
		'type'	=> 'checkbox', // type of field
	),
	array( // Post ID select box
		'label'	=> __('Location', 'bookyourtravel'), // <label>
		'desc'	=> '', // description
		'id'	=> $prefix.'location_post_id', // field id and name
		'type'	=> 'post_select', // type of field
		'post_type' => array('location') // post types to display, options are prefixed with their post type
	),
	array( // Repeatable & Sortable Text inputs
		'label'	=> __('Gallery images', 'bookyourtravel'), // <label>
		'desc'	=> __('A collection of images to be used in slider/gallery on single page', 'bookyourtravel'), // description
		'id'	=> $prefix.'images', // field id and name
		'type'	=> 'repeatable', // type of field
		'sanitizer' => array( // array of sanitizers with matching kets to next array
			'featured' => 'meta_box_santitize_boolean',
			'title' => 'sanitize_text_field',
			'desc' => 'wp_kses_data'
		),
		'repeatable_fields' => array ( // array of fields to be repeated
			array( // Image ID field
				'label'	=> __('Image', 'bookyourtravel'), // <label>
				'id'	=> 'image', // field id and name
				'type'	=> 'image' // type of field
			)
		)
	),
	array(
		'label'	=> __('Map code', 'bookyourtravel'),
		'desc'	=> '',
		'id'	=> $prefix.'map_code',
		'type'	=> 'textarea'
	),
	array( // Taxonomy Select box
		'label'	=> __('Tour type', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> 'tour_type', // field id and name, needs to be the exact name of the taxonomy
		'type'	=> 'tax_select' // type of field
	), 
	array( 
		'label'	=> __('Is for reservation only?', 'bookyourtravel'), // <label>
		'desc'	=> __('If this option is checked, then this particular tour will not be processed via WooCommerce even if WooCommerce is in use.', 'bookyourtravel'), // description
		'id'	=> $prefix.'is_reservation_only', // field id and name
		'type'	=> 'checkbox', // type of field
	),
	array(
		'label'	=> __('Availability extra text', 'bookyourtravel'),
		'desc'	=> __('Extra text shown on availability tab above the book now area.', 'bookyourtravel'),
		'id'	=> $prefix.'availability_text',
		'type'	=> 'textarea'
	),
	array(
		'label'	=> __('Contact email addresses', 'bookyourtravel'),
		'desc'	=> __('Contact email addresses, separate each address with a semi-colon ;', 'bookyourtravel'),
		'id'	=> $prefix.'contact_email',
		'type'	=> 'text'
	)
);

global $default_tour_extra_fields;

$tour_extra_fields = of_get_option('tour_extra_fields');
if (!is_array($tour_extra_fields) || count($tour_extra_fields) == 0)
	$tour_extra_fields = $default_tour_extra_fields;
	
foreach ($tour_extra_fields as $tour_extra_field) {
	$field_is_hidden = isset($tour_extra_field['hide']) ? intval($tour_extra_field['hide']) : 0;
	
	if (!$field_is_hidden) {
		$extra_field = null;
		$field_label = isset($tour_extra_field['label']) ? $tour_extra_field['label'] : '';
		$field_id = isset($tour_extra_field['id']) ? $tour_extra_field['id'] : '';
		$field_type = isset($tour_extra_field['type']) ? $tour_extra_field['type'] :  '';
		if (!empty($field_label) && !empty($field_id) && !empty($field_type)) {
			$extra_field = array(
				'label'	=> $field_label,
				'desc'	=> '',
				'id'	=> $prefix.$field_id,
				'type'	=> $field_type
			);
		}

		if ($extra_field) 
			$tour_custom_meta_fields[] = $extra_field;
	}
}

$transmission_types = array();
$transmission_types[] = array('value' => 'manual', 'label' => __('Manual transmission', 'bookyourtravel'));
$transmission_types[] = array('value' => 'auto', 'label' => __('Auto transmission', 'bookyourtravel'));

$prefix = 'car_rental_';
$car_rental_custom_meta_fields = array(
	array( // Post ID select box
		'label'	=> __('Is Featured', 'bookyourtravel'), // <label>
		'desc'	=> __('Show in lists where only featured items are shown.', 'bookyourtravel'), // description
		'id'	=> $prefix.'is_featured', // field id and name
		'type'	=> 'checkbox', // type of field
	),
	array( // Post ID select box
		'label'	=> __('Location', 'bookyourtravel'), // <label>
		'desc'	=> '', // description
		'id'	=> $prefix.'location_post_id', // field id and name
		'type'	=> 'post_select', // type of field
		'post_type' => array('location') // post types to display, options are prefixed with their post type
	),
	array( // Repeatable & Sortable Text inputs
		'label'	=> 'Gallery images', // <label>
		'desc'	=> 'A collection of images to be used in slider/gallery on single page', // description
		'id'	=> $prefix.'images', // field id and name
		'type'	=> 'repeatable', // type of field
		'sanitizer' => array( // array of sanitizers with matching kets to next array
			'featured' => 'meta_box_santitize_boolean',
			'title' => 'sanitize_text_field',
			'desc' => 'wp_kses_data'
		),
		'repeatable_fields' => array ( // array of fields to be repeated
			array( // Image ID field
				'label'	=> 'Image', // <label>
				'id'	=> 'image', // field id and name
				'type'	=> 'image' // type of field
			)
		)
	),
	array(
		'label'	=> __('Price per day', 'bookyourtravel'),
		'desc'	=> __('What is the car\'s rental price per day?', 'bookyourtravel'),
		'id'	=> $prefix.'price_per_day',
		'type'	=> 'text'
	),
	array(
		'label'	=> __('Contact email addresses', 'bookyourtravel'),
		'desc'	=> __('Contact email addresses, separate each address with a semi-colon ;', 'bookyourtravel'),
		'id'	=> $prefix.'contact_email',
		'type'	=> 'text'
	),
	array(
		'label'	=> __('Number of available cars', 'bookyourtravel'),
		'desc'	=> __('What number of cars are available for rent (used for admin purposes to determine availability)?', 'bookyourtravel'),
		'id'	=> $prefix.'number_of_cars',
		'type'	=> 'text'
	),
	array(
		'label'	=> __('Max count', 'bookyourtravel'),
		'desc'	=> __('How many people are allowed in the car?', 'bookyourtravel'),
		'id'	=> $prefix.'max_count',
		'type'	=> 'slider',
		'min'	=> '1',
		'max'	=> '10',
		'step'	=> '1'
	),
	array(
		'label'	=> __('Minimum age', 'bookyourtravel'),
		'desc'	=> __('What is the minimum age of people in the car?', 'bookyourtravel'),
		'id'	=> $prefix.'min_age',
		'type'	=> 'slider',
		'min'	=> '18',
		'max'	=> '100',
		'step'	=> '1'
	),
	array(
		'label'	=> __('Number of doors', 'bookyourtravel'),
		'desc'	=> __('What is the number of doors the car has?', 'bookyourtravel'),
		'id'	=> $prefix.'number_of_doors',
		'type'	=> 'slider',
		'min'	=> '1',
		'max'	=> '10',
		'step'	=> '1'
	),
	array( 
		'label'	=> __('Unlimited mileage', 'bookyourtravel'), // <label>
		'desc'	=> __('Is there no restriction on mileage covered?', 'bookyourtravel'), // description
		'id'	=> $prefix.'is_unlimited_mileage', // field id and name
		'type'	=> 'checkbox', // type of field
	),
	array( 
		'label'	=> __('Air-conditioning', 'bookyourtravel'), // <label>
		'desc'	=> __('Is there air-conditioning?', 'bookyourtravel'), // description
		'id'	=> $prefix.'is_air_conditioned', // field id and name
		'type'	=> 'checkbox', // type of field
	),
	array( 
		'label'	=> __('Transmission type', 'bookyourtravel'), // <label>
		'desc'	=> __('What is the car\'s transmission type?', 'bookyourtravel'), // description
		'id'	=> $prefix.'transmission_type', // field id and name
		'type'	=> 'select', // type of field
		'options' => $transmission_types
	),
	array( // Taxonomy Select box
		'label'	=> __('Car type', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> 'car_type', // field id and name, needs to be the exact name of the taxonomy
		'type'	=> 'tax_select' // type of field
	),
	array( 
		'label'	=> __('Is for reservation only?', 'bookyourtravel'), // <label>
		'desc'	=> __('If this option is checked, then this particular car rental will not be processed via WooCommerce even if WooCommerce is in use.', 'bookyourtravel'), // description
		'id'	=> $prefix.'is_reservation_only', // field id and name
		'type'	=> 'checkbox', // type of field
	)
);

global $default_car_rental_extra_fields;

$car_rental_extra_fields = of_get_option('car_rental_extra_fields');
if (!is_array($car_rental_extra_fields) || count($car_rental_extra_fields) == 0)
	$car_rental_extra_fields = $default_car_rental_extra_fields;
	
foreach ($car_rental_extra_fields as $car_rental_extra_field) {
	$field_is_hidden = isset($car_rental_extra_field['hide']) ? intval($car_rental_extra_field['hide']) : 0;
	
	if (!$field_is_hidden) {
		$extra_field = null;
		$field_label = isset($car_rental_extra_field['label']) ? $car_rental_extra_field['label'] : '';
		$field_id = isset($car_rental_extra_field['id']) ? $car_rental_extra_field['id'] : '';
		$field_type = isset($car_rental_extra_field['type']) ? $car_rental_extra_field['type'] :  '';
		if (!empty($field_label) && !empty($field_id) && !empty($field_type)) {
			$extra_field = array(
				'label'	=> $field_label,
				'desc'	=> '',
				'id'	=> $prefix.$field_id,
				'type'	=> $field_type
			);
		}

		if ($extra_field) 
			$car_rental_custom_meta_fields[] = $extra_field;
	}
}

$prefix = 'review_';
$review_custom_meta_fields = array(
	array(
		'label'	=> __('Likes', 'bookyourtravel'),
		'desc'	=> __('What the user likes about the accommodation', 'bookyourtravel'),
		'id'	=> $prefix.'likes',
		'type'	=> 'textarea'
	),
	array(
		'label'	=> __('Dislikes', 'bookyourtravel'),
		'desc'	=> __('What the user dislikes about the accommodation', 'bookyourtravel'),
		'id'	=> $prefix.'dislikes',
		'type'	=> 'textarea'
	),
	array( // Post ID select box
		'label'	=> __('Reviewed item', 'bookyourtravel'), // <label>
		'desc'	=> '', // description
		'id'	=>  $prefix.'post_id', // field id and name
		'type'	=> 'post_select', // type of field
		'post_type' => array('accommodation', 'tour', 'cruise') // post types to display, options are prefixed with their post type
	),
	array('label'	=> __('Cleanliness', 'bookyourtravel'),	'desc'	=> __('Cleanliness rating', 'bookyourtravel'), 'id'	=> $prefix.'cleanliness', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
	array('label'	=> __('Comfort', 'bookyourtravel'),	'desc'	=> __('Comfort rating', 'bookyourtravel'), 'id'	=> $prefix.'comfort', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
	array('label'	=> __('Location', 'bookyourtravel'),	'desc'	=> __('Location rating', 'bookyourtravel'), 'id'	=> $prefix.'location', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
	array('label'	=> __('Staff', 'bookyourtravel'),	'desc'	=> __('Staff rating', 'bookyourtravel'), 'id'	=> $prefix.'staff', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
	array('label'	=> __('Services', 'bookyourtravel'),	'desc'	=> __('Services rating', 'bookyourtravel'), 'id'	=> $prefix.'services', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
	array('label'	=> __('Value for money', 'bookyourtravel'),	'desc'	=> __('Value for money rating', 'bookyourtravel'), 'id'	=> $prefix.'value_for_money', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
	array('label'	=> __('Sleep quality', 'bookyourtravel'),	'desc'	=> __('Sleep quality rating', 'bookyourtravel'), 'id'	=> $prefix.'sleep_quality', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
	array('label'	=> __('Overall', 'bookyourtravel'),	'desc'	=> __('Overall rating', 'bookyourtravel'), 'id'	=> $prefix.'overall', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
	array('label'	=> __('Accommodation', 'bookyourtravel'),	'desc'	=> __('Accommodation rating', 'bookyourtravel'), 'id'	=> $prefix.'accommodation', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
	array('label'	=> __('Transport', 'bookyourtravel'),	'desc'	=> __('Transport rating', 'bookyourtravel'), 'id'	=> $prefix.'transport', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
	array('label'	=> __('Meals', 'bookyourtravel'),	'desc'	=> __('Meals rating', 'bookyourtravel'), 'id'	=> $prefix.'meals', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
	array('label'	=> __('Guide', 'bookyourtravel'),	'desc'	=> __('Guide rating', 'bookyourtravel'), 'id'	=> $prefix.'guide', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
	array('label'	=> __('Program accuracy', 'bookyourtravel'),	'desc'	=> __('Program accuracy rating', 'bookyourtravel'), 'id'	=> $prefix.'program_accuracy', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' )
);

$prefix = 'room_type_';
$room_type_custom_meta_fields = array(
	array(
		'label'	=> __('Max adult count', 'bookyourtravel'),
		'desc'	=> __('How many adults are allowed in the room?', 'bookyourtravel'),
		'id'	=> $prefix.'max_count',
		'type'	=> 'slider',
		'min'	=> '1',
		'max'	=> '10',
		'step'	=> '1'
	),
	array(
		'label'	=> __('Max child count', 'bookyourtravel'),
		'desc'	=> __('How many children are allowed in the room?', 'bookyourtravel'),
		'id'	=> $prefix.'max_child_count',
		'type'	=> 'slider',
		'min'	=> '1',
		'max'	=> '10',
		'step'	=> '1'
	),
	array(
		'label'	=> __('Bed size', 'bookyourtravel'),
		'desc'	=> __('How big is/are the beds?', 'bookyourtravel'),
		'id'	=> $prefix.'bed_size',
		'type'	=> 'text'
	),
	array(
		'label'	=> __('Room size', 'bookyourtravel'),
		'desc'	=> __('What is the room size (m2)?', 'bookyourtravel'),
		'id'	=> $prefix.'room_size',
		'type'	=> 'text'
	),
	array(
		'label'	=> __('Room meta information', 'bookyourtravel'),
		'desc'	=> __('What other information applies to this specific room type?', 'bookyourtravel'),
		'id'	=> $prefix.'meta',
		'type'	=> 'text'
	),
	array( // Taxonomy Select box
		'label'	=> __('Facilities', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> 'facility', // field id and name, needs to be the exact name of the taxonomy
		'type'	=> 'tax_checkboxes' // type of field
	),
);

$prefix = 'accommodation_';
$accommodation_custom_meta_fields = array(
	array( // Post ID select box
		'label'	=> __('Is Featured', 'bookyourtravel'), // <label>
		'desc'	=> __('Show in lists where only featured items are shown.', 'bookyourtravel'), // description
		'id'	=> $prefix.'is_featured', // field id and name
		'type'	=> 'checkbox', // type of field
	),
	array( // Post ID select box
		'label'	=> __('Is Self Catered', 'bookyourtravel'), // <label>
		'desc'	=> '', // description
		'id'	=> $prefix.'is_self_catered', // field id and name
		'type'	=> 'checkbox', // type of field
	),
	array(
		'label'	=> __('Max adult count', 'bookyourtravel'),
		'desc'	=> __('How many adults are allowed in the accommodation?', 'bookyourtravel'),
		'id'	=> $prefix.'max_count',
		'type'	=> 'slider',
		'min'	=> '1',
		'max'	=> '10',
		'step'	=> '1'
	),
	array(
		'label'	=> __('Max child count', 'bookyourtravel'),
		'desc'	=> __('How many children are allowed in the accommodation?', 'bookyourtravel'),
		'id'	=> $prefix.'max_child_count',
		'type'	=> 'slider',
		'min'	=> '1',
		'max'	=> '10',
		'step'	=> '1'
	),
	array( // Post ID select box
		'label'	=> __('Room types', 'bookyourtravel'), // <label>
		'desc'	=> '', // description
		'id'	=>  'room_types', // field id and name
		'type'	=> 'post_checkboxes', // type of field
		'post_type' => array('room_type') // post types to display, options are prefixed with their post type
	),
	array( 
		'label'	=> __('Price per person?', 'bookyourtravel'), // <label>
		'desc'	=> __('Is price calculated per person (adult, child)? If not then calculations are done per room / per apartment.', 'bookyourtravel'), // description
		'id'	=> $prefix.'is_price_per_person', // field id and name
		'type'	=> 'checkbox', // type of field
	),
	array(
		'label'	=> __('Count children stay free', 'bookyourtravel'),
		'desc'	=> __('How many kids stay free before we charge a fee?', 'bookyourtravel'),
		'id'	=> $prefix.'count_children_stay_free',
		'type'	=> 'slider',
		'min'	=> '1',
		'max'	=> '5',
		'step'	=> '1'
	),
	array( 
		'label'	=> __('Is for reservation only?', 'bookyourtravel'), // <label>
		'desc'	=> __('If this option is checked, then this particular accommodation will not be processed via WooCommerce even if WooCommerce is in use.', 'bookyourtravel'), // description
		'id'	=> $prefix.'is_reservation_only', // field id and name
		'type'	=> 'checkbox', // type of field
	),
	array(
		'label'	=> __('Star count', 'bookyourtravel'),
		'desc'	=> '',
		'id'	=> $prefix.'star_count',
		'type'	=> 'slider',
		'min'	=> '1',
		'max'	=> '5',
		'step'	=> '1'
	),
	array( // Taxonomy Select box
		'label'	=> __('Facilities', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> 'facility', // field id and name, needs to be the exact name of the taxonomy
		'type'	=> 'tax_checkboxes' // type of field
	),
	array( // Taxonomy Select box
		'label'	=> __('Accommodation type', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> 'accommodation_type', // field id and name, needs to be the exact name of the taxonomy
		'type'	=> 'tax_select' // type of field
	),
	array( // Post ID select box
		'label'	=> __('Location', 'bookyourtravel'), // <label>
		'desc'	=> '', // description
		'id'	=> $prefix.'location_post_id', // field id and name
		'type'	=> 'post_select', // type of field
		'post_type' => array('location') // post types to display, options are prefixed with their post type
	),
	array( // Repeatable & Sortable Text inputs
		'label'	=> 'Gallery images', // <label>
		'desc'	=> 'A collection of images to be used in slider/gallery on single page', // description
		'id'	=> $prefix.'images', // field id and name
		'type'	=> 'repeatable', // type of field
		'sanitizer' => array( // array of sanitizers with matching kets to next array
			'featured' => 'meta_box_santitize_boolean',
			'title' => 'sanitize_text_field',
			'desc' => 'wp_kses_data'
		),
		'repeatable_fields' => array ( // array of fields to be repeated
			array( // Image ID field
				'label'	=> 'Image', // <label>
				'id'	=> 'image', // field id and name
				'type'	=> 'image' // type of field
			)
		)
	),
	array(
		'label'	=> __('Address', 'bookyourtravel'),
		'desc'	=> '',
		'id'	=> $prefix.'address',
		'type'	=> 'text'
	),
	array(
		'label'	=> __('Website address', 'bookyourtravel'),
		'desc'	=> '',
		'id'	=> $prefix.'website_address',
		'type'	=> 'text'
	),
	array(
		'label'	=> __('Availability extra text', 'bookyourtravel'),
		'desc'	=> __('Extra text shown on availability tab above the book now area.', 'bookyourtravel'),
		'id'	=> $prefix.'availability_text',
		'type'	=> 'textarea'
	),
	array(
		'label'	=> __('Contact email addresses', 'bookyourtravel'),
		'desc'	=> __('Contact email addresses, separate each address with a semi-colon ;', 'bookyourtravel'),
		'id'	=> $prefix.'contact_email',
		'type'	=> 'text'
	),
	array(
		'label'	=> __('Latitude coordinates', 'bookyourtravel'),
		'desc'	=> __('Latitude coordinates for use with google map (leave blank to not use)', 'bookyourtravel'),
		'id'	=> $prefix.'latitude',
		'type'	=> 'text'
	),
	array(
		'label'	=> __('Longitude coordinates', 'bookyourtravel'),
		'desc'	=> __('Longitude coordinates for use with google map (leave blank to not use)', 'bookyourtravel'),
		'id'	=> $prefix.'longitude',
		'type'	=> 'text'
	),	
);

global $default_accommodation_extra_fields;

$accommodation_extra_fields = of_get_option('accommodation_extra_fields');
if (!is_array($accommodation_extra_fields) || count($accommodation_extra_fields) == 0)
	$accommodation_extra_fields = $default_accommodation_extra_fields;
	
foreach ($accommodation_extra_fields as $accommodation_extra_field) {
	$field_is_hidden = isset($accommodation_extra_field['hide']) ? intval($accommodation_extra_field['hide']) : 0;
	
	if (!$field_is_hidden) {
		$extra_field = null;
		$field_label = isset($accommodation_extra_field['label']) ? $accommodation_extra_field['label'] : '';
		$field_id = isset($accommodation_extra_field['id']) ? $accommodation_extra_field['id'] : '';
		$field_type = isset($accommodation_extra_field['type']) ? $accommodation_extra_field['type'] :  '';
		if (!empty($field_label) && !empty($field_id) && !empty($field_type)) {
			$extra_field = array(
				'label'	=> $field_label,
				'desc'	=> '',
				'id'	=> $prefix.$field_id,
				'type'	=> $field_type
			);
		}

		if ($extra_field) 
			$accommodation_custom_meta_fields[] = $extra_field;
	}
}

$prefix = 'cruise_';
$cruise_custom_meta_fields = array(
	array( // Post ID select box
		'label'	=> __('Is Featured', 'bookyourtravel'), // <label>
		'desc'	=> __('Show in lists where only featured items are shown.', 'bookyourtravel'), // description
		'id'	=> $prefix.'is_featured', // field id and name
		'type'	=> 'checkbox', // type of field
	),
	array( // Post ID select box
		'label'	=> __('Cabin types', 'bookyourtravel'), // <label>
		'desc'	=> '', // description
		'id'	=> 'cabin_types', // field id and name
		'type'	=> 'post_checkboxes', // type of field
		'post_type' => array('cabin_type') // post types to display, options are prefixed with their post type
	),
	array( 
		'label'	=> __('Price per person?', 'bookyourtravel'), // <label>
		'desc'	=> __('Is price calculated per person (adult, child)? If not then calculations are done per cabin.', 'bookyourtravel'), // description
		'id'	=> $prefix.'is_price_per_person', // field id and name
		'type'	=> 'checkbox', // type of field
	),
	array(
		'label'	=> __('Count children stay free', 'bookyourtravel'),
		'desc'	=> __('How many kids stay free before we charge a fee?', 'bookyourtravel'),
		'id'	=> $prefix.'count_children_stay_free',
		'type'	=> 'slider',
		'min'	=> '1',
		'max'	=> '5',
		'step'	=> '1'
	),
	array( 
		'label'	=> __('Is for reservation only?', 'bookyourtravel'), // <label>
		'desc'	=> __('If this option is checked, then this particular cruise will not be processed via WooCommerce even if WooCommerce is in use.', 'bookyourtravel'), // description
		'id'	=> $prefix.'is_reservation_only', // field id and name
		'type'	=> 'checkbox', // type of field
	),
	array( // Taxonomy Select box
		'label'	=> __('Facilities', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> 'facility', // field id and name, needs to be the exact name of the taxonomy
		'type'	=> 'tax_checkboxes' // type of field
	),
	array( // Taxonomy Select box
		'label'	=> __('Cruise type', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> 'cruise_type', // field id and name, needs to be the exact name of the taxonomy
		'type'	=> 'tax_select' // type of field
	),
	array( // Repeatable & Sortable Text inputs
		'label'	=> 'Gallery images', // <label>
		'desc'	=> 'A collection of images to be used in slider/gallery on single page', // description
		'id'	=> $prefix.'images', // field id and name
		'type'	=> 'repeatable', // type of field
		'sanitizer' => array( // array of sanitizers with matching kets to next array
			'featured' => 'meta_box_santitize_boolean',
			'title' => 'sanitize_text_field',
			'desc' => 'wp_kses_data'
		),
		'repeatable_fields' => array ( // array of fields to be repeated
			array( // Image ID field
				'label'	=> 'Image', // <label>
				'id'	=> 'image', // field id and name
				'type'	=> 'image' // type of field
			)
		)
	),
	array(
		'label'	=> __('Availability extra text', 'bookyourtravel'),
		'desc'	=> __('Extra text shown on availability tab above the book now area.', 'bookyourtravel'),
		'id'	=> $prefix.'availability_text',
		'type'	=> 'textarea'
	),
	array(
		'label'	=> __('Contact email addresses', 'bookyourtravel'),
		'desc'	=> __('Contact email addresses, separate each address with a semi-colon ;', 'bookyourtravel'),
		'id'	=> $prefix.'contact_email',
		'type'	=> 'text'
	),
);

global $default_cruise_extra_fields;

$cruise_extra_fields = of_get_option('cruise_extra_fields');
if (!is_array($cruise_extra_fields) || count($cruise_extra_fields) == 0)
	$cruise_extra_fields = $default_cruise_extra_fields;
	
foreach ($cruise_extra_fields as $cruise_extra_field) {
	$field_is_hidden = isset($cruise_extra_field['hide']) ? intval($cruise_extra_field['hide']) : 0;
	
	if (!$field_is_hidden) {
		$extra_field = null;
		$field_label = isset($cruise_extra_field['label']) ? $cruise_extra_field['label'] : '';
		$field_id = isset($cruise_extra_field['id']) ? $cruise_extra_field['id'] : '';
		$field_type = isset($cruise_extra_field['type']) ? $cruise_extra_field['type'] :  '';
		if (!empty($field_label) && !empty($field_id) && !empty($field_type)) {
			$extra_field = array(
				'label'	=> $field_label,
				'desc'	=> '',
				'id'	=> $prefix.$field_id,
				'type'	=> $field_type
			);
		}

		if ($extra_field) 
			$cruise_custom_meta_fields[] = $extra_field;
	}
}

$prefix = 'cabin_type_';
$cabin_type_custom_meta_fields = array(
	array(
		'label'	=> __('Max adult count', 'bookyourtravel'),
		'desc'	=> __('How many adults are allowed in the cabin?', 'bookyourtravel'),
		'id'	=> $prefix.'max_count',
		'type'	=> 'slider',
		'min'	=> '1',
		'max'	=> '10',
		'step'	=> '1'
	),
	array(
		'label'	=> __('Max child count', 'bookyourtravel'),
		'desc'	=> __('How many children are allowed in the cabin?', 'bookyourtravel'),
		'id'	=> $prefix.'max_child_count',
		'type'	=> 'slider',
		'min'	=> '1',
		'max'	=> '10',
		'step'	=> '1'
	),
	array(
		'label'	=> __('Bed size', 'bookyourtravel'),
		'desc'	=> __('How big is/are the beds?', 'bookyourtravel'),
		'id'	=> $prefix.'bed_size',
		'type'	=> 'text'
	),
	array(
		'label'	=> __('Cabin size', 'bookyourtravel'),
		'desc'	=> __('What is the cabin size (m2)?', 'bookyourtravel'),
		'id'	=> $prefix.'room_size',
		'type'	=> 'text'
	),
	array(
		'label'	=> __('Cabin meta information', 'bookyourtravel'),
		'desc'	=> __('What other information applies to this specific cabin type?', 'bookyourtravel'),
		'id'	=> $prefix.'meta',
		'type'	=> 'text'
	),
	array( // Taxonomy Select box
		'label'	=> __('Facilities', 'bookyourtravel'), // <label>
		// the description is created in the callback function with a link to Manage the taxonomy terms
		'id'	=> 'facility', // field id and name, needs to be the exact name of the taxonomy
		'type'	=> 'tax_checkboxes' // type of field
	),
);

$prefix = 'location_';
$location_custom_meta_fields = array(
	array( // Post ID select box
		'label'	=> __('Is Featured', 'bookyourtravel'), // <label>
		'desc'	=> __('Show in lists where only featured items are shown.', 'bookyourtravel'), // description
		'id'	=> $prefix.'is_featured', // field id and name
		'type'	=> 'checkbox', // type of field
	),
	array(
		'label'	=> __('Country', 'bookyourtravel'),
		'desc'	=> __('Country name', 'bookyourtravel'),
		'id'	=> $prefix.'country',
		'type'	=> 'text'
	),
	array( // Repeatable & Sortable Text inputs
		'label'	=> __('Gallery images', 'bookyourtravel'), // <label>
		'desc'	=> __('A collection of images to be used in slider/gallery on single page', 'bookyourtravel'), // description
		'id'	=> $prefix.'images', // field id and name
		'type'	=> 'repeatable', // type of field
		'sanitizer' => array( // array of sanitizers with matching kets to next array
			'featured' => 'meta_box_santitize_boolean',
			'title' => 'sanitize_text_field',
			'desc' => 'wp_kses_data'
		),
		'repeatable_fields' => array ( // array of fields to be repeated
			array( // Image ID field
				'label'	=> __('Image', 'bookyourtravel'), // <label>
				'id'	=> 'image', // field id and name
				'type'	=> 'image' // type of field
			)
		)
	),
);

global $default_location_extra_fields;

$location_extra_fields = of_get_option('location_extra_fields');
if (!is_array($location_extra_fields) || count($location_extra_fields) == 0)
	$location_extra_fields = $default_location_extra_fields;
	
foreach ($location_extra_fields as $location_extra_field) {
	$field_is_hidden = isset($location_extra_field['hide']) ? intval($location_extra_field['hide']) : 0;
	
	if (!$field_is_hidden) {
		$extra_field = null;
		$field_label = isset($location_extra_field['label']) ? $location_extra_field['label'] : '';
		$field_id = isset($location_extra_field['id']) ? $location_extra_field['id'] : '';
		$field_type = isset($location_extra_field['type']) ? $location_extra_field['type'] :  '';
		if (!empty($field_label) && !empty($field_id) && !empty($field_type)) {
			$extra_field = array(
				'label'	=> $field_label,
				'desc'	=> '',
				'id'	=> $prefix.$field_id,
				'type'	=> $field_type
			);
		}

		if ($extra_field) 
			$location_custom_meta_fields[] = $extra_field;
	}
}

add_action( 'admin_init', 'location_admin_init' );
add_action( 'admin_init', 'location_list_admin_init');

function location_admin_init() {
	global $location_custom_meta_fields;
	new custom_add_meta_box( 'location_custom_meta_fields', 'Extra information', $location_custom_meta_fields, 'location', true );
}


if ($enable_accommodations) {
	add_action( 'admin_init', 'accommodation_admin_init' );
}

if ($enable_tours) {
	add_action( 'admin_init', 'tour_admin_init' );
}

if ($enable_car_rentals) {
	add_action( 'admin_init', 'car_rental_admin_init' );
}

if ($enable_cruises) {
	add_action( 'admin_init', 'cruise_admin_init' );
}

if ($enable_reviews) {
	add_action( 'admin_init', 'review_admin_init' );
}

add_action( 'admin_init', 'frontend_submit_admin_init' );
add_action( 'admin_init', 'user_register_admin_init' );

function user_register_admin_init() {
	global $user_register_meta_box, $user_register_custom_meta_fields;

	$user_register_meta_box = new custom_add_meta_box( 'user_register_custom_meta_fields', __('Extra information', 'bookyourtravel'), $user_register_custom_meta_fields, 'page' );		
	remove_action( 'add_meta_boxes', array( $user_register_meta_box, 'add_box' ) );
	add_action('add_meta_boxes', 'byt_user_register_mf_add_boxes');
}

function byt_user_register_mf_add_boxes() {
	global $post, $user_register_meta_box;
	$template_file = get_post_meta($post->ID,'_wp_page_template',true);
	if ($template_file == 'page-user-register.php') {
		add_meta_box( $user_register_meta_box->id, $user_register_meta_box->title, array( $user_register_meta_box, 'meta_box_callback' ), 'page', 'normal', 'high' );
	}
}

function frontend_submit_admin_init() {
	global $frontend_submit_meta_box, $frontend_submit_custom_meta_fields;

	$frontend_submit_meta_box = new custom_add_meta_box( 'frontend_submit_custom_meta_fields', __('Extra information', 'bookyourtravel'), $frontend_submit_custom_meta_fields, 'page' );		
	remove_action( 'add_meta_boxes', array( $frontend_submit_meta_box, 'add_box' ) );
	add_action('add_meta_boxes', 'byt_frontend_submit_mf_add_boxes');
}

function byt_frontend_submit_mf_add_boxes() {
	global $post, $frontend_submit_meta_box;
	$template_file = get_post_meta($post->ID,'_wp_page_template',true);
	if ($template_file == 'page-user-submit-content.php') {
		add_meta_box( $frontend_submit_meta_box->id, $frontend_submit_meta_box->title, array( $frontend_submit_meta_box, 'meta_box_callback' ), 'page', 'normal', 'high' );
	}
}

function cruise_admin_init() {
	global $cruise_custom_meta_fields, $cruise_list_meta_box, $cruise_list_custom_meta_fields, $cabin_type_custom_meta_fields;

	new custom_add_meta_box( 'cruise_custom_meta_fields', __('Extra information', 'bookyourtravel'), $cruise_custom_meta_fields, 'cruise' );
	new custom_add_meta_box( 'cabin_type_custom_meta_fields', __('Extra information', 'bookyourtravel'), $cabin_type_custom_meta_fields, 'cabin_type' );

	$cruise_list_meta_box = new custom_add_meta_box( 'cruise_list_custom_meta_fields', __('Extra information', 'bookyourtravel'), $cruise_list_custom_meta_fields, 'page' );		
	remove_action( 'add_meta_boxes', array( $cruise_list_meta_box, 'add_box' ) );
	add_action('add_meta_boxes', 'byt_cruise_type_archive_mf_add_boxes');
}

function byt_cruise_type_archive_mf_add_boxes() {
	global $post, $cruise_list_meta_box;
	$template_file = get_post_meta($post->ID,'_wp_page_template',true);
	if ($template_file == 'page-cruise-list.php') {
		add_meta_box( $cruise_list_meta_box->id, $cruise_list_meta_box->title, array( $cruise_list_meta_box, 'meta_box_callback' ), 'page', 'normal', 'high' );
	}
}

function tour_admin_init() {
	global $tour_custom_meta_fields;
	new custom_add_meta_box( 'tour_custom_meta_fields', __('Extra information', 'bookyourtravel'), $tour_custom_meta_fields, 'tour' );
	
	global $tour_list_meta_box, $tour_list_custom_meta_fields;
	$tour_list_meta_box = new custom_add_meta_box( 'tour_list_custom_meta_fields', __('Extra information', 'bookyourtravel'), $tour_list_custom_meta_fields, 'page' );	
	remove_action( 'add_meta_boxes', array( $tour_list_meta_box, 'add_box' ) );
	add_action('add_meta_boxes', 'byt_tour_type_archive_mf_add_boxes');
}

function byt_tour_type_archive_mf_add_boxes() {
	global $post, $tour_list_meta_box;
	$template_file = get_post_meta($post->ID,'_wp_page_template',true);
	if ($template_file == 'page-tour-list.php') {
		add_meta_box( $tour_list_meta_box->id, $tour_list_meta_box->title, array( $tour_list_meta_box, 'meta_box_callback' ), 'page', 'normal', 'high' );
	}
}

function car_rental_admin_init() {
	global $car_rental_custom_meta_fields;
	new custom_add_meta_box( 'car_rental_custom_meta_fields', __('Extra information', 'bookyourtravel'), $car_rental_custom_meta_fields, 'car_rental' );	
	
	global $car_list_meta_box, $car_list_custom_meta_fields;
	$car_list_meta_box = new custom_add_meta_box( 'car_list_custom_meta_fields', __('Extra information', 'bookyourtravel'), $car_list_custom_meta_fields, 'page' );	
	remove_action( 'add_meta_boxes', array( $car_list_meta_box, 'add_box' ) );
	add_action('add_meta_boxes', 'byt_car_type_archive_mf_add_boxes');
}

function byt_car_type_archive_mf_add_boxes() {
	global $post, $car_list_meta_box;
	$template_file = get_post_meta($post->ID,'_wp_page_template',true);
	if ($template_file == 'page-car_rental-list.php') {
		add_meta_box( $car_list_meta_box->id, $car_list_meta_box->title, array( $car_list_meta_box, 'meta_box_callback' ), 'page', 'normal', 'high' );
	}
}

function location_list_admin_init() {
	global $location_list_meta_box, $location_list_custom_meta_fields;
	$location_list_meta_box = new custom_add_meta_box( 'location_list_custom_meta_fields', __('Extra information', 'bookyourtravel'), $location_list_custom_meta_fields, 'page' );	
	remove_action( 'add_meta_boxes', array( $location_list_meta_box, 'add_box' ) );
	add_action('add_meta_boxes', 'byt_location_list_mf_add_boxes');
}

function byt_location_list_mf_add_boxes() {
	global $post, $location_list_meta_box;
	$template_file = get_post_meta($post->ID,'_wp_page_template',true);
	if ($template_file == 'page-location-list.php') {
		add_meta_box( $location_list_meta_box->id, $location_list_meta_box->title, array( $location_list_meta_box, 'meta_box_callback' ), 'page', 'normal', 'high' );
	}
}
	
function review_admin_init() {
	global $review_custom_meta_fields;
	new custom_add_meta_box( 'review_custom_meta_fields', __('Extra information', 'bookyourtravel'), $review_custom_meta_fields, 'review' );
}
	
function accommodation_admin_init() {
	
	global $accommodation_custom_meta_fields, $room_type_custom_meta_fields, $review_custom_meta_fields, $accommodation_list_custom_meta_fields, $self_catered_list_custom_meta_fields, $hotel_list_custom_meta_fields;
	new custom_add_meta_box( 'accommodation_custom_meta_fields', __('Extra information', 'bookyourtravel'), $accommodation_custom_meta_fields, 'accommodation' );
	new custom_add_meta_box( 'room_type_custom_meta_fields', __('Extra information', 'bookyourtravel'), $room_type_custom_meta_fields, 'room_type' );

	global $accommodation_list_meta_box;
	$accommodation_list_meta_box = new custom_add_meta_box( 'accommodation_list_custom_meta_fields', __('Extra information', 'bookyourtravel'), $accommodation_list_custom_meta_fields, 'page' );	
	remove_action( 'add_meta_boxes', array( $accommodation_list_meta_box, 'add_box' ) );
	add_action('add_meta_boxes', 'byt_accommodation_list_mf_add_boxes');
	
	global $self_catered_list_meta_box;
	$self_catered_list_meta_box = new custom_add_meta_box( 'self_catered_list_custom_meta_fields', __('Extra information', 'bookyourtravel'), $self_catered_list_custom_meta_fields, 'page' );	
	remove_action( 'add_meta_boxes', array( $self_catered_list_meta_box, 'add_box' ) );
	add_action('add_meta_boxes', 'byt_self_catered_list_mf_add_boxes');

	global $hotel_list_meta_box;
	$hotel_list_meta_box = new custom_add_meta_box( 'hotel_list_custom_meta_fields', __('Extra information', 'bookyourtravel'), $hotel_list_custom_meta_fields, 'page' );	
	remove_action( 'add_meta_boxes', array( $hotel_list_meta_box, 'add_box' ) );
	add_action('add_meta_boxes', 'byt_hotel_list_mf_add_boxes');
}

function byt_accommodation_list_mf_add_boxes() {
	global $post, $accommodation_list_meta_box;
	$template_file = get_post_meta($post->ID,'_wp_page_template',true);
	if ($template_file == 'page-accommodation-list.php') {
		add_meta_box( $accommodation_list_meta_box->id, $accommodation_list_meta_box->title, array( $accommodation_list_meta_box, 'meta_box_callback' ), 'page', 'normal', 'high' );
	}
}

function byt_self_catered_list_mf_add_boxes() {
	global $post, $self_catered_list_meta_box;
	$template_file = get_post_meta($post->ID,'_wp_page_template',true);
	if ($template_file == 'page-self_catered-list.php') {
		add_meta_box( $self_catered_list_meta_box->id, $self_catered_list_meta_box->title, array( $self_catered_list_meta_box, 'meta_box_callback' ), 'page', 'normal', 'high' );
	}
}

function byt_hotel_list_mf_add_boxes() {
	global $post, $hotel_list_meta_box;
	$template_file = get_post_meta($post->ID,'_wp_page_template',true);
	if ($template_file == 'page-hotel-list.php') {
		add_meta_box( $hotel_list_meta_box->id, $hotel_list_meta_box->title, array( $hotel_list_meta_box, 'meta_box_callback' ), 'page', 'normal', 'high' );
	}
}

