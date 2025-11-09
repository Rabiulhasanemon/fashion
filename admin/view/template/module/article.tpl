<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-featured" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-featured" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_blurb; ?></label>
            <div class="col-sm-10">
              <input type="text" name="blurb" value="<?php echo $blurb; ?>" placeholder="<?php echo $entry_blurb; ?>" id="input-name" class="form-control" />
              <?php if ($error_blurb) { ?>
              <div class="text-danger"><?php echo $error_blurb; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-class"><?php echo $entry_class; ?></label>
            <div class="col-sm-10">
              <input type="text" name="class" value="<?php echo $class; ?>" placeholder="<?php echo $entry_class; ?>" id="input-class" class="form-control" />
              <?php if ($error_class) { ?>
              <div class="text-danger"><?php echo $error_class; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort"><?php echo $entry_sort; ?></label>
            <div class="col-sm-10">
              <select name="sort" id="input-sort" class="form-control">
                <?php if ($sort == 'custom') { ?>
                <option value="custom" selected="selected"><?php echo $text_custom; ?></option>
                <option value="date"><?php echo $text_date; ?></option>
                <?php } else { ?>
                <option value="custom"><?php echo $text_custom; ?></option>
                <option value="date" selected="selected"><?php echo $text_date; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_filter; ?></label>
            <div class="col-sm-10">
              <select name="filter" class="form-control" id="input-filter">
                <option value="latest" <?php echo $filter == "latest" ? "selected" : "" ?> ><?php echo $text_latest ?></option>
                <option value="featured" <?php echo $filter == "featured" ? "selected" : "" ?> ><?php echo $text_featured ?></option>
                <option value="popular" <?php echo $filter == "popular" ? "selected" : "" ?> ><?php echo $text_popular ?></option>
                <option value="selected" <?php echo $filter == "selected" ? "selected" : "" ?> ><?php echo $text_selected ?></option>
              </select>
            </div>
          </div>
          <div class="form-group selected-articles">
            <label class="col-sm-2 control-label" for="input-article"><span data-toggle="tooltip" title="<?php echo $help_article; ?>"><?php echo $entry_article; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="article_name" value="" placeholder="<?php echo $entry_article; ?>" id="input-article" class="form-control" />
              <div id="article" class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($articles as $article) { ?>
                <div id="article<?php echo $article['article_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $article['name']; ?>
                  <input type="hidden" name="article[]" value="<?php echo $article['article_id']; ?>" />
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="form-group category-group">
            <label class="col-sm-2 control-label" for="input-category"><?php echo $entry_category; ?></label>
            <div class="col-sm-10">
              <input type="text" name="category" value="<?php echo $category; ?>" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
              <input type="hidden" name="category_id" value="<?php echo $category_id; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-limit"><?php echo $entry_limit; ?></label>
            <div class="col-sm-10">
              <input type="text" name="limit" value="<?php echo $limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-featured-width"><?php echo $entry_featured_image; ?></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-sm-6">
                  <input type="text" name="featured_width" value="<?php echo $featured_width; ?>" placeholder="<?php echo $entry_featured_width; ?>" id="input-featured-width" class="form-control" />
                  <?php if ($error_featured_width) { ?>
                  <div class="text-danger"><?php echo $error_featured_width; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-6">
                  <input type="text" name="featured_height" value="<?php echo $featured_height; ?>" placeholder="<?php echo $entry_featured_height; ?>" id="input-height" class="form-control" />
                  <?php if ($error_featured_height) { ?>
                  <div class="text-danger"><?php echo $error_featured_height; ?></div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_image; ?></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-sm-6">
                  <input type="text" name="width" value="<?php echo $width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
                  <?php if ($error_width) { ?>
                  <div class="text-danger"><?php echo $error_width; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-6">
                  <input type="text" name="height" value="<?php echo $height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
                  <?php if ($error_height) { ?>
                  <div class="text-danger"><?php echo $error_height; ?></div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-title"><?php echo $entry_title; ?></label>
            <div class="col-sm-10">
              <select name="show_title" id="input-show_title" class="form-control">
                <?php if ($show_title) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-view"><?php echo $entry_view; ?></label>
            <div class="col-sm-10">
              <select name="view" id="input-view" class="form-control">
                <option value="awards" <?php if($view == "awards") { echo "selected"; } ?> >Awards</option>
                <option value="news" <?php if($view == "news") { echo "selected"; } ?> >News</option>
                <option value="grid" <?php if($view == "grid") { echo "selected"; } ?> ><?php echo $text_grid; ?></option>
                <option value="list" <?php if($view == "list") { echo "selected"; } ?>><?php echo $text_list; ?></option>
                <option value="single_featured_list" <?php if($view == "single_featured_list") { echo "selected"; } ?>><?php echo $text_single_featured_list; ?></option>
                <option value="single_featured_grid" <?php if($view == "single_featured_grid") { echo "selected"; } ?>><?php echo $text_single_featured_grid; ?></option>
                <option value="multi_featured_list" <?php if($view == "multi_featured_list") { echo "selected"; } ?>><?php echo $text_multi_featured_list; ?></option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-ads-position"><?php echo $entry_ads_position; ?></label>
            <div class="col-sm-10">
              <select name="ads_position_id" id="input-ads-position" class="form-control">
                <option value=""><?php echo $text_none; ?></option>
                <?php foreach ($ads_positions as $ads_position) { ?>
                <?php if ($ads_position['ads_position_id'] == $ads_position_id) { ?>
                <option value="<?php echo $ads_position['ads_position_id']; ?>" selected="selected"><?php echo $ads_position['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $ads_position['ads_position_id']; ?>"><?php echo $ads_position['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
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
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('input[name=\'article_name\']').autocomplete({
	source: function(request, response) {
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
	select: function(item) {
		$('input[name=\'article_name\']').val('');
		
		$('#article' + item['value']).remove();
		
		$('#article').append('<div id="article' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="article[]" value="' + item['value'] + '" /></div>');
	}
});


$('#article').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
$('input[name=\'category\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=blog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
        $('input[name=\'category\']').val(item['label']);
        $('input[name=\'category_id\']').val(item['value']);
    }
});
$('#input-filter').on("change", function() {
    var value = this.value;
  if( value === "latest" || value === "featured" || value === "popular") {
    $('.selected-articles').hide("slow")
    $('.category-group').show("slow")
  } else {
    $('.category-group').hide("slow")
    $('.selected-articles').show("slow")
  }
}).trigger("change");
//--></script></div>
<?php echo $footer; ?>
