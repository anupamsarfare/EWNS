<?php

add_action( 'wp_ajax_settings_ajax_save_password', 'settings_ajax_save_password' );
add_action( 'wp_ajax_settings_ajax_save_email', 'settings_ajax_save_email' );
add_action( 'wp_ajax_settings_ajax_save_last_name', 'settings_ajax_save_last_name' );
add_action( 'wp_ajax_settings_ajax_save_first_name', 'settings_ajax_save_first_name' );

global $enc_key;
$enc_key = get_bloginfo();

function contact_encrypt($string, $key) {
	return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
}
function contact_decrypt($encrypted, $key) {
	return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
}

function settings_ajax_save_password() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
			$user_id = wp_kses($_REQUEST['userId'], '');	
			$oldPassword = wp_kses($_REQUEST['oldPassword'], '');
			$password = wp_kses($_REQUEST['password'], '');
			
			$user = get_user_by( 'id', $user_id );
			if ( $user && wp_check_password( $oldPassword, $user->data->user_pass, $user->ID) )
			{
				// ok
				echo wp_update_user( array ( 'ID' => $user_id, 'user_pass' => $password ) ) ;
			} else {
				
			}
		}
	}
	
	// Always die in functions echoing ajax content
	die();
}

function settings_ajax_save_email() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
			$email = wp_kses($_REQUEST['email'], '');
			$user_id = wp_kses($_REQUEST['userId'], '');	
			echo wp_update_user( array ( 'ID' => $user_id, 'user_email' => $email ) ) ;
		}
	}
	
	// Always die in functions echoing ajax content
	die();
}

function settings_ajax_save_last_name() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
			$lastName = wp_kses($_REQUEST['lastName'], '');
			$user_id = wp_kses($_REQUEST['userId'], '');	
			echo wp_update_user( array ( 'ID' => $user_id, 'last_name' => $lastName ) ) ;
		}
	}
	
	// Always die in functions echoing ajax content
	die();
}

function settings_ajax_save_first_name() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
			$firstName = wp_kses($_REQUEST['firstName'], '');
			$user_id = wp_kses($_REQUEST['userId'], '');	
			echo wp_update_user( array ( 'ID' => $user_id, 'first_name' => $firstName ) ) ;
		}
	}
	
	// Always die in functions echoing ajax content
	die();
}

add_action( 'wp_ajax_hotel_term_search_request', 'hotel_term_search_request');
add_action( 'wp_ajax_nopriv_hotel_term_search_request', 'hotel_term_search_request');
function hotel_term_search_request() {
	$nonce = $_REQUEST['nonce'];
	$term = wp_kses($_REQUEST['q'], '');
	
	if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
	
		$results = search_content_and_location_by_term($term, 'accommodation', 10);
		
		$output = '';
		foreach ($results as $result) {
			$output .= $result->post_title . ';';
		}
		echo $output;
		
 	}

	die;
}

add_action( 'wp_ajax_self_catered_term_search_request', 'self_catered_term_search_request');
add_action( 'wp_ajax_nopriv_self_catered_term_search_request', 'self_catered_term_search_request');
function self_catered_term_search_request() {
	$nonce = $_REQUEST['nonce'];
	$term = wp_kses($_REQUEST['q'], '');
	
	if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
	
		$results = search_content_and_location_by_term($term, 'accommodation', 10);
		
		$output = '';
		foreach ($results as $result) {
			$output .= $result->post_title . ';';
		}
		echo $output;
	}

	die;
}

add_action( 'wp_ajax_car_rental_term_search_request', 'car_rental_term_search_request');
add_action( 'wp_ajax_nopriv_car_rental_term_search_request', 'car_rental_term_search_request');
function car_rental_term_search_request() {
	$nonce = $_REQUEST['nonce'];
	$term = wp_kses($_REQUEST['q'], '');
	
	if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
	
		$results = search_content_and_location_by_term($term, 'car_rental', 10);
		
		$output = '';
		foreach ($results as $result) {
			$output .= $result->post_title . ';';
		}
		echo $output;
	}

	die;
}

add_action( 'wp_ajax_tour_term_search_request', 'tour_term_search_request');
add_action( 'wp_ajax_nopriv_tour_term_search_request', 'tour_term_search_request');
function tour_term_search_request() {
	$nonce = $_REQUEST['nonce'];
	$term = wp_kses($_REQUEST['q'], '');
	
	if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
	
		$results = search_content_and_location_by_term($term, 'tour', 10);
		
		$output = '';
		foreach ($results as $result) {
			$output .= $result->post_title . ';';
		}
		echo $output;
	}

	die;
}

add_action( 'wp_ajax_car_rental_booked_dates_request', 'car_rental_booked_dates_request');
add_action( 'wp_ajax_nopriv_car_rental_booked_dates_request', 'car_rental_booked_dates_request');
function car_rental_booked_dates_request() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

			$car_rental_id = wp_kses($_REQUEST['car_rental_id'], '');	
			$month = wp_kses($_REQUEST['month'], '');	
			$year = wp_kses($_REQUEST['year'], '');	
		
			if ($car_rental_id > 0) {
				
				$booked_dates = car_rental_get_booked_days($car_rental_id, $month, $year);
				echo json_encode($booked_dates);
			}
		}
	}
	
	die();
}

add_action( 'wp_ajax_accommodation_available_start_dates_request', 'accommodation_available_start_dates_request');
add_action( 'wp_ajax_nopriv_accommodation_available_start_dates_request', 'accommodation_available_start_dates_request');
function accommodation_available_start_dates_request() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

			$accommodation_id = wp_kses($_REQUEST['accommodationId'], '');	
			$room_type_id = wp_kses($_REQUEST['roomTypeId'], '');	
			$month = wp_kses($_REQUEST['month'], '');	
			$year = wp_kses($_REQUEST['year'], '');	
		
			if ($accommodation_id > 0) {
				
				$available_dates = list_accommodation_vacancy_start_dates($accommodation_id, $room_type_id, $month, $year);
				echo json_encode($available_dates);
			}
		}
	}
	
	die();
}

