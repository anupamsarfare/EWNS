<?php
/*	Template Name: Byt Home page
 * The Front Page template file.
 *
 * This is the template of the page that can be selected to be shown as the front page.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
	global $currency_symbol;
	
	get_header();  
	
	if (have_posts()) { ?>
	<section class="full">
	<?php while ( have_posts() ) : the_post(); ?>
		<article <?php post_class("static-content"); ?> id="page-<?php the_ID(); ?>">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'bookyourtravel' ) ); ?>
			<?php wp_link_pages('before=<div class="pagination">&after=</div>'); ?>
		</article>
	<?php endwhile; ?>
	</section>
	<?php } 	
	
	$enable_car_rentals = of_get_option('enable_car_rentals', 1); 
	$enable_accommodations = of_get_option('enable_accommodations', 1); 
	$enable_tours = of_get_option('enable_tours', 1); 
	$enable_cruises = of_get_option('enable_cruises', 1); 
	
 	get_template_part('includes/parts/post', 'latest'); 
	
	if ($enable_accommodations) {
		get_template_part('includes/parts/accommodation', 'latest'); 
	}

	if ($enable_tours) {
		get_template_part('includes/parts/tour', 'latest'); 
	}
	
	if ($enable_cruises) {
		get_template_part('includes/parts/cruise', 'latest'); 
	}

	if ($enable_car_rentals) {
?>
			<script>
				window.formMultipleError = <?php echo json_encode(__('You failed to provide {0} fields. They have been highlighted below.', 'bookyourtravel'));  ?>;
			</script>
<?php		
		get_template_part('includes/parts/car_rental', 'latest'); 
	}
	
	get_template_part('includes/parts/location', 'latest'); 

	wp_reset_postdata();
	get_sidebar('home-footer');
	get_footer(); 
?>