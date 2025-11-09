<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
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
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-payment-id"><?php echo $entry_payment_id; ?></label>
                <input type="text" name="filter_payment_id" value="<?php echo $filter_payment_id; ?>" placeholder="<?php echo $entry_payment_id; ?>" id="input-payment-id" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
                <input type="text" name="filter_total" value="<?php echo $filter_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
              </div> 
              <div class="form-group">
                <label class="control-label" for="input-transaction-id"><?php echo $entry_transaction_id; ?></label>
                <input type="text" name="filter_transaction_id" value="<?php echo $filter_transaction_id; ?>" placeholder="<?php echo $entry_transaction_id; ?>" id="input-transaction-id" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_status == "Pending") { ?>
                  <option value="Pending" selected="selected"><?php echo $text_pending; ?></option>
                  <?php } else { ?>
                  <option value="Pending"><?php echo $text_pending; ?></option>
                  <?php } ?>
                  <?php if ($filter_status == "Approved") { ?>
                  <option value="Approved" selected="selected"><?php echo $text_approved; ?></option>
                  <?php } else { ?>
                  <option value="Approved"><?php echo $text_approved; ?></option>
                  <?php } ?>
                  <?php if ($filter_status == "Failed") { ?>
                  <option value="Failed" selected="selected"><?php echo $text_failed; ?></option>
                  <?php } else { ?>
                  <option value="Failed"><?php echo $text_failed; ?></option>
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
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-payment">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left"><?php if ($sort == 'p.payment_id') { ?>
                    <a href="<?php echo $sort_payment_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_payment_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_payment_id; ?>"><?php echo $column_payment_id; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'p.order_id') { ?>
                    <a href="<?php echo $sort_order_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_order_id; ?>"><?php echo $column_order_id; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'p.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'p.total') { ?>
                    <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
                    <?php } ?></td>
                  <td><?php echo $column_gateway; ?></td>
                  <td><?php echo $column_transaction_id; ?></td>
                  <td><?php echo $column_tracking; ?></td>
                  <td width="20%"><?php echo $column_comment; ?></td>
                  <td class="text-left"><?php if ($sort == 'p.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($payments) { ?>
                <?php foreach ($payments as $payment) { ?>
                <tr>
                  <td class="text-left"><?php echo $payment['payment_id']; ?></td>
                  <td class="text-left"><?php echo $payment['order_id']; ?></td>
                  <td class="text-left"><?php echo $payment['status']; ?></td>
                  <td class="text-left"><?php echo $payment['total']; ?></td>
                  <td class="text-left"><?php echo $payment['gateway_title']; ?></td>
                  <td class="text-left"><?php echo $payment['transaction_id']; ?></td>
                  <td class="text-left"><?php echo $payment['tracking_no']; ?><br><?php echo $payment['payer_info']; ?></td>
                  <td class="text-left"><?php echo $payment['comment']; ?></td>
                  <td class="text-left"><?php echo $payment['date_added']; ?></td>
                  <td class="text-right"><a href="<?php echo $payment['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-primary"><i class="fa fa-eye"></i></a></td>
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
	url = 'index.php?route=sale/payment&token=<?php echo $token; ?>';
	
	var filter_payment_id = $('input[name=\'filter_payment_id\']').val();
	
	if (filter_payment_id) {
		url += '&filter_payment_id=' + encodeURIComponent(filter_payment_id);
	}
	
	var filter_order_id = $('input[name=\'filter_order_id\']').val();
	
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}

	var filter_total = $('input[name=\'filter_total\']').val();

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}

    var filter_transaction_id = $('input[name=\'filter_transaction_id\']').val();

    if (filter_transaction_id) {
      url += '&filter_transaction_id=' + encodeURIComponent(filter_transaction_id);
    }
	
	var filter_status = $('select[name=\'filter_status\']').val();
	
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status); 
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