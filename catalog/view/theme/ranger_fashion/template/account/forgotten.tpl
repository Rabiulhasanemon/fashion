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
<div class="container alert-container">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
    <?php } ?>
</div>
<div class="container account-page forgotten-page">
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="panel">
          <h1><?php echo $heading_title; ?></h1>
          <p class="mb-2"><?php echo $text_email; ?></p>
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
              <div class="form-group required">
                  <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                  <div class="info">
                      <input type="text" name="email" value="" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                  </div>
              </div>
              <div class="buttons">
                  <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
                  <div class="pull-right">
                      <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
                  </div>
              </div>
          </form>
      </div>
   <?php echo $content_bottom; ?></div>
</div>
<?php echo $footer; ?>