<?php


function lisa_add_algolia_dashboard_widgets() {
	wp_add_dashboard_widget(
		widget_id: 'lisa_list_algolia_indices_widget',
		widget_name: __( 'Algolia Indices', 'lisa' ),
		callback: 'lisa_list_algolia_indices_widget_cb'
	);
}

add_action( hook_name: 'wp_dashboard_setup', callback: 'lisa_add_algolia_dashboard_widgets' );

function lisa_list_algolia_indices_widget_cb() {
	$algolia_credentials = get_option( option: 'lisa_algolia_credentials' );
	$application_id = $algolia_credentials['lisa_algolia_credentials_application_id'] ?? '';
	$search_api_key = $algolia_credentials['lisa_algolia_credentials_search_api_key'] ?? '';
	$indices = get_option( option: 'lisa_algolia_index_metadata', default_value: false );
	?>
	<div class="lisa-algolia-indices-widget">
		<div id="lisa-indices-list">
			<?php
			if ( empty( $application_id ) || empty( $search_api_key ) ) {
				?>
				<p>
					<?php
					printf(
						esc_html__( 'The Algolia Indices widget requires an Application ID and a Search API Key. You can configure these in the %1$s.', 'lisa' ),
						sprintf(
							'<a href="%s">%s</a>',
							esc_url( admin_url( 'admin.php?page=lisa' ) ),
							esc_html__( 'LISA settings page', 'lisa' )
						)
					);
					return;
					?>
				</p>
				<?php
			}

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
		</div>

		<button id="lisa-fetch-indices" class="button">
			<?php esc_html_e( 'Fetch Indices', 'lisa' ); ?>
		</button>
	</div>
	<?php
}
