<?php
$post_title         = isset($_POST['post_title']) ? $_POST['post_title'] : '';
$post_content       = isset($_POST['post_content']) ? $_POST['post_content'] : '';
$post_categories    = isset($_POST['quizcat']) ? $_POST['quizcat'] : '';
$quiz               = isset($_POST['quiz']) ? $_POST['quiz'] : '';
$status             = isset($_POST['status']) ? $_POST['status'] : '';
$waktu              = isset($_POST['waktu']) ? $_POST['waktu'] : '';
$kunci              = isset($_POST['kunci']) ? $_POST['kunci'] : '';

// $all_meta_values = get_post_meta(52,'quiz',false);
// echo '<pre>'.print_r($all_meta_values,1).'</pre>';

if ($quiz) {
  // Loop melalui sub-array 'tanya'
  foreach ($quiz['tanya'] as $key => $value) {
    // Membuat array baru dengan struktur yang diinginkan
    $new_quiz_array[] = array(
        'tanya' => $value,
        'a' => $quiz['a'][$key],
        'b' => $quiz['b'][$key],
        'c' => $quiz['c'][$key],
        'd' => $quiz['d'][$key],
        'jawaban' => $quiz['jawaban'][$key],
        'pembahasan' => $quiz['pembahasan'][$key]
    );
  }
  // Mengganti sub-array 'quiz' dengan array yang baru dibuat
  $quiz = $new_quiz_array;
}


if ($post_title) {
  $quiz_post = array(
    'post_title'    => $post_title,
    'post_content'  => $post_content,
    'post_status'   => $status,
    'post_type'     => 'velocity-quiz',
  );
  $pid = wp_insert_post($quiz_post,true);
  if(!is_wp_error($pid)) {
    if($post_categories){
      wp_set_object_terms($pid,$post_categories,'quiz-category');
    }
    update_post_meta($pid,'waktu',$waktu);
    update_post_meta($pid,'kunci',$kunci);
    if($quiz){
      update_post_meta($pid,'quiz',$quiz);      
    }
    echo '<div class="alert alert-success">Quiz berhasil disimpan.</div>';
    echo '<script>window.setTimeout(function(){
        window.location.href = "'.get_permalink().'?hal=edit&id='.$pid.'";
    }, 10);</script>';
  } else {
    echo '<div class="alert alert-danger">Quiz gagal disimpan.</div>';
  }
}
?>

