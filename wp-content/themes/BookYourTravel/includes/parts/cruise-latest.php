<?php
	$show_cruise_offers = of_get_option('show_cruise_offers', '0');
	if ($show_cruise_offers) { 
		$latest_cruises_count = (int)of_get_option('latest_cruises_count', 4);
		$show_featured_cruises_only = (bool)of_get_option('show_featured_cruises_only', false); 
	?>	
	<!--latest deals-->
	<section class="deals clearfix full">
		<h1><?php _e('Explore our latest cruises', 'bookyourtravel'); ?></h1>
		<div class="inner-wrap">
		<?php
			$cruise_results = list_cruises(1, $latest_cruises_count, 'cruises.post_date', 'DESC', array(), array(), $show_featured_cruises_only);
			if ( count($cruise_results) > 0 && $cruise_results['total'] > 0 ) {
				foreach ($cruise_results['results'] as $cruise_result) {
					global $cruise_class;
					$post = $cruise_result;
					setup_postdata( $post ); 
					$cruise_class = 'one-fourth';
					get_template_part('includes/parts/cruise', 'item');
				}
			}
		?>	
		</div>
	</section>
<?php  
	} // end if ($show_cruise_offers)