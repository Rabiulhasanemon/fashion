<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $title; ?></title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">
<div style="width: 680px;"><a href="<?php echo $store_url; ?>" title="<?php echo $store_name; ?>"><img src="<?php echo $logo; ?>" alt="<?php echo $store_name; ?>" style="margin-bottom: 20px; bquote: none;" /></a>
  <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_greeting; ?></p>
  <table style="bquote-collapse: collapse; width: 100%; bquote-top: 1px solid #DDDDDD; bquote-left: 1px solid #DDDDDD; margin-bottom: 20px;">
    <thead>
      <tr>
        <td style="font-size: 12px; bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;" colspan="2"><?php echo $text_quote_detail; ?></td>
      </tr>
      <tr>
        <td style="font-size: 12px; bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;" ><?php echo $text_store_details; ?></td>
        <td style="font-size: 12px; bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;" ><?php echo $text_customer_details; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="font-size: 12px;	bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">
          <address>
            <strong><?php echo $store_name ;?></strong><br />
            <?php echo $store_address; ?>
          </address>
          <b><?php echo $text_telephone; ?></b> <?php echo $store_telephone; ?><br />
          <b><?php echo $text_email; ?></b> <?php echo $store_email; ?><br />
          <b><?php echo $text_website; ?></b> <a href="<?php echo $store_url; ?>"><?php echo $store_url; ?></a>
        </td>
        <td style="font-size: 12px;	bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">
          <b><?php echo $text_quote_id; ?></b> <?php echo $quote_id; ?><br />
          <b><?php echo $text_date_modified; ?></b> <?php echo $date_modified; ?><br />
          <b><?php echo $text_email; ?></b> <?php echo $email; ?><br />
          <b><?php echo $text_telephone; ?></b> <?php echo $telephone; ?><br />
          <b><?php echo $text_ip; ?></b> <?php echo $ip; ?><br />
      </tr>
    </tbody>
  </table>
  <table style="bquote-collapse: collapse; width: 100%; bquote-top: 1px solid #DDDDDD; bquote-left: 1px solid #DDDDDD; margin-bottom: 20px;">
    <thead>
      <tr>
        <td style="font-size: 12px; bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;"><?php echo $text_product; ?></td>
        <td style="font-size: 12px; bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;"><?php echo $text_model; ?></td>
        <td style="font-size: 12px; bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;"><?php echo $text_quantity; ?></td>
        <td style="font-size: 12px; bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;"><?php echo $text_price; ?></td>
        <td style="font-size: 12px; bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;"><?php echo $text_discount; ?></td>
        <td style="font-size: 12px; bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;"><?php echo $text_total; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td style="font-size: 12px;	bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><?php echo $product['name']; ?></td>
        <td style="font-size: 12px;	bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><?php echo $product['model']; ?></td>
        <td style="font-size: 12px;	bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo $product['quantity']; ?></td>
        <td style="font-size: 12px;	bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo $product['price']; ?></td>
        <td style="font-size: 12px;	bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo $product['discount']; ?></td>
        <td style="font-size: 12px;	bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo $product['total']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
    <tr>
      <td style="font-size: 12px;	bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;" colspan="4"><b><?php echo $text_subtotal; ?>:</b></td>
      <td style="font-size: 12px;	bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo $subtotal; ?></td>
    </tr>
    <tr>
      <td style="font-size: 12px;	bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;" colspan="4"><b><?php echo $text_discount; ?>:</b></td>
      <td style="font-size: 12px;	bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo $total_discount; ?></td>
    </tr>
    <tr>
      <td style="font-size: 12px;	bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;" colspan="4"><b><?php echo $text_total; ?>:</b></td>
      <td style="font-size: 12px;	bquote-right: 1px solid #DDDDDD; bquote-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo $total; ?></td>
    </tr>
    </tfoot>
  </table>
  <?php if ($comment) { ?>
  <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
    <thead>
    <tr>
      <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;"><?php echo $text_instruction; ?></td>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><?php echo $comment; ?></td>
    </tr>
    </tbody>
  </table>
  <?php } ?>
  <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_footer; ?></p>
</div>
</body>
</html>
