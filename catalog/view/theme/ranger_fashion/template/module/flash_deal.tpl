<?php if ($products) { ?>
<div id="rf-flash-deal-module" class="rf-flash-deal-wrapper">
    <div class="container">
        <!-- Premium Module Header - Consistent Style -->
        <div class="rf-module-header" id="rf-flash-header-<?php echo isset($module_id) ? $module_id : time(); ?>">
            <div class="rf-module-header-left">
                <h2 class="rf-module-title"><?php echo isset($heading_title) ? htmlspecialchars($heading_title) : 'Flash Deal'; ?></h2>
            </div>
            <div class="rf-module-header-right">
                <div class="rf-carousel-nav">
                    <button type="button" class="rf-nav-arrow rf-nav-prev" aria-label="Previous">
                        <i class="fa fa-chevron-left"></i>
                    </button>
                    <button type="button" class="rf-nav-arrow rf-nav-next" aria-label="Next">
                        <i class="fa fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Flash Deal Products -->
        <div class="rf-flash-content">
            <div id="rf-flash-carousel" class="rf-flash-carousel owl-carousel">
                <?php foreach ($products as $product) { ?>
                <div class="rf-flash-item">
                    <div class="rf-flash-card">
                        <!-- Image Section -->
                        <div class="rf-flash-image-box">
                            <?php if ($product['discount']) { ?>
                            <div class="rf-flash-badge">-<?php echo (int)$product['discount']; ?>%</div>
                            <?php } ?>
                            <a href="<?php echo $product['href']; ?>" class="rf-flash-img-link">
                                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="rf-flash-img">
                            </a>
                            <div class="rf-flash-quick-actions">
                                <button type="button" class="rf-quick-btn" onclick="wishlist.add('<?php echo $product['product_id']; ?>');" title="Wishlist">
                                    <i class="fa fa-heart-o"></i>
                                </button>
                                <button type="button" class="rf-quick-btn" onclick="compare.add('<?php echo $product['product_id']; ?>');" title="Compare">
                                    <i class="fa fa-exchange"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Info Section -->
                        <div class="rf-flash-info-box">
                            <?php if ($product['category_name']) { ?>
                            <div class="rf-flash-category"><?php echo htmlspecialchars($product['category_name']); ?></div>
                            <?php } ?>
                            
                            <h3 class="rf-flash-name">
                                <a href="<?php echo $product['href']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                            </h3>
                            
                            <div class="rf-flash-stars">
                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                <i class="fa fa-star <?php echo ($i <= $product['rating']) ? 'rf-star-filled' : 'rf-star-empty'; ?>"></i>
                                <?php } ?>
                            </div>
                            
                            <div class="rf-flash-price-row">
                                <?php if ($product['special']) { ?>
                                <span class="rf-flash-old-price"><?php echo $product['price']; ?></span>
                                <span class="rf-flash-new-price"><?php echo $product['special']; ?></span>
                                <?php } else { ?>
                                <span class="rf-flash-new-price"><?php echo $product['price']; ?></span>
                                <?php } ?>
                            </div>
                            
                            <?php if (!empty($product['end_date'])) { ?>
                            <div class="rf-flash-timer" data-end-date="<?php echo htmlspecialchars($product['end_date']); ?>">
                                <div class="rf-timer-box">
                                    <span class="rf-timer-num">00</span>
                                    <span class="rf-timer-txt">Days</span>
                                </div>
                                <div class="rf-timer-box">
                                    <span class="rf-timer-num">00</span>
                                    <span class="rf-timer-txt">Hrs</span>
                                </div>
                                <div class="rf-timer-box">
                                    <span class="rf-timer-num">00</span>
                                    <span class="rf-timer-txt">Min</span>
                                </div>
                                <div class="rf-timer-box">
                                    <span class="rf-timer-num">00</span>
                                    <span class="rf-timer-txt">Sec</span>
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
   PREMIUM FLASH DEAL MODULE - RF PREFIX
   New unique classes to avoid conflicts
   ================================================= */

#rf-flash-deal-module.rf-flash-deal-wrapper {
    padding: 30px 0;
    background: #ffffff;
    position: relative;
}

/* Premium Module Header - Global Style for All Modules */
.rf-module-header {
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

.rf-module-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, #FF6A00 0%, rgba(255, 106, 0, 0.1) 100%);
}

.rf-module-header-left {
    flex: 1;
}

.rf-module-title {
    font-size: 20px;
    font-weight: 700;
    color: #333333;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
    display: inline-block;
}

.rf-module-title::before {
    content: '';
    position: absolute;
    left: -20px;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 100%;
    background: #FF6A00;
    border-radius: 2px;
    display: none;
}

.rf-module-header-right {
    flex-shrink: 0;
}

.rf-carousel-nav {
    display: flex;
    gap: 10px;
}

.rf-nav-arrow {
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

.rf-nav-arrow:hover {
    background: #FF6A00;
    color: #ffffff;
    border-color: #FF6A00;
    transform: scale(1.05);
}

.rf-nav-arrow:active {
    transform: scale(0.95);
}

/* Flash Deal Content */
.rf-flash-content {
    position: relative;
}

.rf-flash-carousel {
    position: relative;
}

/* Flash Card */
.rf-flash-card {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    margin: 5px;
    border: 1px solid #f0f0f0;
}

.rf-flash-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

/* Image Box */
.rf-flash-image-box {
    position: relative;
    padding-top: 100%;
    overflow: hidden;
    background: #f8f9fa;
}

.rf-flash-img-link {
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

.rf-flash-img {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
    object-fit: contain;
    transition: transform 0.4s ease;
}

.rf-flash-card:hover .rf-flash-img {
    transform: scale(1.08);
}

/* Discount Badge */
.rf-flash-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: #FF6A00;
    color: #ffffff;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 13px;
    z-index: 10;
    box-shadow: 0 2px 8px rgba(255, 106, 0, 0.3);
}

/* Quick Actions */
.rf-flash-quick-actions {
    position: absolute;
    top: 12px;
    right: 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    opacity: 0;
    transform: translateX(10px);
    transition: all 0.3s ease;
}

.rf-flash-card:hover .rf-flash-quick-actions {
    opacity: 1;
    transform: translateX(0);
}

.rf-quick-btn {
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

.rf-quick-btn:hover {
    background: #FF6A00;
    color: #ffffff;
    transform: scale(1.1);
}

/* Info Box */
.rf-flash-info-box {
    padding: 18px;
    text-align: left;
}

.rf-flash-category {
    font-size: 12px;
    color: #FF6A00;
    text-transform: capitalize;
    letter-spacing: 0.3px;
    margin-bottom: 8px;
    font-weight: 500;
}

.rf-flash-name {
    font-size: 15px;
    font-weight: 600;
    margin: 0 0 10px 0;
    line-height: 1.4;
    min-height: 42px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.rf-flash-name a {
    color: #333333;
    text-decoration: none;
    transition: color 0.2s ease;
}

.rf-flash-name a:hover {
    color: #FF6A00;
}

/* Stars */
.rf-flash-stars {
    margin-bottom: 10px;
}

.rf-flash-stars i {
    font-size: 13px;
    margin-right: 2px;
}

.rf-flash-stars .rf-star-filled {
    color: #FFC107;
}

.rf-flash-stars .rf-star-empty {
    color: #e0e0e0;
}

/* Price Row */
.rf-flash-price-row {
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.rf-flash-old-price {
    font-size: 14px;
    color: #999999;
    text-decoration: line-through;
}

.rf-flash-new-price {
    font-size: 20px;
    font-weight: 700;
    color: #FF6A00;
}

/* Countdown Timer - Orange Theme */
.rf-flash-timer {
    display: flex;
    gap: 8px;
    justify-content: flex-start;
}

.rf-timer-box {
    background: #FF6A00;
    border-radius: 6px;
    padding: 8px 10px;
    min-width: 50px;
    text-align: center;
    box-shadow: 0 2px 6px rgba(255, 106, 0, 0.25);
}

.rf-timer-num {
    display: block;
    font-size: 16px;
    font-weight: 700;
    color: #ffffff;
    line-height: 1;
}

.rf-timer-txt {
    display: block;
    font-size: 10px;
    color: rgba(255, 255, 255, 0.9);
    text-transform: capitalize;
    margin-top: 3px;
    letter-spacing: 0.3px;
}

/* Owl Carousel Navigation */
#rf-flash-carousel.owl-carousel .owl-nav {
    display: none;
}

#rf-flash-carousel.owl-carousel .owl-dots {
    display: none;
}

/* =================================================
   RESPONSIVE DESIGN
   ================================================= */

@media (max-width: 1200px) {
    .rf-flash-name {
        font-size: 14px;
        min-height: 40px;
    }
    
    .rf-flash-new-price {
        font-size: 18px;
    }
}

@media (max-width: 991px) {
    #rf-flash-deal-module.rf-flash-deal-wrapper {
        padding: 25px 0;
    }
    
    .rf-module-title {
        font-size: 18px;
    }
    
    .rf-flash-card {
        margin: 5px 3px;
    }
}

@media (max-width: 768px) {
    #rf-flash-deal-module.rf-flash-deal-wrapper {
        padding: 20px 0;
    }
    
    .rf-module-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 15px;
    }
    
    .rf-module-header-right {
        width: 100%;
        display: flex;
        justify-content: flex-end;
    }
    
    .rf-module-title {
        font-size: 16px;
    }
    
    .rf-nav-arrow {
        width: 36px;
        height: 36px;
        font-size: 12px;
    }
    
    .rf-flash-info-box {
        padding: 15px;
    }
    
    .rf-flash-name {
        font-size: 13px;
        min-height: 36px;
    }
    
    .rf-flash-new-price {
        font-size: 16px;
    }
    
    .rf-flash-old-price {
        font-size: 12px;
    }
    
    .rf-timer-box {
        min-width: 45px;
        padding: 6px 8px;
    }
    
    .rf-timer-num {
        font-size: 14px;
    }
    
    .rf-timer-txt {
        font-size: 9px;
    }
}

