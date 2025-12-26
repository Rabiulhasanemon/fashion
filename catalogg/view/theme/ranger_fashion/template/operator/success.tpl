<?php echo $header; ?>
<section class="after-header p-tb-10">
  <div class="container">
    <div class="row">
      <div class="col-sm-8">
        <ul class="breadcrumb">
          <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
          <?php } ?>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="col-sm-4">
        <h6 class="page-heading"><?php echo $heading_title; ?></h6>
      </div>
    </div>
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
        <h1><?php echo $heading_title; ?></h1>
        <?php echo $text_message; ?>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>