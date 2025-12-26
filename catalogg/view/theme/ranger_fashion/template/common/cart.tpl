<div class="header-title">
  <span class="close-btn close"><i class="material-icons" aria-hidden="true">arrow_back</i></span>
  <p>YOUR CART</p>
</div>
<div class="content">
  <div class="cart-items">
    <?php foreach ($products as $product) { ?>
    <div class="item-wrap">
      <div class="item">
        <div class="image">
          <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>">
        </div>
        <div class="name"><?php echo $product['name']; ?>
          <?php if ($product['option']) { ?>
          <?php foreach ($product['option'] as $option) { ?>
          <br />
          - <small><?php echo $option['name']; ?>: <b><?php echo $option['value']; ?></b></small>
          <?php } ?>
          <?php } ?>
          <div class="cart-price">
            <div class="price"><?php echo $product['price']; ?>
              <span class="multiplication-item">X</span>
            </div>
            <div class="quantity-wrapper quantity-btn">
              <span class="add-down value" data-type="dec">-</span>
              <input class="quantity" type="text" name="<?php echo $product['key']; ?>" value="<?php echo $product['quantity']; ?>" readonly>
              <span class="add-up value" data-type="inc" <?php echo $product['available_stock'] !== false && $product['quantity'] >= $product['available_stock'] ? "disabled" : "" ?>>+</span>
            </div>
          </div>
        </div>
        <?php if(!$product["stock"]) { ?>
        <div class="error"><span><?php echo $error_stock; ?></span></div>
        <?php } ?>
      </div>
      <div class="amount-item"><?php echo $product['total']; ?></div>
      <div class="item-cancel" onclick="cart.remove('<?php echo $product['key']; ?>');" title="<?php echo $button_remove; ?>"><i class="material-icons" aria-hidden="true">close</i></div>
    </div>
    <?php } if(!$products) { ?>
    <div class="cart-empty text-center">
      <img src="catalog/view/theme/himel_shop/image/cart-empty.png" width="100" height="100" alt="cart empty">
      <p class="text-center" style="color: var(--lightRed)">
        <?php echo $text_empty; ?></p>
    </div>
    <?php } ?>
  </div>
</div>

<div class="footer">
  <div class="promotion-code">
    <input type="text" placeholder="Promo Code" id="input-cart-coupon">
    <button class="button-coupon" data-target="#input-cart-coupon" type="submit">Apply</button>
  </div>
  <?php foreach ($totals as $total) { ?>
  <div class="total">
    <div class="title"><?php echo $total['title']; ?></div>
    <div class="amount"><?php echo $total['text']; ?></div>
  </div>
  <?php } ?>
  <?php if($products) { ?>
  <div class="checkout-btn">
    <a href="<?php echo $checkout; ?>"><button class="btn"><?php echo $text_checkout; ?></button></a>
  </div>
  <?php } ?>
</div>