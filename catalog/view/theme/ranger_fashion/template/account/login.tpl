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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
    <?php } ?>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
    <?php } ?>
</div>
<div class="container account-page login-page">
    <div id="content" class="content">
        <div class="panel">
            <h1><?php echo $heading_title; ?></h1>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                <?php if ($redirect) { ?>
                <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
                <?php } ?>
                <div class="form-group">
                    <label class="control-label" for="input-username"><?php echo $entry_username; ?></label>
                    <input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
                    <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />

                </div>
                <div class="form-group">
                    <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a> / <a href="<?php echo $register; ?>"><?php echo $text_register; ?></a>
                </div>
                <div class="button-wrap">
                    <button type="submit" class="btn btn-primary" ><?php echo $button_login; ?></button>
                    <hr>
                    <a href="<?php echo $fb_login_url?>" class="btn btn-primary facebook"> Login With Facebook</a>
                    <hr>
                    <a href="<?php echo $google_login_url?>" class="btn btn-primary google"> Login With Google</a>
                </div>

            </form>
        </div>
        <?php echo $content_bottom; ?></div>
</div>
<?php echo $footer; ?>