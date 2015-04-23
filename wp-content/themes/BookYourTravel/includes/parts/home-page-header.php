<?php 	
	global $custom_search_results_page, $enable_hotel_search, $enable_tour_search, $enable_self_catered_search, $enable_car_rental_search, $enable_cruise_search;
	global $frontpage_show_slider, $search_box_what_text, $search_box_where_text, $search_box_when_text, $search_box_who_text;
	
	if (is_page_template('byt_home.php')) {
		get_sidebar('home-above-slider');
		$homepage_slider = of_get_option('homepage_slider', '-1');
		$homepage_slider_alias = '';
		if ($homepage_slider >= 0) {
			$sliders_array = array();
			if (class_exists ('RevSlider')) {
				try {
					$slider = new RevSlider();
					$sliders_array = $slider->getAllSliderAliases();
					$homepage_slider_alias = $sliders_array[$homepage_slider];
				}catch(Exception $e){}
			}
		}
		if (!empty($homepage_slider_alias) && $frontpage_show_slider && function_exists('putRevSlider')) {
			putRevSlider($homepage_slider_alias);
		}
	}
	
	get_template_part('includes/parts/home-search', 'box'); 
	
	if (is_front_page() && !is_home()) {
		get_sidebar('home-below-slider');
	}