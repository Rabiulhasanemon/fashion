<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-bkash" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-bkash" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-bank-user"><?php echo $entry_user; ?></label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="bkash_user" id="input-bank-user" value="<?php echo $bkash_user; ?>">
              <?php if ($error_bkash_user) { ?>
              <div class="text-danger"><?php echo $error_bkash_user; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-bank-pass"><?php echo $entry_pass; ?></label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="bkash_pass" id="input-bank-pass" value="<?php echo $bkash_pass; ?>">
              <?php if ($error_bkash_pass) { ?>
              <div class="text-danger"><?php echo $error_bkash_pass; ?></div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-bank-app_key"><?php echo $entry_app_key; ?></label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="bkash_app_key" id="input-bank-app_key" value="<?php echo $bkash_app_key; ?>">
              <?php if ($error_bkash_app_key) { ?>
              <div class="text-danger"><?php echo $error_bkash_app_key; ?></div>
              <?php } ?>
            </div>
          </div>


          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-bank-app_secret"><?php echo $entry_app_secret; ?></label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="bkash_app_secret" id="input-bank-app_secret" value="<?php echo $bkash_app_secret; ?>">
              <?php if ($error_bkash_app_secret) { ?>
              <div class="text-danger"><?php echo $error_bkash_app_secret; ?></div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-bank-token_url"><?php echo $entry_token_url; ?></label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="bkash_token_url" id="input-bank-token_url" value="<?php echo $bkash_token_url; ?>">
              <?php if ($error_bkash_token_url) { ?>
              <div class="text-danger"><?php echo $error_bkash_token_url; ?></div>
              <?php } ?>
            </div>
          </div>


          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-bank-create_url"><?php echo $entry_create_url; ?></label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="bkash_create_url" id="input-bank-create_url" value="<?php echo $bkash_create_url; ?>">
              <?php if ($error_bkash_create_url) { ?>
              <div class="text-danger"><?php echo $error_bkash_create_url; ?></div>
              <?php } ?>
            </div>
          </div>


          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-bank-execute_url"><?php echo $entry_execute_url; ?></label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="bkash_execute_url" id="input-bank-execute_url" value="<?php echo $bkash_execute_url; ?>">
              <?php if ($error_bkash_execute_url) { ?>
              <div class="text-danger"><?php echo $error_bkash_execute_url; ?></div>
              <?php } ?>
            </div>
          </div>


          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-bank-query_url"><?php echo $entry_query_url; ?></label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="bkash_query_url" id="input-bank-query_url" value="<?php echo $bkash_query_url; ?>">
              <?php if ($error_bkash_query_url) { ?>
              <div class="text-danger"><?php echo $error_bkash_query_url; ?></div>
              <?php } ?>
            </div>
          </div>


          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-bank-script_url"><?php echo $entry_script_url; ?></label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="bkash_script_url" id="input-bank-script_url" value="<?php echo $bkash_script_url; ?>">
              <?php if ($error_bkash_script_url) { ?>
              <div class="text-danger"><?php echo $error_bkash_script_url; ?></div>
              <?php } ?>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="bkash_order_status_id" id="input-order-status" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $bkash_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="bkash_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $bkash_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="bkash_status" id="input-status" class="form-control">
                <?php if ($bkash_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="bkash_sort_order" value="<?php echo $bkash_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>