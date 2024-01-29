<?php get_header(); ?>

<div class="container">

<?php while (have_posts()) : the_post();
    global $wpdb;
    $table_name = $wpdb->prefix."velocity_quiz";
    $post_id = get_the_ID();
    $user_id = get_current_user_id();
    $sudahjawab = $wpdb->get_results("SELECT * FROM $table_name WHERE post_id = $post_id and user_id = $user_id");
    $waktu = get_post_meta($post_id,'waktu',true);
    $time = $waktu ? $waktu.' menit' : '-';
    $kunci = get_post_meta($post_id,'kunci',true);
    $date = date( 'd-m-Y H:i:s', current_time( 'timestamp', 0 ) );
    $act = isset($_GET['act']) ? $_GET['act'] : '';
    $essay = get_post_meta($post_id,'essay',true);

    $infoessay = '<h5 class="card-title border-bottom pb-3 text-center fw-bold">Detail</h5>';
    $infoessay .= '<table class="table"><tbody>';
    $infoessay .= '<tr>';
        $infoessay .= '<td class="fw-bold">Nama Essay</td>';
        $infoessay .= '<td>:</td>';
        $infoessay .= '<td>'.get_the_title($post_id).'</td>';
    $infoessay .= '</tr>';
    $infoessay .= '<tr>';
        $infoessay .= '<td class="fw-bold">Waktu</td>';
        $infoessay .= '<td>:</td>';
        $infoessay .= '<td>'.$time.'</td>';
    $infoessay .= '</tr>';
    $infoessay .= '<tr>';
        $infoessay .= '<td class="fw-bold">Keterangan</td>';
        $infoessay .= '<td>:</td>';
        $infoessay .= '<td>';
            $infoessay .= get_the_content($post_id);
        $infoessay .= '</td>';
    $infoessay .= '</tr>';
    $infoessay .= '</tbody></table>';
