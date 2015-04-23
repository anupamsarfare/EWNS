<?php 
/* Template Name: User Room List
 * The template for displaying the user submitted room list.
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

global $current_user, $currency_symbol;

$current_user = wp_get_current_user();
$user_info = get_userdata($current_user->ID);

get_header(); 
byt_breadcrumbs();
get_sidebar('under-header');

global $post;
$page_id = $post->ID;
$page_custom_fields = get_post_custom( $page_id);

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
				<li class="active"><a href="<?php echo $list_user_room_types_url; ?>" title="<?php _e('My Room Types', 'bookyourtravel'); ?>"><?php _e('My Room Types', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo $list_user_accommodations_url; ?>" title="<?php _e('My Accommodations', 'bookyourtravel'); ?>"><?php _e('My Accommodations', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo $list_user_accommodation_vacancies_url; ?>" title="<?php _e('My Vacancies', 'bookyourtravel'); ?>"><?php _e('My Vacancies', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo $submit_room_types_url; ?>" title="<?php _e('Submit Room Types', 'bookyourtravel'); ?>"><?php _e('Submit Room Types', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo $submit_accommodations_url; ?>" title="<?php _e('Submit Accommodations', 'bookyourtravel'); ?>"><?php _e('Submit Accommodations', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo $submit_accommodation_vacancies_url; ?>" title="<?php _e('Submit Vacancies', 'bookyourtravel'); ?>"><?php _e('Submit Vacancies', 'bookyourtravel'); ?></a></li>
				<?php } ?>
			</ul>
		</nav>
		<!--//inner navigation-->
		
		<!--Room list-->
		<section id="MyRoomList" class="tab-content initial">
			<article>
				<?php
					$room_type_query = list_room_types($current_user->ID, array('publish', 'private'));
					if ($room_type_query->have_posts()) {
					?>
					<ul class="room-types">
					<?php
						while ($room_type_query->have_posts()) {
							$room_type_query->the_post();
							global $post;				
							$room_type_id = intval($post->ID);
							$room_type_obj = new byt_room_type($room_type_id);
					?>
						<li id="room_type_<?php echo $room_type_id; ?>">
							<?php if ($room_type_obj->get_main_image('medium')) { ?>
								<figure class="left"><img src="<?php echo $room_type_obj->get_main_image('medium') ?>" alt="" /><a href="<?php echo $room_type_obj->get_main_image(); ?>" class="image-overlay" rel="prettyPhoto[gallery1]"></a></figure>
							<?php } ?>
							<div class="meta room_type">
								<h2><?php echo $room_type_obj->get_title(); ?> <?php if ($room_type_obj->get_status() == 'private') echo '<span class="private">' . __('Pending', 'bookyourtravel') . '</span>'; ?></h2>
								<?php byt_render_field('', '', '', $room_type_obj->get_custom_field('meta'), '', true, true); ?>
								<?php byt_render_link_button("#", "more-info", "", __('+ more info', 'bookyourtravel')); ?>
							</div>
							<div class="room-information">
								<div class="row">
									<span class="first"><?php _e('Max:', 'bookyourtravel'); ?></span>
									<span class="second">
										<?php for ( $j = 0; $j < $room_type_obj->get_custom_field('max_count'); $j++ ) { ?>
										<img src="<?php echo get_byt_file_uri('/images/ico/person.png'); ?>" alt="" />
										<?php } ?>
									</span>
									<?php byt_render_link_button($submit_room_types_url . "?fesid=" . $post->ID, "gradient-button", "", __('Edit', 'bookyourtravel')); ?>
								</div>
							</div>
							<div class="more-information">
								<?php byt_render_field('', '', __('Room facilities:', 'bookyourtravel'), $room_type_obj->get_facilities_string(), '', true, true); ?>
								<?php echo $room_type_obj->get_description(); ?>
								<?php byt_render_field('', '', __('Bed size:', 'bookyourtravel'), $room_type_obj->get_custom_field('bed_size'), '', true, true); ?>
								<?php byt_render_field('', '', __('Room size:', 'bookyourtravel'), $room_type_obj->get_custom_field('room_size'), '', true, true); ?>
							</div>
						</li>
					<?php } ?>
					</ul>
				<?php }  else {
						   echo '<p>' . __('You have not submitted any room types yet.', 'bookyourtravel') . '</p>';
					  }?>
			</article>
		</section>
	</section>
<?php
wp_reset_postdata();
wp_reset_query();
get_footer(); 