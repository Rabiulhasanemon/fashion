<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-permanent-redirect').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-permanent-redirect">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'r.old_url') { ?>
                    <a href="<?php echo $sort_old_url; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_old_url; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_old_url; ?>"><?php echo $column_old_url; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'r.new_url') { ?>
                    <a href="<?php echo $sort_new_url; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_new_url; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_new_url; ?>"><?php echo $column_new_url; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'r.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($permanent_redirects) { ?>
                <?php foreach ($permanent_redirects as $permanent_redirect) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($permanent_redirect['permanent_redirect_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $permanent_redirect['permanent_redirect_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $permanent_redirect['permanent_redirect_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $permanent_redirect['old_url']; ?></td>
                  <td class="text-left"><?php echo $permanent_redirect['new_url']; ?></td>
                  <td class="text-left"><?php echo $permanent_redirect['date_added']; ?></td>
                  <td class="text-right"><a href="<?php echo $permanent_redirect['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
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
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
<?php echo $footer; ?>