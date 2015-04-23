<?php
/*	Template Name: Location list
 * The template for displaying all locations in a list.
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
get_header(''); 
byt_breadcrumbs();
get_sidebar('under-header');

global $post;
$page_id = $post->ID;
$page_custom_fields = get_post_custom( $page_id);

$parent_location = null;
$parent_location_id = null;
$parent_location_title = '';
if (isset($page_custom_fields['location_list_location_post_id'])) {
	$parent_location_id = $page_custom_fields['location_list_location_post_id'][0];
	$parent_location = get_post($parent_location_id);
	if ($parent_location)
		$parent_location_title = $parent_location->post_title;
}

global $current_date;
$posts_per_page = of_get_option('locations_archive_posts_per_page', 12);

if ( get_query_var('paged') ) {
    $paged = get_query_var('paged');
} else if ( get_query_var('page') ) {
    $paged = get_query_var('page');
} else {
    $paged = 1;
}
$posts_per_page = of_get_option('accommodations_archive_posts_per_page', 12);

$args = array(
	'posts_per_page'   => $posts_per_page,
	'paged'			   => $paged,
	'offset'           => 0,
	'category'         => '',
	'orderby'          => 'title',
	'order'            => 'ASC',
	'post_type'        => 'location',
	'post_status'      => 'publish'); 
	
if ($parent_location_id) {
	$args['post_parent'] = $parent_location_id;
}

$now = time();
$current_date = date('Y-m-d', $now);
	
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
	$query = new WP_Query($args); 
?>	
	<div class="destinations clearfix">
		<?php if ( $query->have_posts() ) { ?>
		<div class="inner-wrap">
		<?php
		while ($query->have_posts()) {
			global $post, $location_class;
			$query->the_post(); 
			$location_class = 'one-fourth';
			get_template_part('includes/parts/location', 'item');	
		} // end while ($query->have_posts()) ?>
		</div>
		<nav class="page-navigation bottom-nav">
			<!--back up button-->
			<a href="#" class="scroll-to-top" title="<?php _e('Back up', 'bookyourtravel'); ?>"><?php _e('Back up', 'bookyourtravel'); ?></a> 
			<!--//back up button-->
			<!--pager-->
			<div class="pager">
				<?php byt_display_pager($query->max_num_pages); ?>
			</div>
		</nav>
	<?php } // end if ( $query->have_posts() ) ?>
	</div><!--//destinations clearfix-->
</section>
<?php
	wp_reset_postdata();
get_footer(); 
?>