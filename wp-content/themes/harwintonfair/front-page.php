<?php
/**
 * @package Harwintonfair
 */
get_header(); ?>
<div id="primary" class="content-area">
	<?php
	/* Get all sticky posts */
	$sticky = get_option( 'sticky_posts' );

	if ( $sticky ) {
		/* Sort the stickies with the newest ones at the top */
		rsort( $sticky );

		/* Get the 5 newest stickies (change 5 for a different number) */
		$sticky = array_slice( $sticky, 0, 5 );

		$query = new WP_Query(
			array(
				'posts_per_page'      => 1,
				'post__in'            => $sticky,
				'ignore_sticky_posts' => 1,
			)
		);
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				echo get_template_part( 'parts/sticky' );
			}
		}
		wp_reset_postdata();
	}
	?>
	<main class="wrapper
	<?php
	if ( is_active_sidebar( 'sidebar' ) ) {
		echo ' two-column';} ?>" role="main">
	<div>
		<?php
		/**
		 * Show today's program if any.
		 */
		$today = date( 'Y-m-d' );
		$query = new WP_Query(
			array(
				'post_type'      => 'program',
				'posts_per_page' => '50',
				'paged'          => '1',
				'order'          => 'ASC',
				'orderby'        => 'meta_value',
				'meta_key'       => 'begin',
				'meta_type'      => 'DATETIME',
				'meta_compare'   => 'LIKE',
				'meta_value'     => $today,
			)
		);
		if ( $query->have_posts() ) : ?>
		<div>
			<h1>Today's Fair Program - <?php echo date( 'l, F jS', strtotime( $today ) );?></h1>
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				?>
				<section class="event-day">
				<ol>
					<?php get_template_part( 'parts/program-list-item' ); ?>
				</ol>
			</section>
				<?php endwhile; ?>
			</div>
			<?php
		endif;
		wp_reset_postdata();
		?>
		<?php
		/**
		 * Show sticky posts if any.
		 */
		$category = get_theme_mod( 'featured_category' );
		$query    = new WP_Query(
			array(
				'posts_per_page'      => 4,
				'cat'                 => $category,
				'ignore_sticky_posts' => 1,
			)
		);
		if ( $query->have_posts() ) : ?>
		<div>
			<?php
			while ( $query->have_posts() ) {
				$query->the_post();
				echo get_template_part( 'parts/page' );
			}
			?>
		</div>
		<?php endif;
		wp_reset_postdata(); ?>
		</div>
		<?php if ( is_active_sidebar( 'sidebar' ) ) : ?>
		<aside>
			<?php dynamic_sidebar( 'sidebar' ); ?>
		</aside>
		<?php endif; ?>
	</main>
</div>
<?php get_footer(); ?>