add_action( 'wp_ajax_accommodation_available_end_dates_request', 'accommodation_available_end_dates_request');
add_action( 'wp_ajax_nopriv_accommodation_available_end_dates_request', 'accommodation_available_end_dates_request');
function accommodation_available_end_dates_request() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

			$accommodation_id = wp_kses($_REQUEST['accommodationId'], '');	
			$room_type_id = wp_kses($_REQUEST['roomTypeId'], '');	
			$year = wp_kses($_REQUEST['year'], '');
			$month = wp_kses($_REQUEST['month'], '');	
			$day = wp_kses($_REQUEST['day'], '');	
		
			if ($accommodation_id > 0) {				
				$available_dates = list_accommodation_vacancy_end_dates($accommodation_id, $room_type_id, $month, $year, $day);
				echo json_encode($available_dates);
			}
		}
	}
	
	die();
}

add_action( 'wp_ajax_tour_schedule_dates_request', 'tour_schedule_dates_request');
add_action( 'wp_ajax_nopriv_tour_schedule_dates_request', 'tour_schedule_dates_request');
function tour_schedule_dates_request() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

			$tour_id = wp_kses($_REQUEST['tourId'], '');	
			$month = wp_kses($_REQUEST['month'], '');	
			$year = wp_kses($_REQUEST['year'], '');	
			$day = wp_kses($_REQUEST['day'], '');
			$hour = 0;
			$minute = 0;
			
			$date_from = date('Y-m-d', strtotime("$year-$month-$day $hour:$minute"));
		
			if ($tour_id > 0) {
				$tour_obj = new byt_tour(intval($tour_id));
				$schedule_entries = list_available_tour_schedule_entries($tour_id, $date_from, $year, $month, $tour_obj->get_type_is_repeated(), $tour_obj->get_type_day_of_week_index());				
				echo json_encode($schedule_entries);
			}
		}
	}
	
	die();
}

add_action( 'wp_ajax_cruise_schedule_dates_request', 'cruise_schedule_dates_request');
add_action( 'wp_ajax_nopriv_cruise_schedule_dates_request', 'cruise_schedule_dates_request');
function cruise_schedule_dates_request() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

			$cruise_id = wp_kses($_REQUEST['cruiseId'], '');	
			$cabin_type_id = wp_kses($_REQUEST['cabinTypeId'], '');
			$month = wp_kses($_REQUEST['month'], '');	
			$year = wp_kses($_REQUEST['year'], '');	
			$day = wp_kses($_REQUEST['day'], '');
			$hour = 0;
			$minute = 0;
			
			$date_from = date('Y-m-d', strtotime("$year-$month-$day $hour:$minute"));
		
			if ($cruise_id > 0) {
				$cruise_obj = new byt_cruise(intval($cruise_id));
				$schedule_entries = list_available_cruise_schedule_entries($cruise_id, $cabin_type_id, $date_from, $year, $month, $cruise_obj->get_type_is_repeated(), $cruise_obj->get_type_day_of_week_index());				
				echo json_encode($schedule_entries);
			}
		}
	}
	
	die();
}

add_action( 'wp_ajax_tour_is_reservation_only_request', 'tour_is_reservation_only_request');
add_action( 'wp_ajax_nopriv_tour_is_reservation_only_request', 'tour_is_reservation_only_request');
function tour_is_reservation_only_request() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

			$tour_id = wp_kses($_REQUEST['tour_id'], '');	
			$is_reservation_only = get_post_meta( $tour_id, 'tour_is_reservation_only', true );
			$is_reservation_only = isset($is_reservation_only) ? $is_reservation_only : 0;
			
			echo $is_reservation_only;
		}
	}
	
	die();
}

add_action( 'wp_ajax_cruise_is_reservation_only_request', 'cruise_is_reservation_only_request');
add_action( 'wp_ajax_nopriv_cruise_is_reservation_only_request', 'cruise_is_reservation_only_request');
function cruise_is_reservation_only_request() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

			$cruise_id = wp_kses($_REQUEST['cruise_id'], '');	
			$is_reservation_only = get_post_meta( $cruise_id, 'cruise_is_reservation_only', true );
			$is_reservation_only = isset($is_reservation_only) ? $is_reservation_only : 0;
			
			echo $is_reservation_only;
		}
	}
	
	die();
}

add_action( 'wp_ajax_car_rental_is_reservation_only_request', 'car_rental_is_reservation_only_request');
add_action( 'wp_ajax_nopriv_car_rental_is_reservation_only_request', 'car_rental_is_reservation_only_request');
function car_rental_is_reservation_only_request() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

			$car_rental_id = wp_kses($_REQUEST['car_rental_id'], '');	
			$is_reservation_only = get_post_meta( $car_rental_id, 'car_rental_is_reservation_only', true );
			$is_reservation_only = isset($is_reservation_only) ? $is_reservation_only : 0;
			
			echo $is_reservation_only;
		}
	}
	
	die();
}

add_action( 'wp_ajax_accommodation_is_reservation_only_request', 'accommodation_is_reservation_only_request');
add_action( 'wp_ajax_nopriv_accommodation_is_reservation_only_request', 'accommodation_is_reservation_only_request');
function accommodation_is_reservation_only_request() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

			$accommodation_id = wp_kses($_REQUEST['accommodation_id'], '');	
			$is_reservation_only = get_post_meta( $accommodation_id, 'accommodation_is_reservation_only', true );
			$is_reservation_only = isset($is_reservation_only) ? $is_reservation_only : 0;
			
			echo $is_reservation_only;
		}
	}
	
	die();
}

