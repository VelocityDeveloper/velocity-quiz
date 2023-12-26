<?php

add_action( 'wp_ajax_nopriv_submitquiz', 'submitquiz_ajax' );
add_action('wp_ajax_submitquiz', 'submitquiz_ajax');
function submitquiz_ajax() {
    // global $wpdb;
    // $table_name = $wpdb->prefix . "pelamar";
    echo '<pre>'.print_r($_POST,1).'</pre>'; 
    wp_die();
}

add_action('wp_ajax_hapusquiz', 'hapusquiz_ajax');
function hapusquiz_ajax() {
    $id = isset($_POST['id'])?$_POST['id'] : '';
    if($id){
        $result = wp_delete_post($id,true);
    }
    wp_die();
}