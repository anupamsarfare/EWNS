<?php
/**
 * The sidebar containing the tour widget area.
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */

global $post, $current_user, $tour_obj, $entity_obj, $currency_symbol, $price_decimal_places, $score_out_of_10, $enable_reviews;

$tour_location = $tour_obj->get_location();
$tour_location_title = '';
if ($tour_location) {
	$tour_location_title = $tour_location->get_title();
}
?>
<aside id="secondary" class="right-sidebar widget-area" role="complementary">
	<ul>
		<li>
			<article class="tour-details clearfix">
				<h1><?php echo $tour_obj->get_title(); ?></h1>
				<span class="address"><?php echo $tour_location_title; ?></span>
				<?php if ($score_out_of_10 > 0) { ?>
				<span class="rating"><?php echo $score_out_of_10; ?> / 10</span>
				<?php } ?>
				<?php byt_render_field("description", "", "", strip_tags_and_shorten($tour_obj->get_description(), 100), "", true); ?>
				<?php 
				if ($enable_reviews) {
					$reviews_by_current_user_query = list_reviews($tour_obj->get_base_id(), $current_user->ID);
					if (!$reviews_by_current_user_query->have_posts() && is_user_logged_in()) {
						byt_render_link_button("#", "gradient-button right leave-review review-tour", "", __('Leave a review', 'bookyourtravel'));
					} 
				}
				if ($tour_obj->get_custom_field('contact_email')) {
					byt_render_link_button("#", "gradient-button right contact-tour", "", __('Send inquiry', 'bookyourtravel'));
				} ?>
			</article>
			
		</li>			
		<?php if ($enable_reviews) { ?>
		<li>
			<?php 
				$all_reviews_query = list_reviews($tour_obj->get_base_id());
				if ($all_reviews_query->have_posts()) { 
					while ($all_reviews_query->have_posts()) { 
					$all_reviews_query->the_post();
					global $post;	
					$likes = get_post_meta($post->ID, 'review_likes', true); 
					$author = get_the_author();
					?>
					<!--testimonials-->
					<article class="testimonials clearfix">
						<blockquote><?php echo $likes; ?></blockquote>
						<span class="name"><?php echo $author; ?></span>
					</article>
					<!--//testimonials-->
					<?php 
						break; 
					} 
				} ?>
		</li>
		<?php } ?>
		<?php 
		wp_reset_postdata(); 
		dynamic_sidebar( 'right-tour' ); ?>
	</ul>
</aside><!-- #secondary -->