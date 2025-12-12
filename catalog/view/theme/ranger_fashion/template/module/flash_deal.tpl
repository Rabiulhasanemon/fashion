<?php if ($products) { ?>
<div id="fd-module-wrapper" class="fd-module-container">
    <div class="container">
        <!-- Simple Module Header -->
        <div class="fd-module-header-wrapper" id="fd-header-<?php echo isset($module_id) ? $module_id : time(); ?>">
            <div class="fd-header-content">
                <h2 class="fd-module-heading">Flash Deal</h2>
            </div>
        </div>
        
        <!-- Flash Deal Products Carousel -->
        <div class="fd-products-wrapper">
            <div id="fd-products-carousel" class="fd-carousel owl-carousel">
                <?php foreach ($products as $product) { ?>
                <div class="fd-product-item">
                    <div class="fd-product-card">
                        <!-- Product Image Section -->
                        <div class="fd-image-container">
                            <a href="<?php echo $product['href']; ?>" class="fd-image-link">
                                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="fd-product-image">
                            </a>
                            
                            <!-- Discount Badge - Top Right Corner -->
                            <?php if ($product['discount']) { ?>
                            <div class="fd-discount-label">-<?php echo (int)$product['discount']; ?>%</div>
                            <?php } ?>
                            
                        </div>
                        
                        <!-- Product Info Section -->
                        <div class="fd-info-container">
                            <!-- Category -->
                            <?php if ($product['category_name']) { ?>
                            <div class="fd-category-text"><?php echo htmlspecialchars($product['category_name']); ?></div>
                            <?php } ?>
                            
                            <!-- Product Name -->
                            <h3 class="fd-product-title">
                                <a href="<?php echo $product['href']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                            </h3>
                            
                            <!-- Star Rating -->
                            <div class="fd-rating-stars">
                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                <i class="fa fa-star <?php echo ($i <= $product['rating']) ? 'fd-star-active' : 'fd-star-inactive'; ?>"></i>
                                <?php } ?>
                            </div>
                            
                            <!-- Price Section -->
                            <div class="fd-price-section">
                                <?php if ($product['special']) { ?>
                                <span class="fd-price-original"><?php echo $product['price']; ?></span>
                                <span class="fd-price-current"><?php echo $product['special']; ?></span>
                                <?php } else { ?>
                                <span class="fd-price-current"><?php echo $product['price']; ?></span>
                                <?php } ?>
                            </div>
                            
                            <!-- Countdown Timer - Orange Square Boxes -->
                            <?php if (!empty($product['end_date'])) { ?>
                            <div class="fd-countdown-wrapper" data-end-date="<?php echo htmlspecialchars($product['end_date']); ?>">
                                <div class="fd-timer-unit">
                                    <div class="fd-timer-box">
                                        <span class="fd-timer-value">00</span>
                                    </div>
                                    <span class="fd-timer-label">Days</span>
                                </div>
                                <div class="fd-timer-unit">
                                    <div class="fd-timer-box">
                                        <span class="fd-timer-value">00</span>
                                    </div>
                                    <span class="fd-timer-label">Hrs</span>
                                </div>
                                <div class="fd-timer-unit">
                                    <div class="fd-timer-box">
                                        <span class="fd-timer-value">00</span>
                                    </div>
                                    <span class="fd-timer-label">Min</span>
                                </div>
                                <div class="fd-timer-unit">
                                    <div class="fd-timer-box">
                                        <span class="fd-timer-value">00</span>
                                    </div>
                                    <span class="fd-timer-label">Sec</span>
                                </div>
                            </div>
                            <?php } ?>
                            
                            <!-- Action Buttons - Bottom Left of Info Area -->
                            <div class="fd-action-buttons">
                                <button type="button" class="fd-action-btn" onclick="compare.add('<?php echo $product['product_id']; ?>');" title="Compare">
                                    <i class="fa fa-exchange"></i>
                                </button>
                                <button type="button" class="fd-action-btn fd-cart-btn" onclick="cart.add('<?php echo $product['product_id']; ?>');" title="Add to Cart">
                                    <i class="fa fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<style>
/* =================================================
   FLASH DEAL MODULE - NEW PREMIUM DESIGN
   FD Prefix - All New Classes to Avoid Conflicts
   ================================================= */

#fd-module-wrapper.fd-module-container {
    padding: 30px 0;
    background: #ffffff;
    position: relative;
}

