<?php

/**
 * Plugin Name: Event Management
 * Description: Matt Thomas' event management plugin.
 * Version: 0.0.1
 * Author: Matt Thomas
 * Text Domain: mattsevents
 */
defined( 'ABSPATH' ) or die();

/**
 * Register activity custom post type.
 */
add_action(
	'init', function () {
		$args = array(
			'has_archive'       => true,
			'labels'            => array(
				'add_new'      => __( 'Add Activity', 'mattsevents' ),
				'add_new_item' => __( 'New Activity', 'mattsevents' ),
				'all_items'    => __( 'Activities', 'mattsevents' ),
				'edit_item'    => __( 'Edit Activity', 'mattsevents' ),
				'name'         => __( 'Events', 'mattsevents' ),
			),
			'public'            => true,
			'show_in_nav_menus' => true,
			'supports'          => array(
				'editor',
				'excerpt',
				'revisions',
				'title',
				'thumbnail',
			),
			'taxonomies'        => array(
				'events',
				'locations',
			),
		);
		register_post_type( 'activity', $args );
	}
);

/**
 * Register Location taxonomy.
 */
add_action(
	'init',
	function () {
		$labels = array(
			'name'                       => __( 'Locations', 'mattsevents' ),
			'singular_name'              => __( 'Location', 'mattsevents' ),
			'search_items'               => __( 'Search Locations', 'mattsevents' ),
			'popular_items'              => __( 'Popular Locations', 'mattsevents' ),
			'all_items'                  => __( 'All Locations', 'mattsevents' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Location', 'mattsevents' ),
			'update_item'                => __( 'Update Location', 'mattsevents' ),
			'add_new_item'               => __( 'Add New Location', 'mattsevents' ),
			'new_item_name'              => __( 'New Location Name', 'mattsevents' ),
			'separate_items_with_commas' => __( 'Separate locations with commas', 'mattsevents' ),
			'add_or_remove_items'        => __( 'Add or remove locations', 'mattsevents' ),
			'choose_from_most_used'      => __( 'Choose from the most used locations', 'mattsevents' ),
			'menu_name'                  => __( 'Locations', 'mattsevents' ),
		);

		register_taxonomy(
			'location', 'activity', array(
				'hierarchical'          => false,
				'labels'                => $labels,
				'show_ui'               => true,
				'show_admin_column'     => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var'             => true,
				// 'rewrite'               => array( 'slug' => 'program/location' ),
			)
		);
	},
	0
);

/**
 * Register Event taxonomy.
 */
add_action(
	'init',
	function () {
		$labels = array(
			'name'                       => __( 'Events', 'mattsevents' ),
			'singular_name'              => __( 'Event', 'mattsevents' ),
			'search_items'               => __( 'Search Events', 'mattsevents' ),
			'popular_items'              => __( 'Popular Events', 'mattsevents' ),
			'all_items'                  => __( 'All Events', 'mattsevents' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Event', 'mattsevents' ),
			'update_item'                => __( 'Update Event', 'mattsevents' ),
			'add_new_item'               => __( 'Add New Event', 'mattsevents' ),
			'new_item_name'              => __( 'New Event Name', 'mattsevents' ),
			'separate_items_with_commas' => __( 'Separate events with commas', 'mattsevents' ),
			'add_or_remove_items'        => __( 'Add or remove events', 'mattsevents' ),
			'choose_from_most_used'      => __( 'Choose from the most used events', 'mattsevents' ),
			'menu_name'                  => __( 'Events', 'mattsevents' ),
		);

		register_taxonomy(
			'event', 'activity', array(
				'hierarchical'          => false,
				'labels'                => $labels,
				'show_ui'               => true,
				'show_admin_column'     => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var'             => true,
				// 'rewrite'               => array( 'slug' => 'program/event' ),
			)
		);
	},
	0
);

/**
 * Display custom fields when creating new events.
 */
add_action(
	'event_add_form_fields',
	function ( $term ) {
		?>
		<div class="form-field">
			<label for="taxImage"><?php _e( 'Image', 'mattsevents' ); ?></label>
	
			<input type="text" name="taxImage" id="taxImage" value="">
		</div>
		<?php
	}, 10, 2
);

/**
 * Display custom fields when editing events.
 */
add_action(
	'event_edit_form_fields', function ( $term ) {
		$term_image = get_term_meta( $term->term_id, 'image', true );
		?>
		<tr class="form-field">
			<th><label for="taxImage"><?php _e( 'Image', 'mattsevents' ); ?></label></th>
			<td>	 
				<input type="text" name="taxImage" id="taxImage" value="<?php echo esc_attr( $term_image ) ? esc_attr( $term_image ) : ''; ?>">
			</td>
		</tr>
		<?php
	}, 10
);

/**
 * Save custom fields when editing and saving events.
 */
function event_save_image( $term_id ) {
	if ( isset( $_POST['taxImage'] ) ) {
		$term_image = $_POST['taxImage'];
		if ( $term_image ) {
			 update_term_meta( $term_id, 'image', $term_image );
		}
	}
}
add_action( 'edited_event', 'event_save_image' );
add_action( 'create_event', 'event_save_image' );
