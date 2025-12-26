<?php echo $header; ?>
<div class="container alert-container">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
</div>

<div class="container body">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
            <h1><?php echo $heading_title; ?></h1>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 payment-data">
                    <div class="t-head">
                        <?=$text_customer?>
                    </div>
                    <form class="checkout-content" id="checkout-form" action="<?php echo $action; ?>" method="post">
                        <div id="payment-address">
                            <div class="fields-group">
                                <label for="input-firstname"><span class="required">*</span>   <?=$entry_firstname?> :</label><br>
                                <input name="firstname" type="text" id="input-firstname" value="<?php echo $firstname; ?>" class="form-control large-field">
                                <?php if ($error_firstname) { ?>
                                <div class="text-danger"><?php echo $error_firstname; ?></div>
                                <?php } ?>
                            </div>
                            <div class="fields-group">
                                <label for="input-lastname"><span class="required">*</span>   <?=$entry_lastname?> :</label><br>
                                <input type="text" id="input-lastname" name="lastname" value="<?php echo $lastname; ?>" class="form-control large-field">
                                <?php if ($error_lastname) { ?>
                                <div class="text-danger"><?php echo $error_lastname; ?></div>
                                <?php } ?>
                            </div>
                            <div class="fields-group">
                                <label for="input-address"><span class="required">*</span>   <?= $entry_address?> :</label><br>
                                <input type="text" id="input-address" name="address_1" value="<?php echo $address_1 ?>" class="form-control large-field">
                                <?php if ($error_address_1) { ?>
                                <div class="text-danger"><?php echo $error_address_1; ?></div>
                                <?php } ?>
                            </div>
                            <div class="fields-group">
                                <label for="input-telephone"><span class="required">*</span>   <?=$entry_telephone?>:</label><br>
                                <input type="tel" id="input-telephone" name="telephone" value="<?php echo $telephone; ?>" class="form-control large-field">
                                <?php if ($error_telephone) { ?>
                                <div class="text-danger"><?php echo $error_telephone; ?></div>
                                <?php } ?>
                            </div>
                            <div class="fields-group">
                                <label for="email-ch">   <?=$entry_email?>:</label><br>
                                <input type="text" id="email-ch" name="email" value="<?php echo $email; ?>" class="form-control large-field">
                                <?php if ($error_email) { ?>
                                <div class="text-danger"><?php echo $error_email; ?></div>
                                <?php } ?>
                            </div>
                            <div class="fields-group">
                                <label for="city-ch"><span class="required">*</span><?=$entry_city?>:</label><br>
                                <input type="text" id="city-ch" name="city" value="<?php echo $city; ?>" class="form-control large-field">
                                <?php if ($error_city) { ?>
                                <div class="text-danger"><?php echo $error_city; ?></div>
                                <?php } ?>
                            </div>
                            <div class="fields-group">
                                <label class=""><?=$entry_zone?></label>
                                <select name="zone_id" class="form-control large-field">
                                    <?php foreach ($zones as $zone) { ?>
                                    <option value="<?=$zone['zone_id']?>" <?php echo $zone_id == $zone['zone_id'] ? "selected" : ""?>><?=$zone['name']?></option>
                                    <?php } ?>
                                </select>
                            </div>

                        </div>
                        <div class="delivery-methods">
                            <label> <?=$text_shipping_method?></label><br>
                            <?php if ($shipping_methods) { ?>
                            <?php foreach ($shipping_methods as $shipping_method) { ?>
                            <p><strong><?php echo $shipping_method['title']; ?></strong></p>
                            <?php if (!$shipping_method['error']) { ?>
                            <?php foreach ($shipping_method['quote'] as $quote) { ?>
                            <div class="radio">
                                <label>
                                    <?php if ($quote['code'] == $shipping_method_code || !$shipping_method_code) { ?>
                                    <?php $shipping_method_code = $quote['code']; ?>
                                    <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" />
                                    <?php } ?>
                                    <?php echo $quote['title']; ?> - <?php echo $quote['text']; ?>
                                </label>
                                <input type="hidden" name="<?php echo $quote['code']; ?>.title" value="<?php echo $quote['title']; ?>">
                            </div>
                            <?php } ?>
                            <?php } else { ?>
                            <div class="alert alert-danger"><?php echo $shipping_method['error']; ?></div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                        </div>
                        <div class="payment-methods">
                            <?php if ($payment_methods) { ?>
                            <p><?php echo $text_payment_method; ?></p>
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
                            <?php } ?>
                        </div>
                        <div class="fields-group">
                            <label for="comment_field">  <?=$entry_comment?>:</label><br>
                            <input type="text" id="comment_field" class="form-control large-field" name="comment" value="<?php echo $comment ?>">
                        </div>
                        <?php if ($text_agree) { ?>
                        <div class="fields-group"><?php echo $text_agree; ?>
                            <?php if ($agree) { ?>
                            <input type="checkbox" name="agree" value="1" checked="checked" />
                            <?php } else { ?>
                            <input type="checkbox" name="agree" value="1" />
                            <?php } ?>
                        </div>
                        <?php } ?>
                        <div id="confirm">
                            <div class="payment">
                                <button id="button-confirm" class=" btn btn-lg btn-success btn-primary" type="submit"><?=$button_confirm?></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 checkout-data">
                    <div class="cart-info table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <td class="name t-head">  <?=$column_name?></td>
                                <td class="price t-head"><?=$column_price?></td>
                                <td class="quantity t-head"><?=$column_quantity?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($products as $product) { ?>
                            <tr>
                                <td class="name">
                                    <a href="/product/product&product_id=<?php echo $product['product_id'] ?>"><?php echo $product['name'] ?></a>
                                    <div class="p-model">
                                        <?php echo $product['model'] ?>                                </div>
                                    <div class="cart-option">
                                        <?php foreach ($product['option'] as $option) { ?>
                                        -
                                        <small><?php echo $option['name']; ?>
                                            : <?php echo $option['value']; ?></small><br/>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td class="price"><?php echo $product['price'] ?>   </td>
                                <td class="quantity"><?php echo $product['quantity'] ?>   </td>
                            </tr>
                            <?php } ?>
                            <?php foreach ($totals as $total) { ?>
                            <tr>
                                <td colspan="2" style="text-align: right"><strong><?php echo $total['title']; ?>:</strong></td>
                                <td class="text-right"><?php echo $total['text']; ?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row table-bottom">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td class="shop-total">
                                        <?=$text_total?>: <span><?php echo $cart_total ?></span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <?php echo $content_bottom; ?></div>
        <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
<script>
    $('#button-confirm').on("click", function () {
        $('#button-confirm').button("loading")
    })
</script>