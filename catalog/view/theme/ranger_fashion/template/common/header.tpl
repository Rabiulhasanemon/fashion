<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">

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
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script type="text/javascript">
        var $d = document, app = {
            messageConfig: {cart: "popup"},
            onReady: function(d,a,e,f){a=Array.isArray(a)?a:[a];for(var g=!0,b=d,c=0;c<a.length;c++){var h=a[c];if("undefined"==typeof b[h]){g=!1;break}b=b[h]}g?e():f&&setTimeout(function(){app.onReady(d,a,e,--f)},2E3)}
        };
        fbq = function () { }
    </script>
    <script src="catalog/view/javascript/lib/jquery/jquery-2.2.4.js" type="text/javascript"></script>
    <script src="catalog/view/javascript/cms/common.js" type="text/javascript"></script>
    <script src="catalog/view/javascript/cms/search_suggestion.js?v=33" type="text/javascript"></script>
    <script src="catalog/view/theme/ranger_fashion/javascript/owl.carousel.min.js"></script>
    <script src="catalog/view/theme/ranger_fashion/javascript/site.js" type="text/javascript"></script>

    <!-- ==== CSS Dependencies Start ==== -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    
    <link href="catalog/view/theme/ranger_fashion/stylesheet/icon.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/common.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/main.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/responsive.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/responsive-enhancement.css?v=1" rel="stylesheet">
    <link href="catalog/view/css/noUi/nouislider.min.css" rel="stylesheet">
    <link href="catalog/view/css/owl-carousel/owl.carousel.min.css" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/product-listing.css?v=11" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/category.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/product.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/accounts.css?v=1" rel="stylesheet">
    <link href="catalog/view/theme/ranger_fashion/stylesheet/checkout.css?v=1" rel="stylesheet">

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

<body class="<?php echo $class; ?>">
<div class="notification"></div>

