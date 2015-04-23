<?php
	global $post, $currency_symbol, $cruise_class, $price_decimal_places;
	$cruise_id = $post->ID;
	$cruise_obj = new byt_cruise($post);
	$base_id = $cruise_obj->get_base_id();
	$reviews_total = get_reviews_count($base_id);
	
	$is_price_per_person = $cruise_obj->get_is_price_per_person();
	
	$cruise_image = $cruise_obj->get_main_image();	
	if (empty($cruise_image)) {
		$cruise_image = get_byt_file_uri('/images/uploads/img.jpg');
	}

	$score_out_of_10 = 0;
	if ($reviews_total > 0) {
		$review_score = $cruise_obj->get_custom_field('review_score', false);
		$score_out_of_10 = round($review_score * 10);
	}	
	
	$cruise_description_html = strip_tags_and_shorten($cruise_obj->get_description(), 100) . '<a href="' . $cruise_obj->get_permalink() . '">' . __('More info', 'bookyourtravel') . '</a>';
	
	$current_date = date('Y-m-d', time());
	$cruise_min_price = number_format (get_cruise_min_price($cruise_id, 0, $current_date), $price_decimal_places, ".", "");
?>
	<!--cruise item-->
	<article class="cruise_item <?php echo $cruise_class; ?>">
		<div>
			<figure>
				<a href="<?php echo $cruise_obj->get_permalink(); ?>" title="<?php echo $cruise_obj->get_title(); ?>">
					<img src="<?php echo $cruise_image; ?>" alt="" />
				</a>
			</figure>
			<div class="details">
				<h2><?php echo $cruise_obj->get_title(); ?></h2>
				<?php
				// display cruise address
				if ($score_out_of_10 > 0) { 
					// display score out of 10
					byt_render_field("", "rating", $score_out_of_10 . ' / 10', "", '', false, false);
				} 
				if ($cruise_min_price > 0) { ?>
				<div class="price">
					<?php 
					if ($is_price_per_person) 
						_e('Price per person from ', 'bookyourtravel');
					else
						_e('Price per cabin from ', 'bookyourtravel');
					?>
					<em><span class="curr"><?php echo $currency_symbol; ?></span>
					<span class="amount"><?php echo $cruise_min_price; ?></span></em>
				</div>
				<?php 
				} 
				byt_render_field("description clearfix", "", "", $cruise_description_html, '', false, true);
				byt_render_link_button($cruise_obj->get_permalink(), "gradient-button clearfix", "", __('Book now', 'bookyourtravel')); 
				?>
			</div>
		</div>
	</article>
	<!--//cruise item-->