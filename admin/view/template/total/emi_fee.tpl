<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-emi-fee" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-emi-fee" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-fee-3-month"><?php echo $entry_fee_3_month; ?></label>
            <div class="col-sm-10">
              <input type="text" name="emi_fee_3_month" value="<?php echo $emi_fee_3_month; ?>" placeholder="<?php echo $entry_fee_3_month; ?>" id="input-fee-3-month" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-fee-6-month"><?php echo $entry_fee_6_month; ?></label>
            <div class="col-sm-10">
              <input type="text" name="emi_fee_6_month" value="<?php echo $emi_fee_6_month; ?>" placeholder="<?php echo $entry_fee_6_month; ?>" id="input-fee-6-month" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-fee-9-month"><?php echo $entry_fee_9_month; ?></label>
            <div class="col-sm-10">
              <input type="text" name="emi_fee_9_month" value="<?php echo $emi_fee_9_month; ?>" placeholder="<?php echo $entry_fee_9_month; ?>" id="input-fee-9-month" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-fee-12-month"><?php echo $entry_fee_12_month; ?></label>
            <div class="col-sm-10">
              <input type="text" name="emi_fee_12_month" value="<?php echo $emi_fee_12_month; ?>" placeholder="<?php echo $entry_fee_12_month; ?>" id="input-fee-12-month" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="emi_fee_status" id="input-status" class="form-control">
                <?php if ($emi_fee_status) { ?>
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
              <input type="text" name="emi_fee_sort_order" value="<?php echo $emi_fee_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>