$(function () {
  var doc = $("html"),
    body = $("body"),
    overlay = $(".overlay");

  function Toggle(items) {
    var _self = this;
    items.forEach(function (value) {
      var toggle = $(value.toggle);
      toggle.on("click", function () {
        if (toggle.hasClass("close")) {
          _self.hide(value);
        } else {
          _self.show(value);
        }
      });
    });
  }

  var _t = Toggle.prototype;

  _t.show = function (item) {
    var _self = this;
    if (_self.active) _self.hide(_self.active);
    var target = $(item.target),
      toggle = $(item.toggle);
    if (item.overlay) overlay.addClass("open");
    if (item.no_scroll) $("body").addClass("no-scroll");
    toggle.addClass("close");
    target.addClass("open");
    this.active = item;
  };

  _t.hide = function (item) {
    var _self = this;
    item = item || _self.active;
    if (!item) return;
    var target = $(item.target),
      toggle = $(item.toggle);
    toggle.removeClass("close");
    target.removeClass("open");
    overlay.removeClass("open");
    $("body").removeClass("no-scroll");
  };

  var toggle = new Toggle([
    { toggle: "#nav-toggler", target: "#column-left", overlay: true, no_scroll: true },
    { toggle: ".search-toggle", target: ".search-wrap" },
    { toggle: ".cart-toggler", target: ".mini-cart" },
    {
      toggle: "#lc-toggle, .lc-close",
      target: "#column-left",
      overlay: true,
      no_scroll: true,
    },
  ]);
  overlay.on("click", function () {
    toggle.hide();
  });

  $(document).on("click", function (e) {
    var target = $(e.target);
    if (target.is(".mini-cart .close i")) {
      toggle.hide();
    }

    if (target.data("dismiss")) {
      target.parents("." + target.data("dismiss")).remove();
    }
  });

  $(".drop-open a").on("click", function (e) {
    var $this = $(this),
      parent = $this.parent();
    parent.toggleClass("open");
    var target = $(e.target);
    if (
      (isMobile() && target.is(".drop-open > a")) ||
      target.is(".responsive-menu > .drop-open > a")
    ) {
      e.preventDefault();
    }
  });

  if ($(".category-description").text() == "") {
    $(".category-description").remove();
  }

  // Adding the clear Fix
  window.addClearFix = function () {
    var cols1 = $("#column-right, #column-left").size();
    if (cols1 == 2) {
      $("#content .product-layout:nth-child(2n+2)").after(
        '<div class="clearfix visible-md visible-sm"></div>'
      );
    } else if (cols1 == 1) {
      $("#content .product-layout:nth-child(3n+3)").after(
        '<div class="clearfix visible-lg"></div>'
      );
    } else {
      $("#content .product-layout:nth-child(4n+4)").after(
        '<div class="clearfix"></div>'
      );
    }
  };
  addClearFix();

  var btnList = $("#list-view"),
    btnGrid = $("#grid-view");
  var listingView = {
    list: function () {
      btnList.addClass("active");
      btnGrid.removeClass("active");
      $("#content .row > .product-layout").attr(
        "class",
        "col-xs-12 col-md-12 product-layout list"
      );
      localStorage.setItem("display", "list");
    },
    grid: function () {
      $(this).addClass("active");
      $("#list-view").removeClass("active");
      cols = $("#column-right, #column-left").size();
      if (cols == 2) {
        $("#content .product-layout").attr(
          "class",
          "col-xs-12 col-md-6 product-layout grid"
        );
      } else if (cols == 1) {
        $("#content .product-layout").attr(
          "class",
          "col-xs-12 col-md-4 product-layout grid"
        );
      } else {
        $("#content .product-layout").attr(
          "class",
          "col-xs-12 col-md-3 product-layout grid"
        );
      }

      localStorage.setItem("display", "gird");
    },
  };
  btnList.on("click", listingView.list);
  btnGrid.on("click", listingView.grid);

  if (localStorage.getItem("display") === "list") {
    listingView.list();
  }

  function SlideShow(elm) {
    var _self = this;
    _self.elm = elm;
    _self.slides = [];
    _self.dots = [];
    var dotsWrap = $("<div>", {
      class: "slider-dot",
    });
    elm.find(".mySlides").each(function (i, value) {
      var slide = $(value);
      _self.slides[i] = slide;
      var dot = $("<span>", {
        class: "dot",
      });
      dotsWrap.append(dot);
      _self.dots[i] = dot;
      slide.hide();
      dot.on("click", function () {
        _self.showSlides(i);
      });
    });
    elm.append(dotsWrap);
    _self.index = 0;
    _self.showSlides(0);
  }

  var _s = SlideShow.prototype;

  _s.showSlides = function (slideIndex) {
    var _self = this;
    if (_self.timer) {
      clearTimeout(_self.timer);
    }
    if (slideIndex >= _self.slides.length) {
      slideIndex = 0;
    }
    _self.slides[_self.index].hide();
    _self.slides[slideIndex].show();
    _self.dots[_self.index].removeClass("active");
    _self.dots[slideIndex].addClass("active");
    _self.index = slideIndex;
    _self.timer = setTimeout(_self.showSlides.bind(this, ++slideIndex), 5000);
  };

  $(".banner-slider").each(function () {
    new SlideShow($(this));
  });

  var miniCart = $(".mini-cart");
  miniCart.delegate(".quantity-wrapper .value", "click", function (e) {
    var target = $(e.target),
      qtyWrap = target.parents(".quantity-wrapper"),
      type = target.attr("data-type"),
      qtyInp = qtyWrap.find("input"),
      qty = qtyInp.val(),
      key = qtyInp.attr("name");
    if (target.is("[disabled]")) {
      return;
    }
    qty = parseInt(qty) ? parseInt(qty) : 1;
    cart.update(key, type === "inc" ? ++qty : --qty);
  });

  window.addEventListener("scroll", function () {
    if (doc.scrollTop() > 265) {
      body.addClass("on-sticky");
    } else {
      body.removeClass("on-sticky");
    }
  });

  $(".hero-slide-wrapper").owlCarousel({
    items: 3,
    loop: true,
    autoplay: true,
    autoplayTimeout: 3000,
    autoplayHoverPause: true,
    dots: false,
    responsive: {
      0: {
        items: 1,
        margin: 10,
      },
      600: {
        items: 1,
      },
      1000: {
        items: 2,
      },
      1200: {
        items: 3,
      },
    },
  });

  $(".to-top").on("click", function (e) {
    e.preventDefault();
    $("html").scrollTo(0, 600);
  });

  // Add to  Cart Animation
  $(".add-to-cart").click(function () {
    $(".mc-toggler").addClass("bounce");
    setTimeout(function () {
      $(".mc-toggler").removeClass("bounce");
    }, 900);
  });

  //region POPUP
  function showPopup(data) {
    var position = data["position"],
      ads =
        '<a href="' +
        data["url"] +
        '"><img src="' +
        data["image"] +
        '" alt="' +
        data["title"] +
        '" class="img-responsive"></a>';
    if (position == 1) {
      var popup = new Popup(ads);
      popup.render();
      localStorage.showed = current;
      setTimeout(function () {
        popup.close();
      }, 14 * 1000);
    } else {
      $(".ads-pos-" + position).html(ads);
    }
  }

  var current = new Date().getTime(),
    showed = localStorage.showed,
    lastVisited = localStorage.lastVisited,
    ads_list = [];
  showed = parseInt(showed);
  showed = isNaN(showed) ? null : showed;
  lastVisited = parseInt(lastVisited);
  lastVisited = isNaN(lastVisited) ? null : lastVisited;
  var popupDuration = app.popupDuration ? app.popupDuration : 12;
  if (
    lastVisited &&
    current - lastVisited < 60 * 60 * 100 &&
    (!showed || current - showed > popupDuration * 60 * 60 * 1000)
  ) {
    ads_list.push(1);
  }
  $(".ads").each(function () {
    ads_list.push($(this).attr("data-position"));
  });
  if (ads_list.length) {
    var data = "device_type=" + (isMobile() ? 1 : 3);
    ads_list.forEach(function (value) {
      data += "&ads_position[]=" + value;
    });
    $.ajax({
      url: "api/ads",
      data: data,
      method: "post",
      dataType: "json",
      success: function (resp) {
        resp.forEach(function (value) {
          showPopup(value);
        });
        var ads = resp[1];
        if (!ads || !ads.image) return;
        showPopup(ads.image, ads.title, ads.url);
      },
    });
  }
  var hour = new Date().getHours();
  if (hour >= 9 && hour <= 21) {
    $(".svg-icon svg").show();
  }
  localStorage.lastVisited = current;
  //endregion

  /*---------------------------------------

                    Quantity

                ---------------------------------------*/

  String.prototype.getDecimals ||
    (String.prototype.getDecimals = function () {
      var a = this,
        b = ("" + a).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);

      return b ? Math.max(0, (b[1] ? b[1].length : 0) - (b[2] ? +b[2] : 0)) : 0;
    }),
    jQuery(document).on("click", ".plus, .minus", function () {
      var a = jQuery(this).closest(".quantity").find(".qty"),
        b = parseFloat(a.val()),
        c = parseFloat(a.attr("max")),
        d = parseFloat(a.attr("min")),
        e = a.attr("step");

      (b && "" !== b && "NaN" !== b) || (b = 0),
        ("" !== c && "NaN" !== c) || (c = ""),
        ("" !== d && "NaN" !== d) || (d = 0),
        ("any" !== e && "" !== e && void 0 !== e && "NaN" !== parseFloat(e)) ||
          (e = 1),
        jQuery(this).is(".plus")
          ? c && b >= c
            ? a.val(c)
            : a.val((b + parseFloat(e)).toFixed(e.getDecimals()))
          : d && b <= d
          ? a.val(d)
          : b > 0 && a.val((b - parseFloat(e)).toFixed(e.getDecimals())),
        a.trigger("change");
    });
});
