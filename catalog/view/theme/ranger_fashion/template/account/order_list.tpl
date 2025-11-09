<?php echo $header; ?>
<section class="after-header">
    <div class="container">
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ul>
    </div>
</section>
<div class="container account-page order-list">
    <?php echo $column_left; ?>
    <div id="content" class="content left-wrapper">
        <div class="order-list">
         <h1><?php echo $heading_title; ?></h1>
        <?php if ($orders) { ?>
        <?php foreach ($orders as $order) { ?>
        <div class="my-info my-order-history">
            <div class="row">
                <div class="col-md-8">
                    <div class="item">
                        <span class="label"><?php echo $column_order_id; ?>: </span><?php echo $order['order_id']; ?>
                    </div>
                    <div class="item">
                        <span class="label"><?php echo $column_status; ?>: </span><?php echo $order['status']; ?>
                    </div>
                    <div class="item">
                        <span class="label"><?php echo $column_date_added; ?>: </span><?php echo $order['date_added']; ?>
                    </div>
                    <div class="item">
                        <span class="label"><?php echo $column_total; ?>: </span><?php echo $order['total']; ?>
                    </div>
                </div>
                <div class="col-md-4">
                <span class="view-order-btn">
                    <a href="<?php echo $order['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info">View Order</a>
                </span>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="text-right"><?php echo $pagination; ?></div>
        <?php } else { ?>
        <p><?php echo $text_empty; ?></p>
        <?php } ?>
        <div class="buttons">
            <div class="pull-right"><a href="<?php echo $order['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
        </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>