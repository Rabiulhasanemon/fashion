<?php if ($products) { ?>
<div id="fd-module-wrapper" class="fd-module-container">
    <div class="container">
        <!-- Premium Module Header -->
        <div class="fd-module-header-wrapper" id="fd-header-<?php echo isset($module_id) ? $module_id : time(); ?>">
            <div class="fd-header-content">
                <h2 class="fd-module-heading">
                    <?php 
                    $title = isset($heading_title) ? htmlspecialchars($heading_title) : 'Flash Deal';
                    $title_parts = explode(' ', $title, 2);
                    $first_word = isset($title_parts[0]) ? $title_parts[0] : 'Flash';
                    $rest = isset($title_parts[1]) ? $title_parts[1] : 'Deal';
                    ?>
                    <span class="fd-title-flash"><?php echo $first_word; ?></span> <span class="fd-title-deal"><?php echo $rest; ?></span>
                </h2>
            </div>
            <div class="fd-header-controls">
                <button type="button" class="fd-control-btn fd-prev-btn" aria-label="Previous">
                    <i class="fa fa-chevron-left"></i>
                </button>
                <button type="button" class="fd-control-btn fd-next-btn" aria-label="Next">
                    <i class="fa fa-chevron-right"></i>
                </button>
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
                            
                            <!-- Quick Action Buttons -->
                            <div class="fd-action-buttons">
                                <button type="button" class="fd-action-btn" onclick="wishlist.add('<?php echo $product['product_id']; ?>');" title="Wishlist">
                                    <i class="fa fa-heart-o"></i>
                                </button>
                                <button type="button" class="fd-action-btn" onclick="compare.add('<?php echo $product['product_id']; ?>');" title="Compare">
                                    <i class="fa fa-exchange"></i>
                                </button>
                            </div>
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

/* Module Header */
.fd-module-header-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 25px;
    padding: 15px 20px;
    background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%);
    border-left: 4px solid #FF6A00;
    border-radius: 0 8px 8px 0;
    position: relative;
}

.fd-module-header-wrapper::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, #FF6A00 0%, rgba(255, 106, 0, 0.1) 100%);
}

.fd-header-content {
    flex: 1;
}

.fd-module-heading {
    font-size: 20px;
    font-weight: 700;
    color: #333333;
    margin: 0;
    padding: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    line-height: 1.4;
    display: inline-block;
}

.fd-title-flash {
    position: relative;
    display: inline-block;
}

.fd-title-flash::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    right: 0;
    height: 3px;
    background: #FF6A00;
    border-radius: 2px;
}

.fd-title-deal {
    margin-left: 8px;
}

.fd-header-controls {
    display: flex;
    gap: 10px;
}

.fd-control-btn {
    width: 40px;
    height: 40px;
    border: 2px solid #e0e0e0;
    background: #ffffff;
    color: #666666;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border-radius: 8px;
    font-size: 14px;
}

.fd-control-btn:hover {
    background: #FF6A00;
    color: #ffffff;
    border-color: #FF6A00;
    transform: scale(1.05);
}

.fd-control-btn:active {
    transform: scale(0.95);
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
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    margin: 5px;
    border: 1px solid #FFE0B2;
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

/* Discount Badge - Top Right Corner (Yellow-Orange) */
.fd-discount-label {
    position: absolute;
    top: 12px;
    right: 12px;
    background: #FFC107;
    color: #000000;
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 13px;
    z-index: 10;
    box-shadow: 0 2px 6px rgba(255, 193, 7, 0.4);
    border: 1px solid rgba(255, 152, 0, 0.3);
}

/* Action Buttons */
.fd-action-buttons {
    position: absolute;
    top: 12px;
    left: 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    opacity: 0;
    transform: translateX(-10px);
    transition: all 0.3s ease;
}

.fd-product-card:hover .fd-action-buttons {
    opacity: 1;
    transform: translateX(0);
}

.fd-action-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.95);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    color: #666;
    font-size: 14px;
}

.fd-action-btn:hover {
    background: #FF6A00;
    color: #ffffff;
    transform: scale(1.1);
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

/* Star Rating - Always Gray Outlines */
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
    var $prevBtn = $('.fd-prev-btn');
    var $nextBtn = $('.fd-next-btn');
    
    // Initialize Owl Carousel
    if (typeof $.fn.owlCarousel !== 'undefined') {
        $carousel.owlCarousel({
            loop: true,
            margin: 20,
            nav: false,
            dots: false,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            autoplaySpeed: 800,
            smartSpeed: 600,
            responsive: {
                0: {
                    items: 1,
                    margin: 10
                },
                480: {
                    items: 2,
                    margin: 12
                },
                768: {
                    items: 2,
                    margin: 15
                },
                992: {
                    items: 3,
                    margin: 18
                },
                1200: {
                    items: 4,
                    margin: 20
                }
            }
        });
        
        // Custom navigation
        $prevBtn.on('click', function() {
            $carousel.trigger('prev.owl.carousel');
        });
        
        $nextBtn.on('click', function() {
            $carousel.trigger('next.owl.carousel');
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
