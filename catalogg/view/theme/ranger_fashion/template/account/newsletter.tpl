<?php echo $header; ?>
<section class="after-header">
    <div class="container">
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ul>
    </div>
</section>
<div class="container account-page newsletter">
    <?php echo $column_left; ?>
    <div id="content" class="content left-wrapper">
        <div class="my-info">
        <h1><?php echo $heading_title; ?></h1>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
            <fieldset>
                <div class="form-group subscription">
                    <label class="control-label"><?php echo $entry_newsletter; ?></label>
                    <div class="info">
                        <?php if ($newsletter) { ?>
                        <label class="radio-inline">
                            <input type="radio" name="newsletter" value="1" checked="checked" />
                            <?php echo $text_yes; ?> </label>
                        <label class="radio-inline">
                            <input type="radio" name="newsletter" value="0" />
                            <?php echo $text_no; ?></label>
                        <?php } else { ?>
                        <label class="radio-inline">
                            <input type="radio" name="newsletter" value="1" />
                            <?php echo $text_yes; ?> </label>
                        <label class="radio-inline">
                            <input type="radio" name="newsletter" value="0" checked="checked" />
                            <?php echo $text_no; ?></label>
                        <?php } ?>
                    </div>
                </div>
            </fieldset>
            <div class="buttons">
                <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
                <div class="pull-right">
                    <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
<?php echo $footer; ?>