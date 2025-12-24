<footer class="footer style-3">
    <div class="container">
        <!-- Footer Top  -->
        <div class="footer-top">
            <div class="row">
                <div class="col-lg-3 col-xl-3 col-md-5 col-12">
                    <div class="footer-widget footer-about">
                        <!-- Logo  -->
                        <?php if (isset($logo) && $logo) { ?>
                        <a href="<?php echo isset($home) ? $home : 'index.php?route=common/home'; ?>" class="footer-logo">
                            <img src="<?php echo $logo; ?>" alt="<?php echo isset($config_name) ? $config_name : 'Store'; ?>">
                        </a>
                        <?php } ?>
                        <p class="f-about-text">
                            <?php echo isset($config_name) ? $config_name : 'Store'; ?> is an e-commerce platform dedicated to providing quality products to every home.
                        </p>
                        <!-- Footer Contact -->
                        <ul class="footer-contact">
                            <?php if (isset($address) && $address) { ?>
                            <li>
                                <i class="w-icon-map-marker"></i><?php echo strip_tags($address); ?>
                            </li>
                            <?php } ?>
                            <?php if (isset($telephone) && $telephone) { ?>
                            <li>
                                <a href="tel:<?php echo $telephone; ?>"><i class="w-icon-phone"></i><?php echo $telephone; ?></a>
                            </li>
                            <?php } ?>
                            <?php if (isset($email) && $email) { ?>
                            <li>
                                <a href="mailto:<?php echo $email; ?>">
                                    <i class="w-icon-envelop-closed"></i>
                                    <?php echo $email; ?>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                        <!-- Footer Social -->
                        <ul class="footer-social">
                            <?php if (isset($facebook_url) && $facebook_url != '#') { ?>
                            <li>
                                <a href="<?php echo $facebook_url; ?>" target="_blank"><i class="w-icon-facebook"></i></a>
                            </li>
                            <?php } ?>
                            <?php if (isset($twitter_url) && $twitter_url != '#') { ?>
                            <li>
                                <a href="<?php echo $twitter_url; ?>" target="_blank"><i class="w-icon-twitter"></i></a>
                            </li>
                            <?php } ?>
                            <?php if (isset($instagram_url) && $instagram_url != '#') { ?>
                            <li>
                                <a href="<?php echo $instagram_url; ?>" target="_blank"><i class="w-icon-instagram"></i></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-xl-2 col-md-4 col-6">
                    <div class="footer-widget quick-links footer-accordion-item">
                        <p class="footer-widget-title footer-accordion-toggle">
                            About Ruplexa
                            <span class="footer-accordion-icon">+</span>
                        </p>
                        <div class="footer-accordion-content">
                        <ul class="footer-widget-list">
                            <?php if (isset($about_ruplexa) && $about_ruplexa) { ?>
                            <?php foreach ($about_ruplexa as $info) { ?>
                            <li><a href="<?php echo $info['href']; ?>"><?php echo $info['title']; ?></a></li>
                            <?php } ?>
                            <?php } ?>
                        </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-xl-2 col-md-4 col-6">
                    <div class="footer-widget quick-links footer-accordion-item">
                        <p class="footer-widget-title footer-accordion-toggle">
                            My Ruplexa
                            <span class="footer-accordion-icon">+</span>
                        </p>
                        <div class="footer-accordion-content">
                        <ul class="footer-widget-list">
                            <?php if (isset($my_ruplexa) && $my_ruplexa) { ?>
                            <?php foreach ($my_ruplexa as $info) { ?>
                            <li><a href="<?php echo $info['href']; ?>"><?php echo $info['title']; ?></a></li>
                            <?php } ?>
                            <?php } ?>
                        </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-xl-2 col-md-4 col-6">
                    <div class="footer-widget quick-links footer-accordion-item">
                        <p class="footer-widget-title footer-accordion-toggle">
                            Help
                            <span class="footer-accordion-icon">+</span>
                        </p>
                        <div class="footer-accordion-content">
                        <ul class="footer-widget-list">
                            <?php if (isset($help) && $help) { ?>
                            <?php foreach ($help as $info) { ?>
                            <li><a href="<?php echo $info['href']; ?>"><?php echo $info['title']; ?></a></li>
                            <?php } ?>
                            <?php } ?>
                            <?php if (isset($contact) && $contact) { ?>
                            <li><a href="<?php echo $contact; ?>"><?php echo isset($text_contact) ? $text_contact : 'Contact Us'; ?></a></li>
                            <?php } ?>
                        </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-xl-2 col-md-3 col-6">
                    <div class="footer-widget accounts footer-accordion-item">
                        <p class="footer-widget-title footer-accordion-toggle">
                            Shop By Category
                            <span class="footer-accordion-icon">+</span>
                        </p>
                        <div class="footer-accordion-content">
                        <ul class="footer-widget-list">
                            <?php if (isset($categories) && $categories) { ?>
                            <?php $category_count = 0; ?>
                            <?php foreach ($categories as $category) { ?>
                            <?php if ($category_count < 7) { ?>
                            <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
                            <?php $category_count++; ?>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                        </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-xl-3 col-md-7 col-12">
                    <div class="footer-widget newsletter footer-accordion-item">
                        <p class="footer-widget-title footer-accordion-toggle" style="color: #666;">
                            Sign Up Newsletter
                            <span class="footer-accordion-icon">+</span>
                        </p>
                        <div class="footer-accordion-content">
                        <p class="f-widget-text">Don't worry, we won't spam you!</p>
                        <form action="<?php echo isset($newsletter_action) ? $newsletter_action : $newsletter; ?>" method="POST" class="footer-newsletter needs-validation" id="newsletterForm" novalidate="">
                            <input type="email" name="email" class="form-control" placeholder="Type Your E-mail" required="">
                            <button type="submit" class="g-recaptcha">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_4110_502)">
                                        <path d="M13.8945 8.11651L6.57622 15.4546C6.41117 15.6196 6.21643 15.7021 5.992 15.7022C5.76757 15.7022 5.57285 15.6197 5.40785 15.4547C5.24285 15.2897 5.16367 15.0917 5.1703 14.8607C5.17694 14.6296 5.25618 14.4382 5.40802 14.2864L12.7461 6.96811L6.48835 6.96902C6.25072 6.96906 6.05105 6.88823 5.88935 6.72653C5.72765 6.56483 5.64847 6.37011 5.6518 6.14238C5.65513 5.91464 5.73602 5.72155 5.89447 5.56311C6.05292 5.40466 6.25096 5.32542 6.48859 5.32538L14.7266 5.32418C14.951 5.32414 15.1408 5.40168 15.2959 5.55678C15.451 5.71188 15.5351 5.90824 15.5483 6.14588L15.5471 14.3839C15.5471 14.6083 15.4645 14.8031 15.2995 14.9681C15.1344 15.1332 14.943 15.2124 14.7252 15.2058C14.4875 15.2059 14.2879 15.125 14.1262 14.9633C13.9645 14.8016 13.8902 14.6086 13.9035 14.3841L13.8945 8.11651Z" fill="#fff"></path>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_4110_502">
                                            <rect width="14.8351" height="14.0007" fill="white" transform="matrix(0.707107 -0.707107 -0.707107 -0.707107 10 21)"></rect>
                                        </clipPath>
                                    </defs>
                                </svg>
                            </button>
                        </form>
                        <div class="footer-download-app">
                            <p class="title">Download App on Mobile :</p>
                            <p>15% discount on your first purchase</p>
                            <div class="f-download-app-links">
                                <a href="#" target="_blank"><img src="catalog/view/theme/ranger_fashion/image/google-play.svg" alt="play-store" onerror="this.style.display='none'"></a>
                                <a href="#" target="_blank"><img src="catalog/view/theme/ranger_fashion/image/app-store.svg" alt="app-store" onerror="this.style.display='none'"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                                </div>
                                </div>
        <!-- Footer Bottom  -->
        <div class="footer-bottom">
            <div class="row">
                <div class="col-md-12">
                    <div class="footer-bottom-widget">
                        <p class="f-copyight-text">
                            Copyright © <?php echo date('Y'); ?> <?php echo isset($config_name) ? $config_name : 'Store'; ?> <span class="getcommerce-copyright">Developed&nbsp;by <a href="https://getcommerce.xyz" target="_blank">Getcommerce</a></span>
                        </p>
                        <div class="footer-payment-img">
                            <img src="image/catalog/faysy1756641916.png" alt="payment-img" onerror="this.style.display='none'">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
/* Footer Accordion - Mobile and Tablet Only */
@media (max-width: 991px) {
    .footer-accordion-item {
        margin-bottom: 15px;
        border-bottom: 1px solid #e8e8e8;
    }
    
    .footer-accordion-toggle {
        position: relative;
        cursor: pointer;
        user-select: none;
        padding: 15px 0;
        margin: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        transition: color 0.3s ease;
    }
    
    .footer-accordion-toggle:hover {
        color: #FF6A00;
    }
    
    .footer-accordion-icon {
        font-size: 20px;
        font-weight: 300;
        color: #666;
        transition: transform 0.3s ease, color 0.3s ease;
        display: inline-block;
        width: 24px;
        height: 24px;
        line-height: 24px;
        text-align: center;
    }
    
    .footer-accordion-item.active .footer-accordion-icon {
        color: #FF6A00;
        font-weight: 400;
    }
    
    .footer-accordion-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease-out, padding 0.4s ease-out;
        padding: 0;
    }
    
    .footer-accordion-item.active .footer-accordion-content {
        max-height: 1000px;
        padding: 0 0 15px 0;
        transition: max-height 0.5s ease-in, padding 0.5s ease-in;
    }
    
    .footer-accordion-content .footer-widget-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    
    .footer-accordion-content .footer-widget-list li {
        padding: 8px 0;
        border-bottom: none;
    }
    
    .footer-accordion-content .footer-widget-list li a {
        color: #666;
        text-decoration: none;
        transition: color 0.3s ease;
        font-size: 14px;
    }
    
    .footer-accordion-content .footer-widget-list li a:hover {
        color: #FF6A00;
        padding-left: 5px;
    }
    
    .footer-accordion-content .f-widget-text {
        margin-bottom: 15px;
        font-size: 13px;
        color: #666;
    }
    
    .footer-accordion-content .footer-newsletter {
        margin-bottom: 15px;
    }
    
    .footer-accordion-content .footer-download-app {
        margin-top: 15px;
    }
}

/* Desktop and Laptop - No Accordion */
@media (min-width: 992px) {
    .footer-accordion-toggle {
        cursor: default;
    }
    
    .footer-accordion-icon {
        display: none !important;
    }
    
    .footer-accordion-content {
        max-height: none !important;
        overflow: visible !important;
        padding: 0 !important;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    function initFooterAccordion() {
        if ($(window).width() <= 991) {
            // Mobile/Tablet - Initialize accordion
            $('.footer-accordion-content').css('max-height', '0');
            
            $('.footer-accordion-toggle').off('click').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                var $item = $(this).closest('.footer-accordion-item');
                var $content = $item.find('.footer-accordion-content');
                var $icon = $item.find('.footer-accordion-icon');
                var isActive = $item.hasClass('active');
                
                // Toggle current item
                if (isActive) {
                    // Close
                    $item.removeClass('active');
                    $content.css('max-height', '0');
                    $icon.text('+');
                } else {
                    // Open
                    $item.addClass('active');
                    var scrollHeight = $content[0].scrollHeight;
                    $content.css('max-height', scrollHeight + 'px');
                    $icon.text('−');
                }
            });
        } else {
            // Desktop - Remove accordion functionality
            $('.footer-accordion-item').removeClass('active');
            $('.footer-accordion-content').css({
                'max-height': '',
                'overflow': '',
                'padding': ''
            });
            $('.footer-accordion-icon').text('+');
            $('.footer-accordion-toggle').off('click');
        }
    }
    
    // Initialize on page load
    initFooterAccordion();
    
    // Re-initialize on window resize
    var resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            initFooterAccordion();
        }, 250);
    });
});
</script>

