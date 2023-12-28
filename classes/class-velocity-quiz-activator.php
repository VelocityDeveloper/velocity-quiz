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

    private static function create_custom_post_type() {
        $new_post_type = array(
            array(
                'slug' => 'velocity-quiz',
                'name' => 'Velocity Quiz',
                'menu_icon' => 'dashicons-media-text',
            ),
            array(
                'slug' => 'velocity-essay',
                'name' => 'Velocity Essay',
                'menu_icon' => 'dashicons-welcome-write-blog',
            ),
        );
        foreach($new_post_type as $post_type){
            $args = array(
                'labels' => array(
                    'name' => $post_type['name'],
                    'singular_name' => $post_type['slug'],
                ),
                'menu_icon' => $post_type['menu_icon'],
                'public' => true,
                'has_archive' => true,
                'taxonomies' => array('quiz-category'),
                'supports' => array(
                    'title',
                    'editor',
                ),
            );
            register_post_type($post_type['slug'], $args);
        }
    }

    private static function create_custom_taxonomy() {
        $args = array(
            'label' => 'Kategori Kustom',
            'public' => true,
            'hierarchical' => true,
        );
        register_taxonomy('kategori_kustom', 'nama_post_type', $args);
    }
}