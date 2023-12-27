<?php
/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    velocity-quiz
 * @subpackage velocity-quiz/classes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 */
class Velocity_Quiz_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        self::create_velocity_quiz();
        flush_rewrite_rules();
	}

    public static function create_velocity_quiz() {
        
        global $wpdb;
        date_default_timezone_set('Asia/Jakarta'); 
        $v_quiz = $wpdb->prefix . "velocity_quiz";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $sqlv_quiz = "CREATE TABLE IF NOT EXISTS $v_quiz (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                type varchar(20) NOT NULL,
                date datetime NOT NULL,
                user_id bigint(20) NOT NULL,
                post_id bigint(20) NOT NULL,
                detail text NOT NULL,
                PRIMARY KEY (id)
        );";
        dbDelta($sqlv_quiz);

    }

}