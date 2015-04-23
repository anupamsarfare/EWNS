<?php
	global $entity_obj, $score_out_of_10;
	$base_id = $entity_obj->get_base_id();
	$reviews_total = get_reviews_count($base_id);
	$post_type = $entity_obj->get_entity_type();
	
	$guest_reviews_info = '';
	if ($post_type == 'accommodation')
		$guest_reviews_info = __('Guest reviews are written by our customers <strong>after their stay</strong> at %s.', 'bookyourtravel');
	elseif ($post_type == 'tour')
		$guest_reviews_info = __('Guest reviews are written by our customers <strong>after their tour</strong> of %s.', 'bookyourtravel');
	elseif ($post_type == 'cruise')
		$guest_reviews_info = __('Guest reviews are written by our customers <strong>after their voyage</strong> on %s.', 'bookyourtravel');
	
	if ($reviews_total > 0) {
	?>
	<article>
		<h1><?php _e(ucfirst($entity_obj->get_entity_type()) . ' review scores and score breakdown', 'bookyourtravel'); ?></h1>
		<div class="score">
		<?php 
			$review_score = $entity_obj->get_custom_field('review_score', false, true);
			$score_out_of_10 = round($review_score * 10);
		?>
			<span class="achieved"><?php echo $score_out_of_10; ?></span><span> / 10</span>
			<p class="info"><?php echo sprintf(__('Based on %d reviews', 'bookyourtravel'), $reviews_total); ?></p>
			<p class="disclaimer"><?php echo sprintf($guest_reviews_info, $entity_obj->get_title()); ?></p>
		</div>		
		<dl class="chart">
			<?php 
			$total_possible = $reviews_total * 10;	
			
			$review_fields = list_review_fields($post_type, true);
			foreach ($review_fields as $review_field) {
				$field_id = $review_field['id'];
				$field_label = $review_field['label'];
				$field_value = intval($total_possible > 0 ? (sum_review_meta_values($base_id, $field_id) / $total_possible) * 10 : 0);
			?>
			<dt><?php echo $field_label; ?></dt>
			<dd><span style="width:<?php echo $field_value * 10; ?>%;"><?php echo $field_value; ?>&nbsp;&nbsp;&nbsp;</span></dd>
			<?php
			}
			?>
		</dl>
	</article>
	<article>
		<h1><?php _e('Guest reviews', 'bookyourtravel');?></h1>
		<ul class="reviews">
			<!--review-->
			<?php
			$reviews_query = list_reviews($base_id);
			while ($reviews_query->have_posts()) : 
				global $post;
				$reviews_query->the_post();
			?>
			<li>
				<figure class="left"><?php echo get_avatar( get_the_author_meta( 'ID' ), 70 ); ?></figure>
				<address><span><?php the_author(); ?></span><br /><?php echo get_the_date('Y-m-d'); ?><br /><br /></address>
				<div class="pro"><p><?php echo get_post_meta($post->ID, 'review_likes', true); ?></p></div>
				<div class="con"><p><?php echo get_post_meta($post->ID, 'review_dislikes', true); ?></p></div>
			</li>
			<!--//review-->
			<?php endwhile; 
				// Reset Second Loop Post Data
				wp_reset_postdata(); 
			?>
		</ul>
	</article>
<?php } else { ?>
	<article>
	<h3><?php echo sprintf(__('We are sorry, there are no reviews yet for this %s.', 'bookyourtravel'), $post_type); ?></h3>
	</article>
<?php } ?>