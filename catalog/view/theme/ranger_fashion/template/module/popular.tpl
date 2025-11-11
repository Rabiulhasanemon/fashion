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
<div class="product-module">
  <div class="row heading">
    <div class="col-md-10 col-sm-10 col-xs-9">
      <div class="left"><h2 class="unified-module-heading"><?php echo $heading_title; ?></h2></div>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-3">
      <div class="right pull-right"><a href="<?php echo $see_all; ?>" class="see-all">See All</a></div>
    </div>
  </div>
  <div class="row product-listing">
    <?php foreach ($products as $product) { ?>
    <div class="product-wrap">
      <div class="product">
        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>"</a></div>
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