add_action( 'wp_ajax_tour_available_schedule_id_request', 'tour_available_schedule_id_request');
add_action( 'wp_ajax_nopriv_tour_available_schedule_id_request', 'tour_available_schedule_id_request');
function tour_available_schedule_id_request() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

			$tour_id = wp_kses($_REQUEST['tourId'], '');	
			$date_value = wp_kses($_REQUEST['dateValue'], '');	
			$date_value = date('Y-m-d', strtotime($date_value));
			$schedule_id = get_tour_available_schedule_id($tour_id, $date_value);
			echo $schedule_id;
		}
	}
	
	die();
}

add_action( 'wp_ajax_cruise_available_schedule_id_request', 'cruise_available_schedule_id_request');
add_action( 'wp_ajax_nopriv_cruise_available_schedule_id_request', 'cruise_available_schedule_id_request');
function cruise_available_schedule_id_request() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

			$cruise_id = wp_kses($_REQUEST['cruiseId'], '');	
			$cabin_type_id = wp_kses($_REQUEST['cabinTypeId'], '');	
			$date_value = wp_kses($_REQUEST['dateValue'], '');	
			$date_value = date('Y-m-d', strtotime($date_value));
			$schedule_id = get_cruise_available_schedule_id($cruise_id, $cabin_type_id, $date_value);
			echo $schedule_id;
		}
	}
	
	die();
}

add_action( 'wp_ajax_tour_get_price_request', 'tour_get_price_request');
add_action( 'wp_ajax_nopriv_tour_get_price_request', 'tour_get_price_request');
function tour_get_price_request() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

			$tour_id = wp_kses($_REQUEST['tourId'], '');	
			$date_value = wp_kses($_REQUEST['dateValue'], '');	
			$date_value = date('Y-m-d', strtotime($date_value));
			$schedule_id = get_tour_available_schedule_id($tour_id, $date_value);
	
			$price_decimal_places = (int)of_get_option('price_decimal_places', 0);

			if ($schedule_id > 0) {				
				$price = number_format (get_tour_schedule_price($schedule_id, false), $price_decimal_places, ".", "");
				$child_price = number_format (get_tour_schedule_price($schedule_id, true), $price_decimal_places, ".", "");
	
				$prices = array( 
					'price' => $price, 
                    'child_price' => $child_price 
				);
				
				echo json_encode($prices);
			}
		}
	}
	
	die();
}

add_action( 'wp_ajax_cruise_get_price_request', 'cruise_get_price_request');
add_action( 'wp_ajax_nopriv_cruise_get_price_request', 'cruise_get_price_request');
function cruise_get_price_request() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

			$cruise_id = wp_kses($_REQUEST['cruiseId'], '');	
			$cabin_type_id = wp_kses($_REQUEST['cabinTypeId'], '');	
			$date_value = wp_kses($_REQUEST['dateValue'], '');	
			$date_value = date('Y-m-d', strtotime($date_value));
			$schedule_id = get_cruise_available_schedule_id($cruise_id, $cabin_type_id, $date_value);
	
			$price_decimal_places = (int)of_get_option('price_decimal_places', 0);

			if ($schedule_id > 0) {				
				$price = number_format (get_cruise_schedule_price($schedule_id, false), $price_decimal_places, ".", "");
				$child_price = number_format (get_cruise_schedule_price($schedule_id, true), $price_decimal_places, ".", "");
	
				$prices = array( 
					'price' => $price, 
                    'child_price' => $child_price 
				);
				
				echo json_encode($prices);
			}
		}
	}
	
	die();
}

add_action( 'wp_ajax_accommodation_get_price_request', 'accommodation_get_price_request');
add_action( 'wp_ajax_nopriv_accommodation_get_price_request', 'accommodation_get_price_request');
function accommodation_get_price_request() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

			$accommodation_id = wp_kses($_REQUEST['accommodationId'], '');	
			$room_type_id = wp_kses($_REQUEST['roomTypeId'], '');	
			$dateValue = wp_kses($_REQUEST['dateValue'], '');	
			$dateTime = strtotime($dateValue);
			$dateValue = date('Y-m-d', $dateTime);
	
			$price_decimal_places = (int)of_get_option('price_decimal_places', 0);

			if ($accommodation_id > 0) {				
				$price_per_day = number_format (get_accommodation_price($dateValue, $accommodation_id, $room_type_id, false), $price_decimal_places, ".", "");
				$child_price = number_format (get_accommodation_price($dateValue, $accommodation_id, $room_type_id, true), $price_decimal_places, ".", "");
	
				$prices = array( 
					'price_per_day' => $price_per_day, 
                    'child_price' => $child_price 
				);
				
				echo json_encode($prices);
			}
		}
	}
	
	die();
}

