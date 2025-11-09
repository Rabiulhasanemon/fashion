<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-flash-deal" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-flash-deal" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-title"><?php echo $entry_title; ?></label>
            <div class="col-sm-10">
              <input type="text" name="title" value="<?php echo isset($title) ? $title : 'Flash Deal'; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <hr />

          <div class="form-group">
            <label class="col-sm-2 control-label">Products</label>
            <div class="col-sm-10">
              <button type="button" id="btn-add-product" class="btn btn-success"><i class="fa fa-plus-circle"></i> <?php echo $button_add_product; ?></button>
            </div>
          </div>

          <div id="product-container">
            <?php 
            $product_row = 0;
            if (!isset($products)) {
              $products = array();
            }
            ?>
            <?php if (!empty($products)) { ?>
            <?php foreach ($products as $product) { ?>
            <div class="panel panel-default product-row" id="product-row<?php echo $product_row; ?>">
              <div class="panel-heading">
                <h4 class="panel-title">Product #<?php echo ($product_row + 1); ?> <button type="button" class="btn btn-danger btn-xs pull-right btn-remove-product"><i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?></button></h4>
              </div>
              <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-product<?php echo $product_row; ?>"><?php echo $entry_product; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="products[<?php echo $product_row; ?>][product_name]" value="<?php echo isset($product['product_name']) ? $product['product_name'] : ''; ?>" placeholder="<?php echo $entry_product; ?>" id="input-product<?php echo $product_row; ?>" class="form-control" />
                    <input type="hidden" name="products[<?php echo $product_row; ?>][product_id]" value="<?php echo $product['product_id']; ?>" id="input-product-id<?php echo $product_row; ?>" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-discount<?php echo $product_row; ?>"><?php echo $entry_discount; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="products[<?php echo $product_row; ?>][discount]" value="<?php echo isset($product['discount']) ? $product['discount'] : '0'; ?>" placeholder="<?php echo $entry_discount; ?>" id="input-discount<?php echo $product_row; ?>" class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-end-date<?php echo $product_row; ?>"><?php echo $entry_end_date; ?></label>
                  <div class="col-sm-10">
                    <div class="input-group datetime">
                      <input type="text" name="products[<?php echo $product_row; ?>][end_date]" value="<?php echo isset($product['end_date']) ? $product['end_date'] : ''; ?>" placeholder="<?php echo $entry_end_date; ?>" id="input-end-date<?php echo $product_row; ?>" class="form-control" />
                      <span class="input-group-btn">
                        <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-sort-order<?php echo $product_row; ?>"><?php echo $entry_sort_order; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="products[<?php echo $product_row; ?>][sort_order]" value="<?php echo isset($product['sort_order']) ? $product['sort_order'] : '0'; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order<?php echo $product_row; ?>" class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-product-status<?php echo $product_row; ?>"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="products[<?php echo $product_row; ?>][status]" id="input-product-status<?php echo $product_row; ?>" class="form-control">
                      <option value="1" <?php echo (isset($product['status']) && $product['status']) ? 'selected' : ''; ?>><?php echo $text_enabled; ?></option>
                      <option value="0" <?php echo (!isset($product['status']) || !$product['status']) ? 'selected' : ''; ?>><?php echo $text_disabled; ?></option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <?php $product_row++; ?>
            <?php } ?>
            <?php } ?>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php 
$js_text_enabled = isset($text_enabled) ? addslashes($text_enabled) : '';
$js_text_disabled = isset($text_disabled) ? addslashes($text_disabled) : '';
$js_button_remove = isset($button_remove) ? addslashes($button_remove) : '';
$js_entry_product = isset($entry_product) ? addslashes($entry_product) : '';
$js_entry_discount = isset($entry_discount) ? addslashes($entry_discount) : '';
$js_entry_end_date = isset($entry_end_date) ? addslashes($entry_end_date) : '';
$js_entry_sort_order = isset($entry_sort_order) ? addslashes($entry_sort_order) : '';
$js_entry_status = isset($entry_status) ? addslashes($entry_status) : '';
?>
<script type="text/javascript"><!--
var product_row = <?php echo isset($product_row) ? $product_row : 0; ?>;

