<?php
/**
 * Plugin Name: Team Manager
 * Plugin URI: https://github.com/ukrahost/team-member-wp-plugin
 * Description: Manage team members with custom post type, taxonomy, meta fields, shortcode and REST API endpoint.
 * Version: 1.0.3
 * Author: Eliezer Sánchez
 * Author URI: https://www.linkedin.com/in/ukrahost/
 * Text Domain: team-manager
 * Domain Path: /languages
 * Requires PHP: 7.4
 * Requires at least: 5.8
 * Tested up to: 6.7
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */


if (!defined('ABSPATH')) {
    exit;
}

define('TEAM_MANAGER_VERSION', '1.0.0');
define('TEAM_MANAGER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TEAM_MANAGER_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once TEAM_MANAGER_PLUGIN_DIR . 'includes/class-team-manager-plugin.php';

function team_manager_bootstrap() {
    \TeamManager\Plugin::instance();
}

add_action('plugins_loaded', 'team_manager_bootstrap');