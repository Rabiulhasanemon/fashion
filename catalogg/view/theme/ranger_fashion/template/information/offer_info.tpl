<?php echo $header; ?>
<section class="after-header p-tb-10">
  <div class="container">
    <ul class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
      <?php } ?>
    </ul>
  </div>
</section>
<div class="container body">
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
        <div class="main_content">
          <h2><?php echo $heading_title; ?></h2>
          <div class="description"><?php echo $description; ?></div>
          <h4 class="m-tb-15">Click below links to check out our best prices:</h4>
          <ul>
            <?php foreach ($links as $link) { ?>
            <li><a href="<?php echo $link['href'] ?>"><?php echo $link['name'] ?></a></li>
            <?php } ?>
          </ul><?php echo $content_bottom; ?>
        </div>
    </div><?php echo $column_right; ?>
  </div>
</div>
<?php echo $footer; ?>