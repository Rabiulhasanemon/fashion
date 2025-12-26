<?php echo $header; ?>
<div class="after-header p-tb-10">
  <div class="container">
    <h2 class="page_heading"><?php echo $heading_title; ?></h2>
    <ul class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
      <?php } ?>
    </ul>
  </div>
</div>
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
      <div class="main_content" style="text-align: center">
        <p><?php echo $text_response; ?></p>
        <div style="border: 1px solid #DDDDDD; margin-bottom: 20px; width: 350px; margin-left: auto; margin-right: auto;"></div>
        <p><?php echo $text_success; ?></p>
        <p><?php echo $text_success_wait; ?></p>
      </div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>

<script type="text/javascript">
setTimeout('location = \'<?php echo $continue; ?>\';', 2500);
</script>
