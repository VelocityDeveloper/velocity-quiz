<?php


$essay_args = array(
    'showposts' => -1,
    'post_type' => array('velocity-essay'),
  ); 
  $essayposts = get_posts($essay_args);
  echo '<div class="mb-3">';
    echo '<a class="btn btn-primary btn-sm" href="?hal=essay&act=tambah">Tambah Baru +</a>';
  echo '</div>';
  echo '<div class="table-responsive">
  <table class="table table-bordered">
     <thead>
    <tr>
      <th scope="col">Tanggal</th>
      <th scope="col">Nama Essay</th>
      <th scope="col">Status</th>
      <th scope="col" class="text-center">Aksi</th>
    </tr>
  </thead>
  <tbody>';
  foreach ($essayposts as $essaypost) {
    $post_id = $essaypost->ID;
    $post_date = get_the_date('Y-m-d H:i', $post_id);
    echo '<tr class="essay-'.$post_id.'">';
      echo '<td>'.$post_date.'</td>';
      echo '<td><a href="'.get_the_permalink($post_id).'" target="_blank">'.$essaypost->post_title.'</a></td>';
      echo '<td class="text-capitalize">'.$essaypost->post_status.'</td>';
      echo '<td class="text-center px-0">';
        echo '<a href="?hal=essay&act=jawaban&id='.$post_id.'" class="btn btn-sm btn-primary m-1" title="Jawaban"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
        </svg></a>';
        echo '<a href="?hal=essay&act=edit&id='.$post_id.'" class="btn btn-sm btn-success m-1" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
        </svg></a>';
        echo '<div id="'.$post_id.'" class="btn btn-sm btn-danger m-1 hapus-essay" title="Hapus"><span class="h-'.$post_id.'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
        </svg></span></div>';
      echo '</td>';
    echo '</tr>';
  }
  echo '</tbody></table></div>';
  echo'
  <script>
  jQuery(document).ready(function($){
    $(document).on("click",".hapus-essay",function(e){
      if (confirm("Apakah anda yakin ingin menghapus essay ini?")) {
        var get_id = $(this).attr("id");
        $(".h-"+get_id).html("");
        $(".h-"+get_id).addClass("spinner-grow spinner-grow-sm");
            $.ajax({  
            type: "POST",  
            data: "action=hapuspost&id=" + get_id, 
            url: "'.admin_url('admin-ajax.php').'",
            success:function(data) {
              $(".essay-" + get_id).remove();
            }
        });
      }
    });
  });
  </script>';