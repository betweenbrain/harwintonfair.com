<?php

/**
 * Plugin Name: Fair Program
 * Description: Enables a fair program custom post type.
 * Version: 0.0.1
 * Author: Matt Thomas
 * Text Domain: fairprogram
 */
defined( 'ABSPATH' ) or die();

/**
 * Register custom post type.
 */
add_action(
	'init', function () {
		$args = array(
			'has_archive'       => true,
			// https://developer.wordpress.org/reference/functions/get_post_type_labels/
			'labels'            => array(
				'add_new'      => __( 'Add Event', 'program', 'fairprogram' ),
				'add_new_item' => __( 'New Fair Event', 'program', 'fairprogram' ),
				'all_items'    => __( 'Fair Events', 'program', 'fairprogram' ),
				'edit_item'    => __( 'Edit Fair Event', 'program', 'fairprogram' ),
				'name'         => __( 'Fair Program', 'fairprogram' ),
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
				'locations',
			),
		);
		register_post_type( 'program', $args );
	}
);

add_action(
	'init',
	function () {
		$labels = array(
			'name'                       => __( 'Locations', 'taxonomy general name' ),
			'singular_name'              => __( 'Location', 'taxonomy singular name' ),
			'search_items'               => __( 'Search Locations' ),
			'popular_items'              => __( 'Popular Locations' ),
			'all_items'                  => __( 'All Locations' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Location' ),
			'update_item'                => __( 'Update Location' ),
			'add_new_item'               => __( 'Add New Location' ),
			'new_item_name'              => __( 'New Location Name' ),
			'separate_items_with_commas' => __( 'Separate locations with commas' ),
			'add_or_remove_items'        => __( 'Add or remove locations' ),
			'choose_from_most_used'      => __( 'Choose from the most used locations' ),
			'menu_name'                  => __( 'Locations' ),
		);

		register_taxonomy(
			'locations', 'events', array(
				'hierarchical'          => false,
				'labels'                => $labels,
				'show_ui'               => true,
				'show_admin_column'     => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var'             => true,
				'rewrite'               => array( 'slug' => 'program/location' ),
			)
		);
	},
	0
);

/**
 * Add custom meta box to events.
 */
add_action(
	'add_meta_boxes',
	function () {
		add_meta_box(
			'details', // $id
			'Event Dates', // $title
			'show_meta_boxes', // $callback
			'program', // $screen
			'normal', // $context
			'high' // $priority
		);
	}
);

/**
 * Add fields to custom meta box.
 */
function show_meta_boxes() {
	?>
	<input type="hidden" name="events_meta_box_nonce" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>">
	<p>
		<label for="begin">Begins
			<input type="datetime-local" name="begin" id="begin" class="regular-text" value="<?php echo get_value( 'begin' ); ?>" required>
		</label>
	</p>
	<p>
		<label for="end">Ends
			<input type="datetime-local" name="end" id="end" class="regular-text" value="<?php echo get_value( 'end' ); ?>">
		</label>
	</p>
	<?php
}

/**
 * Helper to properly return the meta value without throwing errors when not set.
 */
function get_value( $field ) {
	global $post;
	$meta = get_post_meta( $post->ID );
	return ( is_array( $meta ) && isset( $meta[ $field ] ) ) ? $meta[ $field ][0] : null;
}

/**
 * Enable saving of custom fields.
 */
add_action(
	'save_post', function ( $post_id ) {
		// verify nonce
		if ( ! wp_verify_nonce( $_POST['events_meta_box_nonce'], basename( __FILE__ ) ) ) {
			return $post_id;
		}
		// check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		// check permissions
		if ( 'page' === $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		$fields = array( 'begin', 'end' );

		foreach ( $fields as $field ) {
			$old = get_post_meta( $post_id, $field, true );
			$new = $_POST[ $field ];

			if ( $new && $new !== $old ) {
				update_post_meta( $post_id, $field, $new );
			} elseif ( '' === $new && $old ) {
				delete_post_meta( $post_id, $field, $old );
			}
		}
	}
);
