<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-order-feedback').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-order"><?php echo $entry_order_id; ?></label>
                <input type="text" name="filter_order" value="<?php echo $order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-author"><?php echo $entry_customer; ?></label>
                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-reorder"><?php echo $entry_reorder; ?></label>
                <select name="filter_reorder" id="input-reorder" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_reorder) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <?php } ?>
                  <?php if (!$filter_reorder && !is_null($filter_reorder)) { ?>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-order-feedback">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php echo $column_order_id; ?></td>
                  <td class="text-right"><?php if ($sort == 'f.response') { ?>
                    <a href="<?php echo $sort_response; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_response; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_response; ?>"><?php echo $column_response; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'f.support_agent') { ?>
                    <a href="<?php echo $sort_support_agent; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_support_agent; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_support_agent; ?>"><?php echo $column_support_agent; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'f.delivery_service') { ?>
                    <a href="<?php echo $sort_delivery_service; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_delivery_service; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_delivery_service; ?>"><?php echo $column_delivery_service; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'f.reorder') { ?>
                    <a href="<?php echo $sort_reorder; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_reorder; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_reorder; ?>"><?php echo $column_reorder; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'f.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($order_feedbacks) { ?>
                <?php foreach ($order_feedbacks as $order_feedback) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($order_feedback['order_feedback_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $order_feedback['order_feedback_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $order_feedback['order_feedback_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $order_feedback['order_id']; ?></td>
                  <td class="text-left"><?php echo $order_feedback['response']; ?></td>
                  <td class="text-left"><?php echo $order_feedback['support_agent']; ?></td>
                  <td class="text-left"><?php echo $order_feedback['delivery_service']; ?></td>
                  <td class="text-left"><?php echo $order_feedback['reorder']; ?></td>
                  <td class="text-left"><?php echo $order_feedback['date_added']; ?></td>
                  <td class="text-right"><a href="<?php echo $order_feedback['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=sale/order_feedback&token=<?php echo $token; ?>';
	
	var filter_order = $('input[name=\'order_id\']').val();
	
	if (filter_order) {
		url += '&order_id=' + encodeURIComponent(filter_order);
	}
	
	var filter_customer = $('input[name=\'filter_customer\']').val();
	
	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}
	
	var filter_reorder = $('select[name=\'filter_reorder\']').val();
	
	if (filter_reorder != '*') {
		url += '&filter_reorder=' + encodeURIComponent(filter_reorder);
	}		
			
	var filter_date_added = $('input[name=\'filter_date_added\']').val();
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

	location = url;
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>