<?php

function lisa_settings_init() {
	/**
	 * Creates an entry in the {$wpdb->prefix}_options table.
	 * @see https://developer.wordpress.org/reference/functions/register_setting/
	 */
	register_setting(
		option_group: 'lisa',
		option_name: 'lisa_algolia_credentials',
		args: array(
			'type' => 'array',
			'label' => __( 'Algolia Credentials', 'lisa' ),
			'description' => 'Algolia Credentials will contain various keys needed to connect with Algolia.',
			'sanitize_callback' => 'lisa_sanitize_algolia_credentials_cb'
		)
	);

	register_setting(
		option_group: 'lisa_indices',
		option_name: 'lisa_algolia_index_configs',
		args: array(
			'type' => 'array',
			'label' => __( 'Algolia Indices', 'lisa' ),
			'description' => 'Algolia Indices will contain the configuration for your indices.',
			'sanitize_callback' => 'lisa_sanitize_algolia_index_configs_cb',
		)
	);

	add_settings_section(
		id: 'lisa_section_algolia_credentials',
		title: __( 'Algolia Credentials', 'lisa' ),
		callback: 'lisa_section_credentials_cb',
		page: 'lisa',
		args: array(
			'before_section' => '',
			'after_section' => '',
			'section_class' => '',
		),
	);

	add_settings_section(
		id: 'lisa_section_algolia_pagination',
		title: __( 'Pagination', 'lisa' ),
		callback: 'lisa_section_pagination_cb',
		page: 'lisa_index_settings',
		args: array(
			'before_section' => '',
			'after_section' => '',
			'section_class' => '',
		),
	);

	add_settings_field(
		id: 'lisa_algolia_pagination_hits_per_page',
		title: __( 'Hits Per Page', 'lisa' ),
		callback: 'lisa_field_hits_per_page_cb',
		page: 'lisa_index_settings',
		section: 'lisa_section_algolia_pagination',
		args: array(
			'label_for' => 'lisa_algolia_pagination_hits_per_page',
			'class'     => '',
			'description' => __( 'Number of hits per page.', 'lisa' ),
		),
	);

	add_settings_field(
		id: 'lisa_algolia_pagination_pagination_limited_to',
		title: __( 'Pagination Limited To', 'lisa' ),
		callback: 'lisa_field_pagination_limited_to_cb',
		page: 'lisa_index_settings',
		section: 'lisa_section_algolia_pagination',
		args: array(
			'label_for' => 'lisa_algolia_pagination_pagination_limited_to',
			'class'     => '',
			'description' => __( 'Set the maximum number of hits accessible via pagination.', 'lisa' ),
		),
	);

	add_settings_field(
		id: 'lisa_algolia_credentials_application_id',
		title: __( 'Application ID', 'lisa' ),
		callback: 'lisa_field_application_id_cb',
		page: 'lisa',
		section: 'lisa_section_algolia_credentials',
		args: array(
			'label_for' => 'lisa_algolia_credentials_application_id',
			'class'     => '',
			'description' => __( "This is your unique application identifier. It's used to identify your application when using Algolia's API.", 'lisa' ),
		),
	);

	add_settings_field(
		id: 'lisa_algolia_credentials_search_api_key',
		title: __( 'Search API Key', 'lisa' ),
		callback: 'lisa_field_search_api_key_cb',
		page: 'lisa',
		section: 'lisa_section_algolia_credentials',
		args: array(
			'label_for' => 'lisa_algolia_credentials_search_api_key',
			'class'     => '',
			'description' => __( "This is the public API key which can be safely used in your frontend code.This key is usable for search queries and it's also able to list the indices you've got access to.", 'lisa' ),
		),
	);

	add_settings_field(
		id: 'lisa_algolia_credentials_write_api_key',
		title: __( 'Write API Key', 'lisa' ),
		callback: 'lisa_field_write_api_key_cb',
		page: 'lisa',
		section: 'lisa_section_algolia_credentials',
		args: array(
			'label_for' => 'lisa_algolia_credentials_write_api_key',
			'class'     => '',
			'description' => __( 'This is a private API key. Please keep it secret and use it ONLY from your backend: this key is used to create, update and DELETE your indices. You CANNOT use this key to manage your API keys.', 'lisa' ),
		),
	);

	add_settings_field(
		id: 'lisa_algolia_credentials_admin_api_key',
		title: __( 'Admin API Key', 'lisa' ),
		callback: 'lisa_field_admin_api_key_cb',
		page: 'lisa',
		section: 'lisa_section_algolia_credentials',
		args: array(
			'label_for' => 'lisa_algolia_credentials_admin_api_key',
			'class'     => '',
			'description' => __( 'This is the ADMIN API key. Please keep it secret and use it ONLY from your backend: this key is used to create, update and DELETE your indices. You can also use it to manage your API keys.', 'lisa' ),
		),
	);

	add_settings_field(
		id: 'lisa_algolia_credentials_usage_api_key',
		title: __( 'Usage API Key', 'lisa' ),
		callback: 'lisa_field_usage_api_key_cb',
		page: 'lisa',
		section: 'lisa_section_algolia_credentials',
		args: array(
			'label_for' => 'lisa_algolia_credentials_usage_api_key',
			'class'     => '',
			'description' => __( 'This key is used to access the Usage API and Logs endpoint.', 'lisa' ),
		),
	);

	add_settings_field(
		id: 'lisa_algolia_credentials_monitoring_api_key',
		title: __( 'Monitoring API Key', 'lisa' ),
		callback: 'lisa_field_monitoring_api_key_cb',
		page: 'lisa',
		section: 'lisa_section_algolia_credentials',
		args: array(
			'label_for' => 'lisa_algolia_credentials_monitoring_api_key',
			'class'     => '',
			'description' => __( 'This key is used to access the Monitoring API.', 'lisa' ),
		),
	);
}

