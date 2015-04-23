<?php
	global $post, $car_rental_class, $currency_symbol, $price_decimal_places;
	
	$car_rental_id = $post->ID;
	$car_rental_obj = new byt_car_rental($post);

	$car_rental_image = $car_rental_obj->get_main_image();	
	if (empty($car_rental_image)) {
		$car_rental_image = get_byt_file_uri('/images/uploads/img.jpg');
	}
	
	$price_per_day = number_format ($car_rental_obj->get_custom_field('price_per_day'), $price_decimal_places, ".", "");
?>
<!--car rental-->
<article class="car_rental_item <?php echo $car_rental_class; ?>">
	<div>
		<figure>
			<a href="<?php echo $car_rental_obj->get_permalink(); ?>" title="<?php echo $car_rental_obj->get_title(); ?>">
				<img src="<?php echo $car_rental_image; ?>" alt="" />
			</a>
		</figure>
		<div class="details cars">
			<h2><?php echo $car_rental_obj->get_title(); ?></h2>
			<?php if ($price_per_day > 0) { ?>
			<div class="price">
				<?php _e('Price per day ', 'bookyourtravel'); ?>
				<em><span class="curr"><?php echo $currency_symbol; ?></span>
				<span class="amount"><?php echo $price_per_day; ?></span></em>
			</div>
			<?php } ?>
			<div class="description clearfix ">
				<?php byt_render_field("car_type", "", __('Car type', 'bookyourtravel'), $car_rental_obj->get_type_name(), '', false, true); ?>
				<?php byt_render_field("max_people", "", __('Max people', 'bookyourtravel'), $car_rental_obj->get_custom_field('max_count'), '', false, true); ?>
				<?php byt_render_field("door_count", "", __('Door count', 'bookyourtravel'), $car_rental_obj->get_custom_field('number_of_doors'), '', false, true); ?>
				<?php byt_render_field("min_age", "", __('Minimum driver age', 'bookyourtravel'), $car_rental_obj->get_custom_field('min_age'), '', false, true); ?>
				<?php byt_render_field("transmission", "", __('Transmission', 'bookyourtravel'), ($car_rental_obj->get_custom_field('transmission_type') == 'manual' ? __('Manual', 'bookyourtravel') : __('Automatic', 'bookyourtravel')), '', false, true); ?>
				<?php byt_render_field("air_conditioned", "", __('Air-conditioned?', 'bookyourtravel'), ($car_rental_obj->get_custom_field('is_air_conditioned') ? __('Yes', 'bookyourtravel') : __('No', 'bookyourtravel')), '', false, true); ?>
				<?php byt_render_field("unlimited_mileage", "", __('Unlimited mileage?', 'bookyourtravel'), ($car_rental_obj->get_custom_field('is_unlimited_mileage') ? __('Yes', 'bookyourtravel') : __('No', 'bookyourtravel')), '', false, true); ?>
			</div>
			<?php byt_render_link_button($car_rental_obj->get_permalink(), "clearfix gradient-button", "", __('Book now', 'bookyourtravel')); ?>
		</div>
	</div>
</article>
<!--//car rental item-->