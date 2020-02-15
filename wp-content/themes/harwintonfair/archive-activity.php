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
				'value'       => date( 'Y-m-d' ),
				'compare'     => '>',
				'type'        => 'DATETIME',
			),
		),
	)
);

if ( $query->have_posts() ) :
	foreach ( $query->posts as $post ) {
		$location = wp_get_post_terms( $post->ID, array( 'location' ) )[0];
		$meta     = get_post_meta( $post->ID );
		$latLng   = get_term_meta( $location->term_id, 'latLng', true );

		$occurrence = [];
		foreach ( $meta as $key => $value ) {
			// Check if this field is part of a datetime set.
			if ( strpos( $key, 'begin' ) || strpos( $key, 'end' ) ) {
				$parts      = explode( '_', $key );
				$occurrence = '';

				if ( strpos( $key, 'begin' ) ) {
					$date        = new DateTime( $value[0] );
					$day         = $date->format( 'l, F jS' );
					$occurrence .= $date->format( 'M d, Y g:ia' );
					$time        = $date->format( 'g:ia' );
				}

				if ( strpos( $key, 'end' ) ) {
					$date        = new DateTime( $value[0] );
					$occurrence .= ' - ' . $date->format( 'g:ia' );
				}

				$markers[] = array(
					'date'       => $day,
					'occurrence' => $occurrence,
					'latLng'     => $latLng,
					'location'   => $location->name,
					'name'       => $post->post_title,
					'time'       => $time,
				);
			}
		}
	}
	?>
	<main class="wrapper<?php echo is_active_sidebar( 'sidebar' ) ? ' two-column' : null; ?>" role="main">
	<div class="event-list">
		<section class="event-day">
		<?php
		foreach ( $markers as $key => $marker ) :
			if ( $today != $marker['date'] ) :
				?>
				<?php if ( ! is_null( $today ) ) : ?>
					</ol>
				</section>
				<section class="event-day">
			<?php endif; ?>
				<?php $today = $marker['date']; ?>
				<h2><?php echo $marker['date']; ?></h2>
				<ol>
				<?php endif; ?>
				<li class="entry-content">
					<?php echo $marker['time']; ?>
					<?php echo $marker['name']; ?>
					at the <a href="#map" onClick="google.maps.event.trigger(markers[<?php echo $key; ?>], 'click')">
					<?php echo $marker['location']; ?>
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
			const map = new google.maps.Map(document.getElementById('map'), {
				center: { lat: 41.763031, lng: -73.044465 },
				zoom: 17
			});

			/* Render saved markers */ 
			if(markers){
				markerData.forEach(elem => {
					const contentString = `<h1>${elem['name']}</h1>
					<p>At the ${elem['location']}</p>
					<p>${elem.occurrence}</p>`;
					
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

					  markers.push(marker);
				})
			}
		}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo get_option( 'google_maps_api_key' ); ?>&callback=initMap"async defer></script>

<?php endif; ?>
<?php get_footer(); ?>
