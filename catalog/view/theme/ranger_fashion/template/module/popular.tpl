<style>
.unified-module-heading.cosmetics-module-heading {
  font-size: 28px;
  font-weight: 600;
  color: #1a1a1a;
  line-height: 1.3;
  text-align: left;
  margin: 0;
  padding: 20px 0 16px 0;
  text-transform: none;
  letter-spacing: -0.02em;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  position: relative;
}
.unified-module-heading.cosmetics-module-heading::after {
  content: '';
  position: absolute;
  bottom: 8px;
  left: 0;
  width: 60px;
  height: 3px;
  background: linear-gradient(90deg, #ff6b9d, #ff8c9f);
  border-radius: 2px;
}
@media (max-width: 992px) {
  .unified-module-heading.cosmetics-module-heading { font-size: 24px; padding: 18px 0 14px 0; }
  .unified-module-heading.cosmetics-module-heading::after { width: 50px; height: 2.5px; bottom: 6px; }
}
@media (max-width: 749px) {
  .unified-module-heading.cosmetics-module-heading { font-size: 22px; padding: 16px 0 12px 0; }
  .unified-module-heading.cosmetics-module-heading::after { width: 45px; height: 2px; bottom: 5px; }
}
@media (max-width: 576px) {
  .unified-module-heading.cosmetics-module-heading { font-size: 20px; padding: 14px 0 10px 0; }
  .unified-module-heading.cosmetics-module-heading::after { width: 40px; height: 2px; bottom: 4px; }
}
/* Unified Discount Badge - Red with Yellow Lightning */
.unified-discount-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 10;
    background: #e74c3c;
    border-radius: 6px;
    padding: 6px 10px;
    display: flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}
.discount-badge-icon {
    color: #ffd700;
    font-size: 14px;
    font-weight: bold;
}
.discount-badge-text {
    color: #ffffff;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.3px;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}
.product .image {
    position: relative;
}
</style>
<div class="product-module">
  <div class="row heading">
    <div class="col-md-10 col-sm-10 col-xs-9">
      <div class="left"><h2 class="unified-module-heading cosmetics-module-heading"><?php echo $heading_title; ?></h2></div>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-3">
      <div class="right pull-right"><a href="<?php echo $see_all; ?>" class="see-all">See All</a></div>
    </div>
  </div>
  <div class="row product-listing">
    <?php foreach ($products as $product) { ?>
    <div class="product-wrap">
      <div class="product">
        <div class="image">
          <?php if ($product['special']) { ?>
          <?php
            $price = floatval(str_replace(['৳', ',', ' '], '', $product['price']));
            $special = floatval(str_replace(['৳', ',', ' '], '', $product['special']));
            $discountAmount = $price - $special;
            $discountPercent = ($price > 0) ? round(($discountAmount / $price) * 100) : 0;
          ?>
          <div class="unified-discount-badge">
            <i class="fa fa-bolt discount-badge-icon"></i>
            <span class="discount-badge-text"><?php echo $discountPercent; ?>% OFF</span>
          </div>
          <?php } ?>
          <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>"</a>
        </div>
        <div class="caption">
          <?php if ($product['manufacturer_thumb']) { ?>
          <div class="manufacturer"><img src="<?php echo $product['manufacturer_thumb']; ?>" alt="<?php echo $product['manufacturer']; ?>"></div>
          <?php } ?>
          <?php if ($product['price']) { ?>
          <div class="price-wrap">
            <?php if ($product['special']) { ?>
            <span class="price-new price"><span class="symbol">৳</span><span><?php echo $product['special']; ?></span></span><span class="price-old price"><span class="symbol">৳</span><span><?php echo $product['price']; ?></span></span>
            <?php } else { ?>
            <span class="price"><span class="symbol">৳</span><span><?php echo $product['price']; ?></span></span>
            <?php } ?>
          </div>
          <?php } ?>
        </div>
        <h4 class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
        <div class="actions">
          <div class="top-buttons">
            <button class="btn-compare" type="button" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i><span>Compare</span></button>
            <button class="btn-compare" type="button" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i><span>Favorite</span></button>
          </div>
          <button class="btn-cart" type="button" <?php echo $product["disablePurchase"] ? "disabled" : ""; ?> onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i><span><?php echo $button_cart; ?></span></button>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
</div>
