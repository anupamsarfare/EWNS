<?php
	global $post, $currency_symbol, $tour_class, $price_decimal_places;
	$tour_id = $post->ID;
	$tour_obj = new byt_tour($post);
	$base_id = $tour_obj->get_base_id();
	$reviews_total = get_reviews_count($base_id);

	$tour_image = $tour_obj->get_main_image();	
	if (empty($tour_image)) {
		$tour_image = get_byt_file_uri('/images/uploads/img.jpg');
	}

	$is_price_per_group = $tour_obj->get_is_price_per_group();
	
	$score_out_of_10 = 0;
	if ($reviews_total > 0) {
		$review_score = $tour_obj->get_custom_field('review_score', false);
		$score_out_of_10 = round($review_score * 10);
	}	
	
	$tour_description_html = strip_tags_and_shorten($tour_obj->get_description(), 100) . '<a href="' . $tour_obj->get_permalink() . '">' . __('More info', 'bookyourtravel') . '</a>';

	$current_date = date('Y-m-d', time());
	$tour_min_price = number_format (get_tour_min_price($tour_id, $current_date), $price_decimal_places, ".", "");
	
	$tour_location = $tour_obj->get_location();
	$tour_location_title = '';
	if ($tour_location)
		$tour_location_title = $tour_location->get_title();
?>
	<!--tour item-->
	<article class="tour_item <?php echo $tour_class; ?>">
		<div>
			<figure>
				<a href="<?php echo $tour_obj->get_permalink(); ?>" title="<?php echo $tour_obj->get_title(); ?>">
					<img src="<?php echo $tour_image; ?>" alt="" />
				</a>
			</figure>
			<div class="details">
				<h2><?php echo $tour_obj->get_title(); ?></h2>
				<?php
				// display tour address
				byt_render_field("", "address", $tour_location_title, '', '', false, false); 
				if ($score_out_of_10 > 0) { 
					// display score out of 10
					byt_render_field("", "rating", $score_out_of_10 . ' / 10', "", '', false, false);
				} 
				if ($tour_min_price > 0) { ?>
				<div class="price">
					<?php 
					if (!$is_price_per_group) 
						_e('Price per person from ', 'bookyourtravel');
					else
						_e('Price per group from ', 'bookyourtravel');
					?>
					<em><span class="curr"><?php echo $currency_symbol; ?></span>
					<span class="amount"><?php echo $tour_min_price; ?></span></em>
				</div>
				<?php 
				} 
				byt_render_field("description clearfix", "", "", $tour_description_html, '', false, true);
				byt_render_link_button($tour_obj->get_permalink(), "gradient-button clearfix", "", __('Book now', 'bookyourtravel')); 
				?>
			</div>
		</div>
	</article>
	<!--//tour item-->