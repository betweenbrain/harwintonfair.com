<?php
/**
 * @package Harwintonfair
 */

get_header();

/*
// Load template part based on request

global $wp;
$current_url = add_query_arg( array(), $wp->request );

// echo '<pre>' . print_r($wp->request, true) . '</pre>';
// Add logic here:

$load = locate_template( 'archive-activity.php', true );
if ( $load ) {
	// just exit if template was found and loaded
	exit();
}
*/

if ( have_posts() ) : ?>
	<main class="wrapper" role="main">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<?php $type = get_post_type(); ?>
			<?php get_template_part( 'parts/' . $type ); ?>
		<?php endwhile; ?>
	</main>
<?php endif; ?>
<?php get_footer(); ?>