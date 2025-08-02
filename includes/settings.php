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
			'description' => __( "This is the public API key which can be safely used in your frontend code. This key is usable for search queries and it's also able to list the indices you've got access to.", 'lisa' ),
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
			'description' => __( 'This is the Admin API key. Please keep it secret and use it ONLY from your backend: this key is used to create, update and DELETE your indices. You can also use it to manage your API keys.', 'lisa' ),
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
