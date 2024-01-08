<?php

add_action('init', 'velocity_admin_init');
function velocity_admin_init() {
    global $wp_rewrite;
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
	$post_type_list = array();
	foreach ($new_post_type as $post_type) {
		$pt_slug = $post_type['slug'];
		$args = array(
			'labels' => array(
				'name' => $post_type['name'],
				'singular_name' => $pt_slug,
			),
			'menu_icon' => $post_type['menu_icon'],
			'public' => true,
			'has_archive' => true,
			'taxonomies' => array('quiz-category'),
			'rewrite' => array('slug' => $pt_slug, 'with_front' => false),
			'supports' => array(
				'title',
				'editor',
			),
		);
		register_post_type($pt_slug, $args);
		$post_type_list[] = $pt_slug;
	}
	$tax_args = array(
		'label' => 'Quiz Categories',
		'hierarchical' => true,
		'show_admin_column' => true,
	);
	register_taxonomy('quiz-category', $post_type_list, $tax_args);

	// mengatur ulang permalink wordpress
    if (!get_option('vq_activated')) {
        global $wp_rewrite;
        $structure = get_option('permalink_structure');
        $wp_rewrite->set_permalink_structure($structure);
        $wp_rewrite->flush_rules();
        update_option('vq_activated', true);
    }
}


// Add custome scripts and styles
function velocity_quiz_scripts() {
	$wptheme = wp_get_theme( 'velocity' );
	if (!$wptheme->exists()) {
		wp_enqueue_style( 'vq-bootstrap-style', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
		wp_enqueue_script( 'vq-bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array(), null, true );
	}
	wp_enqueue_style( 'vq-style', VELOCITY_QUIZ_DIR_URI . '/css/velocity-quiz.css');
	wp_enqueue_script( 'vq-js', VELOCITY_QUIZ_DIR_URI . '/js/velocity-quiz.js', array(), null, true );
}
add_action( 'wp_enqueue_scripts', 'velocity_quiz_scripts' );


/*
// Fungsi untuk menambahkan CSS & JavaScript ke halaman admin
function enqueue_admin_scripts() {
    $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';
    if ($post_type == 'velocity-quiz') {
        wp_enqueue_style('vd-quiz-admin-style', VELOCITY_QUIZ_DIR_URI.'/css/admin-style.css');
        //wp_enqueue_script('vd-quiz-admin-script', VELOCITY_QUIZ_DIR_URI.'/js/admin-script.js', array('jquery'), '', true);
    }
}
add_action('admin_enqueue_scripts', 'enqueue_admin_scripts');
*/


// Fungsi yang akan dijalankan ketika pengguna dihapus
function velocity_quiz_on_user_delete($user_id) {
	global $wpdb;
	$table_quiz = $wpdb->prefix."velocity_quiz";
	$ada = $wpdb->get_results("SELECT * FROM $table_quiz WHERE user_id = $user_id");
	if($ada){
		$wpdb->delete($table_quiz, array('user_id' => $user_id,));
	}
}
add_action('delete_user', 'velocity_quiz_on_user_delete');


// Fungsi yang akan dijalankan setelah posting dihapus
function velocity_quiz_on_delete_post($post_id) {
	global $wpdb;
	$table_quiz = $wpdb->prefix."velocity_quiz";
	$ada = $wpdb->get_results("SELECT * FROM $table_quiz WHERE post_id = $post_id");
	if($ada){
		$wpdb->delete($table_quiz, array('post_id' => $post_id,));
	}
}
add_action('delete_post', 'velocity_quiz_on_delete_post');


// mengatur template default single quiz
function velocity_quiz_single($single_template) {
    global $post;
    if($post->post_type == 'velocity-quiz') {
        $single_template = VELOCITY_QUIZ_DIR.'inc/quiz-tampil.php';
    } else if($post->post_type == 'velocity-essay') {
        $single_template = VELOCITY_QUIZ_DIR.'inc/essay-tampil.php';
    }
    return $single_template;
}
add_filter('single_template', 'velocity_quiz_single');


// shortcode
function velocity_quiz() {
    ob_start();
	if(current_user_can('administrator')){
    	require_once(VELOCITY_QUIZ_DIR.'/inc/page-quiz.php');
	} elseif(is_user_logged_in()){
		require_once(VELOCITY_QUIZ_DIR.'/inc/user-home.php');
	} else {
		$login_args = array(
			'form_id' => 'velocity-login-form',
		);
		wp_login_form($login_args);
	}
    return ob_get_clean();
}
add_shortcode ('velocity-quiz', 'velocity_quiz');
