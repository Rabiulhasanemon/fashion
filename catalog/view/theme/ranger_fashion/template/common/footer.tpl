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
                        <div class="ruplexa-footer-social">
                            <h4 class="ruplexa-social-title">Follow Us</h4>
                            <ul class="ruplexa-social-icons">
                                <?php if (isset($facebook_url) && $facebook_url != '#') { ?>
                                <li>
                                    <a href="<?php echo $facebook_url; ?>" target="_blank" rel="noopener noreferrer" class="ruplexa-social-link ruplexa-social-facebook" title="Facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if (isset($twitter_url) && $twitter_url != '#') { ?>
                                <li>
                                    <a href="<?php echo $twitter_url; ?>" target="_blank" rel="noopener noreferrer" class="ruplexa-social-link ruplexa-social-twitter" title="Twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if (isset($instagram_url) && $instagram_url != '#') { ?>
                                <li>
                                    <a href="<?php echo $instagram_url; ?>" target="_blank" rel="noopener noreferrer" class="ruplexa-social-link ruplexa-social-instagram" title="Instagram">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if (isset($youtube_url) && $youtube_url != '#') { ?>
                                <li>
                                    <a href="<?php echo $youtube_url; ?>" target="_blank" rel="noopener noreferrer" class="ruplexa-social-link ruplexa-social-youtube" title="YouTube">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-xl-2 col-md-4 col-6">
                    <div class="footer-widget quick-links footer-accordion-item">
                        <p class="footer-widget-title footer-accordion-toggle">
                            About Ruplexa
                            <span class="footer-accordion-icon">+</span>
                        </p>
                        <div class="footer-accordion-content">
                        <ul class="footer-widget-list ruplexa-footer-list">
                            <?php if (isset($about_ruplexa) && !empty($about_ruplexa)) { ?>
                            <?php foreach ($about_ruplexa as $info) { ?>
                            <li><a href="<?php echo $info['href']; ?>"><?php echo htmlspecialchars($info['title']); ?></a></li>
                            <?php } ?>
                            <?php } else { ?>
                            <!-- Fallback if pages not loaded yet -->
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Blog</a></li>
                            <li><a href="#">Careers</a></li>
                            <li><a href="#">Gift cards</a></li>
                            <li><a href="#">Beauty With Heart</a></li>
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
                        <ul class="footer-widget-list ruplexa-footer-list">
                            <?php if (isset($my_ruplexa) && !empty($my_ruplexa)) { ?>
                            <?php foreach ($my_ruplexa as $info) { ?>
                            <li><a href="<?php echo $info['href']; ?>"><?php echo htmlspecialchars($info['title']); ?></a></li>
                            <?php } ?>
                            <?php } else { ?>
                            <!-- Fallback if pages not loaded yet -->
                            <li><a href="<?php echo isset($special) ? $special : '#'; ?>">Specials</a></li>
                            <li><a href="<?php echo isset($wishlist) ? $wishlist : '#'; ?>">Wish List</a></li>
                            <li><a href="<?php echo isset($order) ? $order : '#'; ?>">Order History</a></li>
                            <li><a href="<?php echo isset($account) ? $account : '#'; ?>">My Account</a></li>
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
                        <ul class="footer-widget-list ruplexa-footer-list">
                            <?php if (isset($help) && !empty($help)) { ?>
                            <?php foreach ($help as $info) { ?>
                            <li><a href="<?php echo $info['href']; ?>"><?php echo htmlspecialchars($info['title']); ?></a></li>
                            <?php } ?>
                            <?php } else { ?>
                            <!-- Fallback if pages not loaded yet -->
                            <li><a href="#">Customer Service</a></li>
                            <li><a href="#">Return and exchanges</a></li>
                            <li><a href="#">Delivery and Pickup Options</a></li>
                            <li><a href="#">Shipping</a></li>
                            <li><a href="#">Billing</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Terms and Condition</a></li>
                            <li><a href="#">Beauty Service FAQ</a></li>
                            <?php } ?>
                            <?php if (isset($contact) && $contact) { ?>
                            <li><a href="<?php echo $contact; ?>"><?php echo isset($text_contact) ? $text_contact : 'Contact Us'; ?></a></li>
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
/* Ruplexa Premium Footer Design */
.footer.style-3 {
    background: linear-gradient(180deg, #1a1a1a 0%, #2d2d2d 100%);
    color: #ffffff;
    padding: 60px 0 0;
    margin-top: 50px;
    position: relative;
}

.footer.style-3::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #FF6B9D 0%, #FF8E9B 50%, #FF6B9D 100%);
}

.footer-top {
    padding-bottom: 40px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.footer-widget {
    margin-bottom: 30px;
}

.footer-about .footer-logo img {
    max-width: 180px;
    height: auto;
    margin-bottom: 20px;
    filter: brightness(0) invert(1);
}

.f-about-text {
    color: #b0b0b0;
    font-size: 14px;
    line-height: 1.8;
    margin-bottom: 25px;
}

.footer-contact {
    list-style: none;
    padding: 0;
    margin: 0 0 25px 0;
}

.footer-contact li {
    margin-bottom: 12px;
    color: #b0b0b0;
    font-size: 14px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.footer-contact li i {
    color: #FF6B9D;
    font-size: 16px;
    margin-top: 2px;
    min-width: 20px;
}

.footer-contact li a {
    color: #b0b0b0;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-contact li a:hover {
    color: #FF6B9D;
}

/* Ruplexa Social Media Icons - Premium Design */
.ruplexa-footer-social {
    margin-top: 25px;
}

.ruplexa-social-title {
    font-size: 16px;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.ruplexa-social-icons {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.ruplexa-social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.ruplexa-social-link::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    transform: translate(-50%, -50%);
    transition: width 0.3s ease, height 0.3s ease;
}

.ruplexa-social-link:hover::before {
    width: 100%;
    height: 100%;
}

.ruplexa-social-link i {
    font-size: 18px;
    position: relative;
    z-index: 1;
    transition: transform 0.3s ease;
}

.ruplexa-social-link:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

.ruplexa-social-link:hover i {
    transform: scale(1.1);
}

/* Individual Social Media Colors */
.ruplexa-social-facebook:hover {
    background: #1877F2;
    color: #ffffff;
}

.ruplexa-social-twitter:hover {
    background: #1DA1F2;
    color: #ffffff;
}

.ruplexa-social-instagram:hover {
    background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
    color: #ffffff;
}

.ruplexa-social-youtube:hover {
    background: #FF0000;
    color: #ffffff;
}

/* Footer Widget Titles */
.footer-widget-title {
    font-size: 18px;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
    padding-bottom: 12px;
}

.footer-widget-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 2px;
    background: linear-gradient(90deg, #FF6B9D, #FF8E9B);
}

.footer-widget-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-widget-list li {
    margin-bottom: 10px;
}

.footer-widget-list li a {
    color: #b0b0b0;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s ease;
    display: inline-block;
}

.footer-widget-list li a:hover {
    color: #FF6B9D;
    padding-left: 8px;
}

/* Newsletter Section */
.f-widget-text {
    color: #b0b0b0;
    font-size: 13px;
    margin-bottom: 15px;
}

.footer-newsletter {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
}

.footer-newsletter .form-control {
    flex: 1;
    padding: 12px 18px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.05);
    color: #ffffff;
    border-radius: 25px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.footer-newsletter .form-control::placeholder {
    color: #888;
}

.footer-newsletter .form-control:focus {
    outline: none;
    border-color: #FF6B9D;
    background: rgba(255, 255, 255, 0.1);
}

.footer-newsletter button {
    width: 50px;
    height: 50px;
    border: none;
    background: linear-gradient(135deg, #FF6B9D 0%, #FF8E9B 100%);
    border-radius: 50%;
    color: #ffffff;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.footer-newsletter button:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(255, 107, 157, 0.4);
}

/* Footer Bottom */
.footer-bottom {
    padding: 25px 0;
    background: rgba(0, 0, 0, 0.3);
    margin-top: 30px;
}

.footer-bottom-widget {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.f-copyight-text {
    color: #b0b0b0;
    font-size: 13px;
    margin: 0;
}

.f-copyight-text a {
    color: #FF6B9D;
    text-decoration: none;
}

.f-copyight-text a:hover {
    text-decoration: underline;
}

.footer-payment-img img {
    max-height: 30px;
    filter: brightness(0) invert(1);
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.footer-payment-img img:hover {
    opacity: 1;
}

/* Responsive Design */
@media (max-width: 991px) {
    .footer.style-3 {
        padding: 40px 0 0;
    }
    
    .footer-bottom-widget {
        flex-direction: column;
        text-align: center;
    }
    
    .ruplexa-social-icons {
        justify-content: center;
    }
}

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
    
    /* Ruplexa Footer List - Unique Class to Avoid Conflicts */
    .ruplexa-footer-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .ruplexa-footer-list li {
        margin: 0;
        padding: 8px 0;
    }
    .ruplexa-footer-list li a {
        color: #666;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
        display: inline-block;
    }
    .ruplexa-footer-list li a:hover {
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

<!-- Compare Floating Button -->
<a href="<?php echo isset($compare) ? $compare : '#'; ?>" 
   class="ruplexa-compare-float-btn" 
   title="Compare Products">
    <div class="ruplexa-compare-icon-wrapper">
        <i class="fa fa-balance-scale"></i>
    </div>
    <?php if (isset($compare_count) && $compare_count > 0) { ?>
    <span class="ruplexa-compare-badge"><?php echo $compare_count; ?></span>
    <?php } ?>
</a>

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
/* Compare Floating Button - New Classes to Avoid Conflicts */
.ruplexa-compare-float-btn {
    position: fixed;
    bottom: 100px;
    right: 30px;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9998;
    box-shadow: 0 4px 16px rgba(102, 126, 234, 0.4);
    text-decoration: none;
    transition: all 0.3s ease;
    overflow: visible;
}

.ruplexa-compare-float-btn:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
}

.ruplexa-compare-icon-wrapper {
    position: relative;
    z-index: 2;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ruplexa-compare-float-btn i {
    font-size: 28px;
    color: #ffffff;
    animation: ruplexa-compare-icon-bounce 2s infinite;
}

.ruplexa-compare-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #FF6B9D;
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 8px rgba(255, 107, 157, 0.4);
    animation: ruplexa-compare-badge-pulse 2s infinite;
}

@keyframes ruplexa-compare-icon-bounce {
    0%, 100% {
        transform: translateY(0) rotate(0deg);
    }
    50% {
        transform: translateY(-3px) rotate(-5deg);
    }
}

@keyframes ruplexa-compare-badge-pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.15);
    }
}

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
    .ruplexa-compare-float-btn {
        width: 56px;
        height: 56px;
        bottom: 90px;
        right: 20px;
    }
    
    .ruplexa-compare-float-btn i {
        font-size: 24px;
    }
    
    .ruplexa-compare-badge {
        width: 22px;
        height: 22px;
        font-size: 11px;
        top: -4px;
        right: -4px;
    }
    
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
    .ruplexa-compare-float-btn {
        width: 50px;
        height: 50px;
        bottom: 80px;
        right: 15px;
    }
    
    .ruplexa-compare-float-btn i {
        font-size: 22px;
    }
    
    .ruplexa-compare-badge {
        width: 20px;
        height: 20px;
        font-size: 10px;
        top: -3px;
        right: -3px;
    }
    
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
