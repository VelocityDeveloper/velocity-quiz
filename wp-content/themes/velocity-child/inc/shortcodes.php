<?php
/**
 * Kumpulan shortcode yang digunakan di theme ini.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
//[resize-thumbnail width="300" height="150" linked="true" class="w-100"]
add_shortcode('resize-thumbnail', 'resize_thumbnail');
function resize_thumbnail($atts) {
    ob_start();
	global $post;
    $atribut = shortcode_atts( array(
        'output'	=> 'image', /// image or url
        'width'    	=> '300', ///width image
        'height'    => '150', ///height image
        'crop'      => 'false',
        'upscale'   	=> 'true',
        'linked'   	=> 'true', ///return link to post	
        'class'   	=> 'w-100', ///return class name to img	
        'attachment' 	=> 'true',
        'post_id' 	=> $post->ID
    ), $atts );

    $post_id		= $atribut['post_id'];
    $output			= $atribut['output'];
    $attach         = $atribut['attachment'];
    $width          = $atribut['width'];
    $height         = $atribut['height'];
    $crop           = $atribut['crop'];
    $upscale        = $atribut['upscale'];
    $linked        	= $atribut['linked'];
    $class        	= $atribut['class']?'class="'.$atribut['class'].'"':'';
	$urlimg			= get_the_post_thumbnail_url($post_id,'full');
	
	if(empty($urlimg) && $attach == 'true'){
          $attachments = get_posts( array(
            'post_type' 		=> 'attachment',
            'posts_per_page' 	=> 1,
            'post_parent' 		=> $post_id,
        	'orderby'          => 'date',
        	'order'            => 'DESC',
          ) );
          if ( $attachments ) {
				$urlimg = wp_get_attachment_url( $attachments[0]->ID, 'full' );
          }
    }

	if($urlimg):
		$urlresize      = aq_resize( $urlimg, $width, $height, $crop, true, $upscale );
		if($output=='image'):
			if($linked=='true'):
				echo '<a href="'.get_the_permalink($post_id).'" title="'.get_the_title($post_id).'">';
			endif;
			echo '<img src="'.$urlresize.'" width="'.$width.'" height="'.$height.'" loading="lazy" '.$class.'>';
			if($linked=='true'):
				echo '</a>';
			endif;
		else:
			echo $urlresize;
		endif;

	else:
		if($linked=='true'):
			echo '<a href="'.get_the_permalink($post_id).'" title="'.get_the_title($post_id).'">';
		endif;
		echo '<svg style="background-color: #ececec;width: 100%;height: auto;" width="'.$width.'" height="'.$height.'"></svg>';
		if($linked=='true'):
			echo '</a>';
		endif;
	endif;

	return ob_get_clean();
}

//[excerpt count="150"]
add_shortcode('excerpt', 'vd_getexcerpt');
function vd_getexcerpt($atts){
    ob_start();
	global $post;
    $atribut = shortcode_atts( array(
        'count'	=> '150', /// count character
    ), $atts );

    $count		= $atribut['count'];
    $excerpt	= get_the_content();
    $excerpt 	= strip_tags($excerpt);
    $excerpt 	= substr($excerpt, 0, $count);
    $excerpt 	= substr($excerpt, 0, strripos($excerpt, " "));
    $excerpt 	= ''.$excerpt.'...';

    echo $excerpt;

	return ob_get_clean();
}

// [vd-breadcrumbs]
add_shortcode('vd-breadcrumbs','vd_breadcrumbs');
function vd_breadcrumbs() {
    ob_start();
    if ( shortcode_exists( 'velocity-breadcrumbs' ) ) {
        echo do_shortcode('[velocity-breadcrumbs]');
    } else {
        echo justg_breadcrumb();
    }
    return ob_get_clean();
}

//[ratio-thumbnail size="medium" ratio="16:9"]
add_shortcode('ratio-thumbnail', 'ratio_thumbnail');
function ratio_thumbnail($atts) {
    ob_start();
	global $post;

    $atribut = shortcode_atts( array(
        'size'      => 'medium', // thumbnail, medium, large, full
        'ratio'     => '16:9', // 16:9, 8:5, 4:3, 3:2, 1:1
    ), $atts );

    $size       = $atribut['size'];
    $ratio      = $atribut['ratio'];
    $ratio      = $ratio?str_replace(":","-",$ratio):'';
	$urlimg     = get_the_post_thumbnail_url($post->ID,$size);

    echo '<div class="ratio-thumbnail">';
        echo '<a class="ratio-thumbnail-link" href="'.get_the_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
            echo '<div class="ratio-thumbnail-box ratio-thumbnail-'.$ratio.'" style="background-image: url('.$urlimg.');">';
                echo '<img src="'.$urlimg.'" loading="lazy" class="ratio-thumbnail-image"/>';
            echo '</div>';
        echo '</a>';
    echo '</div>';

	return ob_get_clean();
}



add_shortcode('tombol-whatsapp', function($atts) {
	global $post;
    $atribut = shortcode_atts( array(
        'post_id' 	=> $post->ID
    ), $atts );
    $post_id = $atribut['post_id'];	
    $wa = velocitytheme_option('whatsapp_number', '');
	$html = '';    
	// replace all except numbers
    $whatsapp_number = $wa ? preg_replace('/[^0-9]/', '', $wa) : $wa;	
    // replace 0 with 62 if first digit is 0
    if (substr($whatsapp_number, 0, 1) == 0) {
        $whatsapp_number    = substr_replace($whatsapp_number, '62', 0, 1);
    }	
	// if whatsapp_number exist
    if($wa) {
        $msg = 'Saya ingin memesan produk '.get_the_title($post_id).' '.get_the_permalink($post_id);
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/></svg>';
		$html .= '<a class="btn btn-success text-white px-3" href="https://wa.me/'.$whatsapp_number.'?text='.$msg.'" target="_blank">'.$icon.' Pesan Sekarang</a>';
    }
    return $html;
});


add_shortcode('velocity-menu-sitemap', function($atts) {
    ob_start();
    $defaults = array(
        'menu'			=> 'priamry',
        'container'		=> false,
        'menu_class'	=> 'velocity-menu',
    );
    wp_nav_menu( $defaults ); 
    return ob_get_clean();
});