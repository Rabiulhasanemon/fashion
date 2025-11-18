<?php if (isset($categories) && $categories) { ?>
<div class="sticky-content-wrapper">
    <div class="header-bottom sticky-content fix-top sticky-header has-dropdown">
        <div class="container">
            <div class="inner-wrap">
                <div id="mobile-menu-toggle" class="mobile-menu-toggle-btn"><span></span><span></span><span></span></div>
                <div class="header-left">
                    <div class="header-menu">
                        <div class="main-nav">
                            <nav id="mobile-nav-menu" class="mobile-nav-container">
                                <ul class="mobile-nav-list">
                            <?php foreach ($categories as $category) { ?>
                            <?php if (isset($category['children']) && !empty($category['children'])) { ?>
                            <li class="has-submenu drop-open c-1">
                                <a href="<?php echo $category['href']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </a>
                                <ul class="drop-down drop-menu-1">
                                    <?php foreach ($category['children'] as $child) { ?>
                                    <li>
                                        <a href="<?php echo $child['href']; ?>">
                                            <?php echo htmlspecialchars($child['name']); ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } else { ?>
                            <li class="">
                                <a href="<?php echo $category['href']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </a>
                            </li>
                            <?php } ?>
                            <?php } ?>
                                </ul>
                                <div class="mobile-nav-overlay"></div>
                            </nav>
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
<?php } ?>

<style>
/* Header Bottom Navigation Styles */
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

@media (max-width: 767px) {
    .header-bottom .container {
        max-width: 100%;
        padding: 0 15px;
    }
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

.header-bottom .main-nav .menu {
    display: flex;
    align-items: center;
    list-style: none;
    margin: 0;
    padding: 0;
    flex-wrap: wrap;
}

.header-bottom .main-nav .menu > li {
    position: relative;
    display: inline-block;
    margin-right: 2.4rem;
}

.header-bottom .main-nav .menu > li:last-child {
    margin-right: 0;
}

.header-bottom .main-nav .menu > li > a {
    display: block;
    padding: 0;
    font-size: 0.9rem;
    font-weight: 400;
    letter-spacing: -0.009em;
    line-height: 1.1;
    text-transform: capitalize;
    color: var(--white-color, #fff);
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
}

.header-bottom .main-nav .menu > li.active > a:not(.menu-title),
.header-bottom .main-nav .menu > li:hover > a:not(.menu-title) {
    color: var(--primary-color, #FF6A00) !important;
}

.header-bottom .main-nav .menu > li.has-submenu > a::after {
    content: "\f107";
    display: inline-block;
    margin-left: 0.6rem;
    margin-top: 1px;
    right: -16px;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-family: "Font Awesome 5 Free";
    font-size: 0.8rem;
    font-weight: 900;
    vertical-align: middle;
    color: inherit;
}

.header-bottom .main-nav .menu .submenu {
    position: absolute;
    top: -9999px;
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

.header-bottom .main-nav .menu > li.show > .submenu,
.header-bottom .main-nav .menu > li:hover > .submenu {
    visibility: visible;
    top: calc(100% + 8px);
    opacity: 1;
    transform: translate3d(0, 0, 0);
}

.header-bottom .main-nav .menu .submenu li {
    padding: 0;
    width: 100%;
    margin: 0;
    position: relative;
}

.header-bottom .main-nav .menu .submenu li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, #FF6A00 0%, #ff8c42 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.header-bottom .main-nav .menu .submenu li:hover::before {
    opacity: 1;
}

.header-bottom .main-nav .menu .submenu li a {
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

.header-bottom .main-nav .menu .submenu li a::after {
    content: '';
    position: absolute;
    left: 1.9rem;
    right: 1.9rem;
    bottom: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.08), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.header-bottom .main-nav .menu .submenu li:last-child a::after {
    display: none;
}

.header-bottom .main-nav .menu .submenu li.active > a,
.header-bottom .main-nav .menu .submenu li:hover > a {
    color: #FF6A00;
    background: linear-gradient(90deg, rgba(255, 106, 0, 0.05) 0%, rgba(255, 106, 0, 0.02) 100%);
    padding-left: 2.1rem;
    font-weight: 500;
}

.header-bottom .main-nav .menu .submenu li:hover > a::after {
    opacity: 1;
}

.header-bottom .h-flash-btn {
    border-radius: 4px;
    background: var(--white-color, #fff);
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
    color: var(--primary-color, #FF6A00) !important;
    text-decoration: none;
    transition: all 0.3s ease;
    white-space: nowrap;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.header-bottom .h-flash-btn:hover {
    background: var(--white-color, #fff);
    color: var(--primary-color, #FF6A00) !important;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}

.header-bottom .h-flash-btn i {
    font-size: 1.6rem;
    color: var(--primary-color, #FF6A00);
}

/* Sticky Header */
.header-bottom.sticky-content.fixed {
    position: fixed;
    left: 0;
    right: 0;
    opacity: 1;
    transform: translateY(0);
    z-index: 1051;
    box-shadow: 0 0 10px 1px rgba(0, 0, 0, 0.1);
    background: var(--white-color, #fff);
}

.header-bottom.sticky-content.fixed.fix-top {
    top: 0;
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

.header-bottom.sticky-content.fixed .main-nav .menu > li > a {
    color: var(--title-color, #232323);
}

.header-bottom.sticky-content.fixed .main-nav .menu > li.active > a:not(.menu-title),
.header-bottom.sticky-content.fixed .main-nav .menu > li:hover > a:not(.menu-title) {
    color: var(--primary-color, #FF6A00) !important;
}

.header-bottom.sticky-content.fixed .h-flash-btn {
    background: var(--white-color, #fff);
    color: var(--primary-color, #FF6A00) !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.header-bottom.sticky-content.fixed .h-flash-btn:hover {
    background: var(--white-color, #fff);
    color: var(--primary-color, #FF6A00) !important;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}

.header-bottom.sticky-content.fixed .h-flash-btn i {
    color: var(--primary-color, #FF6A00);
}

/* NEW Mobile Navigation - Using Unique Class Names */
.mobile-menu-toggle-btn {
    display: none;
}

.mobile-nav-container {
    position: relative;
    display: none;
}

.mobile-nav-overlay {
    display: none;
}

/* Body scroll lock when menu is open */
body.mobile-menu-open {
    overflow: hidden;
    position: fixed;
    width: 100%;
}

/* Mobile Navigation Styles - Only for Mobile View */
@media only screen and (max-width: 991.98px) {
    /* Hide desktop navigation on mobile */
    .header-bottom .main-nav .menu {
        display: none !important;
    }
    
    .header-bottom .main-nav {
        display: none !important;
    }
    
    /* Show mobile toggle button */
    .mobile-menu-toggle-btn {
        width: 30px;
        height: 30px;
        position: relative;
        display: flex !important;
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

    .mobile-menu-toggle-btn span {
        display: block;
        width: 22px;
        height: 2px;
        background-color: #000;
        position: relative;
        transition: all 0.3s ease;
        border-radius: 2px;
    }

    .mobile-menu-toggle-btn span:before,
    .mobile-menu-toggle-btn span:after {
        content: "";
        position: absolute;
        width: 22px;
        height: 2px;
        background-color: #000;
        left: 0;
        transition: all 0.3s ease;
        border-radius: 2px;
    }

    .mobile-menu-toggle-btn span:before {
        top: -7px;
    }

    .mobile-menu-toggle-btn span:after {
        top: 7px;
    }

    /* When menu is open - transform to X */
    .mobile-menu-toggle-btn.menu-active span {
        background-color: transparent;
    }

    .mobile-menu-toggle-btn.menu-active span:before {
        transform: rotate(45deg) translate(5px, 5px);
    }

    .mobile-menu-toggle-btn.menu-active span:after {
        transform: rotate(-45deg) translate(5px, -5px);
    }

    /* Navigation Container - Show on mobile */
    .mobile-nav-container {
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
    }

    .mobile-nav-container.menu-open {
        visibility: visible;
        opacity: 1;
    }

    /* Navigation List - Slide from left */
    .mobile-nav-list {
        width: 300px;
        position: fixed;
        left: -300px;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        background: #ffffff;
        margin: 0;
        padding: 20px 0 0 0;
        list-style: none;
        transition: left 0.3s ease;
        z-index: 99999;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        display: block !important;
    }

    .mobile-nav-container.menu-open .mobile-nav-list {
        left: 0;
    }

    /* Navigation Items */
    .mobile-nav-list li {
        display: block;
        width: 100%;
        border-bottom: 1px solid #e8e8e8;
        margin: 0;
    }

    .mobile-nav-list li:last-child {
        border-bottom: none;
        margin-bottom: 50px;
    }

    .mobile-nav-list > li > a {
        display: block;
        padding: 15px 20px;
        color: #000;
        font-size: 14px;
        line-height: 1.5;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .mobile-nav-list > li > a:hover {
        color: var(--primaryColor, #A68A6A);
        background-color: #f5f5f5;
    }

    /* Submenu Items */
    .mobile-nav-list .drop-menu-1 {
        display: none;
        background-color: #f1f5f9;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .mobile-nav-list li.menu-item-open .drop-menu-1 {
        display: block;
    }

    .mobile-nav-list .drop-menu-1 li {
        border-bottom: none;
        background-color: transparent;
    }

    .mobile-nav-list .drop-menu-1 li a {
        padding: 12px 20px 12px 40px;
        font-size: 13px;
        color: #333;
    }

    /* Submenu Toggle Icon */
    .mobile-nav-list > li.has-submenu > a:after,
    .mobile-nav-list > li.drop-open > a:after {
        content: "\e5cc";
        float: right;
        font-family: "Material Icons";
        font-size: 20px;
        transition: transform 0.3s ease;
    }

    .mobile-nav-list > li.menu-item-open > a:after {
        content: "\e5ce";
    }

    /* Overlay */
    .mobile-nav-overlay {
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

    .mobile-nav-container.menu-open .mobile-nav-overlay {
        opacity: 1;
        visibility: visible;
    }
    
    /* Ensure header-bottom is visible on mobile */
    .header-bottom {
        display: block !important;
    }
    
    /* Make sure inner-wrap shows toggle button */
    .header-bottom .inner-wrap {
        display: flex !important;
        align-items: center;
    }
}

@media (max-width: 767px) {
    .header-bottom .inner-wrap {
        justify-content: center;
    }
    
    .header-bottom .h-flash-btn {
        padding: 6px 8px;
        font-size: 11px;
    }
    
    .header-bottom .h-flash-btn i {
        font-size: 16px;
    }
}
</style>

<script>
// NEW Mobile Navigation - Using Unique Class Names - No Conflicts
(function() {
    'use strict';
    
    var mobileToggle = null;
    var mobileNav = null;
    var mobileOverlay = null;
    var isMenuOpen = false;
    
    function initMobileNavigation() {
        // Get elements using new class names
        mobileToggle = document.getElementById('mobile-menu-toggle');
        mobileNav = document.getElementById('mobile-nav-menu');
        mobileOverlay = document.querySelector('.mobile-nav-overlay');
        
        if (!mobileToggle) {
            console.log('Mobile toggle button not found');
            return false;
        }
        
        if (!mobileNav) {
            console.log('Mobile nav menu not found');
            return false;
        }
        
        console.log('Mobile navigation initialized successfully');
        
        // Toggle menu function
        function toggleMobileMenu() {
            isMenuOpen = !isMenuOpen;
            
            if (isMenuOpen) {
                // Open menu
                mobileToggle.classList.add('menu-active');
                mobileNav.classList.add('menu-open');
                document.body.classList.add('mobile-menu-open');
            } else {
                // Close menu
                mobileToggle.classList.remove('menu-active');
                mobileNav.classList.remove('menu-open');
                document.body.classList.remove('mobile-menu-open');
            }
        }
        
        // Close menu function
        function closeMobileMenu() {
            if (isMenuOpen) {
                isMenuOpen = false;
                mobileToggle.classList.remove('menu-active');
                mobileNav.classList.remove('menu-open');
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
        
        // Submenu toggle
        var submenuLinks = mobileNav.querySelectorAll('.mobile-nav-list > li.has-submenu > a, .mobile-nav-list > li.drop-open > a');
        submenuLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 991) {
                    e.preventDefault();
                    e.stopPropagation();
                    var parentLi = this.parentElement;
                    parentLi.classList.toggle('menu-item-open');
                }
            });
        });
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isMenuOpen) {
                closeMobileMenu();
            }
        });
        
        // Close when clicking outside (but not on menu items)
        document.addEventListener('click', function(e) {
            if (isMenuOpen && window.innerWidth <= 991) {
                if (!e.target.closest('#mobile-menu-toggle') && 
                    !e.target.closest('#mobile-nav-menu') && 
                    !e.target.closest('.mobile-nav-list')) {
                    closeMobileMenu();
                }
            }
        });
        
        return true;
    }
    
    // Initialize when DOM is ready
    function init() {
        // Sticky header functionality (keep existing)
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
        
        // Initialize mobile navigation
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initMobileNavigation);
        } else {
            initMobileNavigation();
        }
    }
    
    // Start initialization
    init();
})();
</script>
