<div class="topsellers-products">
  <h3 class="title">Top Seller</h3>
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