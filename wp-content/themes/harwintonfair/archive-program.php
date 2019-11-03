<?php
/**
 * @package Harwintonfair
 */

get_header();

$paged  = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$events = new WP_Query(
	array(
		'post_type'      => 'program',
		'posts_per_page' => '50',
		'paged'          => $paged,
		'order'          => 'ASC',
		'orderby'        => 'meta_value',
		'meta_key'       => 'begin',
		'meta_type'      => 'DATETIME',
	)
);
$today  = null;

if ( $events->have_posts() ) : ?>
	<main class="wrapper
	<?php
	if ( is_active_sidebar( 'sidebar' ) ) {
		echo ' two-column';}
	?>
	" role="main">
	<div>
		<section class="event-day">
		<?php while ( $events->have_posts() ) : ?>
			<?php
			$events->the_post();
			$meta = get_post_meta( $post->ID );
			?>
			<?php
			$date = date( 'l, F jS', strtotime( $meta['begin'][0] ) );
			if ( $today != $date ) :
				?>
				<?php if ( ! is_null( $today ) ) : ?>
					</ol>
				</section>
				<section class="event-day">
			<?php endif; ?>
				<?php $today = $date; ?>
				<h2><?php echo $date; ?></h2>
				<ol>
				<?php endif; ?>
			<?php get_template_part( 'parts/program' ); ?>
		<?php endwhile; ?>
			</ol>
		</section>
	</div>
	<?php if ( is_active_sidebar( 'sidebar' ) ) : ?>
	<aside>
		<?php dynamic_sidebar( 'sidebar' ); ?>
	</aside>
	<?php endif; ?>
	</main>
<?php endif; ?>
<?php get_footer(); ?>
