<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php the_title(); ?></title>
	<meta name="description" content="<?php echo strip_tags( get_the_excerpt() ); ?>">
	<meta name="author" content="<?php the_title(); ?>">
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-143005804-1"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'UA-143005804-1');
	</script>
	<?php wp_head(); ?>
	<?php
	if ( $bar_color = get_theme_mod( 'bar_color' ) ) :
		?>
		<style type="text/css">
			.bar {
				background-color: 
				<?php
				echo $bar_color;
				?>
				}
		</style>
		<?php
	endif;
	?>
</head>

<body>
	<?php
	if ( $description = get_bloginfo( 'description', 'display' ) ) :
		?>
		<div class="headline bar top-bar" id="top">
			<div class="wrapper">
				<?php echo $description; ?>
				<ul id="menu-social-menu" class="menu">
					<li>
						<a href="https://www.youtube.com/channel/UCc8obEhKof48LrPH4UzTgwQ">
							<span>YouTube</span>
							<svg id="icon-youtube" viewBox="0 0 24 24">
								<path d="M21.8,8.001c0,0-0.195-1.378-0.795-1.985c-0.76-0.797-1.613-0.801-2.004-0.847c-2.799-0.202-6.997-0.202-6.997-0.202 h-0.009c0,0-4.198,0-6.997,0.202C4.608,5.216,3.756,5.22,2.995,6.016C2.395,6.623,2.2,8.001,2.2,8.001S2,9.62,2,11.238v1.517 c0,1.618,0.2,3.237,0.2,3.237s0.195,1.378,0.795,1.985c0.761,0.797,1.76,0.771,2.205,0.855c1.6,0.153,6.8,0.201,6.8,0.201 s4.203-0.006,7.001-0.209c0.391-0.047,1.243-0.051,2.004-0.847c0.6-0.607,0.795-1.985,0.795-1.985s0.2-1.618,0.2-3.237v-1.517 C22,9.62,21.8,8.001,21.8,8.001z M9.935,14.594l-0.001-5.62l5.404,2.82L9.935,14.594z"></path>
							</svg>
						</a>
					</li>
					<li>
						<a href="https://www.facebook.com/HarwintonFair/">
							<span>Facebook</span>
							<svg id="icon-facebook" viewBox="0 0 24 24">
								<path d="M20.007,3H3.993C3.445,3,3,3.445,3,3.993v16.013C3,20.555,3.445,21,3.993,21h8.621v-6.971h-2.346v-2.717h2.346V9.31 c0-2.325,1.42-3.591,3.494-3.591c0.993,0,1.847,0.074,2.096,0.107v2.43l-1.438,0.001c-1.128,0-1.346,0.536-1.346,1.323v1.734h2.69 l-0.35,2.717h-2.34V21h4.587C20.555,21,21,20.555,21,20.007V3.993C21,3.445,20.555,3,20.007,3z"></path>
							</svg>
						</a>
					</li>
				</ul>
			</div>
		</div>
	<?php endif; ?>
	<?php if ( has_custom_logo() || is_active_sidebar( 'main_menu' ) ) : ?>
		<div class="sticky-header">
			<div class="wrapper">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'main_menu' ) ) : ?>
					<nav class="main-navigation" id="menu" role="navigation">
						<a href="#menu" aria-label="Open main menu" class="toggle open">Menu</a>
						<a href="#top" aria-label="Close main menu" class="toggle close">Close</a>
						<?php dynamic_sidebar( 'main_menu' ); ?>
					</nav>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if ( is_active_sidebar( 'call_to_action' ) ) : ?>
		<div class="call-to-action">
			<?php dynamic_sidebar( 'call_to_action' ); ?>
		</div>
	<?php endif; ?>
