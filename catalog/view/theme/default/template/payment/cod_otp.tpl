<?php echo $header; ?>
<div class="container alert-container">
    <?php if ($error) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php } ?>
</div>
<div class="container cod-otp-verify bg-white p-15 m-tb-15">
    <form method="post" action="<?php echo $action; ?>">
        <div class="info-group">
            <label>Type:</label>
            <span class="value">Merchant Payment</span>
        </div>
    </form>
</div>
<?php echo $footer; ?>