<?php

function lisa_fetch_algolia_indices_ajax() {
	check_ajax_referer( action: 'lisa_fetch_algolia_indices', query_arg: 'nonce' );

	$algolia_credentials = get_option( option: 'lisa_algolia_credentials' );
	$application_id = $algolia_credentials['lisa_algolia_credentials_application_id'] ?? '';
	$search_api_key = $algolia_credentials['lisa_algolia_credentials_search_api_key'] ?? '';

	if ( empty( $application_id ) || empty( $search_api_key ) ) {
		wp_send_json_error( value: 'The Algolia Indices widget requires an Application ID and a Search API Key.', status_code: 400 );
		return;
	}

	$response = wp_remote_get(
		url: "https://$application_id.algolia.net/1/indexes",
		args: array(
			'headers' => array(
				'x-algolia-application-id' => $application_id,
				'x-algolia-api-Key' => $search_api_key,
				'accept' => 'application/json',
			)
		)
	);

	if ( is_wp_error( $response ) ) {
		wp_send_json_error( value: 'Request failed.', status_code: 500 );
		return;
	}

	$data = json_decode( wp_remote_retrieve_body( response: $response ), associative: true );
	$indices = $data['items'] ?? array();

	update_option(
		option: 'lisa_algolia_indices',
		value: $indices
	);

	wp_send_json_success( value: $indices, status_code: 200 );
	return;
}

add_action( hook_name: 'wp_ajax_lisa_fetch_algolia_indices', callback: 'lisa_fetch_algolia_indices_ajax' );

function lisa_fetch_algolia_index_settings_ajax() {
	check_ajax_referer( action: 'lisa_fetch_algolia_index_settings', query_arg: 'nonce' );

	$index_name = $_POST['index_name'] ?? '';

	if ( empty( $index_name ) ) {
		wp_send_json_error( value: 'No index name was specified.', status_code: 400 );
		return;
	}

	$algolia_credentials = get_option( option: 'lisa_algolia_credentials' );
	$application_id = $algolia_credentials['lisa_algolia_credentials_application_id'] ?? '';
	$search_api_key = $algolia_credentials['lisa_algolia_credentials_search_api_key'] ?? '';

	if ( empty( $application_id ) || empty( $search_api_key ) ) {
		wp_send_json_error( value: 'The Algolia Indices widget requires an Application ID and a Search API Key.', status_code: 400 );
		return;
	}

	$response = wp_remote_get(
		url: "https://$application_id.algolia.net/1/indexes/$index_name/settings",
		args: array(
			'headers' => array(
				'x-algolia-application-id' => $application_id,
				'x-algolia-api-Key' => $search_api_key,
				'accept' => 'application/json',
			)
		)
	);

	if ( is_wp_error( $response ) ) {
		wp_send_json_error( value: 'Request failed.', status_code: 500 );
		return;
	}

	$data = json_decode( wp_remote_retrieve_body( response: $response ), associative: true );

	wp_send_json_success( value: $data, status_code: 200 );
	return;
}

add_action( hook_name: 'wp_ajax_lisa_fetch_algolia_index_settings', callback: 'lisa_fetch_algolia_index_settings_ajax' );