function lisa_sanitize_algolia_index_configs_cb( array $input ): array {
	if ( ! is_array( $input ) ) {
		return array();
	}

	$existing_configs = get_option( 'lisa_algolia_index_configs', array() );
	$index_name = $_POST['index_name'] ?? '';

	if ( empty( $index_name ) ) {
		add_settings_error(
			setting: 'lisa_messages',
			code: 'missing_index_name',
			message: __( 'No index name to save to.', 'lisa' ),
			type: 'error'
		);

		return $existing_configs;
	}

	if ( $index_name && isset( $input[ $index_name ] ) ) {
		$sanitized = array();

		if ( ! empty( $input[ $index_name ]['lisa_algolia_pagination_hits_per_page'] ) ) {
			$sanitized['lisa_algolia_pagination_hits_per_page'] = trim( $input[ $index_name ]['lisa_algolia_pagination_hits_per_page'] );

			if (
				! preg_match( '/^\d+$/', $sanitized['lisa_algolia_pagination_hits_per_page'] ) ||
				(int) $sanitized['lisa_algolia_pagination_hits_per_page'] < 1 ||
				(int) $sanitized['lisa_algolia_pagination_hits_per_page'] > 1000
			) {
				add_settings_error(
					'lisa_messages',
					'invalid_hits_per_page',
					__( 'Hits Per Page must be a number between 1 and 1000.', 'lisa' ),
					'error'
				);
			}
		}

		if ( ! empty( $input[ $index_name ]['lisa_algolia_pagination_pagination_limited_to'] ) ) {
			$sanitized['lisa_algolia_pagination_pagination_limited_to'] = trim( $input[ $index_name ]['lisa_algolia_pagination_pagination_limited_to'] );

			if (
				! preg_match( '/^\d+$/', $sanitized['lisa_algolia_pagination_pagination_limited_to'] ) ||
				(int) $sanitized['lisa_algolia_pagination_pagination_limited_to'] < 100 ||
				(int) $sanitized['lisa_algolia_pagination_pagination_limited_to'] > 100000
			) {
				add_settings_error(
					'lisa_messages',
					'invalid_pagination_limited_to',
					__( 'Pagination Limited To must be a number between 100 and 100000.', 'lisa' ),
					'error'
				);
			}
		}

		$existing_configs[ $index_name ] = array_merge(
			$existing_configs[ $index_name ] ?? array(),
			$sanitized
		);
	}

	return $existing_configs;
}

