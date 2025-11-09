<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-customer-group" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customer-group" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
            <div class="col-sm-10">
              <input type="text" value="" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
              <div id="order-id" class="well well-sm" style="height: 300px; overflow: auto;">

              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="order_status_id" id="input-order-status" class="form-control">
                <?php foreach ($order_statuses as $order_status_it) { ?>
                <option value="<?php echo $order_status_it['order_status_id']; ?>"><?php echo $order_status_it['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-notify"><?php echo $entry_notify; ?></label>
            <div class="col-sm-10">
              <input type="checkbox" name="notify" value="1" id="input-notify" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-comment"><?php echo $entry_comment; ?></label>
            <div class="col-sm-10">
              <textarea name="comment" rows="8" id="input-comment" class="form-control"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="application/javascript">

  $('#input-order-id').on("keypress", function (e) {

    if(e.keyCode === 13){
      e.preventDefault();
      if(!this.value) return
      $('#order-id-' + this.value).remove();
      $('#order-id').append('<div id="order-id-' + this.value + '" class="order-id"><i class="fa fa-minus-circle"></i> ' + this.value + '<input type="hidden" name="order_id[]" value="' + this.value + '" /></div>');
      this.value = ""
    }
  })

  $('#order-id').delegate('.fa-minus-circle', 'click', function() {
    $(this).parent().remove();
  });
</script>
<?php echo $footer; ?>