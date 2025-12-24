<!DOCTYPE html>
<html  dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo $title; ?></title>
    <base href="<?php echo $base; ?>/" />
    <?php if ($description) { ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php } ?>
    <?php if ($keywords) { ?>
    <meta name="keywords" content= "<?php echo $keywords; ?>" />
    <?php } ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if ($icon) { ?>
    <link href="<?php echo $icon; ?>" rel="icon" />
    <?php } ?>
    <?php foreach ($links as $link) { ?>
    <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
    <?php } ?>
    <?php foreach ($metas as $meta) { ?>
    <meta property="<?php echo $meta['property']; ?>" content="<?php echo $meta['content']; ?>" />
    <?php } ?>
    <script type="text/javascript">
        var $d = document, app = {
            messageConfig: {cart: "popup"},
            onReady: function(d,a,e,f){a=Array.isArray(a)?a:[a];for(var g=!0,b=d,c=0;c<a.length;c++){var h=a[c];if("undefined"==typeof b[h]){g=!1;break}b=b[h]}g?e():f&&setTimeout(function(){app.onReady(d,a,e,--f)},2E3)}
        };
    </script>
    <script src="catalog/view/javascript/lib/jquery/jquery-2.2.4.js" type="text/javascript"></script>
    <script src="catalog/view/javascript/cms/common.js" type="text/javascript"></script>
    <script src="catalog/view/javascript/cms/search_suggestion.js?v=33" type="text/javascript"></script>
    <script src="catalog/view/theme/ranger_fashion/javascript/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="catalog/view/theme/ranger_fashion/javascript/swiper-bundle.min.js" type="text/javascript"></script>
    <script src="catalog/view/theme/ranger_fashion/javascript/site.js" type="text/javascript"></script>

    <!-- ==== CSS Dependencies Start ==== -->
<!-- Add Font Awesome CDN in your <head> section -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
            href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap"
            rel="stylesheet"
    />
    
    <link href="catalog/view/theme/ranger_fashion/stylesheet/icon.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/swiper-bundle.min.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/common.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/main.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/responsive.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/product-image-standard.css" rel="stylesheet">
    <link href="catalog/view/css/noUi/nouislider.min.css" rel="stylesheet">
    <link href="catalog/view/css/owl-carousel/owl.carousel.min.css" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/product-listing.css?v=11" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/category.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/product.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/accounts.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/checkout.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/new_header.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/module-standard.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/unified-module-headings.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/module-reward-points.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/module-block-title.css?v=1" rel="stylesheet">

    <?php foreach ($styles as $style) { ?>
    <link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
    <?php } ?>
    <?php foreach ($synScripts as $script) { ?>
    <script src="<?php echo $script; ?>" type="text/javascript"></script>
    <?php } foreach ($scripts as $script) { ?>
    <script  src="<?php echo $script; ?>" type="text/javascript"></script>
    <?php } ?>
    <?php echo $google_analytics; ?>

 

</head>

