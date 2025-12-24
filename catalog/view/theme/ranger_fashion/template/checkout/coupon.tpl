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
                    $('.alert, .ctp-new-alert').remove();

                    if (json['error']) {
                        var alertRow = $(".ctp-new-alert-wrapper");
                        if(alertRow.length == 0) {
                            alertRow = $('.ctp-new-container .ctp-new-alert-wrapper');
                        }
                        if(alertRow.length == 0) {
                            alertRow = $('<div class="ctp-new-alert-wrapper"></div>').prependTo($('.ctp-new-container'));
                        }
                        alertRow.append('<div class="ctp-new-alert ctp-new-alert-danger"><i class="fa fa-exclamation-circle"></i> <span>' + json['error'] + '</span><button type="button" class="ctp-new-alert-close" data-dismiss="alert">&times;</button></div>');
                        $("html, body").animate({ scrollTop: 0 }, 600);
                    }

                    if (json['success']) {
                        var alertRow = $(".ctp-new-alert-wrapper");
                        if(alertRow.length == 0) {
                            alertRow = $('.ctp-new-container .ctp-new-alert-wrapper');
                        }
                        if(alertRow.length == 0) {
                            alertRow = $('<div class="ctp-new-alert-wrapper"></div>').prependTo($('.ctp-new-container'));
                        }
                        alertRow.append('<div class="ctp-new-alert ctp-new-alert-success"><i class="fa fa-check-circle"></i> <span>' + json['success'] + '</span><button type="button" class="ctp-new-alert-close" data-dismiss="alert">&times;</button></div>');
                        
                        // Reload cart totals and page content via AJAX
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    }

                    if (json['redirect'] && !json['success']) {
                        location = json['redirect'];
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Coupon error:', error);
                    $('.alert, .ctp-new-alert').remove();
                    var alertRow = $(".ctp-new-alert-wrapper");
                    if(alertRow.length == 0) {
                        alertRow = $('<div class="ctp-new-alert-wrapper"></div>').prependTo($('.ctp-new-container'));
                    }
                    alertRow.append('<div class="ctp-new-alert ctp-new-alert-danger"><i class="fa fa-exclamation-circle"></i> <span>An error occurred. Please try again.</span><button type="button" class="ctp-new-alert-close" data-dismiss="alert">&times;</button></div>');
                }
            });
        });
    }, 10)
    //--></script>