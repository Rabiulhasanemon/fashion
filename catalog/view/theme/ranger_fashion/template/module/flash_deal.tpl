<?php if ($products) { ?>
<div id="fld-wrapper-<?php echo isset($module_id) ? $module_id : time(); ?>" class="fld-main-container">
    <div class="container">
        <!-- Simple Header -->
        <div class="fld-header-box">
            <h2 class="fld-title-text">
                <span class="fld-title-flash">Flash</span><span class="fld-title-deal"> Deal</span>
            </h2>
        </div>
        
        <!-- Products Carousel -->
        <div class="fld-carousel-container">
            <div id="fld-carousel-<?php echo isset($module_id) ? $module_id : time(); ?>" class="fld-owl-carousel owl-carousel">
                <?php foreach ($products as $product) { ?>
                <div class="fld-item-wrapper">
                    <div class="fld-card-box">
                        <!-- Product Image - Left Side -->
                        <div class="fld-img-wrapper">
                            <a href="<?php echo $product['href']; ?>" class="fld-img-link">
                                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="fld-img">
                            </a>
                            
                            <!-- Discount Badge -->
                            <?php if ($product['discount']) { ?>
                            <div class="fld-badge-yellow">-<?php echo (int)$product['discount']; ?>%</div>
                            <?php } ?>
                            
                            <!-- Add to Cart Button - Over Image -->
                            <div class="fld-buttons-box-overlay">
                                <button type="button" class="fld-btn-cart" onclick="cart.add('<?php echo $product['product_id']; ?>');" title="Add to Cart">
                                    <i class="fa fa-shopping-cart"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                        
                        <!-- Product Details - Right Side -->
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
    padding: 20px 0;
    background: #ffffff;
    position: relative;
    width: 100%;
}

/* Header */
.fld-header-box {
    margin-bottom: 15px;
    padding: 0;
}

.fld-title-text {
    font-size: 18px;
    font-weight: 600;
    color: #000000;
    margin: 0;
    padding: 0;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
    line-height: 1.4;
}

.fld-title-flash {
    position: relative;
    display: inline-block;
}

.fld-title-flash::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 100%;
    height: 3px;
    background: #FF6A00;
}

.fld-title-deal {
    color: #000000;
}

/* Carousel Container */
.fld-carousel-container {
    position: relative;
    width: 100%;
}

.fld-owl-carousel {
    position: relative;
}

.fld-owl-carousel .owl-item {
    display: flex;
    align-items: stretch;
    height: 180px;
}

.fld-owl-carousel .owl-stage {
    display: flex;
    align-items: stretch;
}

/* Product Card */
.fld-item-wrapper {
    padding: 0 8px;
    height: 180px;
    display: flex;
    align-items: stretch;
}

.fld-card-box {
    background: #ffffff;
    border: 1px solid #FFCC80;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 180px;
    display: flex;
    flex-direction: row;
    align-items: stretch;
    min-height: 180px;
    max-height: 180px;
}

.fld-card-box:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Image Wrapper - Left Side */
.fld-img-wrapper {
    position: relative;
    width: 40%;
    min-width: 140px;
    overflow: hidden;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    margin: 0;
}

.fld-img-link {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.fld-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
    padding: 0;
    margin: 0;
}

.fld-card-box:hover .fld-img {
    transform: scale(1.05);
}

/* Discount Badge */
.fld-badge-yellow {
    position: absolute;
    top: 8px;
    right: 8px;
    background: #FFC107;
    color: #000000;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 700;
    font-size: 11px;
    z-index: 10;
    line-height: 1;
}

/* Add to Cart Button Overlay on Image */
.fld-buttons-box-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 8px;
    z-index: 15;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.3) 50%, transparent 100%);
}

/* Details Box - Right Side */
.fld-details-box {
    padding: 12px;
    text-align: left;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    width: 60%;
    height: 100%;
}

/* Category Text */
.fld-cat-text {
    font-size: 10px;
    color: #999999;
    text-transform: capitalize;
    margin-bottom: 6px;
    font-weight: 400;
    flex-shrink: 0;
    height: 14px;
}

/* Product Name */
.fld-name-text {
    font-size: 16px;
    font-weight: 600;
    margin: 0 0 8px 0;
    line-height: 1.5;
    height: 48px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    flex-shrink: 0;
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
    margin-bottom: 8px;
    display: flex;
    gap: 2px;
    flex-shrink: 0;
    height: 16px;
}