<!-- AQC Style Header Start -->
<header class="aqc-header-wrapper">
    <!-- Promotional Banner -->
    <div class="aqc-promo-banner">
        <div class="aqc-promo-container">
            <div class="aqc-promo-content">
                <span class="aqc-promo-badge">BEST OFFER</span>
                <span class="aqc-promo-text">Free Shipping on Orders Over $230</span>
                <a href="<?php echo $base; ?>/information/offer" class="aqc-promo-link">
                    Shop Now <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <div class="aqc-main-header">
        <div class="aqc-header-container">
            <div class="aqc-header-content">
                <!-- Logo Section -->
                <div class="aqc-logo-section">
                    <a href="<?php echo $base; ?>" class="aqc-logo-link">
                        <img src="<?php echo $logo; ?>" alt="<?php echo $name; ?>" title="<?php echo $name; ?>" class="aqc-logo-img" />
                    </a>
                </div>

                <!-- Search Section -->
                <div class="aqc-search-section">
                    <div class="aqc-search-wrapper">
                        <div class="aqc-search-container" id="aqc-search">
                            <input type="text" name="search" value="" placeholder="Search in..." autocomplete="off" class="aqc-search-input">
                            <ul class="dropdown-menu"></ul>
                            <button type="button" class="aqc-search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

                <!-- Actions Section -->
                <div class="aqc-actions-section">
                    <nav class="aqc-nav-actions">
                        <!-- Track Order -->
                        <div class="aqc-nav-item">
                            <a href="<?php echo $base; ?>/account/order" class="aqc-nav-link">
                                <i class="fas fa-truck"></i>
                                <div class="aqc-nav-text">
                                    <span class="aqc-nav-label">Track Order</span>
                                </div>
                            </a>
                        </div>

                        <!-- Sign In -->
                        <div class="aqc-nav-item">
                        <?php if ($logged) { ?>
                            <a href="<?php echo $account; ?>" class="aqc-nav-link">
                                <i class="fas fa-user"></i>
                                <div class="aqc-nav-text">
                                    <span class="aqc-nav-label">Account</span>
                            </div>
                        </a>
                        <?php } else { ?>
                            <a href="<?php echo $login; ?>" class="aqc-nav-link">
                                <i class="fas fa-user"></i>
                                <div class="aqc-nav-text">
                                    <span class="aqc-nav-label">Sign In</span>
                            </div>
                        </a>
                        <?php } ?>
                    </div>

                        <!-- Wishlist -->
                        <div class="aqc-nav-item">
                            <a href="<?php echo $wishlist; ?>" class="aqc-nav-link">
                                <i class="fas fa-heart"></i>
                                <div class="aqc-nav-text">
                                    <span class="aqc-nav-label">Wishlist</span>
                            </div>
                        </a>
                    </div>

                        <!-- Cart -->
                        <div class="aqc-nav-item aqc-cart-item">
                            <a href="javascript:void(0)" class="aqc-nav-link cart-toggler mc-toggler">
                                <i class="fas fa-shopping-cart"></i>
                                <div class="aqc-nav-text">
                                    <span class="aqc-nav-label">Cart</span>
                                    <span class="aqc-cart-count"><?php
                                        $items = $text_items;
                                        preg_match('/\d+/', $items, $matches);
                                        echo isset($matches[0]) ? $matches[0] : '0';
                                    ?></span>
                            </div>
                        </a>
                    </div>

                        <!-- More Menu -->
                        <div class="aqc-nav-item aqc-more-item">
                            <a href="javascript:void(0)" class="aqc-nav-link aqc-more-toggle">
                                <i class="fas fa-bars"></i>
                                <div class="aqc-nav-text">
                                    <span class="aqc-nav-label">More</span>
                                </div>
                            </a>
                            <!-- Dropdown Menu -->
                            <div class="aqc-more-dropdown">
                                <div class="aqc-dropdown-content">
                                    <?php echo $navigation ?>
                                </div>
                            </div>
                        </div>
                    </nav>

                    <!-- Mobile Menu Toggle -->
                    <div class="aqc-mobile-toggle">
                        <button class="aqc-mobile-btn" id="aqc-mobile-menu-btn">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="aqc-mobile-menu" id="aqc-mobile-menu">
        <div class="aqc-mobile-menu-content">
            <!-- Mobile Search -->
            <div class="aqc-mobile-search">
                <div class="aqc-mobile-search-container">
                    <input type="text" placeholder="Search in..." class="aqc-mobile-search-input">
                    <button class="aqc-mobile-search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div class="aqc-mobile-nav">
                <?php echo $navigation ?>
            </div>

            <!-- Mobile Actions -->
            <div class="aqc-mobile-actions">
                <a href="<?php echo $base; ?>/account/order" class="aqc-mobile-action">
                    <i class="fas fa-truck"></i>
                    <span>Track Order</span>
                </a>
                <a href="<?php echo $wishlist; ?>" class="aqc-mobile-action">
                    <i class="fas fa-heart"></i>
                    <span>Wishlist</span>
                </a>
                <?php if ($logged) { ?>
                <a href="<?php echo $account; ?>" class="aqc-mobile-action">
                    <i class="fas fa-user"></i>
                    <span>Account</span>
                </a>
                <?php } else { ?>
                <a href="<?php echo $login; ?>" class="aqc-mobile-action">
                    <i class="fas fa-user"></i>
                    <span>Sign In</span>
                </a>
                <?php } ?>
            </div>
        </div>
    </div>
</header>

<!-- AQC Style Navigation -->
<div class="aqc-navigation-wrapper">
    <div class="aqc-nav-container">
        <nav class="aqc-main-nav" id="aqc-main-nav">
            <div class="aqc-nav-content">
                <!-- Existing Navigation -->
                <div class="aqc-existing-nav">
                    <?php echo $navigation ?>
                </div>

                
        </nav>

        <!-- Mobile Navigation Toggle (for mobile view) -->
        <div class="aqc-mobile-nav-toggle">
            <button class="aqc-mobile-nav-btn" id="aqc-mobile-nav-toggle">
                <i class="fas fa-bars"></i>
                <span>Menu</span>
            </button>
        </div>
    </div>
</div>

<!-- AQC Header Styles -->
<style>
/* Reset and Base Styles for AQC Header */
.aqc-header-wrapper * {
    box-sizing: border-box;
}

.aqc-header-wrapper {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    position: relative;
    z-index: 1000;
}

/* Promotional Banner */
.aqc-promo-banner {
    background: linear-gradient(135deg, #4A90E2 0%, #5BA3F5 100%);
    padding: 8px 0;
    color: white;
    font-size: 14px;
    font-weight: 500;
}

.aqc-promo-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.aqc-promo-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    text-align: center;
}

.aqc-promo-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.aqc-promo-text {
    font-weight: 500;
}

