<?php echo $header; ?>
<div class="after-header p-tb-15">
    <div class="container">
        <h2 class="page_heading category-name"><?php echo $heading_title; ?></h2>
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ul>
    </div>
</div>
<div class="container body">

  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="main_content">
          <h2><?php echo $text_my_account; ?></h2>
          <ul class="list-unstyled">
              <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
              <li><a href="<?php echo $quote; ?>"><?php echo $text_quotation; ?></a></li>
              <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
              <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
          </ul>
          <?php echo $content_bottom; ?>
      </div>
    </div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>