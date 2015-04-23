<?php 
get_header(); 
byt_breadcrumbs();
get_sidebar('under-header');

global $post, $car_rental_obj, $current_user, $entity_obj, $currency_symbol, $price_decimal_places, $default_tour_tabs, $default_tour_extra_fields;

$car_rental_extra_fields = of_get_option('car_rental_extra_fields');
if (!is_array($car_rental_extra_fields) || count($car_rental_extra_fields) == 0)
	$car_rental_extra_fields = $default_car_rental_extra_fields;

$tab_array = of_get_option('car_rental_tabs');
if (!is_array($tab_array) || count($tab_array) == 0)
	$tab_array = $default_car_rental_tabs;
	
if ( have_posts() ) {

	the_post();
	$car_rental_obj = new byt_car_rental($post);
	$entity_obj = $car_rental_obj;
	
	$price_per_day = number_format ($car_rental_obj->get_custom_field('price_per_day'), $price_decimal_places, ".", "");
	
	$car_rental_location = $car_rental_obj->get_location();
	$pick_up_location_title = '';
	if ($car_rental_location)
		$pick_up_location_title = $car_rental_location->get_title();

?>
	<script>
		window.postType = 'car_rental';
	</script>
<?php		
	get_template_part('includes/parts/inquiry', 'form');
?>
	<!--car rental three-fourth content-->
	<section class="three-fourth">
		<?php	
		get_template_part('includes/parts/car_rental', 'booking-form');
		get_template_part('includes/parts/car_rental', 'confirmation-form');	
		?>	
		<script>	
			window.carRentalId = <?php echo $car_rental_obj->get_id(); ?>;
			window.formSingleError = <?php echo json_encode(__('You failed to provide 1 field. It has been highlighted below.', 'bookyourtravel')); ?>;
			window.formMultipleError = <?php echo json_encode(__('You failed to provide {0} fields. They have been highlighted below.', 'bookyourtravel'));  ?>;
			window.carRentalPrice = <?php echo $price_per_day; ?>;
			window.carRentalTitle = <?php echo json_encode($car_rental_obj->get_title()); ?>;
			window.carRentalCarType = <?php echo json_encode($car_rental_obj->get_type_name()); ?>;
			window.carRentalPickUp = <?php echo json_encode($pick_up_location_title); ?>;
			window.currentMonth = <?php echo date('n'); ?>;
			window.currentYear = <?php echo date('Y'); ?>;
		</script>
		<?php $car_rental_obj->render_image_gallery(); ?>
		<!--inner navigation-->
		<nav class="inner-nav">
			<ul>
				<?php do_action( 'byt_show_single_car_rental_tab_items_before' ); ?>
				<?php
				$first_display_tab = '';			
				$i = 0;
				if (is_array($tab_array) && count($tab_array) > 0) {
					foreach ($tab_array as $tab) {
					
						if (!isset($tab['hide']) || $tab['hide'] != '1') {
					
							$tab_label = '';
							if (isset($tab['label'])) {
								$tab_label = $tab['label'];
								$tab_label = get_translated_dynamic_string(get_option_id_context('car_rental_tabs') . ' ' . $tab['label'], $tab_label);
							}
						
							if($i==0)
								$first_display_tab = $tab['id'];
							byt_render_tab('car_rental', $tab['id'], '',  '<a href="#' . $tab['id'] . '" title="' . $tab_label . '">' . $tab_label . '</a>');
						}
						$i++;
					}
				} 	
				?>
				<?php do_action( 'byt_show_single_car_rental_tab_items_after' ); ?>
			</ul>
		</nav>
		<!--//inner navigation-->
		<?php do_action( 'byt_show_single_car_rental_tab_content_before' ); ?>
		<!--description-->
		<section id="description" class="tab-content <?php echo $first_display_tab == 'description' ? 'initial' : ''; ?>">
			<article>
				<h1><?php echo $car_rental_obj->get_title(); ?></h1>
				<?php byt_render_field("text-wrap", "", "", $car_rental_obj->get_description()); ?>
				<div class="text-wrap">
				<?php byt_render_field("location", "", __('Location', 'bookyourtravel'), $pick_up_location_title, '', false, true); ?>
				<?php byt_render_field("car_type", "", __('Car type', 'bookyourtravel'), $car_rental_obj->get_type_name(), '', false, true); ?>
				<?php byt_render_field("max_people", "", __('Max people', 'bookyourtravel'), $car_rental_obj->get_custom_field('max_count'), '', false, true); ?>
				<?php byt_render_field("door_count", "", __('Door count', 'bookyourtravel'), $car_rental_obj->get_custom_field('number_of_doors'), '', false, true); ?>
				<?php byt_render_field("min_age", "", __('Minimum driver age', 'bookyourtravel'), $car_rental_obj->get_custom_field('min_age'), '', false, true); ?>
				<?php byt_render_field("transmission", "", __('Transmission', 'bookyourtravel'), ($car_rental_obj->get_custom_field('transmission_type') == 'manual' ? __('Manual', 'bookyourtravel') : __('Automatic', 'bookyourtravel')), '', false, true); ?>
				<?php byt_render_field("air_conditioned", "", __('Air-conditioned?', 'bookyourtravel'), ($car_rental_obj->get_custom_field('is_air_conditioned') ? __('Yes', 'bookyourtravel') : __('No', 'bookyourtravel')), '', false, true); ?>
				<?php byt_render_field("unlimited_mileage", "", __('Unlimited mileage?', 'bookyourtravel'), ($car_rental_obj->get_custom_field('is_unlimited_mileage') ? __('Yes', 'bookyourtravel') : __('No', 'bookyourtravel')), '', false, true); ?>
				<?php byt_render_tab_extra_fields('car_rental_extra_fields', $car_rental_extra_fields, 'description', $car_rental_obj, '', false, true); ?>
				</div>
				<?php byt_render_link_button("#", "clearfix gradient-button book_car_rental", "", __('Book now', 'bookyourtravel')); ?>				
			</article>
		</section>
		<!--//description-->
		
		<?php do_action( 'byt_show_single_car_rental_tab_content_after' ); ?>
	</section>
	<!--//car rental content-->	
<?php
	get_sidebar('right-car_rental'); 
} // end if
get_footer(); 