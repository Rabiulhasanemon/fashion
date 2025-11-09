<?php echo $header; ?>
<div class="after-header p-tb-15">
    <div class="container">
        <h2 class="page_heading category-name"><?php echo $heading_title; ?></h2>
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ul>
    </div>
</div>
<div class="container product_details_container body" itemscope itemtype="http://schema.org/Product">
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="row">
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-8'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?> product-images">
          <?php if ($thumb || $images) { ?>
            <?php if ($thumb) { ?>
            <div class="product_details_image"><a class="thumbnail" href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>"><img itemprop="image" src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a></div>
            <?php } ?>
          <ul class="thumbnails product_details_thumb_image">
            <?php if ($images) { ?>
            <?php foreach ($images as $image) { ?>
            <li class="image-additional"><a class="thumbnail" href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"> <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a></li>
              <meta itemprop="image"  content="<?php echo $image['popup']; ?>"/>
            <?php } ?>
            <?php } ?>
          </ul>
          <?php } ?>

        </div>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-4'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
          <h1 itemprop="name" class="product_name"><?php echo $heading_title; ?></h1>
          <ul class="list-unstyled product_detail_info_block brand_code_availability">
              <?php if ($manufacturer) { ?>
              <li><label><?php echo $text_manufacturer; ?></label><a href="<?php echo $manufacturers; ?>"><span itemprop="brand" class="value"><?php echo $manufacturer; ?></span></a></li>
              <?php } ?>
              <li><label><?php echo $text_model; ?></label><span class="value"><?php echo $model; ?></span></li>
              <?php if ($reward) { ?>
              <li><label><?php echo $text_reward; ?> </label><span class="value"><?php echo $reward; ?></span></li>
              <?php } ?>
              <li><label><?php echo $text_stock; ?> </label><span class="value font-bolder"><?php echo $stock; ?></span></li>
          </ul>
          <?php if ($price) { ?>
          <ul itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="list-unstyled product_detail_info_block product_price_info">
              <link itemprop="availability" href="http://schema.org/<?php echo $stock_meta; ?>"/>
              <meta itemprop="priceCurrency" content="BDT" />
              <meta itemprop="price" content="<?php echo $raw_price; ?>" />
              <?php if (!$special) { ?>
              <li>
                  <h2><?php echo $price; ?></h2>
              </li>
              <?php } else { ?>
              <li><span class="previous_price" style="text-decoration: line-through;"><?php echo $price; ?></span></li>
              <li>
                  <h2><?php echo $special; ?></h2>
              </li>
              <?php } ?>
              <?php if ($tax) { ?>
              <li><?php echo $text_tax; ?> <?php echo $tax; ?></li>
              <?php } ?>
              <?php if ($points) { ?>
              <li><?php echo $text_points; ?> <?php echo $points; ?></li>
              <?php } ?>
              <?php if ($text_available_emi) { ?>
              <li><span class="emi-text"><?php echo $text_available_emi; ?></span></li>
              <?php } ?>
              <?php if ($discounts) { ?>
              <li>
                  <hr>
              </li>
              <?php foreach ($discounts as $discount) { ?>
              <li><?php echo $discount['quantity']; ?><?php echo $text_discount; ?><?php echo $discount['price']; ?></li>
              <?php } ?>
              <?php } ?>
          </ul>
          <?php } ?>
          <div id="product" class="product_detail_info_block">
          <div class="row add_cart_option">
              <div class="col-md-2">
                <div class="product_quantity">
                    <label class="control-label" for="input-quantity"><?php echo $entry_qty; ?></label>
                    <input type="text" name="quantity" value="<?php echo $minimum; ?>" size="2" id="input-quantity" class="form-control" />
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                </div>
              </div>
              <div class="col-md-5">
                  <button type="button" <?php echo $disablePurchase ? "disabled" : ""; ?> id="button-cart" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary btn-lg btn-block"><?php echo $button_cart; ?></button>
              </div>
              <div class="col-md-5">
                  <div class="btn-group wishlist_compare_wrap">
                      <button type="button" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product_id; ?>');"><i class="fa fa-heart-o"></i></button>
                      <button type="button" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product_id; ?>');"><i class="fa fa-exchange"></i></button>
                      <a target="_blank" href="<?php echo $print_url?>"><button type="button" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_print; ?>" ><i class="fa fa-print"></i></button></a>
                  </div>
              </div>
          </div>
            <?php if ($minimum > 1) { ?>
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_minimum; ?></div>
            <?php } ?>
          </div>
          <?php if ($review_status) { ?>
          <div class="rating">
          <?php if ($no_of_review) { ?><span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"><meta itemprop="ratingValue" content="<?php echo $rating ; ?>"/><meta itemprop="reviewCount" content="<?php echo $no_of_review ; ?>"/></span<?php } ?>
            <p>
              <?php for ($i = 1; $i <= 5; $i++) { ?>
              <?php if ($rating < $i) { ?>
              <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
              <?php } else { ?>
              <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
              <?php } ?>
              <?php } ?>
              <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $reviews; ?></a> / <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $text_write; ?></a></p>
            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style"><a class="addthis_button_facebook_like" fb:like:layout="button_count"></a> <a class="addthis_button_tweet"></a> <a class="addthis_button_pinterest_pinit"></a> <a class="addthis_counter addthis_pill_style"></a></div>
            <script type="text/javascript" async src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-515eeaf54693130e"></script>
            <!-- AddThis Button END -->
          </div>
          <?php } ?>
        </div>
      </div>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-specification" data-toggle="tab"><?php echo $tab_attribute; ?></a></li>
        <?php if ($review_status) { ?>
        <li><a href="#tab-review" data-toggle="tab"><?php echo $tab_review; ?></a></li>
        <?php } ?>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab-specification">
            <table class="table table-bordered">
                <tbody>
                    <?php if($model) { ?>
                    <tr>
                        <td><?php echo $text_model; ?></td>
                        <td><?php echo $model; ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($manufacturer) { ?>
                    <tr>
                        <td><?php echo $text_manufacturer ; ?></td>
                        <td><?php echo $manufacturer; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>

                <?php if ($attribute_groups) { ?>
                    <?php foreach ($attribute_groups as $attribute_group) { ?>
                    <thead>
                    <tr>
                        <td colspan="2"><strong><?php echo $attribute_group['name']; ?></strong></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                    <tr>
                        <td><?php echo $attribute['name']; ?></td>
                        <td><?php echo $attribute['text']; ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                    <?php } ?>
                <?php } ?>
                <tr>
                    <td><?php echo $tab_description; ?></td>
                    <td itemprop="description"><?php echo $description; ?></td>
                </tr>
            </table>
        </div>
        <?php if ($review_status) { ?>
        <div class="tab-pane" id="tab-review">
            <form class="form-horizontal" id="form-review">
                <div id="review"></div>
                <h2><?php echo $text_write; ?></h2>
                <?php if ($review_guest) { ?>
                <div class="form-group required">
                    <div class="col-sm-12">
                        <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                        <input type="text" name="name" value="" id="input-name" class="form-control" />
                    </div>
                </div>
                <div class="form-group required">
                    <div class="col-sm-12">
                        <label class="control-label" for="input-review"><?php echo $entry_review; ?></label>
                        <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
                        <div class="help-block"><?php echo $text_note; ?></div>
                    </div>
                </div>
                <div class="form-group required">
                    <div class="col-sm-12">
                        <label class="control-label"><?php echo $entry_rating; ?></label>
                        &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
                        <input type="radio" name="rating" value="1" />
                        &nbsp;
                        <input type="radio" name="rating" value="2" />
                        &nbsp;
                        <input type="radio" name="rating" value="3" />
                        &nbsp;
                        <input type="radio" name="rating" value="4" />
                        &nbsp;
                        <input type="radio" name="rating" value="5" />
                        &nbsp;<?php echo $entry_good; ?></div>
                </div>
                <?php if ($site_key) { ?>
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
                    </div>
                </div>
                <?php } ?>
                <div class="buttons clearfix">
                    <div class="pull-right">
                        <button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_continue; ?></button>
                    </div>
                </div>
                <?php } else { ?>
                <?php echo $text_login; ?>
                <?php } ?>
            </form>
        </div>
        <?php } ?>
    </div>
      <?php if ($tags) { ?>
      <p><?php echo $text_tags; ?>
        <?php for ($i = 0; $i < count($tags); $i++) { ?>
        <?php if ($i < (count($tags) - 1)) { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
        <?php } else { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
        <?php } ?>
        <?php } ?>
      </p>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
      <column id="column-right" class="col-sm-3 hidden-xs">
          <?php echo $column_right; ?>
          <?php if ($products) { ?>
          <div class="related-products">
              <h3 class="title"><?php echo $text_related; ?></h3>
              <?php $i = 0; ?>
              <?php foreach ($products as $product) { ?>

              <div class="related-product">
                  <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
                  <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
                  <div class="price">
                      <?php if (!$product['special']) { ?>
                      <?php echo $product['price']; ?>
                      <?php } else { ?>
                      <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
                      <?php } ?>
                  </div>
              </div>
              <?php $i++; ?>
              <?php } ?>
          </div>
          <?php } ?>
      </column>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'recurring_id\'], input[name="quantity"]').change(function(){
	$.ajax({
		url: 'product/product/getRecurringDescription',
		type: 'post',
		data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#recurring-description').html('');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['success']) {
				$('#recurring-description').html(json['success']);
			}
		}
	});
});
//--></script>
<script type="text/javascript"><!--
$('#button-cart').on('click', function() {
	$.ajax({
		url: 'checkout/cart/add',
		type: 'post',
		data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-cart').button('loading');
		},
		complete: function() {
			$('#button-cart').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						var element = $('#input-option' + i.replace('_', '-'));

						if (element.parent().hasClass('input-group')) {
							element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						} else {
							element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						}
					}
				}

				if (json['error']['recurring']) {
					$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
				}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			}

			if (json['success']) {
                var alertRow = $(".container.alert-container");
                if(alertRow.size() == 0) {
                    alertRow = $('<div class="container alert-container"></div>').insertAfter($('.after-header'));
                }
                alertRow.append('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('#cart > button').html('<i class="fa fa-shopping-cart"></i> ' + json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');

				$('#cart > ul').load('common/cart/info ul li');
                fbq && fbq('track', 'AddToCart');
			}
		}
	});
});
//--></script>
<script type="text/javascript"><!--
$('button[id^=\'button-upload\']').on('click', function() {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'tool/upload',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).button('loading');
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$('.text-danger').remove();

					if (json['error']) {
						$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);

						$(node).parent().find('input').attr('value', json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
//--></script>
<script type="text/javascript"><!--
$('#review').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

    $('#review').fadeOut('slow');

    $('#review').load(this.href);

    $('#review').fadeIn('slow');
});

$('#review').load('product/product/review?product_id=<?php echo $product_id; ?>');

$('#button-review').on('click', function() {
	$.ajax({
		url: 'product/product/write?product_id=<?php echo $product_id; ?>',
		type: 'post',
		dataType: 'json',
		data: $("#form-review").serialize(),
		beforeSend: function() {
			$('#button-review').button('loading');
		},
		complete: function() {
			$('#button-review').button('reset');
		},
		success: function(json) {
			$('.alert-success, .alert-danger').remove();

			if (json['error']) {
				$('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
                var alertRow = $(".container.alert-container");
                if(alertRow.size() == 0) {
                    alertRow = $('<div class="container alert-container"></div>').insertAfter($('.after-header'));
                }
                alertRow.append('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').prop('checked', false);
			}
		}
	});
});

$(document).ready(function() {
	$('.product-images').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:true
		}
	});
});
//--></script>
<?php echo $footer; ?>