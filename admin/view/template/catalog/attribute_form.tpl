<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-attribute" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-attribute" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_group; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="attribute_group_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($attribute_group_description[$language['language_id']]) ? $attribute_group_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_group; ?>" class="form-control" />
              </div>
              <?php if (isset($error_group[$language['language_id']])) { ?>
              <div class="text-danger"><?php echo $error_group[$language['language_id']]; ?></div>
              <?php } ?>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-profile"><?php echo $entry_profile; ?></label>
            <div class="col-sm-10">
              <input type="text" value="<?php echo $attribute_profile; ?>" placeholder="<?php echo $entry_profile; ?>" id="input-profile" class="form-control" />
              <input type="hidden" name="attribute_profile_id" value="<?php echo $attribute_profile_id; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
          <table id="attribute" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left required"><?php echo $entry_name ?></td>
                <td class="text-right"><?php echo $entry_sort_order; ?></td>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php $attribute_row = 0; ?>
              <?php foreach ($attributes as $attribute) { ?>
              <tr id="attribute-row<?php echo $attribute_row; ?>">
                <td class="text-left" style="width: 70%;"><input type="hidden" name="attribute[<?php echo $attribute_row; ?>][attribute_id]" value="<?php echo $attribute['attribute_id']; ?>" />
                  <?php foreach ($languages as $language) { ?>
                  <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                    <input type="text" name="attribute[<?php echo $attribute_row; ?>][attribute_description][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($attribute['attribute_description'][$language['language_id']]) ? $attribute['attribute_description'][$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name ?>" class="form-control" />
                  </div>
                  <?php if (isset($error_attribute[$attribute_row][$language['language_id']])) { ?>
                  <div class="text-danger"><?php echo $error_attribute[$attribute_row][$language['language_id']]; ?></div>
                  <?php } ?>
                  <?php } ?></td>
                <td class="text-right"><input type="text" name="attribute[<?php echo $attribute_row; ?>][sort_order]" value="<?php echo $attribute['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" /></td>
                <td class="text-left"><button type="button" onclick="$('#attribute-row<?php echo $attribute_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
              </tr>
              <?php $attribute_row++; ?>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="2"></td>
                <td class="text-left"><a onclick="addAttributeRow();" data-toggle="tooltip" title="<?php echo $button_attribute_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></a></td>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
var attribute_row = <?php echo $attribute_row; ?>;

function addAttributeRow() {
	html  = '<tr id="attribute-row' + attribute_row + '">';	
    html += '  <td class="text-left" style="width: 70%;"><input type="hidden" name="attribute[' + attribute_row + '][attribute_id]" value="" />';
	<?php foreach ($languages as $language) { ?>
	html += '  <div class="input-group">';
	html += '    <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span><input type="text" name="attribute[' + attribute_row + '][attribute_description][<?php echo $language['language_id']; ?>][name]" value="" placeholder="<?php echo $entry_name ?>" class="form-control" />';
    html += '  </div>';
	<?php } ?>
	html += '  </td>';
	html += '  <td class="text-right"><input type="text" name="attribute[' + attribute_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#attribute-row' + attribute_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';	
	
	$('#attribute tbody').append(html);
	
	attribute_row++;
}
//--></script>
  <script type="text/javascript"><!--
      $('#input-profile').autocomplete({
          'source': function(request, response) {
              $.ajax({
                  url: 'index.php?route=catalog/attribute_profile/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                  dataType: 'json',
                  success: function(json) {
                      json.unshift({
                          attribute_profile_id: 0,
                          name: '<?php echo $text_none; ?>'
                      });

                      response($.map(json, function(item) {
                          return {
                              label: item['name'],
                              value: item['attribute_profile_id']
                          }
                      }));
                  }
              });
          },
          'select': function(item) {
              $('#input-profile').val(item['label']);
              $('input[name=\'attribute_profile_id\']').val(item['value']);
          }
      });
      //--></script> </div>
<?php echo $footer; ?> 