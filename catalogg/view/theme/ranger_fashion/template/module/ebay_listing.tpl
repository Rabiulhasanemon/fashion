<div class="module-heading-wrapper">
  <h2 class="unified-module-heading cosmetics-module-heading"><?php echo $heading_title; ?></h2>
</div>
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
  background: linear-gradient(90deg, #10503D, #A68A6A);
  border-radius: 2px;
}
.module-heading-wrapper {
  text-align: left;
  margin-bottom: 24px;
  padding: 0 20px;
  width: 100%;
}
@media (max-width: 992px) {
  .unified-module-heading.cosmetics-module-heading { font-size: 24px; padding: 18px 0 14px 0; }
  .unified-module-heading.cosmetics-module-heading::after { width: 50px; height: 2.5px; bottom: 6px; }
}
@media (max-width: 749px) {
  .unified-module-heading.cosmetics-module-heading { font-size: 22px; padding: 16px 0 12px 0; }
  .unified-module-heading.cosmetics-module-heading::after { width: 45px; height: 2px; bottom: 5px; }
  .module-heading-wrapper { padding: 0 15px; }
}
@media (max-width: 576px) {
  .unified-module-heading.cosmetics-module-heading { font-size: 20px; padding: 14px 0 10px 0; }
  .unified-module-heading.cosmetics-module-heading::after { width: 40px; height: 2px; bottom: 4px; }
  .module-heading-wrapper { padding: 0 10px; }
}
</style>
<div class="row product-layout">
  <?php foreach ($products as $product) { ?>
  <div class="col-lg-3 col-md-3s col-sm-6 col-xs-12">
    <div class="product-thumb transition">
      <div class="image"><a target="_blank" href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
      <div class="caption">
        <p><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></p>
        <p class="price"><?php echo $product['price']; ?></p>
      </div>
    </div>
  </div>
  <?php } ?>
  <img src="<?php echo $tracking_pixel; ?>" height="0" width="0" />
</div>