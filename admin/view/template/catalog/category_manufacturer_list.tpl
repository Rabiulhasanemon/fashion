<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-category-manufacturer').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
                <label class="control-label" for="input-category-name"><?php echo $entry_category; ?></label>
                <input type="text" name="filter_category_name" value="<?php echo $filter_category_name; ?>" placeholder="<?php echo $entry_category; ?>" id="input-category-name" class="form-control" />
                <input type="hidden" name="filter_category_id" value="<?php echo $filter_category_id; ?>" id="input-category-id">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-manufacturer-name"><?php echo $entry_manufacturer; ?></label>
                <input type="text" name="filter_manufacturer_name" value="<?php echo $filter_manufacturer_name; ?>" placeholder="<?php echo $entry_manufacturer; ?>" id="input-manufacturer-name" class="form-control" />
                <input type="hidden" name="filter_manufacturer_id" value="<?php echo $filter_manufacturer_id; ?>" id="input-manufacturer-id">
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-category-manufacturer">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'category') { ?>
                    <a href="<?php echo $sort_category; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_category; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_category; ?>"><?php echo $column_category; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'manufacturer') { ?>
                    <a href="<?php echo $sort_manufacturer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_manufacturer; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_manufacturer; ?>"><?php echo $column_manufacturer; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($category_manufacturers) { ?>
                <?php foreach ($category_manufacturers as $category_manufacturer) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($category_manufacturer['category_manufacturer_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $category_manufacturer['category_manufacturer_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $category_manufacturer['category_manufacturer_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $category_manufacturer['category']; ?></td>
                  <td class="text-left"><?php echo $category_manufacturer['manufacturer']; ?></td>
                  <td class="text-right"><a href="<?php echo $category_manufacturer['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
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
</div>
<script type="text/javascript"><!--
    $('#button-filter').on('click', function() {
        var url = 'index.php?route=catalog/category_manufacturer&token=<?php echo $token; ?>';

        var filter_category_id = $('input[name=\'filter_category_id\']').val();

        if (filter_category_id && filter_category_id != 0) {
            url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
        }

        var filter_manufacturer_id = $('input[name=\'filter_manufacturer_id\']').val();

        if (filter_manufacturer_id && filter_manufacturer_id != 0) {
            url += '&filter_manufacturer_id=' + encodeURIComponent(filter_manufacturer_id);
        }
        
        location = url;
    });
    //--></script>
<script type="text/javascript"><!--
    $('input[name=\'filter_category_name\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    json.unshift({
                        category_id: 0,
                        name: '<?php echo $text_none; ?>'
                    });
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
            $('input[name=\'filter_category_name\']').val(item['label']);
            $('input[name=\'filter_category_id\']').val(item['value']);

        }
    });
    $('input[name=\'filter_manufacturer_name\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    json.unshift({
                        manufacturer_id: 0,
                        name: '<?php echo $text_none; ?>'
                    });
                    response($.map(json, function(item) {
                        return {
                            label: item['name'],
                            value: item['manufacturer_id']
                        }
                    }));
                }
            });
        },
        'select': function(item) {
            $('input[name=\'filter_manufacturer_name\']').val(item['label']);
            $('input[name=\'filter_manufacturer_id\']').val(item['value']);

        }
    });
    //--></script>
<?php echo $footer; ?>