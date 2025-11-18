<?php
// Debug: Check if categories exist
$has_categories = isset($categories) && is_array($categories) && count($categories) > 0;

// Debug output (remove in production)
if (!$has_categories) {
    // Check what variables are available
    $debug_vars = get_defined_vars();
    $available_keys = array_keys($debug_vars);
    // Uncomment below to see what's available:
    // echo "<!-- DEBUG: Available vars: " . implode(', ', $available_keys) . " -->";
    // echo "<!-- DEBUG: Categories count: " . (isset($categories) ? count($categories) : 'NOT SET') . " -->";
}
?>
<div class="sticky-content-wrapper">
    <div class="header-bottom sticky-content fix-top sticky-header has-dropdown">
        <div class="container">
            <div class="inner-wrap">
                <!-- Mobile Menu Toggle Button -->
                <div id="new-mobile-toggle" class="new-mobile-toggle-btn">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                
                <div class="header-left">
                    <div class="header-menu">
                        <div class="main-nav">
                            <!-- Desktop Navigation Menu -->
                            <ul class="desktop-menu">
                                <?php if ($has_categories) { ?>
                                    <?php foreach ($categories as $category) { ?>
                                        <li class="menu-item <?php echo !empty($category['children']) ? 'has-children' : ''; ?>">
                                            <a href="<?php echo $category['href']; ?>" class="menu-link">
                                                <?php echo htmlspecialchars($category['name']); ?>
                                                <?php if (!empty($category['children'])) { ?>
                                                    <i class="fa fa-chevron-down"></i>
                                                <?php } ?>
                                            </a>
                                            <?php if (!empty($category['children'])) { ?>
                                                <ul class="submenu">
                                                    <?php foreach ($category['children'] as $child) { ?>
                                                        <li>
                                                            <a href="<?php echo $child['href']; ?>">
                                                                <?php echo htmlspecialchars($child['name']); ?>
                                                            </a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                <?php } else { ?>
                                    <li class="menu-item">
                                        <a href="#" class="menu-link">No Categories Available</a>
                                    </li>
                                <?php } ?>
                            </ul>
                            
                            <!-- Mobile Navigation Menu -->
                            <div id="new-mobile-menu" class="new-mobile-menu">
                                <div class="new-mobile-overlay"></div>
                                <ul class="new-mobile-list">
                                    <?php 
                                    // Debug: Check categories
                                    $cat_count = isset($categories) && is_array($categories) ? count($categories) : 0;
                                    
                                    // Always show at least one test item for debugging
                                    $show_test_item = false;
                                    if ($cat_count === 0 || !$has_categories) {
                                        $show_test_item = true;
                                    }
                                    ?>
                                    <?php if ($has_categories && $cat_count > 0) { ?>
                                        <?php foreach ($categories as $category) { ?>
                                            <?php if (isset($category['name']) && !empty($category['name'])) { ?>
                                            <li class="mobile-menu-item <?php echo !empty($category['children']) ? 'has-submenu' : ''; ?>">
                                                <a href="<?php echo isset($category['href']) ? $category['href'] : '#'; ?>" class="mobile-menu-link">
                                                    <?php echo htmlspecialchars($category['name']); ?>
                                                    <?php if (!empty($category['children'])) { ?>
                                                        <span class="submenu-toggle">+</span>
                                                    <?php } ?>
                                                </a>
                                                <?php if (!empty($category['children']) && is_array($category['children'])) { ?>
                                                    <ul class="mobile-submenu">
                                                        <?php foreach ($category['children'] as $child) { ?>
                                                            <?php if (isset($child['name']) && !empty($child['name'])) { ?>
                                                            <li>
                                                                <a href="<?php echo isset($child['href']) ? $child['href'] : '#'; ?>">
                                                                    <?php echo htmlspecialchars($child['name']); ?>
                                                                </a>
                                                            </li>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </ul>
                                                <?php } ?>
                                            </li>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                    
                                    <?php if ($show_test_item) { ?>
                                        <li class="mobile-menu-item" style="background: #fff3cd; border: 2px solid #ffc107;">
                                            <a href="#" class="mobile-menu-link" style="color: #856404 !important; font-weight: bold;">
                                                DEBUG: Categories Count = <?php echo $cat_count; ?> | Has Categories = <?php echo $has_categories ? 'YES' : 'NO'; ?>
                                            </a>
                                        </li>
                                        <li class="mobile-menu-item">
                                            <a href="#" class="mobile-menu-link">Test Item 1</a>
                                        </li>
                                        <li class="mobile-menu-item">
                                            <a href="#" class="mobile-menu-link">Test Item 2</a>
                                        </li>
                                        <li class="mobile-menu-item">
                                            <a href="#" class="mobile-menu-link">Test Item 3</a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if (isset($flash_sale_url) && $flash_sale_url) { ?>
                <a href="<?php echo $flash_sale_url; ?>" class="h-flash-btn">
                    <i class="fi-rs-sparkles"></i><span>Flash Sale</span>
                </a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<style>
/* ============================================
   DESKTOP NAVIGATION STYLES
   ============================================ */
.sticky-content-wrapper {
    position: relative;
}

.header-bottom {
    padding: 0;
    background: #10503D;
    position: relative;
    z-index: 1000;
}

.header-bottom .container {
    max-width: 100%;
    margin: 0 auto;
    padding: 0 20px;
}

.header-bottom .inner-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    position: relative;
}

.header-bottom .header-left {
    flex: 1;
}

.header-bottom .main-nav {
    display: block;
}

/* Desktop Menu */
.desktop-menu {
    display: flex;
    align-items: center;
    list-style: none;
    margin: 0;
    padding: 0;
    flex-wrap: wrap;
}

.desktop-menu > .menu-item {
    position: relative;
    display: inline-block;
    margin-right: 2.4rem;
}

.desktop-menu > .menu-item:last-child {
    margin-right: 0;
}

.desktop-menu > .menu-item > .menu-link {
    display: block;
    padding: 15px 0;
    font-size: 0.9rem;
    font-weight: 400;
    letter-spacing: -0.009em;
    line-height: 1.1;
    text-transform: capitalize;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
}

.desktop-menu > .menu-item > .menu-link i {
    margin-left: 5px;
    font-size: 0.7rem;
    transition: transform 0.3s ease;
}

.desktop-menu > .menu-item:hover > .menu-link,
.desktop-menu > .menu-item.active > .menu-link {
    color: #FF6A00 !important;
}

.desktop-menu > .menu-item:hover > .menu-link i {
    transform: rotate(180deg);
}

/* Desktop Submenu */
.desktop-menu .submenu {
    position: absolute;
    top: calc(100% + 8px);
    left: -1.5rem;
    min-width: 240px;
    padding: 1.2rem 0;
    background: #ffffff;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.08);
    border-radius: 12px;
    z-index: 1001;
    visibility: hidden;
    opacity: 0;
    transform: translate3d(0, -15px, 0);
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    list-style: none;
    margin: 0;
    border: 1px solid rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.desktop-menu > .menu-item:hover > .submenu {
    visibility: visible;
    opacity: 1;
    transform: translate3d(0, 0, 0);
}

.desktop-menu .submenu li {
    padding: 0;
    width: 100%;
    margin: 0;
    position: relative;
}

.desktop-menu .submenu li a {
    display: block;
    padding: 0.85rem 1.9rem;
    color: #333333;
    font-weight: 400;
    font-size: 14px;
    letter-spacing: -0.01em;
    line-height: 1.5;
    text-transform: capitalize;
    text-decoration: none;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    background: transparent;
}

.desktop-menu .submenu li:hover > a {
    color: #FF6A00;
    background: linear-gradient(90deg, rgba(255, 106, 0, 0.05) 0%, rgba(255, 106, 0, 0.02) 100%);
    padding-left: 2.1rem;
    font-weight: 500;
}

/* Flash Sale Button */
.header-bottom .h-flash-btn {
    border-radius: 4px;
    background: #fff;
    padding: 0.6rem 1.2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.2rem;
    font-style: normal;
    font-weight: 600;
    line-height: 1.2;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: #FF6A00 !important;
    text-decoration: none;
    transition: all 0.3s ease;
    white-space: nowrap;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.header-bottom .h-flash-btn:hover {
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}

.header-bottom .h-flash-btn i {
    font-size: 1.6rem;
    color: #FF6A00;
}

/* Sticky Header */
.header-bottom.sticky-content.fixed {
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    opacity: 1;
    transform: translateY(0);
    z-index: 1051;
    box-shadow: 0 0 10px 1px rgba(0, 0, 0, 0.1);
    background: #fff;
    animation: fixedTopContent 0.4s;
}

@keyframes fixedTopContent {
    from {
        opacity: 0;
        transform: translateY(-100%);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.header-bottom.sticky-content.fixed .desktop-menu > .menu-item > .menu-link {
    color: #232323;
}

.header-bottom.sticky-content.fixed .desktop-menu > .menu-item:hover > .menu-link {
    color: #FF6A00 !important;
}

/* ============================================
   MOBILE NAVIGATION STYLES
   ============================================ */
.new-mobile-toggle-btn {
    display: none;
}

.new-mobile-menu {
    display: none;
}

/* Mobile Styles */
@media only screen and (max-width: 991.98px) {
    /* Hide desktop menu on mobile */
    .desktop-menu {
        display: none !important;
    }
    
    /* Show mobile toggle button */
    .new-mobile-toggle-btn {
        width: 30px;
        height: 30px;
        position: relative;
        display: flex !important;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 99999;
        padding: 5px;
        margin-right: 15px;
        flex-shrink: 0;
        background: transparent;
        border: none;
        -webkit-tap-highlight-color: transparent;
    }
    
    .new-mobile-toggle-btn span {
        display: block;
        width: 22px;
        height: 2px;
        background-color: #fff;
        margin: 3px 0;
        transition: all 0.3s ease;
        border-radius: 2px;
    }
    
    .header-bottom.sticky-content.fixed .new-mobile-toggle-btn span {
        background-color: #000;
    }
    
    /* Toggle button active state (X) */
    .new-mobile-toggle-btn.active span:nth-child(1) {
        transform: rotate(45deg) translate(7px, 7px);
    }
    
    .new-mobile-toggle-btn.active span:nth-child(2) {
        opacity: 0;
    }
    
    .new-mobile-toggle-btn.active span:nth-child(3) {
        transform: rotate(-45deg) translate(7px, -7px);
    }
    
    /* Mobile Menu Container */
    .new-mobile-menu {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        visibility: hidden;
        opacity: 0;
        transition: visibility 0.3s, opacity 0.3s;
        z-index: 99998;
        display: block !important;
        pointer-events: none;
    }
    
    .new-mobile-menu.open {
        visibility: visible !important;
        opacity: 1 !important;
        pointer-events: auto;
    }
    
    /* Mobile Menu List */
    .new-mobile-list {
        width: 300px;
        position: fixed;
        left: -300px;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
        background: #ffffff;
        margin: 0;
        padding: 20px 0 0 0;
        list-style: none;
        transition: left 0.3s ease;
        z-index: 99999;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    .new-mobile-menu.open .new-mobile-list {
        left: 0;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    /* Mobile Menu Items */
    .new-mobile-list .mobile-menu-item {
        display: block !important;
        width: 100%;
        border-bottom: 1px solid #e8e8e8;
        margin: 0;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    .new-mobile-list .mobile-menu-item:last-child {
        border-bottom: none;
        margin-bottom: 50px;
    }
    
    .new-mobile-list .mobile-menu-link {
        display: flex !important;
        align-items: center;
        justify-content: space-between;
        padding: 15px 20px;
        color: #000 !important;
        font-size: 14px;
        line-height: 1.5;
        text-decoration: none;
        transition: all 0.3s ease;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    .new-mobile-list .mobile-menu-link:hover {
        color: #FF6A00;
        background-color: #f5f5f5;
    }
    
    .new-mobile-list .submenu-toggle {
        font-size: 20px;
        font-weight: bold;
        color: #666;
        transition: transform 0.3s ease;
    }
    
    .new-mobile-list .mobile-menu-item.open .submenu-toggle {
        transform: rotate(45deg);
    }
    
    /* Mobile Submenu */
    .new-mobile-list .mobile-submenu {
        display: none;
        background-color: #f1f5f9;
        padding: 0;
        margin: 0;
        list-style: none;
    }
    
    .new-mobile-list .mobile-menu-item.open .mobile-submenu {
        display: block;
    }
    
    .new-mobile-list .mobile-submenu li {
        border-bottom: none;
        background-color: transparent;
    }
    
    .new-mobile-list .mobile-submenu li a {
        display: block;
        padding: 12px 20px 12px 40px;
        font-size: 13px;
        color: #333;
        text-decoration: none;
    }
    
    .new-mobile-list .mobile-submenu li a:hover {
        color: #FF6A00;
        background-color: #e8e8e8;
    }
    
    /* Mobile Overlay */
    .new-mobile-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        z-index: 99997;
        display: block !important;
    }
    
    .new-mobile-menu.open .new-mobile-overlay {
        opacity: 1;
        visibility: visible;
    }
    
    /* Ensure header-bottom is visible on mobile */
    .header-bottom {
        display: block !important;
    }
    
    .header-bottom .inner-wrap {
        display: flex !important;
        align-items: center;
    }
    
    /* Flash Sale Button on Mobile */
    .header-bottom .h-flash-btn {
        padding: 6px 8px;
        font-size: 11px;
    }
    
    .header-bottom .h-flash-btn i {
        font-size: 16px;
    }
}

/* Body scroll lock when mobile menu is open */
body.mobile-menu-open {
    overflow: hidden;
    position: fixed;
    width: 100%;
}
</style>

<script>
// New Mobile Navigation - Completely Fresh Implementation
(function() {
    'use strict';
    
    var mobileToggle = null;
    var mobileMenu = null;
    var mobileOverlay = null;
    var isMenuOpen = false;
    
    function initMobileNavigation() {
        mobileToggle = document.getElementById('new-mobile-toggle');
        mobileMenu = document.getElementById('new-mobile-menu');
        mobileOverlay = document.querySelector('.new-mobile-overlay');
        
        if (!mobileToggle || !mobileMenu) {
            console.log('Mobile navigation elements not found');
            return false;
        }
        
        console.log('New mobile navigation initialized successfully');
        
        // Debug: Check if menu items exist
        var menuItems = mobileMenu.querySelectorAll('.mobile-menu-item');
        console.log('Mobile menu items found: ' + menuItems.length);
        
        if (menuItems.length === 0) {
            console.warn('WARNING: No mobile menu items found!');
        } else {
            menuItems.forEach(function(item, index) {
                console.log('Menu item ' + index + ':', item.textContent.trim());
            });
        }
        
        // Toggle menu function
        function toggleMobileMenu() {
            isMenuOpen = !isMenuOpen;
            
            if (isMenuOpen) {
                mobileToggle.classList.add('active');
                mobileMenu.classList.add('open');
                document.body.classList.add('mobile-menu-open');
            } else {
                mobileToggle.classList.remove('active');
                mobileMenu.classList.remove('open');
                document.body.classList.remove('mobile-menu-open');
            }
        }
        
        // Close menu function
        function closeMobileMenu() {
            if (isMenuOpen) {
                isMenuOpen = false;
                mobileToggle.classList.remove('active');
                mobileMenu.classList.remove('open');
                document.body.classList.remove('mobile-menu-open');
            }
        }
        
        // Toggle button click
        mobileToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleMobileMenu();
        });
        
        // Overlay click to close
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                closeMobileMenu();
            });
        }
        
        // Submenu toggle for mobile
        var submenuToggles = mobileMenu.querySelectorAll('.mobile-menu-item.has-submenu > .mobile-menu-link');
        submenuToggles.forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 991) {
                    e.preventDefault();
                    e.stopPropagation();
                    var parentLi = this.parentElement;
                    parentLi.classList.toggle('open');
                }
            });
        });
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isMenuOpen) {
                closeMobileMenu();
            }
        });
        
        return true;
    }
    
    // Initialize sticky header
    function initStickyHeader() {
        if (typeof jQuery !== 'undefined') {
            jQuery(document).ready(function($) {
                var $headerBottom = $('.header-bottom.sticky-content');
                if ($headerBottom.length) {
                    var headerOffset = $headerBottom.offset().top;
                    
                    function checkSticky() {
                        if ($(window).scrollTop() > headerOffset) {
                            $headerBottom.addClass('fixed');
                        } else {
                            $headerBottom.removeClass('fixed');
                        }
                    }
                    
                    $(window).on('scroll', checkSticky);
                    checkSticky();
                }
            });
        }
    }
    
    // Initialize everything
    function init() {
        initStickyHeader();
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initMobileNavigation);
        } else {
            initMobileNavigation();
        }
    }
    
    init();
})();
</script>
