<?php
	$show_tour_offers = of_get_option('show_tour_offers', '0');
	if ($show_tour_offers) { 
		$latest_tours_count = (int)of_get_option('latest_tours_count', 4);
		$show_featured_tours_only = (bool)of_get_option('show_featured_tours_only', false); 
	?>	
	<!--latest deals-->
	<section class="deals clearfix full">
		<h1><?php _e('Explore our latest tours', 'bookyourtravel'); ?></h1>
		<div class="inner-wrap">
		<?php
			$tour_results = list_tours(1, $latest_tours_count, 'tours.post_date', 'DESC', 0, array(), array(), $show_featured_tours_only);
			if ( count($tour_results) > 0 && $tour_results['total'] > 0 ) {
				foreach ($tour_results['results'] as $tour_result) {
					global $tour_class;
					$post = $tour_result;
					setup_postdata( $post ); 
					$tour_class = 'one-fourth';
					get_template_part('includes/parts/tour', 'item');
				}
			}
		?>	
		</div>
	</section>
<?php  
	} // end if ($show_tour_offers) 
?>