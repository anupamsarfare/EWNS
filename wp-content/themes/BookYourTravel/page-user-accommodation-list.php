<?php 
/* Template Name: User Accommodation List
 * The template for displaying the user submitted accommodation list.
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */

global $enable_accommodations;
global $frontend_submit;
global $current_url, $list_user_accommodations_url, $submit_accommodations_url;
global $post;
global $current_user, $currency_symbol;

if ( !is_user_logged_in() || !$enable_accommodations || !current_user_can($frontend_submit->get_manage_permissions()) ) {
	wp_redirect( get_home_url() );
	exit;
}

$current_user = wp_get_current_user();
$user_info = get_userdata($current_user->ID);

get_header(); 
byt_breadcrumbs();
get_sidebar('under-header');

$page_id = $post->ID;
$page_custom_fields = get_post_custom( $page_id);
$current_url = get_permalink( $page_id );

if ( get_query_var('paged') ) {
    $paged = get_query_var('paged');
} else if ( get_query_var('page') ) {
    $paged = get_query_var('page');
} else {
    $paged = 1;
}
$posts_per_page = of_get_option('accommodations_archive_posts_per_page', 12);

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
	<!--three-fourth content-->
	<section class="full">
		<h1><?php _e('My account', 'bookyourtravel'); ?></h1>
		<!--inner navigation-->
		<nav class="inner-nav">
			<ul>
				<li><a href="<?php echo $my_account_page; ?>" title="<?php _e('Settings', 'bookyourtravel'); ?>"><?php _e('Settings', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo $my_account_page; ?>" title="<?php _e('My Accommodation Bookings', 'bookyourtravel'); ?>"><?php _e('My Accommodation Bookings', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo $my_account_page; ?>" title="<?php _e('My Reviews', 'bookyourtravel'); ?>"><?php _e('My Reviews', 'bookyourtravel'); ?></a></li>
				<?php if (current_user_can($frontend_submit->get_manage_permissions())) { ?>
				<li><a href="<?php echo $list_user_room_types_url; ?>" title="<?php _e('My Room Types', 'bookyourtravel'); ?>"><?php _e('My Room Types', 'bookyourtravel'); ?></a></li>
				<li class="active"><a href="<?php echo $list_user_accommodations_url; ?>" title="<?php _e('My Accommodations', 'bookyourtravel'); ?>"><?php _e('My Accommodations', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo $list_user_accommodation_vacancies_url; ?>" title="<?php _e('My Vacancies', 'bookyourtravel'); ?>"><?php _e('My Vacancies', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo $submit_room_types_url; ?>" title="<?php _e('Submit Room Types', 'bookyourtravel'); ?>"><?php _e('Submit Room Types', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo $submit_accommodations_url; ?>" title="<?php _e('Submit Accommodations', 'bookyourtravel'); ?>"><?php _e('Submit Accommodations', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo $submit_accommodation_vacancies_url; ?>" title="<?php _e('Submit Vacancies', 'bookyourtravel'); ?>"><?php _e('Submit Vacancies', 'bookyourtravel'); ?></a></li>
				<?php } ?>
			</ul>
		</nav>
		<!--//inner navigation-->
		
		<!--Room list-->
		<section id="MyAccommodationList" class="tab-content initial">
			<?php
				$accommodation_results = list_accommodations ( $paged, $posts_per_page, '', '', 0, array(), array(), false, null, $current_user->ID, true );
			?>
			<div class="deals clearfix">
				<?php if ( count($accommodation_results) > 0 && $accommodation_results['total'] > 0 ) { ?>
				<div class="inner-wrap">
				<?php
				foreach ($accommodation_results['results'] as $accommodation_result) {
					global $post, $accommodation_class;
					$post = $accommodation_result;
					setup_postdata( $post ); 
					$accommodation_class = 'full-width';
					get_template_part('includes/parts/accommodation', 'item');
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
						$total_results = $accommodation_results['total'];
						byt_display_pager( ceil($total_results/$posts_per_page) );
						?>
					</div>
				</nav>
			<?php } else {
					   echo '<p>' . __('You have not submitted any accommodations yet.', 'bookyourtravel') . '</p>';
				  }  // end if ( $query->have_posts() ) ?>
			</div><!--//deals clearfix-->
		</section>
	</section>
<?php
wp_reset_postdata();
wp_reset_query();
get_footer(); 