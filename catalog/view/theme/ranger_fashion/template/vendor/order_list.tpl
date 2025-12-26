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

<div class="container account-modern-page">
    <?php echo $column_left; ?>
    <div id="content" class="content account-modern-content">
        <div class="account-modern-section">
            <h2 class="account-modern-heading"><?php echo $heading_title; ?></h2>
            
            <?php if ($orders) { ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <td class="text-left"><?php echo $text_order_id; ?></td>
                            <td class="text-left"><?php echo $text_customer; ?></td>
                            <td class="text-left"><?php echo $text_status; ?></td>
                            <td class="text-right"><?php echo $text_total; ?></td>
                            <td class="text-right"><?php echo $text_earning; ?></td>
                            <td class="text-left"><?php echo $text_date_added; ?></td>
                            <td class="text-right"><?php echo $text_view; ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order) { ?>
                        <tr>
                            <td class="text-left">#<?php echo $order['order_id']; ?></td>
                            <td class="text-left"><?php echo $order['customer_name']; ?></td>
                            <td class="text-left"><?php echo $order['status']; ?></td>
                            <td class="text-right"><?php echo $order['total']; ?></td>
                            <td class="text-right"><strong><?php echo $order['vendor_earning']; ?></strong></td>
                            <td class="text-left"><?php echo $order['date_added']; ?></td>
                            <td class="text-right"><a href="<?php echo $order['view']; ?>" class="btn btn-info btn-sm">View</a></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                <div class="col-sm-6 text-right"><?php echo $results; ?></div>
            </div>
            <?php } else { ?>
            <div class="alert alert-info">No orders found.</div>
            <?php } ?>
        </div>
        
        <?php echo $content_bottom; ?>
    </div>
</div>
<?php echo $footer; ?>


