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
<div class="container alert-container">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <span>
                <?php echo $error_warning; ?>
            </span></div>
        <?php } ?>
</div>
<div class="container account-page account-register">
    <div id="content" class="content">
        <div class="panel">
            <?php echo $content_top; ?>
            <h1><?php echo $heading_title; ?></h1>
            <p class="ifHaveAccount"><?php echo $text_account_already; ?></p>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal registration_form">
                <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
                <div class="form-group required">
                    <label class="control-label" for="input-firstname">Name</label>
                    <div class="info">
                        <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
                        <?php if ($error_firstname) { ?>
                        <div class="text-danger"><?php echo $error_firstname; ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group required">
                    <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                    <div class="info">
                        <input type="email" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                        <?php if ($error_email) { ?>
                        <div class="text-danger"><?php echo $error_email; ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group required">
                    <label class="control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
                    <div class="info">
                        <input type="tel" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
                        <?php if ($error_telephone) { ?>
                        <div class="text-danger"><?php echo $error_telephone; ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group required">
                    <label class="control-label" for="input-address-1"><?php echo $entry_address_1; ?></label>
                    <div class="info">
                        <input type="text" name="address_1" value="<?php echo $address_1; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-address-1" class="form-control" />
                        <?php if ($error_address_1) { ?>
                        <div class="text-danger"><?php echo $error_address_1; ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group required">
                    <label class="control-label" for="input-city"><?php echo $entry_city; ?></label>
                    <div class="info">
                        <input type="text" name="city" value="<?php echo $city; ?>" placeholder="<?php echo $entry_city; ?>" id="input-city" class="form-control" />
                        <?php if ($error_city) { ?>
                        <div class="text-danger"><?php echo $error_city; ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="input-region"><?php echo $entry_region; ?></label>
                    <div class="info">
                        <select name="region_id" id="input-region" class="form-control">
                            <?php foreach ($regions as $region) { ?>
                            <?php if ($region['region_id'] == $region_id) { ?>
                            <option value="<?php echo $region['region_id']; ?>" selected="selected"><?php echo $region['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $region['region_id']; ?>"><?php echo $region['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                        <?php if ($error_region) { ?>
                        <div class="text-danger"><?php echo $error_region; ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group required">
                    <label class="control-label" for="input-zone"><?php echo $entry_zone; ?></label>
                    <div class="info">
                        <select name="zone_id" id="input-zone" class="form-control">
                            <?php foreach ($zones as $zone) { ?>
                            <?php if ($zone['zone_id'] == $zone_id) { ?>
                            <option value="<?php echo $zone['zone_id']; ?>" selected="selected"><?php echo $zone['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $zone['zone_id']; ?>"><?php echo $zone['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                        <?php if ($error_zone) { ?>
                        <div class="text-danger"><?php echo $error_zone; ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group required">
                    <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
                    <div class="info">
                        <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                        <?php if ($error_password) { ?>
                        <div class="text-danger"><?php echo $error_password; ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group subscription">
                    <label class="control-label"><?php echo $entry_newsletter; ?></label>
                    <div class="info">
                        <?php if ($newsletter) { ?>
                        <label class="radio-inline">
                            <input type="radio" name="newsletter" value="1" checked="checked" />
                            <?php echo $text_yes; ?></label>
                        <label class="radio-inline">
                            <input type="radio" name="newsletter" value="0" />
                            <?php echo $text_no; ?></label>
                        <?php } else { ?>
                        <label class="radio-inline">
                            <input type="radio" name="newsletter" value="1" />
                            <?php echo $text_yes; ?></label>
                        <label class="radio-inline">
                            <input type="radio" name="newsletter" value="0" checked="checked" />
                            <?php echo $text_no; ?></label>
                        <?php } ?>
                    </div>
                </div>
                <?php if ($text_agree) { ?>
                <div class="buttons"><?php echo $text_agree; ?>
                    <?php if ($agree) { ?>
                    <input type="checkbox" name="agree" value="1" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="agree" value="1" />
                    <?php } ?>
                    &nbsp;
                    <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
                </div>
        </div>
        <?php } else { ?>
        <div class="buttons">
            <div class="pull-right">
                <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
            </div>
        </div>
        <?php } ?>
        </form>
        <?php echo $content_bottom; ?>
    </div>
</div>
<script type="text/javascript"><!--
app.onReady(window, "$", function () {

    $('select[name=\'country_id\']').on('change', function() {
        $.ajax({
            url: 'account/account/country?country_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
            },
            complete: function() {
                $('.fa-spin').remove();
            },
            success: function(json) {
                if (json['postcode_required'] == '1') {
                    $('input[name=\'postcode\']').parent().parent().addClass('required');
                } else {
                    $('input[name=\'postcode\']').parent().parent().removeClass('required');
                }

                html = '<option value=""><?php echo $text_select; ?></option>';

                if (json['zone'] && json['zone'] != '') {
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
                            html += ' selected="selected"';
                        }

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }
                } else {
                    html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
                }

                $('select[name=\'zone_id\']').html(html);
                $('select[name=\'zone_id\']').trigger('change');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('select[name=\'country_id\']').trigger('change');

    $('select[name=\'zone_id\']').on('change', function() {
        $.ajax({
            url: 'account/account/zone?zone_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('select[name=\'zone_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
            },
            complete: function() {
                $('.fa-spin').remove();
            },
            success: function(json) {
                html = '';

                if (json['region'] && json['region'] != '') {
                    for (i = 0; i < json['region'].length; i++) {
                        html += '<option value="' + json['region'][i]['region_id'] + '"';

                        if (json['region'][i]['region_id'] == '<?php echo $region_id; ?>') {
                            html += ' selected="selected"';
                        }

                        html += '>' + json['region'][i]['name'] + '</option>';
                    }
                } else {
                    html = '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
                }

                $('select[name=\'region_id\']').html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('select[name=\'zone_id\']').trigger('change');

}, 10)

//--></script>
<?php echo $footer; ?>