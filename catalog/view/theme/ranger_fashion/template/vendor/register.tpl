<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
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
    <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-store"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <p><?php echo $text_description; ?></p>
        <form action="<?php echo $action; ?>" method="post" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-store-name"><?php echo $entry_store_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="store_name" value="<?php echo $store_name; ?>" placeholder="<?php echo $entry_store_name; ?>" id="input-store-name" class="form-control" />
              <?php if ($error_store_name) { ?>
              <div class="text-danger"><?php echo $error_store_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-store-description"><?php echo $entry_store_description; ?></label>
            <div class="col-sm-10">
              <textarea name="store_description" rows="5" placeholder="<?php echo $entry_store_description; ?>" id="input-store-description" class="form-control"><?php echo $store_description; ?></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-store-address"><?php echo $entry_store_address; ?></label>
            <div class="col-sm-10">
              <textarea name="store_address" rows="3" placeholder="<?php echo $entry_store_address; ?>" id="input-store-address" class="form-control"><?php echo $store_address; ?></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-store-city"><?php echo $entry_store_city; ?></label>
            <div class="col-sm-10">
              <input type="text" name="store_city" value="<?php echo $store_city; ?>" placeholder="<?php echo $entry_store_city; ?>" id="input-store-city" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-store-phone"><?php echo $entry_store_phone; ?></label>
            <div class="col-sm-10">
              <input type="text" name="store_phone" value="<?php echo $store_phone; ?>" placeholder="<?php echo $entry_store_phone; ?>" id="input-store-phone" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-store-email"><?php echo $entry_store_email; ?></label>
            <div class="col-sm-10">
              <input type="email" name="store_email" value="<?php echo $store_email; ?>" placeholder="<?php echo $entry_store_email; ?>" id="input-store-email" class="form-control" />
            </div>
          </div>
          <div class="pull-right">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_continue; ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>