<form method="post" enctype="multipart/form-data">
  <div class="velocity-field">

    <div class="border p-3 mb-3">
      <h5 class="vd-field-title mt-0">Judul Quiz</h5>
      <input type="text" class="form-control" name="post_title" required>

      <h5 class="vd-field-title">Keterangan</h5>
      <?php wp_editor('','post_content'); ?>

      <?php 
      $tax_args = array(
        'taxonomy'   => 'quiz-category',
        'hide_empty' => false,
      );
      $terms = get_terms($tax_args); ?>
      <h5 class="vd-field-title">Kategori</h5>
      <select class="form-select" name="quizcat">
        <option value="">Pilih Kategori</option>
        <?php foreach ($terms as $term) {
          echo '<option value="' . $term->slug . '">' . $term->name . '</option>';
        } ?>
      </select>

      <h5 class="vd-field-title">Waktu Pengerjaan</h5>
      <input type="number" class="form-control" name="waktu">
      <small class="text-muted">Dalam menit. Kosongkan jika tanpa batas waktu pengerjaan.</small>

      <h5 class="vd-field-title">Tampilkan Kunci Jawaban</h5>
      <select class="form-select" name="kunci">
        <option value="Ya">Ya</option>
        <option value="Tidak">Tidak</option>
      </select>
      <small class="text-muted">Jika aktif, kunci jawaban beserta pembahasannya akan langsung tampil.</small>

    </div>


    <?php $pilihan_jawaban = '<h5 class="vd-field-title">Pilihan Jawaban</h5>';
        $pilihan_jawaban .= '<div class="input-group mb-2">';
          $pilihan_jawaban .= '<div class="input-group-prepend"><div class="input-group-text">A</div></div>';
          $pilihan_jawaban .= '<input type="text" class="form-control" name="quiz[a][]" value="" required="">';
        $pilihan_jawaban .= '</div>';
        $pilihan_jawaban .= '<div class="input-group mb-2">';
          $pilihan_jawaban .= '<div class="input-group-prepend"><div class="input-group-text">B</div></div>';
          $pilihan_jawaban .= '<input type="text" class="form-control" name="quiz[b][]" value="" required="">';
        $pilihan_jawaban .= '</div>';
        $pilihan_jawaban .= '<div class="input-group mb-2">';
          $pilihan_jawaban .= '<div class="input-group-prepend"><div class="input-group-text">C</div></div>';
          $pilihan_jawaban .= '<input type="text" class="form-control" name="quiz[c][]" value="" required="">';
        $pilihan_jawaban .= '</div>';
        $pilihan_jawaban .= '<div class="input-group mb-2">';
          $pilihan_jawaban .= '<div class="input-group-prepend"><div class="input-group-text">D</div></div>';
          $pilihan_jawaban .= '<input type="text" class="form-control" name="quiz[d][]" value="" required="">';
        $pilihan_jawaban .= '</div>';
        ?>

      <?php $jawaban = '<div class="form-group">';
          $jawaban .= '<h5 class="vd-field-title">Jawaban Benar</h5>';
          $jawaban .= '<select class="form-control" name="quiz[jawaban][]" required="">';
            $jawaban .= '<option value="a">A</option>';
            $jawaban .= '<option value="b">B</option>';
            $jawaban .= '<option value="c">C</option>';
            $jawaban .= '<option value="d">D</option>';
          $jawaban .= '</select>';
          $jawaban .= '</div>';
        ?>
  </div>
  <h5 class="vd-field-title mt-3">Pilih Status</h5>
  <select class="form-select mb-4" name="status" required="">
    <option value="publish">Publish</option>
    <option value="draft">Draft</option>
  </select>

  <div id="tambah" class="btn btn-info text-white">Tambah Soal</div>
  <button type="submit" class="btn btn-success">Simpan</button>
</form>

<?php echo wp_enqueue_editor(); ?>
<?php echo wp_enqueue_media(); ?>

<div id="jumlah-soal">0</div>

<script>
function hapus(id) {
  if (confirm('Hapus ini?')){
    document.getElementById(id).remove();
        
    // Get the count of elements with class 'velocity-form-control'
    var count = document.getElementsByClassName('velocity-form-control').length;
        
    // Set the HTML content of 'jumlah-soal' element
    document.getElementById('jumlah-soal').innerHTML = count;
  }
}


jQuery(function($) {
  var i = 1;  
  $("#tambah").click(function(){
    i++;
    var ket = '<small>Shortcode audio: <b>[audio mp3="https://example.com/audio.mp3"][/audio]</b><br>Shortcode youtube: <b>[velocity-youtube link="https://www.youtube.com/watch?v=poC51BAa4bg"]</b></small>';
    var function_hapus = "hapus('velocity-field-"+i+"');";
    var awal = '<div class="velocity-form-control" id="velocity-field-'+i+'">';
    var close = '<div class="vd-hapus" onClick="'+function_hapus+'">x</div>';
    var ask = '<h5 class="vd-field-title">Soal</h5><textarea class="form-control" id="ask'+i+'" name="quiz[tanya][]"></textarea>'+ket;
    var pj = '<?php echo $pilihan_jawaban;?>';
    var jwb = '<?php echo $jawaban;?>';
    var pmb = '<h5 class="vd-field-title">Pembahasan</h5><textarea class="form-control" name="quiz[pembahasan][]"></textarea>';
    var akhir = '</div>';
    $(".velocity-field").append(awal+close+ask+pj+jwb+pmb+akhir);
    wp.editor.initialize(
      'ask'+i,
      {
        tinymce: {
          wpautop: true,
          plugins : 'charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview',
          toolbar1: 'bold italic underline strikethrough | bullist numlist | blockquote hr wp_more | alignleft aligncenter alignright | link unlink | fullscreen | wp_adv',
          toolbar2: 'formatselect alignjustify forecolor | pastetext removeformat charmap | outdent indent | undo redo | wp_help'
        },
        quicktags: true,
        mediaButtons: true,
      }
    );

    // menghitung jumlah soal
    var count = $(".velocity-form-control").length;
    $("#jumlah-soal").html(count);

  });
  
});
</script>