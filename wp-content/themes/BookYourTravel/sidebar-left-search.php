<?php
/**
 * The sidebar containing the left widget area for custom search results page.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
?>
<?php if ( is_active_sidebar( 'left-search' ) ) { ?>
	<aside id="secondary" class="left-sidebar widget-area" role="complementary">
		<ul>
		<?php dynamic_sidebar( 'left-search' ); ?>
		</ul>
	</aside><!-- #secondary -->
<?php } else { ?>
	<aside class="left-sidebar">
		<?php 
		$widget_args = array(
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		);
		the_widget('byt_Search_Widget', null, $widget_args); 
		?>
	</aside>
<?php } 