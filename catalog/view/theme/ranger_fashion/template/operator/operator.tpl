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