<body>
<div class="notification"></div>
<header class="new-desktop-header">
    <!-- Top Announcement Bar -->
    <div class="header-top-announcement">
        <div class="container">
            <span class="best-offer-badge">BEST OFFER</span>
            <span>Free Shipping on Orders Over 2000 tk</span>
            <a href="<?php echo isset($shop_now_url) ? $shop_now_url : 'index.php?route=product/special'; ?>" class="shop-now-link">Shop Now ></a>
        </div>
    </div>

    <!-- Main Header -->
    <div class="main-header-area">
        <div class="container main-header-container">
            <!-- Logo -->
            <div class="header-logo-container">
                <a href="<?php echo $base; ?>">
                    <img src="<?php echo $logo; ?>" alt="AQC" title="AQC" width="150" />
                </a>
            </div>

            <!-- Search Bar -->
            <div class="header-search-container">
                <?php echo $search; ?>
            </div>

            <!-- Icons -->
            <div class="header-icons-container">
                <a href="<?php echo isset($offer_url) ? $offer_url : 'index.php?route=information/offer'; ?>" class="header-icon-item offer-deals-btn ruplexa-offer-deals">
                    <span class="icon"><i class="fas fa-tag"></i></span>
                    <span class="icon-text">Offer Deals</span>
                    <?php if (isset($active_offer) && !empty($active_offer) && isset($active_offer['date_end_timestamp'])) { ?>
                    <div class="ruplexa-offer-timer">
                        <div class="ruplexa-timer-label">Ends in:</div>
                        <div class="ruplexa-timer-display" data-end-time="<?php echo $active_offer['date_end_timestamp']; ?>">
                            <span class="ruplexa-timer-item">
                                <span class="ruplexa-timer-value" id="ruplexa-days">00</span>
                                <span class="ruplexa-timer-label-small">Days</span>
                            </span>
                            <span class="ruplexa-timer-separator">:</span>
                            <span class="ruplexa-timer-item">
                                <span class="ruplexa-timer-value" id="ruplexa-hours">00</span>
                                <span class="ruplexa-timer-label-small">Hours</span>
                            </span>
                            <span class="ruplexa-timer-separator">:</span>
                            <span class="ruplexa-timer-item">
                                <span class="ruplexa-timer-value" id="ruplexa-minutes">00</span>
                                <span class="ruplexa-timer-label-small">Min</span>
                            </span>
                            <span class="ruplexa-timer-separator">:</span>
                            <span class="ruplexa-timer-item">
                                <span class="ruplexa-timer-value" id="ruplexa-seconds">00</span>
                                <span class="ruplexa-timer-label-small">Sec</span>
                            </span>
                        </div>
                    </div>
                    <?php } ?>
                </a>
                <a href="<?php echo isset($order) ? $order : '#'; ?>" class="header-icon-item">
                    <span class="icon"><i class="fas fa-map-marker-alt"></i></span>
                    <span class="icon-text">Track Order</span>
                </a>
                <?php if ($logged) { ?>
                <a href="<?php echo $account; ?>" class="header-icon-item">
                    <span class="icon"><i class="fas fa-user"></i></span>
                    <span class="icon-text">Sign In</span>
                </a>
                <?php } else { ?>
                <a href="<?php echo $login; ?>" class="header-icon-item">
                    <span class="icon"><i class="fas fa-user"></i></span>
                    <span class="icon-text">Sign In</span>
                </a>
                <?php } ?>
                <a href="<?php echo isset($wishlist) ? $wishlist : '#'; ?>" class="header-icon-item">
                    <span class="icon"><i class="fas fa-heart"></i></span>
                    <span class="icon-text">Wishlist</span>
                </a>
                <a href="javascript:void(0)" class="header-icon-item cart-icon cart-toggler mc-toggler">
                    <span class="icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count-badge"><?php preg_match('/\\d+/', $text_items, $matches); echo $matches[0] ?? '0'; ?></span>
                    </span>
                    <span class="icon-text">Cart</span>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Navigation Bar (visible on desktop below new header) -->
<div class="desktop-navigation-bar">
    <div class="container">
        <div class="desktop-nav-wrapper">
            <?php echo $navigation ?>
        </div>
    </div>
</div>

