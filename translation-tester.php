<?php
/**
 * Plugin Name:       Translation Tester
 * Plugin URI:        http://pickngrip.com/translation-tester/
 * Description:       Check if the POT file that you are trying to use contains all the strings of the plugin or theme.
 * Version:           1.0.0
 * Author:            Radu Constantin
 * Author URI:        http://pickngrip.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       translation-tester
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-translation-tester.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

$translatation_tester_plugin = new Translation_Tester();
