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
		)
	);
}

add_action( hook_name: 'admin_enqueue_scripts', callback: 'lisa_enqueue_admin_scripts' );
