<?php
/**
 * @package Harwintonfair
 */

get_header();

if (have_posts()) : ?>
	<main class="wrapper" role="main">
		<?php while (have_posts()) : the_post(); ?>
		<?php $type = get_post_type(); ?>
			<?php get_template_part('parts/' . $type); ?>
		<?php endwhile; ?>
	</main>
<?php endif; ?>
<?php get_footer(); ?>