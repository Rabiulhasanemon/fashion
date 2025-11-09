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
<div class="container  account-page login-page">
    <div class="panel">
        <h1><?php echo $heading_title; ?></h1>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
            <div class="form-group">
                <label>Product</label>
                <div class="b-box"><?php echo $name; ?></div>
            </div>
            <div class="form-group required">
                <label><?php echo $entry_rating; ?></label>
                <div id="input-ratting">
                    <?php echo $entry_bad; ?> &nbsp; <input type="radio" name="rating" value="1" /> &nbsp; <input type="radio" name="rating" value="2" /> &nbsp; <input type="radio" name="rating" value="3" /> &nbsp; <input type="radio" name="rating" value="4" /> &nbsp; <input type="radio" name="rating" value="5" checked/> <?php echo $entry_good; ?>
                    <?php if ($error_ratting) { ?>
                    <div class="text-danger"><?php echo $error_ratting; ?></div>
                    <?php } ?>
                </div>
            </div>
            <div class="form-group required">
                <label  for="input-text"><?php echo $entry_review; ?></label>
                <textarea name="text" id="input-text" placeholder="<?php echo $entry_review; ?>" class="form-control"><?php echo $text; ?></textarea>
                <?php if ($error_text) { ?>
                <div class="text-danger"><?php echo $error_text; ?></div>
                <?php } ?>
            </div>
            <div class="button-wrap">
                <button type="submit" class="btn btn-primary" ><?php echo $button_save; ?></button>
                <a class="btn st-outline m-t-10" href="<?php echo $back; ?>"><?php echo $button_back; ?></a>
            </div>
        </form>
    </div>
</div>
<?php echo $footer; ?>