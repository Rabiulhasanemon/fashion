<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-coupon" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-coupon" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-product" data-toggle="tab"><?php echo $tab_product; ?></a></li>
            <li><a href="#tab-category" data-toggle="tab"><?php echo $tab_category; ?></a></li>
            <?php if ($coupon_id) { ?>
            <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
            <?php } ?>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                  <?php if ($error_name) { ?>
                  <div class="text-danger"><?php echo $error_name; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-code"><span data-toggle="tooltip" title="<?php echo $help_code; ?>"><?php echo $entry_code; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="code" value="<?php echo $code; ?>" placeholder="<?php echo $entry_code; ?>" id="input-code" class="form-control" />
                  <?php if ($error_code) { ?>
                  <div class="text-danger"><?php echo $error_code; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-type"><span data-toggle="tooltip" title="<?php echo $help_type; ?>"><?php echo $entry_type; ?></span></label>
                <div class="col-sm-10">
                  <select name="type" id="input-type" class="form-control">
                    <?php if ($type == 'P') { ?>
                    <option value="P" selected="selected"><?php echo $text_percent; ?></option>
                    <?php } else { ?>
                    <option value="P"><?php echo $text_percent; ?></option>
                    <?php } ?>
                    <?php if ($type == 'F') { ?>
                    <option value="F" selected="selected"><?php echo $text_amount; ?></option>
                    <?php } else { ?>
                    <option value="F"><?php echo $text_amount; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-discount"><?php echo $entry_discount; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="discount" value="<?php echo $discount; ?>" placeholder="<?php echo $entry_discount; ?>" id="input-discount" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="total" value="<?php echo $total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-max-total"><?php echo $entry_max_total; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="max_total" value="<?php echo $max_total; ?>" placeholder="<?php echo $entry_max_total; ?>" id="input-max-total" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_logged; ?>"><?php echo $entry_logged; ?></span></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($logged) { ?>
                    <input type="radio" name="logged" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="logged" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$logged) { ?>
                    <input type="radio" name="logged" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="logged" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_shipping; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($shipping) { ?>
                    <input type="radio" name="shipping" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="shipping" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$shipping) { ?>
                    <input type="radio" name="shipping" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="shipping" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="col-sm-3">
                  <div class="input-group date">
                    <input type="text" name="date_start" value="<?php echo $date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="col-sm-3">
                  <div class="input-group date">
                    <input type="text" name="date_end" value="<?php echo $date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-uses-total"><span data-toggle="tooltip" title="<?php echo $help_uses_total; ?>"><?php echo $entry_uses_total; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="uses_total" value="<?php echo $uses_total; ?>" placeholder="<?php echo $entry_uses_total; ?>" id="input-uses-total" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-uses-customer"><span data-toggle="tooltip" title="<?php echo $help_uses_customer; ?>"><?php echo $entry_uses_customer; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="uses_customer" value="<?php echo $uses_customer; ?>" placeholder="<?php echo $entry_uses_customer; ?>" id="input-uses-customer" class="form-control" />
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
            </div>
            <div class="tab-pane" id="tab-product">
              <div class="table-responsive">
                <table id="coupon-product" class="table table-striped table-bordered table-hover">
                  <thead>
                  <tr>
                    <td class="text-left"><?php echo $entry_product; ?></td>
                    <td class="text-right"><?php echo $entry_discount; ?></td>
                    <td class="text-right"><?php echo $entry_type; ?></td>
                    <td></td>
                  </tr>
                  </thead>
                  <tbody>
                  <?php $product_row = 0; ?>
                  <?php foreach ($coupon_products as $coupon_product) { ?>
                  <tr id="product-row<?php echo $product_row; ?>">
                    <td class="text-right">
                      <input type="text" name="coupon_product[<?php echo $product_row; ?>][product_name]" value="<?php echo $coupon_product['product_name']; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
                      <input type="hidden" name="coupon_product[<?php echo $product_row; ?>][product_id]" value="<?php echo $coupon_product['product_id']; ?>"/>
                    </td>
                    <td class="text-right"><input type="text" name="coupon_product[<?php echo $product_row; ?>][discount]" value="<?php echo $coupon_product['discount']; ?>" placeholder="<?php echo $entry_discount; ?>" class="form-control" /></td>
                    <td class="text-left">
                      <select name="coupon_product[<?php echo $product_row; ?>][type]"  class="form-control">
                        <?php if ($coupon_product['type'] == 'P') { ?>
                        <option value="P" selected="selected"><?php echo $text_percent; ?></option>
                        <?php } else { ?>
                        <option value="P"><?php echo $text_percent; ?></option>
                        <?php } ?>
                        <?php if ($coupon_product['type'] == 'F') { ?>
                        <option value="F" selected="selected"><?php echo $text_amount; ?></option>
                        <?php } else { ?>
                        <option value="F"><?php echo $text_amount; ?></option>
                        <?php } ?>
                      </select>
                    </td>
                    <td class="text-left"><button type="button" onclick="$('#product-row<?php echo $product_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                  </tr>
                  <?php $product_row++; ?>
                  <?php } ?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <td colspan="3"></td>
                    <td class="text-left"><button type="button" onclick="addProduct();" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                  </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="tab-category">
              <div class="table-responsive">
                <table id="coupon-category" class="table table-striped table-bordered table-hover">
                  <thead>
                  <tr>
                    <td class="text-left"><?php echo $entry_category; ?></td>
                    <td class="text-right"><?php echo $entry_discount; ?></td>
                    <td class="text-right"><?php echo $entry_type; ?></td>
                    <td></td>
                  </tr>
                  </thead>
                  <tbody>
                  <?php $category_row = 0; ?>
                  <?php foreach ($coupon_categories as $coupon_category) { ?>
                  <tr id="category-row<?php echo $category_row; ?>">
                    <td class="text-right">
                      <input type="text" name="coupon_category[<?php echo $category_row; ?>][category_name]" value="<?php echo $coupon_category['category_name']; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
                      <input type="hidden" name="coupon_category[<?php echo $category_row; ?>][category_id]" value="<?php echo $coupon_category['category_id']; ?>"/>
                    </td>
                    <td class="text-right"><input type="text" name="coupon_category[<?php echo $category_row; ?>][discount]" value="<?php echo $coupon_category['discount']; ?>" placeholder="<?php echo $entry_discount; ?>" class="form-control" /></td>
                    <td class="text-left">
                      <select name="coupon_category[<?php echo $category_row; ?>][type]"  class="form-control">
                        <?php if ($coupon_category['type'] == 'P') { ?>
                        <option value="P" selected="selected"><?php echo $text_percent; ?></option>
                        <?php } else { ?>
                        <option value="P"><?php echo $text_percent; ?></option>
                        <?php } ?>
                        <?php if ($coupon_category['type'] == 'F') { ?>
                        <option value="F" selected="selected"><?php echo $text_amount; ?></option>
                        <?php } else { ?>
                        <option value="F"><?php echo $text_amount; ?></option>
                        <?php } ?>
                      </select>
                    </td>
                    <td class="text-left"><button type="button" onclick="$('#category-row<?php echo $category_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                  </tr>
                  <?php $category_row++; ?>
                  <?php } ?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <td colspan="3"></td>
                    <td class="text-left"><button type="button" onclick="addCategory();" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                  </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <?php if ($coupon_id) { ?>
            <div class="tab-pane" id="tab-history">
              <div id="history"></div>
            </div>
            <?php } ?>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php if ($coupon_id) { ?>
  <script type="text/javascript"><!--
    $('#history').delegate('.pagination a', 'click', function(e) {
	  e.preventDefault();
	  $('#history').load(this.href);
    });			

    $('#history').load('index.php?route=marketing/coupon/history&token=<?php echo $token; ?>&coupon_id=<?php echo $coupon_id; ?>');
  //--></script>
  <?php } ?>
  <script type="text/javascript"><!--
      $('.date').datetimepicker({
          pickTime: false
      });
  //--></script>
</div>
<script type="text/javascript"><!--
    var product_row = <?php echo $product_row; ?>;

    function addProduct() {
        var html  = '<tr id="product-row' + product_row + '">';
        html += '  <td class="text-right"><input type="text" name="coupon_product[' + product_row + '][product_name]" value="" placeholder="<?php echo $entry_name; ?>" class="form-control" /><input type="hidden" name="coupon_product[' + product_row + '][product_id]" /></td>';
        html += '  <td class="text-right"><input type="text" name="coupon_product[' + product_row + '][discount]" value="" placeholder="<?php echo $entry_discount; ?>" class="form-control" /></td>';
        html += '  <td class="text-left"><select name="coupon_product[' + product_row + '][type]" class="form-control">';
        html += '   <option value="F"><?php echo $text_amount; ?></option>';
        html += '    <option value="P" ><?php echo $text_percent; ?></option>';
        html += '  </select></td>';
        html += '  <td class="text-left"><button type="button" onclick="$(\'#product-row' + product_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#coupon-product tbody').append(html);
        productAutoComplete(product_row)
        product_row++;
    }

    function productAutoComplete(product_row) {
        $('input[name=\'coupon_product[' + product_row + '][product_name]\']').autocomplete({
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
                $('input[name=\'coupon_product[' + product_row + '][product_name]\']').val(item['label']);
                $('input[name=\'coupon_product[' + product_row + '][product_id]\']').val(item['value']);
            }
        });
    }

    $('#coupon-product tbody tr').each(function(index, element) {
        productAutoComplete(index);
    });

    //--></script>
<script type="text/javascript"><!--
    var category_row = <?php echo $category_row; ?>;

    function addCategory() {
        var html  = '<tr id="category-row' + category_row + '">';
        html += '  <td class="text-right"><input type="text" name="coupon_category[' + category_row + '][category_name]" value="" placeholder="<?php echo $entry_name; ?>" class="form-control" /><input type="hidden" name="coupon_category[' + category_row + '][category_id]" /></td>';
        html += '  <td class="text-right"><input type="text" name="coupon_category[' + category_row + '][discount]" value="" placeholder="<?php echo $entry_discount; ?>" class="form-control" /></td>';
        html += '  <td class="text-left"><select name="coupon_category[' + category_row + '][type]" class="form-control">';
        html += '   <option value="F"><?php echo $text_amount; ?></option>';
        html += '    <option value="P" ><?php echo $text_percent; ?></option>';
        html += '  </select></td>';
        html += '  <td class="text-left"><button type="button" onclick="$(\'#category-row' + category_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#coupon-category tbody').append(html);
        categoryAutoComplete(category_row)
        category_row++;
    }

    function categoryAutoComplete(category_row) {
        $('input[name=\'coupon_category[' + category_row + '][category_name]\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['name'],
                                value: item['category_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'coupon_category[' + category_row + '][category_name]\']').val(item['label']);
                $('input[name=\'coupon_category[' + category_row + '][category_id]\']').val(item['value']);
            }
        });
    }

    $('#coupon-category tbody tr').each(function(index, element) {
        categoryAutoComplete(index);
    });

    //--></script>
<?php echo $footer; ?>