add_action( 'wp_ajax_inquiry_ajax_request', 'inquiry_ajax_request' );
add_action( 'wp_ajax_nopriv_inquiry_ajax_request', 'inquiry_ajax_request' );
function inquiry_ajax_request() {
	global $enc_key;
	if ( isset($_REQUEST) ) {
	
		$your_name = wp_kses($_REQUEST['your_name'], '');
		$your_email = wp_kses($_REQUEST['your_email'], '');
		$your_phone = wp_kses($_REQUEST['your_phone'], '');
		$your_message = wp_kses($_REQUEST['your_message'], '');
		$postId = wp_kses($_REQUEST['postId'], '');	
		$user_id = wp_kses($_REQUEST['userId'], '');	
		$c_val_s = intval(wp_kses($_REQUEST['c_val_s'], ''));
		$c_val_1 = intval(contact_decrypt(wp_kses($_REQUEST['c_val_1'], ''), $enc_key));
		$c_val_2 = intval(contact_decrypt(wp_kses($_REQUEST['c_val_2'], ''), $enc_key));
		
        $nonce = $_REQUEST['nonce'];
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
		
			$add_captcha_to_forms = of_get_option('add_captcha_to_forms', 1);
		
			if ($add_captcha_to_forms && $c_val_s != ($c_val_1 + $c_val_2)) {
				
				echo 'captcha_error';
				die();
				
			} else {
			
				// nonce passed ok
				$post = get_post($postId);
				
				if ($post) {

					$admin_email = get_bloginfo('admin_email');
					$contact_email = get_post_meta($postId, $post->post_type . '_contact_email', true );
					$contact_emails = explode(';', $contact_email);
					if (empty($contact_email))
						$contact_emails = array($admin_email);	
				
					$admin_name = get_bloginfo('name');
					$subject = __('New inquiry', 'bookyourtravel');				
					$message = __("The following inquiry has just arrived: \n Name: %s \n Email: %s \n Phone: %s \n Message: %s \n Inquiring about: %s \n", 'bookyourtravel');
					$message = sprintf($message, $your_name, $your_email, $your_phone, $your_message, $post->post_title);

					$headers   = array();
					$headers[] = "MIME-Version: 1.0";
					$headers[] = "Content-type: text/plain; charset=utf-8";
					$headers[] = "From: " . get_bloginfo( 'name' ) . " <" . $admin_email . ">";
					$headers[] = "Reply-To: " . get_bloginfo( 'name' ) . " <" . $admin_email . ">";
					$headers[] = "X-Mailer: PHP/".phpversion();
					
					$headers_str = implode( "\r\n", $headers );
					
					foreach ($contact_emails as $email) {
						if (!empty($email)) {
							echo wp_mail($email, $subject, $message, $headers_str, '-f ' . $admin_email);
						}
					}
				}
			}
			
		} 
		echo '';
	}
	
	// Always die in functions echoing ajax content
	die();

}

add_action( 'wp_ajax_change_currency_ajax_request', 'change_currency_ajax_request' );
add_action( 'wp_ajax_nopriv_change_currency_ajax_request', 'change_currency_ajax_request' );
function change_currency_ajax_request() {

	if ( isset($_REQUEST) ) {
	
		$new_currency = wp_kses($_REQUEST['new_currency'], '');
		$user_id = wp_kses($_REQUEST['user_id'], '');

        $nonce = $_REQUEST['nonce'];
		
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

			$default_currency = strtoupper(of_get_option('default_currency_select', 'USD'));
	
			$default_currency_list = array(
				'usd'=> '1',
				'gbp'=> '1',
				'eur'=> '1'
			);
	
			$possible_currencies = of_get_option('enabled_currencies', $default_currency_list);

			$enabled_currencies = array();
			foreach ($possible_currencies as $currency => $enabled) {
				if ($enabled == '1')
					$enabled_currencies[] = strtoupper($currency);
			}

			if ( in_array( $new_currency, $enabled_currencies ) ) {
				update_user_meta($user_id, 'user_currency', $new_currency);
				echo '1';
			} else {
				echo '0';
			}
		}
	}
	
	// Always die in functions echoing ajax content
	die();
}

add_action( 'wp_ajax_review_ajax_request', 'review_ajax_request' );
add_action( 'wp_ajax_nopriv_review_ajax_request', 'review_ajax_request' );
function review_ajax_request() {

	if ( isset($_REQUEST) ) {
	
		$likes = wp_kses($_REQUEST['likes'], '');
		$dislikes = wp_kses($_REQUEST['dislikes'], '');
		$reviewed_post_id = wp_kses($_REQUEST['postId'], '');	
		$user_id = wp_kses($_REQUEST['userId'], '');	
        $nonce = $_REQUEST['nonce'];
		
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
		
			// nonce passed ok
			$reviewed_post = get_post($reviewed_post_id);
			$review_fields = list_review_fields($reviewed_post->post_type);
			$user_info = get_userdata($user_id);
			
			if ($reviewed_post != null && $user_info != null && count($review_fields) > 0) {
			
				$reviewed_post_title = get_the_title($reviewed_post_id);
				
				$review_post = array(
					'post_title'    => sprintf(__('Review of %s by %s [%s]', 'bookyourtravel'), $reviewed_post_title, $user_info->user_nicename, $user_id),
					'post_status'   => 'publish',
					'post_author'   => $user_id,
					'post_type' 	=> 'review',
					'post_date' => date('Y-m-d H:i:s')					
				);

				// Insert the post into the database
				$review_post_id = wp_insert_post( $review_post );
				
				if( ! is_wp_error( $review_post_id ) ) {
				
					$new_score_sum = 0;
					foreach ($review_fields as $review_field) {
						$field_id = $review_field['id'];
						$field_value = isset($_REQUEST['reviewField_' . $field_id]) ? intval(wp_kses($_REQUEST['reviewField_' . $field_id], '')) : 0;
						$new_score_sum += $field_value;
						add_post_meta($review_post_id, $field_id, $field_value);
					}
					
					$review_score = floatval(get_post_meta($reviewed_post_id, 'review_score', true));
					$review_score = $review_score ? $review_score : 0;
					
					$review_sum_score = floatval(get_post_meta($reviewed_post_id, 'review_sum_score', true));
					$review_sum_score = $review_sum_score ? $review_sum_score : 0;
					
					$review_count = intval(get_reviews_count($reviewed_post_id));
					$review_count = $review_count ? $review_count : 0;
					$review_count++;
					
					$review_sum_score = $review_sum_score + $new_score_sum;
					$new_review_score = $new_score_sum / (count($review_fields) * 10);
					$review_score = ($review_score + $new_review_score) / $review_count;					
					
					add_post_meta($review_post_id, 'review_likes', $likes);
					add_post_meta($review_post_id, 'review_dislikes', $dislikes);
					add_post_meta($review_post_id, 'review_post_id', $reviewed_post_id);

					update_post_meta($reviewed_post_id, 'review_sum_score', $review_sum_score);
					update_post_meta($reviewed_post_id, 'review_score', $review_score);		
					update_post_meta($reviewed_post_id, 'review_count', $review_count);	
				}
				
				echo $review_post_id;
			}
		} else { 
			echo 'nonce fail';
		}
	}
	
	// Always die in functions echoing ajax content
	die();

}

