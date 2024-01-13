<?php
$post_id = isset($_GET['id']) ? $_GET['id'] : '';
$post_title         = isset($_POST['post_title']) ? $_POST['post_title'] : get_the_title($post_id);
$post_content       = isset($_POST['post_content']) ? $_POST['post_content'] : get_post_field('post_content', $post_id);
$quiz_cat = wp_get_post_terms($post_id,'quiz-category');
$quiz_cat_slug = $quiz_cat?$quiz_cat[0]->slug:'';
$post_cat_slug    = isset($_POST['quizcat']) ? $_POST['quizcat'] : $quiz_cat_slug;
$quiz               = isset($_POST['quiz']) ? $_POST['quiz'] : get_post_meta($post_id,'quiz',true);
$status             = isset($_POST['status']) ? $_POST['status'] : get_post_status($post_id);
$waktu              = isset($_POST['waktu']) ? $_POST['waktu'] : get_post_meta($post_id,'waktu',true);
$tampil_nilai       = isset($_POST['tampil_nilai']) ? $_POST['tampil_nilai'] : get_post_meta($post_id,'tampil_nilai',true);


// echo '<pre>'.print_r($quiz,1).'</pre>';

$post_type = get_post_type($post_id);
if ($post_type == 'velocity-quiz') {

if (isset($_POST['quiz'])) {
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

if (isset($_POST['post_title'])) {
    $actual_link    = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    // Ambil data posting
    $post_data = array(
        'ID'         => $post_id,
        'post_title' => $post_title,
        'post_content' => $post_content,
    );

    // Perbarui posting
    $result = wp_update_post($post_data);

    // Set custom taxonomy
    if (!is_wp_error($result) && $post_cat_slug) {
        wp_set_post_terms($post_id, $post_cat_slug, 'quiz-category');
    }

    if ($result !== 0) {
        // Perbarui atau tambahkan post meta
        update_post_meta($post_id,'waktu',$waktu);
        update_post_meta($post_id,'tampil_nilai',$tampil_nilai); 
        $editquiz = isset($_POST['quiz']) ? $quiz : '';       
        update_post_meta($post_id,'quiz',$editquiz);
        echo '<div class="alert alert-success">Quiz berhasil diperbarui.</div>';
        echo '<script>window.setTimeout(function(){
            window.location.href = "'.$actual_link.'";
        }, 1000);</script>';
    } else {
        echo '<div class="alert alert-danger">Quiz gagal diperbarui.</div>';
    }
} ?>


<div class="row mb-3">
  <div class="col-6 pe-0">
      <a class="btn btn-primary btn-sm" href="?hal=tambah">Tambah Baru +</a>
  </div>
  <div class="col-6 ps-0 text-end">
      <a class="btn btn-info btn-sm" href="<?php echo get_the_permalink($post_id);?>" target="_blank">Lihat Quiz</a>
  </div>
</div>

<form method="post" enctype="multipart/form-data">
  <div class="velocity-field">

    <div class="border p-3 mb-3">
      <h5 class="vd-field-title mt-0">Judul Quiz</h5>
      <input type="text" class="form-control" name="post_title" value="<?php echo $post_title; ?>" required>

      <h5 class="vd-field-title">Keterangan</h5>
      <?php wp_editor($post_content,'post_content'); ?>

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
            $selected = $post_cat_slug == $term->slug ? ' selected="selected"':'';
            echo '<option value="' . $term->slug . '"'.$selected.'>' . $term->name . '</option>';
        } ?>
      </select>

      <h5 class="vd-field-title">Waktu Pengerjaan</h5>
      <input type="number" class="form-control" name="waktu" value="<?php echo $waktu; ?>">
      <small class="text-muted">Dalam menit. Kosongkan jika tanpa batas waktu pengerjaan.</small>

      <h5 class="vd-field-title">Tampil Nilai</h5>
      <select class="form-select" name="tampil_nilai">
        <option value="Ya"<?php echo $tampil_nilai == 'Ya' ? ' selected="selected"':'';?>>Ya</option>
        <option value="Tidak"<?php echo $tampil_nilai == 'Tidak' ? ' selected="selected"':'';?>>Tidak</option>
      </select>
      <small class="text-muted">Jika aktif, hasil nilai dan kunci jawaban beserta pembahasannya akan langsung tampil.</small>
      
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

        $jawaban = '<div class="form-group">';
        $jawaban .= '<h5 class="vd-field-title">Jawaban Benar</h5>';
        $jawaban .= '<select class="form-control" name="quiz[jawaban][]" required="">';
          $jawaban .= '<option value="a">A</option>';
          $jawaban .= '<option value="b">B</option>';
          $jawaban .= '<option value="c">C</option>';
          $jawaban .= '<option value="d">D</option>';
        $jawaban .= '</select>';
        $jawaban .= '</div>';

