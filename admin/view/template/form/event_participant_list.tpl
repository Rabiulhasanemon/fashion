<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $export; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_export; ?>" class="btn btn-primary"><i class="fa fa-cloud-download"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-event_participant').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                <input type="text" name="filter_email" value="<?php echo $filter_email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-event_participant">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php echo $column_id; ?></td>
                  <td class="text-left"><?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'c.email') { ?>
                    <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'c.phone') { ?>
                    <a href="<?php echo $sort_phone; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_phone; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_phone; ?>"><?php echo $column_phone; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'c.university') { ?>
                    <a href="<?php echo $sort_university; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_university; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_university; ?>"><?php echo $column_university; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $column_vr_experience; ?></td>
                  <td class="text-left"><?php echo $column_is_want_to_play; ?></td>
                  <td class="text-left"><?php if ($sort == 'c.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($event_participants) { ?>
                <?php foreach ($event_participants as $event_participant) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($event_participant['event_participant_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $event_participant['event_participant_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $event_participant['event_participant_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $event_participant['event_participant_id']; ?></td>
                  <td class="text-left"><?php echo $event_participant['full_name']; ?></td>
                  <td class="text-left"><?php echo $event_participant['email']; ?></td>
                  <td class="text-left"><?php echo $event_participant['phone']; ?></td>
                  <td class="text-left"><?php echo $event_participant['university']; ?> - <?php echo $event_participant['student_id']; ?></td>
                  <td class="text-left"><?php echo $event_participant['is_want_to_experience']; ?></td>
                  <td class="text-left">
                    <?php echo $event_participant['is_want_to_play']; ?>
                    <?php if($event_participant['game']) { echo " - " . $event_participant['game']; } ?>
                    <?php if($event_participant['gamer_type']) { echo " - " . $event_participant['gamer_type']; } ?>
                  </td>
                  <td class="text-left"><?php echo $event_participant['date_added']; ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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
	url = 'index.php?route=form/event_participant&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_email = $('input[name=\'filter_email\']').val();
	
	if (filter_email) {
		url += '&filter_email=' + encodeURIComponent(filter_email);
	}

	var filter_date_added = $('input[name=\'filter_date_added\']').val();
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
	
	location = url;
});

//--></script></div>
<?php echo $footer; ?> 
