<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $sorter; ?>" data-toggle="tooltip" title="<?php echo $button_sort; ?>" class="btn btn-default"><i class="fa fa-list"></i> <?php echo $button_sort; ?></a>
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default" onclick="$('#form-article').submit()"><i class="fa fa-copy"></i></button>
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
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-aricle-id"><?php echo $entry_article_id; ?></label>
                <input type="text" name="filter_article_id" value="<?php echo $filter_article_id; ?>" placeholder="<?php echo $entry_article_id; ?>" id="input-article-id" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-customer-group"><?php echo $entry_category; ?></label>
                <select name="filter_category_id" id="input-customer-group" class="form-control">
                  <option value=""></option>
                  <?php foreach ($categories as $category) { ?>
                  <?php if ($category['category_id'] == $filter_category_id) { ?>
                  <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-user"><?php echo $entry_user; ?></label>
                <input type="hidden" name="filter_user_id" value="<?php echo $filter_user_id; ?>">
                <input type="text" value="<?php echo $filter_user; ?>" value="<?php echo $filter_user; ?>" placeholder="<?php echo $entry_user; ?>" id="input-user" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                    <label class="control-label" for="input-featured"><?php echo $entry_featured; ?></label>
                    <select name="filter_featured" id="input-featured" class="form-control">
                        <option value="*"></option>
                        <?php if ($filter_featured) { ?>
                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_yes; ?></option>
                        <?php } ?>
                        <?php if (!$filter_featured && !is_null($filter_featured)) { ?>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                        <?php } else { ?>
                        <option value="0"><?php echo $text_no; ?></option>
                        <?php } ?>
                    </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-date-from"><?php echo $entry_date_from; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_from" value="<?php echo $filter_date_from; ?>" placeholder="<?php echo $entry_date_from; ?>" data-date-format="YYYY-MM-DD" id="input-date-from" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-to"><?php echo $entry_date_to; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_to" value="<?php echo $filter_date_to; ?>" placeholder="<?php echo $entry_date_to; ?>" data-date-format="YYYY-MM-DD" id="input-date-to" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $copy; ?>" method="post" enctype="multipart/form-data" id="form-article">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'ad.name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'a.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?>
                  </td>
                  <td class="text-left"><?php echo $column_on_lead; ?></td>
                  <td class="text-left"><?php if ($sort == 'a.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_last_update_info; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_last_update_info; ?></a>
                    <?php } ?>
                  </td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($articles) { ?>
                <?php foreach ($articles as $article) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($article['article_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $article['article_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $article['article_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left">
                    <div class="headline">ID# <?php echo $article['article_id']; ?></div>
                    <div class="headline"><?php echo $article['name']; ?></div>
                    <div class="headline"><b><?php echo $column_viewed; ?>: </b><?php echo $article['viewed']; ?></div>
                  </td>
                  <td class="text-left"><?php echo $article['status']; ?></td>
                  <td class="text-left"><?php echo $article['on_lead']; ?></td>
                  <td class="text-left">
                    <div><?php echo $article['date_added']; ?></div>
                    <div><?php echo $column_user_created; ?>: <?php echo $article['user_created']; ?></div>
                    <?php if($article['date_modified']) { ?>
                    <div><?php echo $article['date_modified']; ?></div>
                    <div><?php echo $column_user_modified; ?>: <?php echo $article['user_modified']; ?></div>
                    <?php } ?>
                  </td>
                  <td class="text-right">
                    <a target="_blank" href="<?php echo $article['history']; ?>" data-toggle="tooltip" title="<?php echo $button_history; ?>" class="btn btn-primary"><i class="fa fa-clock-o"></i></a>
                    <a href="<?php echo $article['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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
	var url = 'index.php?route=blog/article&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_article_id = $('[name=\'filter_article_id\']').val();

	if (filter_article_id) {
		url += '&filter_article_id=' + encodeURIComponent(filter_article_id);
	}

	var filter_user_id = $('[name=\'filter_user_id\']').val();

	if (filter_user_id) {
		url += '&filter_user_id=' + encodeURIComponent(filter_user_id);
	}

	var filter_category_id = $('[name=\'filter_category_id\']').val();

	if (filter_category_id) {
		url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
	}

	var filter_date_from = $('[name=\'filter_date_from\']').val();

	if (filter_date_from) {
		url += '&filter_date_from=' + encodeURIComponent(filter_date_from);
	}

	var filter_date_to = $('[name=\'filter_date_to\']').val();

	if (filter_date_to) {
		url += '&filter_date_to=' + encodeURIComponent(filter_date_to);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	var filter_featured = $('select[name=\'filter_featured\']').val();

	if (filter_featured != '*') {
		url += '&filter_featured=' + encodeURIComponent(filter_featured);
	}

	location = url;
});
//--></script> 
  <script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=blog/article/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['article_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}
});

$('#input-user').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=user/user/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        json.unshift({
          user_id: 0,
          name: '<?php echo $text_none; ?>'
        });
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['user_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('#input-user').val(item['label'])
    $('input[name=\'filter_user_id\']').val(item['value']);
  }
});
//--></script>
  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
    $('.date').datetimepicker({
      pickTime: false
    });
  //--></script></div>
<?php echo $footer; ?>