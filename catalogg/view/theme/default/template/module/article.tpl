<h3><?php echo $heading_title; ?></h3>
<div class="row">
  <?php foreach ($articles as $article) { ?>
  <div class="article-layout col-lg-4 col-md-6 col-sm-12 col-xs-12">
    <div class="article-thumb transition">
      <div class="image">
        <a href="<?php echo $article['href']; ?>"><img src="<?php echo $article['thumb']; ?>" alt="<?php echo $article['name']; ?>" title="<?php echo $article['name']; ?>" class="img-responsive" /></a>
      </div>
      <div class="caption">
        <h4><a href="<?php echo $article['href']; ?>"><?php echo $article['name']; ?></a></h4>
        <p><?php echo $article['intro_text']; ?></p>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
