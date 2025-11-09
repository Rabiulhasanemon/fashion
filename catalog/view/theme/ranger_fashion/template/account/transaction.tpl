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
<div class="container account-page transaction-page">
    <?php echo $column_left; ?>
    <div id="content" class="content left-wrapper">
      <div class="my-info">
          <h1><?php echo $heading_title; ?></h1>
          <p class="mb-2"><?php echo $text_total; ?> <b><?php echo $total; ?></b>.</p>
          <div class="table-responsive">
              <table class="table table-bordered table-hover">
                  <thead>
                  <tr>
                      <td class="text-left"><?php echo $column_date_added; ?></td>
                      <td class="text-left"><?php echo $column_description; ?></td>
                      <td class="text-right"><?php echo $column_amount; ?></td>
                  </tr>
                  </thead>
                  <tbody>
                  <?php if ($transactions) { ?>
                  <?php foreach ($transactions  as $transaction) { ?>
                  <tr>
                      <td class="text-left"><?php echo $transaction['date_added']; ?></td>
                      <td class="text-left"><?php echo $transaction['description']; ?></td>
                      <td class="text-right"><?php echo $transaction['amount']; ?></td>
                  </tr>
                  <?php } ?>
                  <?php } else { ?>
                  <tr>
                      <td class="text-center" colspan="5"><?php echo $text_empty; ?></td>
                  </tr>
                  <?php } ?>
                  </tbody>
              </table>
          </div>
          <div class="row">
              <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
              <div class="col-sm-6 text-right"><?php echo $results; ?></div>
          </div>
          <div class="buttons">
              <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
          </div>
      </div>
      <?php echo $content_bottom; ?>
    </div>
    <?php echo $column_right; ?>
</div>
<?php echo $footer; ?>