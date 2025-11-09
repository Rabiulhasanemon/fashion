<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Merchant</title>
  <meta name="viewport" content="width=device-width" ,="" initial-scale="1.0/">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrom=1">
  <script src="<?php echo $home; ?>catalog/view/javascript/lib/jquery/jquery-2.2.4.js" type="text/javascript"></script>
  <script id = "myScript" src="<?php echo $script_url; ?>"></script>

</head>

<body>
<p style="text-align: center">Connecting bKash Server .............</p>
<button id="bKash_button"></button>

<script type="text/javascript">

    $(function () {

        var paymentRequest = { amount: '<?php echo $amount; ?>', intent:'sale'};
        console.log(JSON.stringify(paymentRequest));

        bKash.init({
            paymentMode: 'checkout',
            paymentRequest: paymentRequest,
            createRequest: function(request){
                console.log('=> createRequest (request) :: ');
                console.log(request);

                $.ajax({
                    url: '<?php echo $create_payment_url; ?>',
                    type:'GET',
                    contentType: 'application/json',
                    success: function(data) {
                        console.log('got data from create  ..');
                        console.log('data ::=>');
                        console.log(data);

                        if(data && data.paymentID != null){
                            paymentID = data.paymentID;
                            bKash.create().onSuccess(data);
                        }
                        else {
                            console.log('error');
                            bKash.create().onError();
                        }
                    },
                    error: function(){
                        console.log('error');
                        bKash.create().onError();
                    }
                });
            },
            executeRequestOnAuthorization: function(){
                console.log('=> executeRequestOnAuthorization');
                $.ajax({
                    url: '<?php echo $execute_payment_url; ?>' + "?paymentID="+paymentID,
                    type: 'GET',
                    contentType:'application/json',
                    success: function(data){
                        console.log('got data from execute  ..');
                        console.log('data ::=>');
                        console.log(data);

                        if(data.paymentID != null){
                            window.location.href = '<?php echo $success; ?>';
                        } else {
                            bKash.execute().onError();
                            window.location.href = '<?php echo $cancel; ?>';
                        }
                    },
                    error: function(){
                        bKash.execute().onError();
                        window.location.href = '<?php echo $cancel; ?>';
                    }
                });
            },
            onClose: function () {
                window.location.href = '<?php echo $cancel; ?>';
            }
        });
        $("#bKash_button").trigger('click');
    });
</script>

</body>
</html>
