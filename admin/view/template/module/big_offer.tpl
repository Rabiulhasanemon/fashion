<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-big-offer" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-cog"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-big-offer" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="big_offer_status" id="input-status" class="form-control">
                <option value="1" <?php echo ($big_offer_status)?'selected':''; ?>><?php echo $text_enabled; ?></option>
                <option value="0" <?php echo (!$big_offer_status)?'selected':''; ?>><?php echo $text_disabled; ?></option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-title"><?php echo $entry_title; ?></label>
            <div class="col-sm-10">
              <input type="text" name="big_offer_title" value="<?php echo $big_offer_title; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_image; ?></label>
            <div class="col-sm-10">
              <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
              <input type="hidden" name="big_offer_image" value="<?php echo $big_offer_image; ?>" id="input-image" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_description; ?></label>
            <div class="col-sm-10">
              <textarea name="big_offer_description" rows="6" placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control summernote"><?php echo $big_offer_description; ?></textarea>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-start"><?php echo $entry_start; ?></label>
            <div class="col-sm-4">
              <input type="text" name="big_offer_start" value="<?php echo $big_offer_start; ?>" placeholder="YYYY-MM-DD HH:MM:SS" id="input-start" class="form-control datetime" />
            </div>
            <label class="col-sm-2 control-label" for="input-end"><?php echo $entry_end; ?></label>
            <div class="col-sm-4">
              <input type="text" name="big_offer_end" value="<?php echo $big_offer_end; ?>" placeholder="YYYY-MM-DD HH:MM:SS" id="input-end" class="form-control datetime" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-product"><?php echo $entry_product; ?></label>
            <div class="col-sm-10">
              <input type="text" name="search_product" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
              <div id="big-offer-product" class="well well-sm" style="height: 150px; overflow: auto; margin-top:10px;">
                <?php foreach ($products as $product) { ?>
                <div id="big-offer-product<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
                  <input type="hidden" name="big_offer_product[]" value="<?php echo $product['product_id']; ?>" />
                </div>
                <?php } ?>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-limit"><?php echo $entry_limit; ?></label>
            <div class="col-sm-4">
              <input type="text" name="big_offer_limit" value="<?php echo (int)$big_offer_limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-banner"><?php echo $entry_banner; ?></label>
            <div class="col-sm-4">
              <select name="big_offer_banner_id" id="input-banner" class="form-control">
                <option value="0">-- None --</option>
                <?php foreach ($banners as $banner) { ?>
                  <option value="<?php echo $banner['banner_id']; ?>" <?php echo (!empty($big_offer_banner_id) && (int)$big_offer_banner_id === (int)$banner['banner_id']) ? 'selected' : ''; ?>><?php echo $banner['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-button-text"><?php echo $entry_button_text; ?></label>
            <div class="col-sm-4">
              <input type="text" name="big_offer_button_text" value="<?php echo $big_offer_button_text; ?>" placeholder="<?php echo $entry_button_text; ?>" id="input-button-text" class="form-control" />
            </div>
            <label class="col-sm-2 control-label" for="input-button-icon"><?php echo $entry_button_icon; ?></label>
            <div class="col-sm-4">
              <input type="text" name="big_offer_button_icon" value="<?php echo $big_offer_button_icon; ?>" placeholder="material-icons / icon class" id="input-button-icon" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-slug"><?php echo $entry_slug; ?></label>
            <div class="col-sm-10">
              <input type="text" readonly value="<?php echo $big_offer_slug; ?>" id="input-slug" class="form-control" />
              <p class="help-block">Will be generated from title when you save. URL: &lt;site&gt;/<?php echo $big_offer_slug ? $big_offer_slug : 'big-offer'; ?></p>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>

<script type="text/javascript">
// Initialize rich text editor
$('textarea.summernote').summernote({height:300});

// Initialize datetime pickers
$('.datetime').datetimepicker({pickDate:true, pickTime:true});

// Product autocomplete and selection
$('input#input-product').autocomplete({
  source: function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return { label: item['name'], value: item['product_id'] }
        }));
      }
    });
  },
  select: function(item) {
    $('input#input-product').val('');
    $('#big-offer-product' + item['value']).remove();
    $('#big-offer-product').append('<div id="big-offer-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="big_offer_product[]" value="' + item['value'] + '" /></div>');
  }
});

$('#big-offer-product').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});
</script>

