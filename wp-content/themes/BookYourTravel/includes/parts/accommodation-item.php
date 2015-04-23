<?php
	global $post, $currency_symbol, $accommodation_class, $price_decimal_places, $current_user, $current_url, $list_user_accommodations_url, $submit_accommodations_url;
	
	$accommodation_id = $post->ID;
	$accommodation_obj = new byt_accommodation($post);
	$base_id = $accommodation_obj->get_base_id();
	$reviews_total = get_reviews_count($base_id);
	
	$accommodation_image = $accommodation_obj->get_main_image();	
	if (empty($accommodation_image)) {
		$accommodation_image = get_byt_file_uri('/images/uploads/img.jpg');
	}
	
	$score_out_of_10 = 0;
	if ($reviews_total > 0) {
		$review_score = $accommodation_obj->get_custom_field('review_score', false);
		$score_out_of_10 = round($review_score * 10);
	}
	
	$accommodation_min_price = number_format(get_accommodation_min_price($accommodation_id), $price_decimal_places, ".", "");
	$accommodation_description_html = strip_tags_and_shorten($accommodation_obj->get_description(), 100) . '<a href="' . $accommodation_obj->get_permalink() . '">' . __('More info', 'bookyourtravel') . '</a>';
?>
	<!--accommodation item-->
	<article class="accommodation_item <?php echo $accommodation_class; ?>">
		<div>
			<figure>
				<a href="<?php echo $accommodation_obj->get_permalink(); ?>" title="<?php echo $accommodation_obj->get_title(); ?>">
					<img src="<?php echo $accommodation_image; ?>" alt="" />
				</a>
			</figure>
			<div class="details">
				<h2>
					<?php echo $accommodation_obj->get_title(); ?> <?php if ($accommodation_obj->get_status() == 'private') echo '<span class="private">' . __('Pending', 'bookyourtravel') . '</span>'; ?>
					<span class="stars">
					<?php
					for ( $i = 0; $i < $accommodation_obj->get_custom_field('star_count'); $i++ ) { ?>
						<img src="<?php echo get_byt_file_uri('/images/ico/star.png'); ?>" alt="" />
					<?php } ?>
					</span>
				</h2>
				<?php 
				
				// display accommodation address
				byt_render_field("", "address", $accommodation_obj->get_custom_field('address'), '', '', false, false);
				if ($score_out_of_10 > 0) {
					// display score out of 10
					byt_render_field("", "rating", $score_out_of_10 . ' / 10', "", '', false, false);
				}			
				if ($accommodation_min_price > 0) { ?>
				<div class="price">
					<?php _e('Price per night from ', 'bookyourtravel'); ?>
					<em><span class="curr"><?php echo $currency_symbol; ?></span>
					<span class="amount"><?php echo $accommodation_min_price; ?></span></em>
				</div>
				<?php } ?>
				<?php 
					byt_render_field("description clearfix", "", "", $accommodation_description_html, '', false, true);
					if (!empty($current_url) && $current_url == $list_user_accommodations_url)
						byt_render_link_button($submit_accommodations_url . '?fesid=' . $accommodation_id, "gradient-button clearfix", "", __('Edit', 'bookyourtravel')); 
					else 
						byt_render_link_button($accommodation_obj->get_permalink(), "gradient-button clearfix", "", __('Book now', 'bookyourtravel')); 

				?>
			</div>
		</div>
	</article>
	<!--//accommodation item-->
<?php ?>