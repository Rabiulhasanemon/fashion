<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="view/javascript/jquery/jquery-2.2.4.min.js"></script>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jsbarcode/3.3.20/JsBarcode.all.min.js"></script>
<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
</head>
<body>
<div class="container position-relative">

  <div class="print-button-wrap">
    <button id="printpagebutton" type="button" onclick="printpage()"><i class="fa fa-print"></i> Print</button>
  </div>

  <?php foreach ($orders as $order) { ?>

  <div style="page-break-after: always;"> 
    <div class="text-center">
      <img src="<?php echo $order['store_logo']; ?>" alt="Ribana" width="120"/>
    </div>

    <p class="pull-left">
      <strong>Date:</strong> <?php echo $order['date_added'];?>
    </p>
    <p class="pull-right">
      <strong style="font-size: 18px">Due: <?php echo $order['due'];?></strong>
    </p>

    <table class="table table-bordered">
      <thead>
        <tr>
          <td><?php echo $text_order_detail; ?></td>
          <td style="width: 50%;"><b><?php echo $text_to; ?></b></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="width: 50%;">
            <?php if ($order['invoice_no']) { ?>
            <b><?php echo $text_invoice_no; ?></b> <?php echo $order['invoice_no']; ?><br />
            <?php } ?>
            <svg class="barcode"
                 jsbarcode-format="code128" jsbarcode-value="<?php echo $order['order_id']; ?>"
                 jsbarcode-textmargin="0"
                 jsbarcode-fontoptions="bold"
                 jsbarcode-height="50"
            ></svg>
            <address>
            <strong><?php echo $order['store_name']; ?></strong><br>
            <?php echo $order['store_address']; ?><br />
             <?php echo $order['store_telephone']; ?><br />
            <?php echo $order['store_email']; ?>
            </address>
          </td>
          <td style="width: 50%;">
            <address>
              <?php echo $order['shipping_address']; ?>
            </address>
          </td>
        </tr>
      </tbody>
    </table>
    <table class="table table-bordered">
      <thead>
        <tr>
          <td><b><?php echo $column_product; ?></b></td>
          <td class="text-right"><b><?php echo $column_quantity; ?></b></td>
          <td class="text-right"><b><?php echo $column_price; ?></b></td>
          <td class="text-right"><b><?php echo $column_total; ?></b></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($order['product'] as $product) { ?>
        <tr>
          <td><?php echo $product['name']; ?>
            <?php foreach ($product['option'] as $option) { ?>
            <br />
            &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
            <?php } ?></td>
          <td class="text-right"><?php echo $product['quantity']; ?></td>
          <td class="text-right"><?php echo $product['price']; ?></td>
          <td class="text-right"><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($order['voucher'] as $voucher) { ?>
        <tr>
          <td><?php echo $voucher['description']; ?></td>
          <td></td>
          <td class="text-right">1</td>
          <td class="text-right"><?php echo $voucher['amount']; ?></td>
          <td class="text-right"><?php echo $voucher['amount']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($order['total'] as $total) { ?>
        <tr>
          <td class="text-right" colspan="3">
            <?php if(isset($total['code']) && $total['code'] == "coupon") { ?>
            <b>Discount</b>
            <?php } else { ?>
            <b><?php echo isset($total['title']) ? $total['title'] : ''; ?></b>
            <?php }  ?>
          </td>
          <td class="text-right"><?php echo isset($total['text']) ? $total['text'] : ''; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <?php if ($order['comment']) { ?>
    <table class="table table-bordered">
      <thead>
        <tr>
          <td><b><?php echo $column_comment; ?></b></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $order['comment']; ?></td>
        </tr>
      </tbody>
    </table>
    <?php } ?>
    <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_footer; ?></p>
    <p>*Please open your parcel in front of the delivery person. If you face any issues, contact with us immediately. Once the delivery guy leaves, no issues will be solved.</p>
    <p>*DO NOT TEAR this money receipt. Keep this money receipt to claim for anything. Without this money receipt no claim will be accepted. Torn money receipt won’t be accepted also.</p>
  </div>
  <?php } ?>
</div>
<script type="text/javascript">
  JsBarcode(".barcode").init();

  function printpage() {

    /* Get the print button and put it into a variable */

    var printButton = document.getElementById("printpagebutton");


    /* Set the button visibility to 'hidden' */
    printButton.style.visibility = 'hidden';

    /* Print the page content */
    window.print()

    /* Restore button visibility */
    printButton.style.visibility = 'visible';

  }
</script>
</body>
</html>