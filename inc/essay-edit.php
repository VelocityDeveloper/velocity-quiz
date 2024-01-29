
<?php
$post_id = isset($_GET['id']) ? $_GET['id'] : '';
$post_title         = isset($_POST['post_title']) ? $_POST['post_title'] : get_the_title($post_id);
$post_content       = isset($_POST['post_content']) ? $_POST['post_content'] : get_post_field('post_content', $post_id);
$essay_cat = wp_get_post_terms($post_id,'quiz-category');
$essay_cat_slug = $essay_cat?$essay_cat[0]->slug:'';
$post_cat_slug    = isset($_POST['quizcat']) ? $_POST['quizcat'] : $essay_cat_slug;
$essay               = isset($_POST['essay']) ? $_POST['essay'] : get_post_meta($post_id,'essay',true);
$status             = isset($_POST['status']) ? $_POST['status'] : get_post_status($post_id);
$waktu              = isset($_POST['waktu']) ? $_POST['waktu'] : get_post_meta($post_id,'waktu',true);
$kunci              = isset($_POST['kunci']) ? $_POST['kunci'] : get_post_meta($post_id,'kunci',true);

//echo '<pre>'.print_r($essay,1).'</pre>'; 

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
        update_post_meta($pid,'kunci',$kunci);
        $editessay = isset($_POST['essay']) ? $_POST['essay'] : '';
        update_post_meta($post_id,'essay',$editessay);
        echo '<div class="alert alert-success">Essay berhasil diperbarui.</div>';
        echo '<script>window.setTimeout(function(){
            window.location.href = "'.$actual_link.'";
        }, 1500);</script>';
    } else {
        echo '<div class="alert alert-danger">Essay gagal diperbarui.</div>';
    }
}
?>

<div class="row mb-3">
  <div class="col-6 pe-0">
      <a class="btn btn-primary btn-sm" href="?hal=essay&act=tambah">Tambah Baru +</a>
  </div>
  <div class="col-6 ps-0 text-end">
      <a class="btn btn-info btn-sm" href="<?php echo get_the_permalink($post_id);?>" target="_blank">Lihat Essay</a>
  </div>
</div>

<form method="post" enctype="multipart/form-data">
  <div class="velocity-field">

    <div class="border p-3 mb-3">
      <h5 class="vd-field-title mt-0">Judul Essay</h5>
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

      <h5 class="vd-field-title">Tampilkan Kunci Jawaban</h5>
      <select class="form-select" name="kunci">
        <option value="Ya"<?php echo $kunci == 'Ya' ? ' selected="selected"':'';?>>Ya</option>
        <option value="Tidak"<?php echo $kunci == 'Tidak' ? ' selected="selected"':'';?>>Tidak</option>
      </select>
      <small class="text-muted">Jika aktif, kunci jawaban beserta pembahasannya akan ditampilkan.</small>
      
    </div>

<?php $ket = '<small>Shortcode audio: <b>[audio mp3="https://example.com/audio.mp3"][/audio]</b><br>Shortcode youtube: <b>[velocity-youtube link="https://www.youtube.com/watch?v=poC51BAa4bg"]</b></small>';
$no = 0;
if($essay) {
    $i = 1;
    foreach ($essay['tanya'] as $tanya) {
        $no = $i++;
        $urutan = $no - 1; ?>
        <div class="velocity-form-control" id="velocity-field-<?php echo $no;?>">
            <div class="vd-hapus" onClick="hapus('velocity-field-<?php echo $no;?>')">x</div>
            <h5 class="vd-field-title mt-0">Soal</h5>
            <textarea class="tanya-awal form-control" id="ask-<?php echo $no;?>" name="essay[tanya][]"><?php echo $tanya;?></textarea>
            <?php echo $ket;?>
            <h5 class="vd-field-title">Pembahasan</h5><textarea class="form-control" name="essay[pembahasan][]"><?php echo $essay['pembahasan'][$urutan];?></textarea>
        </div>
    <?php }
    $jumlah_essay = count($essay['tanya']);
} else { // jika quiz kosong 
    $no = 1;
    $jumlah_essay = 0;
} ?>

  </div>
  <h5 class="vd-field-title mt-3">Pilih Status</h5>
  <select class="form-select mb-4" name="status" required="">
    <option value="publish"<?php echo $status == 'publish' ? ' selected="selected"':'';?>>Publish</option>
    <option value="draft"<?php echo $status == 'draft' ? ' selected="selected"':'';?>>Draft</option>
  </select>
  
  <div class="alert alert-warning py-2">
    <strong>Peringatan:</strong> Jangan mengubah soal ketika sudah ada yang mengerjakan
  </div>

  <div id="tambah" class="btn btn-info text-white">Tambah Soal</div>
  <button type="submit" class="btn btn-success">Simpan</button>
  <div class="btn btn-danger hapus-quiz"><span class="vdhapus"></span>Hapus</div>
</form>

<?php echo wp_enqueue_editor(); ?>
<?php echo wp_enqueue_media(); ?>

<div id="jumlah-soal"><?php echo $jumlah_essay;?></div>


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

    <?php if($essay) { ?>
    <?php for ($x = 0; $x <= $jumlah_essay; $x++) { ?>
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
    <?php } ?>

    var i = <?php echo $no; ?>;  
    $("#tambah").click(function(){
        i++;
        var function_hapus = "hapus('velocity-field-"+i+"');";
        var awal = '<div class="velocity-form-control" id="velocity-field-'+i+'">';
        var close = '<div class="vd-hapus" onClick="'+function_hapus+'">x</div>';
        var ask = '<h5 class="vd-field-title">Soal</h5><textarea class="form-control" id="ask'+i+'" name="essay[tanya][]"></textarea><?php echo $ket;?>';
        var pmb = '<h5 class="vd-field-title">Pembahasan</h5><textarea class="form-control" name="essay[pembahasan][]"></textarea>';
        var akhir = '</div>';
        $(".velocity-field").append(awal+close+ask+pmb+akhir);
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