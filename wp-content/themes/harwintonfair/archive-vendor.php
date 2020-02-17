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
	$filters    = array_merge( $types, $categories );

	foreach ( $query->posts as $post ) {
		$latLng         = get_post_meta( $post->ID, 'latLng', true );
		$postCategories = get_the_terms( $post->ID, 'vendor_category' );
		$postTypes      = get_the_terms( $post->ID, 'vendor_type' );
		$tags           = [];

		foreach ( $postTypes as $type ) {
			$tags[] = $type->slug;
		}

		foreach ( $postCategories as $category ) {
			$tags[] = $category->slug;
		}

		$vendors[] = array(
			'latLng' => $latLng,
			'name'   => $post->post_title,
			'tags'   => $tags,
		);
	}?>
	<main class="wrapper<?php echo is_active_sidebar( 'sidebar' ) ? ' two-column' : null; ?>" role="main">
		<div class="grid">	
			<div class="span-two">
				<h2>Vendors</h2>
				<h3>Filter By:</h3>
				<select onchange="filterMarkers(this.value);">
					<option value="">Reset Filter</option>
					<?php foreach ( $filters as $filter ) : ?>
						<option value="<?php echo $filter->slug; ?>"><?php echo $filter->name; ?></option>
					<?php endforeach; ?>
				</select>
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
			<div class="span-ten" id="map"></div>
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

		filterMarkers = function(val) {
			for (i = 0; i < markerData.length; i++) {
				console.log(val);
				console.log(markerData[i]);
				if(markerData[i].tags.indexOf(val) >= 0 || !val){
					markers[i].setVisible(true);
				} else {          
					markers[i].setVisible(false);
				}  
			}
		}

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
