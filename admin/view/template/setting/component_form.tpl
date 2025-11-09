<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-component" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-component" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if (isset($error_name)) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-parent"><?php echo $entry_depends_on; ?></label>
            <div class="col-sm-10">
              <input type="text" value="<?php echo $depends_on_label; ?>" placeholder="<?php echo $entry_depends_on; ?>" id="input-parent" class="form-control" />
              <input type="hidden" name="depends_on" value="<?php echo $depends_on; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-category"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
              <div id="component-category" class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($component_categories as $component_category) { ?>
                <div id="component-category<?php echo $component_category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $component_category['name']; ?>
                  <input type="hidden" name="component_category[]" value="<?php echo $component_category['category_id']; ?>" />
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-excluded-product"><?php echo $entry_excluded_product; ?></label>
            <div class="col-sm-10">
              <input type="text"  value="" placeholder="<?php echo $entry_excluded_product; ?>" id="input-excluded-product" class="form-control" />
              <div id="component-excluded-product" class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($excluded_products as $component_excluded_product) { ?>
                <div id="component-excluded-product<?php echo $component_excluded_product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $component_excluded_product['name']; ?>
                  <input type="hidden" name="component_excluded_product[]" value="<?php echo $component_excluded_product['product_id']; ?>" />
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_thumb; ?></label>
            <div class="col-sm-10">
                <a href="" id="thumb-preview" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb_preview; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                <input type="hidden" name="thumb" value="<?php echo $thumb; ?>" id="input-thumb" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-filter-profile"><?php echo $entry_filter_profile; ?></span></label>
            <div class="col-sm-10">
              <input type="hidden" name="filter_profile_id" value="<?php echo $filter_profile_id; ?>">
              <input type="text" value="<?php echo $filter_profile; ?>" placeholder="<?php echo $entry_filter_profile; ?>" id="input-filter-profile" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_is_required; ?></label>
            <div class="col-sm-10">
              <select name="is_required" id="input-status" class="form-control">
                <?php if ($is_required) { ?>
                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                <option value="0"><?php echo $text_no; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_yes; ?></option>
                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
//--></script>
  <script type="text/javascript"><!--
$('#input-parent').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=setting/component/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				json.unshift({
					component_id: 0,
					name: '<?php echo $text_none; ?>'
				});

				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['component_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('#input-parent').val(item['label']);
		$('input[name=\'depends_on\']').val(item['value']);
	}
});
//--></script> 
  <script type="text/javascript"><!--
$('input[name=\'category\']').autocomplete({
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
		$('input[name=\'category\']').val('');

		$('#component-category' + item['value']).remove();

		$('#component-category').append('<div id="component-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="component_category[]" value="' + item['value'] + '" /></div>');
	}
});

$('#component-category').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
//--></script>

<script type="text/javascript"><!--
    $('#input-excluded-product').autocomplete({
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
            $('#input-excluded-product').val('');

            $('#component-excluded-product' + item['value']).remove();

            $('#component-excluded-product').append('<div id="component-excluded-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="component_excluded_product[]" value="' + item['value'] + '" /></div>');
        }
    });

    $('#component-excluded-product').delegate('.fa-minus-circle', 'click', function() {
        $(this).parent().remove();
    });
    //--></script>
<script type="text/javascript"><!--
    $('#input-filter-profile').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/filter_profile/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    json.unshift({
                        filter_profile_id: 0,
                        name: '<?php echo $text_none; ?>'
                    });

                    response($.map(json, function(item) {
                        return {
                            label: item['name'],
                            value: item['filter_profile_id']
                        }
                    }));
                }
            });
        },
        'select': function(item) {
            $('#input-filter-profile').val(item['label']);
            $('input[name=\'filter_profile_id\']').val(item['value']);
        }
    });
    //--></script>

</div>
<?php echo $footer; ?>