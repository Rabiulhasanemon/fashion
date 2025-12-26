<?php echo $header; ?>
<div class="container alert-container">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
        <?php } ?>
</div>
<div class="container account_layout customer_registration body">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
        <div class="main_content">
        <?php echo $content_top; ?>
        <h1><?php echo $heading_title; ?></h1>
        <p class="ifHaveAccount"><?php echo $text_operator_already; ?></p>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal registration_form">
        <fieldset id="account">
        <legend><?php echo $text_your_details; ?></legend>
        <div class="form-group required">
            <label class="col-sm-3 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
            <div class="col-sm-9">
                <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
                <?php if ($error_firstname) { ?>
                <div class="text-danger"><?php echo $error_firstname; ?></div>
                <?php } ?>
            </div>
        </div>
        <div class="form-group required">
            <label class="col-sm-3 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
            <div class="col-sm-9">
                <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
                <?php if ($error_lastname) { ?>
                <div class="text-danger"><?php echo $error_lastname; ?></div>
                <?php } ?>
            </div>
        </div>
        <div class="form-group required">
            <label class="col-sm-3 control-label" for="input-designation"><?php echo $entry_designation; ?></label>
            <div class="col-sm-9">
                <input type="text" name="designation" value="<?php echo $designation; ?>" placeholder="<?php echo $entry_designation; ?>" id="input-designation" class="form-control" />
                <?php if ($error_designation) { ?>
                <div class="text-danger"><?php echo $error_designation; ?></div>
                <?php } ?>
            </div>
        </div>
        <div class="form-group required">
            <label class="col-sm-3 control-label" for="input-email"><?php echo $entry_email; ?></label>
            <div class="col-sm-9">
                <input type="email" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                <?php if ($error_email) { ?>
                <div class="text-danger"><?php echo $error_email; ?></div>
                <?php } ?>
            </div>
        </div>
        <div class="form-group required">
            <label class="col-sm-3 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
            <div class="col-sm-9">
                <input type="tel" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
                <?php if ($error_telephone) { ?>
                <div class="text-danger"><?php echo $error_telephone; ?></div>
                <?php } ?>
            </div>
        </div>
        </fieldset>
        <fieldset>
            <legend><?php echo $text_your_password; ?></legend>
            <div class="form-group required">
                <label class="col-sm-3 control-label" for="input-password"><?php echo $entry_password; ?></label>
                <div class="col-sm-9">
                    <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                    <?php if ($error_password) { ?>
                    <div class="text-danger"><?php echo $error_password; ?></div>
                    <?php } ?>
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-3 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
                <div class="col-sm-9">
                    <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" id="input-confirm" class="form-control" />
                    <?php if ($error_confirm) { ?>
                    <div class="text-danger"><?php echo $error_confirm; ?></div>
                    <?php } ?>
                </div>
            </div>
        </fieldset>
        <div class="buttons">
            <div class="pull-right">
                <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
            </div>
        </div>
        </form>
        <?php echo $content_bottom; ?>
        </div>
    </div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>