<?php
/**
 * @package Harwintonfair
 */

get_header();

$today   = date( 'Y-m-d' );
$key     = get_option( 'google_maps_api_key' );
$markers = array();

/**
 * Get future activities.
 */
$query = new WP_Query(
	array(
		'post_type'      => 'activity',
		'posts_per_page' => '50',
		'paged'          => '1',
		'order'          => 'ASC',
		'orderby'        => 'meta_value',
		'meta_query'     => array(
			array(
				// meta key like comparison
				'compare_key' => 'LIKE',
				'key'         => 'begin',
				'value'       => $today,
				'compare'     => '>',
				'type'        => 'DATETIME',
			),
		),
	)
);

if ( $query->have_posts() ) :

	/**
 * Get each activity location.
 */
	foreach ( $query->posts as $post ) {
		$location = wp_get_post_terms( $post->ID, array( 'location' ) )[0];
		$meta     = get_post_meta( $post->ID );
		$latLng   = get_term_meta( $location->term_id, 'latLng', true );

		$occurrence = [];
		foreach ( $meta as $key => $value ) {
			// Check if this field is part of a datetime set.
			if ( strpos( $key, 'begin' ) || strpos( $key, 'end' ) ) {
				$parts = explode( '_', $key );
				// Initialize empty string for this occurrence.
				if ( ! array_key_exists( $parts[0], $occurrence ) ) {
					$occurrence[ $parts[0] ] = '';
				}

				if ( strpos( $key, 'begin' ) ) {
					$date                     = new DateTime( $value[0] );
					$occurrence[ $parts[0] ] .= $date->format( 'M d, Y g:ia' );
				}

				if ( strpos( $key, 'end' ) ) {
					$date                     = new DateTime( $value[0] );
					$occurrence[ $parts[0] ] .= ' - ' . $date->format( 'g:ia' );
				}
			}
		}

		$markers[] = array(
			'occurrence' => $occurrence,
			'latLng'     => $latLng,
			'location'   => $location->name,
			'name'       => $post->post_title,
		);
	}
	?>
	<style>
		#map {
			height: 600px;
			margin: 0 auto;
			width: 600px		}
	</style>
	<main class="wrapper
	<?php
	if ( is_active_sidebar( 'sidebar' ) ) {
		echo ' two-column';}
	?>
	" role="main">
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
		const markers = <?php echo json_encode( $markers ); ?>;

		function initMap() {
			const map = new google.maps.Map(document.getElementById('map'), {
				center: { lat: 41.763031, lng: -73.044465 },
				zoom: 17
			});

			/* Render saved markers */ 
			if(markers){
				markers.forEach(elem => {
					let contentString = `<h1>${elem['name']}</h1>
					<p>At the ${elem['location']}</p>`;

					elem.occurrence.forEach(span => {
						contentString += `<p>${span}</p>`;
					})
					
					const cords = elem['latLng'].replace('(', '').replace(')', '').split(',');
					const infowindow = new google.maps.InfoWindow({
						content: contentString
					  });
					const position = new google.maps.LatLng(cords[0], cords[1]);
					
					const marker = new google.maps.Marker({
						animation: google.maps.Animation.DROP,
						map,
						position,
						title: elem['name']
					});

					marker.addListener('click', function() {
						infowindow.open(map, marker);
					  });
				})
			}
		}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo get_option( 'google_maps_api_key' ); ?>&callback=initMap"async defer></script>

<?php endif; ?>
<?php get_footer(); ?>
