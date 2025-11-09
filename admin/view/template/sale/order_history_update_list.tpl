<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $continue; ?>" data-toggle="tooltip" title="<?php echo $button_continue; ?>" class="btn btn-primary"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
            <tr>
              <td class="text-left"><?php echo $column_sirial; ?></td>
              <td class="text-left"><?php echo $column_order_id; ?></td>
              <td class="text-left"><?php echo $column_type; ?></td>
              <td class="text-left"><?php echo $column_message; ?></td>
              <td class="text-left"><?php echo $column_old_status; ?></td>
              <td class="text-left"><?php echo $column_assignee; ?></td>
              <td class="text-right"><?php echo $column_action;?></td>
            </tr>
            </thead>
            <tbody>
            <?php if ($results) {
              $count = 0;
            ?>
            <?php foreach ($results as $result) {  $count++; ?>
            <tr class="<?php echo $result['type']; ?>">
              <td><?php echo $count;?></td>
              <td class="text-left"><?php echo $result['order_id']; ?></td>
              <td class="text-left"><?php echo $result['type']; ?></td>
              <td class="text-left"><?php echo $result['message']; ?></td>
              <td class="text-left"><?php echo $result['old_status']; ?></td>
              <td class="text-left"><?php echo $result['assignee']; ?></td>
              <td class="text-right">
                <a href="<?php echo $result['view']; ?>" data-toggle="tooltip" target="_blank" title="<?php echo $button_view;?>" class="btn btn-info"><i class="fa fa-eye"></i></a>
              </td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 