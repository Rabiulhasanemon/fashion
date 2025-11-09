<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $download_sample; ?>" data-toggle="tooltip" title="<?php echo $button_download_sample; ?>" class="btn btn-default"><i class="fa fa-download"></i></a>
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
    <?php if (!empty($error_warning)) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if (!empty($success)) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-upload"></i> <?php echo $text_import; ?></h3>
      </div>
      <div class="panel-body">
        <p><?php echo $text_import_info_line1; ?></p>
        <p><?php echo $text_import_info_line2; ?></p>
        <p><?php echo $text_import_info_line3; ?></p>
        <p><?php echo $text_import_info_line4; ?></p>

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-file"><?php echo $entry_file; ?></label>
            <div class="col-sm-10">
              <input type="file" name="import_file" id="input-file" class="form-control" accept=".csv" />
              <p class="help-block"><?php echo $help_file; ?></p>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
              <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> <?php echo $button_import; ?></button>
              <a href="<?php echo $cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>





<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $download_sample; ?>" data-toggle="tooltip" title="<?php echo $button_download_sample; ?>" class="btn btn-default"><i class="fa fa-download"></i></a>
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
    <?php if (!empty($error_warning)) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if (!empty($success)) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-upload"></i> <?php echo $text_import; ?></h3>
      </div>
      <div class="panel-body">
        <p><?php echo $text_import_info_line1; ?></p>
        <p><?php echo $text_import_info_line2; ?></p>
        <p><?php echo $text_import_info_line3; ?></p>
        <p><?php echo $text_import_info_line4; ?></p>

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-file"><?php echo $entry_file; ?></label>
            <div class="col-sm-10">
              <input type="file" name="import_file" id="input-file" class="form-control" accept=".csv" />
              <p class="help-block"><?php echo $help_file; ?></p>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
              <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> <?php echo $button_import; ?></button>
              <a href="<?php echo $cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>


















