<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-ads" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-ads" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-title"><?php echo $entry_title; ?></label>
            <div class="col-sm-10">
              <input type="text" name="title" value="<?php echo $title; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title" class="form-control" />
              <?php if ($error_title) { ?>
              <div class="text-danger"><?php echo $error_title; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-ads-position"><?php echo $entry_ads_position; ?></label>
            <div class="col-sm-10">
              <select name="ads_position_id" id="input-ads-position" class="form-control">
                <?php foreach ($ads_positions as $ads_position) { ?>
                <?php if ($ads_position['ads_position_id'] == $ads_position_id) { ?>
                <option value="<?php echo $ads_position['ads_position_id']; ?>" selected="selected"><?php echo $ads_position['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $ads_position['ads_position_id']; ?>"><?php echo $ads_position['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-device-type"><?php echo $entry_device_type; ?></label>
            <div class="col-sm-10">
              <select name="device_type" id="input-device-type" class="form-control">
                <?php foreach ($devices as $device) { ?>
                <?php if ($device['device_id'] == $device_type) { ?>
                <option value="<?php echo $device['device_id']; ?>" selected="selected"><?php echo $device['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $device['device_id']; ?>"><?php echo $device['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
            <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
              <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
            </div>
            <?php if ($error_image) { ?>
            <div class="text-danger"><?php echo $error_image; ?></div>
            <?php } ?>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-url"><?php echo $entry_url; ?></label>
            <div class="col-sm-10">
              <input type="text" name="url" value="<?php echo $url; ?>" placeholder="<?php echo $entry_url; ?>" id="input-url" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 