/* Simple Module Header */
.fd-module-header-wrapper {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    margin-bottom: 20px;
    padding: 0;
    background: transparent;
    border: none;
    position: relative;
}

.fd-header-content {
    flex: 1;
}

.fd-module-heading {
    font-size: 20px;
    font-weight: 600;
    color: #000000;
    margin: 0;
    padding: 0;
    text-transform: none;
    letter-spacing: 0;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
    line-height: 1.4;
}

/* Products Wrapper */
.fd-products-wrapper {
    position: relative;
}

.fd-carousel {
    position: relative;
}

/* Product Card */
.fd-product-card {
    background: #ffffff;
    border-radius: 0;
    overflow: hidden;
    box-shadow: none;
    transition: all 0.3s ease;
    margin: 5px;
    border: 1px solid #FFCC80;
}

.fd-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
}

/* Image Container */
.fd-image-container {
    position: relative;
    padding-top: 100%;
    overflow: hidden;
    background: #f8f9fa;
}

.fd-image-link {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
}

.fd-product-image {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
    object-fit: contain;
    transition: transform 0.4s ease;
}

.fd-product-card:hover .fd-product-image {
    transform: scale(1.08);
}

/* Discount Badge - Top Right Corner (Yellow) */
.fd-discount-label {
    position: absolute;
    top: 12px;
    right: 12px;
    background: #FFC107;
    color: #000000;
    padding: 6px 10px;
    border-radius: 4px;
    font-weight: 700;
    font-size: 13px;
    z-index: 10;
    line-height: 1;
}

/* Action Buttons - Bottom Left of Info Area */
.fd-action-buttons {
    display: flex;
    flex-direction: row;
    gap: 8px;
    margin-top: 12px;
    align-items: center;
}

.fd-action-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #FF6A00;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.25s ease;
    color: #ffffff;
    font-size: 14px;
}

.fd-action-btn:hover {
    background: #ff8533;
    transform: scale(1.05);
}

.fd-cart-btn {
    background: #FF6A00;
}

/* Info Container */
.fd-info-container {
    padding: 18px;
    text-align: left;
}

/* Category Text - Grey */
.fd-category-text {
    font-size: 12px;
    color: #999999;
    text-transform: capitalize;
    margin-bottom: 8px;
    font-weight: 400;
}

/* Product Title */
.fd-product-title {
    font-size: 15px;
    font-weight: 500;
    margin: 0 0 10px 0;
    line-height: 1.4;
    min-height: 42px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.fd-product-title a {
    color: #333333;
    text-decoration: none;
    transition: color 0.2s ease;
}

.fd-product-title a:hover {
    color: #FF6A00;
}

/* Star Rating - Gray Outlines Only */
.fd-rating-stars {
    margin-bottom: 12px;
    display: flex;
    gap: 2px;
}

.fd-rating-stars i {
    font-size: 13px;
    color: #e0e0e0;
}

.fd-rating-stars .fd-star-active {
    color: #e0e0e0;
}

.fd-rating-stars .fd-star-inactive {
    color: #e0e0e0;
}

/* Price Section */
.fd-price-section {
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.fd-price-original {
    font-size: 14px;
    color: #999999;
    text-decoration: line-through;
}

.fd-price-current {
    font-size: 20px;
    font-weight: 700;
    color: #FF6A00;
}

/* Countdown Timer - Orange Square Boxes */
.fd-countdown-wrapper {
    display: flex;
    gap: 8px;
    justify-content: flex-start;
    margin-bottom: 0;
}

.fd-timer-unit {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.fd-timer-box {
    background: #FF6A00;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    box-shadow: 0 2px 6px rgba(255, 106, 0, 0.25);
}

.fd-timer-value {
    font-size: 18px;
    font-weight: 700;
    color: #ffffff;
    line-height: 1;
}

.fd-timer-label {
    font-size: 10px;
    color: #666666;
    text-transform: capitalize;
    font-weight: 500;
    margin-top: 4px;
}

.fd-timer-unit:last-child .fd-timer-label {
    color: #ffffff;
}

/* Owl Carousel */
#fd-products-carousel.owl-carousel .owl-nav {
    display: none;
}

#fd-products-carousel.owl-carousel .owl-dots {
    display: none;
}

/* =================================================
   RESPONSIVE DESIGN
   ================================================= */

@media (max-width: 1200px) {
    .fd-product-title {
        font-size: 14px;
        min-height: 40px;
    }
    
    .fd-price-current {
        font-size: 18px;
    }
    
    .fd-timer-box {
        width: 45px;
        height: 45px;
    }
    
    .fd-timer-value {
        font-size: 16px;
    }
}

@media (max-width: 991px) {
    #fd-module-wrapper.fd-module-container {
        padding: 25px 0;
    }
    
    .fd-module-heading {
        font-size: 18px;
    }
    
    .fd-product-card {
        margin: 5px 3px;
    }
}