add_action( 'wp_ajax_sync_reviews_ajax_request', 'sync_reviews_ajax_request');
function sync_reviews_ajax_request() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
		if ( wp_verify_nonce( $nonce, 'optionsframework-options' ) ) {
		
			$enable_accommodations = of_get_option('enable_accommodations', 1); 
			if ($enable_accommodations)
				recalculate_review_scores('accommodation');
			
			$enable_tours = of_get_option('enable_tours', 1); 
			if ($enable_tours)
				recalculate_review_scores('tour');
				
			$enable_cruises = of_get_option('enable_cruises', 1); 
			if ($enable_cruises)
				recalculate_review_scores('cruises');
		
			echo '1';
		} else {
			echo '0';
		}
	}
	die();
}

add_action( 'wp_ajax_fix_partial_booking_issue_ajax_request', 'fix_partial_booking_issue_ajax_request');
function fix_partial_booking_issue_ajax_request() {
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
		if ( wp_verify_nonce( $nonce, 'optionsframework-options' ) ) {

			fix_accommodation_booking_dates();
		
			echo '1';
		} else {
			echo '0';
		}
	}
	die();
}

add_action( 'wp_ajax_book_car_rental_ajax_request', 'book_car_rental_ajax_request' );
add_action( 'wp_ajax_nopriv_book_car_rental_ajax_request', 'book_car_rental_ajax_request' );
function book_car_rental_ajax_request() {
	global $enc_key;

	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
		
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
			$first_name = wp_kses($_REQUEST['first_name'], '');
			$last_name = wp_kses($_REQUEST['last_name'], '');
			$email = wp_kses($_REQUEST['email'], '');
			$phone = wp_kses($_REQUEST['phone'], '');
			$address = wp_kses($_REQUEST['address'], '');
			$town = wp_kses($_REQUEST['town'], '');
			$zip = wp_kses($_REQUEST['zip'], '');
			$country = wp_kses($_REQUEST['country'], '');
			$special_requirements = wp_kses($_REQUEST['requirements'], '');
			$date_from = wp_kses($_REQUEST['date_from'], '');
			$date_to = wp_kses($_REQUEST['date_to'], '');
			$car_rental_id = intval(wp_kses($_REQUEST['car_rental_id'], ''));	
			$drop_off = intval(wp_kses($_REQUEST['drop_off'], ''));	

			$c_val_s = intval(wp_kses($_REQUEST['c_val_s'], ''));
			$c_val_1 = intval(contact_decrypt(wp_kses($_REQUEST['c_val_1'], ''), $enc_key));
			$c_val_2 = intval(contact_decrypt(wp_kses($_REQUEST['c_val_2'], ''), $enc_key));
			
			// nonce passed ok
			$car_rental_obj = new byt_car_rental($car_rental_id);			
			
			if ($car_rental_obj != null) {
			
				$add_captcha_to_forms = of_get_option('add_captcha_to_forms', 1);
			
				if ($add_captcha_to_forms && $c_val_s != ($c_val_1 + $c_val_2)) {
					echo 'captcha_error';
					die();
				} else {
				
					$drop_off_location_obj = new byt_location($drop_off);
					$drop_off_location_title = $drop_off_location_obj->get_title();
					$car_rental_location = $car_rental_obj->get_location();
					$pick_up_location_title = '';
					if ($car_rental_location)
						$pick_up_location_title = $car_rental_location->get_title();
					
					$price_per_day = floatval($car_rental_obj->get_custom_field( 'price_per_day' ));
					$datediff =  strtotime($date_to) -  strtotime($date_from);
					$days = floor($datediff/(60*60*24));
					
					$total_price = $price_per_day * $days;
					
					$current_user = wp_get_current_user();
					
					$booking_id = create_car_rental_booking ($first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $date_from, $date_to, $car_rental_id,  $current_user->ID, $total_price, $drop_off);
					
					$is_reservation_only = get_post_meta( $car_rental_id, 'car_rental_is_reservation_only', true );
					
					if (byt_is_woocommerce_active() && !$is_reservation_only) {
						$use_woocommerce_for_checkout = of_get_option('use_woocommerce_for_checkout', 0);
						if ($use_woocommerce_for_checkout) {
							$product_id = byt_woocommerce_create_product($car_rental_obj->get_title(), '', 'ACC_' . $car_rental_id . '_', $booking_id, $total_price, BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CAR_RENTALS); 
							echo $product_id;
						}
					} else {
						echo $booking_id;
					}
					
					$admin_email = get_bloginfo('admin_email');
					$admin_name = get_bloginfo('name');
					
					$headers = "From: $admin_name <$admin_email>\n";
					$subject = __('New car rental booking', 'bookyourtravel');
					$message = '';

					$message = __("New car rental booking: \n
					First name: %s \n
					Last name: %s \n
					Email: %s \n
					Phone: %s \n
					Address: %s \n
					Town: %s \n
					Zip: %s \n
					Country: %s \n
					Special requirements: %s \n
					Date from: %s \n
					Date to: %s \n
					Pick up: %s \n
					Drop off: %s \n
					Total price: %d \n
					Car: %s \n", 'bookyourtravel');	
					
					$message = sprintf($message, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $date_from, $date_to, $pick_up_location_title, $drop_off_location_title, $total_price, $car_rental_obj->get_title());
					
					wp_mail($email, $subject, $message, $headers);

					$contact_email = get_post_meta($car_rental_id, 'car_rental_contact_email', true );
					$contact_emails = explode(';', $contact_email);
					if (empty($contact_email))
						$contact_emails = array($admin_email);	

					foreach ($contact_emails as $e) {
						if (!empty($e)) {
							wp_mail($e, $subject, $message, $headers);			
						}
					}
				}
			}
		} 		
	}
	
	// Always die in functions echoing ajax content
	die();
} 

