<?php
$halaman = isset($_GET['hal']) ? $_GET['hal'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
if($halaman == 'essay'){
  $title = 'Essay';
} else {
  $title = 'Quiz';
}
?>

<div class="container">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-3">
  <div class="container-fluid">
    <div class="navbar-brand"><?php echo $title;?></div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?php get_the_permalink();?>?">Quiz</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php get_the_permalink();?>?hal=essay">Essay</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<?php

if($halaman == 'essay' && $act == 'tambah'){
  include(VELOCITY_QUIZ_DIR.'/inc/essay-tambah.php');
} else if($halaman == 'essay' && $act == 'edit'){
  include(VELOCITY_QUIZ_DIR.'/inc/essay-edit.php');
} else if($halaman == 'essay' && $act == 'jawaban' && $id){
  include(VELOCITY_QUIZ_DIR.'/inc/essay-jawaban.php');
} else if($halaman == 'essay'){
  include(VELOCITY_QUIZ_DIR.'/inc/essay-admin.php');
} else if($halaman == 'tambah'){
  include(VELOCITY_QUIZ_DIR.'/inc/quiz-tambah.php');
} else if($halaman == 'edit' && $id){
  include(VELOCITY_QUIZ_DIR.'/inc/quiz-edit.php');
} else if($halaman == 'quiz' || empty($halaman)){
  $quiz_args = array(
    'showposts' => -1,
    'post_type' => array('velocity-quiz'),
  ); 
  $quizposts = get_posts($quiz_args);
  echo '<div class="mb-3">';
    echo '<a class="btn btn-primary btn-sm" href="?hal=tambah">Tambah Baru +</a>';
  echo '</div>';
  echo '<div class="table-responsive">
  <table class="table table-bordered">
     <thead>
    <tr>
      <th scope="col">Tanggal</th>
      <th scope="col">Nama Quiz</th>
      <th scope="col">Status</th>
      <th scope="col" class="text-center">Aksi</th>
    </tr>
  </thead>
  <tbody>';
  foreach ($quizposts as $quizpost) {
    $post_id = $quizpost->ID;
    $post_date = get_the_date('Y-m-d H:i', $post_id);
    echo '<tr class="quiz-'.$post_id.'">';
      echo '<td>'.$post_date.'</td>';
      echo '<td><a href="'.get_the_permalink($post_id).'" target="_blank">'.$quizpost->post_title.'</a></td>';
      echo '<td class="text-capitalize">'.$quizpost->post_status.'</td>';
      echo '<td class="text-center px-0">';
        echo '<a href="?hal=edit&id='.$post_id.'" class="btn btn-sm btn-success m-1" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
        </svg></a>';
        echo '<div id="'.$post_id.'" class="btn btn-sm btn-danger m-1 hapus-quiz" title="Hapus"><span class="h-'.$post_id.'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
        </svg></span></div>';
      echo '</td>';
    echo '</tr>';
  }
  echo '</tbody></table></div>';
  echo'
  <script>
  jQuery(document).ready(function($){
    $(document).on("click",".hapus-quiz",function(e){
      if (confirm("Apakah anda yakin ingin menghapus quiz ini?")) {
        var get_id = $(this).attr("id");
        $(".h-"+get_id).html("");
        $(".h-"+get_id).addClass("spinner-grow spinner-grow-sm");
            $.ajax({  
            type: "POST",  
            data: "action=hapuspost&id=" + get_id, 
            url: "'.admin_url('admin-ajax.php').'",
            success:function(data) {
              $(".quiz-" + get_id).remove();
            }
        });
      }
    });
  });
  </script>';
}
?>


</div>