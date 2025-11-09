<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="view/javascript/jquery/jquery-2.2.4.min.js"></script>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
</head>
<body>
<div class="container">
  <?php foreach ($orders as $order) { ?>
  <h3><?php echo $text_picklist; ?> #<?php echo $order['order_id']; ?></h3>
  <table class="table table-bordered">
    <thead>
    <tr>
      <td style="width: 50%;">Customer Comment</td>
      <td style="width: 50%;">Our Last Comment</td>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td><?php echo $order['comment']; ?></td>
      <td style="width: 50%;"><?php echo $order['last_comment']; ?></td>
    </tr>
    </tbody>
  </table>
  <table class="table table-bordered">
    <thead>
    <tr>
      <td style="width: 50%;"><b><?php echo $text_to; ?></b></td>
      <td style="width: 50%;"><b><?php echo $text_contact; ?></b></td>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td><?php echo $order['shipping_address']; ?></td>
      <td><?php echo $order['email']; ?><br/>
        <?php echo $order['telephone']; ?></td>
    </tr>
    </tbody>
  </table>
  <table class="table table-bordered">
    <thead>
    <tr>
      <td><b><?php echo $column_reference; ?></b></td>
      <td><b><?php echo $column_product; ?></b></td>
      <td><b><?php echo $column_weight; ?></b></td>
      <td><b><?php echo $column_model; ?></b></td>
      <td class="text-right"><b><?php echo $column_quantity; ?></b></td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($order['product'] as $product) { ?>
    <tr>
      <td><?php if ($product['sku']) { ?>
        <?php echo $text_sku; ?> <?php echo $product['sku']; ?><br />
        <?php } ?>
        <?php if ($product['mpn']) { ?>
        <?php echo $text_mpn; ?><?php echo $product['mpn']; ?><br />
        <?php } ?></td>
      <td><?php echo $product['name']; ?>
        <?php foreach ($product['option'] as $option) { ?>
        <br />
        &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
        <?php } ?></td>
      <td><?php echo $product['weight']; ?></td>
      <td><?php echo $product['model']; ?></td>
      <td class="text-right"><?php echo $product['quantity']; ?></td>
    </tr>
    <?php } ?>
    </tbody>
  </table>
  <?php } ?>
</div>
</body>
</html>