<div class="container">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
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
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>


<?php if (isset($_POST['imgsoal'])) {
  echo '<pre>'.print_r($_POST,1).'</pre>';
} ?>

<form method="post">
  <div class="velocity-field py-3">
    <div class="velocity-form-control">
      <h5>Gambar soal</h5>
          <div class="border p-2 rounded mb-4"><a style="cursor:pointer" class="btn btn-secondary btn-sm text-white" onClick="open_media_uploader_image('imagesoal');">Pilih Gambar</a>
          <div id="imagesoal"></div>
          <input type="hidden" name="imgsoal[]" id="idimagesoal" value="" readonly/>
        </div>

      <h5>Pertanyaan</h5>
        <textarea class="form-control" id="ask" name="tanya[]"></textarea>

    <?php $pilihan_jawaban = '<h5 class="mt-4">Pilihan Jawaban</h5>';
        $pilihan_jawaban .= '<div class="input-group mb-2">';
          $pilihan_jawaban .= '<div class="input-group-prepend"><div class="input-group-text">A</div></div>';
          $pilihan_jawaban .= '<input type="text" class="form-control" name="a[]" value="" required="">';
        $pilihan_jawaban .= '</div>';
        $pilihan_jawaban .= '<div class="input-group mb-2">';
          $pilihan_jawaban .= '<div class="input-group-prepend"><div class="input-group-text">B</div></div>';
          $pilihan_jawaban .= '<input type="text" class="form-control" name="b[]" value="" required="">';
        $pilihan_jawaban .= '</div>';
        $pilihan_jawaban .= '<div class="input-group mb-2">';
          $pilihan_jawaban .= '<div class="input-group-prepend"><div class="input-group-text">C</div></div>';
          $pilihan_jawaban .= '<input type="text" class="form-control" name="c[]" value="" required="">';
        $pilihan_jawaban .= '</div>';
        $pilihan_jawaban .= '<div class="input-group mb-2">';
          $pilihan_jawaban .= '<div class="input-group-prepend"><div class="input-group-text">D</div></div>';
          $pilihan_jawaban .= '<input type="text" class="form-control" name="d[]" value="" required="">';
        $pilihan_jawaban .= '</div>';
        echo $pilihan_jawaban;
        ?>

      <?php $jawaban = '<div class="form-group">';
          $jawaban .= '<h5 class="mt-4">Jawaban Benar</h5>';
          $jawaban .= '<select class="form-control" name="jawaban[]" required="">';
            $jawaban .= '<option value="a">A</option>';
            $jawaban .= '<option value="b">B</option>';
            $jawaban .= '<option value="c">C</option>';
            $jawaban .= '<option value="d">D</option>';
          $jawaban .= '</select>';
          $jawaban .= '</div>';
        echo $jawaban;
        ?>
    </div>
  </div>
  <button type="button" id="tambah" class="btn btn-sm btn-info text-white">Tambah Soal</button>
  <button type="submit" class="btn btn-sm btn-success">Simpan</button>
</form>

<?php echo wp_enqueue_editor(); ?>
<?php echo wp_enqueue_media(); ?>

<script>
  var media_uploader = null;
  function open_media_uploader_image(location_id) {
      media_uploader = wp.media({
          frame: "post",
          state: "insert",
          library: {
            type: ['image']
          },
          multiple: false
      });
      media_uploader.on("insert", function(){
        var json = media_uploader.state().get("selection").first().toJSON();
        var image_url = json.url;
        //var image_caption = json.caption;
        //var image_title = json.title;
        var image_id = json.id;
        document.getElementById(location_id).innerHTML = '<span class="hapusimgsoal"><i class="fa fa-times"></i></span><img src="'+image_url+'" class="m-1">';
        document.getElementById('id'+location_id).value = image_id;
      });
      media_uploader.open();
  }
	jQuery(document).ready(function($){
		$(document).on("click",".hapusimgsoal",function(e){
      $(this).parent().html('');
      var id = $(this).parent().prop('className');
      $('#id'+id).val('');
		});
	});

  function hapus(id) {
    if (confirm('Hapus ini?')){
      document.getElementById(id).remove();
    }
  }


jQuery(function($) {
  $.each( $('#ask'), function( i, editor ) {
    wp.editor.initialize(
      'ask',
      {
        tinymce: {
          wpautop: true,
          plugins : 'charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview',
          toolbar1: 'bold italic underline strikethrough | bullist numlist | blockquote hr wp_more | alignleft aligncenter alignright | link unlink | fullscreen | wp_adv',
          toolbar2: 'formatselect alignjustify forecolor | pastetext removeformat charmap | outdent indent | undo redo | wp_help'
        },
        quicktags: true,
        //mediaButtons: true,
      }
    );
  });

  var i = 1;  
  $("#tambah").click(function(){
    i++;
    var function_hapus = "hapus('velocity-field-"+i+"');";
    var function_media = "open_media_uploader_image('imagesoal"+i+"');";
    var awal = '<div class="velocity-form-control" id="velocity-field-'+i+'">';
    var close = '<div class="vd-hapus" onClick="'+function_hapus+'">x</div>';
    var gambar = '<h5>Gambar soal</h5><div class="border p-2 rounded mb-4"><a style="cursor:pointer" class="btn btn-secondary btn-sm text-white" onClick="'+function_media+'">Pilih Gambar</a></div><div id="imagesoal'+i+'"></div><input type="hidden" name="imgsoal[]" id="idimagesoal'+i+'" value="" readonly/>';
    var ask = '<h5>Pertanyaan</h5><textarea class="form-control" id="ask'+i+'" name="tanya[]"></textarea>';
    var pj = '<?php echo $pilihan_jawaban;?>';
    var jwb = '<?php echo $jawaban;?>';
    var akhir = '</div>';
    $(".velocity-field").append(awal+close+gambar+ask+pj+jwb+akhir);
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
      }
    );
  });
  
});
</script>


</div>