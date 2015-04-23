<?php 
/* Template Name: User Accommodation Vacancy List
 * The template for displaying the user submitted accommodation vacancy list.
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

if ( get_query_var('paged') ) {
    $paged = get_query_var('paged');
} else if ( get_query_var('page') ) {
    $paged = get_query_var('page');
} else {
    $paged = 1;
}
$posts_per_page = 10;

$accommodation_id = 0;
if ( isset($_GET['accid']) ) {
	$accommodation_id = intval($_GET['accid']);
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
				<li><a href="<?php echo $list_user_accommodations_url; ?>" title="<?php _e('My Accommodations', 'bookyourtravel'); ?>"><?php _e('My Accommodations', 'bookyourtravel'); ?></a></li>
				<li class="active"><a href="<?php echo $list_user_accommodation_vacancies_url; ?>" title="<?php _e('My Vacancies', 'bookyourtravel'); ?>"><?php _e('My Vacancies', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo $submit_room_types_url; ?>" title="<?php _e('Submit Room Types', 'bookyourtravel'); ?>"><?php _e('Submit Room Types', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo $submit_accommodations_url; ?>" title="<?php _e('Submit Accommodations', 'bookyourtravel'); ?>"><?php _e('Submit Accommodations', 'bookyourtravel'); ?></a></li>
				<li><a href="<?php echo $submit_accommodation_vacancies_url; ?>" title="<?php _e('Submit Vacancies', 'bookyourtravel'); ?>"><?php _e('Submit Vacancies', 'bookyourtravel'); ?></a></li>
				<?php } ?>
			</ul>
		</nav>
		<!--//inner navigation-->		
		<!--Room list-->
		<script>
		
			function accommodationSelectRedirect(accommodationId) {
				document.location = '<?php echo $list_user_accommodation_vacancies_url; ?>?accid=' + accommodationId;
			};
		
		</script>
		<section id="MyAccommodationVacancyList" class="tab-content initial">
			<div class="filter">
				<label for="filter_user_accommodations"><?php _e('Filter by', 'bookyourtravel'); ?></label>
			<?php
			$accommodation_results = list_accommodations ( 0, 0, '', '', 0, array(), array(), false, null, $current_user->ID );
			$select_accommodations = "<select onchange='accommodationSelectRedirect(this.value)' name='filter_user_accommodations' id='filter_user_accommodations'>";
			$select_accommodations .= "<option value=''>" . __('Select accommodation', 'bookyourtravel') . "</option>";
			if ( count($accommodation_results) > 0 && $accommodation_results['total'] > 0 ) {
				foreach ($accommodation_results['results'] as $accommodation_result) {
					global $post, $accommodation_class;
					$post = $accommodation_result;
					setup_postdata( $post ); 
					$select_accommodations .= "<option value='$post->ID'>$post->post_title</option>";
				}
			}
			$select_accommodations .= "</select>";
			echo $select_accommodations;
			
			if ($accommodation_id > 0) {
				$vacancy_results = list_all_accommodation_vacancies($accommodation_id, 0, '', '', $paged, $posts_per_page);
				
				if ( count($vacancy_results) > 0 && $vacancy_results['total'] > 0 ) {
					foreach ($vacancy_results['results'] as $vacancy_result) {
						$accommodation_obj = new byt_accommodation($vacancy_result->accommodation_id);
						$is_self_catered = $accommodation_obj->get_is_self_catered();
						$is_price_per_person = $accommodation_obj->get_is_price_per_person();
						
						$room_type_obj = null;
						if (!$is_self_catered)
							$room_type_obj = new byt_room_type($vacancy_result->room_type_id);
				?>
				
				<article class="bookings vacancies">
					<h1>
						<a href="<?php echo $accommodation_obj->get_permalink(); ?>"><?php echo $accommodation_obj->get_title(); ?></a>
						<span></span>
					</h1>
					<div class="b-info">
						<table>
							<tr>
								<th><?php _e('Vacancy Id', 'bookyourtravel'); ?>:</th>
								<td>
									<?php echo $vacancy_result->Id; ?>
									<?php byt_render_link_button($submit_accommodation_vacancies_url . "?fesid=" . $vacancy_result->Id, "gradient-button", "", __('Edit', 'bookyourtravel')); ?>
								</td>
							</tr>
							<tr>
								<th><?php _e('Room type', 'bookyourtravel'); ?>:</th>
								<td><?php echo $room_type_obj == null ? __('N/A', 'bookyourtravel') : $room_type_obj->get_title(); ?></td>
							</tr>
							<tr>
								<th><?php _e('Start date', 'bookyourtravel'); ?>:</th>
								<td><?php echo $vacancy_result->start_date; ?></td>
							</tr>
							<tr>
								<th><?php _e('End date', 'bookyourtravel'); ?>:</th>
								<td><?php echo $vacancy_result->end_date; ?></td>
							</tr>
							<tr>
								<th><?php _e('Available rooms', 'bookyourtravel'); ?>:</th>
								<td><?php echo $room_type_obj == null ? __('N/A', 'bookyourtravel') : $vacancy_result->room_count; ?></td>
							</tr>
							<tr>
								<th><?php _e('Price', 'bookyourtravel'); ?>:</th>
								<td><?php echo $currency_symbol . $vacancy_result->price_per_day; ?><?php echo $is_price_per_person ? ' / ' . $currency_symbol . $vacancy_result->price_per_day_child : ''; ?></td>
							</tr>
						</table>
					</div>
				</article>
				<?php } ?>
				<nav class="page-navigation bottom-nav">
					<!--back up button-->
					<a href="#" class="scroll-to-top" title="<?php _e('Back up', 'bookyourtravel'); ?>"><?php _e('Back up', 'bookyourtravel'); ?></a> 
					<!--//back up button-->
					<!--pager-->
					<div class="pager">
						<?php 
						$total_results = $vacancy_results['total'];
						byt_display_pager( ceil($total_results/$posts_per_page) );
						?>
					</div>
				</nav>
				
				<?php
				} else {
				   echo '<p>' . __('You have not submitted any vacancies yet.', 'bookyourtravel') . '</p>';
				}
			}
			?>
			</div>
		</section>
	</section>
<?php
wp_reset_postdata();
wp_reset_query();
get_footer(); 