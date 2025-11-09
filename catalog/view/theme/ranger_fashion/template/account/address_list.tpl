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
    <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
    <?php } ?>
</div>
<div class="container account-page page-address-list">
    <?php echo $column_left; ?>
    <div id="content" class="content left-wrapper">
        <div class="address-info">
        <div class="panel">
            <h2><?php echo $text_address_book; ?></h2>
			<div class="add-address-btn">
                <a href="<?php echo $add; ?>"><i class="material-icons" aria-hidden="true">add</i><?php echo $button_new_address; ?></a>
            </div>
            <?php if ($addresses) { ?>
			
                <?php foreach($addresses as $result) { ?>
				<div class="addresses">
                    <div class="add-name"><?php echo $result['address']; ?></div>
                    <div class="editable-btn">
                        <div class="edit-btn"><a href="<?php echo $result['update']; ?>"><i class="material-icons" aria-hidden="true">edit</i></a></div>
                        <div class="del-btn"><a href="<?php echo $result['delete']; ?>"><i class="material-icons" aria-hidden="true">delete</i></a></div>
                    </div>
                </div>
				<?php } ?>
            <?php } else { ?>
            <p><?php echo $text_empty; ?></p>
            <?php } ?>
        </div>
        <div class="buttons">
            <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
        </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>