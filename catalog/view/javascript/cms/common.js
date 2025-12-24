//Prototype
var app = window.app || {};
app.event = (function () {
	function Event() {
		this.events = {}
    }
    var _e = Event.prototype;
	_e.on = function (type, listener) {
		var listeners = this.events[type] || []
		listeners.push(listener)
        this.events[type] = listeners
    };

    _e.trigger = function (type, args) {
        var listeners = this.events[type] || []
		listeners.forEach(function (listener) {
			listener.apply(null, args)
		})
    };

	return new Event();
})();

Number.prototype.toCommaFormat = function () {
    var parts = this.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
};

String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

function getURLVar(key) {
	var value = {};
	var query = String(document.location).split('?');
	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		}
	}
	return ""
}
function updateURLVar(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re) && value) {
        uri =  uri.replace(re, '$1' + key + "=" + value + '$2');
    } else if(uri.match(re) && !value) {
        uri = uri.replace(re, '$1');
    } else if (value) {
        uri =  uri + separator + key + "=" + value;
    }
    if(uri.endsWith("&") || uri.endsWith("?")) {
    	uri = uri.substr(0, uri.length - 1)
	}
    return uri
}

function shareOnSocialMedea(type, sharing_url, sharing_name, sharing_img) {
    switch(type) {
        case 'twitter':
            window.open('https://twitter.com/intent/tweet?text=' + sharing_name + ' ' + encodeURIComponent(sharing_url), 'sharertwt', 'toolbar=0,status=0,width=640,height=445');
            break;
        case 'facebook':
            window.open('http://www.facebook.com/sharer.php?u=' + sharing_url, 'sharer', 'toolbar=0,status=0,width=660,height=445');
            break;
        case 'linkedin':
            window.open('https://www.linkedin.com/shareArticle?mini=true&url=' + sharing_url + "title=" + sharing_name, 'sharer', 'toolbar=0,status=0,width=660,height=445');
            break;
        case 'google-plus':
            window.open('https://plus.google.com/share?url=' + sharing_url, 'sharer', 'toolbar=0,status=0,width=660,height=445');
            break;
        case 'pinterest':
            var img_url = sharing_img;
            window.open('http://www.pinterest.com/pin/create/button/?media=' + img_url + '&url=' + sharing_url, 'sharerpinterest', 'toolbar=0,status=0,width=660,height=445');
            break;
        case 'whatsapp':
            window.open('http://api.whatsapp.com/send?text=' + sharing_url, 'sharerpinterest', 'toolbar=0,status=0,width=660,height=445');
            break;
        case 'messenger':
            window.open('https://www.facebook.com/dialog/send?app_id=322516586049137&link=' +  encodeURIComponent(sharing_url) + '&redirect_uri=' + encodeURIComponent(location.href), 'toolbar=0,status=0,width=660,height=445');
            break;
    }
}

function isMobile() {
    return window.innerWidth < 992;
}

$(function() {
	$('.text-danger').each(function() {
        var element = $(this).parents(".form-group").addClass('has-error');
    });
	var searchBox = $('#search input[name=\'search\']');
	searchBox.parent().find('button').on('click', function() {
		var url = $('base').attr('href') + 'product/search';
		var value = searchBox.val();
		if (value) {
			url += '?search=' + encodeURIComponent(value);
		}
		var category_id = $('#search select[name=\'category_id\']').val();
		if(category_id) {
			url += '&category_id=' + category_id
		}
		location = url;
        fbq && fbq('track', 'Search', {
            search_string: value
		});
    });
	searchBox.on('keydown', function(e) {
		if (e.keyCode === 13) {
			searchBox.parent().find('button').trigger('click');
		}
	});
    $(document).delegate(".checkout-btn", "click", function () {
        try {
            fbq && fbq('track', 'InitiateCheckout');
        } catch (ex) {}
    });

    var cartBtn = $(".mc-toggler");
    cartBtn.on("click", function () {
    	if(cartBtn.is(".loaded")) return;
    	cart.reload();
    	cartBtn.addClass("loaded")
    })
});

var isShowing = false;
function hideMessage() {
	$(".notification").removeClass("active").removeClass("success").removeClass("error")
}
function showMessage(message, type) {
	if(isShowing) {
        hideMessage()
		clearTimeout(isShowing)
	}
    $(".notification").addClass("active").addClass(type).html(message)

	isShowing = setTimeout(hideMessage, 2000)

}


