<?php
/*	Template Name: Cruise list
 * The template for displaying the cruise list
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
get_header(); 
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
$posts_per_page = of_get_option('cruises_archive_posts_per_page', 12);


global $post;
$page_id = $post->ID;
$page_custom_fields = get_post_custom( $page_id);

$cruise_types = wp_get_post_terms($page_id, 'cruise_type', array("fields" => "all"));
$cruise_type_ids = array();
if (count($cruise_types) > 0) {
	$cruise_type_ids[] = $cruise_types[0]->term_id;
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
		$cruise_results = list_cruises($paged, $posts_per_page, 'post_title', 'ASC', $cruise_type_ids);		
?>
		<div class="deals clearfix">
			<?php if ( count($cruise_results) > 0 && $cruise_results['total'] > 0 ) { ?>
			<div class="inner-wrap">
			<?php
				foreach ($cruise_results['results'] as $cruise_result) { 
					global $post, $cruise_class;
					$post = $cruise_result;
					setup_postdata( $post ); 
					$cruise_class = 'one-fourth';
					get_template_part('includes/parts/cruise', 'item');
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
					$total_results = $cruise_results['total'];
					byt_display_pager( ceil($total_results/$posts_per_page) ); 
					?>
				</div>
			</nav>
		<?php } // end if ( $query->have_posts() ) ?>
		</div><!--//deals clearfix-->
	</section>
<?php
	wp_reset_postdata();
	wp_reset_query();
get_footer(); 