<script>
// Fix search button click issue on desktop header
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.new-desktop-header #search input[type="text"]');
    const searchButton = document.querySelector('.new-desktop-header #search button');
    
    if (searchInput && searchButton) {
        // Prevent button click when clicking on input
        searchInput.addEventListener('click', function(e) {
            e.stopPropagation();
            this.focus();
        });
        
        // Only trigger search when button is directly clicked
        searchButton.addEventListener('click', function(e) {
            e.stopPropagation();
            // Let the existing handler in common.js handle the search
        });
        
        // Ensure input can be focused
        searchInput.addEventListener('focus', function() {
            this.style.zIndex = '2';
        });
        
        searchInput.addEventListener('blur', function() {
            this.style.zIndex = '1';
        });
    }
});
</script>
<style>
  /* Import Material Symbols and Font Awesome */
    @import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined');
    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'); /* For WhatsApp & Messenger icons */

    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }
    /* Floating Icon Container */
    .floating-container {
      position: fixed;
      bottom: 20px;
      right: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      z-index: 9999;
    }

    /* Main Icon */
    .main-icon {
      width: 50px;
      height: 50px;
      background-color: #000000;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
      z-index: 99999;
      border: 1px solid var(--secondaryColor);
    }
    .text.cart-count{
        position: absolute;
        top: -10px;
        right: -10px;
        background: #fafafa;
        border-radius: 50px;
        text-align: center;
        font-size: 13px;
        height: 20px;
        width: 20px;
    }

    .main-icon .material-symbols-outlined {
      font-size: 26px;
      color: #fff;
    }

    /* Dropdown Content */
    .icon-content {
        left: -86px;
      display: flex;
      flex-direction: column;
      align-items: center;
      position: absolute;
      bottom: 47px;
      opacity: 0;
      transform: translateY(10px);
      transition: opacity 0.3s ease, transform 0.3s ease;
    }

    /* Show dropdown on hover */
    .floating-container:hover .icon-content {
      opacity: 1;
      transform: translateY(0);
    }

    /* WhatsApp and Messenger links */
    .icon-content a {
          background-color: #000000;
    color: #ffffff;
      text-decoration: none;
      display: flex;
      align-items: center;
      padding: 10px 15px;
      border-radius: 25px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      font-size: 14px;
      font-weight: bold;
      margin-bottom: 10px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .icon-content a i {
      margin-right: 10px;
      font-size: 18px;
    }

    .icon-content a:hover {
      transform: scale(1.1);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }

    .header-top-wrapper .header-logo.hl a{
        margin: 0px;
    }
    /* Colors for icons */
    .whatsapp {
      color: #25D366; /* WhatsApp Green */
    }

    .messenger {
      color: #0078FF; /* Messenger Blue */
    }
    @media (max-width: 767px) {
    .ts {
        font-size: 10px;
    }
        .header-top-wrapper {
            padding: 5px 0px 15px;
        }
}
.header-t{
    background: var(--secondaryColor);
}
.header-top-area{
    background: white;
        border-bottom: 3px solid var(--primaryColor);
}
.ts,
.ts a{
    color:white;
    font-size: 12px;
}
.shipping-info.ts {
    display: flex;
    justify-content: end;
}

/* Ruplexa Premium Offer Deals with Timer - Cosmetics Theme */
.ruplexa-offer-deals {
    position: relative;
    background: linear-gradient(135deg, #FF6B9D 0%, #FF8E9B 100%);
    border-radius: 12px;
    padding: 12px 18px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    min-width: 140px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(255, 107, 157, 0.3);
    text-decoration: none;
}

.ruplexa-offer-deals:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 107, 157, 0.4);
    text-decoration: none;
}

.ruplexa-offer-deals .icon {
    color: #ffffff;
    font-size: 20px;
}

.ruplexa-offer-deals .icon-text {
    color: #ffffff;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.ruplexa-offer-timer {
    width: 100%;
    margin-top: 4px;
}

.ruplexa-timer-label {
    font-size: 9px;
    color: rgba(255, 255, 255, 0.9);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
    text-align: center;
    font-weight: 600;
}

.ruplexa-timer-display {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 3px;
}

.ruplexa-timer-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 6px;
    padding: 4px 6px;
    min-width: 28px;
    backdrop-filter: blur(4px);
}

