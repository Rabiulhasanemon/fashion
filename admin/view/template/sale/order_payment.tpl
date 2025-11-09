<div class="table-responsive">
  <table class="table table-striped table-bordered">
    <thead>
    <tr>
      <td class="text-left"><?php echo $column_payment_id; ?></td>
      <td class="text-left"><?php echo $column_payment_gateway; ?></td>
      <td class="text-left"><?php echo $column_status; ?></td>
      <td class="text-left"><?php echo $column_total; ?></td>
      <td class="text-left"><?php echo $column_transaction_id; ?></td>
      <td class="text-left"><?php echo $column_tracking; ?></td>
      <td class="text-left"><?php echo $column_comment; ?></td>
      <td class="text-left"><?php echo $column_date_added; ?></td>
    </tr>
    </thead>
    <tbody>
    <?php if ($payments) { ?>
    <?php foreach ($payments as $payment) { ?>
    <tr>
      <td class="text-left"><?php echo $payment['payment_id']; ?></td>
      <td class="text-left"><?php echo $payment['gateway_title']; ?></td>
      <td class="text-left"><?php echo $payment['status']; ?></td>
      <td class="text-left"><?php echo $payment['total']; ?></td>
      <td class="text-left"><?php echo $payment['transaction_id']; ?></td>
      <td class="text-left"><?php echo $payment['tracking_no']; ?></td>
      <td class="text-left"><?php echo $payment['comment']; ?></td>
      <td class="text-left"><?php echo $payment['date_added']; ?></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
