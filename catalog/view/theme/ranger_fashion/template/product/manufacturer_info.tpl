<?php echo $header; ?>
<section class="after-header p-tb-10">
  <div class="container">
    <ul class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
      <?php } ?>
    </ul>
  </div>
</section>
<div class="container body">
    <div id="content">
      <?php echo $content_top; ?>
      <?php if ($products) { ?>
      <div class="top-bar">
        <div class="left-side">
          <div class="show-sort">
            <div class="form-group">
              <label><?php echo $text_limit; ?></label>
              <div class="custom-select">
                <select id="input-limit" onchange="location = this.value;">
                  <?php foreach ($limits as $limits) { ?>
                  <?php if ($limits['value'] == $limit) { ?>
                  <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group sort-by">
          <label><?php echo $text_sort; ?></label>
          <div class="custom-select">
            <select id="input-limit" onchange="location = this.value;">
              <?php foreach ($sorts as $sorts) { ?>
              <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
              <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>
      <div class="product-listing row">
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
                <button class="btn-compare" type="button" onclick="compare.add('<?php echo $product['product_id']; ?>');">Add to Compare</button>
                <button class="btn-compare" type="button" onclick="wishlist.add('<?php echo $product['product_id']; ?>');">Add to Wishlist</button>
              </div>
              <?php if($product["disablePurchase"]) { ?>
              <button class="btn-cart" type="button" disabled><span><?php echo $product["stock_status"]; ?></span></button>
              <?php } else { ?>
              <button class="btn-cart" type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><img class="c-icon" src="catalog/view/theme/lotus/icons/cart-w.svg"/><span><?php echo $button_cart; ?></span></button>
              <?php } ?>
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
      <div class="bottom-bar">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <ul class="pagination clearfix">
              <?php echo $pagination; ?>
            </ul>
          </div>
          <div class="col-md-6 show-item-no">
            <p class="pull-right"><?php echo $results; ?></p>
          </div>
        </div>
      </div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      <?php if ($description) { ?>
      <div class="row"><div class="col-md-12"><div class="category-description p-15"><?php echo $description; ?></div></div></div>
      <?php } ?>
      <?php echo $content_bottom; ?>
    </div>
</div>
<?php echo $footer; ?>