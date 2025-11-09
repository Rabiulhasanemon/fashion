<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            font-weight: 400;
            font-size: 13.3px;
            line-height: 20px;
            width: 100%;
        }
        .header {
            border-bottom: 1px solid black;
            padding-bottom: 11px;
            margin-bottom: 10px;
        }
        .wrapper {
            font-family: sans-serif;
            width: 700px;
            margin: auto;
        }

        .upper-block > div {
            display: inline-block;
            vertical-align: top;
        }
        .image-block {
            width: 300px;
        }

        .image-block img {
            max-width: 100%;
        }

        .details-block {
            padding-top: 15px;
            width: 400px;
        }
        label {
            font-weight: bold;
        }
        .info-row label {
            display: inline-block;
            width: 130px;
        }
        span.value::before {
            content: ":";
            margin: 20px;
        }
        .info-row {
            padding-bottom: 10px;
        }
        .description p {
            margin-top: .5em;
        }

        .lower-block {
            margin-top: 25px;
        }
        .table caption {
            text-align: left;
            padding: 11px;
            border: 1px solid #ddd;
            font-size: 16px;
            font-weight: bold;
        }
        .table-bordered {
            border: 1px solid #ddd;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
        }

        table tbody td:nth-child(1) {
            border-right: 1px solid #e6eaed;
            width: 130px;
            text-align: right;
            font-weight: bold;
        }

        table td {
            border-color: #e6eaed;
            border-width: 1px 0;
            border-top: 1px solid #e6eaed;
            padding: 15px 20px;
        }
    </style>
    <meta charset="UTF-8">
    <title>Product Details || <?php echo $data["product"]["name"]?></title>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <img src="http://www.startech.com.bd/image/catalog/logo.png"/>
        <address>
            Shop # 934-935,943-944
            Level # 09, Multiplan Center
            New Elephant Road
            Dhaka-1205
            Phone: 029660744 ,
            Mobile: 01755666789
        </address>
    </div>
    <div class="upper-block">
        <div class="image-block">
            <img id="image" src="<?php echo $thumb ?>">
        </div><div class="details-block">
            <h3><?php echo $data["product"]["name"]?></h3>
            <div class="info-row">
                <label>Brand</label><span class="value"><?php echo $data["product"]["manufacturer"]?></span>
            </div>
            <div class="info-row">
                <label>Model</label><span class="value"><?php echo $data["product"]["model"]?></span>
            </div>
            <div class="info-row">
                <label>Availability</label><span class="value"><?php echo $data["product"]["stock_status"]?></span>
            </div>
            <div class="info-row">
                <label>Price</label><span class="value"><?php echo $data["product"]["price"]?> TK</span>
            </div>
            <div class="description">
                <label>Description:</label>
                <p><?php echo $description ?></p>
            </div>
        </div>
    </div>
    <div class="lower-block">
        <table class="table table-bordered">
            <caption>Specifications</caption>
            <tbody>

            <?php foreach ($data["attributes"] as $attribute) { ?>
            <tr>
                <td><?php echo $attribute['name']; ?></td>
                <td><?php echo $attribute['text']; ?></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    var image = document.getElementById("image"), isPrinted = false
    image.addEventListener("load", function() {
        if(isPrinted) return
        isPrinted = true
        window.print()
    });
    if(image.complete && !isPrinted) {
        isPrinted = true
        window.print()
    }
</script>
</body>
</html>