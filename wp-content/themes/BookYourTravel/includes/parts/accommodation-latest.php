<?php
$show_accommodation_offers = of_get_option('show_accommodation_offers', '0');
if ($show_accommodation_offers) { 
	$latest_accommodations_count = (int)of_get_option('latest_accommodations_count', 4); 
	$show_featured_accommodations_only = (bool)of_get_option('show_featured_accommodations_only', false); 
	?>	
	<!--latest deals-->
	<section class="deals clearfix full">
		<h1><?php _e('Explore our latest accommodations', 'bookyourtravel'); ?></h1>
		<div class="inner-wrap">
			<?php
			$accommodation_results = list_accommodations(1, $latest_accommodations_count, 'accommodations.post_date', 'DESC', 0, array(), array(), $show_featured_accommodations_only);
			if ( count($accommodation_results) > 0 && $accommodation_results['total'] > 0 ) {
				foreach ($accommodation_results['results'] as $accommodation_result) {
					global $post;				
					$post = $accommodation_result;
					setup_postdata( $post ); 
					global $accommodation_class;
					$accommodation_class = 'one-fourth';
					get_template_part('includes/parts/accommodation', 'item');
				}
			}?>	
		</div>
	</section>
	<!--/latest deals-->
<?php  
wp_reset_postdata();
} // end if ($show_accommodation_offers) ?>
	