?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>        
        <?php 
        if(!is_user_logged_in()){
            echo '<div class="alert alert-warning" role="alert">Silahkan masuk untuk melihat halaman ini.</div>';
        } elseif ($sudahjawab) {
            $detailessay = $sudahjawab[0]->vq_detail;
            $detail = json_decode($detailessay);
            $hasil_nilai = $sudahjawab[0]->nilai;
            if($hasil_nilai){
                echo '<div class="card mx-auto w-100 mb-3" style="max-width: 500px;">';
                    echo '<div class="card-hasil-nilai card-body text-center bg-nilai">';
                        echo '<h3 class="card-title">Nilai anda:</h3>';
                        echo '<p class="card-text h1 fs-1">'.$hasil_nilai.'</p>';
                    echo '</div>';
                echo '</div>';

                if($kunci == 'Ya'){
                    echo '<div class="text-center mb-4">';
                        echo '<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-'.$post_id.'">';
                            echo 'Detail Pengerjaan';
                        echo '</button>';
                    echo '</div>';
                    echo '<div class="modal modal-lg fade" id="modal-'.$post_id.'" tabindex="-1" aria-labelledby="modal-'.$post_id.'Label" aria-hidden="true">';
                    echo '<div class="modal-dialog">';
                        echo '<div class="modal-content">';
                            echo '<div class="modal-header">';
                                echo '<h5 class="modal-title" id="modal-'.$post_id.'Label">Detail Pengerjaan</h5>';
                                echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                            echo '</div>';
                            echo '<div class="modal-body">';
                            $i = 0;
                            foreach ($essay['tanya'] as $data) {
                                $urutan = $i++;
                                $nomorsoal = $urutan + 1;
                                echo '<div class="card-jawaban list-group-item px-0 mb-3">';
                                    echo '<div class="card shadow-sm">';
                                        echo '<div class="card-header">';
                                            echo '<span> Soal no. <strong>'.$nomorsoal.'</strong> </span>';
                                        echo '</div>';
                                        echo '<div class="card-body">';
                                            echo '<div class="mb-1"><b>Pertanyaan:</b></div>';
                                            echo '<div class="card-col-soal border rounded py-2 px-3 mb-3">'.do_shortcode($data).'</div>';
                                            echo '<div class="mb-1"><b>Jawaban:</b></div>';
                                            echo '<div class="card-col-jawab border rounded p-3 mb-3">';
                                                echo $detail[$urutan];
                                            echo '</div>';                    
                                            if($essay['pembahasan'][$urutan]){
                                                echo '<div class="mb-1"><b>Penjelasan:</b></div>';
                                                echo '<div class="card-col-soal border rounded border-dark p-3">'.$essay['pembahasan'][$urutan].'</div>';
                                            }
                                    echo '</div>';
                                    echo '</div>';
                                echo '</div>';
                            }
                            echo '</div>';
                            echo '<div class="modal-footer">';
                                echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }


            } else {
                echo '<div class="alert alert-info card mx-auto w-100 mb-3" style="max-width: 500px;">';
                    echo 'Hasil nilai masih menunggu pengecekan admin.';
                echo '</div>';
            }

            echo '<div class="card mx-auto w-100 p-3" style="max-width: 500px;">';
                echo $infoessay;
            echo '</div>';
        } elseif ($act == 'kerjakan' || isset($_SESSION['kerjaessay'])) { ?>
        <div class="essay-content">

            <p id="countdown9"></p>
            <p id="countdown8" class="text-center mb-4"></p>
            <form class="form-jawab">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            <div class="card">
                <div class="card-header">
                    <?php the_title(); ?>
                </div>
                <div class="card-body">
                    <?php
                    //echo '<pre>'.print_r($essay,1).'</pre>'; 
                    $i = 1;
                    $jml_essay = count($essay['tanya']);
                    foreach ($essay['tanya'] as $data) {
                        $no = $i++;
                        $show = $no == '1'? 'show ' :'';
                        echo '<div id="coll-'.$no.'" class="'.$show.'collapse kolomsoal">';
                            echo '<div class="fs-5 fw-bold mb-3">Soal '.$no.'</div>';
                            echo '<div class="card p-3 border border-success mb-4">
                                <div class="card-soal">'.do_shortcode($data).'</div>
                            </div>';
                            echo '<div class="pilihan-jawaban">';
                                echo '<div class="mb-1 text-muted">Jawaban Anda:</div>';
                                echo '<textarea class="form-control jawaban" name="jawaban[]" data-id="'.$no.'" id="soal-'.$no.'" rows="3" required></textarea>';
                            echo '</div>';
                            if ($no > 1) {
                              $urutbefore = $no - 1;
                              echo '<a class="my-3 mr-3 btn btn-dark linksoal text-white me-2" id="'.$urutbefore.'"><i class="fa fa-caret-left"></i> Sebelumnya</a>';
                            }
                            if ($no < $jml_essay) {
                              $urutnext = $no + 1;
                              echo '<a class="my-3 btn btn-dark linksoal text-white" id="'.$urutnext.'">Selanjutnya <i class="fa fa-caret-right"></i></a>';
                            }
                        echo '</div>';
                    } 
                    echo '<small class="fst-italic text-muted">Ket: Isikan semua jawaban sebelum submit</small>';
                    echo '<ul class="pagination mt-4 essay-pagination">';
                    for ($x = 1; $x <= count($essay['tanya']); $x++) {
                        echo '<li class="page-item"><a class="page-link linksoal link'.$x.'" id="'.$x.'" href="#">'.$x.'</a></li>';
                    }
                    echo '</ul>';
                    ?>
                </div>
                <div class="card-footer text-muted footer-essay d-none">
                    <button type="submit" class="btn btn-primary w-100"><span class="load"></span>SELESAI</button>
                </div>
            </div>
            </form>

    <?php
    //set waktu
    if (empty($_SESSION['kerjaessay']['setwaktuawal'])) {
        $_SESSION['kerjaessay']['setwaktuawal'] = $date;
        $endTime = strtotime("+".$waktu." minutes", strtotime($date));
        $expTime = date('Y/m/d H:i:s', $endTime);
    } else {
        $to_time    = strtotime($date);
        $from_time  = strtotime($_SESSION['kerjaessay']['setwaktuawal']);
        $bettime    = $to_time - $from_time;
        $newmin     = $waktu?$waktu*60:0;
        $newminute  = ceil($newmin-$bettime);
        $endTime    = strtotime("+".$newminute." seconds", strtotime($date));
        $expTime    = date('Y/m/d H:i:s', $endTime);
    } ?>

    <?php 
    //$_SESSION['kerjaessay']['setwaktuawal'] = $date;
    // echo '<pre>'.print_r($_SESSION['kerjaessay'],1).'</pre>';
    // unset($_SESSION['kerjaessay']);
    ?>

    <script>
    jQuery(document).ready(function($){
        $(document).on("click",".linksoal",function(e){
            var get_id = $(this).attr("id");
            $(".kolomsoal").removeClass("show");
            $("#coll-" + get_id).toggleClass("show");
        });

        $(".jawaban").on("input", function() {
            var get_id = $(this).attr("data-id");
            var value = $(this).val();
            if(value) {
                $(".link" + get_id).addClass("bg-success text-white");
            } else {
                $(".link" + get_id).removeClass("bg-success text-white");
            }
            var totaljawaban = $(".jawaban").filter(function() {
                return $(this).val().trim() !== "";
            }).length;
            if(totaljawaban == '<?php echo $jml_essay; ?>'){
                $('.footer-essay').removeClass('d-none');                
            }
        });
        function inputhasilessay() {
            var datas = $('.form-jawab').serialize();
            $(".load").html('<span class="spinner-grow spinner-grow-sm me-2"></span>');
            $.ajax({
                type: "POST",
                data: "action=submitessay&"+datas,
                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                success:function(data) {
                    $(".essay-content").html(data);
                    $(".load").html('');
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log('Error: ' + errorThrown);
                },
            });
        }
        $('.form-jawab').on('submit', function (e) {
            inputhasilessay();
            e.preventDefault();
        });
        <?php if($waktu && !current_user_can('administrator')) { ?>
            $('#countdown8').countdown('<?php echo $expTime; ?>')
            .on('update.countdown', function(event) {
            var format = '<div class="btn btn-secondary">%H</div> : <div class="btn btn-secondary">%M</div> : <div class="btn btn-secondary">%S</div>';
            if(event.offset.totalDays > 0) {
                format = '<div class="btn btn-secondary">%-d Hari </div> - ' + format;
            }
            if(event.offset.weeks > 0) {
                format ='<div class="btn btn-secondary"> %-w Minggu </div> ' + format;
            }
            $(this).html(event.strftime(format));
            })
            .on('finish.countdown', function(e) {
                $("#countdown9").html('<div class="mb-4"><div class="alert alert-danger mx-auto">Waktu Habis</div></div>');
                inputhasilessay();
                e.preventDefault();
            });
        <?php } ?>
    });
    </script>

        </div>        
        <?php 
        } elseif (empty($_SESSION['kerjaessay'])) {
            echo '<div class="card mx-auto w-100 p-3" style="max-width: 500px;">';
                echo $infoessay;
                echo '<a class="btn btn-success" href="?act=kerjakan">Kerjakan</a>';
            echo '</div>';
        } ?>
    </article>
<?php endwhile; ?>
</div>


<?php get_footer(); ?>