function showMsgPopup(message, type) {
    if(!type) type = 'success';
    var popup = new Popup(message, 'mgs-popup ' + type)
    var duration =  5
    popup.render();
    setTimeout(function() {
        popup.close()
    }, duration * 10000000)
}

var cart = {
	'add': function(product_id, quantity, successCallback) {
        var _self = this;
		$.ajax({
			url: 'checkout/cart/add',
			method: 'post',
			data: 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},			
			success: function(json) {
				$('.alert, .text-danger').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
				    if(successCallback) {
                        successCallback()
                        return;
				    }

					setTimeout(function () {
						$('#cart > button').html('<i class="fa fa-shopping-cart"></i><span id="cart-total">' + json['total'] + '</span>');
                        $('#cart .count').text(json['total'])
                        $('#cart .amount').text(json['total_amount'])
                        $('.mc-toggler .value').text(json['count'])
					}, 100);

					$("#cart, .bottom-item.cart").addClass("bounce")
					setTimeout(function () {
						$("#cart, .bottom-item.cart").removeClass("bounce")
                    }, 900);
					_self.reload();
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
                }
                if (json['error']) {
                    showMessage(json['error'], "error")
				}
			}
		});
	},
	'update': function(key, quantity) {
		var _self = this;
		$.ajax({
			url: 'checkout/cart/edit',
			method: 'post',
			data: 'ajax=true&quantity[' + encodeURIComponent(key) + ']=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},			
			success: function(json) {
                setTimeout(function () {
                    $('#cart > button').html('<i class="fa fa-shopping-cart"></i><span id="cart-total">' + json['total'] + '</span>');
                    $('#cart .count').text(json['total'])
                    $('#cart .amount').text(json['total_amount'])
                    $('.cart .value').text(json['count'])
                    $('.mc-toggler .value').text(json['count'])
                }, 100);
				_self.reload();
			}
		});
	},
	'remove': function(key, reloadPage) {
		var _self = this;
		$.ajax({
			url: 'checkout/cart/remove',
			method: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},			
			success: function(json) {
                if(reloadPage) {
					location.reload()
				} else {
                    setTimeout(function () {
                        $('#cart > button').html('<i class="fa fa-shopping-cart"></i><span id="cart-total">' + json['total'] + '</span>');
                        $('#cart .count').text(json['total'])
                        $('#cart .amount').text(json['total_amount'])
                        $('.cart .value').text(json['count'])

                        $('.mc-toggler .value').text(json['count'])

                    }, 100);
                    _self.reload()
				}
			}
		});
	},
	reload: function () {
		var content = $('#mini-cart .content');
		content.append("<div class='mask'></div>")
        app.event.trigger("before_cart_load", [content])
        content.load('common/cart/info', {}, function () {
			app.event.trigger("cart_load", [content])
        });
    }
};

var voucher = {
	'add': function() {

	},
	'remove': function(key) {
		$.ajax({
			url: 'checkout/cart/remove',
			method: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'checkout/cart';
				} else {
					$('#cart > ul').load('common/cart/info ul li');
				}
			}
		});
	}
};

// Animated Notification Function
function showAnimatedNotification(message, type, icon) {
    // Remove any existing notifications
    $('.ruplexa-animated-notification').remove();
    
    // Set icon and colors based on type
    var notificationIcon = icon || 'fa-check-circle';
    var bgGradient = '';
    var iconBg = '';
    
    if (type === 'wishlist') {
        bgGradient = 'linear-gradient(135deg, #10503D 0%, #A68A6A 100%)';
        iconBg = 'rgba(255, 255, 255, 0.2)';
        notificationIcon = 'fa-heart';
    } else if (type === 'compare') {
        bgGradient = 'linear-gradient(135deg, #10503D 0%, #A68A6A 100%)';
        iconBg = 'rgba(255, 255, 255, 0.2)';
        notificationIcon = 'fa-exchange';
    } else {
        bgGradient = 'linear-gradient(135deg, #10503D 0%, #A68A6A 100%)';
        iconBg = 'rgba(255, 255, 255, 0.2)';
    }
    
    // Create animated notification
    var notification = $('<div class="ruplexa-animated-notification">' +
        '<div class="ruplexa-notification-content">' +
        '<div class="ruplexa-notification-icon">' +
        '<i class="fa ' + notificationIcon + '"></i>' +
        '</div>' +
        '<div class="ruplexa-notification-text">' +
        '<p>' + message + '</p>' +
        '</div>' +
        '<button class="ruplexa-notification-close" onclick="$(this).closest(\'.ruplexa-animated-notification\').remove()">' +
        '<i class="fa fa-times"></i>' +
        '</button>' +
        '</div>' +
        '</div>');
    
    // Set background gradient
    notification.find('.ruplexa-notification-content').css('background', bgGradient);
    notification.find('.ruplexa-notification-icon').css('background', iconBg);
    
    // Add to body
    $('body').append(notification);
    
    // Show with slide-in animation
    setTimeout(function() {
        notification.addClass('ruplexa-notification-show');
    }, 100);
    
    // Auto hide after 4 seconds
    setTimeout(function() {
        notification.removeClass('ruplexa-notification-show');
        setTimeout(function() {
            notification.remove();
        }, 400);
    }, 4000);
}

