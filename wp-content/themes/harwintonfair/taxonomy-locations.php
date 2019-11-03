<?php
get_header();

$term   = get_queried_object();
$events = new WP_Query(
	array(
		'posts_per_page' => -1,
		'post_type'      => 'program',
		'order'          => 'ASC',
		'orderby'        => 'meta_value',
		'meta_key'       => 'begin',
		'meta_type'      => 'DATETIME',
		'tax_query'      => array(
			array(
				'taxonomy' => 'locations',
				'field'    => 'term_id',
				'terms'    => $term->term_id,
			),
		),
	)
);
$today  = null;
if ( $events->have_posts() ) : ?>
	<main class="wrapper" role="main">
        <a href="/program">Back to Fair Program</a>
		<h1>Events at the <?php echo $term->name; ?></h1>
		<?php
		while ( $events->have_posts() ) :
			$events->the_post();
			?>
			<?php $meta = get_post_meta( $post->ID ); ?>
			<?php
			$date = date( 'l, F jS', strtotime( $meta['begin'][0] ) );
			if ( $today != $date ) :
				?>
				<?php if ( ! is_null( $today ) ) : ?>
					</ol>
                <?php endif; ?>
				<?php $today = $date; ?>
				<h2><?php echo $date; ?></h2>
				<ol>
			<?php endif; ?>
			<?php get_template_part( 'parts/program' ); ?>
		<?php endwhile; ?>
			</ol>
	</main>
<?php endif; ?>
<?php get_footer(); ?>
