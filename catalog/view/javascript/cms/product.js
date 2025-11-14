app.onReady(window, "$", function () { $(function () {
    function LiteBox(images) {
        var _self = this;
        _self.images = images;
        _self.length = images.size();
        _self.popup = new Popup('<div class="lb-body"></div><div class="lb-footer"><div class="title"></div><div class="counter"></div></div>');
        var popupBody = _self.popup.el.find(".popup-inner");
        var beforeHandler = $('<span class="prev button"></span>'), afterHandler = $('<span class="next button"></span>');
        if(images.size() > 1) {
            popupBody.before(beforeHandler);
            popupBody.after(afterHandler);
        }
        beforeHandler.on("click", function () {
           _self.prev()
        });
        afterHandler.on("click", function () {
            _self.next()
        });
        this.images.each(function (i, img) {
            $(img).on("click", function (e) {
                e.preventDefault();
                _self.show(i)
            })
        });
        _self.index = 0;
    }

    var _l = LiteBox.prototype;
    _l.load = function () {
        var _self = this, a = _self.images.get(_self.index), el = _self.popup.el
        var image = '<img title="' + a.title + '" alt="' + a.title + '" src="' + a.href + '"/>'
        el.find(".lb-body").html(image)
        el.find(".lb-footer .title").text(a.title)
        el.find(".lb-footer .counter").text(_self.index + 1 + ' of ' + _self.length)
    };

    _l.show = function (index) {
        var _self = this;
        _self.index = index;
        _self.load();
        _self.popup.render()
    };

    _l.prev = function () {
        var _self = this;
        _self.index--;
        if(_self.index < 0) {
            _self.index = _self.length - 1
        }
        _self.load()
    };

    _l.next = function () {
        var _self = this;
        _self.index++;
        if(_self.index >= _self.length) {
            _self.index = 0
        }
        _self.load()
    };

    function addToCart(callback) {
        $.ajax({
            url: 'checkout/cart/add',
            method: 'post',
            data: $('#input-enable-emi:checked, #product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-cart').button('loading');
            },
            complete: function() {
                $('#button-cart').button('reset');
            },

            success: function(json) {
                if (json['error']) {
                    if (json['error']['option']) {
                        for (i in json['error']['option']) {
                            var element = $('#input-option' + i.replace('_', '-'));

                            if (element.find('b')) {
                                element.find('b').html('<span class="text-danger">' + json['error']['option'][i] + '</span>');
                            } else {
                                element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                            }
                        }
                    } else if(typeof json['error'] === "string") {
                        showMsgPopup(`<div class="msg-wrap">${json['error']}</div>`, 'error')
                    }

                    // Highlight any found errors
                    $('.text-danger').parent().addClass('has-error');
                }

                if (json['success']) {
                    setTimeout(function () {
                        $('#cart > button').html('<i class="fa fa-shopping-cart"></i><span id="cart-total">' + json['total'] + '</span>');
                        $('#cart .count').text(json['total'])
                        $('#cart .amount').text(json['total_amount'])
                        $('.cart .value').text(json['count'])
                    }, 100);

                    $("#cart, .bottom-item.cart").addClass("bounce");
                    setTimeout(function () {
                        $("#cart, .bottom-item.cart").removeClass("bounce")
                    }, 900);
                    cart.reload();
                    if(app.messageConfig && app.messageConfig.cart === "popup") {
                        showMsgPopup(json['success_popup'], "success")
                    } else {
                        showMessage(json['success'], "success")
                    }
                    fbq && fbq('track', 'AddToCart', {
                        content_ids: [product_id],
                        content_type: 'product',
                        value: json['item_total'],
                        currency: 'BDT'
                    });
                    callback && callback()
                }
            }
        });
    }
    $('#button-cart').on('click', function() {
        addToCart()
    });
    $('#buy-now').on('click', function() {
        addToCart(function () {
            location.href = 'checkout/onepagecheckout'
        })
    });

    $('#product .p-opt input[type=\'radio\']').on('change', function () {
        var $this = $(this)
        $.ajax({
            url: 'product/product/variation',
            method: 'post',
            data: $('#product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product select'),
            dataType: 'json',
            beforeSend: function () {
            },
            success: function (json) {
                if(json['error']) return;
                var wrap = $('#product')
                if (json['special']) {
                    wrap.find(".product-price ins").text(json['special'])
                    wrap.find(".product-price del").text(json['price'])
                    wrap.find(".cash .price .price-new").text(json['special'])
                    wrap.find(".cash .price .price-old").text(json['price'])
                } else {
                    wrap.find(".product-price").text(json['price'])
                    wrap.find(".cash .price").text(json['price'])

                }
                wrap.find(".product-regular-price").text(json['regular_price'])
                wrap.find(".emi .price").text(json['emi_price'] + "/month")
                wrap.find(".emi .p-tag.regular").text("Regular Price: " + json['regular_price'])

                if(json['thumb']) {
                    $('.main-img').attr('src', json['thumb'])
                }

            },
            error: function (xhr, status, error) {
                console.error("Failed to update the price", error);
            }
        });
        var wrap = $this.parents(".p-opt");
        wrap.find(".p-opt-lbl b").text(wrap.find("label:has(:checked)").text())
    });
    
    $('#review, #question').delegate('.pagination a', 'click', function(e) {
        e.preventDefault();
        var panel = $(this).parents("#review, #question");
        panel.removeClass("f-in").addClass("f-out");
        panel.load(this.href);
        panel.removeClass('f-out').addClass("f-in")
    });

    $('#review').load('index.php?route=product/product/review&product_id=' + product_id);

    $('#question').load('product/product/question?product_id=' + product_id);

    $('#button-review, #button-question').on('click', function(e) {
        e.preventDefault();
        var $this = $(this), form = $this.parents("form");
        var formDataObj = {};
        (new FormData(form.get(0))).forEach((value, key) => (formDataObj[key] = value));
        $.ajax({
            url: form.attr("action"),
            method: 'post',
            data: formDataObj,
            dataType: 'json',
            beforeSend: function() {
                $this.button('loading');
            },
            complete: function() {
                $this.button('reset');
            },
            success: function(json) {
                // Remove existing alerts
                $('.alert-success, .alert-danger, .premium-notification').remove();
                
                if (json['error']) {
                    form.before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                }
                
                if (json['success']) {
                    // Show premium notification
                    showPremiumNotification(json['success']);
                    
                    // Reset form
                    form.get(0).reset();
                    
                    // Reload review list if it's a review submission
                    if ($this.attr('id') === 'button-review') {
                        setTimeout(function() {
                            $('#review').load('index.php?route=product/product/review&product_id=' + product_id);
                        }, 1000);
                    }
                }
            },
            error: function(xhr, status, error) {
                $('.alert-success, .alert-danger, .premium-notification').remove();
                var errorMsg = 'An error occurred. Please try again.';
                try {
                    var jsonResponse = JSON.parse(xhr.responseText);
                    if (jsonResponse['error']) {
                        errorMsg = jsonResponse['error'];
                    }
                } catch(e) {
                    // Use default error message
                }
                form.before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + errorMsg + '</div>');
            }
        });
    });
    
    // Premium Notification Function
    function showPremiumNotification(message) {
        // Remove any existing notifications
        $('.premium-notification').remove();
        
        // Create premium notification
        var notification = $('<div class="premium-notification">' +
            '<div class="premium-notification-content">' +
            '<div class="premium-notification-icon">' +
            '<i class="fa fa-check-circle"></i>' +
            '</div>' +
            '<div class="premium-notification-text">' +
            '<h4>Thank You!</h4>' +
            '<p>' + message + '</p>' +
            '</div>' +
            '<button class="premium-notification-close" onclick="$(this).parent().parent().fadeOut(300, function(){$(this).remove()})">' +
            '<i class="fa fa-times"></i>' +
            '</button>' +
            '</div>' +
            '</div>');
        
        // Add to body
        $('body').append(notification);
        
        // Show with animation
        setTimeout(function() {
            notification.addClass('show');
        }, 100);
        
        // Auto hide after 5 seconds
        setTimeout(function() {
            notification.removeClass('show');
            setTimeout(function() {
                notification.remove();
            }, 300);
        }, 5000);
    }


    $(".share-ico").on("click", function () {
        shareOnSocialMedea($(this).data("type"), location.href, $(".product-title h1").text(), $(".main-img").attr("src"))
    });


    $(".view-more").on("click", function (e) {
        e.preventDefault();
        var area =  $("#specification");
        if(area.length === 0) area = $("#description");
        $("html,body").scrollTo(area.offset().top - 140, 600)
    });

    $("[data-area]").on("click", function (e) {
        e.preventDefault();
        var $this = $(this);
        $("html,body").scrollTo($("#" + $this.data("area")).offset().top - 140, 600)
    });

    var qtyBox = $('.quantity [name=quantity]');
    $(".quantity > span").on("click", function () {
        var $this = $(this), qty = +qtyBox.val();
        if($this.is(".increment")) {
            qty++
        } else  {
            qty--
        }
        if(isNaN(qty) || qty < 1) {
            qtyBox.val(1)
        } else {
            qtyBox.val(qty)
        }
    });
    new LiteBox($('.product-images a'))
})}, 20);