var wishlist = {
	'add': function(product_id) {
		$.ajax({
			url: 'account/wishlist/add',
			method: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				if (json['success']) {
					// Show animated notification
					showAnimatedNotification(json['success'], 'wishlist', 'fa-heart');
				}

				if (json['info']) {
					// Show animated notification
					showAnimatedNotification(json['info'], 'wishlist', 'fa-heart');
				}

				// Update wishlist count
				if (json['total']) {
					$('#wishlist-total span').html(json['total']);
					$('#wishlist-total').attr('title', json['total']);
				}

                fbq && fbq('track', 'AddToWishlist');
            }
		});
	},
	'remove': function() {

	}
};

var compare = {
	'add': function(product_id) {
		$.ajax({
			url: 'product/compare/add',
			method: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				if (json['success']) {
					// Show animated notification
					showAnimatedNotification(json['success'], 'compare', 'fa-exchange');
					
					// Update compare count
					if (json['total']) {
						$('#compare-total').html(json['total']);
					}
					if (json['count']) {
						$('#compare .value').text(json['count']);
						// Update compare badge in footer
						$('.ruplexa-compare-badge').text(json['count']);
					}
				}
			}
		});
	},
	'remove': function() {

	}
};

var restock_request = {
	add: function (product_id) {
        var _self = this;
        $.ajax({
            url: 'checkout/restock_request/add',
            method: 'post',
            data: {
                product_id: product_id,
                referrer: location.href
            },
            dataType: 'json',
            beforeSend: function() {
            },
            complete: function() {
            },
            success: function(json) {
                if (json['redirect']) {
                    location = json['redirect'];
                }
                if (json['success']) {
                    showMessage(json['success'], "success")
                }
                if (json['error']) {
                    showMessage(json['error'], "error")
                }
            }
        });
    }

};
/* Agree to Terms */
$(document).delegate('.agree', 'click', function(e) {
	e.preventDefault();

	$('#modal-agree').remove();

	var element = this;

	$.ajax({
		url: $(element).attr('href'),
		method: 'get',
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-agree" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
			html += '      </div>';
			html += '      <div class="modal-body">' + data + '</div>';
			html += '    </div';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#modal-agree').modal('show');
		}
	});
});

