<?php

function lisa_options_pages() {
	add_menu_page(
		page_title: __( 'Lisa Options', 'lisa' ),
		menu_title: __( 'Lisa', 'lisa' ),
		capability: 'manage_options',
		menu_slug: 'lisa',
		callback: 'lisa_options_page_html_cb',
		icon_url: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMjggMTI4IiB3aWR0aD0iMjAiIGhlaWdodD0iMjAiPgogIDxnIHRyYW5zZm9ybT0ic2NhbGUoMC44NSkgdHJhbnNsYXRlKDEwLDEwKSI+CjxwYXRoIGZpbGw9IiMwMDNkZmYiIGQ9Ik02My45OTgtLjA0MkMyOS4wMjQtLjA0Mi41MTEgMjguMTYuMDA2IDYzLjAxNWMtLjUxMiAzNS40MDIgMjguMjA4IDY0LjczNCA2My42MTMgNjQuOTQgMTAuOTM0LjA2MyAyMS40NjUtMi42MTIgMzAuODE3LTcuNjkzYTEuNSAxLjUgMCAwIDAgLjI3Ni0yLjQzOGwtNS45ODctNS4zMDljLTEuMjE2LTEuMDgtMi45NS0xLjM4NS00LjQ0Ny0uNzQ3LTYuNTI4IDIuNzc3LTEzLjYyMiA0LjE5NS0yMC45MyA0LjEwNi0yOC42MDgtLjM1MS01MS43MjItMjQuMTUzLTUxLjI2Ni01Mi43NjEuNDUtMjguMjQ0IDIzLjU2Ny01MS4wODQgNTEuOTE2LTUxLjA4NGg1MS45MjR2OTIuMjk1bC0yOS40Ni0yNi4xNzZjLS45NTItLjg0OC0yLjQxNC0uNjgxLTMuMTgyLjMzNS00LjcyOCA2LjI2Mi0xMi40MzEgMTAuMTU1LTIwLjk4NyA5LjU2NC0xMS44NjgtLjgyLTIxLjQ4My0xMC4zNzMtMjIuMzc0LTIyLjIzNi0xLjA2Mi0xNC4xNTIgMTAuMTUtMjYuMDA0IDI0LjA4Mi0yNi4wMDQgMTIuNTk4IDAgMjIuOTczIDkuNjk3IDI0LjA1NiAyMi4wMThhNC4yOTcgNC4yOTcgMCAwIDAgMS40MTYgMi44NWw3LjY3MiA2LjgwMWMuODcuNzcgMi4yNTMuMyAyLjQ2NS0uODQ1LjU1My0yLjk1Ny43NDgtNi4wNDEuNTMtOS4yMDMtMS4yMzctMTguMDItMTUuODMxLTMyLjUxNC0zMy44NTgtMzMuNjI1LTIwLjY2Ny0xLjI3NS0zNy45NDYgMTQuODk0LTM4LjQ5NCAzNS4xNjEtLjUzNSAxOS43NSAxNS42NDcgMzYuNzc2IDM1LjM5OSAzNy4yMTJhMzYuMDI4IDM2LjAyOCAwIDAgMCAyMi4wNjctNi45MDRsMzguNDkyIDM0LjEyMmMxLjY1MSAxLjQ2MiA0LjI1NS4yOTIgNC4yNTUtMS45MTVWMi4zOWEyLjQzNCAyLjQzNCAwIDAgMC0yLjQzMi0yLjQzeiIvPiAgPC9nPgo8L3N2Zz4=',
	);

	add_submenu_page(
		parent_slug: 'lisa',
		page_title: __( 'Algolia Indices', 'lisa' ),
		menu_title: __( 'Indices', 'lisa' ),
		capability: 'manage_options',
		menu_slug: 'lisa_indices',
		callback: 'lisa_indices_page_html_cb',
	);
}

function lisa_indices_page_html_cb() {
	$indices = get_option( option: 'lisa_algolia_indices', default_value: false );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<?php
		if ( false === $indices ) {
			?>
			<p><?php esc_html_e( 'No indices fetched yet.', 'lisa' ); ?></p>
			<?php
		} else {
			$count = count( $indices );
			if ( 0 === $count ) {
				?>
				<p>
					<?php _e( 'No indices found in your Algolia account.', 'lisa' ) ?>
				</p>
				<?php
			} else {
				?>
				<p>
					<?php
					printf(
						esc_html(
							_n(
								'Found %d index in your Algolia account.',
								'Found %d indices in your Algolia account.',
								$count,
								'lisa'
							)
						),
						$count
					);
					?>
				</p>
				<?php
			}
		}
		?>
		<table class="widefat striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Name', 'lisa' ); ?></th>
					<th><?php esc_html_e( 'Created At', 'lisa' ); ?></th>
					<th><?php esc_html_e( 'Updated At', 'lisa' ); ?></th>
					<th><?php esc_html_e( 'Entries', 'lisa' ); ?></th>
					<th><?php esc_html_e( 'Data Size', 'lisa' ); ?></th>
					<th><?php esc_html_e( 'File Size', 'lisa' ); ?></th>
					<th><?php esc_html_e( 'Last Build Time', 'lisa' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ( $indices as $index ) {
					?>
					<tr>
						<td><?php echo esc_html( $index['name'] ); ?></td>
						<td><?php echo esc_html( lisa_time_ago( $index['createdAt'] ) ); ?></td>
						<td><?php echo esc_html( lisa_time_ago( $index['updatedAt'] ) ); ?></td>
						<td><?php echo esc_html( $index['entries'] ); ?></td>
						<td><?php echo esc_html( $index['dataSize'] ); ?></td>
						<td><?php echo esc_html( $index['fileSize'] ); ?></td>
						<td><?php echo esc_html( $index['lastBuildTimeS'] ); ?> <?php _e( 'seconds', 'lisa' ); ?></td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
	</div>
	<?php
}

function lisa_options_page_html_cb() {
	/** WordPress will add the 'settings-updated' GET parameter when a user submits settings. */
	if ( isset( $_GET['settings-updated'] ) ) {
		add_settings_error(
			setting: 'lisa_messages',
			code: 'lisa_message',
			message: __( 'Settings saved.', 'lisa' ),
			type: 'success'
		);
	}

	settings_errors( setting: 'lisa_messages' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			/**
			 * Outputs the nonce, action and option_page fields for security.
			 * @see https://developer.wordpress.org/reference/functions/settings_fields/
			 */
			settings_fields( option_group: 'lisa' );

			/**
			 * Prints out all the settings sections added to the specified page.
			 * @see https://developer.wordpress.org/reference/functions/do_settings_sections/
			 */
			do_settings_sections( page: 'lisa' );

			/**
			 * Adds a submit button with provided text and appropriate classes.
			 * @see https://developer.wordpress.org/reference/functions/submit_button/
			 */
			submit_button( text: 'Save Changes' );
			?>
		</form>
	</div>
	<?php
}

add_action( hook_name: 'admin_menu', callback: 'lisa_options_pages' );
