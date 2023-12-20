<?php

/**
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://velocitydeveloper.com
 * @since             1.0.0
 * @package           Velocity_Quiz
 *
 * @wordpress-plugin
 * Plugin Name:       Velocity Quiz
 * Plugin URI:        https://velocitydeveloper.com
 * Description:       Quiz plugin by Velocity Developer
 * Version:           1.0.0
 * Author:            Velocity
 * Author URI:        https://velocitydeveloper.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       velocity-quiz
 * Domain Path:       /languages
 */

 // If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('VELOCITY_QUIZ_VERSION', '1.0.0');

define('PLUGIN_DIR', plugin_dir_path(__DIR__));
define('PLUGIN_FILE', plugin_basename(__FILE__));
define('PLUGIN_BASE_NAME', plugin_basename(__DIR__));
define('VELOCITY_QUIZ_DIR_URL', plugin_dir_url(__FILE__));



function velocity_quiz() {
    ob_start();
    require_once(plugin_dir_path(__FILE__).'/inc/page-quiz.php');
    return ob_get_clean();
}
add_shortcode ('velocity-quiz', 'velocity_quiz');