.fld-star-grey {
    font-size: 11px;
    color: #e0e0e0;
}

/* Price Box */
.fld-price-box {
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    flex-shrink: 0;
    min-height: 24px;
}

.fld-price-old {
    font-size: 12px;
    color: #999999;
    text-decoration: line-through;
}

.fld-price-new {
    font-size: 16px;
    font-weight: 700;
    color: #FF6A00;
}

/* Countdown Timer */
.fld-timer-container {
    display: flex;
    gap: 6px;
    justify-content: flex-start;
    margin-bottom: 10px;
    flex-shrink: 0;
    min-height: 50px;
}

.fld-timer-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 3px;
}

.fld-timer-square {
    background: #FF6A00;
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    box-shadow: 0 2px 6px rgba(255, 106, 0, 0.25);
}

.fld-timer-num {
    font-size: 14px;
    font-weight: 700;
    color: #ffffff;
    line-height: 1;
}

.fld-timer-text {
    font-size: 9px;
    color: #666666;
    text-transform: capitalize;
    font-weight: 500;
    margin-top: 2px;
}

.fld-timer-text-white {
    color: #ffffff;
}

/* Action Buttons - Overlay on Image */
.fld-buttons-box-overlay .fld-btn-cart {
    width: 100%;
    max-width: 100%;
    padding: 8px 12px;
    border-radius: 6px;
    background: linear-gradient(135deg, #FF6A00 0%, #ff8533 100%);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #ffffff;
    font-size: 12px;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(255, 106, 0, 0.5);
    position: relative;
    overflow: hidden;
    gap: 6px;
    white-space: nowrap;
}

.fld-buttons-box-overlay .fld-btn-cart:hover {
    background: linear-gradient(135deg, #ff8533 0%, #FF6A00 100%);
    box-shadow: 0 4px 12px rgba(255, 106, 0, 0.7);
    transform: translateY(-2px);
}

.fld-buttons-box-overlay .fld-btn-cart:active {
    transform: translateY(0);
}

.fld-buttons-box-overlay .fld-btn-cart i {
    font-size: 13px;
}

.fld-btn-cart {
    padding: 8px 16px;
    border-radius: 6px;
    background: linear-gradient(135deg, #FF6A00 0%, #ff8533 100%);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #ffffff;
    font-size: 13px;
    font-weight: 600;
    box-shadow: 0 2px 6px rgba(255, 106, 0, 0.3);
    position: relative;
    overflow: hidden;
    gap: 6px;
    white-space: nowrap;
}

.fld-btn-cart:hover {
    background: linear-gradient(135deg, #ff8533 0%, #FF6A00 100%);
    box-shadow: 0 4px 12px rgba(255, 106, 0, 0.5);
    transform: translateY(-2px);
}

.fld-btn-cart:active {
    transform: translateY(0);
}

.fld-btn-cart i {
    font-size: 14px;
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
    .fld-main-container {
        padding: 18px 0;
    }
    
    .fld-title-text {
        font-size: 17px;
    }
    
    .fld-name-text {
        font-size: 12px;
        min-height: 32px;
    }
    
    .fld-price-new {
        font-size: 15px;
    }
    
    .fld-timer-square {
        width: 35px;
        height: 35px;
    }
    
    .fld-timer-num {
        font-size: 13px;
    }
    
    .fld-item-wrapper {
        height: 170px;
    }
    
    .fld-owl-carousel .owl-item {
        height: 170px;
    }
    
    .fld-img-wrapper {
        padding: 0;
    }
    
    .fld-details-box {
        padding: 10px;
    }
}

@media (max-width: 991px) {
    .fld-main-container {
        padding: 15px 0;
    }
    
    .fld-title-text {
        font-size: 16px;
    }
    
    .fld-header-box {
        margin-bottom: 12px;
    }
    
    .fld-item-wrapper {
        padding: 0 6px;
        height: 160px;
    }
    
    .fld-owl-carousel .owl-item {
        height: 160px;
    }
    
    .fld-img-wrapper {
        width: 38%;
        min-width: 120px;
        padding: 0;
    }
    
    .fld-details-box {
        width: 62%;
        padding: 10px;
    }
    
    .fld-card-box {
        height: 160px;
        min-height: 160px;
        max-height: 160px;
    }
}

@media (max-width: 768px) {
    .fld-main-container {
        padding: 15px 0;
    }
    
    .fld-title-text {
        font-size: 15px;
    }
    
    .fld-header-box {
        margin-bottom: 10px;
    }
    
    .fld-item-wrapper {
        padding: 0 5px;
    }
    
    /* Switch to vertical layout on mobile */
    .fld-item-wrapper {
        height: auto;
    }
    
    .fld-owl-carousel .owl-item {
        height: auto;
    }
    
    .fld-card-box {
        flex-direction: column;
        border-radius: 6px;
        height: auto;
        min-height: auto;
        max-height: none;
    }
    
    .fld-img-wrapper {
        width: 100%;
        min-width: auto;
        padding: 0;
        aspect-ratio: 1;
    }
    
    .fld-img-link {
        width: 100%;
        height: 100%;
    }
    
    .fld-details-box {
        width: 100%;
        padding: 10px;
    }
    
    .fld-name-text {
        font-size: 14px;
        min-height: 42px;
    }
    
    .fld-buttons-box-overlay {
        padding: 6px;
    }
    
    .fld-buttons-box-overlay .fld-btn-cart {
        font-size: 11px;
        padding: 6px 10px;
    }
    
    .fld-price-new {
        font-size: 15px;
    }
    
    .fld-price-old {
        font-size: 11px;
    }
    
    .fld-timer-square {
        width: 32px;
        height: 32px;
    }
    
    .fld-timer-num {
        font-size: 12px;
    }
    
    .fld-timer-text {
        font-size: 8px;
    }
    
    .fld-timer-container {
        gap: 5px;
    }
    
    .fld-details-box .fld-buttons-box {
        margin: 8px 0 6px 0;
        gap: 0;
    }
    
    .fld-btn-cart {
        padding: 7px 14px;
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .fld-main-container {
        padding: 12px 0;
    }
    
    .fld-title-text {
        font-size: 14px;
    }
    
    .fld-header-box {
        margin-bottom: 8px;
    }
    
    .fld-item-wrapper {
        padding: 0 4px;
    }
    
    .fld-img-wrapper {
        padding: 0;
    }
    
    .fld-details-box {
        padding: 8px;
    }
    
    .fld-cat-text {
        font-size: 9px;
        margin-bottom: 4px;
    }
    
    .fld-name-text {
        font-size: 13px;
        min-height: 39px;
        margin-bottom: 6px;
    }
    
    .fld-btn-cart {
        padding: 6px 12px;
        font-size: 12px;
    }
    
    .fld-stars-box {
        margin-bottom: 6px;
    }
    
    .fld-star-grey {
        font-size: 10px;
    }
    
    .fld-price-box {
        margin-bottom: 8px;
        gap: 6px;
    }
    
    .fld-price-new {
        font-size: 14px;
    }
    
    .fld-price-old {
        font-size: 10px;
    }
    
    .fld-timer-container {
        gap: 4px;
        margin-bottom: 8px;
    }
    
    .fld-timer-square {
        width: 28px;
        height: 28px;
    }
    
    .fld-timer-num {
        font-size: 11px;
    }
    
    .fld-timer-text {
        font-size: 7px;
    }
    
    .fld-badge-yellow {
        font-size: 10px;
        padding: 3px 6px;
        top: 6px;
        right: 6px;
    }
    
    .fld-details-box .fld-buttons-box {
        margin: 8px 0 6px 0;
        gap: 0;
    }
    
    .fld-btn-cart {
        padding: 6px 12px;
        font-size: 12px;
    }
}

@media (max-width: 360px) {
    .fld-main-container {
        padding: 10px 0;
    }
    
    .fld-title-text {
        font-size: 13px;
    }
    
    .fld-timer-square {
        width: 26px;
        height: 26px;
    }
    
    .fld-timer-num {
        font-size: 10px;
    }
    
    .fld-timer-text {
        font-size: 6px;
    }
    
    .fld-btn-cart {
        padding: 5px 10px;
        font-size: 11px;
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
                    margin: 8
                },
                480: {
                    items: 1,
                    margin: 10
                },
                576: {
                    items: 2,
                    margin: 8
                },
                768: {
                    items: 2,
                    margin: 10
                },
                992: {
                    items: 2,
                    margin: 12
                },
                1200: {
                    items: 3,
                    margin: 15
                },
                1400: {
                    items: 3,
                    margin: 18
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
