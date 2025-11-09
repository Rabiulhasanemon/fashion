<html>
<head>
    <style type="text/css">
        table {
            color: #333; /* Lighten up font color */
            font-family: Helvetica, Arial, sans-serif; /* Nicer font */
            width: 100%;
            border-collapse:
            collapse; border-spacing: 0;
            margin-bottom: 20px;
        }

        td, th { border: 1px solid #CCC; height: 30px; } /* Make cells a bit taller */

        th {
            background: #F3F3F3; /* Light grey background */
            font-weight: bold; /* Make sure they're bold */
        }

        td {
            background: #FAFAFA; /* Lighter grey background */
            text-align: center; /* Center our text */
        }
    </style>
</head>
<body>
<div style="width: 90%; margin: auto">
    <?php foreach($results as $name => $products) { ?>
    <h3><?php echo($name); ?></h3>
    <table>
        <tr>
            <th>Name</th>
            <th>Website Price</th>
            <th>Current Price</th>
            <th>Comment</th>
        </tr>
        <?php foreach($products as $product) { ?>
        <tr>
            <td><?php echo($product['name']) ?></td>
            <td><?php echo($product['price']) ?></td>
            <td></td>
            <td></td>
        </tr>
        <?php } ?>
    </table>
    <?php }?>
</div>

</body>
</html>