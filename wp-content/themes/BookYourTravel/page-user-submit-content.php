<?php
/*	Template Name: User Submit Content
 * The template for displaying submit forms for front-end content submission
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */

global $enable_accommodations;
global $frontend_submit;

if ( !is_user_logged_in() || !$enable_accommodations || !current_user_can($frontend_submit->get_manage_permissions()) ) {
	wp_redirect( get_home_url() );
	exit;
}
 
get_header('accommodation'); 
byt_breadcrumbs();
get_sidebar('under-header');

global $post;
$page_id = $post->ID;
$page_custom_fields = get_post_custom( $page_id );
$current_url = get_permalink( $page_id );

$content_type = 'accommodation';
if (isset($page_custom_fields['frontend_submit_content_type'])) {
	$content_type = $page_custom_fields['frontend_submit_content_type'][0];
	$frontend_submit->prepare_form($content_type);
}

$my_account_page_id  = get_current_language_page_id(of_get_option('my_account_page', ''));
$my_account_page = get_permalink($my_account_page_id);
$submit_room_types_url_id = get_current_language_page_id(of_get_option('submit_room_types_url', ''));
$submit_room_types_url = get_permalink($submit_room_types_url_id);
$submit_accommodations_url_id = get_current_language_page_id(of_get_option('submit_accommodations_url', ''));
$submit_accommodations_url = get_permalink($submit_accommodations_url_id);
$submit_accommodation_vacancies_url_id = get_current_language_page_id(of_get_option('submit_accommodation_vacancies_url', ''));
$submit_accommodation_vacancies_url = get_permalink($submit_accommodation_vacancies_url_id);
$list_user_room_types_url_id = get_current_language_page_id(of_get_option('list_user_room_types_url', ''));
$list_user_room_types_url = get_permalink($list_user_room_types_url_id);
$list_user_accommodations_url_id = get_current_language_page_id(of_get_option('list_user_accommodations_url', ''));
$list_user_accommodations_url = get_permalink($list_user_accommodations_url_id);
$list_user_accommodation_vacancies_url_id = get_current_language_page_id(of_get_option('list_user_accommodation_vacancies_url', ''));
$list_user_accommodation_vacancies_url = get_permalink($list_user_accommodation_vacancies_url_id);
?>
	<section class="full">
		<nav class="inner-nav">
			<ul>
				<li><a href="<?php echo $my_account_page; ?>" title="<?php _e('Settings', 'bookyourtravel'); ?>"><?php _e('Settings', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo $my_account_page; ?>" title="<?php _e('My Accommodation Bookings', 'bookyourtravel'); ?>"><?php _e('My Accommodation Bookings', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo $my_account_page; ?>" title="<?php _e('My Reviews', 'bookyourtravel'); ?>"><?php _e('My Reviews', 'bookyourtravel'); ?></a></li>
				<?php if (current_user_can($frontend_submit->get_manage_permissions())) { ?>
				<li><a href="<?php echo $list_user_room_types_url; ?>" title="<?php _e('My Room Types', 'bookyourtravel'); ?>"><?php _e('My Room Types', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo $list_user_accommodations_url; ?>" title="<?php _e('My Accommodations', 'bookyourtravel'); ?>"><?php _e('My Accommodations', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo $list_user_accommodation_vacancies_url; ?>" title="<?php _e('My Vacancies', 'bookyourtravel'); ?>"><?php _e('My Vacancies', 'bookyourtravel'); ?></a></li>
				<li <?php echo $current_url == $submit_room_types_url ? 'class="active"' : ''; ?>><a href="<?php echo $submit_room_types_url; ?>" title="<?php _e('Submit Room Types', 'bookyourtravel'); ?>"><?php _e('Submit Room Types', 'bookyourtravel'); ?></a></li>
				<li <?php echo $current_url == $submit_accommodations_url ? 'class="active"' : ''; ?>><a href="<?php echo $submit_accommodations_url; ?>" title="<?php _e('Submit Accommodations', 'bookyourtravel'); ?>"><?php _e('Submit Accommodations', 'bookyourtravel'); ?></a></li>
				<li <?php echo $current_url == $submit_accommodation_vacancies_url ? 'class="active"' : ''; ?>><a href="<?php echo $submit_accommodation_vacancies_url; ?>" title="<?php _e('Submit Vacancies', 'bookyourtravel'); ?>"><?php _e('Submit Vacancies', 'bookyourtravel'); ?></a></li>
				<?php } ?>
			</ul>
		</nav>
		<!--//inner navigation-->	
		<section id="Submit" class="tab-content initial">
			<?php  while ( have_posts() ) : the_post(); ?>
			<article id="page-<?php the_ID(); ?>">
				<h1><?php the_title(); ?></h1>
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'bookyourtravel' ) ); ?>
				<?php wp_link_pages('before=<div class="pagination">&after=</div>'); ?>
				<?php echo $frontend_submit->upload_form($content_type); ?>
			</article>		
			<?php endwhile; ?>
		</section>
	</section>
<?php
wp_reset_postdata();
wp_reset_query();
get_footer();