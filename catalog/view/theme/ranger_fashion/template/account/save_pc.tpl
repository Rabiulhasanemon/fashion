<?php echo $header; ?>
<section class="after-header p-tb-10">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="col-sm-4">
                <h6 class="page-heading"><?php echo $heading_title; ?></h6>
            </div>
        </div>
    </div>
</section>
<div class="container account-page">
    <?php echo $column_left; ?>
    <div id="content" class="content">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
            <fieldset>
                <legend><?php echo $text_save_pc; ?></legend>
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                        <?php if ($error_name) { ?>
                        <div class="text-danger"><?php echo $error_name; ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                        <textarea name="description" id="input-description" class="form-control" placeholder="<?php echo $entry_description; ?>"><?php echo $description; ?></textarea>
                        <?php if ($error_description) { ?>
                        <div class="text-danger"><?php echo $error_description; ?></div>
                        <?php } ?>
                    </div>
                </div>
            </fieldset>
            <div class="buttons">
                <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
                <div class="pull-right">
                    <input type="submit" value="<?php echo $button_save; ?>" class="btn btn-primary" />
                </div>
            </div>
        </form>
        <?php echo $content_bottom; ?>
    </div>
    <?php echo $column_right; ?>
</div>
<?php echo $footer; ?>