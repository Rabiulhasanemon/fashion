<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-category" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-category" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
            <li><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
            <li><a href="#tab-modules" data-toggle="tab"><?php echo isset($tab_modules) ? $tab_modules : 'Modules'; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active in" id="tab-general">
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="category_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_name[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group ">
                    <label class="col-sm-2 control-label" for="input-blurb<?php echo $language['language_id']; ?>"><?php echo $entry_blurb; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="category_description[<?php echo $language['language_id']; ?>][blurb]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['blurb'] : ''; ?>" placeholder="<?php echo $entry_blurb; ?>" id="input-blurb<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_blurb[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_blurb[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-intro<?php echo $language['language_id']; ?>"><?php echo $entry_intro; ?></label>
                    <div class="col-sm-10">
                      <textarea name="category_description[<?php echo $language['language_id']; ?>][intro]" placeholder="<?php echo $entry_intro; ?>" id="input-intro<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['intro'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="category_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="category_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_meta_title[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="category_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                    <div class="col-sm-10">
                      <textarea name="category_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
            <div class="tab-pane fade" id="tab-data">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-parent"><?php echo $entry_parent; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="path" value="<?php echo $path; ?>" placeholder="<?php echo $entry_parent; ?>" id="input-parent" class="form-control" />
                  <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
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
                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <div class="checkbox">
                      <label>
                        <?php if (in_array(0, $category_store)) { ?>
                        <input type="checkbox" name="category_store[]" value="0" checked="checked" />
                        <?php echo $text_default; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="category_store[]" value="0" />
                        <?php echo $text_default; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php foreach ($stores as $store) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($store['store_id'], $category_store)) { ?>
                        <input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                        <?php echo $store['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" />
                        <?php echo $store['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><?php echo $entry_keyword; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
                  <?php if ($error_keyword) { ?>
                  <div class="text-danger"><?php echo $error_keyword; ?></div>
                  <?php } ?>                
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_icon; ?></label>
                <div class="col-sm-10">
                  <a href="" id="icon-preview" data-toggle="image" class="img-thumbnail"><img src="<?php echo $icon_preview; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="icon" value="<?php echo $icon; ?>" id="input-icon" />
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
                <label class="col-sm-2 control-label"><?php echo $entry_image; ?></label>
                <div class="col-sm-10"><a href="" id="image-preview" data-toggle="image" class="img-thumbnail"><img src="<?php echo $image_preview; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-top"><span data-toggle="tooltip" title="<?php echo $help_top; ?>"><?php echo $entry_top; ?></span></label>
                <div class="col-sm-10">
                  <div class="checkbox">
                    <label>
                      <?php if ($top) { ?>
                      <input type="checkbox" name="top" value="1" checked="checked" id="input-top" />
                      <?php } else { ?>
                      <input type="checkbox" name="top" value="1" id="input-top" />
                      <?php } ?>
                      &nbsp; </label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-column"><span data-toggle="tooltip" title="<?php echo $help_column; ?>"><?php echo $entry_column; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="column" value="<?php echo $column; ?>" placeholder="<?php echo $entry_column; ?>" id="input-column" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
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
            <div class="tab-pane" id="tab-design">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_store; ?></td>
                      <td class="text-left"><?php echo $entry_layout; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="text-left"><?php echo $text_default; ?></td>
                      <td class="text-left"><select name="category_layout[0]" class="form-control">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <?php if (isset($category_layout[0]) && $category_layout[0] == $layout['layout_id']) { ?>
                          <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <?php foreach ($stores as $store) { ?>
                    <tr>
                      <td class="text-left"><?php echo $store['name']; ?></td>
                      <td class="text-left"><select name="category_layout[<?php echo $store['store_id']; ?>]" class="form-control">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <?php if (isset($category_layout[$store['store_id']]) && $category_layout[$store['store_id']] == $layout['layout_id']) { ?>
                          <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-view"><?php echo $entry_view; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="view" value="<?php echo $view; ?>" placeholder="<?php echo $entry_view; ?>" id="input-view" class="form-control" />
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-modules">
              <div class="table-responsive">
                <table id="category-modules" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo isset($entry_module) ? $entry_module : 'Module'; ?></td>
                      <td class="text-left"><?php echo isset($entry_module_description) ? $entry_module_description : 'Description'; ?></td>
                      <td class="text-left"><?php echo $entry_sort_order; ?></td>
                      <td class="text-left"><?php echo $entry_status; ?></td>
                      <td class="text-right"><?php echo isset($button_remove) ? $button_remove : 'Remove'; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $module_row = 0; ?>
                    <?php if (isset($category_modules) && is_array($category_modules)) { ?>
                    <?php foreach ($category_modules as $category_module) { ?>
                    <tr id="module-row<?php echo $module_row; ?>">
                      <td class="text-left">
                        <select name="category_module[<?php echo $module_row; ?>][code]" class="form-control module-select" data-row="<?php echo $module_row; ?>">
                          <option value=""><?php echo $text_none; ?></option>
                          <?php if (isset($available_modules)) { ?>
                          <?php foreach ($available_modules as $module) { ?>
                          <?php 
                            $selected = false;
                            if (isset($category_module['module_id']) && $category_module['module_id'] > 0) {
                              $selected = ($module['module_id'] == $category_module['module_id']);
                            } else {
                              $selected = ($module['code'] == $category_module['code']);
                            }
                          ?>
                          <option value="<?php echo $module['code']; ?>" data-module-id="<?php echo $module['module_id']; ?>" <?php echo $selected ? 'selected="selected"' : ''; ?>><?php echo $module['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                        <input type="hidden" name="category_module[<?php echo $module_row; ?>][module_id]" class="module-id-input" value="<?php echo isset($category_module['module_id']) ? $category_module['module_id'] : 0; ?>" />
                      </td>
                      <td class="text-left">
                        <textarea name="category_module[<?php echo $module_row; ?>][description]" id="module-description-<?php echo $module_row; ?>" rows="3" class="form-control summernote" placeholder="Enter module description..."><?php echo isset($category_module['description']) ? htmlspecialchars($category_module['description']) : ''; ?></textarea>
                      </td>
                      <td class="text-left">
                        <input type="text" name="category_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo isset($category_module['sort_order']) ? $category_module['sort_order'] : 0; ?>" placeholder="0" class="form-control" />
                      </td>
                      <td class="text-left">
                        <select name="category_module[<?php echo $module_row; ?>][status]" class="form-control">
                          <?php if (isset($category_module['status']) && $category_module['status']) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                        </select>
                      </td>
                      <td class="text-right">
                        <button type="button" onclick="$('#module-row<?php echo $module_row; ?>').remove();" data-toggle="tooltip" title="<?php echo isset($button_remove) ? $button_remove : 'Remove'; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                      </td>
                    </tr>
                    <?php $module_row++; ?>
                    <?php } ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="4"></td>
                      <td class="text-right">
                        <button type="button" onclick="addModule();" data-toggle="tooltip" title="<?php echo isset($button_add_module) ? $button_add_module : 'Add Module'; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
$('#input-description<?php echo $language['language_id']; ?>').summernote({
	height: 300
});
      $('#input-intro<?php echo $language['language_id']; ?>').summernote({
        height: 100
      });
<?php } ?>
//--></script> 
  <script type="text/javascript"><!--
$('input[name=\'path\']').autocomplete({
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
		$('input[name=\'path\']').val(item['label']);
		$('input[name=\'parent_id\']').val(item['value']);
	}
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
  <script type="text/javascript"><!--
$('#language a:first').tab('show');

var module_row = <?php echo isset($module_row) ? $module_row : 0; ?>;

function addModule() {
	html  = '<tr id="module-row' + module_row + '">';
	html += '  <td class="text-left">';
	html += '    <select name="category_module[' + module_row + '][code]" class="form-control module-select" data-row="' + module_row + '">';
	html += '      <option value=""><?php echo $text_none; ?></option>';
	<?php if (isset($available_modules)) { ?>
	<?php foreach ($available_modules as $module) { ?>
	html += '      <option value="<?php echo $module['code']; ?>" data-module-id="<?php echo $module['module_id']; ?>"><?php echo addslashes($module['name']); ?></option>';
	<?php } ?>
	<?php } ?>
	html += '    </select>';
	html += '    <input type="hidden" name="category_module[' + module_row + '][module_id]" class="module-id-input" value="0" />';
	html += '  </td>';
	html += '  <td class="text-left">';
	html += '    <textarea name="category_module[' + module_row + '][description]" id="module-description-' + module_row + '" rows="3" class="form-control summernote" placeholder="Enter module description..."></textarea>';
	html += '  </td>';
	html += '  <td class="text-left">';
	html += '    <input type="text" name="category_module[' + module_row + '][sort_order]" value="0" placeholder="0" class="form-control" />';
	html += '  </td>';
	html += '  <td class="text-left">';
	html += '    <select name="category_module[' + module_row + '][status]" class="form-control">';
	html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
	html += '      <option value="0"><?php echo $text_disabled; ?></option>';
	html += '    </select>';
	html += '  </td>';
	html += '  <td class="text-right">';
	html += '    <button type="button" onclick="$(\'#module-row' + module_row + '\').remove();" data-toggle="tooltip" title="<?php echo isset($button_remove) ? addslashes($button_remove) : "Remove"; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>';
	html += '  </td>';
	html += '</tr>';

	$('#category-modules tbody').append(html);

	// Initialize Summernote for the newly added description field
	$('#module-description-' + (module_row - 1)).summernote({
		height: 150
	});

	module_row++;
}

// Update module_id when module selection changes
$(document).on('change', '.module-select', function() {
	var row = $(this).data('row');
	var selectedOption = $(this).find('option:selected');
	var moduleId = selectedOption.data('module-id') || 0;
	$('input[name="category_module[' + row + '][module_id]"]').val(moduleId);
});

// Initialize Summernote for existing module description fields
$(document).ready(function() {
	$('textarea.summernote').each(function() {
		if (!$(this).next('.note-editor').length) {
			$(this).summernote({
				height: 150
			});
		}
	});
});

// Sync Summernote content before form submission
$('#form-category').on('submit', function() {
	$('textarea.summernote').each(function() {
		if ($(this).next('.note-editor').length) {
			$(this).val($(this).summernote('code'));
		}
	});
});
//--></script></div>
<?php echo $footer; ?>