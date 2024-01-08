<?php 

function velocity_quiz_user($type = 'quiz') {
    global $wpdb;
    $table_name = $wpdb->prefix."velocity_quiz";
    $user_id = get_current_user_id();
    $set = isset($_GET['set']) ? $_GET['set'] : '';
    $post_args = array(
        'showposts' => -1,
        'post_type' => array('velocity-'.$type),
    ); 
    $sudahdikerjakan = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $user_id");
    if($sudahdikerjakan && $set != 'sudah'){
        foreach($sudahdikerjakan as $qp){
            $idpost[] = $qp->post_id;
        }
        $post_args['post__not_in'] = $idpost;
    } elseif($sudahdikerjakan && $set == 'sudah') {
        foreach($sudahdikerjakan as $qp){
            $idpost[] = $qp->post_id;
        }
        $post_args['post__in'] = $idpost;
    }
    $post_list = get_posts($post_args);

    if(empty($sudahdikerjakan) && $set == 'sudah') {
        $post_list = '';
    }

    $classbelum = $set != 'sudah' ? ' active fw-bold' :'';
    $classsudah = $set == 'sudah' ? ' active fw-bold' :'';
    $html = '';
    $html .= '<ul class="nav nav-tabs mb-3">';
        $html .= '<li class="nav-item"><a class="nav-link'.$classbelum.'" href="?hal='.$type.'">Belum Dikerjakan</a></li>';
        $html .= '<li class="nav-item"><a class="nav-link'.$classsudah.'" href="?hal='.$type.'&set=sudah">Sudah Dikerjakan</a></li>';
    $html .= '</ul>';
    $html .= '<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>';
        if($set == 'sudah'){
            $html .= '<th scope="col">Dikerjakan</th>';
        } else {
            $html .= '<th scope="col">Tanggal</th>';
        }
        $html .= '<th scope="col" class="text-capitalize">Nama '.$type.'</th>';
        if($set == 'sudah'){
            $html .= '<th scope="col">Nilai</th>';
        }
        $html .= '<th scope="col" class="text-center">Lihat</th>
        </tr>
    </thead>
    <tbody>';
    if(empty($post_list)){
        $html .= '<tr>';
            $html .= '<td colspan="3"><small class="text-muted fst-italic text-center d-block">Tidak ada data ditemukan.</small></td>';
        $html .= '</tr>';
    } else {
        foreach ($post_list as $post) {
            $post_id = $post->ID;
            $post_date = get_the_date('Y-m-d H:i', $post_id);
            $quizjawab = $wpdb->get_results("SELECT * FROM $table_name WHERE post_id = $post_id and user_id = $user_id");        
            $hasil_ujian = '';
            if($quizjawab){
                $nilai = $quizjawab[0]->nilai?$quizjawab[0]->nilai:'<small class="fst-italic fw-normal text-muted">pending</small>';
                $hasil_ujian = '<td class="align-middle text-success fw-bold">'.$nilai.'</td>';
            }
            $html .= '<tr>';
                $html .= '<td class="align-middle">'.$post_date.'</td>';
                $html .= '<td class="align-middle">'.$post->post_title.'</td>';
                $html .= $hasil_ujian;
                $html .= '<td class="align-middle text-center px-0">';
                    $html .= '<a href="'.get_the_permalink($post_id).'" class="btn btn-sm btn-primary m-1" title="Lihat" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                    </svg></a>';
                $html .= '</td>';
            $html .= '</tr>';
        }
    }
    $html .= '</tbody></table></div>';
    return $html;
}