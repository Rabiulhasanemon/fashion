<!DOCTYPE html>
<html>
<head>
</head>
<body onLoad="document.send_form.submit();">
<form name="send_form" method="get" action="https://www.ipdcez.com/Customer/CIFUI" >
    <input type="hidden" name="VendorId" value="27">
    <input type="hidden" name="StoreId" value="455">
    <input type="hidden" name="Name" value="<?php echo $name; ?>">
    <input type="hidden" name="DeliveryAddress" value="<?php echo $shipping_address_1; ?>">
    <input type="hidden" name="ProductDetails" value="Order-<?php echo $order_id; ?>">
    <input type="hidden" name="ProductPrice" value="<?php echo $total; ?>">
    <input type="hidden" name="ExchangePrice" value="0">
    <input type="hidden" name="ProductCode" value="<?php echo $order_id; ?>">
</form>
</body>
</html>