.aqc-promo-link {
    color: white;
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: opacity 0.2s ease;
}

.aqc-promo-link:hover {
    opacity: 0.8;
    color: white;
}

/* Main Header */
.aqc-main-header {
    background: white;
    border-bottom: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.aqc-header-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.aqc-header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 80px;
    gap: 20px;
}

/* Logo Section */
.aqc-logo-section {
    flex-shrink: 0;
}

.aqc-logo-link {
    display: block;
    text-decoration: none;
}

.aqc-logo-img {
    height: 50px;
    width: auto;
    max-width: 180px;
    object-fit: contain;
}

/* Search Section */
.aqc-search-section {
    flex: 1;
    max-width: 600px;
    margin: 0 40px;
}

.aqc-search-wrapper {
    position: relative;
    width: 100%;
}

.aqc-search-container {
    position: relative;
    display: flex;
    align-items: center;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.aqc-search-container:focus-within {
    border-color: #4A90E2;
    background: white;
    box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
}

.aqc-search-input {
    flex: 1;
    padding: 9px 16px;
    border: none;
    background: transparent;
    font-size: 16px;
    color: #333;
    outline: none;
}

.aqc-search-input::placeholder {
    color: #6b7280;
}

.aqc-search-btn {
    padding: 12px 16px;
    border: none;
    background: #4A90E2;
    color: white;
    border-radius: 0 6px 6px 0;
    cursor: pointer;
    transition: background 0.2s ease;
}

.aqc-search-btn:hover {
    background: #357ABD;
}

.aqc-search-btn i {
    font-size: 16px;
}

/* Actions Section */
.aqc-actions-section {
    display: flex;
    align-items: center;
    gap: 8px;
}

.aqc-nav-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.aqc-nav-item {
    position: relative;
}

.aqc-nav-link {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    padding: 8px 12px;
    text-decoration: none;
    color: black;
    border-radius: 8px;
    transition: all 0.2s ease;
    min-width: 70px;
}

.aqc-nav-link:hover {
    background: #f3f4f6;
    color: #4A90E2;
    text-decoration: none;
}

.aqc-nav-link i {
    font-size: 20px;
    margin-bottom: 2px;
}

.aqc-nav-text {
    text-align: center;
}

.aqc-nav-label {
    font-size: 12px;
    font-weight: 500;
    display: block;
    line-height: 1.2;
}

/* Cart Count */
.aqc-cart-count {
    background: #ef4444;
    color: white;
    font-size: 10px;
    font-weight: 600;
    padding: 2px 6px;
    border-radius: 10px;
    position: absolute;
    top: 4px;
    right: 8px;
    min-width: 18px;
    text-align: center;
    line-height: 14px;
}

/* More Dropdown */
.aqc-more-item {
    position: relative;
}

.aqc-more-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    min-width: 250px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.2s ease;
    z-index: 1000;
    margin-top: 8px;
}

.aqc-more-item:hover .aqc-more-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.aqc-dropdown-content {
    background: black;
    padding: 0px 0;
}

/* Mobile Menu Toggle */
.aqc-mobile-toggle {
    display: none;
}

.aqc-mobile-btn {
    display: flex;
    flex-direction: column;
    gap: 3px;
    background: none;
    border: none;
    padding: 8px;
    cursor: pointer;
}

.aqc-mobile-btn span {
    width: 24px;
    height: 2px;
    background: #374151;
    transition: all 0.2s ease;
}

/* Mobile Menu */
.aqc-mobile-menu {
    position: fixed;
    top: 0;
    left: -100%;
    width: 280px;
    height: 100vh;
    background: white;
    z-index: 9999;
    transition: left 0.3s ease;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
}

.aqc-mobile-menu.active {
    left: 0;
}

.aqc-mobile-menu-content {
    padding: 20px;
}

.aqc-mobile-search {
    margin-bottom: 20px;
}

.aqc-mobile-search-container {
    display: flex;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    overflow: hidden;
}

.aqc-mobile-search-input {
    flex: 1;
    padding: 12px;
    border: none;
    font-size: 14px;
    outline: none;
}

.aqc-mobile-search-btn {
    padding: 12px;
    border: none;
    background: #4A90E2;
    color: white;
    cursor: pointer;
}

.aqc-mobile-nav {
    margin-bottom: 20px;
}

.aqc-mobile-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.aqc-mobile-action {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    text-decoration: none;
    color: #374151;
    border-radius: 6px;
    transition: background 0.2s ease;
}

