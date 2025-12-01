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
        <!-- Modern Header with Timer - New Classes (tcp- prefix) -->
        <div class="tcp-modern-header" id="tcp-header-<?php echo $module_uid; ?>">
            <div class="tcp-header-left">
                <div class="tcp-title-tab">
                    <strong><?php echo !empty($name) ? htmlspecialchars($name) : 'Deals Of The Week'; ?></strong>
                </div>
            </div>
            <div class="tcp-header-center">
                <?php if (count($tabs) > 1) { ?>
                <div class="tcp-tabs-nav">
                    <?php $i=0; foreach ($tabs as $tab) { ?>
                    <button type="button" class="tcp-tab-btn <?php echo $i==0 ? 'tcp-tab-active' : ''; ?>" 
                            data-tab-id="<?php echo $i; ?>">
                        <?php echo htmlspecialchars($tab['title']); ?>
                    </button>
                    <?php $i++; } ?>
                </div>
                <?php } ?>
            </div>
            <div class="tcp-header-right">
                <div class="tcp-countdown-wrapper">
                    <div class="tcp-countdown" id="tcp-countdown-<?php echo $module_uid; ?>" data-end-date="<?php echo !empty($date_end) ? htmlspecialchars($date_end) : date('Y-m-d H:i', strtotime('+7 days')); ?>">
                        <span class="tcp-countdown-item">
                            <span class="tcp-countdown-value">00</span><span class="tcp-countdown-label">D</span>
                        </span>
                        <span class="tcp-countdown-item">
                            <span class="tcp-countdown-value">00</span><span class="tcp-countdown-label">H</span>
                        </span>
                        <span class="tcp-countdown-item">
                            <span class="tcp-countdown-value">00</span><span class="tcp-countdown-label">M</span>
                        </span>
                        <span class="tcp-countdown-item">
                            <span class="tcp-countdown-value">00</span><span class="tcp-countdown-label">S</span>
                        </span>
                    </div>
                </div>
                <div class="tcp-nav-arrows">
                    <button type="button" class="tcp-nav-btn tcp-nav-prev" aria-label="Previous">
                        <i class="fa fa-chevron-left"></i>
                    </button>
                    <button type="button" class="tcp-nav-btn tcp-nav-next" aria-label="Next">
                        <i class="fa fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-lg-12">
        <?php $i=0; foreach ($tabs as $tab) { ?>
                <div class="tabbed-category-slider-wrapper <?php echo $i==0 ? 'active' : ''; ?>" data-tab-id="<?php echo $i; ?>" style="<?php echo $i==0 ? '' : 'display: none;'; ?>">
                    <div class="popular-category-slider owl-carousel" id="tcp-carousel-<?php echo $module_uid; ?>-<?php echo $i; ?>">
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
                                            <a class="product-button wishlist_store" href="javascript:;" title="Wishlist" onclick="wishlist.add('<?php echo $product['product_id']; ?>'); return false;"><i class="fa fa-heart"></i></a>
                                            <a class="product-button product_compare" href="javascript:;" title="Compare" onclick="compare.add('<?php echo $product['product_id']; ?>'); return false;"><i class="fa fa-exchange"></i></a>
                                            <a class="product-button add_to_single_cart" data-target="<?php echo $product['product_id']; ?>" href="javascript:;" title="Add to Cart" onclick="cart.add('<?php echo $product['product_id']; ?>'); return false;"><i class="fa fa-shopping-cart"></i></a>
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
                                        <?php if (!empty($product['points']) && $product['points'] > 0) { ?>
                                        <div class="module-reward-points">
                                          <i class="fa fa-gift"></i>
                                          <span>Earn <?php echo $product['points']; ?> points</span>
                                        </div>
                                        <?php } ?>
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

/* Modern Header - New Classes (tcp- prefix) */
.tcp-modern-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 30px;
    padding: 0;
    background: #fff;
    border-bottom: 2px solid #ff505a;
    position: relative;
    gap: 20px;
    flex-wrap: wrap;
}

.tcp-header-left {
    flex-shrink: 0;
    padding-left: 0;
}

.tcp-title-tab {
    position: relative;
    display: inline-block;
}

.tcp-title-tab strong {
    display: inline-block;
    padding: 12px 24px;
    background-color: #ff505a;
    color: #fff !important;
    font-weight: 700;
    text-transform: uppercase;
    position: relative;
    font-size: 16px;
    line-height: 1.4;
    margin: 0;
}

/* Triangular flag shape at top-left */
.tcp-title-tab strong:before {
    content: "";
    position: absolute;
    left: 0;
    top: -10px;
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-bottom: 10px solid #ff1d2a;
    z-index: 1;
}

