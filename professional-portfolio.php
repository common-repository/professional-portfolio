<?php
/*
Plugin Name: Professional Portfolio
Description: Display portfolio in your selected page
Version: 1.0.4
Author: Ittanta Technologies Pvt. Ltd.
Author URI: http://ittanta.com
License: GPLv2 or later
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}

global $pport_db_version;
$pport_db_version = '1.0.0';

define('PPORT_URL',plugin_dir_url(plugin_basename(__FILE__)));
define('PPORT_DIR', plugin_dir_path(__FILE__));
define('PPORT_CLASSES_DIR', plugin_dir_path(__FILE__).'/classes/');
define('PPORT_ASSETS_URL', plugin_dir_url(plugin_basename(__FILE__)).'assets/');
define('PPORT_NAME','professional-portfolio');


/**
 * The code that runs during plugin activation.
*/

function activatePport(){
	require_once PPORT_CLASSES_DIR . 'class.activator.php';
	PPORT_Activator::activate();
}
register_activation_hook( __FILE__, 'activatePport' );

/**
 * The code that runs during plugin deactivation.
 */
function deactivatePport(){
	require_once PPORT_CLASSES_DIR . 'class.deactivator.php';
	PPORT_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivatePport' );


function uninstallPport(){
    global $wpdb;
}

register_uninstall_hook(__FILE__,'uninstallPport');


/**
 * The core plugin class that is used to define 
 * admin-specific hooks, and public-specific site hooks.
 */
require_once PPORT_CLASSES_DIR . 'class.portfolio.php';

function runProfessionalPortfolio() {
	
	$plugin = new ProfessionalPortfolio();
	$plugin->run();
}
runProfessionalPortfolio();
