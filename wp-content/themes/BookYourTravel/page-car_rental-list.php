<?php
/*	Template Name: Car rental list
 * The template for displaying the car rental list.
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
get_header('accommodation'); 
byt_breadcrumbs();
get_sidebar('under-header');
global $currency_symbol;

if ( get_query_var('paged') ) {
    $paged = get_query_var('paged');
} else if ( get_query_var('page') ) {
    $paged = get_query_var('page');
} else {
    $paged = 1;
}
$posts_per_page = of_get_option('car_rentals_archive_posts_per_page', 12);

global $post;
$page_id = $post->ID;
$page_custom_fields = get_post_custom( $page_id);

$car_types = wp_get_post_terms($page_id, 'car_type', array("fields" => "all"));

$car_type_ids = array();
if (count($car_types) > 0) {
	$car_type_ids[] = $car_types[0]->term_id;
}
?>		
	<section class="full">
		<?php  while ( have_posts() ) : the_post(); ?>
		<article <?php post_class("static-content"); ?> id="page-<?php the_ID(); ?>">
			<h1><?php the_title(); ?></h1>
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'bookyourtravel' ) ); ?>
			<?php wp_link_pages('before=<div class="pagination">&after=</div>'); ?>
		</article>
		<?php endwhile; ?>
<?php		
	$car_rental_results = list_car_rentals($paged, $posts_per_page, 'post_title', 'ASC', 0, $car_type_ids);
?>	
		<div class="deals clearfix">
			<script>
				window.formMultipleError = <?php echo json_encode(__('You failed to provide {0} fields. They have been highlighted below.', 'bookyourtravel'));  ?>;
			</script>
			<?php if ( count($car_rental_results) > 0 && $car_rental_results['total'] > 0 ) { ?>
			<div class="inner-wrap">
			<?php
				foreach ($car_rental_results['results'] as $car_rental_result) { 
					global $post, $car_rental_class;
					$post = $car_rental_result;
					setup_postdata( $post ); 

					$car_rental_class = 'one-fourth';
					get_template_part('includes/parts/car_rental', 'item');
				}
			?>
			</div>
			<nav class="page-navigation bottom-nav">
				<!--back up button-->
				<a href="#" class="scroll-to-top" title="<?php _e('Back up', 'bookyourtravel'); ?>"><?php _e('Back up', 'bookyourtravel'); ?></a> 
				<!--//back up button-->
				<!--pager-->
				<div class="pager">
					<?php 
					$total_results = $car_rental_results['total'];
					byt_display_pager( ceil($total_results/$posts_per_page) );
					?>
				</div>
			</nav>
		<?php } // end if ( $query->have_posts() ) ?>
		</div><!--//deals clearfix-->
	</section>
<?php
	wp_reset_postdata();
get_footer(); 
?>