function addProduct() {
  var html = '<div class="panel panel-default product-row" id="product-row' + product_row + '">';
  html += '  <div class="panel-heading">';
  html += '    <h4 class="panel-title">Product #' + (product_row + 1) + ' <button type="button" class="btn btn-danger btn-xs pull-right btn-remove-product"><i class="fa fa-minus-circle"></i> <?php echo $js_button_remove; ?></button></h4>';
  html += '  </div>';
  html += '  <div class="panel-body">';
  html += '    <div class="form-group">';
  html += '      <label class="col-sm-2 control-label" for="input-product' + product_row + '"><?php echo $js_entry_product; ?></label>';
  html += '      <div class="col-sm-10">';
  html += '        <input type="text" name="products[' + product_row + '][product_name]" value="" placeholder="<?php echo $js_entry_product; ?>" id="input-product' + product_row + '" class="form-control" />';
  html += '        <input type="hidden" name="products[' + product_row + '][product_id]" value="" id="input-product-id' + product_row + '" />';
  html += '      </div>';
  html += '    </div>';
  html += '    <div class="form-group">';
  html += '      <label class="col-sm-2 control-label" for="input-discount' + product_row + '"><?php echo $js_entry_discount; ?></label>';
  html += '      <div class="col-sm-10">';
  html += '        <input type="text" name="products[' + product_row + '][discount]" value="0" placeholder="<?php echo $js_entry_discount; ?>" id="input-discount' + product_row + '" class="form-control" />';
  html += '      </div>';
  html += '    </div>';
  html += '    <div class="form-group">';
  html += '      <label class="col-sm-2 control-label" for="input-end-date' + product_row + '"><?php echo $js_entry_end_date; ?></label>';
  html += '      <div class="col-sm-10">';
  html += '        <div class="input-group datetime">';
  html += '          <input type="text" name="products[' + product_row + '][end_date]" value="" placeholder="<?php echo $js_entry_end_date; ?>" id="input-end-date' + product_row + '" class="form-control" />';
  html += '          <span class="input-group-btn">';
  html += '            <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>';
  html += '          </span>';
  html += '        </div>';
  html += '      </div>';
  html += '    </div>';
  html += '    <div class="form-group">';
  html += '      <label class="col-sm-2 control-label" for="input-sort-order' + product_row + '"><?php echo $js_entry_sort_order; ?></label>';
  html += '      <div class="col-sm-10">';
  html += '        <input type="text" name="products[' + product_row + '][sort_order]" value="0" placeholder="<?php echo $js_entry_sort_order; ?>" id="input-sort-order' + product_row + '" class="form-control" />';
  html += '      </div>';
  html += '    </div>';
  html += '    <div class="form-group">';
  html += '      <label class="col-sm-2 control-label" for="input-product-status' + product_row + '"><?php echo $js_entry_status; ?></label>';
  html += '      <div class="col-sm-10">';
  html += '        <select name="products[' + product_row + '][status]" id="input-product-status' + product_row + '" class="form-control">';
  html += '          <option value="1" selected><?php echo $js_text_enabled; ?></option>';
  html += '          <option value="0"><?php echo $js_text_disabled; ?></option>';
  html += '        </select>';
  html += '      </div>';
  html += '    </div>';
  html += '  </div>';
  html += '</div>';

  $('#product-container').append(html);

  // Bind remove button for the newly added product
  $('#product-row' + product_row + ' .btn-remove-product').on('click', function() {
    $(this).closest('.product-row').remove();
  });

  bindAutocomplete(product_row);
  
  // Initialize datetime picker for the new field
  // Find the input within the newly added input-group.datetime
  $('#input-end-date' + product_row).parent().datetimepicker({
    pickDate: true,
    pickTime: true
  });

  product_row++;
}

function bindAutocomplete(index) {
  $('#input-product' + index).autocomplete({
    source: function(request, response) {
      $.ajax({
        url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
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
    minLength: 0,
    select: function(item) {
      $('#input-product' + index).val(item.label);
      $('#input-product-id' + index).val(item.value);
    }
  });
}

// Initialize when document is ready
$(document).ready(function() {
  // Bind add product button
  $('#btn-add-product').on('click', function() {
    addProduct();
  });
  
  // Bind remove buttons for existing products using event delegation
  $(document).on('click', '.btn-remove-product', function() {
    $(this).closest('.product-row').remove();
  });
  
  // Bind existing products
  <?php $product_row = 0; ?>
  <?php if (isset($products) && !empty($products)) { ?>
  <?php foreach ($products as $product) { ?>
  bindAutocomplete(<?php echo $product_row; ?>);
  <?php $product_row++; ?>
  <?php } ?>
  <?php } ?>

  // Initialize datetime picker for existing fields
  // The datetime picker is initialized on the .input-group.datetime container, not the input
  $('.input-group.datetime').each(function() {
    $(this).datetimepicker({
      pickDate: true,
      pickTime: true
    });
  });
});
//--></script>

<?php echo $footer; ?>

