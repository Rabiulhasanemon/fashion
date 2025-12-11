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
                                <ul class="new-mobile-list" style="background: #fff !important; position: fixed !important; z-index: 99999 !important;">
                                    <!-- ALWAYS VISIBLE TEST ITEM -->
                                    <li class="mobile-menu-item" style="display: block !important; visibility: visible !important; opacity: 1 !important; background: #ff0000 !important; color: #fff !important; padding: 20px !important; border: 3px solid #000 !important;">
                                        <a href="#" class="mobile-menu-link" style="color: #fff !important; font-weight: bold !important; font-size: 18px !important;">
                                            🔴 TEST ITEM - IF YOU SEE THIS, MENU IS WORKING
                                        </a>
                                    </li>
                                    
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
                                            <li class="mobile-menu-item <?php echo !empty($category['children']) ? 'has-submenu' : ''; ?>" style="display: block !important; visibility: visible !important; opacity: 1 !important;">
                                                <a href="<?php echo isset($category['href']) ? $category['href'] : '#'; ?>" class="mobile-menu-link" style="display: flex !important; visibility: visible !important; opacity: 1 !important;">
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
                                        <li class="mobile-menu-item" style="display: block !important; visibility: visible !important; opacity: 1 !important; background: #fff3cd; border: 2px solid #ffc107;">
                                            <a href="#" class="mobile-menu-link" style="color: #856404 !important; font-weight: bold; display: flex !important;">
                                                DEBUG: Categories Count = <?php echo $cat_count; ?> | Has Categories = <?php echo $has_categories ? 'YES' : 'NO'; ?>
                                            </a>
                                        </li>
                                        <li class="mobile-menu-item" style="display: block !important; visibility: visible !important; opacity: 1 !important;">
                                            <a href="#" class="mobile-menu-link" style="display: flex !important;">Test Item 1</a>
                                        </li>
                                        <li class="mobile-menu-item" style="display: block !important; visibility: visible !important; opacity: 1 !important;">
                                            <a href="#" class="mobile-menu-link" style="display: flex !important;">Test Item 2</a>
                                        </li>
                                        <li class="mobile-menu-item" style="display: block !important; visibility: visible !important; opacity: 1 !important;">
                                            <a href="#" class="mobile-menu-link" style="display: flex !important;">Test Item 3</a>
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
    top: calc(100% + 12px);
    left: -1.5rem;
    min-width: 260px;
    padding: 0;
    background: #ffffff;
    box-shadow: 0 8px 30px rgba(16, 80, 61, 0.15), 0 2px 8px rgba(0, 0, 0, 0.08);
    border-radius: 12px;
    z-index: 1001;
    visibility: hidden;
    opacity: 0;
    transform: translate3d(0, -15px, 0);
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    list-style: none;
    margin: 0;
    border: 1px solid rgba(16, 80, 61, 0.1);
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
    border-bottom: 1px solid rgba(16, 80, 61, 0.05);
}

.desktop-menu .submenu li:last-child {
    border-bottom: none;
}

.desktop-menu .submenu li a {
    display: block;
    padding: 12px 24px;
    color: #333333;
    font-weight: 400;
    font-size: 14px;
    letter-spacing: -0.01em;
    line-height: 1.6;
    text-transform: capitalize;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    background: transparent;
    margin: 0;
}

.desktop-menu .submenu li a::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, #10503D 0%, #A68A6A 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.desktop-menu .submenu li:hover > a {
    color: #10503D;
    background: linear-gradient(90deg, rgba(16, 80, 61, 0.08) 0%, rgba(166, 138, 106, 0.05) 100%);
    padding-left: 28px;
    font-weight: 600;
}

