<?php

/*-----------------------------------------------------------------------------------

	Plugin Name: BYT Search Widget

-----------------------------------------------------------------------------------*/

// Add function to widgets_init that'll load our widget.
add_action( 'widgets_init', 'byt_search_widgets' );

// Register widget.
function byt_search_widgets() {
	register_widget( 'byt_Search_Widget' );
}

// Widget class.
class byt_search_widget extends WP_Widget {

	/*-----------------------------------------------------------------------------------*/
	/*	Widget Setup
	/*-----------------------------------------------------------------------------------*/
	
	function byt_Search_Widget() {
	
		wp_register_script( 'bookyourtravel-search-widget', get_byt_file_uri ('/js/search_widget.js'), array('jquery', 'bookyourtravel-jquery-uniform', 'jquery-ui-spinner'), '1.0', true );	
		wp_enqueue_script( 'bookyourtravel-search-widget' );	
	
		wp_enqueue_script( 'custom-suggest', get_byt_file_uri ('/js/custom-suggest.js'), array('jquery'), '', true );
	
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'byt_search_widget', 'description' => __('BookYourTravel: Search', 'bookyourtravel') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 260, 'height' => 600, 'id_base' => 'byt_search_widget' );

		/* Create the widget. */
		$this->WP_Widget( 'byt_search_widget', __('BookYourTravel: Search', 'bookyourtravel'), $widget_ops, $control_ops );
	}


