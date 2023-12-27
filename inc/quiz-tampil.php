<?php get_header(); ?>

<div class="container">
<?php while (have_posts()) : the_post(); ?>
<?php $post_id = get_the_ID(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <h1><?php the_title(); ?></h1>
        <div class="entry-content">
            <?php the_content(); ?>
            <?php $waktu = get_post_meta($post_id,'waktu',true);?>
            <?php $tampil_nilai = get_post_meta($post_id,'tampil_nilai',true);?>

            <p id="countdown9"></p>
            <p id="countdown8" class="text-center mb-4"></p>
            <form class="form-jawab">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            <div class="card">
                <div class="card-header">
                    <?php the_title(); ?>
                </div>
                <div class="card-body">
                    <?php $quiz = get_post_meta($post_id,'quiz',true);
                    $i = 1;
                    $jml_quiz = count($quiz);
                    foreach ($quiz as $data) {
                        $no = $i++;
                        $show = $no == '1'? 'show ' :'';
                        echo '<div id="coll-'.$no.'" class="'.$show.'collapse kolomsoal">';
                            echo '<div class="fs-5 fw-bold mb-3">Soal '.$no.'</div>';
                            echo '<div class="card p-3 border border-success mb-4">
                                <div class="card-soal">'.$data['tanya'].'</div>
                            </div>';
                            echo '<div class="pilihan-jawaban">';
                            $pilihan_jawaban = array('a','b','c','d');
                            foreach ($pilihan_jawaban as $abjab) {
                                echo '<div class="d-block">';
                                    echo '<input class="d-none soalradio" type="radio" data-id="'.$no.'" id="soal-'.$no.$abjab.'" name="jawaban['.$no.']" value="'.$abjab.'" required>';
                                    echo '<label class="w-100 p-0 soalradiolabel" for="soal-'.$no.$abjab.'">';
                                        echo '<div class="input-group mb-2">';
                                            echo '<div class="input-group-prepend text-uppercase">';
                                                echo '<div class="input-group-text radio-elv rounded-start rounded-0">'.$abjab.'</div>';
                                            echo '</div>';
                                            echo '<div class="form-control radio-elv">'.$data[$abjab].'</div>';
                                        echo '</div>';
                                    echo '</label>';
                                echo '</div>';
                            }
                            echo '</div>';
                            if ($no > 1) {
                              $urutbefore = $no - 1;
                              echo '<a class="my-3 mr-3 btn btn-dark linksoal text-white me-2" id="'.$urutbefore.'"><i class="fa fa-caret-left"></i> Sebelumnya</a>';
                            }
                            if ($no < $jml_quiz) {
                              $urutnext = $no + 1;
                              echo '<a class="my-3 btn btn-dark linksoal text-white" id="'.$urutnext.'">Selanjutnya <i class="fa fa-caret-right"></i></a>';
                            }
                        echo '</div>';
                    } 
                    echo '<small class="fst-italic text-muted">Ket: Isikan semua jawaban sebelum submit</small>';
                    echo '<ul class="pagination mt-4 quiz-pagination">';
                    for ($x = 1; $x <= count($quiz); $x++) {
                        echo '<li class="page-item"><a class="page-link linksoal link'.$x.'" id="'.$x.'" href="#">'.$x.'</a></li>';
                    }
                    echo '</ul>';
                    ?>
                </div>
                <div class="card-footer text-muted footer-quiz d-none">
                    <button type="submit" class="btn btn-primary w-100"><span class="load"></span>SELESAI</button>
                </div>
            </div>
            </form>
            <div class="hasil-quiz mt-3"></div>

        </div>
    </article>

<?php
  //set waktu
if (empty($_SESSION['kerjaquiz']['setwaktuawal'])) {
    $_SESSION['kerjaquiz']['setwaktuawal'] = $date;
    $endTime = strtotime("+".$waktu." minutes", strtotime($date));
    $expTime = date('Y/m/d H:i:s', $endTime);
} else {
    $to_time    = strtotime($date);
    $from_time  = strtotime($_SESSION['kerjaquiz']['setwaktuawal']);
    $bettime    = $to_time - $from_time;
    $newmin     = $waktu?$waktu*60:0;
    $newminute  = ceil($newmin-$bettime);
    $endTime    = strtotime("+".$newminute." seconds", strtotime($date));
    $expTime    = date('Y/m/d H:i:s', $endTime);
} ?>

    <script>
    jQuery(document).ready(function($){
        $(document).on("click",".linksoal",function(e){
            var get_id = $(this).attr("id");
            $(".kolomsoal").removeClass("show");
            $("#coll-" + get_id).toggleClass("show");
        });
        $(document).on("click",".soalradio",function(e){
            var get_id = $(this).attr("data-id");
            var value  = $(this).attr("value");
            $(".link" + get_id).addClass("bg-success text-white");
            var totalPilihan = $('.soalradio:checked').length;
            if(totalPilihan == '<?php echo $jml_quiz; ?>'){
                $('.footer-quiz').removeClass('d-none');                
            }
        });
        $('.form-jawab').on('submit', function (e) {
            e.preventDefault();
            var datas = $('.form-jawab').serialize();
            $(".load").html('<span class="spinner-grow spinner-grow-sm me-2"></span>');
            $.ajax({
                type: "POST",
                data: "action=submitquiz&"+datas,
                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                success:function(data) {
                    $(".hasil-quiz").html(data);
                    $(".load").html('');
                }
            });
        });
    });
  </script>
<?php endwhile; ?>
</div>


<?php get_footer(); ?>