<?php
$current_user = wp_get_current_user();
$halaman = isset($_GET['hal']) ? $_GET['hal'] : '';
if($halaman == 'essay'){
    $title = 'Essay';
} elseif($halaman == 'quiz') {
    $title = 'Quiz';
} else {
    $title = '@'.$current_user->user_login;
} ?>

<div class="container">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-3">
  <div class="container-fluid">
    <div class="navbar-brand"><?php echo $title;?></div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="?">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php get_the_permalink();?>?hal=quiz">Quiz</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php get_the_permalink();?>?hal=essay">Essay</a>
        </li>
      </ul>
    </div>
  </div>
</nav>


<?php
if($halaman == 'quiz'){
    require_once(VELOCITY_QUIZ_DIR.'/inc/user-quiz.php');
} else if($halaman == 'essay'){
    require_once(VELOCITY_QUIZ_DIR.'/inc/user-essay.php');
} else {
    echo 'hello';
}
?>

</div>