function lisa_sanitize_algolia_credentials_cb( array $input ): array {
	$sanitized = array();

	if ( ! empty( $input['lisa_algolia_credentials_application_id'] ) ) {
		$sanitized['lisa_algolia_credentials_application_id'] = trim( $input['lisa_algolia_credentials_application_id'] );

		if ( ! preg_match( pattern: '/^[A-Z0-9]{10}$/', subject: $sanitized['lisa_algolia_credentials_application_id'] ) ) {
			add_settings_error(
				setting: 'lisa_messages',
				code: 'invalid_application_id',
				message: __( 'Invalid Algolia Application ID. It must be 10 uppercase alphanumeric characters.', 'lisa' ),
				type: 'warning'
			);
		}
	}

	if ( ! empty( $input['lisa_algolia_credentials_search_api_key'] ) ) {
		$sanitized['lisa_algolia_credentials_search_api_key'] = trim( $input['lisa_algolia_credentials_search_api_key'] );

		if ( ! preg_match( pattern: '/^[a-f0-9]{32}$/i', subject: $sanitized['lisa_algolia_credentials_search_api_key'] ) ) {
			add_settings_error(
				setting: 'lisa_messages',
				code: 'invalid_search_api_key',
				message: __( 'Invalid Algolia Search API Key. It must be 32 hexadecimal characters.', 'lisa' ),
				type: 'warning'
			);
		}
	}

	if ( ! empty( $input['lisa_algolia_credentials_write_api_key'] ) ) {
		$sanitized['lisa_algolia_credentials_write_api_key'] = trim( $input['lisa_algolia_credentials_write_api_key'] );

		if ( ! preg_match( pattern: '/^[a-f0-9]{32}$/i', subject: $sanitized['lisa_algolia_credentials_write_api_key'] ) ) {
			add_settings_error(
				setting: 'lisa_messages',
				code: 'invalid_write_api_key',
				message: __( 'Invalid Algolia Write API Key. It must be 32 hexadecimal characters.', 'lisa' ),
				type: 'warning'
			);
		}
	}

	if ( ! empty( $input['lisa_algolia_credentials_admin_api_key'] ) ) {
		$sanitized['lisa_algolia_credentials_admin_api_key'] = trim( $input['lisa_algolia_credentials_admin_api_key'] );

		if ( ! preg_match( pattern: '/^[a-f0-9]{32}$/i', subject: $sanitized['lisa_algolia_credentials_admin_api_key'] ) ) {
			add_settings_error(
				setting: 'lisa_messages',
				code: 'invalid_admin_api_key',
				message: __( 'Invalid Algolia Admin API Key. It must be 32 hexadecimal characters.', 'lisa' ),
				type: 'warning'
			);
		}
	}

	if ( ! empty( $input['lisa_algolia_credentials_usage_api_key'] ) ) {
		$sanitized['lisa_algolia_credentials_usage_api_key'] = trim( $input['lisa_algolia_credentials_usage_api_key'] );

		if ( ! preg_match( pattern: '/^[a-f0-9]{32}$/i', subject: $sanitized['lisa_algolia_credentials_usage_api_key'] ) ) {
			add_settings_error(
				setting: 'lisa_messages',
				code: 'invalid_usage_api_key',
				message: __( 'Invalid Algolia Usage API Key. It must be 32 hexadecimal characters.', 'lisa' ),
				type: 'warning'
			);
		}
	}

	if ( ! empty( $input['lisa_algolia_credentials_monitoring_api_key'] ) ) {
		$sanitized['lisa_algolia_credentials_monitoring_api_key'] = trim( $input['lisa_algolia_credentials_monitoring_api_key'] );

		if ( ! preg_match( pattern: '/^[a-f0-9]{32}$/i', subject: $sanitized['lisa_algolia_credentials_monitoring_api_key'] ) ) {
			add_settings_error(
				setting: 'lisa_messages',
				code: 'invalid_monitoring_api_key',
				message: __( 'Invalid Algolia Monitoring API Key. It must be 32 hexadecimal characters.', 'lisa' ),
				type: 'warning'
			);
		}
	}

	return $sanitized;
}

function lisa_section_credentials_cb( array $args ) {
	?>
	<p id="<?php echo esc_attr( $args['id'] ); ?>">
		<?php esc_html_e( 'These credentials are used to connect with Algolia. You can find them in your Algolia Dashboard.' ); ?>
	</p>
	<?php
}

