<?php if (isset($categories) && $categories) { ?>
<div class="header-bottom sticky-content fix-top sticky-header has-dropdown">
    <div class="container">
        <div class="inner-wrap">
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
                <i class="fi-rs-sparkles"></i>Flash Sale
            </a>
            <?php } ?>
        </div>
    </div>
</div>
<?php } ?>

<style>
/* Header Bottom Navigation Styles */
.header-bottom {
    padding: 0;
    background: var(--primary-color, #FF6A00);
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
    margin-right: 24px;
}

.header-bottom .main-nav .menu > li:last-child {
    margin-right: 0;
}

.header-bottom .main-nav .menu > li > a {
    display: block;
    padding: 13px 0;
    font-size: 16px;
    font-weight: 500;
    line-height: 150%;
    color: var(--white-color, #fff);
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
}

.header-bottom .main-nav .menu > li > a:hover,
.header-bottom .main-nav .menu > li.active > a {
    color: var(--white-color, #fff) !important;
}

.header-bottom .main-nav .menu > li.has-submenu > a::after {
    content: "";
    display: inline-block;
    margin-left: 6px;
    font-family: "Font Awesome 5 Free";
    font-size: 8px;
    font-weight: 900;
    vertical-align: middle;
}

.header-bottom .main-nav .menu .submenu {
    position: absolute;
    top: 100%;
    left: 0;
    min-width: 215px;
    padding: 1rem 0;
    background: #fff;
    box-shadow: 0 2px 35px rgba(0, 0, 0, 0.1);
    z-index: 1001;
    visibility: hidden;
    opacity: 0;
    transform: translate3d(0, -10px, 0);
    transition: all 0.3s ease-out;
    list-style: none;
    margin: 0;
}

.header-bottom .main-nav .menu > li:hover > .submenu,
.header-bottom .main-nav .menu > li.show > .submenu {
    visibility: visible;
    opacity: 1;
    transform: translate3d(0, 0, 0);
}

.header-bottom .main-nav .menu .submenu li {
    padding: 0 1.9rem;
    width: 100%;
}

.header-bottom .main-nav .menu .submenu li a {
    display: block;
    padding: 0.7rem 0 0.8rem 0;
    color: var(--primary-color, #FF6A00);
    font-weight: 500;
    font-size: 15px;
    text-decoration: none;
    transition: color 0.3s ease;
}

.header-bottom .main-nav .menu .submenu li a:hover {
    color: var(--secondary-color, #ff8c42);
}

.header-bottom .h-flash-btn {
    border-radius: 4px;
    background: var(--white-color, #fff);
    padding: 6px 10px;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    font-style: normal;
    font-weight: 600;
    line-height: 120%;
    letter-spacing: 0.7px;
    text-transform: uppercase;
    color: var(--primary-color, #FF6A00) !important;
    text-decoration: none;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.header-bottom .h-flash-btn:hover {
    background: var(--secondary-color, #ff8c42);
    color: var(--white-color, #fff) !important;
}

.header-bottom .h-flash-btn i {
    font-size: 24px;
}

/* Sticky Header */
.header-bottom.sticky-content.fixed {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    opacity: 1;
    transform: translateY(0);
    z-index: 1051;
    box-shadow: 0 0 10px 1px rgba(0, 0, 0, 0.1);
    background: var(--white-color, #fff);
}

.header-bottom.sticky-content.fixed .main-nav .menu > li > a {
    color: var(--title-color, #232323);
}

.header-bottom.sticky-content.fixed .main-nav .menu > li > a:hover,
.header-bottom.sticky-content.fixed .main-nav .menu > li.active > a {
    color: var(--primary-color, #FF6A00) !important;
}

.header-bottom.sticky-content.fixed .h-flash-btn {
    background: var(--primary-color, #FF6A00);
    color: var(--white-color, #fff) !important;
}

.header-bottom.sticky-content.fixed .h-flash-btn:hover {
    background: var(--secondary-color, #ff8c42);
}

/* Responsive */
@media (max-width: 991px) {
    .header-bottom .main-nav {
        display: none;
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
    
    // Mobile menu toggle (if needed)
    $('.header-bottom .menu > li.has-submenu > a').on('click', function(e) {
        if ($(window).width() <= 991) {
            e.preventDefault();
            $(this).parent().toggleClass('show');
        }
    });
});
</script>