add_action( 'wp_ajax_book_accommodation_ajax_request', 'book_accommodation_ajax_request' );
add_action( 'wp_ajax_nopriv_book_accommodation_ajax_request', 'book_accommodation_ajax_request' );
function book_accommodation_ajax_request() {

	global $enc_key;

	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
		
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
			$first_name = wp_kses($_REQUEST['first_name'], '');
			$last_name = wp_kses($_REQUEST['last_name'], '');
			$email = wp_kses($_REQUEST['email'], '');
			$phone = wp_kses($_REQUEST['phone'], '');
			$address = wp_kses($_REQUEST['address'], '');
			$town = wp_kses($_REQUEST['town'], '');
			$zip = wp_kses($_REQUEST['zip'], '');
			$country = wp_kses($_REQUEST['country'], '');
			$special_requirements = wp_kses($_REQUEST['requirements'], '');
			$date_from = date('Y-m-d', strtotime(wp_kses($_REQUEST['date_from'], '')));
			$date_to = date('Y-m-d', strtotime(wp_kses($_REQUEST['date_to'], '')));
			$accommodation_id = wp_kses($_REQUEST['accommodation_id'], '');		
			$room_type_id = wp_kses($_REQUEST['room_type_id'], '');		
			$room_count = wp_kses($_REQUEST['room_count'], '');	
			$adults = wp_kses($_REQUEST['adults'], '');
			$children = wp_kses($_REQUEST['children'], '');
			$c_val_s = intval(wp_kses($_REQUEST['c_val_s'], ''));
			$c_val_1 = intval(contact_decrypt(wp_kses($_REQUEST['c_val_1'], ''), $enc_key));
			$c_val_2 = intval(contact_decrypt(wp_kses($_REQUEST['c_val_2'], ''), $enc_key));
			
			// nonce passed ok
			$accommodation = get_post($accommodation_id);
			if ($room_type_id)
				$room_type = get_post($room_type_id);
			
			if ($accommodation != null) {
			
				$add_captcha_to_forms = of_get_option('add_captcha_to_forms', 1);
			
				if ($add_captcha_to_forms && $c_val_s != ($c_val_1 + $c_val_2)) {
					echo 'captcha_error';
					die();
				} else {
					
					$is_self_catered = get_post_meta( $accommodation_id, 'accommodation_is_self_catered', true );
					$is_reservation_only = get_post_meta( $accommodation_id, 'accommodation_is_reservation_only', true );
					$current_user = wp_get_current_user();
					$total_price = get_accommodation_total_price($accommodation_id, $date_from, $date_to, $room_type_id, $room_count, $adults, $children);
					
					$booking_id = create_accommodation_booking ($first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $room_count, $date_from, $date_to, $accommodation_id, $room_type_id, $current_user->ID, $is_self_catered, $total_price, $adults, $children);

					if (byt_is_woocommerce_active() && !$is_reservation_only) {
						$use_woocommerce_for_checkout = of_get_option('use_woocommerce_for_checkout', 0);
						if ($use_woocommerce_for_checkout) {
							$product_id = byt_woocommerce_create_product($accommodation->post_title, '', 'ACC_' . $accommodation_id . '_', $booking_id, $total_price, BOOKYOURTRAVEL_WOO_PRODUCT_CAT_ACCOMMODATIONS); 
							echo $product_id;
						}
					} else {
						echo $booking_id;
					}
					
					$admin_email = get_bloginfo('admin_email');
					$admin_name = get_bloginfo('name');
					
					$headers = "From: $admin_name <$admin_email>\n";
					$subject = __('New accommodation booking', 'bookyourtravel');
					$message = '';
					if ($is_self_catered) {
						$message = __("New self-catered booking: \n
						First name: %s \n
						Last name: %s \n
						Email: %s \n
						Phone: %s \n
						Address: %s \n
						Town: %s \n
						Zip: %s \n
						Country: %s \n
						Special requirements: %s \n
						Adults: %s \n
						Children: %s \n
						Date from: %s \n
						Date to: %s \n
						Total price: %d \n
						Accommodation: %s \n", 'bookyourtravel');	
						$message = sprintf($message, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $date_from, $date_to, $total_price, $accommodation->post_title);
					} else {
						$message = __("New hotel booking: \n
						First name: %s \n
						Last name: %s \n
						Email: %s \n
						Phone: %s \n
						Address: %s \n
						Town: %s \n
						Zip: %s \n
						Country: %s \n
						Special requirements: %s \n
						Room count: %d \n
						Adults: %s \n
						Children: %s \n
						Date from: %s \n
						Date to: %s \n
						Total price: %d \n
						Accommodation: %s \n
						Room type: %s \n", 'bookyourtravel');
						$message = sprintf($message, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $room_count, $adults, $children, $date_from, $date_to, $total_price, $accommodation->post_title, $room_type->post_title);
					}

					wp_mail($email, $subject, $message, $headers);

					$contact_email = get_post_meta($accommodation_id, 'accommodation_contact_email', true );
					$contact_emails = explode(';', $contact_email);
					if (empty($contact_email))
						$contact_emails = array($admin_email);	

					foreach ($contact_emails as $e) {
						if (!empty($e)) {
							wp_mail($e, $subject, $message, $headers);			
						}
					}
				}
			}
		} 		
	}
	
	// Always die in functions echoing ajax content
	die();
} 

