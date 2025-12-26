<?php echo $header; ?>
<div class="main-content-wrapper account-page login-page login-phone">
    <div class="after-top-bar">
        <div class="container p-top-5">
            <div  class="breadcrumb">
                <ul itemscope itemtype="http://schema.org/BreadcrumbList">
                    <?php foreach ($breadcrumbs as $i => $breadcrumb) { if($i < 1) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } else { ?>
                    <li  itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemtype="http://schema.org/Thing" itemprop="item" href="<?php echo $breadcrumb['href']; ?>"><span itemprop="name"><?php echo $breadcrumb['text']; ?></span></a><meta itemprop="position" content="<?php echo $i; ?>" /></li>
                    <?php }} ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="wrapper-container ">
        <?php if ($success) { ?>
        <div class="alert" data-type="success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
        <?php } ?>
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger" data-type="error"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
        <?php } ?>
        <div class="panel">
            <h3><?php echo $heading_title; ?></h3>
            <form action="<?php echo $action; ?>" method="post" class="contact-from-info">
                <?php if ($redirect) { ?>
                <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
                <?php } ?>
                <div class="option">
                    <label for="input-telephone"><?php echo $entry_telephone; ?></label>
                    <div><input id="input-telephone" type="text" name="telephone" placeholder="<?php echo $entry_telephone; ?>" autofocus></div>
                    <?php if ($error_telephone) { ?>
                    <div class="text-danger"><?php echo $error_telephone; ?></div>
                    <?php } ?>
                </div>
                <button class="submit btn btn-primary" type="submit"><?php echo $button_continue; ?></button>
            </form>
        </div>
    </div>
</div>
<?php echo $footer; ?>