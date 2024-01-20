<?php $testimonials = $settings->t_columns;
echo '<div id="testimoni-modul" class="row testimoni-'.$id.'">';
foreach($testimonials as $testimoni){
    echo '<div class="col-md-4 mb-4 testimoni-list">';
    echo '<div class="border h-100 p-3">';
        echo '<div class="row align-items-center mb-3">';
            echo '<div class="col-4 col-sm-3">';
                echo '<svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16"><path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/></svg>';
            echo '</div>';
            echo '<div class="col-8 col-sm-9">';
                echo '<div class="nama-testimoni text-dark fs-5 fw-bold">'.$testimoni->name.'</div>';
                echo '<div class="prof-testimoni text-secondary">'.$testimoni->profession.'</div>';
            echo '</div>';
        echo '</div>';
        echo '<div class="testimoni-description">'.$testimoni->desc.'</div>';
    echo '</div>';
    echo '</div>';
}
echo '</div>';?>
