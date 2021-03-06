<?php
// exit if uninstall is not called
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

$keep = get_option( 'vscf-setting' );
if ( $keep != 'yes' ) {
	// delete options
	delete_option( 'widget_vscf-widget');
	delete_option( 'vscf-setting' );
	delete_option( 'vscf-setting-2' );
	delete_option( 'vscf-setting-3' );
	delete_option( 'vscf-setting-4' );
	delete_option( 'vscf-setting-5' );
	delete_option( 'vscf-setting-6' );
	delete_option( 'vscf-setting-7' );
	delete_option( 'vscf-setting-8' );
	delete_option( 'vscf-setting-9' );
	delete_option( 'vscf-setting-10' );
	delete_option( 'vscf-setting-11' );
	delete_option( 'vscf-setting-12' );
	delete_option( 'vscf-setting-13' );
	delete_option( 'vscf-setting-14' );
	delete_option( 'vscf-setting-15' );
	delete_option( 'vscf-setting-16' );
	delete_option( 'vscf-setting-17' );
	delete_option( 'vscf-setting-18' );
	delete_option( 'vscf-setting-19' );
	delete_option( 'vscf-setting-20' );
	delete_option( 'vscf-setting-21' );
	delete_option( 'vscf-setting-22' );
	delete_option( 'vscf-setting-23' );

	// delete site options in multisite
	delete_site_option( 'widget_vscf-widget' );
	delete_site_option( 'vscf-setting' );
	delete_site_option( 'vscf-setting-2' );
	delete_site_option( 'vscf-setting-3' );
	delete_site_option( 'vscf-setting-4' );
	delete_site_option( 'vscf-setting-5' );
	delete_site_option( 'vscf-setting-6' );
	delete_site_option( 'vscf-setting-7' );
	delete_site_option( 'vscf-setting-8' );
	delete_site_option( 'vscf-setting-9' );
	delete_site_option( 'vscf-setting-10' );
	delete_site_option( 'vscf-setting-11' );
	delete_site_option( 'vscf-setting-12' );
	delete_site_option( 'vscf-setting-13' );
	delete_site_option( 'vscf-setting-14' );
	delete_site_option( 'vscf-setting-15' );
	delete_site_option( 'vscf-setting-16' );
	delete_site_option( 'vscf-setting-17' );
	delete_site_option( 'vscf-setting-18' );
	delete_site_option( 'vscf-setting-19' );
	delete_site_option( 'vscf-setting-20' );
	delete_site_option( 'vscf-setting-21' );
	delete_site_option( 'vscf-setting-22' );
	delete_site_option( 'vscf-setting-23' );

	// set global
	global $wpdb;

	// delete submissions
	$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type = 'submission'" );
}
