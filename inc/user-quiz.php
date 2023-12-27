<?php global $wpdb;
$table_quiz = $wpdb->prefix."velocity_quiz";
$user_id = get_current_user_id();
$set = isset($_GET['set']) ? $_GET['set'] : '';
$quiz_args = array(
    'showposts' => -1,
    'post_type' => array('velocity-quiz'),
); 
$sudahdikerjakan = $wpdb->get_results("SELECT * FROM $table_quiz WHERE user_id = $user_id");
if($sudahdikerjakan && $set != 'sudah'){
    foreach($sudahdikerjakan as $qp){
        $idpost[] = $qp->post_id;
    }
    $quiz_args['post__not_in'] = $idpost;
}
$quizposts = get_posts($quiz_args);

$classbelum = $set != 'sudah' ? ' active fw-bold' :'';
$classsudah = $set == 'sudah' ? ' active fw-bold' :'';
echo '<div class="container">';
  echo '<ul class="nav nav-tabs mb-3">';
    echo '<li class="nav-item"><a class="nav-link'.$classbelum.'" href="?">Belum Dikerjakan</a></li>';
    echo '<li class="nav-item"><a class="nav-link'.$classsudah.'" href="?set=sudah">Sudah Dikerjakan</a></li>';
  echo '</ul>';
  echo '<div class="table-responsive">
  <table class="table table-bordered">
     <thead>
    <tr>';
    if($set == 'sudah'){
        echo '<th scope="col">Dikerjakan</th>';
    } else {
        echo '<th scope="col">Tanggal</th>';
    }
    echo '<th scope="col">Nama Quiz</th>';
      if($set == 'sudah'){
        echo '<th scope="col">Nilai</th>';
      }
      echo '<th scope="col" class="text-center">Lihat</th>
    </tr>
  </thead>
  <tbody>';
  if(empty($quizposts)){
    echo '<tr>';
        echo '<td colspan="3"><small class="text-muted fst-italic text-center d-block">Tidak ada data ditemukan.</small></td>';
    echo '</tr>';
  } else {
    foreach ($quizposts as $quizpost) {
        $post_id = $quizpost->ID;
        $post_date = get_the_date('Y-m-d H:i', $post_id);
        $sudahjawab = $wpdb->get_results("SELECT * FROM $table_quiz WHERE post_id = $post_id and user_id = $user_id");        
        if($sudahjawab){
            $detailquiz = $sudahjawab[0]->detail;
            $detail = json_decode($detailquiz);
            $post_date = $sudahjawab[0]->date;
        }
        echo '<tr class="quiz-'.$post_id.'">';
            echo '<td>'.$post_date.'</td>';
            echo '<td>'.$quizpost->post_title.'</td>';
            if($sudahjawab){
                echo '<td class="text-success fw-bold">'.$detail->nilai.'</td>';
            }
            echo '<td class="text-center px-0">';
                echo '<a href="'.get_the_permalink($post_id).'" class="btn btn-sm btn-primary m-1" title="Lihat" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                </svg></a>';
            echo '</td>';
        echo '</tr>';
    }
  }
  echo '</tbody></table></div>';

echo '</div>';