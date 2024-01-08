<?php
global $wpdb;
$table_name = $wpdb->prefix . "velocity_quiz";
$post_id = isset($_GET['id']) ? $_GET['id'] : '';
$essaydikerjakan = $wpdb->get_results("SELECT * FROM $table_name WHERE post_id = $post_id");


echo '<div class="row mb-3">';
  echo '<div class="col-9 col-md-10">';
    echo '<h3 class="fs-5 fw-normal">'.get_the_title($post_id).'</h3>';
  echo '</div>';
  echo '<div class="col-3 col-md-2 text-sm-end">';
      echo '<a class="btn btn-primary btn-sm" href="?hal=essay">Kembali</a>';
  echo '</div>';
echo '</div>';

echo '<div class="table-responsive">
  <table class="table">
     <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">Nama</th>
      <th scope="col">Dikerjakan</th>
      <th scope="col">Nilai</th>
      <th scope="col" class="text-center">Tindakan</th>
    </tr>
  </thead>
  <tbody>';
  $i = 1;
  $essay_soal = get_post_meta($post_id,'essay',true);
  foreach ($essaydikerjakan as $essay) {
    $no = $i++;
    $id = $essay->vq_id;
    $user_id = $essay->user_id; // Ganti dengan ID pengguna yang sesuai
    $user_name = get_the_author_meta('display_name', $user_id);
    $nilai = $essay->nilai ? '<span class="text-success fw-bold">'.$essay->nilai.'</span>' : '-';
    $jawaban = json_decode($essay->vq_detail);
    $datapoin = $essay->vq_result ? json_decode($essay->vq_result,true) : '';
    echo '<tr class="essay-'.$id.'">';
      echo '<td class="align-middle">'.$no.'</td>';
      echo '<td class="align-middle">'.$user_name.'</td>';
      echo '<td class="align-middle">'.$essay->vq_date.'</td>';
      echo '<td class="align-middle nilai-'.$id.'">'.$nilai.'</td>';
      echo '<td class="align-middle text-center px-0">';
        echo '<div data-bs-toggle="modal" data-bs-target="#modal-'.$id.'" class="btn btn-sm btn-success m-1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
        </svg></div>';
        echo '<div id="'.$id.'" class="btn btn-sm btn-danger m-1 hapus-quiz" title="Hapus"><span class="h-'.$id.'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
        </svg></span></div>';

        echo '<div class="modal fade" id="modal-'.$id.'" tabindex="-1" aria-labelledby="modal-'.$id.'-Label" aria-hidden="true">';
          echo '<div class="modal-dialog modal-lg modal-dialog-scrollable">';
            echo '<form class="modal-content form-essay" id="'.$id.'">';
              echo '<div class="modal-header">';
                echo '<h5 class="modal-title">Berikan Nilai - <span class="text-success">'.$user_name.'</span></h5>';
                echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
              echo '</div>';
              echo '<div class="modal-body text-start">';
                // echo '<pre class="text-start">'.print_r($essay_soal,1).'</pre>';
                // echo '<pre class="text-start">'.print_r($jawaban,1).'</pre>';
                echo '<input type="hidden" name="post_id" value="'.$post_id.'">';
                echo '<input type="hidden" name="user_id" value="'.$user_id.'">';
                $nomor=1;
                foreach($essay_soal as $skey => $svalue){
                  $nm = $nomor++;
                  echo '<div class="card-jawaban list-group-item px-0 mb-3">';
                    echo '<div class="card shadow-sm">';
                      echo '<div class="card-header">';
                        echo '<span> Soal no. <strong>'.$nm.'</strong> </span>';
                      echo '</div>';
                    echo '<div class="card-body">';        
                      echo '<div class="soal">';
                        echo '<label for="soal">Soal</label>';
                        echo '<div class="card-col-soal border rounded p-3 mb-3">';
                          echo $svalue;
                          echo '<input type="hidden" name="idsoal" value="'.$skey.'">';
                        echo '</div>';
                      echo '</div>  ';
                      echo '<div class="form-group">';
                        echo '<label for="jawab">Jawaban</label>';      
                        echo '<div class="card-col-jawab border border-dark rounded p-3 mb-3">';
                          echo $jawaban[$skey];
                        echo '</div>';
                      echo '</div> ';
                      echo '<div class="form-group">';
                        echo '<label for="jawab">Nilai</label>';
                        $dp = $datapoin?$datapoin[$skey]:'';
                        echo '<input type="number" value="'.$dp.'" name="hasil['.$skey.']" class="form-nilai form-control" min="0" max="5" required>';
                        echo '<small class="form-text text-muted">Beri nilai poin 0 sampai 5</small>';
                      echo '</div>';
                    echo '</div>';
                echo '</div>';
                echo '</div>';
                }
              echo '</div>';
              echo '<div class="modal-footer">';
                echo '<div class="btn btn-secondary" data-bs-dismiss="modal">Tutup</div>';
                echo '<button type="submit" class="btn btn-primary btn-'.$id.'">Simpan</button>';
              echo '</div>';
            echo '</form>';
          echo '</div>';
        echo '</div>';
      echo '</td>';
    echo '</tr>';
  }
echo '</tbody></table></div>';

$loading = '<div class="spinner-grow spinner-grow-sm text-light"><span class="visually-hidden">Loading...</span></div>';
echo "<script>
jQuery(function($) {
  $('.form-essay').each(function () {
    $(this).on('submit', function (e) {
      e.preventDefault();
      var form_id = $(this).attr('id');
      var datas = $(this).serialize();
      $('.btn-'+form_id).html('Simpan ".$loading."');
      $.ajax({
        type: 'POST',
        data: 'action=nilaiessay&id='+form_id+'&'+datas,
        url: '".admin_url('admin-ajax.php')."',
        success:function(data) {
          setTimeout(function () {
            $('#modal-'+form_id).modal('hide');
            $('.nilai-'+form_id).html(data);
            $('.btn-'+form_id).html('Simpan');
          }, 1000);
        }
      });
    });
  });
});
</script>";
