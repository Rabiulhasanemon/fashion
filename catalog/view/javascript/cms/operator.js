app.onReady(window, "$", function () {
    var modal = '<div class="popup f-in"><div class="popup-inner p-15 bg-white" style="min-width: 350px; text-align: left"><div class="popup-title"></div><div class="popup-body"></div><span class="popup-close" href="#"></span></div></div>';

    function Modal(title, url, afterLoad) {
        var _self = this, body = this.body = $(modal);
        body.find(".popup-body").load(url,undefined, afterLoad);
        body.find(".popup-title").text(title);
        body.find(".popup-close").on("click", function() {
            _self.close();
        });
        _self.render()
    }

    var _m = Modal.prototype;

    _m.render = function () {
        $("body").append(this.body)
        this.body.show()
    };


    _m.close = function () {
        this.body.remove()
    };

    $(function () {
        var productId = $("input[name=product_id]").val(),
            priceUpdateBtnRow = $('<div class="row"><button type="button" data-loading-text="Loading..." class="btn btn-primary btn-lg btn-block">Update Price</button></div>'),
            priceUpdateBtn = priceUpdateBtnRow.find("button");
        $.ajax({
            url: "operator/login/isLoggedIn",
            dataType: "json",
            success: function (resp) {
                if(resp.is_logged_in == true) {
                    $(".cart-option").after(priceUpdateBtnRow)
                }
            }
        });

        var modal;
        priceUpdateBtnRow.on("click", function () {
            modal  = new Modal("Price Update Request", "index.php?route=operator/product_update_request&product_id=" + productId , afterModalRender)
        });

        function afterModalRender() {
            modal.body.find("form").on("submit", function (e) {
                e.preventDefault();
                var $this = $(this), submitBtn = $this.find("button[type=submit]");

                $.ajax({
                    url: 'operator/product_update_request/add',
                    method: 'post',
                    dataType: 'json',
                    data: new FormData(this),
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        submitBtn.text('Submitting ..');
                        submitBtn.attr("disabled", "disabled")
                    },
                    complete: function() {
                        submitBtn.text('Submit');
                        submitBtn.removeAttr("disabled")
                    },
                    success: function(json) {
                        $this.find('.text-danger').remove();

                        if (json['error']) {
                            $this.prepend('<div class="text-danger">' + json['error'] + '</div>');
                        }

                        if (json['success']) {
                            modal.close()
                            alert(json['success']);
                        }
                        if (json['reload']) {
                            location.reload();
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            })
        }
    });
}, 20);