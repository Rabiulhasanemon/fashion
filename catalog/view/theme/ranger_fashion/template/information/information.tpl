<?php echo $header; ?>
<section class="after-header">
  <div class="container">
    <ul class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
      <?php foreach ($breadcrumbs as $i => $breadcrumb) { if($i < 1) { ?>
      <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
      <?php } else { ?>
      <li  itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemtype="http://schema.org/Thing" itemprop="item" href="<?php echo $breadcrumb['href']; ?>"><span itemprop="name"><?php echo $breadcrumb['text']; ?></span></a><meta itemprop="position" content="<?php echo $i; ?>" /></li>
      <?php }} ?>
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
        <div class="info-page">
            <?php echo $description; ?><?php echo $content_bottom; ?></div>
        </div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>