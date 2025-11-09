<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-filter').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-order-id"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-order-status"><?php echo $entry_profile; ?></label>
                <select name="filter_profile_id" id="input-profile-id" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($filter_profiles as $filter_profile) { ?>
                  <?php if ($filter_profile['filter_profile_id'] == $filter_profile_id) { ?>
                  <option value="<?php echo $filter_profile['filter_profile_id']; ?>" selected="selected"><?php echo $filter_profile['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $filter_profile['filter_profile_id']; ?>"><?php echo $filter_profile['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-filter">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'fg.label') { ?>
                    <a href="<?php echo $sort_label; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_label; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_label; ?>"><?php echo $column_label ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'fgd.name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_group; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_group; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'fg.sort_order') { ?>
                    <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($filters) { ?>
                <?php foreach ($filters as $filter) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($filter['filter_group_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $filter['filter_group_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $filter['filter_group_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $filter['label']; ?></td>
                  <td class="text-left"><?php echo $filter['name']; ?></td>
                  <td class="text-right"><?php echo $filter['sort_order']; ?></td>
                  <td class="text-right"><a href="<?php echo $filter['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
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
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
      $('#button-filter').on('click', function() {
         var url = 'index.php?route=catalog/filter&token=<?php echo $token; ?>';

          var filter_name = $('input[name=\'filter_name\']').val();

          if (filter_name) {
              url += '&filter_name=' + encodeURIComponent(filter_name);
          }

          var filter_profile_id = $('select[name=\'filter_profile_id\']').val();

          if (filter_profile_id != '*') {
              url += '&filter_profile_id=' + encodeURIComponent(filter_profile_id);
          }

          location = url;
      });
      //--></script>
</div>
<?php echo $footer; ?>