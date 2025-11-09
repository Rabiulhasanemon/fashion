<?php echo $header; ?>
<section class="after-header p-tb-10">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <ul class="breadcrumb">
          <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
          <?php } ?>
        </ul>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</section>
<section id="content-top" class="bg-white"><div class="container"><?php echo $content_top; ?></div></section>
<section class="payment-page">
  <div class="container">
    <h2><?php echo $text_instruction; ?></h2>
    <p><b><?php echo $text_description; ?></b></p>
    <div class="well well-sm">
      <p><?php echo $bank; ?></p>
      <p><?php echo $text_payment; ?></p>
    </div>
    <form id="payment-form" action="<?php echo $action; ?>" method="post">
      <div class="form-group">
        <label><?php echo $entry_comment; ?></label>
        <textarea  name="comment" class="form-control"></textarea>
      </div>
      <div class="form-group">
        <button class="btn btn-primary" type="submit" id="button-confirm"><?php echo $button_confirm; ?></button>
      </div>
    </form>
  </div>
</section>
<section class="content-bottom">
  <div class="container">
    <?php echo $content_bottom; ?>
  </div>
</section>
<?php echo $footer; ?>

