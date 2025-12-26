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

<?php if ($success) { ?>
<div class="container">
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
</div>
<?php } ?>

<?php if ($error_warning) { ?>
<div class="container">
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
</div>
<?php } ?>

<div class="container account-modern-page">
    <?php echo $column_left; ?>
    <div id="content" class="content account-modern-content">
        <div class="account-modern-section">
            <h2 class="account-modern-heading"><?php echo $heading_title; ?></h2>
            
            <div class="alert alert-info">
                <strong><?php echo $text_pending_balance; ?>:</strong> <?php echo $pending_balance; ?>
            </div>
            
            <form action="<?php echo $action; ?>" method="post" class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_amount; ?></label>
                    <div class="col-sm-10">
                        <input type="number" name="amount" step="0.01" min="0.01" class="form-control" required />
                        <?php if (isset($error_amount)) { ?>
                        <div class="text-danger"><?php echo $error_amount; ?></div>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_payment_method; ?></label>
                    <div class="col-sm-10">
                        <select name="payment_method" class="form-control" required>
                            <option value="">-- Select --</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="bkash">bKash</option>
                            <option value="nagad">Nagad</option>
                            <option value="rocket">Rocket</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_account_details; ?></label>
                    <div class="col-sm-10">
                        <textarea name="account_details" class="form-control" rows="3" placeholder="Account number, mobile number, etc."></textarea>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Submit Withdrawal Request</button>
                    </div>
                </div>
            </form>
            
            <h3><?php echo $text_withdrawal_history; ?></h3>
            <?php if ($withdrawals) { ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td><?php echo $text_amount; ?></td>
                            <td><?php echo $text_payment_method; ?></td>
                            <td><?php echo $text_status; ?></td>
                            <td><?php echo $text_request_date; ?></td>
                            <td><?php echo $text_processed_date; ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($withdrawals as $withdrawal) { ?>
                        <tr>
                            <td><?php echo $withdrawal['amount']; ?></td>
                            <td><?php echo $withdrawal['payment_method']; ?></td>
                            <td>
                                <?php if ($withdrawal['status'] == 'pending') { ?>
                                <span class="label label-warning">Pending</span>
                                <?php } elseif ($withdrawal['status'] == 'approved') { ?>
                                <span class="label label-success">Approved</span>
                                <?php } elseif ($withdrawal['status'] == 'completed') { ?>
                                <span class="label label-success">Completed</span>
                                <?php } else { ?>
                                <span class="label label-danger">Rejected</span>
                                <?php } ?>
                            </td>
                            <td><?php echo $withdrawal['request_date']; ?></td>
                            <td><?php echo $withdrawal['processed_date']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } else { ?>
            <p>No withdrawal requests yet.</p>
            <?php } ?>
        </div>
        
        <?php echo $content_bottom; ?>
    </div>
</div>
<?php echo $footer; ?>


