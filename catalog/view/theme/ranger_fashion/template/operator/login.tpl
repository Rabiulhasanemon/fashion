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
<div class="container alert-container">
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
    <?php } ?>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
    <?php } ?>
</div>
<div class="container account_layout customer_login body">
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
          <div class="row">
              <div class="col-sm-6">
                  <div class="well">
                      <h2><?php echo $text_new_operator; ?></h2>
                      <p><strong><?php echo $text_register; ?></strong></p>
                      <a href="<?php echo $register; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
              </div>
              <div class="col-sm-6">
                  <div class="well">
                      <h2><?php echo $text_registered_operator; ?></h2>
                      <p><strong><?php echo $text_i_am_registered_operator; ?></strong></p>
                      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                          <div class="form-group">
                              <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                              <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                          </div>
                          <div class="form-group">
                              <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
                              <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                              <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></div>
                          <input type="submit" value="<?php echo $button_login; ?>" class="btn btn-primary" />
                          <?php if ($redirect) { ?>
                          <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
                          <?php } ?>
                      </form>
                  </div>
              </div>
          </div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>