function lisa_section_pagination_cb( array $args ) {
	global $lisa_current_index;
	$lisa_current_index = $_GET['index_name'] ?? '';
	?>
	<p id="<?php echo esc_attr( $args['id'] ); ?>">
		<?php esc_html_e( 'The pagination settings for Algolia.', 'lisa' ); ?>
	</p>
	<?php
}

function lisa_field_hits_per_page_cb( array $args ) {
	global $lisa_current_index;
	$algolia_indices = get_option( option: 'lisa_algolia_index_configs' );
	$hits_per_page = $algolia_indices[ $lisa_current_index ][ $args['label_for'] ] ?? '';
	?>
	<input
		type="text"
		id="<?php echo esc_attr( $args['label_for'] ); ?>"
		name="lisa_algolia_index_configs[<?php echo esc_attr( $lisa_current_index ); ?>][<?php echo esc_attr( $args['label_for'] ); ?>]"
		value="<?php echo $hits_per_page; ?>"
	/>
	<?php
	if ( ! empty( $args['description'] ) ) {
		?>
		<p class="description">
			<?php echo esc_html( $args['description'] ); ?>
		</p>
		<?php
	}
}

function lisa_field_pagination_limited_to_cb( array $args ) {
	global $lisa_current_index;
	$algolia_indices = get_option( option: 'lisa_algolia_index_configs' );
	$pagination_limited_to = $algolia_indices[ $lisa_current_index ][ $args['label_for'] ] ?? '';
	?>
	<input
		type="text"
		id="<?php echo esc_attr( $args['label_for'] ); ?>"
		name="lisa_algolia_index_configs[<?php echo esc_attr( $lisa_current_index ); ?>][<?php echo esc_attr( $args['label_for'] ); ?>]"
		value="<?php echo $pagination_limited_to; ?>"
	/>
	<?php
	if ( ! empty( $args['description'] ) ) {
		?>
		<p class="description">
			<?php echo esc_html( $args['description'] ); ?>
		</p>
		<?php
	}
}

function lisa_field_application_id_cb( array $args ) {
	$algolia_credentials = get_option( option: 'lisa_algolia_credentials' );
	$application_id = $algolia_credentials[ $args['label_for'] ] ?? '';
	?>
	<input
		type="text"
		id="<?php echo esc_attr( $args['label_for'] ); ?>"
		name="lisa_algolia_credentials[<?php echo esc_attr( $args['label_for'] ); ?>]"
		value="<?php echo $application_id; ?>"
	/>
	<?php
	if ( ! empty( $args['description'] ) ) {
		?>
		<p class="description">
			<?php echo esc_html( $args['description'] ); ?>
		</p>
		<?php
	}
}

function lisa_field_search_api_key_cb( array $args ) {
	$algolia_credentials = get_option( option: 'lisa_algolia_credentials' );
	$search_api_key = $algolia_credentials[ $args['label_for'] ] ?? '';
	?>
	<input
		type="text"
		id="<?php echo esc_attr( $args['label_for'] ); ?>"
		name="lisa_algolia_credentials[<?php echo esc_attr( $args['label_for'] ); ?>]"
		value="<?php echo $search_api_key; ?>"
	/>
	<?php
	if ( ! empty( $args['description'] ) ) {
		?>
		<p class="description">
			<?php echo esc_html( $args['description'] ); ?>
		</p>
		<?php
	}
}

function lisa_field_write_api_key_cb( array $args ) {
	$algolia_credentials = get_option( option: 'lisa_algolia_credentials' );
	$write_api_key = $algolia_credentials[ $args['label_for'] ] ?? '';
	?>
	<input
		type="password"
		id="<?php echo esc_attr( $args['label_for'] ); ?>"
		name="lisa_algolia_credentials[<?php echo esc_attr( $args['label_for'] ); ?>]"
		value="<?php echo $write_api_key; ?>"
	/>
	<?php
	if ( ! empty( $args['description'] ) ) {
		?>
		<p class="description">
			<?php echo esc_html( $args['description'] ); ?>
		</p>
		<?php
	}
}

function lisa_field_admin_api_key_cb( array $args ) {
	$algolia_credentials = get_option( option: 'lisa_algolia_credentials' );
	$admin_api_key = $algolia_credentials[ $args['label_for'] ] ?? '';
	?>
	<input
		type="password"
		id="<?php echo esc_attr( $args['label_for'] ); ?>"
		name="lisa_algolia_credentials[<?php echo esc_attr( $args['label_for'] ); ?>]"
		value="<?php echo $admin_api_key; ?>"
	/>
	<?php
	if ( ! empty( $args['description'] ) ) {
		?>
		<p class="description">
			<?php echo esc_html( $args['description'] ); ?>
		</p>
		<?php
	}
}

