<?php
/**
 * Plugin Name: LISA - Live Indexing & Search for Algolia
 * Description: A minimal yet powerful plugin to manage Algolia indexes from within WordPress.
 * Author: Tom de Visser
 * Version: 0.1.0
 * Theme Domain: lisa
 */

defined( constant_name: 'ABSPATH' ) or die;

define( 'LISA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'LISA_PLUGIN_URI', plugin_dir_path( __FILE__ ) );

require_once LISA_PLUGIN_URI . 'includes/assets.php';
require_once LISA_PLUGIN_URI . 'includes/utilities.php';
require_once LISA_PLUGIN_URI . 'includes/settings.php';
require_once LISA_PLUGIN_URI . 'includes/admin-menu.php';
require_once LISA_PLUGIN_URI . 'includes/ajax-handlers.php';
require_once LISA_PLUGIN_URI . 'includes/dashboard.php';
