<?php echo $header; ?>
<section class="after-header p-tb-10">
    <div class="container">
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ul>
    </div>
</section>
<div class="container alert-container">
    <?php if ($attention) { ?>
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $attention; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if (isset($success) && $success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
</div>
<div class="container cart-page">
    <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1 class="title"><?php echo $heading_title; ?>
        <?php if ($weight) { ?>
        &nbsp;(<?php echo $weight; ?>)
        <?php } ?>
      </h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class="table-responsive">
          <table class="table table-bordered cart-table bg-white">
            <thead>
              <tr>
                <td class="text-center rs-none"><?php echo $column_image; ?></td>
                <td class="text-left"><?php echo $column_name; ?></td>
                <td class="text-left"><?php echo $column_quantity; ?></td>
                <td class="text-right rs-none"><?php echo $column_price; ?></td>
                <td class="text-right"><?php echo $column_total; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product) { ?>
              <tr>
                <td class="text-center rs-none"><?php if ($product['thumb']) { ?>
                  <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
                  <?php } ?></td>
                <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                  <?php if (!$product['stock']) { ?>
                  <span class="text-danger">***</span>
                  <?php } ?>
                  <?php if ($product['option']) { ?>
                  <?php foreach ($product['option'] as $option) { ?>
                  <br />
                  <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                  <?php } ?>
                  <?php } ?>
                  <?php if ($product['reward']) { ?>
                  <br />
                  <small><?php echo $product['reward']; ?></small>
                  <?php } ?></td>
                <td class="text-left"><div class="input-group btn-block" style="max-width: 200px;">
                    <input type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" class="form-control" />
                    <span class="input-group-btn">
                    <button type="submit" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-primary"><i class="material-icons">autorenew</i></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="cart.remove('<?php echo $product['key']; ?>', true);"><i class="material-icons">cancel</i></button></span></div></td>
                <td class="text-right rs-none">
                    <?php 
                    // Calculate discount for cart display
                    $cart_discount_percent = 0;
                    $cart_discount_amount = 0;
                    $cart_discount_amount_formatted = '';
                    if (isset($product['special']) && $product['special']) {
                        $p = floatval(str_replace(['৳', ',', ' ', 'TK', 'tk'], '', $product['price']));
                        $s = floatval(str_replace(['৳', ',', ' ', 'TK', 'tk'], '', $product['special']));
                        $cart_discount_amount = $p - $s;
                        if ($p > 0) {
                            $cart_discount_percent = round(($cart_discount_amount / $p) * 100);
                        }
                        $cart_discount_amount_formatted = '৳' . number_format($cart_discount_amount, 2, '.', '');
                    }
                    ?>
                    <?php if (isset($product['special']) && $product['special']) { ?>
                    <div class="cart-discount-price-row">
                        <span class="cart-discount-price-current"><?php echo $product['special']; ?></span>
                        <span class="cart-discount-price-old"><?php echo $product['price']; ?></span>
                        <div class="cart-discount-separator"></div>
                        <span class="cart-discount-save-text">Save <span class="cart-discount-save-amount"><?php echo $cart_discount_amount_formatted; ?></span></span>
                        <?php if ($cart_discount_percent > 0) { ?>
                        <div class="cart-discount-badge">
                            <?php echo $cart_discount_percent; ?>% OFF
                        </div>
                        <?php } ?>
                    </div>
                    <?php } else { ?>
                    <span class="cart-discount-price-current"><?php echo $product['price']; ?></span>
                    <?php } ?>
                </td>
                <td class="text-right"><?php echo $product['total']; ?></td>
              </tr>
              <?php } ?>
              <?php foreach ($vouchers as $vouchers) { ?>
              <tr>
                <td></td>
                <td class="text-left"><?php echo $vouchers['description']; ?></td>
                <td class="text-left"></td>
                <td class="text-left"><div class="input-group btn-block" style="max-width: 200px;">
                    <input type="text" name="" value="1" size="1" disabled="disabled" class="form-control" />
                    <span class="input-group-btn"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="voucher.remove('<?php echo $vouchers['key']; ?>');"><i class="fa fa-times-circle"></i></button></span></div></td>
                <td class="text-right"><?php echo $vouchers['amount']; ?></td>
                <td class="text-right"><?php echo $vouchers['amount']; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </form>
      <div class="row">
            <div class="col-lg-4 offset-lg-8">
                <table class="table bg-white subtotal-table">
                    <?php foreach ($totals as $total) { ?>
                    <tr>
                        <td class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
                        <td class="text-right"><?php echo $total['text']; ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
      </div>
      <?php if ($coupon || $voucher || $reward || $shipping) { ?>
        <div class="page-section">
            <div class="row">
                <div class="col-md-6 col-sm-12"><?php echo $coupon; ?></div>
                <div class="col-md-6 col-sm-12"><?php echo $voucher; ?></div>
            </div>
        </div>
      <?php } ?>

      <div class="buttons">
          <div class="row">
              <div class="col-sm-12">
                  <div class="text-right">
                      <a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_shopping; ?></a>
                      <a href="<?php echo $checkout; ?>" class="btn btn-primary checkout-btn"><?php echo $button_checkout; ?></a>
                  </div>
              </div>
          </div>
      </div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
<?php echo $footer; ?>

<style>
/* Cart Discount Price Style - New Classes to Avoid Conflicts */
/* Price Row - Horizontal Layout Matching Image Exactly */
.cart-discount-price-row {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: nowrap;
    width: 100%;
    justify-content: flex-end;
}

/* Current Price - Large Pink */
.cart-discount-price-current {
    font-size: 36px;
    font-weight: 700;
    color: #e91e63;
    line-height: 1.1;
    margin: 0;
    white-space: nowrap;
    letter-spacing: -0.5px;
}

/* Original Price - Small Light Gray with Strikethrough */
.cart-discount-price-old {
    font-size: 18px;
    color: #999;
    text-decoration: line-through;
    font-weight: 400;
    margin: 0;
    white-space: nowrap;
    line-height: 1.1;
    opacity: 0.8;
}

/* Separator - Thin Vertical Gray Line */
.cart-discount-separator {
    width: 1px;
    height: 32px;
    background: #ddd;
    flex-shrink: 0;
    margin: 0 4px;
}

/* Save Text - Medium Green */
.cart-discount-save-text {
    font-size: 16px;
    color: #4caf50;
    font-weight: 600;
    margin: 0;
    white-space: nowrap;
    line-height: 1.1;
}

.cart-discount-save-amount {
    font-weight: 700;
    color: #4caf50;
    margin-left: 2px;
}

/* Discount Badge - Purple Rounded Tag with Glow Effect (Matching Image Exactly) */
.cart-discount-badge {
    background: #9c27b0;
    color: #ffffff;
    padding: 12px 24px;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 700;
    white-space: nowrap;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    margin-left: auto;
    flex-shrink: 0;
    position: relative;
    /* Soft blurred edge with multiple glow layers around entire badge */
    box-shadow: 
        0 0 20px rgba(156, 39, 176, 0.5),
        0 0 40px rgba(156, 39, 176, 0.3),
        0 0 60px rgba(156, 39, 176, 0.15),
        0 2px 8px rgba(156, 39, 176, 0.4),
        inset 0 1px 1px rgba(255, 255, 255, 0.2);
    /* Light-colored glow/outline around white text - creates light halo effect */
    text-shadow: 
        0 0 8px rgba(255, 255, 255, 0.7),
        0 0 12px rgba(255, 255, 255, 0.5),
        0 0 16px rgba(255, 255, 255, 0.3),
        0 1px 2px rgba(0, 0, 0, 0.3);
}

/* Responsive Design for Cart Discount */
@media (max-width: 768px) {
    .cart-discount-price-row {
        gap: 8px;
        flex-wrap: wrap;
        justify-content: flex-start;
    }
    
    .cart-discount-price-current {
        font-size: 28px;
    }
    
    .cart-discount-price-old {
        font-size: 16px;
    }
    
    .cart-discount-separator {
        height: 24px;
    }
    
    .cart-discount-save-text {
        font-size: 14px;
    }
    
    .cart-discount-badge {
        font-size: 14px;
        padding: 10px 18px;
        letter-spacing: 1px;
        border-radius: 10px;
        box-shadow: 
            0 0 15px rgba(156, 39, 176, 0.4),
            0 0 30px rgba(156, 39, 176, 0.25),
            0 0 45px rgba(156, 39, 176, 0.12),
            0 2px 6px rgba(156, 39, 176, 0.3),
            inset 0 1px 1px rgba(255, 255, 255, 0.2);
        text-shadow: 
            0 0 6px rgba(255, 255, 255, 0.6),
            0 0 10px rgba(255, 255, 255, 0.4),
            0 0 14px rgba(255, 255, 255, 0.25),
            0 1px 2px rgba(0, 0, 0, 0.25);
    }
}
</style>