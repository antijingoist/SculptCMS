<?php
   include 'sections/header.php';
?>

<nav class="navdrawer-container promote-layer sc-content-navigation" id="navigation"><h4><a href="#nav_top">Navigation</a></h4>
<?php
  show_nav();
?>
</nav>


<div id="skrollr-body">
    <section id="slide-1" class="homeSlide"><div class="bcg"
       data-top="background-position: 50% 50%;opacity: 1;"

      data-top-center="background-position: 50% +100px; opacity: 1;"

      data-anchor-target="#slide-1">

    </div>

   <div class="bcg bcg2"
      data-top="background-position: 50% 50%; opacity: 0;"
      data-top-center="background-position: 50% +100px; opacity: 1"
      data-anchor-target="#slide-1">
   </div>
</section>
<main>
<div id="content" class="sc-content-main">
   <?php // rand_image(); ?>
  <?php page_text($current_page);?>
</div>
</main>
<?php include 'sections/footer.php'; ?>