/*-----------------------------------------------------------------------------------*/
/*	Display Widget
/*-----------------------------------------------------------------------------------*/
	
	function widget( $args, $instance ) {
		extract( $args );
		
		global $custom_search_results_page;
		global $enable_hotel_search, $enable_tour_search, $enable_self_catered_search, $enable_car_rental_search, $enable_cruise_search;
		global $enable_reviews;
		
		$what = '1';
		if (isset($_GET['what'])) {
			$what = wp_kses($_GET['what'], '');	
		}
		
		$searchable_count = 0;			
		if ($enable_hotel_search)
			$searchable_count++;
		if ($enable_self_catered_search)
			$searchable_count++;
		if ($enable_car_rental_search)
			$searchable_count++;	
		if ($enable_tour_search)
			$searchable_count++;
		if ($enable_cruise_search)
			$searchable_count++;

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : __('Refine search results', 'bookyourtravel') );
		
		$what_text = isset($instance['what_text']) ? $instance['what_text'] : __('What?', 'bookyourtravel');
		$when_text = isset($instance['when_text']) ? $instance['when_text'] : __('When?', 'bookyourtravel');
		$accommodation_date_from_label_text = isset($instance['accommodation_date_from_label_text']) ? $instance['accommodation_date_from_label_text'] : __('Check-in date', 'bookyourtravel');
		$accommodation_date_to_label_text = isset($instance['accommodation_date_to_label_text']) ? $instance['accommodation_date_to_label_text'] : __('Check-out date', 'bookyourtravel');
		$accommodation_location_label_text = isset($instance['accommodation_location_label_text']) ? $instance['accommodation_location_label_text'] : __('Your destination', 'bookyourtravel');
		$rooms_label_text = isset($instance['rooms_label_text']) ? $instance['rooms_label_text'] : __('Rooms', 'bookyourtravel');
		$guests_label_text = isset($instance['guests_label_text']) ? $instance['guests_label_text'] : __('Guests', 'bookyourtravel');
		$accommodation_type_label_text = isset($instance['accommodation_type_label_text']) ? $instance['accommodation_type_label_text'] : __('Accommodaton type', 'bookyourtravel');
		$star_rating_label_text = isset($instance['star_rating_label_text']) ? $instance['star_rating_label_text'] : __('Star rating', 'bookyourtravel');
		$user_rating_label_text = isset($instance['user_rating_label_text']) ? $instance['user_rating_label_text'] : __('User rating', 'bookyourtravel');
		
		$car_rental_date_from_label_text = isset($instance['car_rental_date_from_label_text']) ? $instance['car_rental_date_from_label_text'] : __('Pick-up date', 'bookyourtravel');
		$car_rental_date_to_label_text = isset($instance['car_rental_date_to_label_text']) ? $instance['car_rental_date_to_label_text'] : __('Drop-off date', 'bookyourtravel');	
		$car_rental_location_label_text = isset($instance['car_rental_location_label_text']) ? $instance['car_rental_location_label_text'] : __('Pick up from', 'bookyourtravel');
		$drivers_age_label_text = isset($instance['drivers_age_label_text']) ? $instance['drivers_age_label_text'] : __("Driver's age", 'bookyourtravel');
		$car_type_label_text = isset($instance['car_type_label_text']) ? $instance['car_type_label_text'] : __('Car type', 'bookyourtravel');		

		$tour_date_from_label_text = isset($instance['tour_date_from_label_text']) ? $instance['tour_date_from_label_text'] : __('Start date', 'bookyourtravel');
		$tour_location_label_text = isset($instance['tour_location_label_text']) ? $instance['tour_location_label_text'] : __('Tour location', 'bookyourtravel');

		$cruise_date_from_label_text = isset($instance['cruise_date_from_label_text']) ? $instance['cruise_date_from_label_text'] : __('Start date', 'bookyourtravel');		
		$cabins_label_text = isset($instance['cabins_label_text']) ? $instance['cabins_label_text'] : __('Cabins', 'bookyourtravel');
		$cabin_type_label_text = isset($instance['cabin_type_label_text']) ? $instance['cabin_type_label_text'] : __('Cabin type', 'bookyourtravel');		

		$price_per_person_label_text = isset($instance['price_per_person_label_text']) ? $instance['price_per_person_label_text'] : __('Price per person', 'bookyourtravel');
		$price_per_night_label_text = isset($instance['price_per_night_label_text']) ? $instance['price_per_night_label_text'] : __('Price per night', 'bookyourtravel');
		$price_per_day_label_text = isset($instance['price_per_day_label_text']) ? $instance['price_per_day_label_text'] : __('Price per day', 'bookyourtravel');

		$submit_button_text = isset($instance['submit_button_text']) ? $instance['submit_button_text'] : __('Search again', 'bookyourtravel');

		/* Before widget (defined by themes). */

		/* Display Widget */
		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			// echo $before_title . $title . $after_title;
		?>
			<script>
				
				window.searchWidgetPricePerPersonLabel = '<?php echo $price_per_person_label_text; ?>';
				window.searchWidgetPricePerNightLabel = '<?php echo $price_per_night_label_text; ?>';
				window.searchWidgetPricePerDayLabel = '<?php echo $price_per_day_label_text; ?>';
				
			</script>
			<article class="refine-search-results byt_search_widget">
				<form class="widget-search" method="get" action="<?php echo $custom_search_results_page; ?>">
					<?php echo $before_title . $title . $after_title; ?>
					<dl>
					<?php 					
					$this->render_what_section($searchable_count, $what_text);
					$this->render_price_range_section($what, $price_per_person_label_text, $price_per_night_label_text, $price_per_day_label_text);
					$this->render_star_rating_section($star_rating_label_text);
					
					if ($enable_reviews)
						$this->render_user_rating_section($user_rating_label_text);
						
					$this->render_car_type_section($car_type_label_text);
					$this->render_accommodation_type_section($accommodation_type_label_text);
					$this->render_cabin_type_section($cabin_type_label_text);
					$this->render_drivers_age_section($drivers_age_label_text);
					$this->render_rooms_section($rooms_label_text);
					$this->render_guests_section($guests_label_text);
					$this->render_cabins_section($cabins_label_text);
					$this->render_when_section($when_text, $accommodation_date_from_label_text, $accommodation_date_to_label_text, $car_rental_date_from_label_text, $car_rental_date_to_label_text, $tour_date_from_label_text, $cruise_date_from_label_text);
					$this->render_location_section($accommodation_location_label_text, $car_rental_location_label_text, $tour_location_label_text);
					?>					
					</dl>
					<input type="submit" value="<?php echo $submit_button_text; ?>" class="gradient-button" id="search-submit" />
				</form>
			</article>        	
		<?php

		/* After widget (defined by themes). */
	}
	
	function render_location_section($accommodation_location_label_text, $car_rental_location_label_text, $tour_location_label_text) {
		$term = isset($_GET['term']) ? wp_kses($_GET['term'], '') : '';
	?>	
		<dt class="where">
			<?php echo $accommodation_location_label_text; ?>
		</dt>
		<dd class="where">
			<script>		
				window.searchAccommodationLocationLabel = '<?php echo $accommodation_location_label_text; ?>';
				window.searchCarRentalLocationLabel = '<?php echo $car_rental_location_label_text; ?>';
				window.searchTourLocationLabel = '<?php echo $tour_location_label_text; ?>';
			</script>
			<div class="destination">
				<input type="text" placeholder="" id="search_widget_term" name="term" value="<?php echo $term;?>" />
			</div>
		</dd>
	<?php
	}
	
	function render_guests_section($guests_label_text) {
		$guests = isset($_GET['guests']) ? intval(wp_kses($_GET['guests'], '')) : 0;
		?>
		<dt class="guests">
			<?php echo $guests_label_text; ?>
		</dt>
		<dd class="guests">
			<div class="spinner">
				<input type="text" id="search_widget_guests" name="guests" value="<?php echo $guests; ?>" />
			</div>
		</dd>
		<?php
	}
	
	function render_rooms_section($rooms_label_text) {
		$rooms = isset($_GET['rooms']) ? intval(wp_kses($_GET['rooms'], '')) : 0;
		?>
		<dt class="rooms">
			<?php echo $rooms_label_text; ?>
		</dt>
		<dd class="rooms">
			<div class="spinner">
				<input type="text" id="search_widget_rooms" name="rooms" value="<?php echo $rooms; ?>" />
			</div>
		</dd>
		<?php
	}
	
	function render_cabins_section($cabins_label_text) {
		$cabins = isset($_GET['cabins']) ? intval(wp_kses($_GET['cabins'], '')) : 0;
		?>
		<dt class="cabins">
			<?php echo $cabins_label_text; ?>
		</dt>
		<dd class="cabins">
			<div class="spinner">
				<input type="text" id="search_widget_cabins" name="cabins" value="<?php echo $cabins; ?>" />
			</div>
		</dd>
		<?php
	}
	
	function render_drivers_age_section($drivers_age_label_text) {
		$age = isset($_GET['age']) ? intval(wp_kses($_GET['age'], '')) : 0;
		?>
		<dt class="age">
			<?php echo $drivers_age_label_text; ?>
		</dt>
		<dd class="age">
			<div class="spinner">
				<input type="text" id="search_widget_drivers_age" name="age" value="<?php echo $age; ?>" />
			</div>
		</dd>
		<?php
	}
	
	function render_when_section($when_text, $accommodation_date_from_label_text, $accommodation_date_to_label_text, $car_rental_date_from_label_text, $car_rental_date_to_label_text, $tour_date_from_label_text, $cruise_date_from_label_text) { 
		
		$from = isset($_GET['from']) && !empty($_GET['from'])  ? date('m/d/Y', strtotime(wp_kses($_GET['from'], ''))) : null;
		$to = isset($_GET['to']) && !empty($_GET['to']) ? date('m/d/Y', strtotime(wp_kses($_GET['to'], ''))) : null;
	?>
		<dt class="when">
			<?php echo $when_text; ?>
		</dt>
		<dd class="when">
			<script>
				window.searchAccommodationDateFromLabel = '<?php echo $accommodation_date_from_label_text; ?>';
				window.searchAccommodationDateToLabel = '<?php echo $accommodation_date_to_label_text; ?>';
				window.searchCarRentalDateFromLabel = '<?php echo $car_rental_date_from_label_text; ?>';
				window.searchCarRentalDateToLabel = '<?php echo $car_rental_date_to_label_text; ?>';
				window.searchTourDateFromLabel = '<?php echo $tour_date_from_label_text; ?>';		
				window.searchCruiseDateFromLabel = '<?php echo $cruise_date_from_label_text; ?>';	
			</script>
			<div class="datepicker">
				<label for="search_widget_date_from"><?php echo $accommodation_date_from_label_text; ?></label>
				<div class="datepicker-wrap"><input type="text" id="search_widget_date_from" placeholder="" id="from" name="from" <?php echo $from != null ? 'value="' . $from . '"' : '' ?> /></div>
			</div>
			<div class="datepicker">
				<label for="search_widget_date_to"><?php echo $accommodation_date_to_label_text; ?></label>
				<div class="datepicker-wrap"><input type="text" id="search_widget_date_to" placeholder="" id="to" name="to" <?php echo $to != null ? 'value="' . $to . '"' : '' ?> /></div>
			</div>
		</dd>
		<?php
	}	
	
	function render_cabin_type_section($cabin_type_label_text) {
	
		$request_type_ids = array();
		if (isset($_GET['cabin_types'])) {
			$request_type_ids = retrieve_array_of_values_from_query_string('cabin_types', true);
		}
	
		$cabin_type_query = list_cabin_types();
		if ($cabin_type_query->have_posts()) {
	?>	
		<dt class="cabin_type"><?php echo $cabin_type_label_text; ?></dt>
		<dd class="cabin_type">
		<?php
			$i = 0;
			while ($cabin_type_query->have_posts()) {
				$cabin_type_query->the_post();
				global $post;

				$checked = '';
				if (in_array($post->ID, $request_type_ids)) {
					$checked = " checked='checked' ";
				}
		?>
			<div class="checkbox">
				<input <?php echo $checked; ?> value="<?php echo $post->ID; ?>" type="checkbox" id="at<?php echo $i + 1; ?>" name="cabin_types[]" />
				<label for="at<?php echo $i + 1; ?>"><?php echo $post->post_title; ?></label>
			</div>
		<?php 
			$i++;
		} ?>
		</dd>	
	<?php
		}	
	}
		
	function render_accommodation_type_section($accommodation_type_label_text) {
	
		$request_type_ids = array();
		if (isset($_GET['accommodation_types'])) {
			$request_type_ids = retrieve_array_of_values_from_query_string('accommodation_types', true);
		}
	
		$args = array( 
			'taxonomy'=>'accommodation_type', 
			'hide_empty'=>'1'
		);
		$accommodation_types = get_categories($args);
	
		if (count($accommodation_types) > 0) {
	?>	
		<dt class="accommodation_type"><?php echo $accommodation_type_label_text; ?></dt>
		<dd class="accommodation_type">
		<?php for ($i = 0; $i < count($accommodation_types); $i++) {
			$checked = '';
			if (isset($accommodation_types[$i])) {
				$accommodation_type = $accommodation_types[$i];
				if (in_array($accommodation_type->term_id, $request_type_ids)) {
					$checked = " checked='checked' ";
				}
		?>
			<div class="checkbox">
				<input <?php echo $checked; ?> value="<?php echo $accommodation_type->term_id; ?>" type="checkbox" id="at<?php echo $i + 1; ?>" name="accommodation_types[]" />
				<label for="at<?php echo $i + 1; ?>"><?php echo $accommodation_type->name; ?></label>
			</div>
		<?php 	} ?>
		<?php } ?>
		</dd>	
	<?php
		}	
	}
	
	function render_car_type_section($car_type_label_text) {
	
		$request_type_ids = array();
		if (isset($_GET['car_types'])) {
			$request_type_ids = retrieve_array_of_values_from_query_string('car_types', true);
		}
	
		$args = array( 
			'taxonomy'=>'car_type', 
			'hide_empty'=>'1'
		);
		$car_types = get_categories($args);
		
		if (count($car_types) > 0) {
	?>	
		<dt class="car_type"><?php echo $car_type_label_text; ?></dt>
		<dd class="car_type">
		<?php for ($i = 0; $i < count($car_types); $i++) { 
			if (isset($car_types[$i])) {
			$car_type = $car_types[$i];
			
			$checked = '';
			if (in_array($car_type->term_id, $request_type_ids)) {
				$checked = " checked='checked' ";
			}
		?>
			<div class="checkbox">
				<input <?php echo $checked; ?> value="<?php echo $car_type->term_id; ?>" type="checkbox" id="ct<?php echo $i + 1; ?>" name="car_types[]" />
				<label for="ct<?php echo $i + 1; ?>"><?php echo $car_type->name; ?></label>
			</div>
		<?php } 		
		}?>
		</dd>	
	<?php
		}	
	}
	
	function render_user_rating_section($user_rating_label_text) {
		$rating = isset($_GET['rating']) ? intval(wp_kses($_GET['rating'], '')) : 0;
		if (isset($_GET['rating'])) {
			if ($rating > 10)
				$rating = 10;
			else if ($rating < 0)
				$rating = 0;
		}
	?>
		<dt class="user_rating"><?php echo $user_rating_label_text; ?></dt>
		<dd class="user_rating">
			<script>
				window.searchWidgetRating = <?php echo $rating; ?>;
			</script>
			<div id="search_widget_rating_slider"></div>
			<input type="hidden" id="search_widget_rating" name="rating" value="<?php echo $rating; ?>" />
			<span class="min">0</span><span class="max">10</span>
		</dd>
	<?php
	
	}
	
	function render_star_rating_section($star_rating_label_text) {
		$stars = isset($_GET['stars']) ? intval(wp_kses($_GET['stars'], '')) : 0;
		if (isset($_GET['stars'])) {
			$stars = intval(wp_kses($_GET['stars'], ''));
			if ($stars > 5)
				$stars = 5;
			else if ($stars < 0)
				$stars = 0;
		}
	?>
		<dt class="star_rating"><?php echo $star_rating_label_text; ?></dt>
		<dd class="star_rating">
			<script>
				window.searchWidgetStars = <?php echo $stars; ?>;
			</script>
			<span class="stars-info"><?php echo sprintf(__('%d or more', 'bookyourtravel'), $stars); ?></span>
			<div class="search_widget_star" data-rating="<?php echo $stars; ?>"></div>
		</dd>
	<?php
	
	}
	
	function render_price_range_section($what, $price_per_person_label_text, $price_per_night_label_text, $price_per_day_label_text) {
	
		global $currency_symbol;
		
		$request_type_ids = array();
		if (isset($_GET['price'])) {
			$request_type_ids = retrieve_array_of_values_from_query_string('price', true);
		}
	
		$price_range_bottom = of_get_option('price_range_bottom', '0');
		$price_range_increment = of_get_option('price_range_increment', '50');
		$price_range_count = of_get_option('price_range_count', '5');
		$default_currency = strtoupper(of_get_option('default_currency_select', 'USD'));
	
		if ($price_range_count > 0) { ?>
			<dt class="price_per">
			<?php
			if ($what == 1 || $what == 2) {
				echo $price_per_night_label_text;
			} elseif ($what == 3) {
				echo $price_per_day_label_text;
			} elseif ($what == 4 || $what == 5) { 
				echo $price_per_person_label_text;
			} ?>
			</dt>
			<dd>
			<?php 
				$bottom = 0;
				$top = 0;
				$out = '';
				for ( $i = 0; $i < $price_range_count; $i++ ) { 
					$price_index = $i + 1;
					$checked = '';
					if (in_array($price_index, $request_type_ids)) {
						$checked = " checked='checked' ";
					}
				?>
				<div class="checkbox">
					<input <?php echo $checked; ?> type="checkbox" id="price<?php echo $price_index; ?>" name="price[]" value="<?php echo $price_index; ?>" />
					<label for="price<?php echo $price_index; ?>">
					<?php								
					$bottom = ($i * $price_range_increment) + $price_range_bottom;
					$top = (($price_index) * $price_range_increment) + $price_range_bottom - 1;								
					echo $bottom;
					if ($i == ($price_range_count-1)) {
						echo ' <span class="curr">' . $currency_symbol . '</span> +';
					} else {
						echo " - " . $top . ' <span class="curr">' . $currency_symbol . '</span>';
					}								
					?>
					</label>
				</div>
				<?php } ?>
			</dd>
		<?php }
	}
	
	function render_what_section($searchable_count, $what_text) {
	
		$what = isset($_GET['what']) ? intval(wp_kses($_GET['what'], '')) : 1;
	
		global $enable_hotel_search, $enable_tour_search, $enable_self_catered_search, $enable_car_rental_search, $enable_cruise_search;
	
		if ($searchable_count > 0) { ?>
			<dt class="what"><?php echo $what_text; ?></dt>
			<dd class="what">
		<?php			

			if ($enable_hotel_search) {
				if ($this->is_hotel_what_active($what)) { ?>
				<script>window.activeSearchableNumber = 1;</script>
				<?php } ?>
				<div class="checkbox <?php echo $this->is_hotel_what_active($what) ? 'active' : ''; ?>" >
					<input type="radio" name="what" id="hotel" value="1" <?php echo $this->is_hotel_what_active($what) ? ' checked="checked"' : ''; ?> />
					<label for="hotel"> <?php _e('Hotel', 'bookyourtravel'); ?></label>
				</div>
			<?php } 
			if ($enable_self_catered_search) { 
				if ($this->is_self_catered_what_active($what)) { ?>
				<script>window.activeSearchableNumber = 2;</script>
				<?php } ?>
				<div class="checkbox <?php echo $this->is_self_catered_what_active($what) ? 'active' : ''; ?>" >
					<input type="radio" name="what" id="self_catered" value="2" <?php echo $this->is_self_catered_what_active($what) ? ' checked="checked" ' : '' ?> />
					<label for="self_catered"> <?php _e('Self Catering', 'bookyourtravel'); ?></label>
				</div>
			<?php } 
			if ($enable_car_rental_search) {
				if ($this->is_car_rental_what_active($what)) { ?>
				<script>window.activeSearchableNumber = 3;</script>
				<?php } ?>
				<div class="checkbox <?php echo $this->is_car_rental_what_active($what) ? 'active' : ''?>">
					<input type="radio" name="what" id="car_rental" value="3" <?php echo $this->is_car_rental_what_active($what)  ? ' checked="checked" ' : '' ?> />
					<label for="car_rental"> <?php _e('Rent a Car', 'bookyourtravel'); ?></label>
				</div>
			<?php } 
			if ($enable_tour_search) {
				if ($this->is_tour_what_active($what)) {?>
				<script>window.activeSearchableNumber = 4;</script>
				<?php } ?>
				<div class="checkbox <?php echo $this->is_tour_what_active($what) ? 'active' : ''?>" >
					<input type="radio" name="what" id="tour" value="4" <?php echo $this->is_tour_what_active($what)  ? ' checked="checked" ' : '' ?> />
					<label for="tour"> <?php _e('Tour', 'bookyourtravel'); ?></label>
				</div>
			<?php }
			if ($enable_cruise_search) {
				if ($this->is_cruise_what_active($what) ) { ?>
				<script>window.activeSearchableNumber = 5;</script>
				<?php } ?>
				<div class="checkbox <?php echo $this->is_cruise_what_active($what) ? 'active' : ''?>" >
					<input type="radio" name="what" id="cruise" value="5" <?php echo $this->is_cruise_what_active($what) ? ' checked="checked"' : '' ?> />
					<label for="cruise"> <?php _e('Cruise', 'bookyourtravel'); ?></label>
				</div>
				<?php 
			} ?>
			</dd>
		<?php
		} else {
			if ($enable_hotel_search) {
				echo '<input type="hidden" id="what" name="what" value="1" />';
				echo '<script>window.activeSearchableNumber = 1;</script>';
			} elseif ($enable_self_catered_search) {
				echo '<input type="hidden" id="what" name="what" value="2" />';
				echo '<script>window.activeSearchableNumber = 2;</script>';
			} elseif ($enable_car_rental_search) {
				echo '<input type="hidden" id="what" name="what" value="3" />';
				echo '<script>window.activeSearchableNumber = 3;</script>';
			} elseif ($enable_tour_search) {
				echo '<input type="hidden" id="what" name="what" value="4" />';
				echo '<script>window.activeSearchableNumber = 4;</script>';
			} elseif ($enable_cruise_search) {
				echo '<input type="hidden" id="what" name="what" value="5" />';
				echo '<script>window.activeSearchableNumber = 5;</script>';
			}
		}	
	}
	
	function is_hotel_what_active($what) {
		return $what == 1;
	}

	function is_self_catered_what_active($what) {
		global $enable_hotel_search, $enable_tour_search, $enable_self_catered_search, $enable_car_rental_search, $enable_cruise_search;
		return !$enable_hotel_search || $what == 2;
	}
	
	function is_car_rental_what_active($what) {
		global $enable_hotel_search, $enable_tour_search, $enable_self_catered_search, $enable_car_rental_search, $enable_cruise_search;
		return (!$enable_hotel_search && !$enable_self_catered_search) || $what == 3;
	}
	
	function is_tour_what_active($what) {
		global $enable_hotel_search, $enable_tour_search, $enable_self_catered_search, $enable_car_rental_search, $enable_cruise_search;
		return (!$enable_hotel_search && !$enable_self_catered_search && !$enable_car_rental_search) || $what == 4;
	}
	
	function is_cruise_what_active($what) {
		global $enable_hotel_search, $enable_tour_search, $enable_self_catered_search, $enable_car_rental_search, $enable_cruise_search;
		return (!$enable_hotel_search && !$enable_self_catered_search && !$enable_car_rental_search && !$enable_tour_search) || $what == 5;
	}

