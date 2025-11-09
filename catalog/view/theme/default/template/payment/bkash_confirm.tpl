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
<p style="text-align: center">Connecting to bKash Server .............</p>
<button id="bKash_button"></button>

<script type="text/javascript">
    var paymentRequest = { amount: '<?php echo $amount; ?>', intent:'sale'};
    var endpoints = (function () {
        function redirect(url) {
            window.location.href = url
        }
        return {
            create: function () {
                $.ajax({
                    url: '<?php echo $create_payment_url; ?>',
                    type:'GET',
                    contentType: 'application/json',
                    success: function(data) {
                        if(data && data.paymentID != null){
                            paymentID = data.paymentID;
                            bKash.create().onSuccess(data);
                        } else {
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
            execute: function () {
                var _self = this;
                $.ajax({
                    url: '<?php echo $execute_payment_url; ?>' + "?paymentID="+paymentID,
                    type: 'GET',
                    contentType:'application/json',
                    timeout: 30000,
                    success: function(data){
                        if(data.paymentID != null){
                            redirect('<?php echo $success; ?>');
                        } else {
                            bKash.execute().onError();
                            redirect('<?php echo $cancel; ?>');
                        }
                    },
                    error: function(xhr, status, response){
                        if(status === "timeout") {
                            _self.query()
                        } else {
                            bKash.execute().onError();
                            redirect('<?php echo $cancel; ?>');
                        }

                    }
                });
            },
            query: function () {
                var _self = this;
                $.ajax({
                    url: '<?php echo $query_payment_url; ?>' + "?paymentID="+paymentID,
                    type: 'GET',
                    contentType:'application/json',
                    success: function(data){
                        if(data.paymentID != null){
                            redirect('<?php echo $success; ?>');
                        } else {
                            bKash.execute().onError();
                            redirect('<?php echo $cancel; ?>');
                        }
                    },
                    error: function(xhr, status, response){
                        bKash.execute().onError();
                        redirect('<?php echo $cancel; ?>');
                    }
                });
            }
        }
    })();
    $(function () {
        bKash.init({
            paymentMode: 'checkout',
            paymentRequest: paymentRequest,
            createRequest: function(request){
                endpoints.create()
            },
            executeRequestOnAuthorization: function(){
                endpoints.execute()
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
