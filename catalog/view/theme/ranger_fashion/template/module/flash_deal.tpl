<?php if ($products) { ?>
<div id="fld-wrapper-<?php echo isset($module_id) ? $module_id : time(); ?>" class="fld-main-container">
    <div class="container">
        <!-- Simple Header -->
        <div class="fld-header-box">
            <h2 class="fld-title-text">Flash Deal</h2>
        </div>
        
        <!-- Products Carousel -->
        <div class="fld-carousel-container">
            <div id="fld-carousel-<?php echo isset($module_id) ? $module_id : time(); ?>" class="fld-owl-carousel owl-carousel">
                <?php foreach ($products as $product) { ?>
                <div class="fld-item-wrapper">
                    <div class="fld-card-box">
                        <!-- Product Image -->
                        <div class="fld-img-wrapper">
                            <a href="<?php echo $product['href']; ?>" class="fld-img-link">
                                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="fld-img">
                            </a>
                            
                            <!-- Discount Badge -->
                            <?php if ($product['discount']) { ?>
                            <div class="fld-badge-yellow">-<?php echo (int)$product['discount']; ?>%</div>
                            <?php } ?>
                        </div>
                        
                        <!-- Product Details -->
                        <div class="fld-details-box">
                            <!-- Category -->
                            <?php if ($product['category_name']) { ?>
                            <div class="fld-cat-text"><?php echo htmlspecialchars($product['category_name']); ?></div>
                            <?php } ?>
                            
                            <!-- Product Name -->
                            <h3 class="fld-name-text">
                                <a href="<?php echo $product['href']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                            </h3>
                            
                            <!-- Star Rating -->
                            <div class="fld-stars-box">
                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                <i class="fa fa-star fld-star-grey"></i>
                                <?php } ?>
                            </div>
                            
                            <!-- Price -->
                            <div class="fld-price-box">
                                <?php if ($product['special']) { ?>
                                <span class="fld-price-old"><?php echo $product['price']; ?></span>
                                <span class="fld-price-new"><?php echo $product['special']; ?></span>
                                <?php } else { ?>
                                <span class="fld-price-new"><?php echo $product['price']; ?></span>
                                <?php } ?>
                            </div>
                            
                            <!-- Countdown Timer -->
                            <?php if (!empty($product['end_date'])) { ?>
                            <div class="fld-timer-container" data-end="<?php echo htmlspecialchars($product['end_date']); ?>">
                                <div class="fld-timer-item">
                                    <div class="fld-timer-square">
                                        <span class="fld-timer-num">00</span>
                                    </div>
                                    <span class="fld-timer-text">Days</span>
                                </div>
                                <div class="fld-timer-item">
                                    <div class="fld-timer-square">
                                        <span class="fld-timer-num">00</span>
                                    </div>
                                    <span class="fld-timer-text">Hrs</span>
                                </div>
                                <div class="fld-timer-item">
                                    <div class="fld-timer-square">
                                        <span class="fld-timer-num">00</span>
                                    </div>
                                    <span class="fld-timer-text">Min</span>
                                </div>
                                <div class="fld-timer-item">
                                    <div class="fld-timer-square">
                                        <span class="fld-timer-num">00</span>
                                    </div>
                                    <span class="fld-timer-text fld-timer-text-white">Sec</span>
                                </div>
                            </div>
                            <?php } ?>
                            
                            <!-- Action Buttons -->
                            <div class="fld-buttons-box">
                                <button type="button" class="fld-btn-circle" onclick="compare.add('<?php echo $product['product_id']; ?>');" title="Compare">
                                    <i class="fa fa-exchange"></i>
                                </button>
                                <button type="button" class="fld-btn-circle fld-btn-cart" onclick="cart.add('<?php echo $product['product_id']; ?>');" title="Add to Cart">
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
   FLASH DEAL MODULE - COMPLETELY NEW CLASSES
   FLD Prefix - No Conflicts with Existing Code
   ================================================= */

.fld-main-container {
    padding: 30px 0;
    background: #ffffff;
    position: relative;
    width: 100%;
}

/* Header */
.fld-header-box {
    margin-bottom: 20px;
    padding: 0;
}

.fld-title-text {
    font-size: 20px;
    font-weight: 600;
    color: #000000;
    margin: 0;
    padding: 0;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
    line-height: 1.4;
}

/* Carousel Container */
.fld-carousel-container {
    position: relative;
    width: 100%;
}

.fld-owl-carousel {
    position: relative;
}

/* Product Card */
.fld-item-wrapper {
    padding: 0 10px;
}

.fld-card-box {
    background: #ffffff;
    border: 1px solid #FFCC80;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.fld-card-box:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Image Wrapper */
.fld-img-wrapper {
    position: relative;
    width: 100%;
    padding-top: 100%;
    overflow: hidden;
    background: #f8f9fa;
}

.fld-img-link {
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

.fld-img {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
    object-fit: contain;
    transition: transform 0.4s ease;
}

.fld-card-box:hover .fld-img {
    transform: scale(1.05);
}

/* Discount Badge */
.fld-badge-yellow {
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

/* Details Box */
.fld-details-box {
    padding: 18px;
    text-align: left;
    flex: 1;
    display: flex;
    flex-direction: column;
}

/* Category Text */
.fld-cat-text {
    font-size: 12px;
    color: #999999;
    text-transform: capitalize;
    margin-bottom: 8px;
    font-weight: 400;
}

/* Product Name */
.fld-name-text {
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

.fld-name-text a {
    color: #333333;
    text-decoration: none;
    transition: color 0.2s ease;
}

.fld-name-text a:hover {
    color: #FF6A00;
}

/* Star Rating */
.fld-stars-box {
    margin-bottom: 12px;
    display: flex;
    gap: 2px;
}

.fld-star-grey {
    font-size: 13px;
    color: #e0e0e0;
}

/* Price Box */
.fld-price-box {
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.fld-price-old {
    font-size: 14px;
    color: #999999;
    text-decoration: line-through;
}

.fld-price-new {
    font-size: 20px;
    font-weight: 700;
    color: #FF6A00;
}

/* Countdown Timer */
.fld-timer-container {
    display: flex;
    gap: 8px;
    justify-content: flex-start;
    margin-bottom: 12px;
}

.fld-timer-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.fld-timer-square {
    background: #FF6A00;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    box-shadow: 0 2px 6px rgba(255, 106, 0, 0.25);
}

.fld-timer-num {
    font-size: 18px;
    font-weight: 700;
    color: #ffffff;
    line-height: 1;
}

.fld-timer-text {
    font-size: 10px;
    color: #666666;
    text-transform: capitalize;
    font-weight: 500;
    margin-top: 4px;
}

.fld-timer-text-white {
    color: #ffffff;
}

/* Action Buttons */
.fld-buttons-box {
    display: flex;
    flex-direction: row;
    gap: 8px;
    align-items: center;
    margin-top: auto;
}

.fld-btn-circle {
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

.fld-btn-circle:hover {
    background: #ff8533;
    transform: scale(1.05);
}

.fld-btn-cart {
    background: #FF6A00;
}

/* Owl Carousel Overrides */
.fld-owl-carousel.owl-carousel .owl-nav {
    display: none !important;
}

.fld-owl-carousel.owl-carousel .owl-dots {
    display: none !important;
}

/* =================================================
   RESPONSIVE DESIGN
   ================================================= */

@media (max-width: 1200px) {
    .fld-name-text {
        font-size: 14px;
        min-height: 40px;
    }
    
    .fld-price-new {
        font-size: 18px;
    }
    
    .fld-timer-square {
        width: 45px;
        height: 45px;
    }
    
    .fld-timer-num {
        font-size: 16px;
    }
}

@media (max-width: 991px) {
    .fld-main-container {
        padding: 25px 0;
    }
    
    .fld-title-text {
        font-size: 18px;
    }
    
    .fld-item-wrapper {
        padding: 0 8px;
    }
}

@media (max-width: 768px) {
    .fld-main-container {
        padding: 20px 0;
    }
    
    .fld-title-text {
        font-size: 16px;
    }
    
    .fld-details-box {
        padding: 15px;
    }
    
    .fld-name-text {
        font-size: 13px;
        min-height: 36px;
    }
    
    .fld-price-new {
        font-size: 16px;
    }
    
    .fld-price-old {
        font-size: 12px;
    }
    
    .fld-timer-square {
        width: 40px;
        height: 40px;
    }
    
    .fld-timer-num {
        font-size: 14px;
    }
    
    .fld-timer-text {
        font-size: 9px;
    }
}

@media (max-width: 480px) {
    .fld-title-text {
        font-size: 14px;
    }
    
    .fld-details-box {
        padding: 12px;
    }
    
    .fld-cat-text {
        font-size: 11px;
    }
    
    .fld-name-text {
        font-size: 12px;
        min-height: 34px;
    }
    
    .fld-price-new {
        font-size: 15px;
    }
    
    .fld-timer-square {
        width: 35px;
        height: 35px;
    }
    
    .fld-timer-num {
        font-size: 12px;
    }
    
    .fld-timer-text {
        font-size: 8px;
    }
    
    .fld-badge-yellow {
        font-size: 11px;
        padding: 4px 8px;
    }
    
    .fld-btn-circle {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    var carouselId = '#fld-carousel-<?php echo isset($module_id) ? $module_id : time(); ?>';
    var $carousel = $(carouselId);
    
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
    $('.fld-timer-container').each(function() {
        var $timer = $(this);
        var endDate = $timer.data('end');
        if (!endDate) return;
        
        var targetDate = new Date(endDate);
        if (isNaN(targetDate.getTime())) return;
        
        var $items = $timer.find('.fld-timer-item');
        
        function updateTimer() {
            var now = new Date().getTime();
            var distance = targetDate.getTime() - now;
            
            if (distance < 0) {
                $items.eq(0).find('.fld-timer-num').text('00');
                $items.eq(1).find('.fld-timer-num').text('00');
                $items.eq(2).find('.fld-timer-num').text('00');
                $items.eq(3).find('.fld-timer-num').text('00');
                return;
            }
            
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            $items.eq(0).find('.fld-timer-num').text(String(days).padStart(2, '0'));
            $items.eq(1).find('.fld-timer-num').text(String(hours).padStart(2, '0'));
            $items.eq(2).find('.fld-timer-num').text(String(minutes).padStart(2, '0'));
            $items.eq(3).find('.fld-timer-num').text(String(seconds).padStart(2, '0'));
        }
        
        updateTimer();
        setInterval(updateTimer, 1000);
    });
});
</script>
<?php } ?>
