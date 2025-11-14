<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-review-view" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-review-view" class="form-horizontal">
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
            <label class="col-sm-2 control-label" for="input-title"><?php echo $entry_title; ?></label>
            <div class="col-sm-10">
              <input type="text" name="title" value="<?php echo isset($title) ? $title : ''; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_reviews; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="max-height: 400px; overflow: auto;">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 30px;">Select</th>
                      <th>Review</th>
                      <th>Author Image</th>
                      <th>Designation</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($reviews as $review) { 
                      $is_selected = in_array($review['review_id'], $review_ids);
                      $custom_data = isset($review_custom_data) && isset($review_custom_data[$review['review_id']]) ? $review_custom_data[$review['review_id']] : array();
                      $author_image = isset($custom_data['author_image']) ? $custom_data['author_image'] : '';
                      $designation = isset($custom_data['designation']) ? $custom_data['designation'] : '';
                    ?>
                    <tr>
                      <td>
                        <input type="checkbox" name="review_ids[]" value="<?php echo $review['review_id']; ?>" <?php echo $is_selected ? 'checked="checked"' : ''; ?> />
                      </td>
                      <td>
                        <strong><?php echo $review['author']; ?></strong><br>
                        <small><?php echo $review['product']; ?> (<?php echo $review['rating']; ?>★) - <?php echo $review['date_added']; ?></small>
                      </td>
                      <td>
                        <a href="" id="thumb-image-<?php echo $review['review_id']; ?>" data-toggle="image" class="img-thumbnail">
                          <img src="<?php echo !empty($author_image) ? '../image/' . $author_image : '../image/no_image.png'; ?>" alt="" title="" data-placeholder="../image/no_image.png" />
                        </a>
                        <input type="hidden" name="review_custom_data[<?php echo $review['review_id']; ?>][author_image]" value="<?php echo $author_image; ?>" id="input-image-<?php echo $review['review_id']; ?>" />
                      </td>
                      <td>
                        <input type="text" name="review_custom_data[<?php echo $review['review_id']; ?>][designation]" value="<?php echo htmlspecialchars($designation); ?>" placeholder="e.g., Housewife, Banker, Student" class="form-control" />
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <a onclick="$(this).closest('.form-group').find(':checkbox').prop('checked', true);">Select All</a> / <a onclick="$(this).closest('.form-group').find(':checkbox').prop('checked', false);">Unselect All</a>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-limit"><?php echo $entry_limit; ?></label>
            <div class="col-sm-10">
              <input type="text" name="limit" value="<?php echo $limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-layout"><?php echo $entry_layout; ?></label>
            <div class="col-sm-10">
              <select name="layout" id="input-layout" class="form-control">
                <option value="grid" <?php echo ($layout == 'grid') ? 'selected="selected"' : ''; ?>>Grid</option>
                <option value="list" <?php echo ($layout == 'list') ? 'selected="selected"' : ''; ?>>List</option>
                <option value="slider" <?php echo ($layout == 'slider') ? 'selected="selected"' : ''; ?>>Slider</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_show_rating; ?></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($show_rating) { ?>
                <input type="radio" name="show_rating" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="show_rating" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$show_rating) { ?>
                <input type="radio" name="show_rating" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="show_rating" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_show_date; ?></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($show_date) { ?>
                <input type="radio" name="show_date" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="show_date" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$show_date) { ?>
                <input type="radio" name="show_date" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="show_date" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_show_product; ?></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($show_product) { ?>
                <input type="radio" name="show_product" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="show_product" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$show_product) { ?>
                <input type="radio" name="show_product" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="show_product" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
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
</div>
<?php echo $footer; ?>

