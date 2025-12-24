<div class="topsellers-products">
  <div class="module-heading-wrapper">
    <h2 class="title cosmetics-module-heading">Top Seller</h2>
    <?php if (isset($see_all) && $see_all) { ?>
    <div class="topsellers-see-all">
      <a href="<?php echo $see_all; ?>" class="ruplexa-module-see-all-btn">All Products</a>
    </div>
    <?php } ?>
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
  position: relative;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.topsellers-see-all {
  margin-left: auto;
}

/* All Products Button - Unified Style */
.ruplexa-module-see-all-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(135deg, #FF6B9D 0%, #FF8E9B 100%);
    color: #ffffff;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    border-radius: 25px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(255, 107, 157, 0.3);
    white-space: nowrap;
}

.ruplexa-module-see-all-btn:hover {
    background: linear-gradient(135deg, #FF8E9B 0%, #FF6B9D 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 107, 157, 0.4);
    color: #ffffff;
    text-decoration: none;
}

@media (max-width: 768px) {
    .module-heading-wrapper {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .topsellers-see-all {
        margin-left: 0;
        margin-top: 10px;
    }
    
    .ruplexa-module-see-all-btn {
        font-size: 12px;
        padding: 8px 16px;
    }
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