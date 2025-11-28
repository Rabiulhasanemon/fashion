<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
            <li><a href="#tab-links" data-toggle="tab"><?php echo $tab_links; ?></a></li>
            <li><a href="#tab-attribute" data-toggle="tab"><?php echo $tab_attribute; ?></a></li>
            <li><a href="#tab-filter" data-toggle="tab"><?php echo $tab_filter; ?></a></li>
            <li><a href="#tab-option" data-toggle="tab"><?php echo $tab_option; ?></a></li>
            <li><a href="#tab-discount" data-toggle="tab"><?php echo $tab_discount; ?></a></li>
            <li><a href="#tab-special" data-toggle="tab"><?php echo $tab_special; ?></a></li>
            <li><a href="#tab-image" data-toggle="tab"><?php echo $tab_image; ?></a></li>
            <li><a href="#tab-reward" data-toggle="tab"><?php echo $tab_reward; ?></a></li>
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
                      <input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_name[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-sub-name<?php echo $language['language_id']; ?>"><?php echo $entry_sub_name; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="product_description[<?php echo $language['language_id']; ?>][sub_name]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['sub_name'] : ''; ?>" placeholder="<?php echo $entry_sub_name; ?>" id="input-sub-name<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_sub_name[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_sub_name[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-short-description<?php echo $language['language_id']; ?>"><?php echo $entry_short_description; ?></label>
                    <div class="col-sm-10">
                      <textarea class="form-control" rows="6" name="product_description[<?php echo $language['language_id']; ?>][short_description]" placeholder="<?php echo $entry_short_description; ?>" id="input-short-description<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['short_description'] : ''; ?></textarea>
                      <?php if (isset($error_short_description[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_short_description[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-video-url<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_video_url; ?>"><?php echo $entry_video_url; ?></span></label>
                    <div class="col-sm-10">
                      <input type="text" name="product_description[<?php echo $language['language_id']; ?>][video_url]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['video_url'] : ''; ?>" placeholder="https://www.youtube.com/watch?v=..." id="input-video-url<?php echo $language['language_id']; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="product_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="product_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_meta_title[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="product_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                    <div class="col-sm-10">
                      <textarea name="product_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-tag<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_tag; ?>"><?php echo $entry_tag; ?></span></label>
                    <div class="col-sm-10">
                      <input type="text" name="product_description[<?php echo $language['language_id']; ?>][tag]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['tag'] : ''; ?>" placeholder="<?php echo $entry_tag; ?>" id="input-tag<?php echo $language['language_id']; ?>" class="form-control" />
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
            <div class="tab-pane" id="tab-data">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
                <div class="col-sm-10">
                  <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-featured-image"><?php echo $entry_featured_image; ?></label>
                <div class="col-sm-10">
                  <a href="" id="featured-thumb" data-toggle="image" class="img-thumbnail"><img src="<?php echo $featured_thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="featured_image" value="<?php echo $featured_image; ?>" id="input-featured-image" />
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-model"><?php echo $entry_model; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="model" value="<?php echo $model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
                  <?php if ($error_model) { ?>
                  <div class="text-danger"><?php echo $error_model; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sku"><span data-toggle="tooltip" title="<?php echo $help_sku; ?>"><?php echo $entry_sku; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="sku" value="<?php echo $sku; ?>" placeholder="<?php echo $entry_sku; ?>" id="input-sku" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-mpn"><span data-toggle="tooltip" title="<?php echo $help_mpn; ?>"><?php echo $entry_mpn; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="mpn" value="<?php echo $mpn; ?>" placeholder="<?php echo $entry_mpn; ?>" id="input-mpn" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-short-note"><?php echo $entry_short_note; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="short_note" value="<?php echo $short_note; ?>" placeholder="<?php echo $entry_short_note; ?>" id="input-short-note" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-price"><?php echo $entry_price; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="price" value="<?php echo $price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-regular-price"><?php echo $entry_regular_price; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="regular_price" value="<?php echo $regular_price; ?>" placeholder="<?php echo $entry_regular_price; ?>" id="input-regular-price" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
                <div class="col-sm-10">
                  <select name="tax_class_id" id="input-tax-class" class="form-control">
                    <option value="0"><?php echo $text_none; ?></option>
                    <?php foreach ($tax_classes as $tax_class) { ?>
                    <?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
                    <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="quantity" value="<?php echo $quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-cost-price"><?php echo $entry_cost_price; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="cost_price" value="<?php echo $cost_price; ?>" placeholder="<?php echo $entry_cost_price; ?>" id="input-cost-price" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-minimum"><span data-toggle="tooltip" title="<?php echo $help_minimum; ?>"><?php echo $entry_minimum; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="minimum" value="<?php echo $minimum; ?>" placeholder="<?php echo $entry_minimum; ?>" id="input-minimum" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-maximum"><span data-toggle="tooltip" title="<?php echo $help_maximum; ?>"><?php echo $entry_maximum; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="maximum" value="<?php echo $maximum; ?>" placeholder="<?php echo $entry_maximum; ?>" id="input-maximum" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-subtract"><?php echo $entry_subtract; ?></label>
                <div class="col-sm-10">
                  <select name="subtract" id="input-subtract" class="form-control">
                    <?php if ($subtract) { ?>
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
                <label class="col-sm-2 control-label" for="input-stock-status"><span data-toggle="tooltip" title="<?php echo $help_stock_status; ?>"><?php echo $entry_stock_status; ?></span></label>
                <div class="col-sm-10">
                  <select name="stock_status_id" id="input-stock-status" class="form-control">
                    <?php foreach ($stock_statuses as $stock_status) { ?>
                    <?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?>
                    <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
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
                <label class="col-sm-2 control-label"><?php echo $entry_emi; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($emi) { ?>
                    <input type="radio" name="emi" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="emi" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$emi) { ?>
                    <input type="radio" name="emi" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="emi" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
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
                <label class="col-sm-2 control-label" for="input-date-available"><?php echo $entry_date_available; ?></label>
                <div class="col-sm-3">
                  <div class="input-group date">
                    <input type="text" name="date_available" value="<?php echo $date_available; ?>" placeholder="<?php echo $entry_date_available; ?>" data-date-format="YYYY-MM-DD" id="input-date-available" class="form-control" />
                    <span class="input-group-btn">
                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-length"><?php echo $entry_dimension; ?></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-4">
                      <input type="text" name="length" value="<?php echo $length; ?>" placeholder="<?php echo $entry_length; ?>" id="input-length" class="form-control" />
                    </div>
                    <div class="col-sm-4">
                      <input type="text" name="width" value="<?php echo $width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
                    </div>
                    <div class="col-sm-4">
                      <input type="text" name="height" value="<?php echo $height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-length-class"><?php echo $entry_length_class; ?></label>
                <div class="col-sm-10">
                  <select name="length_class_id" id="input-length-class" class="form-control">
                    <?php foreach ($length_classes as $length_class) { ?>
                    <?php if ($length_class['length_class_id'] == $length_class_id) { ?>
                    <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-weight"><?php echo $entry_weight; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="weight" value="<?php echo $weight; ?>" placeholder="<?php echo $entry_weight; ?>" id="input-weight" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-weight-class"><?php echo $entry_weight_class; ?></label>
                <div class="col-sm-10">
                  <select name="weight_class_id" id="input-weight-class" class="form-control">
                    <?php foreach ($weight_classes as $weight_class) { ?>
                    <?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
                    <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
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
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-links">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-manufacturer"><span data-toggle="tooltip" title="<?php echo $help_manufacturer; ?>"><?php echo $entry_manufacturer; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="manufacturer" value="<?php echo $manufacturer ?>" placeholder="<?php echo $entry_manufacturer; ?>" id="input-manufacturer" class="form-control" />
                  <input type="hidden" name="manufacturer_id" value="<?php echo $manufacturer_id; ?>" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-is-manufacturer-is-parent"><?php echo $entry_is_manufacturer_is_parent; ?></label>
                <div class="col-sm-10">
                  <div class="checkbox">
                    <label>
                      <?php if ($is_manufacturer_is_parent) { ?>
                      <input type="checkbox" name="is_manufacturer_is_parent" value="1" checked="checked" id="input-is-manufacturer-is-parent" />
                      <?php } else { ?>
                      <input type="checkbox" name="is_manufacturer_is_parent" value="1" id="input-is-manufacturer-is-parent" />
                      <?php } ?>
                      &nbsp;</label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-parent"><?php echo $entry_parent; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="path" value="<?php echo $path; ?>" placeholder="<?php echo $entry_parent; ?>" id="input-parent" class="form-control" />
                  <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-category"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
                  <div id="product-category" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($product_categories as $product_category) { ?>
                    <div id="product-category<?php echo $product_category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_category['name']; ?>
                      <input type="hidden" name="product_category[]" value="<?php echo $product_category['category_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <div class="checkbox">
                      <label>
                        <?php if (in_array(0, $product_store)) { ?>
                        <input type="checkbox" name="product_store[]" value="0" checked="checked" />
                        <?php echo $text_default; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="product_store[]" value="0" />
                        <?php echo $text_default; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php foreach ($stores as $store) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($store['store_id'], $product_store)) { ?>
                        <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                        <?php echo $store['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" />
                        <?php echo $store['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-download"><span data-toggle="tooltip" title="<?php echo $help_download; ?>"><?php echo $entry_download; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="download" value="" placeholder="<?php echo $entry_download; ?>" id="input-download" class="form-control" />
                  <div id="product-download" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($product_downloads as $product_download) { ?>
                    <div id="product-download<?php echo $product_download['download_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_download['name']; ?>
                      <input type="hidden" name="product_download[]" value="<?php echo $product_download['download_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-related"><span data-toggle="tooltip" title="<?php echo $help_related; ?>"><?php echo $entry_related; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="related" value="" placeholder="<?php echo $entry_related; ?>" id="input-related" class="form-control" />
                  <div id="product-related" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($product_relateds as $product_related) { ?>
                    <div id="product-related<?php echo $product_related['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_related['name']; ?>
                      <input type="hidden" name="product_related[]" value="<?php echo $product_related['product_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-compatible"><?php echo $entry_compatible; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="compatible" value="" placeholder="<?php echo $entry_compatible; ?>" id="input-compatible" class="form-control" />
                  <div id="product-compatible" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($product_compatibles as $product_compatible) { ?>
                    <div id="product-compatible<?php echo $product_compatible['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_compatible['name']; ?>
                      <input type="hidden" name="product_compatible[]" value="<?php echo $product_compatible['product_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-filter">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-profile"><?php echo $entry_filter_profile; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="filter_profile" value="" placeholder="<?php echo $entry_filter_profile; ?>" id="input-filter-profile" class="form-control" />
                  <div id="product-filter-profile" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($product_filter_profiles as $product_filter_profile) { ?>
                    <div id="product-filter-profile<?php echo $product_filter_profile['filter_profile_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_filter_profile['name']; ?>
                      <input type="hidden" name="product_filter_profile[]" value="<?php echo $product_filter_profile['filter_profile_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <?php echo $filter; ?>
            </div>
            <div class="tab-pane" id="tab-attribute">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-attribute-profile"><?php echo $entry_attribute_profile; ?></label>
                <div class="col-sm-10">
                  <input type="text" value="<?php echo $attribute_profile; ?>" placeholder="<?php echo $entry_attribute_profile; ?>" id="input-attribute-profile" class="form-control" />
                  <input type="hidden" name="attribute_profile_id" value="<?php echo $attribute_profile_id; ?>" />
                </div>
              </div>
              <?php echo $attribute; ?>
            </div>
            <div class="tab-pane" id="tab-option">
              <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_option; ?></h3></div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table id="product-option" class="table table-striped table-bordered table-hover">
                      <thead>
                      <tr>
                        <th class="text-left" style="width: 20%"><?php echo $entry_option; ?></th>
                        <th class="text-right"><?php echo $entry_option_value; ?></th>
                        <th style="width: 10%"></th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php $product_option_row = 0; ?>
                      <?php foreach($product_options as $product_option) { ?>

                      <tr id="product-option-row<?php echo $product_option_row; ?>">
                        <td class="text-right option-col">
                          <input type="text" value="<?php echo $product_option['name']; ?>" placeholder="<?php echo $entry_option; ?>" class="form-control option" />
                          <input type="hidden" name="product_option[<?php echo $product_option_row; ?>][option_id]" value="<?php echo $product_option['option_id']; ?>">
                          <input type="hidden" name="product_option[<?php echo $product_option_row; ?>][product_option_id]" value="<?php echo $product_option['product_option_id']; ?>">
                        </td>
                        <td class="text-left option-value-col">
                          <?php foreach($product_option['product_option_value'] as $i => $product_option_value) { ?>
                          <div class="option-checkbox">
                            <label class="form-check-label" for="option-value-<?php echo $product_option_value['option_value_id']; ?>"><?php echo $product_option_value['name']; ?></label>
                            <input class="form-check-input option" type="checkbox" <?php echo $product_option_value['selected'] ? "checked" : ''; ?> name='product_option[<?php echo $product_option_row; ?>][product_option_value][<?php echo $i; ?>][option_value_id]' value="<?php echo $product_option_value['option_value_id']; ?>" id="option-value-<?php echo $product_option_value['option_value_id']; ?>">
                            <input class="form-check-input custom-checkbox" type="checkbox" <?php echo $product_option_value['show'] ? "checked" : ''; ?> name='product_option[<?php echo $product_option_row; ?>][product_option_value][<?php echo $i; ?>][show]' value="1" >
                          </div>
                          <?php } ?>
                        </td>
                        <td class="text-left"><button type="button" onclick="$('#product-option-row<?php echo $product_option_row; ?>').remove(); prepareVarationTable();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                      </tr>
                      <?php $product_option_row++ ; ?>
                      <?php } ?>
                      </tbody>
                      <tfoot>
                      <tr>
                        <td colspan="2"></td>
                        <td class="text-left"><button type="button" onclick="addProductOption();" data-toggle="tooltip" title="<?php echo $button_option_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                      </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_variation; ?></h3></div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table id="product-variation" class="table table-striped table-bordered table-hover">
                      <thead></thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-discount">
              <div class="table-responsive">
                <table id="discount" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_customer_group; ?></td>
                      <td class="text-right"><?php echo $entry_quantity; ?></td>
                      <td class="text-right"><?php echo $entry_priority; ?></td>
                      <td class="text-right"><?php echo $entry_price; ?></td>
                      <td class="text-left"><?php echo $entry_date_start; ?></td>
                      <td class="text-left"><?php echo $entry_date_end; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $discount_row = 0; ?>
                    <?php foreach ($product_discounts as $product_discount) { ?>
                    <tr id="discount-row<?php echo $discount_row; ?>">
                      <td class="text-left"><select name="product_discount[<?php echo $discount_row; ?>][customer_group_id]" class="form-control">
                          <?php foreach ($customer_groups as $customer_group) { ?>
                          <?php if ($customer_group['customer_group_id'] == $product_discount['customer_group_id']) { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                      <td class="text-right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>
                      <td class="text-right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][priority]" value="<?php echo $product_discount['priority']; ?>" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>
                      <td class="text-right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][price]" value="<?php echo $product_discount['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>
                      <td class="text-left" style="width: 20%;"><div class="input-group datetime">
                          <input type="text" name="product_discount[<?php echo $discount_row; ?>][date_start]" value="<?php echo $product_discount['date_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD HH:mm" class="form-control" />
                          <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span></div></td>
                      <td class="text-left" style="width: 20%;"><div class="input-group datetime">
                          <input type="text" name="product_discount[<?php echo $discount_row; ?>][date_end]" value="<?php echo $product_discount['date_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD HH:mm" class="form-control" />
                          <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span></div></td>
                      <td class="text-left"><button type="button" onclick="$('#discount-row<?php echo $discount_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $discount_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="6"></td>
                      <td class="text-left"><button type="button" onclick="addDiscount();" data-toggle="tooltip" title="<?php echo $button_discount_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="tab-special">
              <div class="table-responsive">
                <table id="special" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_customer_group; ?></td>
                      <td class="text-right"><?php echo $entry_priority; ?></td>
                      <td class="text-right"><?php echo $entry_price; ?></td>
                      <td class="text-left"><?php echo $entry_date_start; ?></td>
                      <td class="text-left"><?php echo $entry_date_end; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $special_row = 0; ?>
                    <?php foreach ($product_specials as $product_special) { ?>
                    <tr id="special-row<?php echo $special_row; ?>">
                      <td class="text-left"><select name="product_special[<?php echo $special_row; ?>][customer_group_id]" class="form-control">
                          <?php foreach ($customer_groups as $customer_group) { ?>
                          <?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                      <td class="text-right"><input type="text" name="product_special[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>
                      <td class="text-right"><input type="text" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>
                      <td class="text-left" style="width: 20%;"><div class="input-group datetime">
                          <input type="text" name="product_special[<?php echo $special_row; ?>][date_start]" value="<?php echo $product_special['date_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD HH:mm" class="form-control" />
                          <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span></div></td>
                      <td class="text-left" style="width: 20%;"><div class="input-group datetime">
                          <input type="text" name="product_special[<?php echo $special_row; ?>][date_end]" value="<?php echo $product_special['date_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD HH:mm" class="form-control" />
                          <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span></div></td>
                      <td class="text-left"><button type="button" onclick="$('#special-row<?php echo $special_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $special_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="5"></td>
                      <td class="text-left"><button type="button" onclick="addSpecial();" data-toggle="tooltip" title="<?php echo $button_special_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="tab-image">
              <div class="table-responsive">
                <table id="images" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_image; ?></td>
                      <td class="text-right"><?php echo $entry_sort_order; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $image_row = 0; ?>
                    <?php foreach ($product_images as $product_image) { ?>
                    <tr id="image-row<?php echo $image_row; ?>">
                      <td class="text-left"><a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $product_image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="product_image[<?php echo $image_row; ?>][image]" value="<?php echo $product_image['image']; ?>" id="input-image<?php echo $image_row; ?>" /></td>
                      <td class="text-right"><input type="text" name="product_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $product_image['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
                      <td class="text-left"><button type="button" onclick="$('#image-row<?php echo $image_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $image_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="2"></td>
                      <td class="text-left"><button type="button" onclick="addImage();" data-toggle="tooltip" title="<?php echo $button_image_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="tab-reward">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-points"><span data-toggle="tooltip" title="<?php echo $help_points; ?>"><?php echo $entry_points; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="points" value="<?php echo $points; ?>" placeholder="<?php echo $entry_points; ?>" id="input-points" class="form-control" />
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_customer_group; ?></td>
                      <td class="text-right"><?php echo $entry_reward; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <tr>
                      <td class="text-left"><?php echo $customer_group['name']; ?></td>
                      <td class="text-right"><input type="text" name="product_reward[<?php echo $customer_group['customer_group_id']; ?>][points]" value="<?php echo isset($product_reward[$customer_group['customer_group_id']]) ? $product_reward[$customer_group['customer_group_id']]['points'] : ''; ?>" class="form-control" /></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
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
                      <td class="text-left"><select name="product_layout[0]" class="form-control">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <?php if (isset($product_layout[0]) && $product_layout[0] == $layout['layout_id']) { ?>
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
                      <td class="text-left"><select name="product_layout[<?php echo $store['store_id']; ?>]" class="form-control">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <?php if (isset($product_layout[$store['store_id']]) && $product_layout[$store['store_id']] == $layout['layout_id']) { ?>
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
$('#input-description<?php echo $language['language_id']; ?>').summernote({height: 300});
<?php } ?>
//--></script> 
  <script type="text/javascript"><!--
// Manufacturer
$('input[name=\'manufacturer\']').autocomplete({
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
		$('input[name=\'manufacturer\']').val(item['label']);
		$('input[name=\'manufacturer_id\']').val(item['value']);
	}	
});

// Category
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
		
		$('#product-category' + item['value']).remove();
		
		$('#product-category').append('<div id="product-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_category[]" value="' + item['value'] + '" /></div>');	
	}
});

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

$('#product-category').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
    

// Downloads
$('input[name=\'download\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/download/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['download_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'download\']').val('');
		
		$('#product-download' + item['value']).remove();
		
		$('#product-download').append('<div id="product-download' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_download[]" value="' + item['value'] + '" /></div>');	
	}	
});

$('#product-download').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

// Related
$('input[name=\'related\']').autocomplete({
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
		$('input[name=\'related\']').val('');
		
		$('#product-related' + item['value']).remove();
		
		$('#product-related').append('<div id="product-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_related[]" value="' + item['value'] + '" /></div>');	
	}	
});

$('#product-related').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

// Compatible
$('input[name=\'compatible\']').autocomplete({
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
        $('input[name=\'compatible\']').val('');

        $('#product-compatible' + item['value']).remove();

        $('#product-compatible').append('<div id="product-compatible' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_compatible[]" value="' + item['value'] + '" /></div>');
    }
});

$('#product-compatible').delegate('.fa-minus-circle', 'click', function() {
    $(this).parent().remove();
});
//--></script>

  <script type="text/javascript"><!--
    var product_option_row = <?php echo $product_option_row; ?>;

    function addProductOption() {
      var html  = '<tr id="product-option-row' + product_option_row + '">';
      html += '  <td class="text-right option-col"><input type="text" value="" placeholder="<?php echo $entry_option; ?>" class="form-control option" />' +
              '   <input type="hidden" name="product_option[' + product_option_row + '][product_option_id]">' +
              '   <input type="hidden" name="product_option[' + product_option_row + '][option_id]" class="option-id"></td>';
      html += '  <td class="text-left option-value-col"></td>';
      html += '  <td class="text-left"><button type="button" onclick="$(\'#product-option-row' + product_option_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
      html += '</tr>';

      html = $(html)
      $('#product-option tbody').append(html);
      attachOptionRowEvent(html, product_option_row)
      product_option_row++;
    }
    function attachOptionRowEvent(html, row) {
      html.find('.option').autocomplete({
        'source': function(request, response) {
          $.ajax({
            url: 'index.php?route=catalog/option/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
              response($.map(json, function(item) {
                return {
                  category: item['category'],
                  label: item['name'],
                  value: item['option_id'],
                  type: item['type'],
                  option_value: item['option_value']
                }
              }));
            }
          });
        },
        'select': function(item) {
          var $this = $(this);
          $this.val(item['label']);
          $this.siblings("input[type=hidden].option-id").val(item['value'])
          var optionHtml = ''
          item['option_value'].forEach(function (value, i) {
            optionHtml += `<div class="option-checkbox">
                <label class="form-check-label" for="option-value-${value['option_value_id']}">${value['name']}</label>
                <input class="form-check-input option" type="checkbox" name='product_option[${row}][product_option_value][${i}][option_value_id]' value="${value['option_value_id']}" id="option-value-${value['option_value_id']}">
                <input class="form-check-input custom-checkbox" type="checkbox" name='product_option[${row}][product_option_value][${i}][show]' value="1" checked>
        </div>`
          })
          html.find(".option-value-col").html(optionHtml)
        }
      });
    }
    $("table#product-option tbody tr").each(function (i, elm) {
      attachOptionRowEvent($(elm), i)
    })

    var variations = JSON.parse('<?php echo json_encode($product_variations); ?>')

    function prepareVarationTable() {
      var options = [], optionsValuesList = [];

      var existingVariations = {};
      $("#product-variation tbody tr").each(function () {
        var key = $(this).find("input[name$='[key]']").val();
        existingVariations[key] = {
          price_prefix: $(this).find("select[name$='[price_prefix]']").val(),
          price: $(this).find("input[name$='[price]']").val(),
          quantity: $(this).find("input[name$='[quantity]']").val(),
          sku: $(this).find("input[name$='[sku]']").val(),
          image: $(this).find("input[name$='[image]']").val(),
          thumb: $(this).find("img").attr('src')
        };
      });

      $("#product-option .option-col").each(function () {
        var $this = $(this), optionId = $this.find("[type=hidden]").val();

        options.push({
          option_id: optionId,
          name: $this.find("[type=text]").val()
        })
      })

      $("#product-option .option-value-col").each(function(i) {
        var $this = $(this), optionsValues = [];
        $this.find(".option-checkbox:has(.option:checked)").each(function() {
          var checkbox = $(this);
          optionsValues.push({
            option_value_id: checkbox.find("input").val(),
            name: checkbox.find("label").text(),
            option_id: options[i].option_id
          })
        })
        optionsValuesList.push(optionsValues)
      })

      function combinator(arr) {
        if (arr.length === 0) return [[]];
        let res = [], [first, ...rest] = arr;
        let remaining = combinator(rest);
        first.forEach(e => {
          remaining.forEach(smaller => {
            res.push([e].concat(smaller));
          });
        });
        return res;
      }

      var filteredOptionValueList = optionsValuesList.filter(item => item.length > 0)
      var combinations = combinator(filteredOptionValueList)

      if(!filteredOptionValueList.length) {
        $("#product-variation thead").html('');
        $("#product-variation tbody").html('');
        return;
      }
      var variation_row = 0;

      var header = '<tr>';
      options.forEach(function(option, i) {
        if(!optionsValuesList[i].length) return;
        header += '<td class="text-left">' + option.name + '</td>'
      })
      header += '<td class="text-left"><?php echo $entry_price_prefix; ?></td><td class="text-left"><?php echo $entry_price; ?></td><td class="text-left"><?php echo $entry_quantity; ?></td><td class="text-left"><?php echo $entry_sku; ?></td> <td class="text-left"><?php echo $entry_image; ?></td>'
      header += '</tr>'

      $("#product-variation thead").html(header);

      var body = '';
      combinations.forEach(function(combination) {
        body  += '<tr id="variation-row' + variation_row + '">';
        options.forEach(function(option) {
          var optionValue = combination.find(function(ov) {
            return option.option_id === ov.option_id
          })
          if(!optionValue) return;
          body += '<td>' + optionValue.name + '</td>';

        });
        var key = combination.map(function (params) {
          return Number.parseInt(params.option_value_id)
        }).sort().join("-")

        var variation = existingVariations[key] ||  variations.find(function (item) {
          return item.key === key
        }) || {};

        body += '  <td class="text-right"><input type="hidden" name="product_variation[' + variation_row + '][key]" value="'+ key + '"  class="form-control" /> ';
        body += '  <select  name="product_variation[' + variation_row + '][price_prefix]" value="" class="form-control">';

        if(variation.price_prefix === '-') {
          body += '    <option value="+">+</option>';
          body += '    <option value="-" selected>-</option>';
        } else {
          body += '    <option value="+" selected>+</option>';
          body += '    <option value="-">-</option>';
        }

        body += '  </select> </td>';

        body += '  <td class="text-right"><input type="text" name="product_variation[' + variation_row + '][price]" value="' + (variation.price || "") + '" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>';
        body += '  <td class="text-right"><input type="text" name="product_variation[' + variation_row + '][quantity]" value="' + (variation.quantity || "") + '" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>';
        body += '  <td class="text-right"><input type="text" name="product_variation[' + variation_row + '][sku]" value="' + (variation.sku || "") + '" placeholder="<?php echo $entry_sku; ?>" class="form-control" /></td>';
        body += '  <td class="text-left">' +
                '<a href="" id="variation-image' + variation_row + '" data-toggle="image" class="img-thumbnail">' +
                '<img src="' + (variation.thumb || '<?php echo $placeholder; ?>') + '" alt="" title="" data-placeholder="' + (variation.thumb || '<?php echo $placeholder; ?>') + '" />' +
                '<input type="hidden" name="product_variation[' + variation_row + '][image]" value="' + (variation.image || '' ) + '" id="input-variation-image' + variation_row + '" />' +
                '</a></td>';
        variation_row++;
      })

      $("#product-variation tbody").html(body);

    }

    prepareVarationTable()
    $('#product-option').on("change", function () {
      prepareVarationTable()
    })
    //--></script>
  <script type="text/javascript"><!--
var discount_row = <?php echo $discount_row; ?>;

function addDiscount() {
	html  = '<tr id="discount-row' + discount_row + '">'; 
    html += '  <td class="text-left"><select name="product_discount[' + discount_row + '][customer_group_id]" class="form-control">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
    <?php } ?>
    html += '  </select></td>';		
    html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][quantity]" value="" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>';
    html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>';
	html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>';
    html += '  <td class="text-left"><div class="input-group datetime"><input type="text" name="product_discount[' + discount_row + '][date_start]" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD HH:mm" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
	html += '  <td class="text-left"><div class="input-group datetime"><input type="text" name="product_discount[' + discount_row + '][date_end]" value="" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD HH:mm" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#discount-row' + discount_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

  $('#discount tbody').append(html);

  $('.datetime').datetimepicker({
    pickDate: true,
    pickTime: true
  });
	
	discount_row++;
}
//--></script> 
  <script type="text/javascript"><!--
var special_row = <?php echo $special_row; ?>;

function addSpecial() {
	html  = '<tr id="special-row' + special_row + '">'; 
    html += '  <td class="text-left"><select name="product_special[' + special_row + '][customer_group_id]" class="form-control">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
    <?php } ?>
    html += '  </select></td>';		
    html += '  <td class="text-right"><input type="text" name="product_special[' + special_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>';
	html += '  <td class="text-right"><input type="text" name="product_special[' + special_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>';
    html += '  <td class="text-left" style="width: 20%;"><div class="input-group datetime"><input type="text" name="product_special[' + special_row + '][date_start]" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD HH:mm" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
	html += '  <td class="text-left" style="width: 20%;"><div class="input-group datetime"><input type="text" name="product_special[' + special_row + '][date_end]" value="" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD HH:mm" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#special-row' + special_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

  $('#special tbody').append(html);

  $('.datetime').datetimepicker({
    pickDate: true,
    pickTime: true
  });


  special_row++;
}
//--></script> 
  <script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
	html  = '<tr id="image-row' + image_row + '">';
	html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /><input type="hidden" name="product_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
	html += '  <td class="text-right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#images tbody').append(html);
	
	image_row++;
}
//--></script>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.time').datetimepicker({
	pickDate: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script> 
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
$('#option a:first').tab('show');
//--></script>
  <script type="text/javascript"><!--
    function reloadAttribute(profile_id) {
        $.ajax({
            url: 'index.php?route=catalog/product/attribute&render=1&token=<?php echo $token; ?>&attribute_profile_id=' +  encodeURIComponent(profile_id) + '&product_id=<?php echo $product_id; ?>',
            success: function(resp) {
              $("#attribute-table").replaceWith(resp)
            }
        });
    }
    $('#input-attribute-profile').autocomplete({
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
            $('#input-attribute-profile').val(item['label']);
            $('input[name=\'attribute_profile_id\']').val(item['value']);
            reloadAttribute(item['value'])
        }
    });
//--></script>
<script type="text/javascript"><!--
    function reloadFilter() {
        var url = 'index.php?route=catalog/product/filter&render=1&token=<?php echo $token; ?>&product_id=<?php echo $product_id; ?>',  filter_profile = $("[name='product_filter_profile[]']");
        filter_profile.each(function () {
            url += "&filter_profile_id[]=" + this.value
        });
        if(filter_profile.size() === 0) {
            url += "&filter_profile_id[]=0"
        }
        $.ajax({
            url: url,
            success: function(resp) {
              $("#filter-wrap").replaceWith(resp)
            }
        });
    }

    $('input[name=\'filter_profile\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/filter_profile/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
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
            $('input[name=\'profile\']').val('');
            $('#product-filter-profile' + item['value']).remove();
            $('#product-filter-profile').append('<div id="product-filter-profile' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_filter_profile[]" value="' + item['value'] + '" /></div>');
            reloadFilter()
        }
    });

    $('#product-filter-profile').delegate('.fa-minus-circle', 'click', function() {
        $(this).parent().remove();
        reloadFilter()
    });

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

// Ensure all form data is collected before submission, especially from hidden tabs
$('#form-product').on('submit', function(e) {
    console.log('=== FORM SUBMIT HANDLER TRIGGERED ===');
    
    // Make sure all tabs are visible temporarily to ensure form fields are included
    // This is necessary because some browsers don't include fields from hidden tabs
    
    // Show all tab panes temporarily
    $('.tab-pane').each(function() {
        if (!$(this).hasClass('active')) {
            $(this).addClass('active').show();
        }
    });
    
    // Collect all filter checkboxes - ensure they're all visible
    var product_filters = [];
    var filter_checkboxes = $('input[name="product_filter[]"]:checked');
    console.log('Found filter checkboxes:', filter_checkboxes.length);
    
    filter_checkboxes.each(function() {
        var filter_id = $(this).val();
        if (filter_id && filter_id != '0') {
            product_filters.push(filter_id);
            console.log('  - Filter ID:', filter_id);
        }
    });
    
    // Remove any existing hidden inputs for product_filter (to avoid duplicates)
    $('input[name="product_filter[]"][type="hidden"]').remove();
    
    // Add hidden inputs for checked filters (as backup)
    product_filters.forEach(function(filter_id) {
        $('<input>').attr({
            type: 'hidden',
            name: 'product_filter[]',
            value: filter_id
        }).appendTo('#form-product');
    });
    
    // Ensure all attribute textareas are properly included and have current values
    var attribute_count = 0;
    var attribute_data = {};
    
    $('textarea[name*="product_attribute"]').each(function() {
        var $textarea = $(this);
        var name = $textarea.attr('name');
        
        // Force update the value to ensure it's current
        var current_value = $textarea.val();
        $textarea.val(current_value);
        
        // Ensure the textarea is visible and in the form
        if (!$textarea.is(':visible')) {
            $textarea.show();
        }
        
        // Parse the name to extract attribute_id and language_id
        var match = name.match(/product_attribute\[(\d+)\]\[product_attribute_description\]\[(\d+)\]\[text\]/);
        if (match) {
            var attr_id = match[1];
            var lang_id = match[2];
            if (!attribute_data[attr_id]) {
                attribute_data[attr_id] = {};
            }
            attribute_data[attr_id][lang_id] = current_value;
            console.log('  - Attribute ID:', attr_id, 'Language:', lang_id, 'Text length:', current_value.length);
        }
        
        attribute_count++;
    });
    
    // Also collect attribute data from hidden inputs
    $('input[name*="product_attribute"][name*="[attribute_id]"]').each(function() {
        var $input = $(this);
        if (!$input.is(':visible')) {
            $input.show();
        }
    });
    
    // Log for debugging
    console.log('Form submission summary:');
    console.log('  - Filters:', product_filters.length, 'IDs:', product_filters);
    console.log('  - Attributes:', attribute_count, 'textareas found');
    console.log('  - Attribute data structure:', attribute_data);
    
    // Verify form will include the data
    var formData = new FormData(this);
    var formFilters = [];
    var formAttributes = [];
    
    for (var pair of formData.entries()) {
        if (pair[0] === 'product_filter[]') {
            formFilters.push(pair[1]);
        }
        if (pair[0].indexOf('product_attribute') === 0) {
            formAttributes.push(pair[0] + '=' + (pair[1].length > 20 ? pair[1].substring(0, 20) + '...' : pair[1]));
        }
    }
    
    console.log('FormData will include:');
    console.log('  - Filters:', formFilters.length, 'IDs:', formFilters);
    console.log('  - Attributes:', formAttributes.length, 'fields');
    
    // Small delay to ensure DOM is updated before form submits
    var form = this;
    setTimeout(function() {
        // Restore tab visibility (form will submit before this completes)
        $('.tab-pane').not('#tab-general').removeClass('active').hide();
        $('#tab-general').addClass('active').show();
    }, 50);
    
    // Don't prevent default - let form submit normally
    return true;
});

//--></script></div>
<?php echo $footer; ?> 