// Autocomplete */
$.fn.autocomplete = function(option) {
    return this.each(function() {
        var $this = $(this), self = this;
        this.timer = null;
        this.items = [];

        $.extend(this, option);

        $this.attr('autocomplete', 'off');

        // Focus
        $this.on('focus', function() {
            self.request();
        });

        // Blur
        $this.on('blur', function() {
            setTimeout(function(object) {
                object.hide();
            }, 200, this);
        });

        // Keydown
        $this.on('keydown', function(event) {
            switch(event.keyCode) {
                case 27: // escape
                    self.hide();
                    break;
                default:
                    self.request();
                    break;
            }
        });

        // Click
        self.click = function(event) {
            value = $(event.target).parent().attr('data-value');

            if (value && this.items[value]) {
                event.preventDefault();
                this.select(this.items[value]);
            }
        };

        // Show
        self.show = function() {
            var pos = $this.position(), dropdown = $this.siblings('.dropdown-menu');
            dropdown.css({
                top: pos.top + $this.outerHeight() + "px",
                left: pos.left + "px"
            });
            dropdown.show();
        };

        // Hide
        self.hide = function() {
            $this.siblings('ul.dropdown-menu').hide();
        };

        // Request
        this.request = function() {
            clearTimeout(this.timer);

            self.timer = setTimeout(function(object) {
                object.source($(object).val(), object.response.bind(object));
            }, 200, this);
        };

        // Response
        self.response = function(json) {
            var html = '';

            if (json.length) {
                for (i = 0; i < json.length; i++) {
                    self.items[json[i]['value']] = json[i];
                }

                for (i = 0; i < json.length; i++) {
                    if (!json[i]['category']) {
                        html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
                    }
                }

                // Get all the ones with a categories
                var category = [];

                for (i = 0; i < json.length; i++) {
                    if (json[i]['category']) {
                        if (!category[json[i]['category']]) {
                            category[json[i]['category']] = [];
                            category[json[i]['category']]['name'] = json[i]['category'];
                            category[json[i]['category']]['item'] = [];
                        }

                        category[json[i]['category']]['item'].push(json[i]);
                    }
                }

                for (i in category) {
                    html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

                    for (j = 0; j < category[i]['item'].length; j++) {
                        html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
                    }
                }
            }

            if (html) {
                self.show();
            } else {
                self.hide();
            }

            $this.siblings('ul.dropdown-menu').html(html);
        };

        $this.after('<ul class="dropdown-menu"></ul>');
        $this.siblings('ul.dropdown-menu').delegate('a', 'click', this.click.bind(this));

    });
};

$.fn.button = function (action, text) {
	text = text || "Loading...";
    var _self = this;
    if (_self.size() ===0)return;
    if(action === "loading") {
        _self.addClass("disabled");
        _self.attr("disabled", "");
        _self.data("old_text", _self.text());
        _self.text(text)
    } else {
        _self.removeClass("disabled");
        _self.removeAttr("disabled");
        _self.text(_self.data("old_text"))
    }
};

$.fn._scrollTo = function (to, duration) {
	var _self = this;
    var element = null;
	if(_self.size() > 1) {
		for (var i = 0; i < _self.size(); i++) {
			var el = _self.elements[i];
			if(el.scrollHeight > el.clientHeight) {
				element = el;
				break
			}
        }
        if(element) _self = $(element);
	} else {
		element = _self.get(0)
	}
	if(element == null)  {
		this.each(function () {
			this.scrollTop = to;
        });
		return
	}
    if (duration <= 0 || _self.size() === 0) return;
    var difference = to - element.scrollTop;
    var perTick = difference / duration * 10;

    setTimeout(function() {
        element.scrollTop = element.scrollTop + perTick;
        if (element.scrollTop === to) return;
        this._scrollTo(to, duration - 10);
    }.bind(_self), 10);
};
$.fn.scrollTo = function (to, duration) {
	try {
        window.scroll({
            top: to,
            behavior: "smooth"
        })
	} catch(ex) {
		window.scroll(0, to)
	}
};
function Popup(content) {
    var _self = this;
	var el = $('<div class="popup"><div class="popup-inner">' + content + '<span class="popup-close" href="#"></span></div></div>');
    this.el = el;
    el.find(".popup-close").on("click", function() {
        _self.close();
    });

	el.on("click", function (e) {
		if($(e.target).is(".popup")) _self.close()
    })
}

var _p = Popup.prototype;

_p.render = function () {
	var _self = this;
	if(_self.timer) clearTimeout(_self.timer);
    _self.el.addClass("f-in").removeClass("f-out");
    $("body").append(_self.el);
};

_p.close = function () {
    var _self = this;
	_self.el.addClass("f-out").removeClass("f-in");
    _self.timer = setTimeout(function () {
		_self.el.remove()
    }, 2000)
};

function Tab(elm) {
	var _self = this
    _self.headers  = elm.find("li");
	var active = null
	_self.headers.each(function () {
		var header = $(this)
		$("#" + header.attr("data-tab")).hide();
		header.on("click", function () {
			_self.activate(header)
        });
		if(header.is(".active")) {
			active = header
		}
    });
	if(active == null) {
		active  = $(_self.headers.get(0))
	}
	_self.activate(active)
}
var _t = Tab.prototype;
_t.activate = function (header) {
	var _self = this;
	if(_self.active) {
		_self.active.removeClass("active");
        $("#" + _self.active.attr("data-tab")).hide();
	}
    $("#" + header.attr("data-tab")).show();
	header.addClass("active")
	_self.active = header
};
