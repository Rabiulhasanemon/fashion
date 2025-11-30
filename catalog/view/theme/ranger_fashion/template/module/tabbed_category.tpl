<?php 
// Debug: Ensure module displays even if something is wrong
if (empty($tabs)) {
    // Show debug message if no tabs (only visible if debugging)
    if (isset($_GET['debug_module'])) {
        echo '<!-- TabbedCategory Module: No tabs configured -->';
    }
    return;
}
?>
<div class="deal-of-day-section mt-20" id="<?php echo $module_uid; ?>">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <div>
                        <h2 class="h3"><?php echo !empty($name) ? htmlspecialchars($name) : 'Deals Of The Week'; ?></h2>
                        <?php if (count($tabs) > 1) { ?>
                        <div class="links lux-premium-tabs">
                            <?php $i=0; foreach ($tabs as $tab) { ?>
                            <a class="category_get tabbed-category-tab lux-tab-item <?php echo $i==0 ? 'active' : ''; ?>" 
                               data-tab-id="<?php echo $i; ?>"
                               href="javascript:;"><?php echo htmlspecialchars($tab['title']); ?></a>
                            <?php $i++; } ?>
                        </div>
      <?php } ?>
    </div>
                    <div class="right-area">
                        <div class="countdown countdown-alt" data-date-time="10/10/2022">
                            <span>00<small>Days</small></span> 
                            <span>00<small>Hrs</small></span> 
                            <span>00<small>Min</small></span> 
                            <span>00<small>Sec</small></span>
                        </div>
                        <a class="right_link" href="<?php echo isset($see_all_url) ? $see_all_url : 'index.php?route=product/category'; ?>">
                            View All <i class="icon-chevron-right"></i>
                        </a>
                    </div>
                </div>
      </div>
    </div>
        <div class="row g-3">
            <div class="col-lg-12">
        <?php $i=0; foreach ($tabs as $tab) { ?>
                <div class="tabbed-category-slider-wrapper <?php echo $i==0 ? 'active' : ''; ?>" data-tab-id="<?php echo $i; ?>" style="<?php echo $i==0 ? '' : 'display: none;'; ?>">
                    <div class="popular-category-slider owl-carousel">
          <?php if (!empty($tab['products']) && is_array($tab['products'])) { ?>
              <?php foreach ($tab['products'] as $product) { ?>
                            <div class="slider-item">
                                <div class="product-card">
                                    <div class="product-thumb">
                <?php if ($product['discount']) { ?>
                                        <div class="product-badge product-badge2 bg-info">-<?php echo $product['discount']; ?>%</div>
                <?php } ?>
                                        <img class="lazy" alt="<?php echo htmlspecialchars($product['name']); ?>" src="<?php echo $product['thumb']; ?>">
                                        <div class="product-button-group">
                                            <a class="product-button wishlist_store" href="javascript:;" title="Wishlist" onclick="wishlist.add('<?php echo $product['product_id']; ?>'); return false;"><i class="icon-heart"></i></a>
                                            <a class="product-button product_compare" href="javascript:;" title="Compare" onclick="compare.add('<?php echo $product['product_id']; ?>'); return false;"><i class="icon-repeat"></i></a>
                                            <a class="product-button add_to_single_cart" data-target="<?php echo $product['product_id']; ?>" href="javascript:;" title="To Cart" onclick="cart.add('<?php echo $product['product_id']; ?>'); return false;"><i class="icon-shopping-cart"></i></a>
                                        </div>
              </div>
                                    <div class="product-card-body">
                                        <?php if ($product['category_name']) { ?>
                                        <div class="product-category"><a href="javascript:;"><?php echo htmlspecialchars($product['category_name']); ?></a></div>
                <?php } ?>
                                        <h3 class="product-title"><a href="<?php echo $product['href']; ?>"><?php echo htmlspecialchars($product['name']); ?></a></h3>
                                        <div class="rating-stars">
                  <?php for ($s = 1; $s <= 5; $s++) { ?>
                                            <i class="fas fa-star<?php echo ($s <= $product['rating']) ? ' filled' : ''; ?>"></i>
                  <?php } ?>
                </div>
                                        <h4 class="product-price">
                      <?php if ($product['special']) { ?>
                                            <del><?php echo $product['price']; ?></del>
                                            <?php echo $product['special']; ?>
                      <?php } else { ?>
                                            <?php echo $product['price']; ?>
                      <?php } ?>
                                        </h4>
                    </div>
                  </div>
                </div>
            <?php } ?>
          <?php } else { ?>
                            <div class="text-center p-4">No products found in this category.</div>
              <?php } ?>
            </div>
          </div>
        <?php $i++; } ?>
            </div>
      </div>
  </div>
</div>

<style>
/* Deal of Day Section */
.deal-of-day-section {
    padding: 40px 0;
    background-color: #fff;
}

