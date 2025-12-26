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
<div class="container alert-container">
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
</div>
<div class="container body mb-3">
    <?php echo $column_left; ?>
    <div id="content" class="content left-wrapper order-history-details">
        <h2><?php echo $heading_title; ?></h2>
        <div class="my-info order-history-info">
            <b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?><br />
            <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?><br/>
            <?php echo $text_comment; ?> : <?php echo $comment; ?>
        </div>
        <div class="my-info amount order-history-info">
            <?php foreach ($totals as $total) { ?>
            <span><b><?php echo $total['title']; ?>:</b><?php echo $total['text']; ?></span>
            <?php } ?>
        </div>
        <div class="my-info order-history-info">
            <div class="row">
                <div class="col-md-6">
                    <h4><?php echo $text_payment_address; ?></h4>
                    <address><?php echo $payment_address; ?></address>
                </div>
                <div class="col-md-6 pay-method">
                    <?php if ($payment_method) { ?>
                    <h4><?php echo $text_payment_method; ?></h4> <?php echo $payment_method; ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="my-info order-history-info">
            <div class="row">
                <div class="col-md-6">
                    <h4><?php echo $text_shipping_address; ?></h4>
                    <address><?php echo $shipping_address; ?></address>
                </div>
                <div class="col-md-6 shipping-method">
                    <?php if ($shipping_method) { ?>
                    <h4><?php echo $text_shipping_method; ?></h4><?php echo $shipping_method; ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="item-list">
            <h3><?php echo $text_history; ?></h3>
            <?php foreach ($products as $product) { ?>
                <div class="my-info order-history-info">
                    <span>  <b><?php echo $column_name; ?> :</b> <?php echo $product['name']; ?><?php foreach ($product['option'] as $option) { ?>
                        &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                        <?php } ?>
                    </span>
                    <span>
                        <b><?php echo $column_quantity; ?> : </b><?php echo $product['quantity']; ?>
                    </span>
                    <span>
                         <b><?php echo $column_total; ?> : </b><?php echo $product['total']; ?>
                    </span>
                    <div class="re-again-btn">
                        <?php if ($product['reorder']) { ?>
                        <a href="<?php echo $product['reorder']; ?>" data-toggle="tooltip" title="<?php echo $button_reorder; ?>" class="btn btn-primary"><i class="material-icons">shopping_cart</i></a>
                        <?php } ?>
                        <a href="<?php echo $product['return']; ?>" data-toggle="tooltip" title="<?php echo $button_return; ?>" class="btn btn-danger"><i class="material-icons">keyboard_return</i></a>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php if ($histories) { ?>
        <h3><?php echo $text_history; ?></h3>
        <div class="item-listing">
            <?php foreach ($histories as $history) { ?>
            <div class="my-info order-history-info">
                <span><b><?php echo $history['date_added']; ?> : </b><?php echo $history['date_added']; ?></span>
                <span><b><?php echo $column_status; ?> : </b><?php echo $history['status']; ?></span>
                <span><b><?php echo $column_comment; ?> : </b><?php echo $history['comment']; ?></span>
            </div>
            <?php } ?>
        </div>
        <?php } ?>
        <div class="buttons">
            <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
        </div>
    </div>
</div>
<?php echo $footer; ?>