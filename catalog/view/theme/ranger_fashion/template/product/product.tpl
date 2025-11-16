<?php echo $header; ?>
<section class="after-header">
    <div class="container">
        <ul class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            <?php foreach ($breadcrumbs as $i => $breadcrumb) { if($i < 1) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } else { ?>
            <li  itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemtype="http://schema.org/Thing" itemprop="item" href="<?php echo $breadcrumb['href']; ?>"><span itemprop="name"><?php echo $breadcrumb['text']; ?></span></a><meta itemprop="position" content="<?php echo $i; ?>" /></li>
            <?php }} ?>
        </ul>
    </div>
</section>
<section id="content-top">
    <div class="container"><?php echo $content_top; ?></div>
</section>
<?php if ($success) { ?>
<div class="container alert-container">
    <div class="alert alert-success"><?php echo $success; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
</div>
<?php } ?>
<div class="product-details" itemscope itemtype="http://schema.org/Product">
    <meta itemprop="productID" content="<?php echo $product_id; ?>">
    <meta itemprop="sku" content="<?php echo $product_id; ?>">
    <section class="basic">
        <div class="container" id="product">
            <div class="row gutter-lg">
                <div class="main-content">
                    <div class="product product-single row">
                        <div class="col-xl-6 col-12">
                            <div class="pd-details-gallery product-gallery product-gallery-sticky">
                                <div class="pd-vertical-slider-container">
                                    <?php if ($images && count($images) > 0) { ?>
                                    <div class="p-thumb-img-slider swiper p-details-slider vertical-thumbs swiper-thumbs">
                                        <div class="swiper-wrapper">
                                            <?php if ($thumb) { ?>
                                            <div class="swiper-slide single-swiper-thumbs swiper-slide-thumb-active">
                                                <img src="<?php echo $thumb; ?>" alt="product-img">
                                            </div>
                                            <?php } ?>
                                            <?php foreach ($images as $image) { ?>
                                            <div class="swiper-slide single-swiper-thumbs">
                                                <img src="<?php echo $image['thumb']; ?>" alt="product-img">
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="swiper-button-next"></div>
                                        <div class="swiper-button-prev"></div>
                                    </div>
                                    <?php } ?>
                                    <div class="p-details-big-img swiper p-details-slider-2">
                                        <div class="swiper-wrapper">
                                            <?php if ($thumb) { ?>
                                            <div class="swiper-slide single-slider-img zoom zoomSingleImage" href="<?php echo $popup; ?>" data-fancybox="photo">
                                                <img src="<?php echo $thumb; ?>" alt="product-img">
                                                <meta itemprop="image" content="<?php echo $thumb; ?>"/>
                                            </div>
                                            <?php } ?>
                                            <?php if ($images) { ?>
                                            <?php foreach ($images as $image) { ?>
                                            <div class="swiper-slide single-slider-img zoom zoomSingleImage" href="<?php echo $image['popup']; ?>" data-fancybox="photo">
                                                <img src="<?php echo $image['popup']; ?>" alt="product-img">
                                                <meta itemprop="image" content="<?php echo $image['thumb']; ?>"/>
                                            </div>
                                            <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-12">
                            <div class="product-details" data-sticky-options="{'minWidth': 767}">
                                <h1 class="product-title"><?php echo $heading_title; ?></h1>
                                <div class="pd-details-info-top">
                                    <div class="product-price">
                                        <?php if ($disablePurchase || !$special) { ?>
                                        <ins class="new-price new-price-filter"><?php echo $price; ?></ins>
                                        <small class="mr-0 old-price-filter"><del class="old-price"></del></small>
                                        <?php } else { ?>
                                        <ins class="new-price new-price-filter"><?php echo $special; ?></ins>
                                        <small class="mr-0 old-price-filter"><del class="old-price"><?php echo $price; ?></del></small>
                                        <?php } ?>
                                        <input type="hidden" name="product_price" id="product_discount_price" value="<?php echo $special ? str_replace(['৳', ','], '', $special) : '0'; ?>">
                                        <input type="hidden" name="product_price" id="product_price" value="<?php echo str_replace(['৳', ','], '', $price); ?>">
                                    </div>
                                </div>
                                <hr class="product-divider">
                                <input type="hidden" name="product_id" id="product_id" value="<?php echo $product_id; ?>">
                                <input type="hidden" value="0" id="selectedVariantId" name="selected_variant">
                                
                                <div id="product_details_add_to_cart_section">
                                    <?php if ($options) { ?>
                                    <div class="p-opt-wrap">
                                        <?php foreach ($options as $option) { ?>
                                        <?php if($option['type'] === 'select'){ ?>
                                        <div class="p-opt color required">
                                            <div class="p-opt-lbl" id="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?>: <b></b></div>
                                            <div class="p-opt-vals">
                                                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                                <label><input class="hide" type="radio" value="<?php echo $option_value['option_value_id']; ?>" name="option[<?php echo $option['product_option_id']; ?>]" title="<?php echo $option_value['name']; ?>"><span><?php echo $option_value['name']; ?></span></label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php } else { ?>
                                        <div class="p-opt required">
                                            <div class="p-opt-lbl" id="input-option<?php echo $option['product_option_id']; ?>">Select <?php echo $option['name']; ?>: <b></b></div>
                                            <div class="p-opt-vals">
                                                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                                <label><input class="hide" type="radio" value="<?php echo $option_value['option_value_id']; ?>" name="option[<?php echo $option['product_option_id']; ?>]" title="<?php echo $option_value['name']; ?>"><span><?php echo $option_value['name']; ?></span></label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php } ?>
                                    </div>
                                    <?php } ?>
                                    
                                    <div class="product-qty-form">
                                        <span>Quantity:</span>
                                        <div class="input-group">
                                            <button class="w-icon-plus quantity__value_details decrease" data-id="<?php echo $product_id; ?>"></button>
                                            <input class="form-control" id="product_details_cart_qty" value="<?php echo $minimum; ?>" type="number" min="1" max="100">
                                            <button class="w-icon-minus quantity__value_details increase" data-id="<?php echo $product_id; ?>"></button>
                                        </div>
                                    </div>
                                    <div class="pd-action-order-btns">
                                        <button class="btn btn-primary cart-qty-<?php echo $product_id; ?> addToCartWithQty" data-id="<?php echo $product_id; ?>" data-color="0" data-size="0" type="button"><i class="w-icon-cart"></i><span> Add to Cart</span></button>
                                        <button class="product-btn shake-btn btn btn-primary theme-btn buyNow-btn cart-buynow-qty-<?php echo $product_id; ?> addToBuyNowWithQty rsi-shake" data-id="<?php echo $product_id; ?>" data-color="0" data-size="0" type="button">Buy Now</button>
                                    </div>
                                </div>
                                
                                <div class="product-details-action-group">
                                    <a href="https://wa.me/8801321208940?text=Hello%21+I%27m+interested+in%3A%0AProduct%3A+<?php echo urlencode($heading_title); ?>%0APrice%3A+<?php echo urlencode($price); ?>%0AProduct+URL%3A+<?php echo urlencode($current_url ?? ''); ?>" class="btn btn-primary whatsapp-order-btn">
                                        <i class="fi fi-brands-whatsapp"></i>
                                        Order on WhatsApp
                                    </a>
                                    <a href="tel:09642922922" class="btn btn-primary call-for-order-btn" title="Call for price"><i class="w-icon-phone"></i> Call for order</a>
                                </div>
                                
                                <?php if (isset($manufacturer) && $manufacturer) { ?>
                                <div class="pd-brand-group">
                                    <div class="product-brand">
                                        <span>Brand:</span>
                                        <a href="<?php echo $manufacturer['href']; ?>" style="text-decoration: none;">
                                            <?php if (isset($manufacturer['image']) && $manufacturer['image']) { ?>
                                            <img src="<?php echo $manufacturer['image']; ?>" alt="<?php echo $manufacturer['name']; ?>">
                                            <?php } else { ?>
                                            <span><?php echo $manufacturer['name']; ?></span>
                                            <?php } ?>
                                        </a>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Product Features Section -->
                    <div class="pd-features">
                        <section class="feature-section style-1">
                            <div class="feature-wrapper">
                                <div class="feature-item">
                                    <div class="feature-icon"><i class="fi fi-rr-wheat"></i></div>
                                    <div class="feature-label"><span>100% Natural food</span></div>
                                </div>
                                <div class="feature-item">
                                    <div class="feature-icon"><i class="fi fi-rr-tachometer-fastest"></i></div>
                                    <div class="feature-label">Fastest Delivery</div>
                                </div>
                                <div class="feature-item">
                                    <div class="feature-icon"><i class="fi-rr-shield-check"></i></div>
                                    <div class="feature-label">Secure Payment</div>
                                </div>
                                <div class="feature-item">
                                    <div class="feature-icon"><i class="fi-rr-shipping-fast"></i></div>
                                    <div class="feature-label"><span>Delivery all over the country</span></div>
                                </div>
                            </div>
                        </section>
                    </div>
                    
                    <!-- Product Tabs -->
                    <div class="product-tabs">
                        <div class="jump-spec">
                            <button class="btn active" data-target="pd-menu-1">Description</button>
                            <?php if (isset($video) && $video) { ?>
                            <button class="btn" data-target="pd-menu-3">Product Video</button>
                            <?php } ?>
                            <button class="btn" data-target="pd-menu-6">Customer Reviews (<?php echo $no_of_review ?? 0; ?>)</button>
                        </div>
                        
                        <div class="section pd-menu-1">
                            <div class="tab-pane">
                                <p class="title tab-pane-title font-weight-bold mb-2">Product Details</p>
                                <div class="product-tabs-content">
                                    <div itemprop="description" class="seo-description"><?php echo $description; ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (isset($video) && $video) { ?>
                        <div class="section pd-menu-3">
                            <div class="tab-pane">
                                <p class="title tab-pane-title font-weight-bold mb-2">Video</p>
                                <div class="product-video">
                                    <div class="product-video-overview">
                                        <div class="product-video-thumb-img">
                                            <iframe src="<?php echo $video; ?>" frameborder="0" allowfullscreen=""></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        
                        <div class="section pd-menu-6">
                            <div class="tab-pane" id="product-tab-reviews">
                                <?php if ($no_of_review) { ?>
                                <div class="row mb-4">
                                    <div class="col-xl-4 col-lg-5 mb-4">
                                        <div class="ratings-wrapper">
                                            <div class="avg-rating-container">
                                                <p class="avg-mark font-weight-bolder ls-50"><?php echo number_format($rating, 1); ?></p>
                                                <div class="avg-rating">
                                                    <p class="text-dark mb-1">Average Rating</p>
                                                    <div class="ratings-container">
                                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                        <i class="far fa-star" style="color: <?php echo $i <= $rating ? '#f93' : 'gray'; ?>;"></i>
                                                        <?php } ?>
                                                        &nbsp;&nbsp;<a href="javascript:void(0)" class="rating-reviews">(<?php echo $no_of_review; ?> Reviews)</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-8 col-lg-7 mb-4">
                                        <div class="review-form-wrapper">
                                            <span class="title tab-pane-title font-weight-bold mb-1">Submit Your Review</span>
                                            <p class="mb-3">Your email address will not be published. Required fields are marked *</p>
                                            <form action="<?php echo $action; ?>" method="post" class="review-form">
                                                <div class="form-group mb-1">
                                                    <label for="review" class="d-block pl-0 mb-1">Write your opinion about the product</label>
                                                    <textarea cols="30" rows="6" name="review" placeholder="Write Your Review Here..." class="form-control" id="review"></textarea>
                                                </div>
                                                <div class="row gutter-md">
                                                    <div class="col-md-6">
                                                        <label for="review" class="d-block pl-0 mb-1">Your Rating: </label>
                                                        <select name="rarting" class="form-control" required="">
                                                            <option value="">Select One</option>
                                                            <option value="5">Perfect</option>
                                                            <option value="4">Good</option>
                                                            <option value="3">Average</option>
                                                            <option value="2">Not that bad</option>
                                                            <option value="1">Very poor</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        <label for="review" class="d-block pl-0 mb-1">&nbsp;</label>
                                                        <button type="submit" style="padding: 0.75em 1.98em;" class="btn btn-dark">Submit Review</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <div id="review" class="mb-3"><?php echo $review; ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Related Products Section -->
                    <?php if ($products) { ?>
                    <section class="related-product-section">
                        <div class="title-link-wrapper mb-4">
                            <span class="title p-0">Related Products</span>
                            <a href="<?php echo $shop_url ?? '#'; ?>" class="btn btn-dark btn-link btn-slide-right btn-icon-right">More Products<i class="w-icon-long-arrow-right"></i></a>
                        </div>
                        <div class="swiper-container swiper-theme" data-swiper-options='{"spaceBetween": 20, "slidesPerView": 2, "breakpoints": {"576": {"slidesPerView": 3}, "768": {"slidesPerView": 4}, "992": {"slidesPerView": 4}}}'>
                            <div class="swiper-wrapper">
                                <?php foreach ($products as $product) { ?>
                                <div class="swiper-slide">
                                    <div class="product style-6">
                                        <figure class="product-media">
                                            <a href="<?php echo $product['href']; ?>">
                                                <img class="lazy" src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>">
                                            </a>
                                        </figure>
                                        <div class="product-details">
                                            <h4 class="product-name">
                                                <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                                            </h4>
                                            <div class="product-price">
                                                <?php if ($product['special']) { ?>
                                                <ins class="new-price"><?php echo $product['special']; ?></ins>
                                                <del class="old-price"><?php echo $product['price']; ?></del>
                                                <?php } else { ?>
                                                <ins class="new-price"><?php echo $product['price']; ?></ins>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="product-card-action">
                                            <div class="quantity-container">
                                                <button class="open-quantity-btn" data-id="<?php echo $product['product_id']; ?>-0-0">
                                                    <i class="fi-rr-shopping-cart"></i> Add To Cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </section>
                    <?php } ?>
                </div>
                
                <!-- Sidebar -->
                <?php if ($products) { ?>
                <aside class="sidebar product-sidebar sidebar-fixed right-sidebar sticky-sidebar-wrapper">
                    <div class="sidebar-overlay"></div>
                    <a class="sidebar-close" href="#"><i class="close-icon"></i></a>
                    <a href="#" class="sidebar-toggle d-flex d-lg-none"><i class="fas fa-chevron-left"></i></a>
                    <div class="sidebar-content scrollable">
                        <div class="pin-wrapper">
                            <div class="sticky-sidebar">
                                <div class="widget widget-products">
                                    <div class="title-link-wrapper mb-2">
                                        <span class="title title-link font-weight-bold">More Products</span>
                                    </div>
                                    <div class="swiper nav-top">
                                        <div class="swiper-container swiper-theme nav-top" data-swiper-options='{"slidesPerView": 1, "spaceBetween": 20, "navigation": {"prevEl": ".swiper-button-prev", "nextEl": ".swiper-button-next"}}'>
                                            <div class="swiper-wrapper">
                                                <div class="widget-col swiper-slide">
                                                    <?php foreach (array_slice($products, 0, 2) as $product) { ?>
                                                    <div class="product product-widget">
                                                        <figure class="product-media">
                                                            <a href="<?php echo $product['href']; ?>">
                                                                <img class="" src="<?php echo $product['thumb']; ?>" alt="" style="width: 100%; max-height: 113px">
                                                            </a>
                                                        </figure>
                                                        <div class="product-details">
                                                            <h4 class="product-name">
                                                                <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                                                            </h4>
                                                            <div class="product-price">
                                                                <?php if ($product['special']) { ?>
                                                                <ins class="new-price"><?php echo $product['special']; ?></ins>
                                                                <del class="old-price"><?php echo $product['price']; ?></del>
                                                                <?php } else { ?>
                                                                <ins class="new-price"><?php echo $product['price']; ?></ins>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <button class="swiper-button-next"></button>
                                            <button class="swiper-button-prev"></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
                <?php } ?>
            </div>
        </div>
    </section>
</div>
<section class="content-bottom">
    <div class="container">
        <?php echo $content_bottom; ?>
    </div>
</section>
<?php echo $footer; ?>
<script>
var product_id = <?php echo $product_id; ?>;
fbq && fbq('track', 'ViewContent', {
    content_ids: ['<?php echo $product_id; ?>'],
    content_type: 'product',
    value: <?php echo $raw_price; ?>,
    currency: 'BDT'
});
</script>

<script>
jQuery(document).ready(function($) {
    // Product Gallery Swiper Initialization
    if (typeof Swiper !== 'undefined') {
        // Thumbnail Slider (Vertical) - only if thumbnails exist
        if ($('.p-thumb-img-slider').length && $('.p-thumb-img-slider .swiper-slide').length > 0) {
            var thumbSlider = new Swiper('.p-thumb-img-slider', {
                direction: 'vertical',
                slidesPerView: 'auto',
                spaceBetween: 10,
                freeMode: true,
                watchSlidesProgress: true,
                navigation: {
                    nextEl: '.p-thumb-img-slider .swiper-button-next',
                    prevEl: '.p-thumb-img-slider .swiper-button-prev',
                },
            });

            // Main Image Slider with thumbs
            var mainSlider = new Swiper('.p-details-big-img', {
                spaceBetween: 10,
                thumbs: {
                    swiper: thumbSlider,
                },
            });
        } else {
            // Main Image Slider without thumbs (single image or no thumbnails)
            var mainSlider = new Swiper('.p-details-big-img', {
                spaceBetween: 10,
            });
        }

        // Related Products Swiper
        if ($('.related-product-section .swiper-container').length) {
            var relatedSwiper = new Swiper('.related-product-section .swiper-container', {
                spaceBetween: 20,
                slidesPerView: 2,
                breakpoints: {
                    576: {
                        slidesPerView: 3
                    },
                    768: {
                        slidesPerView: 4
                    },
                    992: {
                        slidesPerView: 4
                    }
                }
            });
        }

        // Sidebar Products Swiper
        if ($('.product-sidebar .swiper-container').length) {
            var sidebarSwiper = new Swiper('.product-sidebar .swiper-container', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.product-sidebar .swiper-button-next',
                    prevEl: '.product-sidebar .swiper-button-prev',
                },
            });
        }
    }

    // Tab Switching
    $('.jump-spec .btn').on('click', function() {
        var target = $(this).data('target');
        $('.jump-spec .btn').removeClass('active');
        $(this).addClass('active');
        $('.product-tabs .section').hide();
        $('.product-tabs .' + target).show();
    });

    // Show first tab by default
    $('.jump-spec .btn.active').trigger('click');

    // Copy Link Functionality
    const copyLinkElement = document.querySelector('.copy-link');
    if (copyLinkElement) {
        copyLinkElement.addEventListener('click', () => {
            const currentUrl = window.location.href;
            navigator.clipboard.writeText(currentUrl)
                .then(() => {
                    const copyMessage = document.createElement('div');
                    copyMessage.classList.add('copy-message');
                    copyMessage.textContent = 'Copied!';
                    copyLinkElement.appendChild(copyMessage);

                    setTimeout(() => {
                        if (copyLinkElement.contains(copyMessage)) {
                            copyLinkElement.removeChild(copyMessage);
                        }
                    }, 2000);
                })
                .catch(err => {
                    console.error('Failed to copy:', err);
                });
        });
    }
});
</script>