.deal-of-day-section .container {
    max-width: 100%;
    margin: 0 auto;
    padding: 0 20px;
    width: 100%;
    box-sizing: border-box;
}

/* Mobile: Full width */
@media (max-width: 767px) {
    .deal-of-day-section .container {
    max-width: 100% !important;
        padding: 0 15px !important;
    }
}

.mt-20 {
    margin-top: 20px !important;
}

/* Section Title */
.section-title {
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    padding-bottom: 15px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.section-title h2 {
    padding-bottom: 0;
    margin-bottom: 0;
    font-weight: 600;
    font-size: 28px;
    position: relative;
    color: #1a1a1a;
    letter-spacing: -0.02em;
    font-family: 'Inter', sans-serif;
}

.section-title h2::before {
    display: none;
}

.section-title .right-area {
    display: flex;
    align-items: center;
    gap: 20px;
}

.section-title .countdown {
    display: inline-flex;
    margin-top: 0;
    margin-left: 20px;
    background: #fff0f5;
    padding: 6px 15px;
    border-radius: 50px;
    border: 1px solid #ff6b9d;
}

.section-title .countdown span {
    display: inline-block;
    text-align: center;
    color: #ff6b9d;
    min-width: auto;
    padding: 0;
    border-radius: 0;
    margin-right: 10px;
    font-size: 14px;
    font-weight: 700;
}

.section-title .countdown span small {
    display: inline-block;
    background: 0 0;
    color: #666;
    margin-top: 0;
    padding: 0;
    margin-left: 3px;
    font-weight: 400;
    text-transform: uppercase;
    font-size: 10px;
}

.section-title .right_link {
    color: #666;
    margin-left: 20px;
    transition: 0.3s linear;
    font-weight: 500;
    text-decoration: none;
    font-size: 14px;
}

.section-title .right_link:hover {
    color: #ff6b9d;
}

.section-title .links {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
}

/* Premium Product Card Styles (Consistent with Featured/Latest) */
.product-card {
    display: block;
    position: relative;
    width: 100%;
    border-radius: 12px;
    background-color: #fff;
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    box-shadow: none;
}

.product-card:hover {
    border-color: rgba(0,0,0,0.05);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    transform: translateY(-5px);
}

.product-card .product-thumb {
    display: block;
    width: 100%;
    overflow: hidden;
    position: relative;
    padding-top: 100%; /* 1:1 Aspect Ratio */
}

.product-card .product-thumb > img {
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.product-card:hover .product-thumb > img {
    transform: scale(1.05);
}

.product-card .product-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    border-radius: 4px;
    padding: 4px 8px;
    height: auto;
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    line-height: 1.2;
    z-index: 9;
    background: #ff6b9d !important;
}

.product-card .product-button-group {
    position: absolute;
    left: 0;
    bottom: -50px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    opacity: 0;
    visibility: hidden;
    z-index: 15;
    transition: bottom 0.3s ease, opacity 0.3s ease;
    pointer-events: none;
    background: rgba(255,255,255,0.9);
    padding: 10px;
    backdrop-filter: blur(5px);
}

.product-card:hover .product-button-group {
    bottom: 0;
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}

.product-card .product-button-group .product-button {
    height: 35px;
    width: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #333;
    padding: 0;
    text-align: center;
    text-decoration: none;
    border-radius: 50%;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin: 0;
    background: #fff !important;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    z-index: 16;
}

.product-card .product-button-group .product-button:hover {
    background: #ff6b9d !important;
    color: #fff !important;
    transform: scale(1.1);
}

.product-card .product-button-group .product-button i {
    font-size: 14px;
    color: inherit;
    line-height: 1;
    display: block;
}

.product-card .product-card-body {
    padding: 15px;
    text-align: center;
    display: flex;
    flex-direction: column;
}

.product-card .product-category {
    width: 100%;
    margin-bottom: 6px;
    font-size: 12px;
}

.product-card .product-category > a {
    transition: color 0.2s;
    color: #999;
    text-decoration: none;
}

.product-card .product-category > a:hover {
    color: #ff6b9d;
}

.product-card .product-title {
    margin-bottom: 8px;
    font-size: 15px;
    font-weight: 500;
}

.product-card .product-title > a {
    transition: color 0.2s;
    color: #333;
    text-decoration: none;
    display: block;
    line-height: 1.4;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    height: 42px; /* Fixed height for 2 lines */
}

.product-card .product-title > a:hover {
    color: #ff6b9d;
}

.product-card .rating-stars {
    display: block;
    margin-bottom: 8px;
}

.product-card .rating-stars > i {
    display: inline-block;
    margin-right: 2px;
    color: #e0e0e0;
    font-size: 12px;
}

.product-card .rating-stars > i.filled {
    color: #ffc107;
}

.product-card .product-price {
    display: inline-block;
    margin-bottom: 0;
    font-size: 16px;
    font-weight: 600;
    text-align: center;
    color: #1a1a1a;
    margin-top: auto;
}

.product-card .product-price > del {
    margin-right: 5px;
    color: #999;
    font-weight: 400;
    font-size: 14px;
    text-decoration: line-through;
}

/* Slider Item */
.slider-item {
    padding: 10px 0;
}

/* Owl Carousel Navigation */
.popular-category-slider.owl-carousel .owl-nav div {
    width: 36px;
    height: 36px;
    line-height: 36px;
    border: 0;
    border-radius: 50%;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    background: #fff !important;
    color: #333 !important;
    opacity: 1 !important;
    top: 50%;
    transform: translateY(-50%);
    transition: all 0.3s ease;
}

.popular-category-slider.owl-carousel .owl-prev {
    left: -18px;
    right: auto;
}

.popular-category-slider.owl-carousel .owl-next {
    right: -18px;
    left: auto;
}

.popular-category-slider.owl-carousel .owl-nav div:hover {
    background: #ff6b9d !important;
    color: #fff !important;
}

/* Tab Navigation */
.lux-premium-tabs {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 5px;
}

.lux-tab-item {
    display: inline-flex;
    align-items: center;
    padding: 8px 20px;
    border-radius: 30px;
    background: #f5f5f5;
    color: #666;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.lux-tab-item:hover {
    background: #fff;
    color: #ff6b9d;
    border-color: #ff6b9d;
    transform: translateY(-2px);
}

.lux-tab-item.active {
    background: #ff6b9d;
    color: #fff;
    box-shadow: 0 4px 10px rgba(255, 107, 157, 0.3);
    border-color: #ff6b9d;
}

/* Responsive Design */
@media (max-width: 992px) {
    .section-title {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .section-title .right-area {
        margin-top: 15px;
        width: 100%;
        flex-wrap: wrap;
    }
    
    .section-title h2 {
        font-size: 24px;
    }
    
    .section-title .countdown {
        margin-left: 0;
    }
}

@media (max-width: 767px) {
    .deal-of-day-section {
        padding: 20px 0;
    }
    
    .section-title h2 {
        font-size: 22px;
    }
    
    .section-title .right-area {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .lux-premium-tabs {
        width: 100%;
        overflow-x: auto;
        padding-bottom: 10px;
        flex-wrap: nowrap;
    }
    
    .lux-tab-item {
        white-space: nowrap;
    }
}
</style>

<script>
(function() {
    var root = document.getElementById('<?php echo $module_uid; ?>');
    if (!root || root.dataset.tcp2Initialized) {
        return;
    }
    root.dataset.tcp2Initialized = 'true';

    var sliders = root.querySelectorAll('.popular-category-slider');
    var owlCarousels = [];

    // Initialize owl carousel for each tab
    sliders.forEach(function(slider) {
        if (typeof jQuery !== 'undefined' && jQuery.fn.owlCarousel) {
            var owl = jQuery(slider).owlCarousel({
                loop: true,
                margin: 15,
                nav: false,
                dots: false,
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 2,
                        margin: 8,
                        slideBy: 2
                    },
                    576: {
                        items: 2,
                        margin: 10,
                        slideBy: 2
                    },
                    768: {
                        items: 4,
                        margin: 12,
                        slideBy: 2
                    },
                    992: {
                        items: 4,
                        margin: 15
                    },
                    1200: {
                        items: 5,
                        margin: 15
                    }
                }
            });
            owlCarousels.push(owl);
        }
    });

    // Tab switching functionality
    var tabWrappers = root.querySelectorAll('.tabbed-category-slider-wrapper');
    var currentTab = 0;

    // Tab click handlers if multiple tabs exist
    if (tabWrappers.length > 1) {
        var tabItems = root.querySelectorAll('.tabbed-category-tab');
        tabItems.forEach(function(tab, index) {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                var tabId = parseInt(this.getAttribute('data-tab-id'));
                
                // Update active tab
                tabItems.forEach(function(t) { t.classList.remove('active'); });
                this.classList.add('active');
                
                // Show/hide sliders
                tabWrappers.forEach(function(wrapper, idx) {
                    if (idx === tabId) {
                        wrapper.style.display = 'block';
                        wrapper.classList.add('active');
                        // Refresh owl carousel
                        var slider = wrapper.querySelector('.popular-category-slider');
                        if (slider && typeof jQuery !== 'undefined' && jQuery(slider).data('owl.carousel')) {
                            jQuery(slider).trigger('refresh.owl.carousel');
                        }
                    } else {
                        wrapper.style.display = 'none';
                        wrapper.classList.remove('active');
                    }
                });
                
                currentTab = tabId;
            });
        });
    }
})();
</script>