/*-----------------------------------------------------------------------------------*/
/*	Update Widget
/*-----------------------------------------------------------------------------------*/
	
	function update( $new_instance, $old_instance ) {
	
		$instance = $old_instance;

		/* Strip tags to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );		
		$instance['what_text'] = strip_tags( $new_instance['what_text']);
		$instance['when_text'] = strip_tags( $new_instance['when_text']);
		$instance['accommodation_date_from_label_text'] = strip_tags( $new_instance['accommodation_date_from_label_text']);
		$instance['accommodation_date_to_label_text'] = strip_tags( $new_instance['accommodation_date_to_label_text']);
		$instance['accommodation_location_label_text'] = strip_tags( $new_instance['accommodation_location_label_text']);
		$instance['rooms_label_text'] = strip_tags( $new_instance['rooms_label_text']);
		$instance['guests_label_text'] = strip_tags( $new_instance['guests_label_text']);
		$instance['accommodation_type_label_text'] = strip_tags( $new_instance['accommodation_type_label_text']);
		$instance['star_rating_label_text'] = strip_tags( $new_instance['star_rating_label_text']);
		$instance['user_rating_label_text'] = strip_tags( $new_instance['user_rating_label_text']);

		$instance['car_rental_location_label_text'] = strip_tags( $new_instance['car_rental_location_label_text']);
		$instance['car_rental_date_from_label_text'] = strip_tags( $new_instance['car_rental_date_from_label_text']);
		$instance['car_rental_date_to_label_text'] = strip_tags( $new_instance['car_rental_date_to_label_text']);	
		$instance['drivers_age_label_text'] = strip_tags( $new_instance['drivers_age_label_text']);
		$instance['car_type_label_text'] = strip_tags( $new_instance['car_type_label_text']);		

		$instance['tour_location_label_text'] = strip_tags( $new_instance['tour_location_label_text']);
		$instance['tour_date_from_label_text'] = strip_tags( $new_instance['tour_date_from_label_text']);

		$instance['cruise_date_from_label_text'] = strip_tags( $new_instance['cruise_date_from_label_text']);		
		$instance['cabins_label_text'] = strip_tags( $new_instance['cabins_label_text']);
		$instance['cabin_type_label_text'] = strip_tags( $new_instance['cabin_type_label_text']);		
		
		$instance['price_per_person_label_text'] = strip_tags( $new_instance['price_per_person_label_text']);
		$instance['price_per_night_label_text'] = strip_tags( $new_instance['price_per_night_label_text']);
		$instance['price_per_day_label_text'] = strip_tags( $new_instance['price_per_day_label_text']);
		$instance['submit_button_text'] = strip_tags( $new_instance['submit_button_text']);

		return $instance;
	}
	

/*-----------------------------------------------------------------------------------*/
/*	Widget Settings
/*-----------------------------------------------------------------------------------*/
	 
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
			'title' => __('Refine search results', 'bookyourtravel'),
			'what_text' => __('What?', 'bookyourtravel'),
			'when_text' => __('When?', 'bookyourtravel'),
			'accommodation_date_from_label_text' => __('Check-in date', 'bookyourtravel'),
			'accommodation_date_to_label_text' => __('Check-out date', 'bookyourtravel'),
			'rooms_label_text' => __('Rooms', 'bookyourtravel'),
			'guests_label_text' => __('Guests', 'bookyourtravel'),
			'accommodation_location_label_text' => __('Your destination', 'bookyourtravel'),
			'accommodation_type_label_text' => __('Accommodaton type', 'bookyourtravel'),
			'star_rating_label_text' => __('Star rating', 'bookyourtravel'),
			'user_rating_label_text' => __('User rating', 'bookyourtravel'),
			'car_rental_location_label_text' => __('Pick up from', 'bookyourtravel'),
			'car_rental_date_from_label_text' => __('Pick-up date', 'bookyourtravel'),
			'car_rental_date_to_label_text' => __('Drop-off date', 'bookyourtravel'),	
			'drivers_age_label_text' => __("Driver's age", 'bookyourtravel'),
			'car_type_label_text' => __('Car type', 'bookyourtravel'),		
			'tour_location_label_text' => __('Tour location', 'bookyourtravel'),
			'tour_date_from_label_text' => __('Start date', 'bookyourtravel'),
			'cruise_date_from_label_text' => __('Start date', 'bookyourtravel'),		
			'cabins_label_text' => __('Cabins', 'bookyourtravel'),
			'price_per_person_label_text' => __('Price per person', 'bookyourtravel'),
			'price_per_night_label_text' => __('Price per night', 'bookyourtravel'),
			'price_per_day_label_text' => __('Price per day', 'bookyourtravel'),
			'submit_button_text' => __('Search again', 'bookyourtravel')
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'what_text' ); ?>"><?php _e('What label:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'what_text' ); ?>" name="<?php echo $this->get_field_name( 'what_text' ); ?>" value="<?php echo $instance['what_text']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'when_text' ); ?>"><?php _e('When label:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'when_text' ); ?>" name="<?php echo $this->get_field_name( 'when_text' ); ?>" value="<?php echo $instance['when_text']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'accommodation_date_from_label_text' ); ?>"><?php _e('Accommodation date from label:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'accommodation_date_from_label_text' ); ?>" name="<?php echo $this->get_field_name( 'accommodation_date_from_label_text' ); ?>" value="<?php echo $instance['accommodation_date_from_label_text']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'accommodation_date_to_label_text' ); ?>"><?php _e('Accommodation date to label:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'accommodation_date_to_label_text' ); ?>" name="<?php echo $this->get_field_name( 'accommodation_date_to_label_text' ); ?>" value="<?php echo $instance['accommodation_date_to_label_text']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'accommodation_location_label_text' ); ?>"><?php _e('Accommodation location label:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'accommodation_location_label_text' ); ?>" name="<?php echo $this->get_field_name( 'accommodation_location_label_text' ); ?>" value="<?php echo $instance['accommodation_location_label_text']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'rooms_label_text' ); ?>"><?php _e('Rooms label:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'rooms_label_text' ); ?>" name="<?php echo $this->get_field_name( 'rooms_label_text' ); ?>" value="<?php echo $instance['rooms_label_text']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'guests_label_text' ); ?>"><?php _e('Guests label:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'guests_label_text' ); ?>" name="<?php echo $this->get_field_name( 'guests_label_text' ); ?>" value="<?php echo $instance['guests_label_text']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'accommodation_type_label_text' ); ?>"><?php _e("Accommodation type label", 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'accommodation_type_label_text' ); ?>" name="<?php echo $this->get_field_name( 'accommodation_type_label_text' ); ?>" value="<?php echo $instance['accommodation_type_label_text']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'star_rating_label_text' ); ?>"><?php _e("Star rating label", 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'star_rating_label_text' ); ?>" name="<?php echo $this->get_field_name( 'star_rating_label_text' ); ?>" value="<?php echo $instance['star_rating_label_text']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'price_per_person_label_text' ); ?>"><?php _e("Price per person label", 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'price_per_person_label_text' ); ?>" name="<?php echo $this->get_field_name( 'price_per_person_label_text' ); ?>" value="<?php echo $instance['price_per_person_label_text']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'price_per_night_label_text' ); ?>"><?php _e("Price per night label", 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'price_per_night_label_text' ); ?>" name="<?php echo $this->get_field_name( 'price_per_night_label_text' ); ?>" value="<?php echo $instance['price_per_night_label_text']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'price_per_day_label_text' ); ?>"><?php _e("Price per day label", 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'price_per_day_label_text' ); ?>" name="<?php echo $this->get_field_name( 'price_per_day_label_text' ); ?>" value="<?php echo $instance['price_per_day_label_text']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'user_rating_label_text' ); ?>"><?php _e("User rating label", 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'user_rating_label_text' ); ?>" name="<?php echo $this->get_field_name( 'user_rating_label_text' ); ?>" value="<?php echo $instance['user_rating_label_text']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'car_rental_date_from_label_text' ); ?>"><?php _e('Car rental date from label:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'car_rental_date_from_label_text' ); ?>" name="<?php echo $this->get_field_name( 'car_rental_date_from_label_text' ); ?>" value="<?php echo $instance['car_rental_date_from_label_text']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'car_rental_date_to_label_text' ); ?>"><?php _e('Car rental date to label:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'car_rental_date_to_label_text' ); ?>" name="<?php echo $this->get_field_name( 'car_rental_date_to_label_text' ); ?>" value="<?php echo $instance['car_rental_date_to_label_text']; ?>" />
		</p>	
		
		<p>
			<label for="<?php echo $this->get_field_id( 'car_rental_location_label_text' ); ?>"><?php _e('Car rental location label:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'car_rental_location_label_text' ); ?>" name="<?php echo $this->get_field_name( 'car_rental_location_label_text' ); ?>" value="<?php echo $instance['car_rental_location_label_text']; ?>" />
		</p>
				
		<p>
			<label for="<?php echo $this->get_field_id( 'drivers_age_label_text' ); ?>"><?php _e("Driver's age label", 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'drivers_age_label_text' ); ?>" name="<?php echo $this->get_field_name( 'drivers_age_label_text' ); ?>" value="<?php echo $instance['drivers_age_label_text']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'car_type_label_text' ); ?>"><?php _e("Car type label", 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'car_type_label_text' ); ?>" name="<?php echo $this->get_field_name( 'car_type_label_text' ); ?>" value="<?php echo $instance['car_type_label_text']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'tour_date_from_label_text' ); ?>"><?php _e('Tour date from label:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'tour_date_from_label_text' ); ?>" name="<?php echo $this->get_field_name( 'tour_date_from_label_text' ); ?>" value="<?php echo $instance['tour_date_from_label_text']; ?>" />
		</p>	
		
		<p>
			<label for="<?php echo $this->get_field_id( 'tour_location_label_text' ); ?>"><?php _e('Tour location label:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'tour_location_label_text' ); ?>" name="<?php echo $this->get_field_name( 'tour_location_label_text' ); ?>" value="<?php echo $instance['tour_location_label_text']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'cruise_date_from_label_text' ); ?>"><?php _e('Cruise date from label:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'cruise_date_from_label_text' ); ?>" name="<?php echo $this->get_field_name( 'cruise_date_from_label_text' ); ?>" value="<?php echo $instance['cruise_date_from_label_text']; ?>" />
		</p>	

		<p>
			<label for="<?php echo $this->get_field_id( 'cabins_label_text' ); ?>"><?php _e('Cabins label:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'cabins_label_text' ); ?>" name="<?php echo $this->get_field_name( 'cabins_label_text' ); ?>" value="<?php echo $instance['cabins_label_text']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'cabin_type_label_text' ); ?>"><?php _e("Cabin type label", 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'cabin_type_label_text' ); ?>" name="<?php echo $this->get_field_name( 'cabin_type_label_text' ); ?>" value="<?php echo $instance['cabin_type_label_text']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'submit_button_text' ); ?>"><?php _e('Search again', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'submit_button_text' ); ?>" name="<?php echo $this->get_field_name( 'submit_button_text' ); ?>" value="<?php echo $instance['submit_button_text']; ?>" />
		</p>		
		
	<?php
	}	

}