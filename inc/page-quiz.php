<div class="container">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-3">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><?php echo get_bloginfo('name');?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">MENU</a>
          <ul class="dropdown-menu dropdown-menu-primary" aria-labelledby="navbarDarkDropdownMenuLink">
            <li><a class="dropdown-item" href="<?php get_the_permalink();?>?">Halaman Depan Quiz</a></li>
            <li><a class="dropdown-item" href="<?php get_the_permalink();?>?hal=tambah">Tambah Quiz</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<?php
$halaman = isset($_GET['hal']) ? $_GET['hal'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

if($halaman == 'tambah'){
  include(VELOCITY_QUIZ_DIR.'/inc/quiz-tambah.php');
} else if($halaman == 'edit' && $id){
  include(VELOCITY_QUIZ_DIR.'/inc/quiz-edit.php');
} else {
  $quiz_args = array(
    'showposts' => -1,
    'post_type' => array('velocity-quiz'),
  ); 
  $quizposts = get_posts($quiz_args);
  echo '<div class="mb-3">';
    echo '<a class="btn btn-primary btn-sm" href="?hal=tambah">Tambah +</a>';
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
      echo '<td>'.$quizpost->post_title.'</td>';
      echo '<td class="text-capitalize">'.get_post_status($post_id).'</td>';
      echo '<td class="text-center px-0">';
        echo '<a href="'.get_the_permalink($post_id).'" class="btn btn-sm btn-primary m-1" title="Lihat" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
        </svg></a>';
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
            data: "action=hapusquiz&id=" + get_id, 
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