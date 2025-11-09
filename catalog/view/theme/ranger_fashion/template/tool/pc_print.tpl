<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo $heading_title; ?></title>
    <style type="text/css">
        *,body{margin:0}.wrapper,img{max-width:100%}*{padding:0}img{height:auto}.wrapper{width:794px;margin:0 auto}.top-area{display:flex;justify-content:center;align-items:center;margin:20px 0}.logo{margin-right:20px}.company-info h1{color:#EF4A26}.address{border-top:2px solid #EF4A26;margin-top:4px;line-height:24px}table{width:100%;max-width:99%;border-collapse:collapse}table>tbody>tr>td{padding:12px;border-right:1px solid #333}table>tbody>tr>td:last-child{border:0}.component-info{background:#EF4A26;color:#fff;border:1px solid #EF4A26}tr.details{border:1px solid #333}.total-amount .amount-label{text-align:right}
    </style>
</head>
<body>

<div class="wrapper">
    <div class="top-area">
        <div class="logo"><a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" alt="<?php echo $store; ?>"></a></div>
        <div class="company-info">
            <h1><?php echo $store; ?></h1>
            <div class="address">
                <p><?php echo $address; ?></p>
                <p><strong>Phone: </strong><?php echo $telephone; ?>, <strong>Email:</strong><?php echo $email; ?></p>
                <p class="web"><?php echo $web; ?></p>
            </div>
        </div>
    </div>

    <div class="all-printed-component">
        <table>
            <tr class="component-info">
                <td class="component-name"><b><?php echo $column_component;?></b></td>
                <td class="product-name"><b><?php echo $column_product_name;?></b></td>
                <td class="price"><b><?php echo $column_price;?></b></td>
            </tr>
            <?php foreach ($components as $component) { ?>
            <tr class="details">
                <td class="component"><?php echo $component['name'];?></td>
                <?php if($component['product_id']) { ?>
                <td class="name"><?php echo $component['product_name'];?></td>
                <td class="price"><?php if($component['product_special']) { ?>
                    <div class="price-old"><?php echo $component['product_price'];?></div>
                    <div class="price-new"><?php echo $component['product_special'];?></div>
                    <?php } else { ?>
                    <div class="price"><?php echo $component['product_price'];?></div>
                    <?php } ?>
                </td>
                <?php } else { ?>
                    <td class="name"></td>
                    <td class="price"></td>
                <?php } ?>
            </tr>
            <?php } ?>
            <tr class="details total-amount">
                <td colspan="2" class="amount-label"><b><?php echo $text_total ?>:</b></td>
                <td class="amount"><?php echo $total ?></td>
            </tr>
        </table>
    </div>
</div>
<script>
    window.print()
</script>
</body>
</html>