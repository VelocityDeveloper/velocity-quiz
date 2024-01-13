<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


add_action('init', 'velocity_admin_init');
function velocity_admin_init() {
    register_post_type('laporan', array(
        'labels' => array(
            'name' => 'Laporan',
            'singular_name' => 'laporan',
            'add_new' => 'Tambah Laporan Baru',
            'add_new_item' => 'Tambah Laporan Baru',
            'edit_item' => 'Ubah Laporan',
            'view_item' => 'Lihat Laporan',
            'search_items' => 'Cari Laporan',
            'not_found' => 'Tidak ditemukan',
            'not_found_in_trash' => 'Tidak ada laporan di kotak sampah'
        ),
        'menu_icon' => 'dashicons-screenoptions',
        'public' => true,
        'show_ui' => true,
        'has_archive' => true,
        'taxonomies' => array('kategori-laporan'),
        'supports' => array(
            'title',
            'editor',
        ),
    ));
	register_taxonomy(
	'kategori-laporan',
	'laporan',
	array(
		'label' => __( 'Kategori Laporan' ),
		'hierarchical' => true,
		'show_admin_column' => true,
	));

    $taxonomy = 'kategori-laporan';
    $term_list = ['Pengaduan','Aspirasi','Permintaan Informasi'];
    foreach($term_list as $term_name){
        $term = get_term_by('name', $term_name, $taxonomy);
        if (!$term) {
            wp_insert_term($term_name, $taxonomy);
        }
    }

}



add_filter( 'rwmb_meta_boxes', 'vel_metabox' );
function vel_metabox( $meta_boxes ){
	$textdomain = 'justg';
	$meta_boxes[] = array(
		'id'         => 'standard',
		'title'      => __( 'Velocity Fields', $textdomain ),
		'post_types' => array( 'laporan' ),
		'context'    => 'normal',
		'priority'   => 'high',
		'autosave'   => true,
		'fields'     => array(
			array(
                'id'               => 'tglkejadian',
                'name'             => 'Tanggal Kejadian',
                'type'             => 'text',
            ),
			array(
                'id'               => 'lokasi',
                'name'             => 'Lokasi Kejadian',
                'type'             => 'text',
            ),
			array(
                'id'               => 'asalpelapor',
                'name'             => 'Asal Pelapor',
                'type'             => 'text',
            ),
			array(
                'id'               => 'lampiran',
                'name'             => 'Lampiran',
                'type'             => 'file_advanced',
                'force_delete'     => false,
                'max_file_uploads' => 1,
                'max_status'       => false,
			),
		)
	);

	return $meta_boxes;
}



