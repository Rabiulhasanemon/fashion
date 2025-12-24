<div class="topsellers-products">
  <div class="module-heading-wrapper">
    <h2 class="title cosmetics-module-heading">Top Seller</h2>
  </div>
<style>
.topsellers-products .cosmetics-module-heading {
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
.topsellers-products .cosmetics-module-heading::after {
  content: '';
  position: absolute;
  bottom: 8px;
  left: 0;
  width: 60px;
  height: 3px;
  background: linear-gradient(90deg, #ff6b9d, #ff8c9f);
  border-radius: 2px;
}
.module-heading-wrapper {
  text-align: left;
  margin-bottom: 24px;
  padding: 0 20px;
  width: 100%;
}
@media (max-width: 992px) {
  .topsellers-products .cosmetics-module-heading { font-size: 24px; padding: 18px 0 14px 0; }
  .topsellers-products .cosmetics-module-heading::after { width: 50px; height: 2.5px; bottom: 6px; }
}
@media (max-width: 749px) {
  .topsellers-products .cosmetics-module-heading { font-size: 22px; padding: 16px 0 12px 0; }
  .topsellers-products .cosmetics-module-heading::after { width: 45px; height: 2px; bottom: 5px; }
  .module-heading-wrapper { padding: 0 15px; }
}
@media (max-width: 576px) {
  .topsellers-products .cosmetics-module-heading { font-size: 20px; padding: 14px 0 10px 0; }
  .topsellers-products .cosmetics-module-heading::after { width: 40px; height: 2px; bottom: 4px; }
  .module-heading-wrapper { padding: 0 10px; }
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
.related-product .image {
    position: relative;
}
</style>
  <?php $i = 0; ?>
  <?php foreach ($products as $product) { ?>

  <div class="related-product">
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
      <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a>
    </div>
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