.aqc-mobile-action:hover {
    background: #f3f4f6;
    color: #4A90E2;
}

.aqc-mobile-action i {
    font-size: 18px;
    width: 20px;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .aqc-search-section {
        margin: 0 20px;
    }
    
    .aqc-nav-actions {
        gap: 4px;
    }
    
    .aqc-nav-link {
        padding: 6px 8px;
        min-width: 60px;
    }
    
    .aqc-nav-link i {
        font-size: 18px;
    }
    
    .aqc-nav-label {
        font-size: 11px;
    }
}

@media (max-width: 768px) {
    .aqc-header-content {
        gap: 12px;
    }
    
    .aqc-search-section {
        display: none;
    }
    
    .aqc-nav-actions {
        display: none;
    }
    
    .aqc-mobile-toggle {
        display: block;
    }
    
    .aqc-promo-content {
        flex-direction: column;
        gap: 8px;
    }
    
    .aqc-promo-banner {
        padding: 12px 0;
    }
    
    .aqc-logo-img {
        height: 40px;
    }
}

@media (max-width: 480px) {
    .aqc-header-container {
        padding: 0 16px;
    }
    
    .aqc-promo-container {
        padding: 0 16px;
    }
    
    .aqc-header-content {
        min-height: 70px;
    }
    
    .aqc-promo-text {
        font-size: 13px;
    }
    
    .aqc-promo-badge {
        font-size: 11px;
    }
}

/* Dropdown Menu Styling */
.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #e5e7eb;
    border-top: none;
    border-radius: 0 0 6px 6px;
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
}

.dropdown-menu li {
    padding: 10px 16px;
    cursor: pointer;
    border-bottom: 1px solid #f3f4f6;
    transition: background 0.2s ease;
}

.dropdown-menu li:hover {
    background: #f8f9fa;
}

.dropdown-menu li:last-child {
    border-bottom: none;
}

/* ===== AQC Navigation Styles ===== */
/* Navigation Wrapper */
.aqc-navigation-wrapper {
    background: #041f1e;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    position: relative;
    z-index: 999;
}

.aqc-nav-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    position: relative;
}

.aqc-main-nav {
    position: relative;
}

.aqc-nav-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 50px;
}

/* Existing Navigation Styling */
.aqc-existing-nav {
    flex: 1;
}

/* Style the existing navigation elements */
.aqc-existing-nav ul {
    display: flex;
    align-items: center;
    margin: 0;
    padding: 0;
    list-style: none;
}

.aqc-existing-nav li {
    position: relative;
}

.aqc-existing-nav a {
        display: flex
;
    align-items: center;
    gap: 6px;
    padding: 7px 18px;
    color: white;
    flex-direction: row;
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.2s 
Ease;
    white-space: nowrap;
    position: relative;
}

.aqc-existing-nav a:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #FFD700;
    text-decoration: none;
}

/* Style dropdown menus in existing navigation */
.aqc-existing-nav .dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    min-width: 280px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.2s ease;
    z-index: 1000;
    margin-top: 0;
}

.aqc-existing-nav li:hover .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.aqc-existing-nav .dropdown-menu li {
    position: relative;
}

.aqc-existing-nav .dropdown-menu a {
    display: block;
    padding: 10px 20px;
    color: #374151;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.2s ease;
}

.aqc-existing-nav .dropdown-menu a:hover {
    background: #f8f9fa;
    color: #2C5F41;
    text-decoration: none;
}

/* Flash Sale Button */
.aqc-nav-actions {
    display: flex;
    align-items: center;
}

.aqc-flash-sale-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #FF6B35 0%, #FF8E53 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.2s ease;
    box-shadow: 0 3px 8px rgba(255, 107, 53, 0.3);
}

.aqc-flash-sale-btn:hover {
    background: linear-gradient(135deg, #FF8E53 0%, #FF6B35 100%);
    transform: translateY(-1px);
    box-shadow: 0 5px 12px rgba(255, 107, 53, 0.4);
    color: white;
    text-decoration: none;
}

.aqc-flash-sale-btn i,
.aqc-flash-icon {
    font-size: 16px;
    color: white;
    display: inline-block;
    margin-right: 4px;
    animation: flashPulse 2s infinite;
}

@keyframes flashPulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.8;
        transform: scale(1.1);
    }
}

