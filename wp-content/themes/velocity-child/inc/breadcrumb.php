<?php
/**
 * breadcrumbs.
 */
add_shortcode ('velocity-breadcrumbs', 'vel_breadcrumbs');
function vel_breadcrumbs() {
       
    // Settings
    $breadcrums_id      = 'breadcrumbs';
    $breadcrums_class   = 'p-0 m-0 breadcrumbs';
    $home_title         = 'Home';
      
    // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
    $custom_taxonomy    = 'product_cat';
       
    // Get the query & post information
    global $post,$wp_query;
       
    // Do not display on the homepage
    if ( !is_front_page() ) {
       
        // Build the breadcrums
        $html = '<ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">';
           
        // Home page
        $html .= '<li class="breadcrumb-item d-inline-block"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';
           
        if ( is_archive() && !is_tax() && !is_category() && !is_tag() ) {
              
            $custom_tax_name = get_queried_object()->label;
            $html .= '<li class="breadcrumb-item d-inline-block item-archive">'.$custom_tax_name.'</li>';
              
        } else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {
              
              
            $current_taxonomy = get_queried_object()->taxonomy;
			if($current_taxonomy == 'kategori-produk'){
				$post_type = 'produk';
			} else {
				$post_type = 'post';
			}
              
            // If it is a custom post type display name and link
            if($post_type != 'post') {
                  
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
              
                $html .= '<li class="breadcrumb-item d-inline-block item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
              
            }
              
            $custom_tax_name = get_queried_object()->name;
            $html .= '<li class="breadcrumb-item d-inline-block active item-current item-archive">' . $custom_tax_name . '</li>';
              
        } else if ( is_single() ) {
              
            // If post is a custom post type
            $post_type = get_post_type();
              
            // If it is a custom post type display name and link
            if($post_type != 'post') {
                  
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
              
                $html .= '<li class="breadcrumb-item d-inline-block item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
              
            }
              
            // Get post category info
            $category = get_the_category();
             
            if(!empty($category)) {
              
                // Get last category post is in
                $thelast = array_values($category);
                $last_category = end($thelast);
                  
                // Get parent any categories and create array
                $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
                $cat_parents = explode(',',$get_cat_parents);
                  
                // Loop through parent categories and store in variable $cat_display
                $cat_display = '';
                foreach($cat_parents as $parents) {
                    $cat_display .= '<li class="item-cat breadcrumb-item d-inline-block">'.$parents.'</li>';
                }
             
            }
              
            // If it's a custom post type within a custom taxonomy
            $taxonomy_exists = taxonomy_exists($custom_taxonomy);
            if(empty($last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {
                   
                $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
                $cat_id         = $taxonomy_terms[0]->term_id;
                $cat_nicename   = $taxonomy_terms[0]->slug;
                $cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
                $cat_name       = $taxonomy_terms[0]->name;
               
            }
              
            // Check if the post is in a category
            if(!empty($last_category)) {
                $html .= $cat_display;
                $html .= '<li class="breadcrumb-item d-inline-block item-current item-' . $post->ID . '">' . get_the_title() . '</li>';
                  
            // Else if post is in a custom taxonomy
            } else if(!empty($cat_id)) {
                  
                $html .= '<li class="breadcrumb-item d-inline-block item-cat item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '"><a class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></li>';
                $html .= '<li class="breadcrumb-item d-inline-block item-current item-' . $post->ID . '">' . get_the_title() . '</li>';
              
            } else {
                  
                $html .= '<li class="breadcrumb-item d-inline-block active item-current item-' . $post->ID . '">' . get_the_title() . '</li>';
                  
            }
              
        } else if ( is_category() ) {
               
            // Category page
            $html .= '<li class="breadcrumb-item d-inline-block active item-current item-cat">' . single_cat_title('', false) . '</li>';
               
        } else if ( is_page() ) {
               
            // Standard page
            if( $post->post_parent ){
                   
                // If child page, get parents 
                $anc = get_post_ancestors( $post->ID );
                   
                // Get parents in the right order
                $anc = array_reverse($anc);
                   
                // Parent page loop
                if ( !isset( $parents ) ) $parents = null;
                foreach ( $anc as $ancestor ) {
                    $parents .= '<li class="breadcrumb-item d-inline-block item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
                }
                   
                // Display parent pages
                $html .= $parents;
                   
                // Current page
                $html .= '<li class="breadcrumb-item d-inline-block active item-current item-' . $post->ID . '">' . get_the_title() . '</li>';
                   
            } else {
                   
                // Just display current page if not parents
                $html .= '<li class="breadcrumb-item d-inline-block activeitem-current item-' . $post->ID . '">' . get_the_title() . '</li>';
                   
            }
               
        } else if ( is_tag() ) {
               
            // Tag page
               
            // Get tag information
            $term_id        = get_query_var('tag_id');
            $taxonomy       = 'post_tag';
            $args           = 'include=' . $term_id;
            $terms          = get_terms( $taxonomy, $args );
            $get_term_id    = $terms[0]->term_id;
            $get_term_slug  = $terms[0]->slug;
            $get_term_name  = $terms[0]->name;
               
            // Display the tag name
            $html .= '<li class="breadcrumb-item d-inline-block active item-current item-tag-' . $get_term_id . ' item-tag-' . $get_term_slug . '">' . $get_term_name . '</li>';
           
        } elseif ( is_day() ) {
               
            // Day archive
               
            // Year link
            $html .= '<li class="breadcrumb-item d-inline-block item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
               
            // Month link
            $html .= '<li class="breadcrumb-item d-inline-block item-month item-month-' . get_the_time('m') . '"><a class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></li>';
               
            // Day display
            $html .= '<li class="breadcrumb-item d-inline-block active item-current item-' . get_the_time('j') . '">' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</li>';
               
        } else if ( is_month() ) {
               
            // Month Archive
               
            // Year link
            $html .= '<li class="breadcrumb-item d-inline-block item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
               
            // Month display
            $html .= '<li class="breadcrumb-item d-inline-block item-month item-month-' . get_the_time('m') . '">' . get_the_time('M') . ' Archives</li>';
               
        } else if ( is_year() ) {
               
            // Display year archive
            $html .= '<li class="breadcrumb-item d-inline-block active item-current item-current-' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</li>';
               
        } else if ( is_author() ) {
               
            // Auhor archive
               
            // Get the author information
            global $author;
            $userdata = get_userdata( $author );
               
            // Display author name
            $html .= '<li class="breadcrumb-item d-inline-block active item-current item-current-' . $userdata->user_nicename . '">' . 'Author: ' . $userdata->display_name . '</li>';
           
        } else if ( get_query_var('paged') ) {
               
            // Paginated archives
            $html .= '<li class="breadcrumb-item d-inline-block active item-current item-current-' . get_query_var('paged') . '">' . 'Page' . ' ' . get_query_var('paged') . '</li>';
               
        } else if ( is_search() ) {
           
            // Search results page
            $html .= '<li class="breadcrumb-item d-inline-block active item-current item-current-' . get_search_query() . '">Search results for: ' . get_search_query() . '</li>';
           
        } elseif ( is_404() ) {
               
            // 404 page
            $html .= '<li class="breadcrumb-item d-inline-block active item-current">' . 'Error 404' . '</li>';
        }
       
        $html .= '</ul>';
           
    }
return $html;
}