$ket = '<small>Shortcode audio: <b>[audio mp3="https://example.com/audio.mp3"][/audio]</b><br>Shortcode youtube: <b>[velocity-youtube link="https://www.youtube.com/watch?v=poC51BAa4bg"]</b></small>';
$no = 0;
if($quiz) {
    $i = 1;
    foreach ($quiz as $data) {
        $no = $i++; ?>
        <div class="velocity-form-control" id="velocity-field-<?php echo $no;?>">
            <div class="vd-hapus" onClick="hapus('velocity-field-<?php echo $no;?>')">x</div>
            <h5 class="vd-field-title mt-0">Soal</h5>
            <textarea class="tanya-awal form-control" id="ask-<?php echo $no;?>" name="quiz[tanya][]"><?php echo $data['tanya'];?></textarea>
            <?php echo $ket;
        $array_pl = ['a'=>'A','b'=>'B','c'=>'C','d'=>'D'];

        echo '<h5 class="vd-field-title">Pilihan Jawaban</h5>';
        foreach($array_pl as $key => $value){
            echo '<div class="input-group mb-2">';
                echo '<div class="input-group-prepend"><div class="input-group-text text-uppercase">'.$key.'</div></div>';
                echo '<input type="text" class="form-control" name="quiz['.$key.'][]" value="'.$data[$key].'" required="">';
            echo '</div>';
        }

        echo '<div class="form-group">';
        echo '<h5 class="vd-field-title">Jawaban Benar</h5>';
        echo '<select class="form-control" name="quiz[jawaban][]" required="">';
            foreach($array_pl as $key => $value){
                $selected = $data['jawaban'] == $key ? ' selected' : '';
                echo '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
            }
            echo '</select>';
        echo '</div>'; ?>

      <h5 class="vd-field-title">Pembahasan</h5>
      <textarea class="form-control" name="quiz[pembahasan][]"><?php echo isset($data['pembahasan']) ? $data['pembahasan'] : ''; ?></textarea>

        </div>
    <?php }
    $jumlah_quiz = count($quiz);
} else { // jika quiz kosong 
    $no = 1;
    $jumlah_quiz = 1;
} ?>

  </div>
  <h5 class="vd-field-title mt-3">Pilih Status</h5>
  <select class="form-select mb-4" name="status" required="">
    <option value="publish"<?php echo $status == 'publish' ? ' selected="selected"':'';?>>Publish</option>
    <option value="draft"<?php echo $status == 'draft' ? ' selected="selected"':'';?>>Draft</option>
  </select>

  <div id="tambah" class="btn btn-info text-white">Tambah Soal</div>
  <button type="submit" class="btn btn-success">Simpan</button>
  <div class="btn btn-danger hapus-quiz"><span class="vdhapus"></span>Hapus</div>
</form>

<?php echo wp_enqueue_editor(); ?>
<?php echo wp_enqueue_media(); ?>

<div id="jumlah-soal"><?php echo $jumlah_quiz;?></div>


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

    <?php for ($x = 0; $x <= $jumlah_quiz; $x++) { ?>
    wp.editor.initialize(
      'ask-<?php echo $x; ?>',
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
    <?php } ?>

    var i = <?php echo $no; ?>;  
    $("#tambah").click(function(){
        i++;
        var function_hapus = "hapus('velocity-field-"+i+"');";
        var awal = '<div class="velocity-form-control" id="velocity-field-'+i+'">';
        var close = '<div class="vd-hapus" onClick="'+function_hapus+'">x</div>';
        var ask = '<h5 class="vd-field-title">Soal</h5><textarea class="form-control" id="ask'+i+'" name="quiz[tanya][]"></textarea><?php echo $ket;?>';
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

    $(".hapus-quiz").click(function(){
      if (confirm("Apakah anda yakin ingin menghapus quiz ini?")) {
            $(".vdhapus").addClass("spinner-grow spinner-grow-sm me-2");
            $.ajax({  
                type: "POST",  
                data: "action=hapuspost&id=" + <?php echo $post_id; ?>, 
                url: "<?php echo admin_url('admin-ajax.php');?>",
                success:function(data) {
                    alert('Data berhasil dihapus.');
                    location.href = '<?php echo get_the_permalink(); ?>';
                }
            });
      }
    });
  
});
</script>

<?php } ?>