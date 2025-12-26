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
<div class="container alert-container">
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
</div>
<div class="container body">
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="main_content">
          <ul class="nav nav-tabs">
              <li class="active" data-tab="tab-quote"><?php echo $tab_quote; ?></li>
              <li data-tab="tab-history"><?php echo $tab_history; ?></li>
          </ul>
          <div class="tab-content">
              <div class="tab-pane active" id="tab-quote">
                  <form action="<?php echo $action; ?>" method="post" id="quote-form">
                      <h2><?php echo $heading_title; ?></h2>
                      <table class="table table-bordered table-hover">
                          <thead>
                          <tr>
                              <td class="text-left" colspan="2"><?php echo $text_quote_detail; ?></td>
                          </tr>
                          </thead>
                          <tbody>
                          <tr>
                              <td class="text-left" style="width: 50%;">
                                  <b><?php echo $text_quote_id; ?></b> #<?php echo $quote_id; ?><br />
                                  <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?></td>
                              <td class="text-left">
                                  <b><?php echo $text_customer_name; ?></b>: <?php echo $customer_name; ?><br />
                                  <b><?php echo $text_customer_email; ?></b>: <?php echo $customer_email; ?><br />
                                  <b><?php echo $text_customer_telephone; ?></b>: <?php echo $customer_telephone; ?><br />
                              </td>
                          </tr>
                          </tbody>
                      </table>
                      <div class="table-responsive">
                          <table class="table table-bordered table-hover">
                              <thead>
                              <tr>
                                  <td class="text-left"><?php echo $column_name; ?></td>
                                  <td class="text-left"><?php echo $column_model; ?></td>
                                  <td class="text-right"><?php echo $column_quantity; ?></td>
                                  <td class="text-right"><?php echo $column_price; ?></td>
                                  <td class="text-right" style="width: 150px"><?php echo $column_quote_price; ?></td>
                                  <td class="text-right"><?php echo $column_total; ?></td>
                              </tr>
                              </thead>
                              <tbody>
                              <?php foreach ($products as $product) { ?>
                              <tr>
                                  <td class="text-left"><?php echo $product['name']; ?></td>
                                  <td class="text-left"><?php echo $product['model']; ?></td>
                                  <td class="text-right"><?php echo $product['quantity']; ?></td>
                                  <td class="text-right price"><?php echo $product['price']; ?></td>
                                  <td><input type="number" min="1" class="form-control quote-price" data-price="<?php echo $product['price_raw']; ?>" type="text" name="quote_price[<?php echo $product['quote_product_id']; ?>]" value="<?php echo $product['quote_price']; ?>"></td>
                                  <td class="text-right"><?php echo $product['total']; ?></td>
                              </tr>
                              <?php } ?>
                              <tr>
                                  <td colspan="4" class="text-right"><?php echo $text_subtotal; ?></td>
                                  <td colspan="2"><?php echo $subtotal; ?></td>
                              </tr>
                              <tr>
                                  <td colspan="4" class="text-right"><?php echo $text_discount; ?></td>
                                  <td colspan="2" id="total-discount"><?php echo $total_discount; ?></td>
                              </tr>
                              <tr>
                                  <td colspan="4" class="text-right"><?php echo $text_total; ?></td>
                                  <td colspan="2" id="total"><?php echo $total; ?></td>
                              </tr>
                              </tbody>
                          </table>
                      </div>
                      <div class="form-group">
                          <label><?php echo $entry_comment; ?></label>
                          <textarea class="form-control" name="comment" placeholder="<?php echo $entry_comment; ?>"><?php echo $comment; ?></textarea>
                      </div>
                      <div class="buttons clearfix">
                          <div class="pull-right">
                              <button type="button" id="btn-print" class="btn btn-primary"><?php echo $button_print; ?></button>
                              <button type="submit" class="btn btn-primary"><?php echo $button_send_quote; ?></button>
                          </div>
                      </div>
                  </form>
              </div>
              <div class="tab-pane" id="tab-history">
                  <table class="table table-bordered table-hover">
                      <tr>
                          <td class="text-left"><?php echo $column_date_added; ?></td>
                          <td class="text-left"><?php echo $column_comment; ?></td>
                      </tr>
                      <tbody>
                      <?php if ($histories) { ?>
                      <?php foreach ($histories as $history) { ?>
                      <tr>
                          <td class="text-left"><?php echo $history['date_added']; ?></td>
                          <td class="text-left"><?php echo $history['comment']; ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                            <tr>
                                <td colspan="2">No History Available</td>
                            </tr>
                      <?php } ?>
                  </table>
              </div>
          </div>

      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script>
    var subtotal = '<?php echo $subtotal_raw; ?>'
    var currencyRightSymbol = '<?php echo $currency_right_symbol; ?>';
    $(document).delegate(".quote-price", "change", function () {
        var totalDiscount = 0;
        $('.quote-price').each(function () {
            var discountedUnitPrice = Number.parseFloat(this.value);
            var unitPrice = $(this).data("price")
            if(discountedUnitPrice) {
                totalDiscount += (unitPrice - discountedUnitPrice);
            }
        });
        $('#total-discount').text(totalDiscount.toLocaleString() + currencyRightSymbol)
        $('#total').text((subtotal - totalDiscount).toLocaleString() + currencyRightSymbol)
    });
    $('#btn-print').on("click", function () {
        var quoteForm = $('#quote-form')
        quoteForm.attr('action', decodeURI('<?php echo $print; ?>'.replace('&amp;', '&')));
        quoteForm.get(0).submit()
    });
    new Tab($(".nav-tabs"))

</script>
<?php echo $footer; ?>