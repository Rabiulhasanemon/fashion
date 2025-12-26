<?php echo $header; ?>
<section class="after-header p-tb-10">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?php echo $home; ?>">Home</a></li>
            <li><a href="<?php echo $vendor_dashboard; ?>">Vendor Dashboard</a></li>
            <li><a href="<?php echo $order_list; ?>">Orders</a></li>
            <li>Order #<?php echo $order_id; ?></li>
        </ul>
    </div>
</section>

<div class="container account-modern-page">
    <div id="content" class="content account-modern-content">
        <div class="account-modern-section">
            <h2 class="account-modern-heading">Order #<?php echo $order_id; ?></h2>
            
            <div class="row">
                <div class="col-md-6">
                    <h4>Customer Information</h4>
                    <p><strong>Name:</strong> <?php echo $firstname; ?> <?php echo $lastname; ?></p>
                    <p><strong>Email:</strong> <?php echo $email; ?></p>
                    <p><strong>Telephone:</strong> <?php echo $telephone; ?></p>
                </div>
                <div class="col-md-6">
                    <h4>Order Details</h4>
                    <p><strong>Date Added:</strong> <?php echo $date_added; ?></p>
                    <p><strong>Payment Method:</strong> <?php echo $payment_method; ?></p>
                    <p><strong>Shipping Method:</strong> <?php echo $shipping_method; ?></p>
                </div>
            </div>
            
            <h4>Products</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>Product</td>
                            <td>Model</td>
                            <td class="text-right">Quantity</td>
                            <td class="text-right">Price</td>
                            <td class="text-right">Total</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) { ?>
                        <tr>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo $product['model']; ?></td>
                            <td class="text-right"><?php echo $product['quantity']; ?></td>
                            <td class="text-right"><?php echo $product['price']; ?></td>
                            <td class="text-right"><?php echo $product['total']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <?php foreach ($totals as $total) { ?>
                        <tr>
                            <td colspan="4" class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
                            <td class="text-right"><?php echo $total['text']; ?></td>
                        </tr>
                        <?php } ?>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>


