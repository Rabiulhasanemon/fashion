<div class="input-group">
  <input type="text" name="coupon" value="<?php echo $coupon; ?>" placeholder="<?php echo $entry_coupon; ?>" id="input-coupon" class="form-control" />
  <span class="input-group-btn"><input type="button" value="<?php echo $button_coupon; ?>" id="button-coupon" data-loading-text="<?php echo $text_loading; ?>"  class="btn btn-primary" /></span>
</div>
<script type="text/javascript"><!--
    app.onReady(window, "$", function () {
        $('#button-coupon').on('click', function() {
            $.ajax({
                url: 'checkout/coupon/coupon',
                type: 'post',
                data: 'coupon=' + encodeURIComponent($('input[name=\'coupon\']').val()),
                dataType: 'json',
                beforeSend: function() {
                    $('#button-coupon').button('loading');
                },
                complete: function() {
                    $('#button-coupon').button('reset');
                },
                success: function(json) {
                    $('.alert').remove();

                    if (json['error']) {
                        var alertRow = $(".container.alert-container");
                        if(alertRow.size() == 0) {
                            alertRow = $('<div class="container alert-container"></div>').insertAfter($('.after-header'));
                        }
                        alertRow.append('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                        $("html,body").scrollTo(0, 600)
                    }

                    if (json['redirect']) {
                        location = json['redirect'];
                    }
                }
            });
        });
    }, 10)
    //--></script>