add_action( 'wp_ajax_book_tour_ajax_request', 'book_tour_ajax_request' );
add_action( 'wp_ajax_nopriv_book_tour_ajax_request', 'book_tour_ajax_request' );
function book_tour_ajax_request() {
	global $enc_key;
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
		
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
			$first_name = wp_kses($_REQUEST['first_name'], '');
			$last_name = wp_kses($_REQUEST['last_name'], '');
			$email = wp_kses($_REQUEST['email'], '');
			$phone = wp_kses($_REQUEST['phone'], '');
			$address = wp_kses($_REQUEST['address'], '');
			$town = wp_kses($_REQUEST['town'], '');
			$zip = wp_kses($_REQUEST['zip'], '');
			$adults = wp_kses($_REQUEST['adults'], '');
			$adults = $adults ? intval($adults) : 1;
			$children = wp_kses($_REQUEST['children'], '');
			$children = $children ? intval($children) : 0;
			$country = wp_kses($_REQUEST['country'], '');
			$special_requirements = wp_kses($_REQUEST['requirements'], '');
			$tour_start_date = wp_kses($_REQUEST['tour_start_date'], '');		
			$tour_schedule_id = wp_kses($_REQUEST['tour_schedule_id'], '');		
		
			$c_val_s = intval(wp_kses($_REQUEST['c_val_s'], ''));
			$c_val_1 = intval(contact_decrypt(wp_kses($_REQUEST['c_val_1'], ''), $enc_key));
			$c_val_2 = intval(contact_decrypt(wp_kses($_REQUEST['c_val_2'], ''), $enc_key));
		
			// nonce passed ok
			$tour_schedule = get_tour_schedule($tour_schedule_id);
			
			if ($tour_schedule != null) {
			
				$add_captcha_to_forms = of_get_option('add_captcha_to_forms', 1);
			
				if ($add_captcha_to_forms && $c_val_s != ($c_val_1 + $c_val_2)) {
					echo 'captcha_error';
					die();
				} else {
			
					$tour_id = $tour_schedule->tour_id;
					$tour = get_post($tour_id);

					$tour_is_price_per_group = get_post_meta($tour_id, 'tour_is_price_per_group', true);
					
					$current_user = wp_get_current_user();
					
					$total_price_adults = $tour_schedule->price;
					$total_price_children = 0;
					
					if (!$tour_is_price_per_group) {
						$total_price_children = $tour_schedule->price_child * $children;
						$total_price_adults = $total_price_adults * $adults;
					}
						
					$total_price = $total_price_adults + $total_price_children;
					$start_date = date('Y-m-d', strtotime($tour_start_date));
					$tour_name = $tour->post_title;
					
					$booking_id = create_tour_booking ($first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $tour_schedule_id, $current_user->ID, $total_price_adults, $total_price_children, $total_price, $start_date);
					
					$is_reservation_only = get_post_meta( $tour_id, 'tour_is_reservation_only', true );
					
					if (byt_is_woocommerce_active() && !$is_reservation_only) {
						$use_woocommerce_for_checkout = of_get_option('use_woocommerce_for_checkout', 0);
						if ($use_woocommerce_for_checkout) {
							$product_id = byt_woocommerce_create_product($tour->post_title, '', 'ACC_' . $tour_id . '_', $booking_id, $total_price, BOOKYOURTRAVEL_WOO_PRODUCT_CAT_TOURS); 
							echo $product_id;
						}
					} else {
						echo $booking_id;
					}
					
					$admin_email = get_bloginfo('admin_email');
					$admin_name = get_bloginfo('name');
					$headers = "From: $admin_name <$admin_email>\n";
					$subject = __('New tour booking', 'bookyourtravel');
					
					$message = __("New tour booking: \n
					First name: %s \n
					Last name: %s \n
					Email: %s \n
					Phone: %s \n
					Address: %s \n
					Town: %s \n
					Zip: %s \n
					Country: %s \n
					Special requirements: %s \n
					Adults: %d \n
					Children: %d \n
					Tour name: %s \n
					Start date: %s \n
					Total price: %d \n", 'bookyourtravel');
					$message = sprintf($message, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $tour_name, $start_date, $total_price);

					wp_mail($email, $subject, $message, $headers);
					
					$contact_email = get_post_meta($tour_id, 'tour_contact_email', true );
					$contact_emails = explode(';', $contact_email);
					if (empty($contact_email))
						$contact_emails = array($admin_email);	

					foreach ($contact_emails as $e) {
						if (!empty($e)) {
							wp_mail($e, $subject, $message, $headers);			
						}
					}
				}
			}
		} 		
	}
	
	// Always die in functions echoing ajax content
	die();
} 


