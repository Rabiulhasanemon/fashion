<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-article" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-article" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
            <li><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
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
                      <input type="text" name="article_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($article_description[$language['language_id']]) ? $article_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_name[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-headline_for_details<?php echo $language['language_id']; ?>"><?php echo $entry_headline_for_details; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="article_description[<?php echo $language['language_id']; ?>][headline_for_details]" value="<?php echo isset($article_description[$language['language_id']]) ? $article_description[$language['language_id']]['headline_for_details'] : ''; ?>" placeholder="<?php echo $entry_headline_for_details; ?>" id="input-headline_for_details<?php echo $language['language_id']; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-upazila"><?php echo $entry_upazila; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="upazila" value="<?php echo $upazila; ?>" placeholder="<?php echo $entry_upazila; ?>" id="input-upazila" class="form-control" />
                      <input type="hidden" name="upazila_id" value="<?php echo $upazila_id; ?>" />
                      <?php if ($error_upazila) { ?>
                      <div class="text-danger"><?php echo $error_upazila; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-shoulder<?php echo $language['language_id']; ?>"><?php echo $entry_shoulder; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="article_description[<?php echo $language['language_id']; ?>][shoulder]" value="<?php echo isset($article_description[$language['language_id']]) ? $article_description[$language['language_id']]['shoulder'] : ''; ?>" placeholder="<?php echo $entry_shoulder; ?>" id="input-shoulder<?php echo $language['language_id']; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-hanger<?php echo $language['language_id']; ?>"><?php echo $entry_hanger; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="article_description[<?php echo $language['language_id']; ?>][hanger]" value="<?php echo isset($article_description[$language['language_id']]) ? $article_description[$language['language_id']]['hanger'] : ''; ?>" placeholder="<?php echo $entry_hanger; ?>" id="input-hanger<?php echo $language['language_id']; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-reporter<?php echo $language['language_id']; ?>"><?php echo $entry_reporter; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="article_description[<?php echo $language['language_id']; ?>][reporter]" value="<?php echo isset($article_description[$language['language_id']]) ? $article_description[$language['language_id']]['reporter'] : ''; ?>" placeholder="<?php echo $entry_reporter; ?>" id="input-reporter<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_reporter[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_reporter[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-intro-text<?php echo $language['language_id']; ?>"><?php echo $entry_intro_text; ?></label>
                    <div class="col-sm-10">
                      <textarea name="article_description[<?php echo $language['language_id']; ?>][intro_text]" placeholder="<?php echo $entry_intro_text; ?>" id="input-intro-text<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($article_description[$language['language_id']]) ? $article_description[$language['language_id']]['intro_text'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="article_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($article_description[$language['language_id']]) ? $article_description[$language['language_id']]['description'] : ''; ?></textarea>
                    </div>
                  </div>

                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="article_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($article_description[$language['language_id']]) ? $article_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_meta_title[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="article_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($article_description[$language['language_id']]) ? $article_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                      <?php if (isset($error_meta_description[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_meta_description[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-tags<?php echo $language['language_id']; ?>"><?php echo $entry_tags; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="article_description[<?php echo $language['language_id']; ?>][tags]" value="<?php echo isset($article_description[$language['language_id']]) ? $article_description[$language['language_id']]['tags'] : ''; ?>" placeholder="<?php echo $entry_tags; ?>" id="input-tags<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_tags[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_tags[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                    <div class="col-sm-10">
                      <textarea name="article_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($article_description[$language['language_id']]) ? $article_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                      <?php if (isset($error_meta_keyword[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_meta_keyword[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>

            </div>
            <div class="tab-pane" id="tab-data">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-parent"><?php echo $entry_parent; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="path" value="<?php echo $path; ?>" placeholder="<?php echo $entry_parent; ?>" id="input-parent" class="form-control" />
                  <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
                  <?php if ($error_parent) { ?>
                  <div class="text-danger"><?php echo $error_parent; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-category"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
                  <div id="article-category" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($article_categories as $article_category) { ?>
                    <div id="article-category<?php echo $article_category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $article_category['name']; ?>
                      <input type="hidden" name="article_category[]" value="<?php echo $article_category['category_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-event-stream"><?php echo $entry_event_stream; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="event_stream" value="" placeholder="<?php echo $entry_event_stream; ?>" id="input-event-stream" class="form-control" />
                  <div id="article-event-stream" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($article_event_streams as $article_event_stream) { ?>
                    <div id="article-event_stream<?php echo $article_event_stream['event_stream_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $article_event_stream['name']; ?>
                      <input type="hidden" name="article_event_stream[]" value="<?php echo $article_event_stream['event_stream_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group hide">
                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <div class="checkbox">
                      <label>
                        <?php if (in_array(0, $article_store)) { ?>
                        <input type="checkbox" name="article_store[]" value="0" checked="checked" />
                        <?php echo $text_default; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="article_store[]" value="0" />
                        <?php echo $text_default; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php foreach ($stores as $store) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($store['store_id'], $article_store)) { ?>
                        <input type="checkbox" name="article_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                        <?php echo $store['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="article_store[]" value="<?php echo $store['store_id']; ?>" />
                        <?php echo $store['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-show-in-headline"><?php echo $entry_show_in_headline; ?></label>
                <div class="col-sm-10">
                  <select name="show_in_headline" id="input-show-in-headline" class="form-control">
                    <?php if ($show_in_headline) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-on-lead"><?php echo $entry_on_lead; ?></label>
                <div class="col-sm-10">
                  <select name="on_lead" id="input-on-lead" class="form-control">
                    <option value="none" <?php if($on_lead == "none") { echo "selected"; } ?>><?php echo $text_none; ?></option>
                    <option value="lead" <?php if($on_lead == "lead") { echo "selected"; } ?>><?php echo $text_lead_news; ?></option>
                    <option value="featured" <?php if($on_lead == "featured") { echo "selected"; } ?>><?php echo $text_show_news; ?></option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="status" id="input-status" class="form-control">
                    <?php if ($status) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_thumb; ?></label>
                <div class="col-sm-10"><a href="" id="thumb-thumb" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb_thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="thumb" value="<?php echo $thumb; ?>" id="input-thumb" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_image; ?></label>
                <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $image_thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-video-url"><?php echo $entry_video_url; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="video_url" value="<?php echo $video_url; ?>" placeholder="<?php echo $entry_video_url; ?>" id="input-video-url" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-video-icon"><?php echo $entry_video_icon; ?></label>
                <div class="col-sm-10">
                  <select name="video_icon" id="input-video-icon" class="form-control">
                    <?php if ($video_icon) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-keyword"><?php echo $entry_keyword; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
                  <?php if ($error_keyword) { ?>
                  <div class="text-danger"><?php echo $error_keyword; ?></div>
                  <?php } ?>
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
                    <td class="text-left"><select name="article_layout[0]" class="form-control">
                        <option value=""></option>
                        <?php foreach ($layouts as $layout) { ?>
                        <?php if (isset($article_layout[0]) && $article_layout[0] == $layout['layout_id']) { ?>
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
                    <td class="text-left"><select name="article_layout[<?php echo $store['store_id']; ?>]" class="form-control">
                        <option value=""></option>
                        <?php foreach ($layouts as $layout) { ?>
                        <?php if (isset($article_layout[$store['store_id']]) && $article_layout[$store['store_id']] == $layout['layout_id']) { ?>
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
        $('#input-intro-text<?php echo $language['language_id']; ?>').summernote({
              height: 300
          });
      <?php } ?>
  //--></script>
  <script type="text/javascript"><!--
    $('#language a:first').tab('show');
    $('#option a:first').tab('show');
    $('input[name=\'category\']').autocomplete({
      'source': function(request, response) {
        $.ajax({
          url: 'index.php?route=blog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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

        $('#article-category' + item['value']).remove();

        $('#article-category').append('<div id="article-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="article_category[]" value="' + item['value'] + '" /></div>');
      }
    });
    $('#article-category').delegate('.fa-minus-circle', 'click', function() {
      $(this).parent().remove();
    });
    
    $('input[name=\'event_stream\']').autocomplete({
      'source': function(request, response) {
        $.ajax({
          url: 'index.php?route=blog/event_stream/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
          dataType: 'json',
          success: function(json) {
            response($.map(json, function(item) {
              return {
                label: item['name'],
                value: item['event_stream_id']
              }
            }));
          }
        });
      },
      'select': function(item) {
        $('input[name=\'event_stream\']').val('');

        $('#article-event-stream' + item['value']).remove();

        $('#article-event-stream').append('<div id="article-event-stream' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="article_event_stream[]" value="' + item['value'] + '" /></div>');
      }
    });

    $('#article-event-stream').delegate('.fa-minus-circle', 'click', function() {
      $(this).parent().remove();
    });

    $('input[name=\'path\']').autocomplete({
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
              $('input[name=\'path\']').val(item['label']);
              $('input[name=\'parent_id\']').val(item['value']);
          }
      });

    $('input[name=\'upazila\']').autocomplete({
          'source': function(request, response) {
              $.ajax({
                  url: 'index.php?route=localisation/upazila/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                  dataType: 'json',
                  success: function(json) {
                      json.unshift({
                          upazila_id: 0,
                          name: '<?php echo $text_none; ?>'
                      });

                      response($.map(json, function(item) {
                          return {
                              label: item['name'],
                              value: item['upazila_id']
                          }
                      }));
                  }
              });
          },
          'select': function(item) {
              $('input[name=\'upazila\']').val(item['label']);
              $('input[name=\'upazila_id\']').val(item['value']);
          }
      });
//--></script>

  <script type="text/javascript">
    $('#language a:first').tab('show');
    $('#input-name<?php echo $language['language_id']; ?>').on('input', function() {

      var keywordInput = $('#input-keyword');
      if (keywordInput.val() === '') {
        var title = $(this).val();
        var slug = title.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        keywordInput.val(slug);
      }
    });
    </script>
</div>
<?php echo $footer; ?>