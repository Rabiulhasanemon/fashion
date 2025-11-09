<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-order-feedback" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-order-feedback" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
            <div class="col-sm-10">
              <input type="text" name="order_id" value="<?php echo $order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" readonly />
            </div>
          </div>
          
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-customer"><?php echo $entry_customer; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="customer" value="<?php echo $customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" readonly/>
            </div>
          </div> 
          
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
            <div class="col-sm-10">
              <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" readonly/>
            </div>
          </div>
          
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_response; ?></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <input type="radio" name="response" value="1" <?php echo $response == 1 ? "checked" : ""; ?>/>
                <span>1</span>
              </label>
              <label class="radio-inline">
                <input type="radio" name="response" value="2" <?php echo $response == 2 ? "checked" : ""; ?>/>
                <span>2</span>
              </label>
              <label class="radio-inline">
                <input type="radio" name="response" value="2" <?php echo $response == 3 ? "checked" : ""; ?>/>
                <span>3</span>
              </label>
              <label class="radio-inline">
                <input type="radio" name="response" value="4" <?php echo $response == 4 ? "checked" : ""; ?>/>
                <span>4</span>
              </label>
              <label class="radio-inline">
                <input type="radio" name="response" value="5" <?php echo $response == 5 ? "checked" : ""; ?>/>
                <span>5</span>
              </label>
              <?php if ($error_response) { ?>
              <div class="text-danger"><?php echo $error_response; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_support_agent; ?></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <input type="radio" name="support_agent" value="1" <?php echo $support_agent == 1 ? "checked" : ""; ?>/>
                <span>1</span>
              </label>
              <label class="radio-inline">
                <input type="radio" name="support_agent" value="2" <?php echo $support_agent == 2 ? "checked" : ""; ?>/>
                <span>2</span>
              </label>
              <label class="radio-inline">
                <input type="radio" name="support_agent" value="2" <?php echo $support_agent == 3 ? "checked" : ""; ?>/>
                <span>3</span>
              </label>
              <label class="radio-inline">
                <input type="radio" name="support_agent" value="4" <?php echo $support_agent == 4 ? "checked" : ""; ?>/>
                <span>4</span>
              </label>
              <label class="radio-inline">
                <input type="radio" name="support_agent" value="5" <?php echo $support_agent == 5 ? "checked" : ""; ?>/>
                <span>5</span>
              </label>
              <?php if ($error_support_agent) { ?>
              <div class="text-danger"><?php echo $error_support_agent; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_delivery_service; ?></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <input type="radio" name="delivery_service" value="1" <?php echo $delivery_service == 1 ? "checked" : ""; ?>/>
                <span>1</span>
              </label>
              <label class="radio-inline">
                <input type="radio" name="delivery_service" value="2" <?php echo $delivery_service == 2 ? "checked" : ""; ?>/>
                <span>2</span>
              </label>
              <label class="radio-inline">
                <input type="radio" name="delivery_service" value="2" <?php echo $delivery_service == 3 ? "checked" : ""; ?>/>
                <span>3</span>
              </label>
              <label class="radio-inline">
                <input type="radio" name="delivery_service" value="4" <?php echo $delivery_service == 4 ? "checked" : ""; ?>/>
                <span>4</span>
              </label>
              <label class="radio-inline">
                <input type="radio" name="delivery_service" value="5" <?php echo $delivery_service == 5 ? "checked" : ""; ?>/>
                <span>5</span>
              </label>
              <?php if ($error_delivery_service) { ?>
              <div class="text-danger"><?php echo $error_delivery_service; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-reorder"><?php echo $entry_reorder; ?></label>
            <div class="col-sm-10">
              <select name="reorder" id="input-reorder" class="form-control">
                <?php if ($reorder) { ?>
                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                <option value="0"><?php echo $text_no; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_yes; ?></option>
                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-comment"><?php echo $entry_comment; ?></label>
            <div class="col-sm-10">
              <textarea name="comment" cols="60" rows="8" placeholder="<?php echo $entry_comment; ?>" id="input-comment" class="form-control"><?php echo $comment; ?></textarea>
              <?php if ($error_comment) { ?>
              <span class="text-danger">
              <?php echo $error_comment; ?></span>
              <?php } ?>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'product\']').val(item['label']);
		$('input[name=\'product_id\']').val(item['value']);		
	}	
});
//--></script></div>
<?php echo $footer; ?>