add_action( 'wp_ajax_book_cruise_ajax_request', 'book_cruise_ajax_request' );
add_action( 'wp_ajax_nopriv_book_cruise_ajax_request', 'book_cruise_ajax_request' );
function book_cruise_ajax_request() {
	global $enc_key;
	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
		
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
			$first_name = wp_kses($_REQUEST['first_name'], '');
			$last_name = wp_kses($_REQUEST['last_name'], '');
			$email = wp_kses($_REQUEST['email'], '');
			$phone = wp_kses($_REQUEST['phone'], '');
			$address = wp_kses($_REQUEST['address'], '');
			$town = wp_kses($_REQUEST['town'], '');
			$zip = wp_kses($_REQUEST['zip'], '');
			$adults = wp_kses($_REQUEST['adults'], '');
			$adults = $adults ? intval($adults) : 1;
			$children = wp_kses($_REQUEST['children'], '');
			$children = $children ? intval($children) : 0;
			$country = wp_kses($_REQUEST['country'], '');
			$special_requirements = wp_kses($_REQUEST['requirements'], '');
			$cruise_start_date = wp_kses($_REQUEST['cruise_start_date'], '');		
			$cruise_schedule_id = wp_kses($_REQUEST['cruise_schedule_id'], '');		
		
			$c_val_s = intval(wp_kses($_REQUEST['c_val_s'], ''));
			$c_val_1 = intval(contact_decrypt(wp_kses($_REQUEST['c_val_1'], ''), $enc_key));
			$c_val_2 = intval(contact_decrypt(wp_kses($_REQUEST['c_val_2'], ''), $enc_key));
		
			// nonce passed ok
			$cruise_schedule = get_cruise_schedule($cruise_schedule_id);
			
			if ($cruise_schedule != null) {
			
				$add_captcha_to_forms = of_get_option('add_captcha_to_forms', 1);
			
				if ($add_captcha_to_forms && $c_val_s != ($c_val_1 + $c_val_2)) {
					echo 'captcha_error';
					die();
				} else {
			
					$cruise_id = $cruise_schedule->cruise_id;
					$cruise_obj = new byt_cruise(intval($cruise_id));
					$cruise = get_post($cruise_id);

					$cruise_is_price_per_person = $cruise_obj->get_is_price_per_person();
					
					$current_user = wp_get_current_user();
					
					$total_price_adults = 0;
					
					$total_price_children = 0;
					if ($cruise_is_price_per_person) {
						$total_price_children = $cruise_schedule->price_child * $children;
						$total_price_adults = $cruise_schedule->price * $adults;
					} else {
						$total_price_adults = $cruise_schedule->price;
					}
						
					$total_price = $total_price_adults + $total_price_children;
					$start_date = date('Y-m-d', strtotime($cruise_start_date));
					$cruise_name = $cruise_obj->get_title();
					
					$booking_id = create_cruise_booking ($first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $cruise_schedule_id, $current_user->ID, $total_price_adults, $total_price_children, $total_price, $start_date);
					
					$is_reservation_only = get_post_meta( $cruise_id, 'cruise_is_reservation_only', true );
					
					if (byt_is_woocommerce_active() && !$is_reservation_only) {
						$use_woocommerce_for_checkout = of_get_option('use_woocommerce_for_checkout', 0);
						if ($use_woocommerce_for_checkout) {
							$product_id = byt_woocommerce_create_product($cruise_obj->get_title(), '', 'ACC_' . $cruise_id . '_', $booking_id, $total_price, BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CRUISES); 
							echo $product_id;
						}
					} else {
						echo $booking_id;
					}
					
					$admin_email = get_bloginfo('admin_email');
					$admin_name = get_bloginfo('name');
					$headers = "From: $admin_name <$admin_email>\n";
					$subject = __('New cruise booking', 'bookyourtravel');
					
					$message = __("New cruise booking: \n
					First name: %s \n
					Last name: %s \n
					Email: %s \n
					Phone: %s \n
					Address: %s \n
					Town: %s \n
					Zip: %s \n
					Country: %s \n
					Special requirements: %s \n
					Adults: %d \n
					Children: %d \n
					Cruise name: %s \n
					Start date: %s \n
					Total price: %d \n", 'bookyourtravel');
					$message = sprintf($message, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $cruise_name, $start_date, $total_price);

					wp_mail($email, $subject, $message, $headers);
					
					$contact_email = get_post_meta($cruise_id, 'cruise_contact_email', true );
					$contact_emails = explode(';', $contact_email);
					if (empty($contact_email))
						$contact_emails = array($admin_email);	

					foreach ($contact_emails as $e) {
						if (!empty($e)) {
							wp_mail($e, $subject, $message, $headers);			
						}
					}
				}
			}
		} 		
	}
	
	// Always die in functions echoing ajax content
	die();
}

add_action( 'wp_ajax_currency_ajax_request', 'currency_ajax_request' );
add_action( 'wp_ajax_nopriv_currency_ajax_request', 'currency_ajax_request' );
function currency_ajax_request() {

	if ( isset($_REQUEST) ) {
        $nonce = $_REQUEST['nonce'];
		
        if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

			$amount = wp_kses($_REQUEST['amount'], '');
			$from = wp_kses($_REQUEST['from'], '');
			$to = wp_kses($_REQUEST['to'], '');
			
			if (isset($_REQUEST['userId'])) {
				$user_id = wp_kses($_REQUEST['userId'], '');
				update_user_meta($user_id, 'user_currency', $to);
			}
			
			if ($from != $to)
				$converted = currency_conversion($amount, $from, $to);
			else
				$converted = $amount;
			echo $converted;
		} 		
	}
	
	// Always die in functions echoing ajax content
	die();
}

function currency_conversion($amount, $from_currency, $to_currency) {
 
	$to_currency = strtolower($to_currency);
	$from_currency = strtolower($from_currency);
	
	$price_decimal_places = (int)of_get_option('price_decimal_places', 0);
	$ecb_url = 'http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';
 
	$currency_convert_xml = get_transient('currency_convert_xml');
	// Note http://www.ecb.europa.eu provides currency conversion from 1 eur to other currency.
	// so have to keep that in mind with conversions
	if (!$currency_convert_xml) {	
		$response = wp_remote_get($ecb_url);
		$currency_convert_xml = $response['body'];
		set_transient( 'currency_convert_xml', $currency_convert_xml, 60*60*24 ); // download once per day
	}
	
	if ($currency_convert_xml) {
		$xml = new SimpleXMLElement($currency_convert_xml) ;
		
		$oneErate_from = 0;
		$oneErate_to = 0;
		foreach($xml->Cube->Cube->Cube as $rate){
			if (strtolower($rate["currency"]) == $from_currency)
				$oneErate_from = floatval($rate["rate"]);
			else if (strtolower($rate["currency"]) == $to_currency)
				$oneErate_to = floatval($rate["rate"]);
		}
		if ($from_currency == 'eur')
			$oneErate_from = 1;
		if ($to_currency == 'eur')
			$oneErate_to = 1;
			
		if ($oneErate_from > 0 && $oneErate_to > 0) {
			return number_format((floatval($amount / $oneErate_from) * $oneErate_to), $price_decimal_places, ".", "");
		}
	}
	
	return 0;
}