.aqc-flash-text {
    font-family: 'Inter', sans-serif;
    font-weight: 700;
    color: white;
}

/* Mobile Navigation Toggle */
.aqc-mobile-nav-toggle {
    display: none;
}

.aqc-mobile-nav-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.aqc-mobile-nav-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

/* Navigation Responsive Design */
@media (max-width: 1024px) {
    .aqc-existing-nav a {
        padding: 12px 14px;
        font-size: 13px;
    }
    
    .aqc-existing-nav .dropdown-menu {
        min-width: 240px;
    }
    
    .aqc-flash-sale-btn {
        padding: 6px 12px;
        font-size: 12px;
    }
}

@media (max-width: 768px) {
    .aqc-existing-nav {
        display: none;
    }
    
    .aqc-mobile-nav-toggle {
        display: block;
    }
    
    .aqc-nav-content {
        justify-content: space-between;
    }
    
    .aqc-flash-sale-btn {
        padding: 8px 12px;
        font-size: 11px;
    }
    
    .aqc-flash-text {
        display: none;
    }
}

@media (max-width: 480px) {
    .aqc-nav-container {
        padding: 0 16px;
    }
    
    .aqc-nav-content {
        min-height: 45px;
    }
    
    .aqc-mobile-nav-btn {
        padding: 6px 12px;
        font-size: 13px;
    }
    
    .aqc-flash-sale-btn {
        padding: 6px 10px;
    }
}

/* Navigation Animation for dropdown arrows */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.aqc-dropdown-menu {
    animation: fadeInDown 0.2s ease-out;
}

.aqc-dropdown-submenu {
    animation: fadeInRight 0.2s ease-out;
}

/* Navigation Hover effects for better UX */
.aqc-nav-item::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: #FFD700;
    transition: width 0.3s ease;
}

.aqc-nav-item:hover::after {
    width: 100%;
}

/* Navigation Accessibility improvements */
.aqc-nav-link:focus,
.aqc-dropdown-link:focus,
.aqc-submenu-link:focus,
.aqc-flash-sale-btn:focus {
    outline: 2px solid #FFD700;
    outline-offset: 2px;
}

/* Navigation Loading state for flash sale button */
.aqc-flash-sale-btn.loading {
    pointer-events: none;
    opacity: 0.7;
}

