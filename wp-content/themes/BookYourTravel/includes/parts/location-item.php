<?php
	global $post, $currency_symbol, $location_class, $price_decimal_places, $enable_accommodations;

	$location_id = $post->ID;
	$location_obj = new byt_location($post);

	$location_image = $location_obj->get_main_image();	
	if (empty($location_image)) {
		$location_image = get_byt_file_uri('/images/uploads/img.jpg');
	}
		
	$hotel_count_result = list_accommodations_count ( 0, 0, 'post_title', 'ASC', $location_id, array(), array(), false, false);
	$self_catered_count_result = list_accommodations_count ( 0, 0, 'post_title', 'ASC', $location_id, array(), array(), false, true);
	// $tours_count_result = list_tours_count(0, 0, 'post_title', 'ASC', $location_id);
	
	$hotel_count = intval($hotel_count_result['total']);
	$self_catered_count = intval($self_catered_count_result['total']);
	
	$accommodation_min_price = number_format(get_accommodation_min_price(0, 0, $location_id), $price_decimal_places, ".", "");
?>
	<!--location item-->
	<article class="location_item <?php echo $location_class; ?>">
		<div>
			<figure>
				<a href="<?php  echo $location_obj->get_permalink(); ?>" title="<?php echo $location_obj->get_title(); ?>">
					<img src="<?php echo $location_image; ?>" alt="" />
				</a>
			</figure>
			<div class="details">
				<?php byt_render_link_button($location_obj->get_permalink(), "gradient-button", "", __('View all', 'bookyourtravel')); ?>
				<h3><?php echo $location_obj->get_title(); ?></h3>
				<?php
				// display hotel and self-catered counts
				if ($enable_accommodations) {
					byt_render_field("", "count", $hotel_count . ' ' . __('Hotels', 'bookyourtravel'), '', '', false);
					byt_render_field("", "count", $self_catered_count . ' ' . __('Self-catered', 'bookyourtravel'), '', '', false);
				}

				if ($accommodation_min_price > 0 && ($hotel_count || $self_catered_count)) { ?>
				<div class="ribbon">
					<div class="half hotel">
						<a href="<?php echo $location_obj->get_permalink(); ?>#hotels" title="<?php _e('View all', 'bookyourtravel'); ?>">
							<span class="small"><?php _e('from', 'bookyourtravel'); ?></span>
							<div class="price">
								<em><span class="curr"><?php echo $currency_symbol; ?></span>
								<span class="amount"><?php echo $accommodation_min_price; ?></span></em>
							</div>
						</a>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</article>
	<!--//location item-->