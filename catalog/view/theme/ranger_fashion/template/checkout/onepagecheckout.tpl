<?php echo $header; ?>
<section class="after-header">
    <div class="container">
        <ul class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            <?php foreach ($breadcrumbs as $i => $breadcrumb) { if($i < 1) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } else { ?>
            <li  itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemtype="http://schema.org/Thing" itemprop="item" href="<?php echo $breadcrumb['href']; ?>"><span itemprop="name"><?php echo $breadcrumb['text']; ?></span></a><meta itemprop="position" content="<?php echo $i; ?>" /></li>
            <?php }} ?>
        </ul>
    </div>
</section>
<div class="container alert-container">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
</div>
<section id="content-top" class="bg-white"><div class="container mt-2"><?php echo $content_top; ?></div></section>
<section class="checkout-page">
    <div class="container">
        <form class="checkout-content" id="checkout-form" action="<?php echo $action; ?>" method="post">
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div class="page-section delivery-info">
                        <div class="page-section-head">
                            <h3>Shipping or Delivery info</h3>
                        </div>
                        <div class="address">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input class="form-control" name="firstname" type="text" id="input-firstname" value="<?php echo $firstname; ?>" placeholder="Name*" >
                                <?php if ($error_firstname) { ?>
                                <div class="text-danger"><?php echo $error_firstname; ?></div>
                                <?php } ?>
                            </div>
                            <div class="form-group hide">
                                <label for="name">Email</label>
                                <input type="email" id="input-email" name="email" value="<?php echo $email; ?>" class="form-control" placeholder="<?php echo $entry_email; ?>">
                                <?php if ($error_email) { ?>
                                <div class="text-danger"><?php echo $error_email; ?></div>
                                <?php } ?>
                            </div>
                            <div class="form-group">
                                <label for="name">Telephone</label>
                                <input type="tel" id="input-telephone" name="telephone" value="<?php echo $telephone; ?>"
                                       class="form-control" placeholder="<?php echo $entry_telephone; ?>*"
                                       maxlength="11" minlength="11" required
                                       onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                >
                                <?php if ($error_telephone) { ?>
                                <div class="text-danger"><?php echo $error_telephone; ?></div>
                                <?php } ?>
                            </div>
                            <div class="form-group">
                                <label for="name">Zone</label>
                                <select name="zone_id" id="input-zone" class="form-control">
                                    <option selected disabled>Choose your zOne</option>
                                    <?php foreach ($zones as $zone) { ?>
                                    <option value="<?=$zone['zone_id']?>" <?php echo $zone_id == $zone['zone_id'] ? "selected" : ""?>><?=$zone['name']?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="name">Region</label>
                                <select name="region_id" id="input-region" class="form-control">
                                    <option value=""><?php echo $text_none; ?></option>
                                    <?php if($regions) { ?>
                                        <?php foreach ($regions as $region) { ?>
                                        <option value="<?=$region['region_id']?>" <?php echo $region_id == $region['region_id'] ? "selected" : ""?>><?=$region['name']?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="name">Address</label>
                                <input type="text" id="input-address" name="address_1" value="<?php echo $address_1 ?>" class="form-control" placeholder="<?php echo $entry_address; ?>*" >
                                <?php if ($error_address_1) { ?>
                                <div class="text-danger"><?php echo $error_address_1; ?></div>
                                <?php } ?>
                            </div>

                            <div class="form-group hide">
                                <label for="land-mark">Special Note / Instruction</label>
                                <textarea class="form-control"  name="comment" value="<?php echo $comment ?>" placeholder="<?php echo $entry_comment; ?>" rows="6"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-sm-12">
                    <div class="row row-payment-delivery-order">
                        <div class="col-md-6">
                            <div class="payment-methods h-100">
                                <div class="page-section h-100">
                                    <div class="page-section-head">
                                        <h3>Payment Method</h3>
                                    </div>
                                    <?php foreach ($payment_methods as $payment_method) { ?>
                                    <div class="radio">
                                        <label>
                                            <?php if ($payment_method['code'] == $payment_method_code || !$payment_method_code) { ?>
                                            <?php $payment_method_code = $payment_method['code']; ?>
                                            <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" checked="checked" />
                                            <?php } else { ?>
                                            <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" />
                                            <?php } ?>
                                            <?php echo $payment_method['title']; ?>
                                            <?php if ($payment_method['terms']) { ?>
                                            (<?php echo $payment_method['terms']; ?>)
                                            <?php } ?>
                                        </label>
                                    </div>
                                    <?php } ?>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 delivery-methods">
                            <div class="page-section">
                                <div class="page-section-head">
                                    <h3>Delivery Method</h3>
                                </div>
                                <?php if ($shipping_methods) { ?>
                                <?php foreach ($shipping_methods as $shipping_method) { ?>
                                <?php if (!$shipping_method['error']) { ?>
                                <?php foreach ($shipping_method['quote'] as $quote) { ?>
                                <div class="radio-inline">
                                    <label>
                                        <?php if ($quote['code'] == $shipping_method_code || !$shipping_method_code) { ?>
                                        <?php $shipping_method_code = $quote['code']; ?>
                                        <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" checked="checked" />
                                        <?php } else { ?>
                                        <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" />
                                        <?php } ?>
                                        <?php echo $quote['title']; ?> - <?php echo $quote['text']; ?> <span class="symbol">৳</span>
                                    </label>
                                </div>
                                <input type="hidden" name="<?php echo $quote['code']; ?>.title" value="<?php echo $quote['title']; ?>">
                                <?php } ?>
                                <?php } else { ?>
                                <div class="alert alert-danger"><?php echo $shipping_method['error']; ?></div>
                                <?php } ?>
                                <?php } ?>
                                <?php } else { ?>
                                <p>Delivery method is not requried</p>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if($is_emi_cart) { ?>
                        <div class="col-md-12 col-sm-12 emi-option">
                            <div class="page-section">
                                <div class="page-section-head">
                                    <h3><?=$entry_emi_tenure?></h3>
                                </div>
                                <div class="form-group">
                                    <select name="emi_tenure" id="input-emi-tenure" class="form-control">
                                        <option value="3"><?php echo $text_tree_month_emi; ?></option>
                                        <option value="6" <?php echo $emi_tenure == 6 ? "selected" : ""; ?>><?php echo $text_six_month_emi; ?></option>
                                        <option value="9" <?php echo $emi_tenure == 9 ? "selected" : ""; ?>><?php echo $text_nine_month_emi; ?></option>
                                        <option value="12" <?php echo $emi_tenure == 12 ? "selected" : ""; ?>><?php echo $text_twelve_month_emi; ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="col-md-12 col-sm-12" id="dis-gift-code">
                            <div class="page-section">
                                <div class="page-section-head">
                                    <h3>Discount /  Gift Card</h3>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="discount-code">
                                            <label><?php echo $entry_coupon ?></label>
                                            <div class="input-group">
                                                <input type="text" name="coupon" placeholder="<?php echo $entry_coupon ?>" id="input-coupon" class="form-control" />
                                                <button type="button" id="button-coupon" data-loading-text="<?php echo $text_loading; ?>"  class="btn btn-primary"><?php echo $button_apply; ?></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="gift-cart">
                                            <label for="gcarts"><?php echo $entry_voucher ?></label>
                                            <div class="input-group">
                                                <input type="text" name="voucher" placeholder="<?php echo $entry_voucher ?>" id="input-voucher" class="form-control" />
                                                <button type="button" id="button-voucher" data-loading-text="<?php echo $text_loading; ?>"  class="btn btn-primary"><?php echo $button_apply; ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="page-section order-info">
                                <div class="page-section-head">
                                    <h3>Order Summary</h3>
                                </div>
                                <table class="buy-product-table checkout-data">
                                    <thead>
                                    <tr>
                                        <td><?=$column_name?></td>
                                        <td class="rs-none text-right"><?=$column_price?></td>
                                        <td class="rs-none text-center"><?=$column_quantity?></td>
                                        <td class="text-right"><?=$column_total?></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($products as $product) { ?>
                                    <tr>
                                        <td class="name">
                                            <a href="/product/product&product_id=<?php echo $product['product_id'] ?>"><?php echo $product['name'] ?></a>
                                            <div class="options">
                                                <?php foreach ($product['option'] as $option) { ?>
                                                -
                                                <small><?php echo $option['name']; ?>
                                                    : <?php echo $option['value']; ?></small><br/>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td class="rs-none text-right"><?php echo $product['price'] ?>   </td>
                                        <td class="rs-none text-center"><?php echo $product['quantity'] ?>   </td>
                                        <td class="price text-right"><?php echo $product['total'] ?>   </td>
                                    </tr>
                                    <?php } ?>
                                    <?php foreach ($totals as $total) { ?>
                                    <tr class="total">
                                        <td colspan="2" class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
                                        <td colspan="2" class="text-right"><?php echo $total['text']; ?></td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <?php if ($text_agree) { ?>
                                <div class="terms-condition" style="margin-bottom: 10px">
                                    <label for="agree"><?php echo $text_agree; ?></label>
                                    <input type="checkbox" id="agree" name="agree" value="1" checked="<?php if($agree){ echo 'checked';};?>" />
                                </div>
                                <?php } ?>
                                <div class="clearfix"></div>
                                <button id="button-confirm" class="btn submit-btn" type="submit"><?php echo $button_confirm; ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<section class="content-bottom">
    <div class="container">
        <?php echo $content_bottom; ?>
    </div>
</section>
<?php echo $footer; ?>
<link href="catalog/view/theme/ribana/stylesheet/chosen.min.css?v=22" rel="stylesheet">
<script src="catalog/view/theme/ribana/javascript/chosen.jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">

    app.onReady(window, "$", function () {
        $("#input-region").chosen()

        var confirmButton = $('#button-confirm');
        $("#checkout-form").on("submit", function (e) {
            // Don't prevent default - allow form to submit normally
            error_log('Form submit event triggered');
            confirmButton.button("loading");
            // Allow form to submit - don't prevent default
            return true;
        });

        function reload(data) {
            confirmButton.button("loading");
            $.ajax({
                url: "checkout/onepagecheckout/reload",
                data: data,
                success: function (resp) {
                    resp = $(resp);
                    $('.delivery-methods').html(resp.find(".delivery-methods").html());
                    $('.payment-methods').html(resp.find(".payment-methods").html());
                    $('.order-info .checkout-data').html(resp.find(".order-info .checkout-data").html());
                    cart.reload();
                    confirmButton.button("reset")
                }
            })
        }
        
        $('select[name=\'zone_id\']').on('change', function() {
            $.ajax({
                url: 'account/account/zone?zone_id=' + this.value,
                dataType: 'json',
                beforeSend: function() {
                    $('select[name=\'zone_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
                },
                complete: function() {
                    $('.fa-spin').remove();
                },
                success: function(json) {

                    html = '';
                    if (json['region'] && json['region'] != '') {
                        for (i = 0; i < json['region'].length; i++) {
                            html += '<option value="' + json['region'][i]['region_id'] + '"';

                            if (json['region'][i]['region_id'] == '<?php echo $region_id; ?>') {
                                html += ' selected="selected"';
                            }

                            html += '>' + json['region'][i]['name'] + '</option>';
                        }
                    } else {
                        html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
                    }

                    $('select[name=\'region_id\']').html(html);
                    $('#input-region').trigger('chosen:updated');
                    reload({
                        "zone_id": $('select[name=\'zone_id\']').val(),
                        "region_id": $('select[name=\'region_id\']').val()
                    })
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
        
        $('#button-coupon').on('click', function() {
            if(this.disabled) return;
            $.ajax({
                url: 'checkout/coupon/coupon',
                type: 'post',
                data: 'coupon=' + encodeURIComponent($('input[name=\'coupon\']').val()),
                dataType: 'json',
                beforeSend: function() {
                    $('#button-coupon').button('loading');
                },
                complete: function() {
                    $('#button-coupon').button('reset');
                },
                success: function(json) {
                    $('.alert').remove();

                    if (json['error']) {
                        showMessage(json['error'], "error")
                    }

                    if (json['success']) {
                        showMessage(json['success'], "success");
                        reload()
                    }
                }
            });
        });

        $('#input-voucher, #input-coupon').on('keydown', function(e) {
            var $this = $(this);
            if (e.keyCode == 13) {
                e.preventDefault();
                $this.siblings("button").trigger("click")
            }
        });

        $('#button-voucher').on('click', function() {
            if(this.disabled) return;
            $.ajax({
                url: 'checkout/voucher/voucher',
                type: 'post',
                data: 'voucher=' + encodeURIComponent($('input[name=\'voucher\']').val()),
                dataType: 'json',
                beforeSend: function() {
                    $('#button-voucher').button('loading');
                },
                complete: function() {
                    $('#button-voucher').button('reset');
                },
                success: function(json) {
                    $('.alert').remove();

                    if (json['error']) {
                        showMessage(json['error'], "error")
                    }

                    if (json['success']) {
                        showMessage(json['success'], "success");
                        reload()
                    }
                }
            });
        });
       
        $("#checkout-form .delivery-methods").on("change", function () {
            reload({
                shipping_method_code: $('[name=shipping_method]:checked').val()
            })
        })

        $("#checkout-form .payment-methods").on("change", function () {
            reload({
                payment_method_code: $('[name=payment_method]:checked').val()
            })
        })

        $("#input-emi-tenure").on("change", function () {
            reload({
                emi_tenure: this.value
            })
        })

    }, 20)
</script>