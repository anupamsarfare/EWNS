<?php
	$show_car_rental_offers = of_get_option('show_car_rental_offers', '0');
	if ($show_car_rental_offers) { 
		$latest_car_rental_count = (int)of_get_option('latest_car_rental_count', 4);
		$show_featured_car_rentals_only = (bool)of_get_option('show_featured_car_rentals_only', false); 
	?>	
	<!--latest deals-->
	<section class="deals clearfix full">
		<h1><?php _e('Top car rental offers', 'bookyourtravel'); ?></h1>
		<div class="inner-wrap">
			<?php

			$car_rental_results = list_car_rentals(1, $latest_car_rental_count, 'car_rentals.post_date', 'DESC', 0, array(), array(), $show_featured_car_rentals_only);
			if ( count($car_rental_results) > 0 && $car_rental_results['total'] > 0 ) {
				foreach ($car_rental_results['results'] as $car_rental_result) {
					global $post;
					global $car_rental_class;
					$post = $car_rental_result;
					setup_postdata( $post ); 				
					$car_rental_class = 'one-fourth';
					get_template_part('includes/parts/car_rental', 'item');
				}
			}?>
		</div>
	</section>
<?php  
	} // end if ($show_car_rental_offers) 
?>