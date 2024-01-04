<?php

add_action( 'wp_ajax_nopriv_submitquiz', 'submitquiz_ajax' );
add_action('wp_ajax_submitquiz', 'submitquiz_ajax');
function submitquiz_ajax() {
    global $wpdb;
    $table_name = $wpdb->prefix . "velocity_quiz";
    $date = date( 'Y/m/d H:i:s', current_time( 'timestamp', 0 ) );
    $post_id = isset($_POST['post_id'])?$_POST['post_id'] : '';
    $jawaban = isset($_POST['jawaban'])?$_POST['jawaban'] : '';
    $quiz = get_post_meta($post_id,'quiz',true);
    $tampilnilai = get_post_meta($post_id,'tampil_nilai',true);
    $i = 0;
    $benar[] = 0;
    $salah[] = 0;
    $nilai = 0;
    foreach ($jawaban as $jawab) {
        $no = $i++;
        if($quiz[$no]['jawaban'] == $jawab){
            $benar[] = 1;
        } else {
            $salah[] = 1;
        }
    }
    $jml_benar = array_sum($benar);
    $jml_salah = array_sum($salah);
    $jumlahsoal = $jml_benar + $jml_salah;
    if($jml_benar || $jml_salah){
        $nilaix = 100 / $jumlahsoal * $jml_benar;
        $nilai  = (round($nilaix,1));
    }
    $array_jawaban = array(
        'benar' => $jml_benar,
        'salah' => $jml_salah,
    );
    if(!current_user_can('administrator')) {
        $wpdb->insert($table_name, array(
            'vq_date' => $date,
            'vq_type' => 'quiz',
            'user_id' => get_current_user_id(),
            'post_id' => $post_id,
            'vq_detail'  => json_encode($array_jawaban),
            'nilai'  => $nilai,
        ));
    }
    echo '<div class="card mx-auto w-100" style="max-width: 500px;">';
        echo '<div class="card-hasil-nilai card-body text-center bg-nilai">';
			if($tampilnilai=='Ya') {
				echo '<h3 class="card-title">Nilai anda:</h3>';
				echo '<p class="card-text h1 fs-1">'.$nilai.'</p>';
			} else {
				echo '<h3 class="card-text h2 text-white">Quiz telah selesai</h3>';
			}
        echo '</div>';
		if($tampilnilai=='Ya') {
			echo '<ul class="list-group list-group-flush">';
                echo '<li class="list-group-item"><i class="fa fa-check text-success"></i> Benar = '.$jml_benar.'</li>';
                echo '<li class="list-group-item"><i class="fa fa-close text-danger"></i> Salah = '.$jml_salah.'</li>';
                echo '<li class="list-group-item"><i class="fa fa-wpforms"></i> Jumlah Soal = '.$jumlahsoal.'</li>';
			echo '</ul>';
		} else {
			echo '<div class="card-body text-center">';
                echo 'Terima kasih, ujian anda telah selesai. klik tombol selesai untuk kembali';
            echo '</div>';
		}
        echo '<div class="card-footer">';
            echo '<a class="btn btn-primary w-100" href="'.get_the_permalink($post_id).'">SELESAI</a>';
        echo '</div>';
    echo '</div>';
    unset($_SESSION['kerjaquiz']);
    wp_die();
}


add_action( 'wp_ajax_nopriv_submitessay', 'submitessay_ajax' );
add_action('wp_ajax_submitessay', 'submitessay_ajax');
function submitessay_ajax() {
    global $wpdb;
    $table_name = $wpdb->prefix . "velocity_quiz";
    $date = date( 'Y/m/d H:i:s', current_time( 'timestamp', 0 ) );
    $post_id = isset($_POST['post_id'])?$_POST['post_id'] : '';
    $jawaban = isset($_POST['jawaban'])?$_POST['jawaban'] : '';
    $essay = get_post_meta($post_id,'essay',true);
    if(!current_user_can('administrator')) {
        $wpdb->insert($table_name, array(
            'vq_date' => $date,
            'vq_type' => 'essay',
            'user_id' => get_current_user_id(),
            'post_id' => $post_id,
            'vq_detail'  => json_encode($jawaban),
            'vq_result'  => 'pending',
        ));
    }
    // echo '<pre>'.print_r($_POST,1).'</pre>';
    // echo '<pre>'.print_r($jawaban,1).'</pre>';
    echo '<div class="card mx-auto w-100" style="max-width: 500px;">';
        echo '<div class="card-hasil-nilai card-body text-center bg-nilai">';
			echo '<h3 class="card-text h2 text-white">Essay telah selesai</h3>';
        echo '</div>';
		echo '<div class="card-body text-center">';
            echo 'Terima kasih, ujian anda telah selesai.<br/>Klik tombol selesai untuk kembali';
        echo '</div>';
        echo '<div class="card-footer">';
            echo '<a class="btn btn-primary w-100" href="'.get_the_permalink($post_id).'">SELESAI</a>';
        echo '</div>';
    echo '</div>';
    unset($_SESSION['kerjaessay']);
    wp_die();
}


add_action('wp_ajax_hapuspost', 'hapuspost_ajax');
function hapuspost_ajax() {
    $id = isset($_POST['id'])?$_POST['id'] : '';
    if($id){
        $result = wp_delete_post($id,true);
    }
    wp_die();
}