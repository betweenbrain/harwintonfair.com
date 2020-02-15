<?php

/**
 * Template part for displaying program items as part of a list.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Karuna
 */
$meta      = get_post_meta( $post->ID );
$locations = wp_get_post_terms( $post->ID, 'locations' );
$date      = ( in_array( '0_end', $meta ) )
	? date( 'l, F jS', strtotime( $meta['0_begin'][0] ) ) . ' at ' . date( 'g:i A', strtotime( $meta['0_begin'][0] ) ) . ' - ' . date( 'g:i A', strtotime( $meta['end'][0] ) )
	: date( 'l, F jS', strtotime( $meta['0_begin'][0] ) ) . ' at ' . date( 'g:i A', strtotime( $meta['0_begin'][0] ) );
?>
<li class="entry-content">
	<?php echo date( 'g:ia', strtotime( $meta['0_begin'][0] ) ); ?>
	<?php if ( isset( $meta['end'] ) ) : ?>
			until <?php echo date( 'g:ia', strtotime( $meta['end'][0] ) ); ?>
	<?php endif; ?>
	<?php
	$post->post_content
	? the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a>' )
	: the_title();
	?>
	<?php if ( sizeof( $locations ) > 0 ) : ?>
		at the
		<?php foreach ( $locations as $term ) : ?>
			<a href="/program/location/<?php echo $term->slug; ?>"><?php echo $term->name; ?></a>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php if ( $post->post_excerpt ) : ?>
	<em><?php echo $post->post_excerpt; ?></em>
<?php endif; ?>
</li>
