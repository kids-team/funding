<?php
/*
Plugin Name: Funding
Description: Receive donations via Mollie or offline Payment
Version: 3.0.0
Author: Wobbie.nl, Thomas Gollenia
Author URI: https://wobbie.nl
Text Domain: doneren-met-mollie
*/

if (!defined('ABSPATH')) {
    die('Please do not load this file directly!');
}

// Plugin Version
if (!defined('DMM_VERSION')) {
    define('DMM_VERSION', '3.0.0');
}

// Plugin Folder Path
if (!defined('DMM_PLUGIN_PATH')) {
    define('DMM_PLUGIN_PATH', plugin_dir_path(__FILE__));
}

define('DMM_PLUGIN_BASE', plugin_basename(__FILE__));

global $wpdb;

require_once( __DIR__ . '/vendor/autoload.php');

// Includes
require_once DMM_PLUGIN_PATH . 'includes/config.php';
require_once DMM_PLUGIN_PATH . 'includes/functions.php';
require_once DMM_PLUGIN_PATH . 'classes/MollieApi.php';
require_once DMM_PLUGIN_PATH . 'classes/Webhook.php';
require_once DMM_PLUGIN_PATH . 'classes/Start.php';
require_once DMM_PLUGIN_PATH . 'classes/Transfer.php';
require_once DMM_PLUGIN_PATH . 'classes/Blocks.php';
require_once DMM_PLUGIN_PATH . 'classes/BankingApi.php';
require_once DMM_PLUGIN_PATH . 'classes/Projects.php';
require_once DMM_PLUGIN_PATH . 'classes/Countries.php';

$dmm_webook = new \Contexis\Funding\Webhook();
$dmm = new \Contexis\Funding\Start();

// Admin includes and functions
if (is_admin()) {
    if(!class_exists('WP_List_Table')) require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
    require_once DMM_PLUGIN_PATH . 'classes/Donations_Table.php';
    require_once DMM_PLUGIN_PATH . 'classes/Donors_Table.php';
    require_once DMM_PLUGIN_PATH . 'classes/Subscriptions_Table.php';
    require_once DMM_PLUGIN_PATH . 'classes/Admin.php';

    $dmm_admin = new \Contexis\Funding\Admin();
}

// Register hook
register_activation_hook(__FILE__, array($dmm, 'dmm_install_database'));
register_uninstall_hook(__FILE__, 'dmm_uninstall_database');

function dmm_uninstall_database()
{
    delete_option('dmm_plugin_version');
}

// Update database when plugin is updated
if (get_option('dmm_version') != DMM_VERSION)
    $dmm->dmm_install_database();

load_plugin_textdomain('funding', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 

\Contexis\Funding\Blocks::init($dmm);