function lisa_field_usage_api_key_cb( array $args ) {
	$algolia_credentials = get_option( option: 'lisa_algolia_credentials' );
	$usage_api_key = $algolia_credentials[ $args['label_for'] ] ?? '';
	?>
	<input
		type="text"
		id="<?php echo esc_attr( $args['label_for'] ); ?>"
		name="lisa_algolia_credentials[<?php echo esc_attr( $args['label_for'] ); ?>]"
		value="<?php echo $usage_api_key; ?>"
	/>
	<?php
	if ( ! empty( $args['description'] ) ) {
		?>
		<p class="description">
			<?php echo esc_html( $args['description'] ); ?>
		</p>
		<?php
	}
}

function lisa_field_monitoring_api_key_cb( array $args ) {
	$algolia_credentials = get_option( option: 'lisa_algolia_credentials' );
	$monitoring_api_key = $algolia_credentials[ $args['label_for'] ] ?? '';
	?>
	<input
		type="text"
		id="<?php echo esc_attr( $args['label_for'] ); ?>"
		name="lisa_algolia_credentials[<?php echo esc_attr( $args['label_for'] ); ?>]"
		value="<?php echo $monitoring_api_key; ?>"
	/>
	<?php
	if ( ! empty( $args['description'] ) ) {
		?>
		<p class="description">
			<?php echo esc_html( $args['description'] ); ?>
		</p>
		<?php
	}
}

/**
 * register_setting() as well as the mentioned add_settings_*() functions should all be added to the admin_init action hook.
 * @see https://developer.wordpress.org/plugins/settings/using-settings-api/
 */
add_action( hook_name: 'admin_init', callback: 'lisa_settings_init' );

function lisa_sync_algolia_index_settings( $old_value, $new_value ) {
	$index_name = $_POST['index_name'] ?? '';

	if ( empty( $index_name ) || empty( $new_value[ $index_name ] ) ) {
		return;
	}

	$credentials = get_option( 'lisa_algolia_credentials' );
	$application_id = $credentials['lisa_algolia_credentials_application_id'] ?? '';
	$write_api_key = $credentials['lisa_algolia_credentials_write_api_key'] ?? '';

	if ( empty( $application_id ) || empty( $write_api_key ) ) {
		return;
	}

	$settings = $new_value[ $index_name ];

	$response = wp_remote_request(
		"https://$application_id.algolia.net/1/indexes/$index_name/settings",
		array(
			'method'  => 'PUT',
			'headers' => array(
				'X-Algolia-API-Key'        => $write_api_key,
				'X-Algolia-Application-Id' => $application_id,
				'Content-Type'             => 'application/json',
			),
			'body'    => wp_json_encode(
				array(
					'hitsPerPage'         => (int) ( $settings['lisa_algolia_pagination_hits_per_page'] ?? 20 ),
					'paginationLimitedTo' => (int) ( $settings['lisa_algolia_pagination_pagination_limited_to'] ?? 1000 ),
				)
			),
		)
	);

	// Handle response and show admin notices.
	if ( is_wp_error( $response ) ) {
		add_settings_error(
			'lisa_messages',
			'algolia_sync_failed',
			__( 'Failed to sync settings to Algolia: network error.', 'lisa' ),
			'error'
		);
		return;
	}

	$response_code = wp_remote_retrieve_response_code( $response );
	if ( $response_code !== 200 ) {
		add_settings_error(
			'lisa_messages',
			'algolia_sync_failed',
			sprintf(
				__( 'Failed to sync settings to Algolia. Response code: %d.', 'lisa' ),
				$response_code
			),
			'error'
		);
		return;
	}

	add_settings_error(
		'lisa_messages',
		'algolia_sync_success',
		__( 'Settings successfully synced to Algolia.', 'lisa' ),
		'updated'
	);
}

add_action( hook_name: 'update_option_lisa_algolia_index_configs', callback: 'lisa_sync_algolia_index_settings', priority: 10, accepted_args: 2 );