<!-- WhatsApp Floating Button -->
<a href="https://wa.me/8801711664166" 
   target="_blank" 
   class="wa-new-float-btn" 
   title="Chat with us on WhatsApp">
    <div class="wa-new-icon-wrapper">
        <i class="fab fa-whatsapp"></i>
    </div>
    <div class="wa-new-pulse-ring"></div>
    <div class="wa-new-pulse-ring-delay"></div>
</a>

<style>
/* WhatsApp Floating Button - New Classes to Avoid Conflicts */
.wa-new-float-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    background: #25D366;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    box-shadow: 0 4px 16px rgba(37, 211, 102, 0.4);
    text-decoration: none;
    transition: all 0.3s ease;
    overflow: visible;
}

.wa-new-float-btn:hover {
    background: #20BA5A;
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(37, 211, 102, 0.6);
}

.wa-new-icon-wrapper {
    position: relative;
    z-index: 2;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.wa-new-float-btn i {
    font-size: 32px;
    color: #ffffff;
    animation: wa-new-icon-bounce 2s infinite;
}

/* Pulse Ring Animation */
.wa-new-pulse-ring,
.wa-new-pulse-ring-delay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 60px;
    height: 60px;
    border: 2px solid #25D366;
    border-radius: 50%;
    opacity: 0;
    animation: wa-new-pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.wa-new-pulse-ring-delay {
    animation-delay: 1s;
}

/* Icon Bounce Animation */
@keyframes wa-new-icon-bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
}

/* Pulse Ring Animation */
@keyframes wa-new-pulse {
    0% {
        transform: translate(-50%, -50%) scale(0.8);
        opacity: 1;
    }
    100% {
        transform: translate(-50%, -50%) scale(2);
        opacity: 0;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .wa-new-float-btn {
        width: 56px;
        height: 56px;
        bottom: 20px;
        right: 20px;
    }
    
    .wa-new-float-btn i {
        font-size: 28px;
    }
    
    .wa-new-pulse-ring,
    .wa-new-pulse-ring-delay {
        width: 56px;
        height: 56px;
    }
}

@media (max-width: 480px) {
    .wa-new-float-btn {
        width: 50px;
        height: 50px;
        bottom: 15px;
        right: 15px;
    }
    
    .wa-new-float-btn i {
        font-size: 24px;
    }
    
    .wa-new-pulse-ring,
    .wa-new-pulse-ring-delay {
        width: 50px;
        height: 50px;
    }
}
</style>