@media (max-width: 480px) {
    .rf-module-header {
        padding: 10px 12px;
    }
    
    .rf-module-title {
        font-size: 14px;
    }
    
    .rf-nav-arrow {
        width: 32px;
        height: 32px;
    }
    
    .rf-flash-info-box {
        padding: 12px;
    }
    
    .rf-flash-category {
        font-size: 11px;
    }
    
    .rf-flash-name {
        font-size: 12px;
        min-height: 34px;
    }
    
    .rf-flash-new-price {
        font-size: 15px;
    }
    
    .rf-timer-box {
        min-width: 40px;
        padding: 5px 6px;
    }
    
    .rf-timer-num {
        font-size: 12px;
    }
    
    .rf-timer-txt {
        font-size: 8px;
    }
    
    .rf-flash-badge {
        font-size: 11px;
        padding: 4px 10px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    var $carousel = $('#rf-flash-carousel');
    var $prevBtn = $('.rf-nav-prev');
    var $nextBtn = $('.rf-nav-next');
    
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
    $('.rf-flash-timer').each(function() {
        var $timer = $(this);
        var endDate = $timer.data('end-date');
        if (!endDate) return;
        
        var targetDate = new Date(endDate);
        if (isNaN(targetDate.getTime())) return;
        
        var $boxes = $timer.find('.rf-timer-box');
        
        function updateTimer() {
            var now = new Date().getTime();
            var distance = targetDate.getTime() - now;
            
            if (distance < 0) {
                $boxes.eq(0).find('.rf-timer-num').text('00');
                $boxes.eq(1).find('.rf-timer-num').text('00');
                $boxes.eq(2).find('.rf-timer-num').text('00');
                $boxes.eq(3).find('.rf-timer-num').text('00');
                return;
            }
            
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            $boxes.eq(0).find('.rf-timer-num').text(String(days).padStart(2, '0'));
            $boxes.eq(1).find('.rf-timer-num').text(String(hours).padStart(2, '0'));
            $boxes.eq(2).find('.rf-timer-num').text(String(minutes).padStart(2, '0'));
            $boxes.eq(3).find('.rf-timer-num').text(String(seconds).padStart(2, '0'));
        }
        
        updateTimer();
        setInterval(updateTimer, 1000);
    });
});
</script>
<?php } ?>
