<?php 
get_header(); 
byt_breadcrumbs();
get_sidebar('under-header');

global $post, $current_user, $cruise_obj, $entity_obj, $currency_symbol, $price_decimal_places, $score_out_of_10, $enable_reviews, $default_cruise_tabs, $default_cruise_extra_fields;

$cruise_extra_fields = of_get_option('cruise_extra_fields');
if (!is_array($cruise_extra_fields) || count($cruise_extra_fields) == 0)
	$cruise_extra_fields = $default_cruise_extra_fields;

$tab_array = of_get_option('cruise_tabs');
if (!is_array($tab_array) || count($tab_array) == 0)
	$tab_array = $default_cruise_tabs;

if ( have_posts() ) {

	the_post();
	$cruise_obj = new byt_cruise($post);
	$cruise_id = $cruise_obj->get_id();
	$entity_obj = $cruise_obj;
	$cruise_date_from = date('Y-m-d', strtotime("+0 day", time()));
	$cruise_date_from_year = date('Y', strtotime("+0 day", time()));
	$cruise_date_from_month = date('n', strtotime("+0 day", time()));

?>
	<script>
		window.postType = 'cruise';
	</script>
<?php
	
	if ($enable_reviews) {
		get_template_part('includes/parts/review', 'form'); 
	}
	get_template_part('includes/parts/inquiry', 'form');
	?>
	<!--cruise three-fourth content-->
	<section class="three-fourth">
		<?php
		get_template_part('includes/parts/cruise', 'booking-form');
		get_template_part('includes/parts/cruise', 'confirmation-form');
		?>	
		<script>
			window.formSingleError = <?php echo json_encode(__('You failed to provide 1 field. It has been highlighted below.', 'bookyourtravel')); ?>;
			window.formMultipleError = <?php echo json_encode(__('You failed to provide {0} fields. They have been highlighted below.', 'bookyourtravel'));  ?>;
			window.cruiseId = <?php echo $cruise_obj->get_id(); ?>;
			window.cruiseIsPricePerPerson = <?php echo $cruise_obj->get_is_price_per_person(); ?>;
			window.cruiseDateFrom = <?php echo json_encode($cruise_date_from); ?>;
			window.cruiseTitle = <?php echo json_encode($cruise_obj->get_title()); ?>;
			window.currentMonth = <?php echo date('n'); ?>;
			window.currentYear = <?php echo date('Y'); ?>;
			window.currentDay = <?php echo date('j'); ?>;
		</script>
		<?php $cruise_obj->render_image_gallery(); ?>
		<!--inner navigation-->
		<nav class="inner-nav">
			<ul>
				<?php
				do_action( 'byt_show_single_cruise_tab_items_before' );
				$first_display_tab = '';			
				$i = 0;
				if (is_array($tab_array) && count($tab_array) > 0) {
					foreach ($tab_array as $tab) {
					
						if (!isset($tab['hide']) || $tab['hide'] != '1') {
					
							$tab_label = '';
							if (isset($tab['label'])) {
								$tab_label = $tab['label'];
								$tab_label = get_translated_dynamic_string(get_option_id_context('cruise_tabs') . ' ' . $tab['label'], $tab_label);
							}
						
							if($i==0)
								$first_display_tab = $tab['id'];
							if ($tab['id'] == 'reviews' && $enable_reviews) {
								byt_render_tab("cruise", $tab['id'], '',  '<a href="#' . $tab['id'] . '" title="' . $tab_label . '">' . $tab_label . '</a>');
							} else {
								byt_render_tab("cruise", $tab['id'], '',  '<a href="#' . $tab['id'] . '" title="' . $tab_label . '">' . $tab_label . '</a>');
							}
						}
						$i++;
					}
				} 				
				do_action( 'byt_show_single_cruise_tab_items_after' ); 
				?>
			</ul>
		</nav>
		<!--//inner navigation-->
		<?php do_action( 'byt_show_single_cruise_tab_content_before' ); ?>
		<!--description-->
		<section id="description" class="tab-content <?php echo $first_display_tab == 'description' ? 'initial' : ''; ?>">
			<article>
				<?php do_action( 'byt_show_single_cruise_description_before' ); ?>
				<?php byt_render_field("text-wrap", "", "", $cruise_obj->get_description(), __('General', 'bookyourtravel')); ?>
				<?php byt_render_tab_extra_fields('cruise_extra_fields', $cruise_extra_fields, 'description', $cruise_obj); ?>
				<?php do_action( 'byt_show_single_cruise_description_after' ); ?>
			</article>
		</section>
		<!--//description-->
		<!--availability-->
		<section id="availability" class="tab-content <?php echo $first_display_tab == 'availability' ? 'initial' : ''; ?>">
			<article>
				<?php do_action( 'byt_show_single_cruise_availability_before' ); ?>
				<h1><?php _e('Available departures', 'bookyourtravel'); ?></h1>
				<?php byt_render_field("text-wrap", "", "", $cruise_obj->get_custom_field('availability_text'), '', false, true); ?>
				<form id="launch-cruise-booking" action="#" method="POST">
					<div class="text-wrap">
						<?php 
						
						if ($cruise_obj->get_type_is_repeated() == 1) {
							echo __('<p>This is a daily cruise.</p>', 'bookyourtravel'); 
						} else if ($cruise_obj->get_type_is_repeated() == 2) {
							echo __('<p>This cruise is repeated every weekday (working day).</p>', 'bookyourtravel'); 
						} else if ($cruise_obj->get_type_is_repeated() == 3) {
							echo sprintf(__('<p>This cruise is repeated every week on a %s.</p>', 'bookyourtravel'), $cruise_obj->get_type_day_of_week_day()); 
						}
						
						$cabin_type_ids = $cruise_obj->get_cabin_types();
						if ($cabin_type_ids && count($cabin_type_ids) > 0) { ?>
						<ul class="cabin-types room-types">
							<?php 
							// Loop through the items returned				
							for ( $z = 0; $z < count($cabin_type_ids); $z++ ) {
								$cabin_type_id = $cabin_type_ids[$z];
								$cabin_type_obj = new byt_cabin_type(intval($cabin_type_id));
								$cabin_type_min_price = number_format (get_cruise_min_price($cruise_id, $cabin_type_id, $cruise_date_from), $price_decimal_places, ".", "");
							?>
							<!--cabin_type-->
							<li id="cabin_type_<?php echo $cabin_type_id; ?>">
								<?php if ($cabin_type_obj->get_main_image('medium')) { ?>
								<figure class="left"><img src="<?php echo $cabin_type_obj->get_main_image('medium') ?>" alt="" /><a href="<?php echo $cabin_type_obj->get_main_image(); ?>" class="image-overlay" rel="prettyPhoto[gallery1]"></a></figure>
								<?php } ?>
								<div class="meta cabin_type room_type">
									<h2><?php echo $cabin_type_obj->get_title(); ?></h2>
									<?php byt_render_field('', '', '', $cabin_type_obj->get_custom_field('meta'), '', true, true); ?>
									<?php byt_render_link_button("#", "more-info", "", __('+ more info', 'bookyourtravel')); ?>
								</div>
								<div class="cabin-information room-information">
									<div class="row">
										<span class="first"><?php _e('Max:', 'bookyourtravel'); ?></span>
										<span class="second">
											<?php for ( $j = 0; $j < $cabin_type_obj->get_custom_field('max_count'); $j++ ) { ?>
											<img src="<?php echo get_byt_file_uri('/images/ico/person.png'); ?>" alt="" />
											<?php } ?>
										</span>
									</div>
									<?php if ($cabin_type_min_price > 0) { ?>
									<div class="row">
										<span class="first"><?php _e('Price from:', 'bookyourtravel'); ?></span>
										<div class="second price">
											<em><span class="curr"><?php echo $currency_symbol; ?></span>
											<span class="amount"><?php echo $cabin_type_min_price; ?></span></em>
											<input type="hidden" class="max_count" value="<?php echo $cabin_type_obj->get_custom_field('max_count'); ?>" />
											<input type="hidden" class="max_child_count" value="<?php echo $cabin_type_obj->get_custom_field('max_child_count'); ?>" />
										</div>
									</div>
									<?php byt_render_link_button("#", "gradient-button book-cruise", "book-cruise-$cabin_type_id", __('Book', 'bookyourtravel')); ?>
									<?php } ?>
								</div>
								<div class="more-information">
									<?php byt_render_field('', '', __('Cabin facilities:', 'bookyourtravel'), $cabin_type_obj->get_facilities_string(), '', true, true); ?>
									<?php echo $cabin_type_obj->get_description(); ?>
									<?php byt_render_field('', '', __('Bed size:', 'bookyourtravel'), $cabin_type_obj->get_custom_field('bed_size'), '', true, true); ?>
									<?php byt_render_field('', '', __('Cabin size:', 'bookyourtravel'), $cabin_type_obj->get_custom_field('cabin_size'), '', true, true); ?>
								</div>
							</li>
							<!--//cabin-->
							<?php 
							} 
							// Reset Second Loop Post Data
							wp_reset_postdata(); 
							// end while ?>
						</ul>	
						<?php 
						} else { 
							byt_render_field('text-wrap', '', '', __('We are sorry, there are no cabins available at this cruise at the moment', 'bookyourtravel'), '', true, true);
						} 

						?>
					</div>
				</form>
				<?php byt_render_tab_extra_fields('cruise_extra_fields', $cruise_extra_fields, 'availability', $cruise_obj); ?>
				<?php do_action( 'byt_show_single_cruise_availability_after' ); ?>

			</article>
		</section>
		<!--//availability-->
		<!--facilities-->
		<section id="facilities" class="tab-content <?php echo $first_display_tab == 'facilities' ? 'initial' : ''; ?>">
			<article>
				<?php do_action( 'byt_show_single_cruise_facilites_before' ); ?>
				<?php 
				$facilities = $cruise_obj->get_facilities();
				if ($facilities && count($facilities) > 0) { ?>
				<h1><?php _e('Facilities', 'bookyourtravel'); ?></h1>
				<div class="text-wrap">	
					<ul class="three-col">
					<?php
					for( $i = 0; $i < count($facilities); $i++) {
						$facility = $facilities[$i];
						echo '<li>' . $facility->name  . '</li>';
					} ?>					
					</ul>
				</div>
				<?php } // endif (!empty($facilities)) ?>			
				<?php byt_render_tab_extra_fields('cruise_extra_fields', $cruise_extra_fields, 'facilities', $cruise_obj); ?>			
				<?php do_action( 'byt_show_single_cruise_facilites_after' ); ?>
			</article>
		</section>
		<!--//facilities-->
		<?php if ($enable_reviews) { ?>
		<!--reviews-->
		<section id="reviews" class="tab-content <?php echo $first_display_tab == 'reviews' ? 'initial' : ''; ?>">
			<?php 
			do_action( 'byt_show_single_cruise_reviews_before' );
			get_template_part('includes/parts/review', 'item'); 
			byt_render_tab_extra_fields('cruise_extra_fields', $cruise_extra_fields, 'reviews', $cruise_obj); 
			do_action( 'byt_show_single_cruise_reviews_after' ); 
			?>
		</section>
		<!--//reviews-->
		<?php } // if ($enable_reviews) ?>
		<?php
		foreach ($tab_array as $tab) {
			if (count(byt_array_search($default_cruise_tabs, 'id', $tab['id'])) == 0) {
			?>
				<section id="<?php echo $tab['id']; ?>" class="tab-content <?php echo ($first_display_tab == $tab['id'] ? 'initial' : ''); ?>">
					<article>
						<?php do_action( 'byt_show_single_cruise_' . $tab['id'] . '_before' ); ?>
						<?php byt_render_tab_extra_fields('cruise_extra_fields', $cruise_extra_fields, $tab['id'], $cruise_obj); ?>
						<?php do_action( 'byt_show_single_cruise_' . $tab['id'] . '_after' ); ?>
					</article>
				</section>
			<?php
			}
		}	
		do_action( 'byt_show_single_cruise_tab_content_after' ); ?>
	</section>
	<!--//cruise content-->	
<?php
	get_sidebar('right-cruise'); 
} // end if
get_footer(); 