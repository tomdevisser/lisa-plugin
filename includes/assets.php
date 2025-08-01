<?php

function lisa_enqueue_admin_scripts() {
	wp_enqueue_style(
		handle: 'lisa-admin-styles',
		src: LISA_PLUGIN_URL . '/assets/css/admin.css',
		deps: array(),
		ver: '0.1.0',
	);

	wp_enqueue_script(
		handle: 'lisa-admin-scripts',
		src: LISA_PLUGIN_URL . '/assets/js/admin.min.js',
		deps: array(),
		ver: '0.1.0',
	);

	wp_localize_script(
		handle: 'lisa-admin-scripts',
		object_name: 'lisa',
		l10n: array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'lisa_fetch_algolia_indices_nonce' => wp_create_nonce( 'lisa_fetch_algolia_indices' ),
			'lisa_fetch_algolia_index_settings_nonce' => wp_create_nonce( 'lisa_fetch_algolia_index_settings' ),
			'fetch_indices_button_label' => __( 'Fetch Indices', 'lisa' ),
			'fetch_index_settings_button_label' => __( 'Fetch Index Settings', 'lisa' ),
			'fetch_status_label' => __( 'Fetching...', 'lisa' ),
			'fetch_error_label' => __( 'Something went wrong while fetching your indices.', 'lisa' ),
		)
	);
}

add_action( hook_name: 'admin_enqueue_scripts', callback: 'lisa_enqueue_admin_scripts' );
