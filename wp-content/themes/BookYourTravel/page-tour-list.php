<?php
/*	Template Name: Tour list
 * The template for displaying the tour list.
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
get_header('tour'); 
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
$posts_per_page = of_get_option('tours_archive_posts_per_page', 12);

global $post;
$page_id = $post->ID;
$page_custom_fields = get_post_custom( $page_id);

$tour_types = wp_get_post_terms($page_id, 'tour_type', array("fields" => "all"));

$tour_type_ids = array();
if (count($tour_types) > 0) {
	$tour_type_ids[] = $tour_types[0]->term_id;
}

$parent_location = null;
$parent_location_id = 0;
if (isset($page_custom_fields['tour_list_location_post_id'])) {
	$parent_location_id = $page_custom_fields['tour_list_location_post_id'][0];
	$parent_location_id = empty($parent_location_id) ? 0 : (int)$parent_location_id;
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
		$tour_results = list_tours($paged, $posts_per_page, 'post_title', 'ASC', $parent_location_id, $tour_type_ids);
?>		
		<div class="deals clearfix">
			<script>
				window.formMultipleError = '<?php _e('You failed to provide {0} fields. They have been highlighted below.', 'bookyourtravel');  ?>';
			</script>
			<?php if ( count($tour_results) > 0 && $tour_results['total'] > 0 ) { ?>
			<div class="inner-wrap">
			<?php
				foreach ($tour_results['results'] as $tour_result) { 
					global $post, $tour_class;
					$post = $tour_result;
					setup_postdata( $post ); 
					$tour_class = 'one-fourth';
					get_template_part('includes/parts/tour', 'item');
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
					$total_results = $tour_results['total'];
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