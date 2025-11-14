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
                        <div class="links">
                            <?php $i=0; foreach ($tabs as $tab) { ?>
                            <a class="category_get tabbed-category-tab <?php echo $i==0 ? 'active' : ''; ?>" 
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
                                            <a class="product-button wishlist_store" href="javascript:;" title="Wishlist"><i class="icon-heart"></i></a>
                                            <a data-target="javascript:;" class="product-button product_compare" href="javascript:;" title="Compare"><i class="icon-repeat"></i></a>
                                            <a class="product-button add_to_single_cart" data-target="<?php echo $product['product_id']; ?>" href="javascript:;" title="To Cart"><i class="icon-shopping-cart"></i></a>
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
    padding: 30px 0;
    background-color: #f3f5f6;
}

.deal-of-day-section .container {
    max-width: 80%;
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
    border-bottom: 2px solid rgba(0, 0, 0, 0.06);
    padding-bottom: 0;
    margin-bottom: 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.section-title h2 {
    padding-bottom: 12px;
    margin-bottom: 0;
    font-weight: 600;
    font-size: 24px;
    position: relative;
}

.section-title h2::before {
    position: absolute;
    content: "";
    height: 2px;
    width: 100%;
    bottom: -2px;
    left: 0;
    background: #377dff;
}

.section-title .right-area {
    display: flex;
    align-items: center;
    gap: 20px;
}

.section-title .countdown {
    display: inline-block;
    margin-top: 0;
    margin-left: 20px;
    background: red;
    padding: 3px 20px 6px;
    border-radius: 50px;
}

.section-title .countdown span {
    display: inline-block;
    text-align: center;
    color: #fff;
    min-width: auto;
    padding: 0;
    border-radius: 0;
    margin-right: 7px;
    font-size: 14px;
}

.section-title .countdown span small {
    display: inline-block;
    background: 0 0;
    color: #fff;
    margin-top: 0;
    padding: 0;
    margin-left: 3px;
}

.section-title .right_link {
    color: #555;
    margin-left: 20px;
    transition: 0.3s linear;
    font-weight: 500;
    text-decoration: none;
}

.section-title .right_link:hover {
    color: #FF6A00;
}

.section-title .right_link i {
    position: relative;
    top: 2px;
    margin-left: 5px;
}

.section-title > div:first-child {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.section-title .links {
    display: flex;
    flex-wrap: wrap;
    gap: 0;
    margin-top: 10px;
}

.section-title .links a {
    color: #444;
    margin-left: 20px;
    position: relative;
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    padding-bottom: 12px;
}

.section-title .links a::before {
    position: absolute;
    content: "";
    bottom: -18px;
    left: 0;
    width: 100%;
    height: 2px;
    background: #FF6A00;
    opacity: 0;
    transition: 0.3s linear;
}

.section-title .links a:hover,
.section-title .links a.active {
    color: #FF6A00;
}

.section-title .links a:hover::before,
.section-title .links a.active::before {
    opacity: 1;
}

/* Product Card Styles */
.product-card {
    display: block;
    position: relative;
    width: 100%;
    border-radius: 10px;
    background-color: #fff;
    overflow: hidden;
    border: 1px solid #fff;
    transition: 0.3s linear;
}

.product-card:hover {
    border-color: #FF6A00;
}

.product-card .product-thumb {
    display: block;
    width: 100%;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    overflow: hidden;
    position: relative;
}

.product-card .product-thumb > img {
    display: block;
    width: 100%;
    padding-top: 0;
    transform: scale(1);
    transition: 0.3s linear;
}

.product-card:hover .product-thumb > img {
    transform: scale(1.1);
}

.product-card .product-badge {
    position: absolute;
    top: 15px;
    left: 0;
    border-radius: 0 9px 30px 0;
    padding: 0 12px 0 10px;
    height: 24px;
    color: #fff;
    font-size: 12px;
    font-weight: 400;
    line-height: 24px;
    z-index: 9;
}

.product-card .product-badge.product-badge2 {
    left: auto;
    right: 0;
    border-radius: 9px 0 0 30px;
    padding: 0 10px 0 12px;
    background: #daa520 !important;
}

.product-card .product-badge.bg-info {
    background: #0dcaf0 !important;
}

.product-card .product-button-group {
    position: absolute;
    left: 0;
    bottom: -15px;
    width: 100%;
    text-align: center;
    opacity: 0;
    visibility: hidden;
    z-index: 2;
    transition: 0.3s linear;
}

.product-card:hover .product-button-group {
    bottom: 10px;
    opacity: 1;
    visibility: visible;
}

.product-card .product-button-group .product-button {
    height: 35px;
    width: 35px;
    line-height: 36px;
    color: #fff;
    padding: 0;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    border-radius: 50%;
    box-shadow: 2px 2px 5px 0 rgba(0, 0, 0, 0.1);
    margin: 0 4px;
    background: #FF6A00 !important;
}

.product-card .product-button-group .product-button:hover {
    background: #ff8c00 !important;
}

.product-card .product-card-body {
    padding: 15px 15px 10px;
}

.product-card .product-category {
    width: 100%;
    margin-bottom: 6px;
    font-size: 13px;
}

.product-card .product-category > a {
    transition: color 0.2s;
    color: #999;
    text-decoration: none;
}

.product-card .product-category > a:hover {
    color: #FF6A00;
}

.product-card .product-title {
    margin-bottom: 5px;
    font-size: 16px;
    font-weight: 400;
}

.product-card .product-title > a {
    transition: color 0.3s;
    color: #232323;
    text-decoration: none;
    font-size: 14px;
    height: 37px;
    display: block;
    font-weight: 500;
    line-height: 18px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.product-card .product-title > a:hover {
    color: #FF6A00;
}

.product-card .rating-stars {
    display: block;
    margin-bottom: 5px;
}

.product-card .rating-stars > i {
    display: inline-block;
    margin-right: 2px;
    color: #c7c7c7;
    font-size: 12px;
}

.product-card .rating-stars > i.filled {
    color: #ffa500;
}

.product-card .product-price {
    display: inline-block;
    margin-bottom: 10px;
    font-size: 15px;
    font-weight: 600;
    text-align: center;
    color: #FF6A00;
}

.product-card .product-price > del {
    margin-right: 5px;
    color: #999;
    font-weight: 400;
    font-size: 14px;
}

/* Slider Item */
.slider-item {
    padding: 10px 6px;
}

/* Owl Carousel Navigation */
.popular-category-slider.owl-carousel .owl-nav div {
    width: 26px;
    height: 26px;
    line-height: 26px;
    border: 0;
    border-radius: 50px;
    box-shadow: 1px 1px 4px 0 rgba(0, 0, 0, 0.13);
    background: #FF6A00 !important;
    color: #fff !important;
    opacity: 1 !important;
    top: 50%;
    transform: translateY(-50%);
    transition: 0.3s linear;
}

.popular-category-slider.owl-carousel .owl-prev {
    right: 33px;
    left: auto;
}

.popular-category-slider.owl-carousel .owl-next {
    right: -10px;
}

.popular-category-slider.owl-carousel .owl-nav div:hover {
    background: #ff8c00 !important;
}

.popular-category-slider.owl-carousel .owl-nav div.disabled {
    background: #f5f6f9 !important;
    opacity: 0.5;
}

/* Tab Navigation */
.tabbed-category-tabs {
    display: flex;
    gap: 0;
    list-style: none;
    padding: 0;
    margin: 0 0 25px 0;
    border-bottom: 2px solid rgba(0, 0, 0, 0.06);
}

.tabbed-category-tabs li {
    padding: 12px 0;
    margin-left: 20px;
    cursor: pointer;
    color: #444;
    font-size: 15px;
    font-weight: 600;
    position: relative;
}

.tabbed-category-tabs li::before {
    position: absolute;
    content: "";
    bottom: -18px;
    left: 0;
    width: 100%;
    height: 2px;
    background: #FF6A00;
    opacity: 0;
    transition: 0.3s linear;
}

.tabbed-category-tabs li:hover,
.tabbed-category-tabs li.active {
    color: #FF6A00;
}

.tabbed-category-tabs li:hover::before,
.tabbed-category-tabs li.active::before {
    opacity: 1;
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
        font-size: 20px;
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
        font-size: 18px;
    }
    
    .section-title .right-area {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .section-title .countdown {
        font-size: 12px;
        padding: 2px 15px 4px;
    }
    
    .section-title .countdown span {
        font-size: 12px;
        margin-right: 5px;
    }
    
    .section-title .right_link {
        font-size: 14px;
    }
    
    .product-card .product-title > a {
        font-size: 13px;
        height: 34px;
    }
    
    .product-card .product-price {
        font-size: 14px;
    }
}

@media (max-width: 576px) {
    .deal-of-day-section {
        padding: 15px 0;
    }
    
    .section-title {
        margin-bottom: 20px;
    }
    
    .section-title h2 {
        font-size: 16px;
    }
    
    .section-title .countdown {
        font-size: 11px;
        padding: 2px 12px 3px;
    }
    
    .section-title .countdown span {
        font-size: 11px;
        margin-right: 4px;
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
                nav: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    576: {
                        items: 2
                    },
                    768: {
                        items: 3
                    },
                    992: {
                        items: 4
                    },
                    1200: {
                        items: 5
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
