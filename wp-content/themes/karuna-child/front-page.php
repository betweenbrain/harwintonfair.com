<?php
/**
 * The template for a static front page
 *
 * @package Karuna
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<?php 
		/* Get all sticky posts */
		$sticky = get_option( 'sticky_posts' );

		/* Sort the stickies with the newest ones at the top */
		rsort( $sticky );
	
		/* Get the 5 newest stickies (change 5 for a different number) */
		$sticky = array_slice( $sticky, 0, 5 );
	
		/* Query sticky posts */
		$the_query = new WP_Query( array( 'post__in' => $sticky, 'ignore_sticky_posts' => 1 ) );
		// The Loop
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				echo get_template_part( 'components/page/content', 'sticky' );
			}
		}
		wp_reset_postdata();
		?>
		<main id="main" class="site-main" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'components/page/content', 'page' ); ?>
			<?php endwhile; ?>

			<?php get_template_part( 'components/features/testimonials/testimonials' ); ?>

			<?php if ( (bool) 1 === (bool) get_theme_mod( 'karuna_display_recent_posts', 1 ) ) {
				get_template_part( 'components/features/recent-posts/recent-posts' );
			} ?>
		</main>
	</div>

<?php get_footer(); ?>
