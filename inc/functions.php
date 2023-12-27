<?php

// add post type and taxnomomy
add_action('init', 'velocity_quiz_init');
function velocity_quiz_init() {
    register_post_type('velocity-quiz', array(
        'labels' => array(
            'name' => 'Velocity Quiz',
            'singular_name' => 'velocity-quiz',
        ),
        'menu_icon' => 'dashicons-media-text',
        'public' => true,
        'has_archive' => true,
		//'show_in_rest' => true, // Use Gutenberg
        'taxonomies' => array('quiz-category'),
        'supports' => array(
            'title',
            'editor',
        ),
    ));
	register_taxonomy(
	'quiz-category',
	'velocity-quiz',
	array(
		'label' => __( 'Quiz Categories' ),
		'hierarchical' => true,
		'show_admin_column' => true,
		//'show_in_rest' => true, // Use Gutenberg
	));
}

// taxonomy filter
function velocity_quiz_taxonomy_filters() {
	global $typenow; 
	// an array of all the taxonomyies you want to display. Use the taxonomy name or slug
	$taxonomies = array('quiz-category'); 
	// must set this to the post type you want the filter(s) displayed on
	if( $typenow == 'velocity-quiz' ){ 
		foreach ($taxonomies as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			if(count($terms) > 0) {
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>All $tax_name</option>";
				foreach ($terms as $term) { 
					echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>'; 
				}
				echo "</select>";
			}
		}
	}
}
add_action( 'restrict_manage_posts', 'velocity_quiz_taxonomy_filters' );


// shortcode
function velocity_quiz() {
    ob_start();
	if(current_user_can('administrator')){
    	require_once(VELOCITY_QUIZ_DIR.'/inc/page-quiz.php');
	} elseif(is_user_logged_in()){
		require_once(VELOCITY_QUIZ_DIR.'/inc/user-quiz.php');
	} else {
		$login_args = array(
			'form_id' => 'velocity-login-form',
		);
		wp_login_form($login_args);
	}
    return ob_get_clean();
}
add_shortcode ('velocity-quiz', 'velocity_quiz');



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

function velocity_quiz_single($single_template) {
    global $post;
    if ($post->post_type == 'velocity-quiz') {
        $single_template = VELOCITY_QUIZ_DIR.'inc/quiz-tampil.php';
    }
    return $single_template;
}
add_filter('single_template', 'velocity_quiz_single');
