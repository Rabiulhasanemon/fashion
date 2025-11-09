<html>
<head>
</head>

<body onLoad="document.send_form.submit();">
<form name="send_form" method="post" action="<?php echo $server_url; ?>" >
  <input type="hidden" value="<?php echo $invoice; ?>" name="encryptedInvoicePay">
</form>
</body>

</html>