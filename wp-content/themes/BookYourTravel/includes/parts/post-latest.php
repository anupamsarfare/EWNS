<?php

$show_latest_offers_posts = of_get_option('show_latest_offers_posts', '0');
$latest_offers_category =  of_get_option('latest_offers_category', '');

if ($show_latest_offers_posts && isset($latest_offers_category)) { 
	$latest_offers_count = (int)of_get_option('latest_offers_count', 4);
?>

			<!--latest offers-->
			<section class="offers clearfix full">
				<h1><?php _e('Explore our latest offers', 'bookyourtravel'); ?></h1>
				<div class="inner-wrap">
				<?php
				$args = array(
					'posts_per_page'   => $latest_offers_count,
					'paged'			   => 1,
					'offset'           => 0,
					'cat'         => $latest_offers_category,
					'orderby'          => 'date',
					'order'            => 'DESC',
					'post_type'        => 'post',
					'post_status'      => 'publish'); 

				$query = new WP_Query($args); 

				if ( $query->have_posts() ) {
					while ($query->have_posts()) {
						global $post;
						$query->the_post();
				
						if ( has_post_thumbnail($post->ID)) {
							$post_image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
							$post_image = $post_image[0];
						}
	
		?>
	<!--accommodation-->
	<article class="one-fourth">
		<div>
			<?php if (!empty($post_image)) { ?>
			<figure><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo $post_image; ?>" alt="" /></a></figure>
			<?php } ?>
			<div class="details">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><h4><?php the_title(); ?></h4></a>
				<a href="<?php the_permalink(); ?>" title="<?php _e('More info', 'bookyourtravel'); ?>" class="clearfix gradient-button"><?php _e('More info', 'bookyourtravel'); ?></a>
			</div>
		</div>
	</article>
	<!--//accommodation-->
				
<?php
		}
	} 
			?>	
				</div><!--//inner-wrap-->
			</section>
<?php } ?>