<?php
/**
 * @package Harwintonfair
 */

get_header();

$today   = null;
$key     = get_option( 'google_maps_api_key' );
$markers = array();

/**
 * Get future activities.
 */
$query = new WP_Query(
	array(
		'post_type'      => 'vendor',
		'posts_per_page' => '50',
		'paged'          => '1',
		'order'          => 'ASC',
	)
);

if ( $query->have_posts() ) :
	foreach ( $query->posts as $post ) {
		$latLng    = $meta     = get_post_meta( $post->ID, 'latLng', true );
		$markers[] = array(
			'latLng' => $latLng,
			'name'   => $post->post_title,
		);
	}
	?>
	<main class="wrapper<?php echo is_active_sidebar( 'sidebar' ) ? ' two-column' : null; ?>" role="main">
	<div class="event-list">
		<section class="event-day">
		<ol>
		<?php
		foreach ( $markers as $key => $marker ) :
			?>
				<li class="entry-content">
					<a href="#map" onClick="google.maps.event.trigger(markers[<?php echo $key; ?>], 'click')">
					<?php echo $marker['name']; ?>
					</a>
				</li>
				<?php endforeach; ?>
			</ol>
		</section>
	</div>
	<div id="map"></div>
	<div>
		<section>
		</section>
	</div>
	<?php if ( is_active_sidebar( 'sidebar' ) ) : ?>
	<aside>
		<?php dynamic_sidebar( 'sidebar' ); ?>
	</aside>
	<?php endif; ?>
	</main>
	<script>
		const markers = [];
		const markerData = <?php echo json_encode( $markers ); ?>;

		function initMap() {
			const infowindow = new google.maps.InfoWindow();
			const map = new google.maps.Map(document.getElementById('map'), {
				center: { lat: 41.763031, lng: -73.044465 },
				zoom: 17
			});

			/* Render saved markers */ 
			if(markers){
				markerData.forEach(elem => {
					const contentString = `<h1>${elem['name']}</h1>
					<p>Maybe we can add tags that describe the vendor?</p>`;
					
					const cords = elem['latLng'].replace('(', '').replace(')', '').split(',');
					const position = new google.maps.LatLng(cords[0], cords[1]);
					
					const marker = new google.maps.Marker({
						animation: google.maps.Animation.DROP,
						map,
						position,
						title: elem['name']
					});

					marker.addListener('click', function() {
						infowindow.setContent(contentString);
						infowindow.open(map, this);
					});

					markers.push(marker);
				})
			}
		}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo get_option( 'google_maps_api_key' ); ?>&callback=initMap"async defer></script>

<?php endif; ?>
<?php get_footer(); ?>
