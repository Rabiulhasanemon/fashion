<div class="topsellers-products">
  <div class="module-heading-wrapper">
    <div class="block-title">
      <strong>Top Seller</strong>
    </div>
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
</style>
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