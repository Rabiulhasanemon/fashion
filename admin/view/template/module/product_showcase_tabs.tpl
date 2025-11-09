<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-product-showcase-tabs" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product-showcase-tabs" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-limit"><?php echo $entry_limit; ?></label>
            <div class="col-sm-10">
              <input type="text" name="limit" value="<?php echo $limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
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
            <label class="col-sm-2 control-label">Tabs</label>
            <div class="col-sm-10">
              <button type="button" onclick="addTab();" class="btn btn-success"><i class="fa fa-plus-circle"></i> <?php echo $button_add_tab; ?></button>
            </div>
          </div>

          <div id="tab-container">
            <?php $tab_row = 0; ?>
            <?php foreach ($tabs as $tab) { ?>
            <div class="panel panel-default tab-row" id="tab-row<?php echo $tab_row; ?>">
              <div class="panel-heading">
                <h4 class="panel-title">Tab #<?php echo ($tab_row + 1); ?> <button type="button" onclick="$(this).closest('.tab-row').remove();" class="btn btn-danger btn-xs pull-right"><i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?></button></h4>
              </div>
              <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-tab-title<?php echo $tab_row; ?>"><?php echo $entry_tab_title; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="tabs[<?php echo $tab_row; ?>][tab_title]" value="<?php echo $tab['tab_title']; ?>" placeholder="<?php echo $entry_tab_title; ?>" id="input-tab-title<?php echo $tab_row; ?>" class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-selection-type<?php echo $tab_row; ?>"><?php echo $entry_selection_type; ?></label>
                  <div class="col-sm-10">
                    <select name="tabs[<?php echo $tab_row; ?>][selection_type]" id="input-selection-type<?php echo $tab_row; ?>" class="form-control selection-type-toggle">
                      <option value="category" <?php echo ($tab['selection_type'] == 'category') ? 'selected' : ''; ?>><?php echo $text_by_category; ?></option>
                      <option value="product" <?php echo ($tab['selection_type'] == 'product') ? 'selected' : ''; ?>><?php echo $text_by_product; ?></option>
                    </select>
                  </div>
                </div>
                <div class="form-group category-group" style="display: <?php echo ($tab['selection_type'] == 'category') ? 'block' : 'none'; ?>;">
                  <label class="col-sm-2 control-label" for="input-category<?php echo $tab_row; ?>"><?php echo $entry_category; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="tabs[<?php echo $tab_row; ?>][category][]" value="" placeholder="<?php echo $entry_category; ?>" id="input-category<?php echo $tab_row; ?>" class="form-control" />
                    <div id="category-list<?php echo $tab_row; ?>" class="well well-sm" style="height: 150px; overflow: auto;">
                      <?php if (isset($tab['category'])) { ?>
                        <?php foreach ($tab['category'] as $category) { ?>
                        <div id="category<?php echo $tab_row; ?>-<?php echo $category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $category['name']; ?>
                          <input type="hidden" name="tabs[<?php echo $tab_row; ?>][category][]" value="<?php echo $category['category_id']; ?>" />
                        </div>
                        <?php } ?>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <div class="form-group product-group" style="display: <?php echo ($tab['selection_type'] == 'product') ? 'block' : 'none'; ?>;">
                  <label class="col-sm-2 control-label" for="input-product<?php echo $tab_row; ?>"><?php echo $entry_product; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="tabs[<?php echo $tab_row; ?>][product][]" value="" placeholder="<?php echo $entry_product; ?>" id="input-product<?php echo $tab_row; ?>" class="form-control" />
                    <div id="product-list<?php echo $tab_row; ?>" class="well well-sm" style="height: 150px; overflow: auto;">
                      <?php if (isset($tab['product'])) { ?>
                        <?php foreach ($tab['product'] as $product) { ?>
                        <div id="product<?php echo $tab_row; ?>-<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
                          <input type="hidden" name="tabs[<?php echo $tab_row; ?>][product][]" value="<?php echo $product['product_id']; ?>" />
                        </div>
                        <?php } ?>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-sort-order<?php echo $tab_row; ?>"><?php echo $entry_sort_order; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="tabs[<?php echo $tab_row; ?>][sort_order]" value="<?php echo $tab['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order<?php echo $tab_row; ?>" class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-tab-status<?php echo $tab_row; ?>"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="tabs[<?php echo $tab_row; ?>][status]" id="input-tab-status<?php echo $tab_row; ?>" class="form-control">
                      <option value="1" <?php echo ($tab['status']) ? 'selected' : ''; ?>><?php echo $text_enabled; ?></option>
                      <option value="0" <?php echo (!$tab['status']) ? 'selected' : ''; ?>><?php echo $text_disabled; ?></option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <?php $tab_row++; ?>
            <?php } ?>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
var tab_row = <?php echo $tab_row; ?>;

function addTab() {
  html  = '<div class="panel panel-default tab-row" id="tab-row' + tab_row + '">';
  html += '  <div class="panel-heading">';
  html += '    <h4 class="panel-title">Tab #' + (tab_row + 1) + ' <button type="button" onclick="$(this).closest(\'.tab-row\').remove();" class="btn btn-danger btn-xs pull-right"><i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?></button></h4>';
  html += '  </div>';
  html += '  <div class="panel-body">';
  html += '    <div class="form-group">';
  html += '      <label class="col-sm-2 control-label" for="input-tab-title' + tab_row + '"><?php echo $entry_tab_title; ?></label>';
  html += '      <div class="col-sm-10">';
  html += '        <input type="text" name="tabs[' + tab_row + '][tab_title]" value="" placeholder="<?php echo $entry_tab_title; ?>" id="input-tab-title' + tab_row + '" class="form-control" />';
  html += '      </div>';
  html += '    </div>';
  html += '    <div class="form-group">';
  html += '      <label class="col-sm-2 control-label" for="input-selection-type' + tab_row + '"><?php echo $entry_selection_type; ?></label>';
  html += '      <div class="col-sm-10">';
  html += '        <select name="tabs[' + tab_row + '][selection_type]" id="input-selection-type' + tab_row + '" class="form-control selection-type-toggle">';
  html += '          <option value="category" selected><?php echo $text_by_category; ?></option>';
  html += '          <option value="product"><?php echo $text_by_product; ?></option>';
  html += '        </select>';
  html += '      </div>';
  html += '    </div>';
  html += '    <div class="form-group category-group">';
  html += '      <label class="col-sm-2 control-label" for="input-category' + tab_row + '"><?php echo $entry_category; ?></label>';
  html += '      <div class="col-sm-10">';
  html += '        <input type="text" name="tabs[' + tab_row + '][category][]" value="" placeholder="<?php echo $entry_category; ?>" id="input-category' + tab_row + '" class="form-control" />';
  html += '        <div id="category-list' + tab_row + '" class="well well-sm" style="height: 150px; overflow: auto;"></div>';
  html += '      </div>';
  html += '    </div>';
  html += '    <div class="form-group product-group" style="display: none;">';
  html += '      <label class="col-sm-2 control-label" for="input-product' + tab_row + '"><?php echo $entry_product; ?></label>';
  html += '      <div class="col-sm-10">';
  html += '        <input type="text" name="tabs[' + tab_row + '][product][]" value="" placeholder="<?php echo $entry_product; ?>" id="input-product' + tab_row + '" class="form-control" />';
  html += '        <div id="product-list' + tab_row + '" class="well well-sm" style="height: 150px; overflow: auto;"></div>';
  html += '      </div>';
  html += '    </div>';
  html += '    <div class="form-group">';
  html += '      <label class="col-sm-2 control-label" for="input-sort-order' + tab_row + '"><?php echo $entry_sort_order; ?></label>';
  html += '      <div class="col-sm-10">';
  html += '        <input type="text" name="tabs[' + tab_row + '][sort_order]" value="0" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order' + tab_row + '" class="form-control" />';
  html += '      </div>';
  html += '    </div>';
  html += '    <div class="form-group">';
  html += '      <label class="col-sm-2 control-label" for="input-tab-status' + tab_row + '"><?php echo $entry_status; ?></label>';
  html += '      <div class="col-sm-10">';
  html += '        <select name="tabs[' + tab_row + '][status]" id="input-tab-status' + tab_row + '" class="form-control">';
  html += '          <option value="1" selected><?php echo $text_enabled; ?></option>';
  html += '          <option value="0"><?php echo $text_disabled; ?></option>';
  html += '        </select>';
  html += '      </div>';
  html += '    </div>';
  html += '  </div>';
  html += '</div>';

  $('#tab-container').append(html);

  bindAutocomplete(tab_row);
  bindSelectionTypeToggle(tab_row);

  tab_row++;
}

function bindAutocomplete(index) {
  // Category autocomplete
  $('#input-category' + index).autocomplete({
    source: function(request, response) {
      $.ajax({
        url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term || request),
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
    minLength: 0,
    select: function(item) {
      $('#input-category' + index).val('');
      
      $('#category' + index + '-' + item['value']).remove();
      
      $('#category-list' + index).append('<div id="category' + index + '-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="tabs[' + index + '][category][]" value="' + item['value'] + '" /></div>');
    }
  });

  $('#category-list' + index).on('click', '.fa-minus-circle', function() {
    $(this).parent().remove();
  });

  // Product autocomplete
  $('#input-product' + index).autocomplete({
    source: function(request, response) {
      $.ajax({
        url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term || request),
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
      $('#input-product' + index).val('');
      
      $('#product' + index + '-' + item['value']).remove();
      
      $('#product-list' + index).append('<div id="product' + index + '-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="tabs[' + index + '][product][]" value="' + item['value'] + '" /></div>');
    }
  });

  $('#product-list' + index).on('click', '.fa-minus-circle', function() {
    $(this).parent().remove();
  });
}

function bindSelectionTypeToggle(index) {
  $('#input-selection-type' + index).on('change', function() {
    var panel = $(this).closest('.panel-body');
    if ($(this).val() == 'category') {
      panel.find('.category-group').show();
      panel.find('.product-group').hide();
    } else {
      panel.find('.category-group').hide();
      panel.find('.product-group').show();
    }
  });
}

// Bind existing tabs when document is ready
$(document).ready(function() {
  // Bind existing tabs
  <?php $tab_row = 0; ?>
  <?php foreach ($tabs as $tab) { ?>
  bindAutocomplete(<?php echo $tab_row; ?>);
  bindSelectionTypeToggle(<?php echo $tab_row; ?>);
  <?php $tab_row++; ?>
  <?php } ?>

  // Bind selection type toggle for existing tabs
  $(document).on('change', '.selection-type-toggle', function() {
    var panel = $(this).closest('.panel-body');
    if ($(this).val() == 'category') {
      panel.find('.category-group').show();
      panel.find('.product-group').hide();
    } else {
      panel.find('.category-group').hide();
      panel.find('.product-group').show();
    }
  });
});
//--></script>

<?php echo $footer; ?>