// [velocity-form-laporan]
add_shortcode('velocity-form-laporan', function() {
    ob_start();
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    $term_list = ['Pengaduan','Aspirasi','Permintaan Informasi'];
    $taxonomy = 'kategori-laporan';
    $captcha = new Velocity_Addons_Captcha();
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $content = isset($_POST['content']) ? $_POST['content'] : '';
    $jenis = isset($_POST['jenis']) ? $_POST['jenis'] : '';
    $tglkejadian = isset($_POST['tglkejadian']) ? $_POST['tglkejadian'] : '';
    $lokasi = isset($_POST['lokasi']) ? $_POST['lokasi'] : '';
    $asalpelapor = isset($_POST['asalpelapor']) ? $_POST['asalpelapor'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    if($title && $content && $status){
        $max_file_size = 2 * 1024 * 1024; // 2 MB dalam bytes
        $verify = $captcha->verify($_POST['g-recaptcha-response']);        
        if (!$verify['success']) {
            echo '<div class="alert alert-danger">reCaptcha tidak valid</div>';
        } elseif ($_FILES['lampiran']['size'] > $max_file_size) {
            echo '<div class="alert alert-danger">File terlalu besar. Maksimum 2MB.</div>';
        } else {
            $laporan_post = array(
                'post_title'    => wp_strip_all_tags($title),
                'post_content'  => wp_strip_all_tags($content),
                'post_status'   => $status,
                'post_type'     => 'laporan',
            );
            $post_id = wp_insert_post($laporan_post);            
            
            if (!is_wp_error($post_id)){
                $error_upload = '';
                if ($_FILES['lampiran']['tmp_name']) {
                    $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG);
                    $detectedType = exif_imagetype($_FILES['lampiran']['tmp_name']);
                    $error = !in_array($detectedType, $allowedTypes);
                    if ($error) {
                        $error_upload = '<div class="alert alert-danger">Gagal: Tipe file harus jpg atau png.</div>';
                    } else {
                        $uploaded_file = media_handle_upload('lampiran',$post_id);            
                        if (is_wp_error($uploaded_file)) {
                            $error_upload = '<div class="alert alert-danger">Error uploading file: ' . $uploaded_file['error'] . '</div>';
                        } else {
                            update_post_meta($post_id,'lampiran', $uploaded_file);
                        }
                    }
                }
                if ($error_upload) {
                    echo $error_upload;
                    wp_delete_post($post_id);
                } else {
                    if($tglkejadian){
                        update_post_meta($post_id,'tglkejadian', $tglkejadian);
                    } if($lokasi){
                        update_post_meta($post_id,'lokasi', $lokasi);
                    } if($asalpelapor){
                        update_post_meta($post_id,'asalpelapor', $asalpelapor);
                    }
                    wp_set_post_terms($post_id,array($jenis),$taxonomy);
                    echo '<div class="alert alert-success">Laporan terkirim</div>';
                }
            } else {
                // Menangani kesalahan pengunggahan
                echo '<div class="alert alert-danger">Terjadi kesalahan: ' . $post_id->get_error_message().'</div>';
            }
        }
    }
    ?>
    <div class="border p-3 shadow-md">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="row mx-0 pb-3 mb-3 border-bottom group-jenis" role="group" aria-label="Basic radio toggle button group">    
            <?php $i = 0;
            foreach($term_list as $term_name){
                $no = ++$i;
                $checked = $no == '1' ? 'checked' : '';
                $term = get_term_by('name',$term_name, $taxonomy);
                echo '<div class="col-sm-4 mt-3 mt-sm-0">';
                    echo '<input type="radio" class="btn-check" name="jenis" id="jenis'.$no.'" value="'.$term->term_id.'" '.$checked.' required>';
                    echo '<label class="btn btn-outline-primary w-100" for="jenis'.$no.'" id="labeljenis'.$no.'">'. $term_name.'</label>';
                echo '</div>';
            } ?>
        </div>
        <div class="mb-3">
            <input class="form-control" type="text" name="title" placeholder="Ketik judul laporan anda*" required>
        </div>
        <div class="mb-3">
            <textarea class="form-control" name="content" placeholder="Ketik isi laporan anda*" required></textarea>
        </div>

        <div class="field-pengaduan">
            <div class="mb-3">
                <label>Tanggal Kejadian*</label>
                <input class="form-control" type="date" name="tglkejadian" placeholder="Pilih tanggal kejadian*" required>
            </div>
            <div class="mb-3">
                <input class="form-control" type="text" name="lokasi" placeholder="Ketik lokasi kejadian*" required>
            </div>
        </div>

        <div class="mb-3 field-lain" style="display:none">
            <input class="form-control" type="text" name="asalpelapor" placeholder="Ketik asal pelapor*">
        </div>
        <div class="mb-3 p-3 border">
            <label for="lampiran" class="form-label">Upload Lampiran</label>
            <input class="form-control" type="file" name="lampiran" id="lampiran">
            <small>Ukuran file maksimal 2MB.</small>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="anonim" value="publish" checked required>
            <label class="form-check-label" for="anonim">
                Anonim
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="rahasia" value="private" required>
            <label class="form-check-label" for="rahasia">
                Rahasia
            </label>
        </div>
        <div class="my-3">            
            <?php $captcha->display(); ?>
        </div>
        <button type="submit" class="btn btn-primary">Lapor</button>
    </form>
    </div>

    <script>
    jQuery(function($) {
      $('.group-jenis input[type="radio"]').change(function() {
        if ($(this).is(':checked')) {
          var tabId = $(this).attr('id');
          var jenis = $("#label"+tabId).html();
          if(jenis == 'Pengaduan'){
            $('.field-lain').hide();
            $('.field-lain input').prop('required', false);
            $('.field-pengaduan').show();
            $('.field-pengaduan input').prop('required', true);
          } else {
            $('.field-lain').show();
            $('.field-lain input').prop('required', true);
            $('.field-pengaduan').hide();
            $('.field-pengaduan input').prop('required', false);
          }
        }
      });
    });
  </script>
<?php return ob_get_clean();
});



