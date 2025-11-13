<?php if (isset($categories) && $categories) { ?>
<div class="sticky-content-wrapper">
    <div class="header-bottom sticky-content fix-top sticky-header has-dropdown">
        <div class="container">
            <div class="inner-wrap">
                <div id="nav-toggler" class="nav-toggler"><span></span><span></span><span></span></div>
                <div class="header-left">
                    <nav class="main-nav ml-0">
                        <ul class="menu">
                            <?php foreach ($categories as $category) { ?>
                            <?php if (isset($category['children']) && !empty($category['children'])) { ?>
                            <li class="has-submenu">
                                <a href="<?php echo $category['href']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </a>
                                <ul class="submenu">
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
                    </nav>
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
    background: var(--secondary-color, #041f1e);
    position: relative;
    z-index: 1000;
}

.header-bottom .container {
    max-width: 80%;
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
    min-width: 21.5rem;
    padding: 2rem 0;
    background: #fff;
    box-shadow: 0 2px 35px rgba(0, 0, 0, 0.1);
    z-index: 1001;
    visibility: hidden;
    opacity: 1;
    transform: translate3d(0, -10px, 0);
    transition: transform 0.3s ease-out;
    list-style: none;
    margin: 0;
}

.header-bottom .main-nav .menu > li.show > .submenu,
.header-bottom .main-nav .menu > li:hover > .submenu {
    visibility: visible;
    top: 100%;
    transform: translate3d(0, 0, 0);
}

.header-bottom .main-nav .menu .submenu li {
    padding: 0 1.9rem;
    width: 100%;
}

.header-bottom .main-nav .menu .submenu li a {
    display: block;
    padding: 0.7rem 0 0.8rem 0;
    color: var(--title-color, #232323);
    font-weight: 500;
    font-size: 1.4rem;
    line-height: 1;
    text-decoration: none;
    transition: color 0.3s ease;
}

.header-bottom .main-nav .menu .submenu li.active > a,
.header-bottom .main-nav .menu .submenu li:hover > a {
    color: var(--secondary-color, #ff8c42);
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
.header-bottom .nav-toggler {
    display: none;
    width: 30px;
    height: 30px;
    position: relative;
    cursor: pointer;
    z-index: 1002;
    margin-right: 15px;
}

.header-bottom .nav-toggler span {
    display: block;
    position: absolute;
    height: 3px;
    width: 100%;
    background: var(--white-color, #fff);
    border-radius: 3px;
    opacity: 1;
    left: 0;
    transform: rotate(0deg);
    transition: 0.25s ease-in-out;
}

.header-bottom .nav-toggler span:nth-child(1) {
    top: 0px;
}

.header-bottom .nav-toggler span:nth-child(2) {
    top: 10px;
}

.header-bottom .nav-toggler span:nth-child(3) {
    top: 20px;
}

.header-bottom .nav-toggler.active span:nth-child(1) {
    top: 10px;
    transform: rotate(135deg);
}

.header-bottom .nav-toggler.active span:nth-child(2) {
    opacity: 0;
    left: -60px;
}

.header-bottom .nav-toggler.active span:nth-child(3) {
    top: 10px;
    transform: rotate(-135deg);
}

/* Responsive */
@media (max-width: 991px) {
    .header-bottom .nav-toggler {
        display: block;
    }
    
    .header-bottom .main-nav {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: var(--primary-color, #FF6A00);
        width: 100%;
        z-index: 1001;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
    }
    
    .header-bottom .main-nav.active {
        display: block;
        max-height: 1000px;
        padding: 15px 0;
    }
    
    .header-bottom .main-nav .menu {
        flex-direction: column;
        align-items: flex-start;
        padding: 0 15px;
    }
    
    .header-bottom .main-nav .menu > li {
        width: 100%;
        margin-right: 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .header-bottom .main-nav .menu > li:last-child {
        border-bottom: none;
    }
    
    .header-bottom .main-nav .menu > li > a {
        padding: 15px 0;
        width: 100%;
    }
    
    .header-bottom .main-nav .menu .submenu {
        position: static;
        visibility: visible;
        opacity: 1;
        transform: none;
        box-shadow: none;
        background: rgba(0, 0, 0, 0.1);
        margin-top: 0;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
        padding: 0;
    }
    
    .header-bottom .main-nav .menu > li.show > .submenu {
        max-height: 500px;
        padding: 10px 0;
    }
    
    .header-bottom .main-nav .menu .submenu li {
        padding: 0 1.5rem;
    }
    
    .header-bottom .main-nav .menu .submenu li a {
        padding: 10px 0;
        color: rgba(255, 255, 255, 0.9);
    }
    
    .header-bottom .h-flash-btn {
        padding: 8px;
        font-size: 12px;
    }
    
    .header-bottom .h-flash-btn i {
        font-size: 18px;
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
    
    // Mobile navigation toggle
    $('#nav-toggler').on('click', function() {
        $(this).toggleClass('active');
        $('.header-bottom .main-nav').toggleClass('active');
    });
    
    // Mobile submenu toggle
    $('.header-bottom .menu > li.has-submenu > a').on('click', function(e) {
        if ($(window).width() <= 991) {
            e.preventDefault();
            $(this).parent().toggleClass('show');
        }
    });
    
    // Close menu when clicking outside
    $(document).on('click', function(e) {
        if ($(window).width() <= 991) {
            if (!$(e.target).closest('.header-bottom').length) {
                $('#nav-toggler').removeClass('active');
                $('.header-bottom .main-nav').removeClass('active');
            }
        }
    });
});
</script>
