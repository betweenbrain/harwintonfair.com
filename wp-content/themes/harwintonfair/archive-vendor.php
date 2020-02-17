<?php
/**
 * @package Harwintonfair
 */

get_header();

$today   = null;
$key     = get_option( 'google_maps_api_key' );
$vendors = array();

/**
 * Get future activities.
 */
$args = array(
	'post_type'      => 'vendor',
	'posts_per_page' => '50',
	'paged'          => '1',
	'order'          => 'ASC',
);

$vendor_category = get_query_var( 'vendor_category' );
$vendor_type     = get_query_var( 'vendor_type' );

if ( $vendor_category || $vendor_type ) {
	$args['tax_query'] = [];

	if ( $vendor_category ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'vendor_category',
			'field'    => 'slug',
			'terms'    => $vendor_category,
		);
	}

	if ( $vendor_type ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'vendor_type',
			'field'    => 'slug',
			'terms'    => $vendor_type,
		);
	}
}

if ( $vendor_category && $vendor_type ) {
	$args['tax_query']['relation'] = 'AND';
}

$query = new WP_Query( $args );

if ( $query->have_posts() ) :

	// Get vendor taxonomies to act as filters.
	$types      = get_terms(
		array(
			'taxonomy'   => 'vendor_type',
			'hide_empty' => true,
		)
	);
	$categories = get_terms(
		array(
			'taxonomy'   => 'vendor_category',
			'hide_empty' => true,
		)
	);

	foreach ( $query->posts as $post ) {
		$latLng         = get_post_meta( $post->ID, 'latLng', true );
		$postCategories = get_the_terms( $post->ID, 'vendor_category' );
		$postTypes      = get_the_terms( $post->ID, 'vendor_type' );
		$vendors[]      = array(
			'latLng' => $latLng,
			'name'   => $post->post_title,
			'tags'   => array_merge( $postTypes, $postCategories ),
		);
	}?>
	<main class="wrapper<?php echo is_active_sidebar( 'sidebar' ) ? ' two-column' : null; ?>" role="main">
		<div class="three-column">	
			<div class="vendors">
				<h2>Vendors</h2>
				<ul>
				<?php foreach ( $vendors as $key => $marker ) : ?>
					<li class="entry-content">
						<a onClick="google.maps.event.trigger(markers[<?php echo $key; ?>], 'click')">
						<?php echo $marker['name']; ?>
						</a>
					</li>
				<?php endforeach; ?>
				</ul>
			</div>
			<div class="tags">
				<h2>Filters</h2>
				<ul>
					<li class="entry-content">
						<a href="
						<?php
						echo add_query_arg(
							array(
								'vendor_type'     => null,
								'vendor_category' => null,
							)
						);
						?>
						">Reset Filters</a>
					</li>
				<?php foreach ( $types as $key => $type ) : ?>
					<li class="entry-content">
						<?php // echo '<pre>' . print_r($type, true) . '</pre>'; ?>
						<a href="<?php echo add_query_arg( 'vendor_type', $type->slug ); ?>">
						<?php echo $type->name; ?>
						</a>
					</li>
				<?php endforeach; ?>
				<?php foreach ( $categories as $key => $category ) : ?>
					<li class="entry-content">
						<a href="<?php echo add_query_arg( 'vendor_category', $category->slug ); ?>">
						<?php echo $category->name; ?>
						</a>
					</li>
				<?php endforeach; ?>
				</ul>
			</div>
			<div id="map"></div>
		</div>
		<?php if ( is_active_sidebar( 'sidebar' ) ) : ?>
		<aside>
			<?php dynamic_sidebar( 'sidebar' ); ?>
		</aside>
		<?php endif; ?>
	</main>
	<script>
		const markers = [];
		const markerData = <?php echo json_encode( $vendors ); ?>;

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
