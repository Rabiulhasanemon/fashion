<div class="module-heading-wrapper">
  <h2 class="unified-module-heading"><?php echo $heading_title; ?></h2>
</div>
<style>
.unified-module-heading {
  font-size: 24px;
  font-weight: 600;
  color: #333;
  line-height: 1.4;
  text-align: center;
  margin: 0;
  padding: 24px 0;
  text-transform: none;
  letter-spacing: 0;
}
.module-heading-wrapper {
  text-align: center;
  margin-bottom: 20px;
  padding: 0;
  width: 100%;
}
@media (max-width: 992px) {
  .unified-module-heading { font-size: 22px; padding: 20px 0; }
}
@media (max-width: 749px) {
  .unified-module-heading { font-size: 20px; padding: 18px 0; }
}
@media (max-width: 576px) {
  .unified-module-heading { font-size: 18px; padding: 15px 0; }
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