<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-category-import" data-toggle="tooltip" title="<?php echo $button_import; ?>" class="btn btn-primary"><i class="fa fa-upload"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-upload"></i> <?php echo $text_import; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-category-import" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_import_info; ?></label>
            <div class="col-sm-10">
              <div class="alert alert-info">
                <p><strong><?php echo $text_import_info; ?></strong></p>
                <ul>
                  <li><?php echo $text_import_info_line1; ?></li>
                  <li><?php echo $text_import_info_line2; ?></li>
                  <li><?php echo $text_import_info_line3; ?></li>
                  <li><?php echo $text_import_info_line4; ?></li>
                </ul>
                <p><a href="<?php echo $download_sample; ?>" class="btn btn-info"><i class="fa fa-download"></i> <?php echo $button_download_sample; ?></a></p>
              </div>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-file"><?php echo $entry_file; ?></label>
            <div class="col-sm-10">
              <input type="file" name="import_file" id="input-file" accept=".csv" />
              <?php if (isset($error_file)) { ?>
              <div class="text-danger"><?php echo $error_file; ?></div>
              <?php } ?>
              <div class="help-block"><?php echo $help_file; ?> Maximum file size: <?php echo ini_get('upload_max_filesize'); ?></div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>


  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-category-import" data-toggle="tooltip" title="<?php echo $button_import; ?>" class="btn btn-primary"><i class="fa fa-upload"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-upload"></i> <?php echo $text_import; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-category-import" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_import_info; ?></label>
            <div class="col-sm-10">
              <div class="alert alert-info">
                <p><strong><?php echo $text_import_info; ?></strong></p>
                <ul>
                  <li><?php echo $text_import_info_line1; ?></li>
                  <li><?php echo $text_import_info_line2; ?></li>
                  <li><?php echo $text_import_info_line3; ?></li>
                  <li><?php echo $text_import_info_line4; ?></li>
                </ul>
                <p><a href="<?php echo $download_sample; ?>" class="btn btn-info"><i class="fa fa-download"></i> <?php echo $button_download_sample; ?></a></p>
              </div>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-file"><?php echo $entry_file; ?></label>
            <div class="col-sm-10">
              <input type="file" name="import_file" id="input-file" accept=".csv" />
              <?php if (isset($error_file)) { ?>
              <div class="text-danger"><?php echo $error_file; ?></div>
              <?php } ?>
              <div class="help-block"><?php echo $help_file; ?> Maximum file size: <?php echo ini_get('upload_max_filesize'); ?></div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>