@media (max-width: 768px) {
    #fd-module-wrapper.fd-module-container {
        padding: 20px 0;
    }
    
    .fd-module-header-wrapper {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 15px;
    }
    
    .fd-header-controls {
        width: 100%;
        justify-content: flex-end;
    }
    
    .fd-module-heading {
        font-size: 16px;
    }
    
    .fd-control-btn {
        width: 36px;
        height: 36px;
        font-size: 12px;
    }
    
    .fd-info-container {
        padding: 15px;
    }
    
    .fd-product-title {
        font-size: 13px;
        min-height: 36px;
    }
    
    .fd-price-current {
        font-size: 16px;
    }
    
    .fd-price-original {
        font-size: 12px;
    }
    
    .fd-timer-box {
        width: 40px;
        height: 40px;
    }
    
    .fd-timer-value {
        font-size: 14px;
    }
    
    .fd-timer-label {
        font-size: 9px;
    }
}

@media (max-width: 480px) {
    .fd-module-header-wrapper {
        padding: 10px 12px;
    }
    
    .fd-module-heading {
        font-size: 14px;
    }
    
    .fd-control-btn {
        width: 32px;
        height: 32px;
    }
    
    .fd-info-container {
        padding: 12px;
    }
    
    .fd-category-text {
        font-size: 11px;
    }
    
    .fd-product-title {
        font-size: 12px;
        min-height: 34px;
    }
    
    .fd-price-current {
        font-size: 15px;
    }
    
    .fd-timer-box {
        width: 35px;
        height: 35px;
    }
    
    .fd-timer-value {
        font-size: 12px;
    }
    
    .fd-timer-label {
        font-size: 8px;
    }
    
    .fd-discount-label {
        font-size: 11px;
        padding: 4px 10px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    var $carousel = $('#fd-products-carousel');
    
    // Initialize Owl Carousel with automatic sliding
    if (typeof $.fn.owlCarousel !== 'undefined') {
        $carousel.owlCarousel({
            loop: true,
            margin: 20,
            nav: false,
            dots: false,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: false,
            autoplaySpeed: 1000,
            smartSpeed: 800,
            responsive: {
                0: {
                    items: 1,
                    margin: 10
                },
                480: {
                    items: 1,
                    margin: 12
                },
                768: {
                    items: 2,
                    margin: 15
                },
                992: {
                    items: 2,
                    margin: 18
                },
                1200: {
                    items: 2,
                    margin: 20
                }
            }
        });
    }
    
    // Countdown timers
    $('.fd-countdown-wrapper').each(function() {
        var $timer = $(this);
        var endDate = $timer.data('end-date');
        if (!endDate) return;
        
        var targetDate = new Date(endDate);
        if (isNaN(targetDate.getTime())) return;
        
        var $units = $timer.find('.fd-timer-unit');
        
        function updateTimer() {
            var now = new Date().getTime();
            var distance = targetDate.getTime() - now;
            
            if (distance < 0) {
                $units.eq(0).find('.fd-timer-value').text('00');
                $units.eq(1).find('.fd-timer-value').text('00');
                $units.eq(2).find('.fd-timer-value').text('00');
                $units.eq(3).find('.fd-timer-value').text('00');
                return;
            }
            
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            $units.eq(0).find('.fd-timer-value').text(String(days).padStart(2, '0'));
            $units.eq(1).find('.fd-timer-value').text(String(hours).padStart(2, '0'));
            $units.eq(2).find('.fd-timer-value').text(String(minutes).padStart(2, '0'));
            $units.eq(3).find('.fd-timer-value').text(String(seconds).padStart(2, '0'));
        }
        
        updateTimer();
        setInterval(updateTimer, 1000);
    });
});
</script>
<?php } ?>