.desktop-menu .submenu li:hover > a::before {
    opacity: 1;
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

/* Ensure mobile menu is never hidden by default styles */
.new-mobile-menu,
.new-mobile-list,
.new-mobile-list li,
.new-mobile-list a {
    box-sizing: border-box;
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
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100vh !important;
        visibility: hidden;
        opacity: 0;
        transition: visibility 0.3s, opacity 0.3s;
        z-index: 99998 !important;
        display: block !important;
        pointer-events: none;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .new-mobile-menu.open {
        visibility: visible !important;
        opacity: 1 !important;
        pointer-events: auto !important;
        display: block !important;
    }
    
    /* Mobile Menu List - ALWAYS VISIBLE FOR DEBUGGING */
    .new-mobile-list {
        width: 300px;
        max-width: 80vw;
        position: fixed !important;
        left: 0 !important; /* CHANGED: Always visible, not hidden */
        top: 0 !important;
        height: 100vh !important;
        max-height: 100vh !important;
        overflow-y: auto;
        overflow-x: hidden;
        background: #ffffff !important;
        margin: 0 !important;
        padding: 20px 0 0 0 !important;
        list-style: none !important;
        transition: left 0.3s ease;
        z-index: 99999 !important;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        transform: none !important;
    }
    
    .new-mobile-menu.open .new-mobile-list {
        left: 0 !important;
        visibility: visible !important;
        opacity: 1 !important;
        display: block !important;
    }
    
    /* Mobile Menu Items */
    .new-mobile-list .mobile-menu-item {
        display: block !important;
        width: 100% !important;
        border-bottom: 1px solid #e8e8e8;
        margin: 0 !important;
        padding: 0 !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: relative !important;
        z-index: 1 !important;
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
// IMMEDIATE DEBUG CHECK
console.log('=== NAVIGATION SCRIPT LOADED ===');
console.log('Document ready state:', document.readyState);

// Check immediately if elements exist
setTimeout(function() {
    var testToggle = document.getElementById('new-mobile-toggle');
    var testMenu = document.getElementById('new-mobile-menu');
    var testList = document.querySelector('.new-mobile-list');
    
    console.log('=== IMMEDIATE ELEMENT CHECK ===');
    console.log('Toggle button exists:', !!testToggle);
    console.log('Mobile menu exists:', !!testMenu);
    console.log('Menu list exists:', !!testList);
    
    if (testList) {
        console.log('Menu list left position:', window.getComputedStyle(testList).left);
        console.log('Menu list display:', window.getComputedStyle(testList).display);
        console.log('Menu list visibility:', window.getComputedStyle(testList).visibility);
        console.log('Menu list items count:', testList.querySelectorAll('li').length);
    }
    console.log('=== END IMMEDIATE CHECK ===');
}, 500);

// New Mobile Navigation - Completely Fresh Implementation
(function() {
    'use strict';
    
    console.log('=== INITIALIZING MOBILE NAVIGATION ===');
    
    var mobileToggle = null;
    var mobileMenu = null;
    var mobileOverlay = null;
    var isMenuOpen = false;
    
    function initMobileNavigation() {
        console.log('initMobileNavigation() called');
        
        mobileToggle = document.getElementById('new-mobile-toggle');
        mobileMenu = document.getElementById('new-mobile-menu');
        mobileOverlay = document.querySelector('.new-mobile-overlay');
        
        console.log('Elements found:', {
            toggle: !!mobileToggle,
            menu: !!mobileMenu,
            overlay: !!mobileOverlay
        });
        
        if (!mobileToggle || !mobileMenu) {
            console.error('ERROR: Mobile navigation elements not found!');
            console.error('Toggle:', mobileToggle);
            console.error('Menu:', mobileMenu);
            return false;
        }
        
        console.log('New mobile navigation initialized successfully');
        
        // Debug: Check if menu items exist
        setTimeout(function() {
            var menuItems = mobileMenu.querySelectorAll('.mobile-menu-item');
            var menuList = mobileMenu.querySelector('.new-mobile-list');
            
            console.log('=== MOBILE MENU DEBUG ===');
            console.log('Mobile menu element:', mobileMenu);
            console.log('Menu list element:', menuList);
            console.log('Menu items found: ' + menuItems.length);
            console.log('Menu list computed left:', menuList ? window.getComputedStyle(menuList).left : 'N/A');
            console.log('Menu list display:', menuList ? window.getComputedStyle(menuList).display : 'N/A');
            console.log('Menu list visibility:', menuList ? window.getComputedStyle(menuList).visibility : 'N/A');
            
            if (menuItems.length === 0) {
                console.warn('WARNING: No mobile menu items found!');
                console.warn('Menu list HTML:', menuList ? menuList.innerHTML.substring(0, 200) : 'No list found');
            } else {
                menuItems.forEach(function(item, index) {
                    console.log('Menu item ' + index + ':', item.textContent.trim().substring(0, 50));
                    console.log('  - Display:', window.getComputedStyle(item).display);
                    console.log('  - Visibility:', window.getComputedStyle(item).visibility);
                });
            }
            console.log('=== END DEBUG ===');
        }, 100);
        
        // Toggle menu function
        function toggleMobileMenu() {
            isMenuOpen = !isMenuOpen;
            
            console.log('Toggle menu called. isMenuOpen:', isMenuOpen);
            console.log('Mobile menu element:', mobileMenu);
            console.log('Mobile toggle element:', mobileToggle);
            
            if (isMenuOpen) {
                mobileToggle.classList.add('active');
                mobileMenu.classList.add('open');
                document.body.classList.add('mobile-menu-open');
                
                // Force menu list to be visible
                var menuList = mobileMenu.querySelector('.new-mobile-list');
                if (menuList) {
                    menuList.style.left = '0px';
                    menuList.style.visibility = 'visible';
                    menuList.style.opacity = '1';
                    menuList.style.display = 'block';
                    console.log('Menu list forced to visible. Left:', menuList.style.left);
                }
                
                console.log('Menu opened. Classes:', mobileMenu.className);
            } else {
                mobileToggle.classList.remove('active');
                mobileMenu.classList.remove('open');
                document.body.classList.remove('mobile-menu-open');
                
                // Reset menu list position
                var menuList = mobileMenu.querySelector('.new-mobile-list');
                if (menuList) {
                    menuList.style.left = '-300px';
                }
                
                console.log('Menu closed. Classes:', mobileMenu.className);
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
        console.log('init() called');
        initStickyHeader();
        
        if (document.readyState === 'loading') {
            console.log('Document still loading, waiting for DOMContentLoaded');
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOMContentLoaded fired');
                initMobileNavigation();
            });
        } else {
            console.log('Document already loaded, initializing immediately');
            initMobileNavigation();
        }
    }
    
    // Try multiple initialization methods
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        // Document already loaded
        setTimeout(init, 100);
    }
    
    // Also try jQuery ready if available
    if (typeof jQuery !== 'undefined') {
        jQuery(document).ready(function() {
            console.log('jQuery ready fired');
            setTimeout(init, 200);
        });
    }
    
    console.log('=== NAVIGATION SCRIPT END ===');
})();
</script>
