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
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
</div>
<section id="content-top" class="bg-white"><div class="container"><?php echo $content_top; ?></div></section>
<section class="payment-page">
    <div class="container">
        <h1 class="page-title">Payment</h1>
        <form class="" id="payment-form" action="<?php echo $action; ?>" method="post">
            <div class="form-group">
                <label><?php echo $entry_order_id; ?></label>
                <input type="text" readonly name="order_id" class="form-control"  value="<?php echo $order_id; ?>">
                <?php if ($error_order_id) { ?>
                <div class="text-danger"><?php echo $error_order_id; ?></div>
                <?php } ?>
            </div>
            <?php if($name) { ?>
            <div class="form-group">
                <label><?php echo $entry_name; ?></label>
                <input type="text" class="form-control"  value="<?php echo $name; ?>">
            </div>
            <?php } ?>
            <?php if($due) { ?>
            <div class="form-group">
                <label><?php echo $entry_due; ?></label>
                <input type="text" class="form-control"  value="<?php echo $due; ?>">
            </div>
            <?php } ?>
            <div class="form-group">
                <label><?php echo $entry_payment_method; ?></label>
                <select name="payment_method" class="form-control" >
                    <?php foreach ($payment_methods as $payment_method) { ?>
                    <option value="<?php echo $payment_method['code']; ?>" ><?php echo $payment_method['title']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit" id="button-confirm"><?php echo $button_pay; ?></button>
            </div>
        </form>
    </div>
</section>
<section class="content-bottom">
    <div class="container">
        <?php echo $content_bottom; ?>
    </div>
</section>
<?php echo $footer; ?>
<script type="text/javascript">
    app.onReady(window, "$", function () {
        var confirmButton = $('#button-confirm');
        $("#payment-form").on("submit", function () {
            confirmButton.button("loading")
        });
    }, 20)
</script>
