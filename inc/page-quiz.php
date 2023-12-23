<div class="container">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-3">
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
            <li><a class="dropdown-item" href="<?php get_the_permalink();?>?">Halaman Depan Quiz</a></li>
            <li><a class="dropdown-item" href="<?php get_the_permalink();?>?hal=tambah">Tambah Quiz</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<?php
$halaman = isset($_GET['hal']) ? $_GET['hal'] : '';

if($halaman == 'tambah'){
  include(VELOCITY_QUIZ_DIR.'/inc/quiz-tambah.php');
} else {
  $quiz_args = array(
    'showposts' => -1,
    'post_type' => array('velocity-quiz'),
      /*'tax_query' => array(
          array(
          'taxonomy' => 'kategori',
          'field' => 'slug',
          'terms' => 'figurine',
      ),
    ),*/
  ); 
  $quizposts = get_posts($quiz_args);
  echo '<ul class="list-group list-group-flush">';
  foreach ($quizposts as $quizpost) {
    echo '<li class="list-group-item"><a href="'.get_the_permalink($quizpost->ID).'">'.$quizpost->post_title.'</a></li>';
  }
  echo '</ul>';
}
?>


</div>