// [velocity-data-laporan kategori=""]
add_shortcode('velocity-data-laporan', function($atts) {
    $atribut = shortcode_atts( array(
        'kategori' 	=> '',
    ), $atts );
    $kategori = $atribut['kategori'];	
	$html = '';
    $args_laporan = array(
        'showposts' => -1,
        'post_type' => array('laporan'),
        'post_status' => 'publish',
    );
    if($kategori){
        $kategori = str_replace(' ', '-', $kategori);
        $kategori = strtolower($kategori);
        $args_laporan['tax_query'] = array(
            array(
                'taxonomy' => 'kategori-laporan',
                'field' => 'slug',
                'terms' => $kategori,
            )
        );
    }
    $laporanlist = get_posts($args_laporan); 
    foreach ($laporanlist as $list) {
        echo do_shortcode('[velocity-detail-laporan post_id="'.$list->ID.'"]');
    }
    return $html;
});



// [velocity-detail-laporan post_id=""]
add_shortcode('velocity-detail-laporan', function($atts) {
	global $post;
    $atribut = shortcode_atts( array(
        'post_id' 	=> $post->ID
    ), $atts );
    $post_id = $atribut['post_id'];	
    $tglkejadian = get_post_meta($post_id, 'tglkejadian', true);
    $lokasi = get_post_meta($post_id, 'lokasi', true);
    $asalpelapor = get_post_meta($post_id, 'asalpelapor', true);
    $lampiran = get_post_meta($post_id, 'lampiran', true);
    $terms = wp_get_post_terms($post_id,'kategori-laporan');
    $isi_laporan = get_the_content($post_id);
    $pengaduan = ''; 
    $html .= '<div class="border p-3 mb-3 bg-white">';
        if ($terms) {
            $html .= '<div class="mb-2 text-info fw-bold text-uppercase">';
                foreach ($terms as $term) {
                    $pengaduan = $term->slug == 'pengaduan' ? true : '';
                    $html .= $term->name.'  ';
                }
            $html .= '</div>';
        }
        $html .= '<h4 class="fs-5 fw-bold mb-3">'.get_the_title($post_id).'</h4>';
        if(!empty($pengaduan) && $tglkejadian){
            $html .= '<div class="mb-2"><strong>Tanggal Kejadian:</strong> <span class="text-dark">'.$tglkejadian.'</span></div>';
        } if(!empty($pengaduan) && $lokasi){
            $html .= '<div class="mb-2"><strong>Lokasi Kejadian:</strong> <span class="text-dark">'.$lokasi.'</span></div>';
        }
        $html .= '<div class="mb-2"><strong>Dari:</strong> <span class="text-dark">Anonim</span></div>';
        if(empty($pengaduan) && $asalpelapor){
            $html .= '<div class="mb-2"><strong>Asal Pelapor:</strong> <span class="text-dark">'.$asalpelapor.'</span></div>';
        }
        if($isi_laporan){
            $html .= '<div class="mb-0 pt-2 border-top">';
                $html .= '<div class="mb-2 fw-bold">Isi Laporan:</div>';
                $html .= $isi_laporan;
            $html .= '</div>';
        } if($lampiran){
            $attachment_url = wp_get_attachment_url($lampiran);
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
          </svg>';
            $html .= '<a class="btn btn-sm btn-success mt-3" href="'.$attachment_url.'" target="_blank">'.$icon.' Lihat Lampiran</a>';
        }
    $html .= '</div>';
    return $html;
});