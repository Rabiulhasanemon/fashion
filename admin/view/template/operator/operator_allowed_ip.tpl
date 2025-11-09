<div class="table-responsive">
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left"><?php echo $column_ip; ?></td>
        <td class="text-left"><?php echo $column_date_added; ?></td>
        <td class="text-right"><?php echo $column_action; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($ips) { ?>
      <?php foreach ($ips as $ip) { ?>
      <tr>
        <td class="text-left"><a href="http://www.geoiptool.com/en/?IP=<?php echo $ip['ip']; ?>" target="_blank"><?php echo $ip['ip']; ?></a></td>
        <td class="text-left"><?php echo $ip['date_added']; ?></td>
        <td class="text-right">
          <button type="button" value="<?php echo $ip['operator_allowed_ip_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs button-allowed-ip-remove"><i class="fa fa-minus-circle"></i> <?php echo $text_button_remove; ?></button>
        </td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>