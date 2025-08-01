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
			'fetch_button_label' => __( 'Fetch Indices', 'lisa' ),
			'fetch_status_label' => __( 'Fetching indices...', 'lisa' ),
			'fetch_error_label' => __( 'Something went wrong while fetching your indices.', 'lisa' ),
			'no_indices_label' => __( 'No indices found in your Algolia account.', 'lisa' ),
			'found_one_index'   => __( 'Found 1 index in your Algolia account.', 'lisa' ),
			'found_many_indices' => __( 'Found %d indices in your Algolia account.', 'lisa' ),
		)
	);
}

add_action( hook_name: 'admin_enqueue_scripts', callback: 'lisa_enqueue_admin_scripts' );