.aqc-flash-sale-btn.loading i {
    animation: spin 1s linear infinite;
}
</style>
<!-- AQC Header JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('aqc-mobile-menu-btn');
    const mobileMenu = document.getElementById('aqc-mobile-menu');
    let isMenuOpen = false;

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function(e) {
            e.preventDefault();
            isMenuOpen = !isMenuOpen;
            
            if (isMenuOpen) {
                mobileMenu.classList.add('active');
                document.body.style.overflow = 'hidden';
                
                // Animate hamburger to X
                const spans = mobileMenuBtn.querySelectorAll('span');
                spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                spans[1].style.opacity = '0';
                spans[2].style.transform = 'rotate(-45deg) translate(7px, -6px)';
            } else {
                mobileMenu.classList.remove('active');
                document.body.style.overflow = '';
                
                // Animate X back to hamburger
                const spans = mobileMenuBtn.querySelectorAll('span');
                spans[0].style.transform = '';
                spans[1].style.opacity = '1';
                spans[2].style.transform = '';
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (isMenuOpen && !mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                isMenuOpen = false;
                mobileMenu.classList.remove('active');
                document.body.style.overflow = '';
                
                const spans = mobileMenuBtn.querySelectorAll('span');
                spans[0].style.transform = '';
                spans[1].style.opacity = '1';
                spans[2].style.transform = '';
            }
        });
    }

    // Search functionality
    const searchInput = document.querySelector('.aqc-search-input');
    const searchBtn = document.querySelector('.aqc-search-btn');
    const searchContainer = document.getElementById('aqc-search');
    const dropdownMenu = searchContainer ? searchContainer.querySelector('.dropdown-menu') : null;
    
    // Mobile search
    const mobileSearchInput = document.querySelector('.aqc-mobile-search-input');
    const mobileSearchBtn = document.querySelector('.aqc-mobile-search-btn');

    let searchTimeout;
    let searchRequest;

    function performSearch(query) {
        if (query.length < 2) {
            if (dropdownMenu) {
                dropdownMenu.style.display = 'none';
            }
            return;
        }

        // Cancel previous request
        if (searchRequest) {
            searchRequest.abort();
        }

        // Make AJAX request for search suggestions
        searchRequest = $.ajax({
            url: 'index.php?route=module/search_suggestion',
            type: 'post',
            data: {search: query},
            dataType: 'json',
            beforeSend: function() {
                if (dropdownMenu) {
                    dropdownMenu.innerHTML = '<li style="text-align: center; padding: 15px;"><i class="fas fa-spinner fa-spin"></i> Searching...</li>';
                    dropdownMenu.style.display = 'block';
                }
            },
            success: function(json) {
                if (dropdownMenu) {
                    dropdownMenu.innerHTML = '';
                    
                    if (json.length > 0) {
                        json.forEach(function(item) {
                            const li = document.createElement('li');
                            li.innerHTML = item.name;
                            li.addEventListener('click', function() {
                                searchInput.value = item.name;
                                dropdownMenu.style.display = 'none';
                                redirectToSearch(item.name);
                            });
                            dropdownMenu.appendChild(li);
                        });
                        dropdownMenu.style.display = 'block';
                    } else {
                        dropdownMenu.innerHTML = '<li style="text-align: center; padding: 15px; color: #666;">No results found</li>';
                        dropdownMenu.style.display = 'block';
                    }
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                if (xhr.statusText !== 'abort' && dropdownMenu) {
                    dropdownMenu.style.display = 'none';
                }
            }
        });
    }

    function redirectToSearch(query) {
        if (query.trim()) {
            window.location.href = 'index.php?route=product/search&search=' + encodeURIComponent(query.trim());
        }
    }

    // Desktop search events
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();
            
            searchTimeout = setTimeout(function() {
                performSearch(query);
            }, 300);
        });

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                redirectToSearch(this.value);
            }
        });

        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length >= 2 && dropdownMenu && dropdownMenu.children.length > 0) {
                dropdownMenu.style.display = 'block';
            }
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (dropdownMenu && !searchContainer.contains(e.target)) {
                dropdownMenu.style.display = 'none';
            }
        });
    }

    if (searchBtn) {
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (searchInput) {
                redirectToSearch(searchInput.value);
            }
        });
    }

    // Mobile search events
    if (mobileSearchInput) {
        mobileSearchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                redirectToSearch(this.value);
            }
        });
    }

    if (mobileSearchBtn) {
        mobileSearchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (mobileSearchInput) {
                redirectToSearch(mobileSearchInput.value);
            }
        });
    }

    // Close mobile menu on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && isMenuOpen) {
            isMenuOpen = false;
            mobileMenu.classList.remove('active');
            document.body.style.overflow = '';
            
            if (mobileMenuBtn) {
                const spans = mobileMenuBtn.querySelectorAll('span');
                spans[0].style.transform = '';
                spans[1].style.opacity = '1';
                spans[2].style.transform = '';
            }
        }
    });

    // Cart functionality (if mini-cart exists)
    const cartTogglers = document.querySelectorAll('.cart-toggler');
    cartTogglers.forEach(function(toggler) {
        toggler.addEventListener('click', function(e) {
            e.preventDefault();
            // Trigger existing cart functionality
            if (typeof window.cart !== 'undefined' && window.cart.show) {
                window.cart.show();
            } else if (typeof showMiniCart === 'function') {
                showMiniCart();
            } else {
                // Fallback to redirect to cart page
                window.location.href = 'index.php?route=checkout/cart';
            }
        });
    });

    // ===== AQC Navigation JavaScript =====
    // Flash Sale Button Animation
    const flashSaleBtn = document.querySelector('.aqc-flash-sale-btn');
    if (flashSaleBtn) {
        flashSaleBtn.addEventListener('click', function(e) {
            this.classList.add('loading');
            
            // Remove loading state after 2 seconds
            setTimeout(() => {
                this.classList.remove('loading');
            }, 2000);
        });
    }
});

// Update cart count function (can be called from external scripts)
function updateAqcCartCount(count) {
    const cartCounts = document.querySelectorAll('.aqc-cart-count');
    cartCounts.forEach(function(element) {
        element.textContent = count;
    });
}

// Expose functions globally for compatibility
window.aqcHeader = {
    updateCartCount: updateAqcCartCount
};
</script>

<div class="mini-cart" id="mini-cart">
    <div class="content">
        <div class="loader"></div>
    </div>
</div>

