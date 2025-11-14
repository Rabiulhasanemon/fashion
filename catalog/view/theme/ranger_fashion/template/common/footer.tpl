<footer class="footer style-3">
    <div class="container">
        <!-- Footer Top  -->
    <div class="footer-top">
            <div class="row">
                <div class="col-lg-5 col-xl-3 col-md-5 col-12">
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
                <div class="col-lg-3 col-xl-2 col-md-4 col-6">
                    <div class="footer-widget quick-links">
                        <p class="footer-widget-title">Information</p>
                        <ul class="footer-widget-list">
                            <?php if (isset($informations) && $informations) { ?>
                            <?php foreach ($informations as $information) { ?>
                            <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
                            <?php } ?>
                            <?php } ?>
                            <?php if (isset($contact) && $contact) { ?>
                            <li><a href="<?php echo $contact; ?>"><?php echo isset($text_contact) ? $text_contact : 'Contact Us'; ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-xl-2 col-md-3 col-6">
                    <div class="footer-widget accounts">
                        <p class="footer-widget-title">Shop By Category</p>
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
                <div class="col-lg-5 col-xl-2 col-md-5 col-6">
                    <div class="footer-widget pages">
                        <p class="footer-widget-title">Support</p>
                        <ul class="footer-widget-list">
                            <?php if (isset($account) && $account) { ?>
                            <li><a href="<?php echo $account; ?>"><?php echo isset($text_account) ? $text_account : 'My Account'; ?></a></li>
                            <?php } ?>
                            <?php if (isset($order) && $order) { ?>
                            <li><a href="<?php echo $order; ?>"><?php echo isset($text_order) ? $text_order : 'Order History'; ?></a></li>
                            <?php } ?>
                            <?php if (isset($wishlist) && $wishlist) { ?>
                            <li><a href="<?php echo $wishlist; ?>"><?php echo isset($text_wishlist) ? $text_wishlist : 'Wish List'; ?></a></li>
                            <?php } ?>
                            <?php if (isset($special) && $special) { ?>
                            <li><a href="<?php echo $special; ?>"><?php echo isset($text_special) ? $text_special : 'Specials'; ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-7 col-xl-3 col-md-7 col-12">
                    <div class="footer-widget newsletter">
                        <p class="footer-widget-title">Sign Up Newsletter</p>
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
        <!-- Footer Bottom  -->
        <div class="footer-bottom">
            <div class="row">
                <div class="col-12">
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
