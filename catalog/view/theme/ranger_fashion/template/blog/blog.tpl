<?php echo $header; ?>
<section class="after-header p-tb-10">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <ul class="breadcrumb">
          <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
          <?php } ?>
        </ul>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</section>
<section class="bg-gray p-tb-15">
  <div class="container">
      <div class="row">
       <?php echo $column_left; ?>
      <?php if ($column_left && $column_right) { ?>
      <?php $class = 'col-sm-6'; ?>
      <?php } elseif ($column_left || $column_right) { ?>
      <?php $class = 'col-sm-9'; ?>
      <?php } else { ?>
          <?php $class = 'col-sm-12'; ?>
      <?php } ?>
      <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
        <div class="row vd-items-wrapper">
            <?php foreach ($articles as $article) { ?>
            <div class="col-md-6">
                <div class="vd-item">
                    <a href="<?php echo $article['href']; ?>">
                        <div class="vd-item-img">
                            <img src="<?php echo $article['thumb']; ?>" alt="<?php echo $article['name']; ?>">
                            <span class="play-icon"><span class="material-icons">play_circle_outline</span></span>
                        </div>
                    </a>
                    <div class="vd-item-details">
                        <a class="vd-name" href="<?php echo $article['href']; ?>"><?php echo $article['name']; ?></a>
                        <a href="<?php echo $article['href']; ?>"><span class="material-icons">arrow_forward</span></a>
                    </div>
                </div>
            </div>
            <?php } if (!$articles) { ?> <p><?php echo $text_empty; ?></p> <?php } ?>
        </div>
        <div class="bottom-bar">
            <div class="row">
                <div class="col-md-6 col-sm-12"><ul class="pagination"><?php echo $pagination; ?></ul></div>
                <div class="col-md-6 rs-none text-right"><p><?php echo $results; ?></p></div>
            </div>
        </div>
        <?php echo $content_bottom; ?>
      </div>
      <?php echo $column_right; ?>
   </div>
  </div>
</section>
<?php echo $footer; ?>
