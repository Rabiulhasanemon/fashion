<?php echo $header; ?>
<?php if($image) { ?>
<section class="category-feature" style="background-image: url('<?php echo $image; ?>')">
    <div class="container">
        <h1 class="name"><?php echo $heading_title; ?></h1>
        <h3 class="name"><?php echo $blurb; ?></h3>
    </div>
</section>
<?php } else { ?>
<section class="after-header">
    <div class="container">
        <ul class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            <?php foreach ($breadcrumbs as $i => $breadcrumb) { if($i < 1) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } else { ?>
            <li  itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemtype="http://schema.org/Thing" itemprop="item" href="<?php echo $breadcrumb['href']; ?>"><span itemprop="name"><?php echo $breadcrumb['text']; ?></span></a><meta itemprop="position" content="<?php echo $i; ?>" /></li>
            <?php }} ?>
        </ul>
    </div>
</section>
<?php } ?>
<section class="category-page gray-bg p-t-20">
  <div class="container">
      <div class="product-listing grid main-content">
          <?php foreach ($products as $product) { ?>
          <div class="product-wrap">
              <div class="product-thumb">
                  <div class="image">
                      <a href="<?php echo $product['href']; ?>">
                          <img src="<?php echo $product['thumb']; ?>" height="310" width="310" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>">
                      </a>
                  </div>
                  <div class="thumb-details">
                      <h5 class="cat-name"><?php echo $heading_title; ?></h5>
                      <h4 class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                      <div class="price-wrap">
                          <?php if ($product['special']) { ?>
                          <span class="price-new price"><span class="symbol">৳</span><span><?php echo $product['special']; ?></span></span><span class="price-old price"><span class="symbol">৳</span><span><?php echo $product['price']; ?></span></span>
                          <?php } else { ?>
                          <span class="price"><span class="symbol">৳</span><span><?php echo $product['price']; ?></span></span>
                          <?php } ?>
                      </div>
                      <div class="actions">
                          <?php if($product["disablePurchase"]) { ?>
                          <button class="btn-cart" type="button" disabled><span><?php echo $product["stock_status"]; ?></span></button>
                          <?php } else { ?>
                          <button class="btn-cart" type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');">Add to Cart</button>
                          <?php } ?>
                      </div>
                  </div>
              </div>
          </div>
          <?php } if(!$products) { ?>
          <div class="empty-content">
              <span class="icon"></span>
              <h5>Sorry! No Product Founds</h5>
              <p>Please try searching for something else</p>
          </div>
          <?php } ?>
      </div>
  </div>
</section>
<?php echo $footer; ?>
