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

<section class="order-success mt-2">
  <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
    <div class="container order-success-info">
      <div class="row">
        <div class="col-lg-12">
          <div class="order-placed-info">
            <div class="row">
              <div class="col-lg-6 offset-lg-3">
                <div class="order-success-content">
                  <div class="success-message">
                    <i class="material-icons" aria-hidden="true">check_circle_outline</i>
                    <h4>Success - Thank <?php echo isset($order) ? $order['firstname'] : 'You'; ?> for Order</h4>
                    <p>Your order number is <strong><?php if(isset($order)) echo '#'.$order['order_id']; ?></strong></p>
                  </div>
                  <?php if ($order && $order['customer_id'] > 0) { ?>
                  <div class="track-order">
                    <p>To track the delivery of your order, go to <a href="account/account">My Account > My Order</a></p>
                    <a href="account/order" class="view-order">View Order</a>
                  </div>
                  <?php } ?>
                  <div class="order-summary">
                    <table>
                      <tr>
                        <th>Order Summary</th>
                        <th></th>
                      </tr>
                      <?php foreach ($order['totals'] as $total) { ?>
                      <tr>
                        <td><?php echo $total['title']; ?></td>
                        <td><?php echo $total['text']; ?></td>
                      </tr>
                      <?php } ?>

                    </table>
                  </div>
                  <a href="/"><button type="submit" class="btn mt-3">Continue Shopping</button></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div  class="mt-3 mb-3">
      <?php echo $content_bottom; ?>
    </div>
  </div>
</section>
<?php if(isset($order)) { ?><script type="text/javascript">
  var contents = [];
  <?php foreach($order_products as $order_product) { ?>contents.push({ 'id': '<?php echo $order_product['product_id']; ?>', 'quantity': <?php echo $order_product['quantity']; ?>, 'item_price': <?php echo $order_product['price']; ?> });<?php } ?>
fbq('track', 'Purchase', {
    contents: contents,
    content_type: 'product',
    value: <?php echo $order['total']; ?>,
    currency: 'BDT'
});
</script><?php } ?>
<?php echo $footer; ?>

