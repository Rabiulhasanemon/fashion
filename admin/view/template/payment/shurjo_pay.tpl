<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-shurjo-pay" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-shurjo-pay" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-shujoPay-merchant-username"><?php echo $entry_merchant_username; ?></label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="shurjo_pay_merchant_username" id="input-shujoPay-merchant-username" value="<?php echo $shurjo_pay_merchant_username; ?>">
              <?php if ($error_shurjo_pay_merchant_username) { ?>
              <div class="text-danger"><?php echo $error_shurjo_pay_merchant_username; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-shujoPay-api-password"><?php echo $entry_api_password; ?></label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="shurjo_pay_api_password" id="input-shujoPay-api-password" value="<?php echo $shurjo_pay_api_password; ?>">
              <?php if ($error_shurjo_pay_api_password) { ?>
              <div class="text-danger"><?php echo $error_shurjo_pay_api_password; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-shujoPay-api-order-prefix"><?php echo $entry_api_order_prefix; ?></label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="shurjo_pay_api_order_prefix" id="input-shujoPay-api-order-prefix" value="<?php echo $shurjo_pay_api_order_prefix; ?>">
              <?php if ($error_shurjo_pay_api_order_prefix) { ?>
              <div class="text-danger"><?php echo $error_shurjo_pay_api_order_prefix; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-shurjoPay-server-url"><?php echo $entry_server_url; ?></label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="shurjo_pay_server_url" id="input-shurjoPay-server-url" value="<?php echo $shurjo_pay_server_url; ?>">
              <?php if ($error_shurjo_pay_server_url) { ?>
              <div class="text-danger"><?php echo $error_shurjo_pay_server_url; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="shurjo_pay_order_status_id" id="input-order-status" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $shurjo_pay_order_status_id) { ?>
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
              <select name="shurjo_pay_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $shurjo_pay_geo_zone_id) { ?>
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
              <select name="shurjo_pay_status" id="input-status" class="form-control">
                <?php if ($shurjo_pay_status) { ?>
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
              <input type="text" name="shurjo_pay_sort_order" value="<?php echo $shurjo_pay_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>