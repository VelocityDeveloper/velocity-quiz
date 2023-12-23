<?php get_header(); ?>

<div class="container">
<?php while (have_posts()) : the_post(); ?>
<?php $post_id = get_the_ID(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <h1><?php the_title(); ?></h1>
        <div class="entry-content">
            <?php the_content(); ?>
            <?php echo get_post_meta($post_id,'waktu',true);?>
            <?php echo get_post_meta($post_id,'tampil_nilai',true);?>
            <?php echo get_post_meta($post_id,'safe',true);?>

            <div class="card">
                <div class="card-header">
                    <?php the_title(); ?>
                </div>
                <div class="card-body">
                    <?php $quiz = get_post_meta($post_id,'quiz',true);
                    $i = 1;
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
                                    echo '<input class="d-none soalradio" type="radio" data-id="'.$no.'" id="soal-'.$no.$abjab.'" name="'.$no.'" value="'.$abjab.'">';
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
                        echo '</div>';
                    } 
                    echo '<ul class="pagination mt-5">';
                    for ($x = 1; $x <= count($quiz); $x++) {
                        echo '<li class="page-item"><a class="page-link" href="#">'.$x.'</a></li>';
                    }
                    echo '</ul>';
                    ?>
                </div>
                <div class="card-footer text-muted">
                    <button type="button" class="btn btn-primary w-100">Selesai</button>
                </div>
            </div>

        </div>
    </article>
<?php /* ?>
    <script>
    jQuery(document).ready(function($){
        $(document).on("click",".linksoal",function(e){
            var get_id = $(this).attr("id");
            $(".kolomsoal").removeClass("show");
            $("#coll-" + get_id).toggleClass("show");
        });
    });
  </script>
<?php */ ?>
<?php endwhile; ?>
</div>


<?php get_footer(); ?>