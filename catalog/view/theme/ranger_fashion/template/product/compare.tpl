<?php echo $header; ?>
<section class="after-header p-tb-10">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</section>
<div class="container alert-container">
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
</div>
<div class="container compare_product edfADG body">

  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="bg-white <?php echo $class; ?>"><?php echo $content_top; ?>
      <?php if ($products) { ?>
      <div class="table-responsive">
          <table class="table table-bordered white-theme compare_table">
              <thead>
              <tr>
                  <td colspan="<?php echo count($products) + 1; ?>"><strong><?php echo $text_product; ?></strong></td>
              </tr>
              </thead>
              <tbody>
              <tr>
                  <td class="name"><?php echo $text_name; ?></td>
                  <?php foreach ($products as $product) { ?>
                  <td><a class="product_name" href="<?php echo $products[$product['product_id']]['href']; ?>"><strong><?php echo $products[$product['product_id']]['name']; ?></strong></a></td>
                  <?php } ?>
              </tr>
              <tr>
                  <td class="name"><?php echo $text_image; ?></td>
                  <?php foreach ($products as $product) { ?>
                  <td class="value">
                      <?php if ($products[$product['product_id']]['thumb']) { ?>
                      <img src="<?php echo $products[$product['product_id']]['thumb']; ?>" alt="<?php echo $products[$product['product_id']]['name']; ?>" title="<?php echo $products[$product['product_id']]['name']; ?>" class="img-thumbnail" />
                      <?php } ?>
                  </td>
                  <?php } ?>
              </tr>
              <tr>
                  <td class="name"><?php echo $text_price; ?></td>
                  <?php foreach ($products as $product) { ?>
                  <td class="value">
                      <?php if ($products[$product['product_id']]['price']) { ?>
                      <?php if (!$products[$product['product_id']]['special']) { ?>
                      <span class="price"><span class="symbol">৳</span><?php echo $products[$product['product_id']]['price']; ?></span>
                      <?php } else { ?>
                      <div class="price-wrap">
                          <span class="price price-old"><span class="symbol">৳</span><?php echo $products[$product['product_id']]['price']; ?></span>
                          <span class="price price-new"><span class="symbol">৳</span><?php echo $products[$product['product_id']]['special']; ?></span>
                      </div>
                      <?php } ?>
                      <?php } ?>
                  </td>
                  <?php } ?>
              </tr>
              <tr>
                  <td class="name"><?php echo $text_model; ?></td>
                  <?php foreach ($products as $product) { ?>
                  <td class="value"><?php echo $products[$product['product_id']]['model']; ?></td>
                  <?php } ?>
              </tr>
              <tr>
                  <td class="name"><?php echo $text_manufacturer; ?></td>
                  <?php foreach ($products as $product) { ?>
                  <td class="value"><?php echo $products[$product['product_id']]['manufacturer']; ?></td>
                  <?php } ?>
              </tr>
              <tr>
                  <td class="name"><?php echo $text_availability; ?></td>
                  <?php foreach ($products as $product) { ?>
                  <td class="value"><?php echo $products[$product['product_id']]['availability']; ?></td>
                  <?php } ?>
              </tr>
              <?php if ($review_status) { ?>
              <tr>
                  <td class="name"><?php echo $text_rating; ?></td>
                  <?php foreach ($products as $product) { ?>
                  <td class="value rating"><?php for ($i = 1; $i <= 5; $i++) { ?>
                      <?php if ($products[$product['product_id']]['rating'] < $i) { ?>
                      <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                      <?php } else { ?>
                      <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                      <?php } ?>
                      <?php } ?>
                      <br />
                      <?php echo $products[$product['product_id']]['reviews']; ?></td>
                  <?php } ?>
              </tr>
              <?php } ?>
              </tbody>
              <?php foreach ($attribute_groups as $attribute_group) { ?>
              <thead>
              <tr>
                  <td colspan="<?php echo count($products) + 1; ?>"><strong><?php echo $attribute_group['name']; ?></strong></td>
              </tr>
              </thead>
              <?php foreach ($attribute_group['attribute'] as $key => $attribute) { ?>
              <tbody>
              <tr>
                  <td class="name"><?php echo $attribute['name']; ?></td>
                  <?php foreach ($products as $product) { ?>
                  <?php if (isset($products[$product['product_id']]['attribute'][$key])) { ?>
                  <td class="value"><?php echo $products[$product['product_id']]['attribute'][$key]; ?></td>
                  <?php } else { ?>
                  <td></td>
                  <?php } ?>
                  <?php } ?>
              </tr>
              </tbody>
              <?php } ?>
              <?php } ?>
              <tr>
                  <td class="name"></td>
                  <?php foreach ($products as $product) { ?>
                  <td class="value"><input type="button" value="<?php echo $button_cart; ?>" class="btn" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');" />
                      <a href="<?php echo $product['remove']; ?>" class="btn btn-danger btn-block"><?php echo $button_remove; ?></a></td>
                  <?php } ?>
              </tr>
          </table>
      </div>
      <?php } else { ?>
      <div class="empty-content text-center">
          <p><?php echo $text_empty; ?></p>
          <a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a>
      </div>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>