.tcp-header-center {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.tcp-header-center::-webkit-scrollbar {
    display: none;
}

.tcp-tabs-nav {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: nowrap;
    overflow-x: auto;
    padding: 0 10px;
}

.tcp-tab-btn {
    padding: 8px 16px;
    border: none;
    background: transparent;
    color: #333;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
    text-transform: uppercase;
    position: relative;
}

.tcp-tab-btn:hover {
    color: #ff505a;
}

.tcp-tab-btn.tcp-tab-active {
    color: #ff505a;
    font-weight: 700;
    border-bottom: 2px solid #ff505a;
}

.tcp-header-right {
    flex-shrink: 0;
    display: flex;
    align-items: center;
    gap: 15px;
    padding-right: 20px;
}

/* Smart Timer Design - Small & Compact */
.tcp-countdown-wrapper {
    display: flex;
    align-items: center;
}

.tcp-countdown {
    display: flex;
    gap: 4px;
    align-items: center;
    background: #fff0f5;
    padding: 4px 8px;
    border-radius: 4px;
    border: 1px solid #ffb3c1;
}

.tcp-countdown-item {
    background: transparent;
    border-radius: 0;
    padding: 0;
    min-width: auto;
    text-align: center;
    box-shadow: none;
    display: inline-flex;
    flex-direction: row;
    align-items: baseline;
    justify-content: center;
    gap: 1px;
}

.tcp-countdown-item:not(:last-child)::after {
    content: ":";
    color: #ff505a;
    font-weight: 700;
    margin: 0 3px;
    font-size: 11px;
    line-height: 1;
}

.tcp-countdown-value {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    color: #ff505a;
    line-height: 1.2;
    min-width: 16px;
    text-align: center;
}

.tcp-countdown-label {
    display: inline-block;
    font-size: 7px;
    color: #999;
    text-transform: uppercase;
    margin-left: 1px;
    letter-spacing: 0.2px;
    font-weight: 500;
    line-height: 1;
}

.tcp-nav-arrows {
    display: flex;
    gap: 8px;
}

.tcp-nav-btn {
    width: 36px;
    height: 36px;
    border: 1px solid #e0e0e0;
    background: #fff;
    color: #333;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border-radius: 4px;
    font-size: 14px;
}

.tcp-nav-btn:hover {
    background: #ff505a;
    color: #fff;
    border-color: #ff505a;
}

.tcp-nav-btn:active {
    transform: scale(0.95);
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
    bottom: 0;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    opacity: 1;
    visibility: visible;
    z-index: 15;
    transition: all 0.3s ease;
    pointer-events: auto;
    background: rgba(255,255,255,0.98);
    padding: 12px 8px;
    backdrop-filter: blur(8px);
    border-top: 1px solid rgba(0,0,0,0.05);
}

.product-card:hover .product-button-group {
    opacity: 1;
    visibility: visible;
    background: rgba(255,255,255,1);
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
}

.product-card .product-button-group .product-button {
    height: 38px;
    width: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #333;
    padding: 0;
    text-align: center;
    text-decoration: none;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
    margin: 0;
    background: #fff !important;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    z-index: 16;
    border: 1px solid rgba(0,0,0,0.05);
}

.product-card .product-button-group .product-button:hover {
    background: #ff505a !important;
    color: #fff !important;
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 4px 12px rgba(255, 80, 90, 0.3);
    border-color: #ff505a;
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
    .tcp-modern-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
        padding: 15px;
    }
    
    .tcp-header-left,
    .tcp-header-center,
    .tcp-header-right {
        width: 100%;
        padding-left: 0;
        padding-right: 0;
    }
    
    .tcp-header-center {
        justify-content: flex-start;
    }
    
    .tcp-header-right {
        justify-content: space-between;
    }
}