.ruplexa-timer-value {
    font-size: 14px;
    font-weight: 700;
    color: #ffffff;
    line-height: 1.2;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.ruplexa-timer-label-small {
    font-size: 8px;
    color: rgba(255, 255, 255, 0.85);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-top: 2px;
    font-weight: 500;
}

.ruplexa-timer-separator {
    color: rgba(255, 255, 255, 0.7);
    font-size: 12px;
    font-weight: 700;
    margin: 0 2px;
    animation: ruplexa-timer-blink 1s infinite;
}

@keyframes ruplexa-timer-blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

/* Old Header Offer Deals Timer */
.ruplexa-offer-deals-old {
    position: relative;
}

.ruplexa-offer-timer-old {
    margin-top: 4px;
    text-align: center;
}

.ruplexa-timer-display-old {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    font-size: 11px;
    color: #FF6B9D;
    font-weight: 600;
}

.ruplexa-timer-value-old {
    background: linear-gradient(135deg, #FF6B9D 0%, #FF8E9B 100%);
    color: #ffffff;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 700;
    min-width: 24px;
    text-align: center;
    display: inline-block;
}

/* Responsive Design */
@media (max-width: 991px) {
    .ruplexa-offer-deals {
        min-width: 120px;
        padding: 10px 14px;
    }
    
    .ruplexa-timer-item {
        min-width: 24px;
        padding: 3px 5px;
    }
    
    .ruplexa-timer-value {
        font-size: 12px;
    }
    
    .ruplexa-timer-label-small {
        font-size: 7px;
    }
}

@media (max-width: 767px) {
    .ruplexa-offer-deals {
        min-width: 100px;
        padding: 8px 12px;
    }
    
    .ruplexa-timer-display {
        gap: 2px;
    }
    
    .ruplexa-timer-item {
        min-width: 20px;
        padding: 2px 4px;
    }
    
    .ruplexa-timer-value {
        font-size: 11px;
    }
    
    .ruplexa-timer-label-small {
        font-size: 6px;
    }
    
    .ruplexa-timer-separator {
        font-size: 10px;
        margin: 0 1px;
    }
}

/* Offer Deals Link Item Styling (Older Header) - Match Default Style */
.link-item.offer-deals-link-item {
    background: transparent;
    border: none;
    margin-right: 10px;
    transition: color 0.3s ease;
    box-shadow: none;
}

.link-item.offer-deals-link-item:hover {
    background: transparent;
    transform: none;
    box-shadow: none;
}

.link-item.offer-deals-link-item a {
    color: #333;
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 0;
}

.link-item.offer-deals-link-item:hover a {
    color: #A68A6A;
}

.link-item.offer-deals-link-item .icon {
    color: inherit;
}

.link-item.offer-deals-link-item .link-text {
    color: inherit;
    font-weight: 500;
    font-size: 13px;
}
</style>
<header>
      <div class=" header-t">
          
        <div class="container">
            <div class="row d-flex justify-content-between align-items-center">
                <div class="col-md-6 col-sm-6 email-info ts">
                    <p><i class="fa fa-envelope"></i><span><a href="mailto:info@fitstationbd.com">info@fitstationbd.com</a></span></p>
                </div>
                
                <div class="col-md-6 col-sm-6 shipping-info ts">
                    <p><i class="fa fa-phone"></i><span><a href="tel: +8801339799750">+8801339799750</a></span></p>
                </div>
            </div>
        </div>
        
    </div>
    <div class="header-top-area">
        <div class="container">
            <div class="header-top-wrapper">
                <div id="nav-toggler"><span></span></div>
                <div class="header-logo hl">
                    <a href="<?php echo $base; ?>"><img src="<?php echo $logo; ?>" alt="Ranger Fashion" title="Ranger Fashion" width="200" height="41"/> </a>
                </div>
                 <?php echo $search; ?>
                 <div class="header-bottom-area">
        
            
    </div>
                <div class="header-links">
                    <div class="header-bottom-wrapper">
                <div class="header-menu">
                    <?php echo $navigation ?>
                </div>
            </div>
                    <div class="link-item offer-deals-link-item ruplexa-offer-deals-old">
                        <a href="<?php echo isset($offer_url) ? $offer_url : 'index.php?route=information/offer'; ?>">
                            <div class="icon">
                                <span class="material-icons">local_offer</span>
                            </div>
                            <span class="link-text">Deals</span>
                            <?php if (isset($active_offer) && !empty($active_offer) && isset($active_offer['date_end_timestamp'])) { ?>
                            <div class="ruplexa-offer-timer-old">
                                <div class="ruplexa-timer-display-old" data-end-time="<?php echo $active_offer['date_end_timestamp']; ?>">
                                    <span class="ruplexa-timer-value-old" id="ruplexa-days-old">00</span>d
                                    <span class="ruplexa-timer-value-old" id="ruplexa-hours-old">00</span>h
                                    <span class="ruplexa-timer-value-old" id="ruplexa-minutes-old">00</span>m
                                </div>
                            </div>
                            <?php } ?>
                        </a>
                    </div>
                    <div class="link-item">
                        <?php if ($logged) { ?>
                        <a href="<?php echo $account; ?>">
                            <div class="icon">
                                <span class="material-icons"> account_circle </span>
                            </div>
                        </a>
                        <?php } else { ?>
                        <a href="<?php echo $login; ?>">
                            <div class="icon">
                                <span class="material-icons"> account_circle </span>
                            </div>
                        </a>
                        <?php } ?>

                    </div>
                    <div class="link-item cart cart-toggler mc-toggler">
                        <a href="javascript:void(0)">
                            <div class="icon">
                                <span class="material-icons"> shopping_cart </span>
                                <div class="text cart-count"><span class="value count">
                                <?php $items = $text_items;
                                    preg_match('/\d+/', $items, $matches);
                                    echo  $matches[0]; ?></span>
                            </div>
                            </div>
                            
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .hamburger_menu {
                position: fixed;
                top: calc(100vh - 50%);
                right: 0px;
                z-index: 9999;
                -moz-transition: all .5s ease-out;
                -webkit-transition: all .5s ease-out;
                -o-transition: all .5s ease-out;
            }
            .hamburger_menu .menu_social {
                width: 40px;
                text-align: center;
            }
            .hamburger_menu .menu_social a {
                display: block;
                background: #000;
                padding: 8px 0px;
                line-height: 18px;
                font-size: 20px;
                color: #fff;
                margin-bottom: 5px;
                border: 1px solid #000;
                -moz-transition: all .5s ease-out;
                -webkit-transition: all .5s ease-out;
                -o-transition: all .5s ease-out;
            }
            
            .hamburger_menu .menu_social a.youtube{
                background: #FF0000;
            }
            .hamburger_menu .menu_social a.facebook{
                background: #1877f2;
            }
            .hamburger_menu .menu_social a.instagram{
                background: linear-gradient(90deg, rgba(138,58,185,1) 0%, rgba(233,89,80,1) 35%, rgba(188,42,141,1) 100%);
            }
        </style>
        <div class="hamburger_menu">
            <div class="menu_social">
                <a class="facebook" href="https://www.facebook.com/fitstationbd" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                <a class="youtube" href="" target="_blank"><i class="fa-brands fa-youtube"></i></a>
                <a class="instagram" href="" target="_blank"><i class="fa-brands fa-instagram"></i></a>        
            </div>
        </div>
<div class="floating-container">
  <!-- Main Icon -->
  <div class="main-icon">
    <span class="material-symbols-outlined">forum</span> <!-- Main icon -->
  </div>
  <!-- Dropdown Content -->
  <div class="icon-content">
    <a href="https://wa.me/+8801339799750" target="_blank">
      <i class="fab fa-whatsapp whatsapp"></i>01339799750
    </a>
    <a href="https://m.me/548231825040097?source=qr_link_share" target="_blank">
      <i class="fab fa-facebook-messenger messenger"></i> Messenger
    </a>
  </div>
</div>
    
    </div>
</header>

<script>
// Ruplexa Premium Offer Timer Counter
(function() {
    function initOfferTimer() {
        const timerDisplays = document.querySelectorAll('.ruplexa-timer-display[data-end-time], .ruplexa-timer-display-old[data-end-time]');
        
        timerDisplays.forEach(function(timerDisplay) {
            const endTime = parseInt(timerDisplay.getAttribute('data-end-time'));
            if (!endTime) return;
            
            function updateTimer() {
                const now = Math.floor(Date.now() / 1000);
                const timeLeft = endTime - now;
                
                if (timeLeft <= 0) {
                    // Timer expired
                    if (timerDisplay.classList.contains('ruplexa-timer-display')) {
                        timerDisplay.innerHTML = '<span style="color: #fff; font-size: 11px; padding: 4px;">Expired</span>';
                    } else {
                        timerDisplay.innerHTML = '<span style="color: #FF6B9D; font-size: 11px;">Expired</span>';
                    }
                    return;
                }
                
                const days = Math.floor(timeLeft / 86400);
                const hours = Math.floor((timeLeft % 86400) / 3600);
                const minutes = Math.floor((timeLeft % 3600) / 60);
                const seconds = timeLeft % 60;
                
                // Update new timer format
                if (timerDisplay.classList.contains('ruplexa-timer-display')) {
                    const daysEl = document.getElementById('ruplexa-days');
                    const hoursEl = document.getElementById('ruplexa-hours');
                    const minutesEl = document.getElementById('ruplexa-minutes');
                    const secondsEl = document.getElementById('ruplexa-seconds');
                    
                    if (daysEl) daysEl.textContent = String(days).padStart(2, '0');
                    if (hoursEl) hoursEl.textContent = String(hours).padStart(2, '0');
                    if (minutesEl) minutesEl.textContent = String(minutes).padStart(2, '0');
                    if (secondsEl) secondsEl.textContent = String(seconds).padStart(2, '0');
                }
                
                // Update old timer format
                if (timerDisplay.classList.contains('ruplexa-timer-display-old')) {
                    const daysEl = document.getElementById('ruplexa-days-old');
                    const hoursEl = document.getElementById('ruplexa-hours-old');
                    const minutesEl = document.getElementById('ruplexa-minutes-old');
                    
                    if (daysEl) daysEl.textContent = String(days).padStart(2, '0');
                    if (hoursEl) hoursEl.textContent = String(hours).padStart(2, '0');
                    if (minutesEl) minutesEl.textContent = String(minutes).padStart(2, '0');
                }
            }
            
            // Update immediately
            updateTimer();
            
            // Update every second
            setInterval(updateTimer, 1000);
        });
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initOfferTimer);
    } else {
        initOfferTimer();
    }
})();
</script>

<div class="mini-cart" id="mini-cart">
    <div class="content">
        <div class="loader"></div>
    </div>
</div>
