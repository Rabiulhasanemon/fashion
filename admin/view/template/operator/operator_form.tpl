<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-operator" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-operator" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <?php if ($operator_id) { ?>
            <li><a href="#tab-allowed-ip" data-toggle="tab"><?php echo $tab_allowed_ip; ?></a></li>
            <li><a href="#tab-ip" data-toggle="tab"><?php echo $tab_ip; ?></a></li>
            <?php } ?>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
                  <?php if ($error_firstname) { ?>
                  <div class="text-danger"><?php echo $error_firstname; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
                  <?php if ($error_lastname) { ?>
                  <div class="text-danger"><?php echo $error_lastname; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                  <?php if ($error_email) { ?>
                  <div class="text-danger"><?php echo $error_email; ?></div>
                  <?php  } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
                  <?php if ($error_telephone) { ?>
                  <div class="text-danger"><?php echo $error_telephone; ?></div>
                  <?php  } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-designation"><?php echo $entry_designation; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="designation" value="<?php echo $designation; ?>" placeholder="<?php echo $entry_designation; ?>" id="input-designation" class="form-control" />
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
                <div class="col-sm-10">
                  <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" autocomplete="off" />
                  <?php if ($error_password) { ?>
                  <div class="text-danger"><?php echo $error_password; ?></div>
                  <?php  } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
                <div class="col-sm-10">
                  <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" autocomplete="off" id="input-confirm" class="form-control" />
                  <?php if ($error_confirm) { ?>
                  <div class="text-danger"><?php echo $error_confirm; ?></div>
                  <?php  } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="status" id="input-status" class="form-control">
                    <?php if ($status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-approved"><?php echo $entry_approved; ?></label>
                <div class="col-sm-10">
                  <select name="approved" id="input-approved" class="form-control">
                    <?php if ($approved) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-safe"><?php echo $entry_safe; ?></label>
                <div class="col-sm-10">
                  <select name="safe" id="input-safe" class="form-control">
                    <?php if ($safe) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <?php if ($operator_id) { ?>
            <div class="tab-pane" id="tab-allowed-ip">
              <div id="allowed-ip"></div>
              <br />
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-comment"><?php echo $entry_ip; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ip" rows="8" placeholder="<?php echo $entry_ip; ?>" id="input-ip" class="form-control">
                </div>
              </div>
              <div class="text-right">
                <button id="button-allowed-ip" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_ip_add; ?></button>
              </div>
            </div>
            <?php } ?>
            <div class="tab-pane" id="tab-ip">
              <div id="ip"></div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    $('#allowed-ip').delegate('.pagination a', 'click', function(e) {
        e.preventDefault();

        $('#allowed-ip').load(this.href);
    });

    $('#allowed-ip').load('index.php?route=operator/operator/allowedIp&token=<?php echo $token; ?>&operator_id=<?php echo $operator_id; ?>');

    $('#button-allowed-ip').on('click', function(e) {
      e.preventDefault();

        $.ajax({
            url: 'index.php?route=operator/operator/allowedIp&token=<?php echo $token; ?>&operator_id=<?php echo $operator_id; ?>',
            type: 'post',
            dataType: 'html',
            data: 'action=add&ip=' + encodeURIComponent($('#tab-allowed-ip input[name=\'ip\']').val()),
            beforeSend: function() {
                $('#button-allowed-ip').button('loading');
            },
            complete: function() {
                $('#button-allowed-ip').button('reset');
            },
            success: function(html) {
                $('.alert').remove();

                $('#allowed-ip').html(html);

                $('#tab-allowed-ip input[name=\'ip\']').val('');
            }
        });
    });
    $('body').delegate('.button-allowed-ip-remove', 'click', function(e) {
        e.preventDefault();
        var $this = $(this)
        $.ajax({
            url: 'index.php?route=operator/operator/allowedIp&token=<?php echo $token; ?>&operator_id=<?php echo $operator_id; ?>',
            type: 'post',
            dataType: 'html',
            data: 'action=remove&operator_allowed_ip_id=' + this.value,
            beforeSend: function() {
                $this.button('removing');
            },
            complete: function() {
                $this.button('reset');
            },
            success: function(html) {
                $('.alert').remove();

                $('#allowed-ip').html(html);

                $('#tab-allowed-ip input[name=\'ip\']').val('');
            }
        });
    })
  </script>

  <script type="text/javascript">

    $('#ip').delegate('.pagination a', 'click', function(e) {
        e.preventDefault();

        $('#ip').load(this.href);
    });
    $('#ip').load('index.php?route=operator/operator/ip&token=<?php echo $token; ?>&operator_id=<?php echo $operator_id; ?>');


    $('body').delegate('.button-ban-add', 'click', function() {
        var element = this;

        $.ajax({
            url: 'index.php?route=operator/operator/addbanip&token=<?php echo $token; ?>',
            type: 'post',
            dataType: 'json',
            data: 'ip=' + encodeURIComponent(this.value),
            beforeSend: function() {
                $(element).button('loading');
            },
            complete: function() {
                $(element).button('reset');
            },
            success: function(json) {
                $('.alert').remove();

                if (json['error']) {
                     $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');

                    $('.alert').fadeIn('slow');
                }

                if (json['success']) {
                    $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

                    $(element).replaceWith('<button type="button" value="' + element.value + '" class="btn btn-danger btn-xs button-ban-remove"><i class="fa fa-minus-circle"></i> <?php echo $text_remove_ban_ip; ?></button>');
                }
            }
        });
    });

    $('body').delegate('.button-ban-remove', 'click', function() {
        var element = this;

        $.ajax({
            url: 'index.php?route=operator/operator/removebanip&token=<?php echo $token; ?>',
            type: 'post',
            dataType: 'json',
            data: 'ip=' + encodeURIComponent(this.value),
            beforeSend: function() {
                $(element).button('loading');
            },
            complete: function() {
                $(element).button('reset');
            },
            success: function(json) {
                $('.alert').remove();

                if (json['error']) {
                     $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                }

                if (json['success']) {
                     $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

                    $(element).replaceWith('<button type="button" value="' + element.value + '" class="btn btn-success btn-xs button-ban-add"><i class="fa fa-plus-circle"></i> <?php echo $text_add_ban_ip; ?></button>');
                }
            }
        });
    });

  </script>
</div>
<?php echo $footer; ?>