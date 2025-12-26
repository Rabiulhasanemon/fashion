<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $text_quotation . "#" . $quote_id; ?></title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class="container">
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
                <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?><br />
                <b><?php echo $text_quote_by; ?>:</b> <?php echo $operator_name; ?><br />
                <b><?php echo $text_email; ?>:</b> <?php echo $operator_email; ?><br />
                <b><?php echo $text_telephone; ?>:</b> <?php echo $operator_telephone; ?><br />
            </td>
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
                <td class="text-right"><?php echo $column_quote_price; ?></td>
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
                <td class="text-right"><?php echo $product['quote_price']; ?></td>
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
</div>
</body>
</html>