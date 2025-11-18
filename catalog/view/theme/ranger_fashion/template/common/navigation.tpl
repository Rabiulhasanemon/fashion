<?php if (isset($categories) && $categories) { ?>
<div class="sticky-content-wrapper">
    <div class="header-bottom sticky-content fix-top sticky-header has-dropdown">
        <div class="container">
            <div class="inner-wrap">
                <div id="nav-toggler" class="nav-toggler"><span></span><span></span><span></span></div>
                <div class="header-left">
                    <div class="header-menu">
                        <div class="main-nav">
                            <nav id="main-nav" class="nav">
                                <ul class="responsive-menu">
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
                                <div class="overlay"></div>
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

/* Mobile Navigation Toggle */
#nav-toggler {
    display: none;
}

.main-nav .nav {
    position: relative;
}

.main-nav .nav .overlay {
    display: none;
}

/* Body scroll lock when menu is open */
body.no-scroll {
    overflow: hidden;
}

/* Responsive - Mobile Navigation */
@media only screen and (max-width: 991.98px) {
    #nav-toggler {
        width: 22px;
        height: 15px;
        background: rgba(255, 255, 255, 0);
        position: absolute;
        top: 35px;
        left: 0;
        transform: translateY(-50%);
        display: block;
        cursor: pointer;
        z-index: 10002;
    }

    #nav-toggler span:after,
    #nav-toggler span:before {
        content: "";
        position: absolute;
        left: 0;
        top: -7px;
    }

    #nav-toggler span:after {
        top: 7px;
    }

    #nav-toggler span {
        position: relative;
        display: block;
    }

    #nav-toggler span,
    #nav-toggler span:after,
    #nav-toggler span:before {
        width: 100%;
        height: 2px;
        background-color: #000;
        transition: all 0.3s;
        backface-visibility: hidden;
        border-radius: 2px;
    }

    /* on activation */
    #nav-toggler.close span {
        background-color: transparent;
    }

    #nav-toggler.close span:before {
        transform: rotate(45deg) translate(6px, 5px);
    }

    #nav-toggler.close span:after {
        transform: rotate(-45deg) translate(5px, -4px);
    }

    .main-nav {
        position: absolute;
        left: 0;
        top: 2px;
    }

    .main-nav .nav .overlay.open {
        content: "";
        position: fixed;
        left: 0;
        top: 55px;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.4);
        z-index: 999;
        display: block;
        -webkit-transition: all 300ms ease;
        -moz-transition: all 300ms ease;
        -o-transition: all 300ms ease;
        transition: all 300ms ease;
    }

    .main-nav .nav ul li {
        border-bottom: 1px solid #e8e8e8;
        margin: 0;
        height: auto;
    }

    .main-nav .nav .drop-menu-2 li {
        position: relative;
        left: 0;
        top: 0;
    }

    .main-nav .nav li a {
        margin-left: 20px;
        color: black;
        line-height: 40px;
        font-size: 14px;
    }

    .main-nav .nav .drop-menu-1 li a {
        margin-left: 25px;
        color: black;
        line-height: 20px;
    }

    .main-nav .nav .drop-menu-2 li a {
        margin-left: 50px;
    }

    .main-nav .nav .toggle + a {
        display: none;
    }

    .main-nav .nav ul li:before {
        display: none;
    }

    .main-nav .nav {
        visibility: hidden;
    }

    .nav .drop-down li.drop-open > a:before {
        display: none;
    }

    .main-nav .nav .drop-menu-1 li {
        display: block;
        background-color: #f1f5f963;
        border-bottom: none;
        line-height: 40px;
        color: black;
        font-size: 12px;
        text-decoration: none;
        font-weight: 600;
        padding: 0;
    }

    .main-nav .nav .drop-down a:hover {
        background: none;
        color: inherit;
    }

    .main-nav .nav .drop-menu-2 li {
        display: block;
        line-height: 30px;
        color: black;
        font-size: 12px;
        text-decoration: none;
        border: none;
        font-weight: 600;
        padding: 0;
    }

    .main-nav .nav ul ul {
        box-shadow: none;
    }

    .main-nav .nav .drop-menu-2 li a.active {
        color: var(--primaryColor);
    }

    .main-nav .nav .drop-menu-2 li:hover {
        color: var(--primaryColor);
    }

    .main-nav .nav.open {
        visibility: visible;
    }

    .main-nav .nav .responsive-menu {
        width: 300px;
        position: fixed;
        left: -300px;
        top: 55px;
        height: calc(100vh - 55px);
        overflow: auto;
        background: white;
        -webkit-transition: all 300ms ease;
        -moz-transition: all 300ms ease;
        -o-transition: all 300ms ease;
        transition: all 300ms ease;
        z-index: 99999;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .main-nav .nav.open .responsive-menu {
        left: 0;
    }

    .main-nav .nav:before {
        display: none;
    }

    .main-nav .nav ul li {
        display: block;
        width: 100%;
        margin-right: 0;
    }

    .main-nav .nav ul li > ul {
        display: none !important;
    }

    .main-nav .nav ul li.open > ul {
        display: block !important;
        width: 300px;
        height: auto;
    }

    .main-nav .nav .drop-menu-1 a {
        display: block;
    }

    .main-nav .nav li a:hover {
        background: none;
        color: var(--primaryColor);
    }

    .main-nav .nav ul ul {
        float: none;
        position: static;
        color: white;
    }

    /* First Tier Dropdown*/
    .main-nav .nav ul ul li {
        display: block;
        width: 100%;
    }

    .main-nav .nav ul ul ul li {
        position: static;
    }

    .main-nav .nav li.drop-open > a:after,
    .main-nav .nav li.has-submenu > a:after {
        content: "\e5cc";
        float: right;
        margin-right: 20px;
        font-size: 15px;
        font-family: "Material Icons";
    }

    .main-nav .nav li.open > a:after {
        content: "\e5ce";
    }

    .drop-menu-1 li a:only-child:after {
        content: " ";
    }

    .responsive-menu > li:last-child {
        margin-bottom: 100px !important;
    }

    .main-nav .nav ul li.drop-open.c-1:after {
        display: none;
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
jQuery(document).ready(function($) {
    // Sticky header functionality
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
    
    // Mobile navigation toggle - Match the provided mobile nav behavior
    $('#nav-toggler').off('click').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var $toggler = $(this);
        var $nav = $('#main-nav');
        var $overlay = $('.main-nav .nav .overlay');
        
        // Toggle classes
        $toggler.toggleClass('close');
        $nav.toggleClass('open');
        $overlay.toggleClass('open');
        
        // Toggle body scroll
        if ($nav.hasClass('open')) {
            $('body').addClass('no-scroll');
        } else {
            $('body').removeClass('no-scroll');
        }
    });
    
    // Mobile submenu toggle - Use 'open' class for submenus
    $(document).on('click', '.main-nav .nav .responsive-menu > li.has-submenu > a, .main-nav .nav .responsive-menu > li.drop-open > a', function(e) {
        if ($(window).width() <= 991) {
            e.preventDefault();
            e.stopPropagation();
            $(this).parent().toggleClass('open');
        }
    });
    
    // Close menu when clicking on overlay
    $('.main-nav .nav .overlay').on('click', function() {
        if ($(window).width() <= 991) {
            $('#nav-toggler').removeClass('close');
            $('#main-nav').removeClass('open');
            $(this).removeClass('open');
            $('body').removeClass('no-scroll');
        }
    });
    
    // Close menu when clicking outside (but not on nav items)
    $(document).on('click', function(e) {
        if ($(window).width() <= 991) {
            var $target = $(e.target);
            // Don't close if clicking on nav-toggler, nav, or menu items
            if (!$target.closest('#nav-toggler').length && 
                !$target.closest('.main-nav').length && 
                !$target.closest('#main-nav').length) {
                $('#nav-toggler').removeClass('close');
                $('#main-nav').removeClass('open');
                $('.main-nav .nav .overlay').removeClass('open');
                $('body').removeClass('no-scroll');
            }
        }
    });
});
</script>