@media (max-width: 767px) {
    .deal-of-day-section {
        padding: 20px 0;
    }
    
    .tcp-title-tab strong {
        font-size: 14px;
        padding: 10px 18px;
    }
    
    .tcp-title-tab strong:before {
        top: -8px;
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-bottom: 8px solid #ff1d2a;
    }
    
    .tcp-countdown {
        padding: 3px 6px;
        gap: 2px;
    }
    
    .tcp-countdown-item:not(:last-child)::after {
        margin: 0 2px;
        font-size: 10px;
    }
    
    .tcp-countdown-value {
        font-size: 10px;
        min-width: 14px;
    }
    
    .tcp-countdown-label {
        font-size: 6px;
    }
    
    .tcp-nav-btn {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }
    
    .product-card .product-button-group {
        padding: 10px 6px;
        gap: 6px;
    }
    
    .product-card .product-button-group .product-button {
        height: 34px;
        width: 34px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    var root = $('#<?php echo $module_uid; ?>');
    if (!root.length || root.data('tcp-initialized')) {
        return;
    }
    root.data('tcp-initialized', true);

    var moduleUid = '<?php echo $module_uid; ?>';
    var currentActiveCarousel = null;
    var owlCarousels = {};

    // Initialize owl carousel for each tab with AUTOMATIC SLIDING
    $('.tabbed-category-slider-wrapper', root).each(function(index) {
        var $wrapper = $(this);
        var carouselId = 'tcp-carousel-' + moduleUid + '-' + index;
        var $slider = $('#' + carouselId, $wrapper);
        
        if ($slider.length && typeof $.fn.owlCarousel !== 'undefined') {
            var owl = $slider.owlCarousel({
                loop: true,
                margin: 15,
                nav: false,
                dots: false,
                autoplay: true, // ENABLE AUTOMATIC SLIDING
                autoplayTimeout: 4000, // 4 seconds
                autoplayHoverPause: true,
                autoplaySpeed: 800,
                smartSpeed: 600,
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
            
            owlCarousels[index] = owl;
            
            // Set first tab's carousel as active
            if (index === 0) {
                currentActiveCarousel = owl;
            }
        }
    });

    // Custom navigation buttons
    $('.tcp-nav-prev', root).on('click', function() {
        if (currentActiveCarousel) {
            currentActiveCarousel.trigger('prev.owl.carousel');
        }
    });
    
    $('.tcp-nav-next', root).on('click', function() {
        if (currentActiveCarousel) {
            currentActiveCarousel.trigger('next.owl.carousel');
        }
    });

    // Tab switching functionality
    $('.tcp-tab-btn', root).on('click', function() {
        var tabId = parseInt($(this).data('tab-id'));
        
        // Update active tab button
        $('.tcp-tab-btn', root).removeClass('tcp-tab-active');
        $(this).addClass('tcp-tab-active');
        
        // Show/hide sliders
        $('.tabbed-category-slider-wrapper', root).each(function(index) {
            if (index === tabId) {
                $(this).css('display', 'block').addClass('active');
                // Update active carousel
                if (owlCarousels[index]) {
                    currentActiveCarousel = owlCarousels[index];
                    // Refresh carousel
                    currentActiveCarousel.trigger('refresh.owl.carousel');
                }
            } else {
                $(this).css('display', 'none').removeClass('active');
            }
        });
    });

    // Timer functionality - Fixed to use date_end from admin panel
    var $countdown = $('#tcp-countdown-' + moduleUid);
    if ($countdown.length) {
        var endDateStr = $countdown.data('end-date');
        if (endDateStr) {
            // Parse the date from admin panel (format: YYYY-MM-DD HH:mm or YYYY-MM-DD)
            // Replace spaces with 'T' for ISO format, or use standard parsing
            var dateStr = endDateStr.replace(' ', 'T');
            if (dateStr.indexOf('T') === -1) {
                dateStr = dateStr + 'T23:59:59'; // Default to end of day if no time
            }
            var endDate = new Date(dateStr);
            
            // Fallback: try alternative parsing
            if (isNaN(endDate.getTime())) {
                endDate = new Date(endDateStr.replace(/-/g, '/'));
            }
            
            if (!isNaN(endDate.getTime())) {
                var $items = $countdown.find('.tcp-countdown-item');
                
                function updateCountdown() {
                    var now = new Date().getTime();
                    var distance = endDate.getTime() - now;
                    
                    if (distance < 0) {
                        // Timer expired
                        $items.eq(0).find('.tcp-countdown-value').text('00');
                        $items.eq(1).find('.tcp-countdown-value').text('00');
                        $items.eq(2).find('.tcp-countdown-value').text('00');
                        $items.eq(3).find('.tcp-countdown-value').text('00');
                        return;
                    }
                    
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    $items.eq(0).find('.tcp-countdown-value').text(String(days).padStart(2, '0'));
                    $items.eq(1).find('.tcp-countdown-value').text(String(hours).padStart(2, '0'));
                    $items.eq(2).find('.tcp-countdown-value').text(String(minutes).padStart(2, '0'));
                    $items.eq(3).find('.tcp-countdown-value').text(String(seconds).padStart(2, '0'));
                }
                
                // Update immediately and then every second
                updateCountdown();
                setInterval(updateCountdown, 1000);
            } else {
                console.log('Tabbed Category: Invalid date format:', endDateStr);
            }
        }
    }
});
</script>
