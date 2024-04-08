<!DOCTYPE html>
<html lang="en" dir="ltr">

<?php 
  session_start();
  error_reporting(0);
?>
<head>
  <!--html head-->
  <?php include '../php/html_head.php' ?>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <!--TOP Navbar -->
  <?php include '../php/topnavbar.php'; ?>
  <!-- First body -->
  <div class="container">
    <div class="banner">
      <img src="../pic/pexels-andre-furtado-370717.jpg">
      <div class="container">
        <div class="home_slider_content" data-animation-in="fadeIn" data-animation-out="animate-out fadeOut">
          <div class="home_slider_title">努力向上</div>
          <div class="home_slider_subtitle">線上輔助工作提升效能</div>
          <a type="button" href="../login/login_main.php" class="btn btn-outline-light">開始使用</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Slide windows-->
  <div class="container">
    <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active" data-bs-interval="10000">
          <img src="../pic/role1.jpg" class="d-block w-100" alt="...">
          <div class="carousel-caption d-none d-md-block">
            <h5>First slide label</h5>
            <p>Some representative placeholder content for the first slide.</p>
          </div>
        </div>
        <div class="carousel-item" data-bs-interval="10000">
          <img src="../pic/role2.jpg" class="d-block w-100" alt="...">
          <div class="carousel-caption d-none d-md-block">
            <h5>Second slide label</h5>
            <p>Some representative placeholder content for the second slide.</p>
          </div>
        </div>
        <div class="carousel-item" data-bs-interval="10000">
          <img src="../pic/role3.jpg" class="d-block w-100" alt="...">
          <div class="carousel-caption d-none d-md-block">
            <h5>Third slide label</h5>
            <p>Some representative placeholder content for the third slide.</p>
          </div>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>

  <!-- footer copyright-->
  <?php include '../php/footer.php'; ?>
</body>

</html>