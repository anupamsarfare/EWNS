<?php
/**
 * The sidebar containing the car rental widget area.
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */

global $post, $current_user;
global $car_rental_obj, $currency_symbol, $price_decimal_places;

$base_tour_id = $car_rental_obj->get_base_id();
$car_rental_location = $car_rental_obj->get_location();
$pick_up_location_title = '';
if ($car_rental_location)
	$pick_up_location_title = $car_rental_location->get_title();
?>
	<aside id="secondary" class="right-sidebar widget-area" role="complementary">
		<ul>
			<li>
				<article class="tour-details clearfix">
					<h1><?php echo $car_rental_obj->get_title(); ?></h1>
					<span class="address"><?php echo $pick_up_location_title; ?></span>
					<?php byt_render_field("description", "", "", strip_tags_and_shorten($car_rental_obj->get_description(), 100), "", true); ?>
					<?php 
					if ($car_rental_obj->get_custom_field('contact_email')) {
						byt_render_link_button("#", "gradient-button right contact-car_rental", "", __('Send inquiry', 'bookyourtravel'));
					} ?>
				</article>				
			</li>			
		<?php 
			wp_reset_postdata(); 
			dynamic_sidebar( 'right-car_rental' ); ?>
		</ul>
	</aside><!-- #secondary -->