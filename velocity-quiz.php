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

/**
 * Define constants
 *
 * @since 1.2.0
 */
if (!defined('VELOCITY_QUIZ_DIR'))	define('VELOCITY_QUIZ_DIR', plugin_dir_path(__FILE__)); // Plugin directory absolute path with the trailing slash. Useful for using with includes eg - /var/www/html/wp-content/plugins/velocity-quiz/
if (!defined('VELOCITY_QUIZ_DIR_URI'))	define('VELOCITY_QUIZ_DIR_URI', plugin_dir_url(__FILE__)); // URL to the plugin folder with the trailing slash. Useful for referencing src eg - http://localhost/wp-content/plugins/velocity-quiz


/// Load everything
$includes = [
	'inc/functions.php',			// fungsi
	'inc/user-functions.php',		// fungsi utk user
	'inc/ajax.php',			// ajax
];
foreach ($includes as $include) {
	require_once(VELOCITY_QUIZ_DIR.$include);
}

/**
 * The code that runs during plugin activation.
 * This action is documented in classes/class-velocity-quiz-activator.php
 */
function activate_velocity_quiz()
{
	require_once VELOCITY_QUIZ_DIR . 'classes/class-velocity-quiz-activator.php';
	Velocity_Quiz_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in classes/class-velocity-quiz-deactivator.php
 */
function deactivate_velocity_quiz()
{
	require_once VELOCITY_QUIZ_DIR . 'classes/class-velocity-quiz-deactivator.php';
	Velocity_Quiz_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_velocity_quiz');
register_deactivation_hook(__FILE__, 'deactivate_velocity_quiz');