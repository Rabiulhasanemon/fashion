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
<div class="container all-brands-container">
  <div id="content" class="d-flex flex-wrap"">
    <?php if ($categories) { ?>
    <?php foreach ($categories as $category) { ?>
    <?php if ($category['manufacturer']) { ?>
    <?php foreach ($category['manufacturer'] as $manufacturer) { ?>
    <div class="brand-wrap">
      <div class="brand">
        <a href="<?php echo $manufacturer['href']; ?>"><img src="<?php echo $manufacturer['image']; ?>" alt="<?php echo $manufacturer['name']; ?>"></a>
      </div>
    </div>
    <?php } ?>
    <?php } ?>
    <?php } ?>
    <?php } else { ?>
    <div class="empty-content">
      <p><?php echo $text_empty; ?></p>
      <a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a>
    </div>
    <?php } ?>
  </div>
</div>
<?php echo $footer; ?>