<?php
/**
 * Fuction yang digunakan di theme ini.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}




add_action('init', 'velocity_admin_init');
function velocity_admin_init() {
    register_post_type('produk', array(
        'labels' => array(
            'name' => 'Produk',
            'singular_name' => 'produk',
            'add_new' => 'Tambah Produk Baru',
            'add_new_item' => 'Tambah Produk Baru',
            'edit_item' => 'Edit Produk',
            'view_item' => 'Lihat Produk',
            'search_items' => 'Cari Produk',
            'not_found' => 'Tidak ditemukan',
            'not_found_in_trash' => 'Tidak ada Produk di kotak sampah'
        ),
        'menu_icon' => 'dashicons-screenoptions',
        'public' => true,
        'has_archive' => true,
		'show_in_rest' => true, // Use Gutenberg
        'taxonomies' => array('kategori-produk'),
        'supports' => array(
            'title',
            'editor',
            'thumbnail',
        ),
    ));
	register_taxonomy(
	'kategori-produk',
	'produk',
	array(
		'label' => __( 'Kategori Produk' ),
		'hierarchical' => true,
		'show_admin_column' => true,
		'show_in_rest' => true, // Use Gutenberg
	));
}

function velocity_add_taxonomy_filters() {
	global $typenow; 
	// an array of all the taxonomyies you want to display. Use the taxonomy name or slug
	$taxonomies = array('kategori-produk'); 
	// must set this to the post type you want the filter(s) displayed on
	if( $typenow == 'produk' ){ 
		foreach ($taxonomies as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			if(count($terms) > 0) {
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>Semua $tax_name</option>";
				foreach ($terms as $term) { 
					echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>'; 
				}
				echo "</select>";
			}
		}
	}
}
add_action( 'restrict_manage_posts', 'velocity_add_taxonomy_filters' );

