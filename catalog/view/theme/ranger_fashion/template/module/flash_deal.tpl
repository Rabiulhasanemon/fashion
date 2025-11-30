<?php if ($products) { ?>
<div id="premium-flash-deal-module" class="premium-flash-section">
    <div class="container">
        <div class="premium-flash-header">
            <div class="premium-flash-title-wrapper">
                <div class="block-title">
                    <strong><?php echo isset($heading_title) ? $heading_title : 'Flash Deal'; ?></strong>
                </div>
                <div class="premium-flash-subtitle">Limited Time Offers - Grab Them Fast!</div>
            </div>
        </div>
        
        <div class="premium-flash-content">
            <div id="premium-flash-carousel" class="premium-flash-carousel owl-carousel">
                <?php foreach ($products as $product) { ?>
                <div class="premium-flash-item">
                    <div class="premium-flash-card">
                        <div class="premium-flash-image-section">
                            <?php if ($product['discount']) { ?>
                            <div class="premium-flash-discount-badge">-<?php echo (int)$product['discount']; ?>%</div>
                            <?php } ?>
                            <a href="<?php echo $product['href']; ?>" class="premium-flash-image-link">
                                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="premium-flash-image">
                            </a>
                            <div class="premium-flash-actions">
                                <button type="button" class="premium-action-icon" onclick="wishlist.add('<?php echo $product['product_id']; ?>');" title="Wishlist">
                                    <i class="fa fa-heart"></i>
                                </button>
                                <button type="button" class="premium-action-icon" onclick="compare.add('<?php echo $product['product_id']; ?>');" title="Compare">
                                    <i class="fa fa-exchange"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="premium-flash-info-section">
                            <div class="premium-flash-details">
                                <?php if ($product['category_name']) { ?>
                                <div class="premium-flash-category"><?php echo htmlspecialchars($product['category_name']); ?></div>
                                <?php } ?>
                                
                                <h3 class="premium-flash-product-name">
                                    <a href="<?php echo $product['href']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                                </h3>
                                
                                <div class="premium-flash-rating">
                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                    <i class="fa fa-star <?php echo ($i <= $product['rating']) ? 'filled' : ''; ?>"></i>
                                    <?php } ?>
                                </div>
                                
                                <div class="premium-flash-pricing">
                                    <?php if ($product['special']) { ?>
                                    <span class="premium-flash-price-new"><?php echo $product['special']; ?></span>
                                    <span class="premium-flash-price-old"><?php echo $product['price']; ?></span>
                                    <?php } else { ?>
                                    <span class="premium-flash-price-new"><?php echo $product['price']; ?></span>
                                    <?php } ?>
                                    <?php if (!empty($product['points']) && $product['points'] > 0) { ?>
                                    <div class="module-reward-points">
                                      <i class="fa fa-gift"></i>
                                      <span>Earn <?php echo $product['points']; ?> points</span>
                                    </div>
                                    <?php } ?>
                                </div>
                                
                                <?php if (!empty($product['end_date'])) { ?>
                                <div class="premium-flash-countdown" data-end-date="<?php echo htmlspecialchars($product['end_date']); ?>">
                                    <div class="premium-countdown-item">
                                        <span class="premium-countdown-value">00</span>
                                        <span class="premium-countdown-label">Days</span>
                                    </div>
                                    <div class="premium-countdown-item">
                                        <span class="premium-countdown-value">00</span>
                                        <span class="premium-countdown-label">Hrs</span>
                                    </div>
                                    <div class="premium-countdown-item">
                                        <span class="premium-countdown-value">00</span>
                                        <span class="premium-countdown-label">Min</span>
                                    </div>
                                    <div class="premium-countdown-item">
                                        <span class="premium-countdown-value">00</span>
                                        <span class="premium-countdown-label">Sec</span>
                                    </div>
                                </div>
                                <?php } ?>
                                
                                <button type="button" class="premium-flash-cart-btn" onclick="cart.add('<?php echo $product['product_id']; ?>');">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span>Add to Cart</span>
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
/* Premium Flash Deal Module - Unique Styling */
#premium-flash-deal-module.premium-flash-section {
    padding: 0px 0;
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    position: relative;
}

.premium-flash-container {
    max-width: 80%;
    margin: 0 auto;
    padding: 0 20px;
}

.premium-flash-header {
    text-align: left;
    margin-bottom: 40px;
}

.premium-flash-title {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 10px 0;
    letter-spacing: -0.5px;
    text-align: left;
}

.premium-flash-subtitle {
    font-size: 16px;
    color: #666;
    font-weight: 400;
}

.premium-flash-carousel {
    position: relative;
}

.premium-flash-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    margin: 10px 5px;
    border: 1px solid rgba(0,0,0,0.05);
}

.premium-flash-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}

.premium-flash-image-section {
    position: relative;
    padding-top: 100%;
    overflow: hidden;
    background: #f8f9fa;
}

.premium-flash-image-link {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.premium-flash-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.premium-flash-card:hover .premium-flash-image {
    transform: scale(1.1);
}

.premium-flash-discount-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
    color: #fff;
    padding: 8px 14px;
    border-radius: 25px;
    font-weight: 700;
    font-size: 14px;
    z-index: 10;
    box-shadow: 0 4px 10px rgba(255,107,107,0.3);
}

.premium-flash-actions {
    position: absolute;
    top: 15px;
    left: 15px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    opacity: 0;
    transform: translateX(-20px);
    transition: all 0.3s ease;
}

.premium-flash-card:hover .premium-flash-actions {
    opacity: 1;
    transform: translateX(0);
}

.premium-action-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.95);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.premium-action-icon:hover {
    background: #ff6b9d;
    color: #fff;
    transform: scale(1.1);
}

.premium-flash-info-section {
    padding: 20px;
}

.premium-flash-category {
    font-size: 12px;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.premium-flash-product-name {
    font-size: 16px;
    font-weight: 600;
    margin: 0 0 10px 0;
    line-height: 1.4;
    min-height: 44px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.premium-flash-product-name a {
    color: #333;
    text-decoration: none;
    transition: color 0.2s;
}

.premium-flash-product-name a:hover {
    color: #ff6b9d;
}

.premium-flash-rating {
    margin-bottom: 12px;
}

.premium-flash-rating i {
    color: #e0e0e0;
    font-size: 14px;
    margin-right: 2px;
}

.premium-flash-rating i.filled {
    color: #ffc107;
}

.premium-flash-pricing {
    margin-bottom: 15px;
}

.premium-flash-price-new {
    font-size: 22px;
    font-weight: 700;
    color: #ff6b9d;
    margin-right: 8px;
}

.premium-flash-price-old {
    font-size: 16px;
    color: #999;
    text-decoration: line-through;
}

.premium-flash-countdown {
    display: flex;
    gap: 8px;
    margin-bottom: 15px;
    justify-content: center;
}

.premium-countdown-item {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    padding: 8px 10px;
    min-width: 50px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(102,126,234,0.3);
}

.premium-countdown-value {
    display: block;
    font-size: 16px;
    font-weight: 700;
    color: #fff;
    line-height: 1;
}

.premium-countdown-label {
    display: block;
    font-size: 9px;
    color: rgba(255,255,255,0.8);
    text-transform: uppercase;
    margin-top: 4px;
    letter-spacing: 0.5px;
}

.premium-flash-cart-btn {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #ff6b9d 0%, #ff8c9f 100%);
    border: none;
    border-radius: 10px;
    color: #fff;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 4px 12px rgba(255,107,157,0.3);
}

.premium-flash-cart-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(255,107,157,0.4);
}

/* Owl Carousel Navigation */
#premium-flash-carousel.owl-carousel .owl-nav {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    transform: translateY(-50%);
    display: flex;
    justify-content: space-between;
    padding: 0 10px;
    pointer-events: none;
}

#premium-flash-carousel.owl-carousel .owl-nav button {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: #fff !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border: none;
    color: #333 !important;
    font-size: 18px;
    transition: all 0.3s ease;
    pointer-events: auto;
}

#premium-flash-carousel.owl-carousel .owl-nav button:hover {
    background: #ff6b9d !important;
    color: #fff !important;
    transform: scale(1.1);
}

/* Responsive */
@media (max-width: 991px) {
    .premium-flash-title {
        font-size: 26px;
    }
    
    .premium-flash-card {
        margin: 10px 3px;
    }
}

@media (max-width: 768px) {
    #premium-flash-deal-module.premium-flash-section {
        padding: 0px 0;
    }
    
    .premium-flash-title {
        font-size: 22px;
    }
    
    .premium-flash-subtitle {
        font-size: 14px;
    }
    
    .premium-flash-image-section {
        padding-top: 100%;
    }
    
    .premium-flash-product-name {
        font-size: 14px;
        min-height: 40px;
    }
    
    .premium-flash-price-new {
        font-size: 18px;
    }
    
    .premium-countdown-item {
        min-width: 45px;
        padding: 6px 8px;
    }
    
    .premium-countdown-value {
        font-size: 14px;
    }
    
    .premium-countdown-label {
        font-size: 8px;
    }
    
    #premium-flash-carousel.owl-carousel .owl-nav button {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .premium-flash-product-name {
        font-size: 13px;
        min-height: 36px;
    }
    
    .premium-flash-price-new {
        font-size: 16px;
    }
    
    .premium-countdown-item {
        min-width: 40px;
        padding: 5px 6px;
    }
    
    .premium-countdown-value {
        font-size: 12px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Initialize Owl Carousel
    if (typeof $.fn.owlCarousel !== 'undefined') {
        $('#premium-flash-carousel').owlCarousel({
            loop: false,
            margin: 20,
            nav: true,
            dots: false,
            autoplay: false,
            responsive: {
                0: {
                    items: 1,
                    margin: 10
                },
                576: {
                    items: 2,
                    margin: 15
                },
                768: {
                    items: 2,
                    margin: 15
                },
                992: {
                    items: 3,
                    margin: 20
                },
                1200: {
                    items: 4,
                    margin: 20
                }
            },
            navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>']
        });
    }
    
    // Initialize countdown timers
    $('.premium-flash-countdown').each(function() {
        var $countdown = $(this);
        var endDate = $countdown.data('end-date');
        if (!endDate) return;
        
        var targetDate = new Date(endDate);
        if (isNaN(targetDate.getTime())) return;
        
        var $items = $countdown.find('.premium-countdown-item');
        
        function updateCountdown() {
            var now = new Date().getTime();
            var distance = targetDate.getTime() - now;
            
            if (distance < 0) {
                $items.eq(0).find('.premium-countdown-value').text('00');
                $items.eq(1).find('.premium-countdown-value').text('00');
                $items.eq(2).find('.premium-countdown-value').text('00');
                $items.eq(3).find('.premium-countdown-value').text('00');
                return;
            }
            
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            $items.eq(0).find('.premium-countdown-value').text(String(days).padStart(2, '0'));
            $items.eq(1).find('.premium-countdown-value').text(String(hours).padStart(2, '0'));
            $items.eq(2).find('.premium-countdown-value').text(String(minutes).padStart(2, '0'));
            $items.eq(3).find('.premium-countdown-value').text(String(seconds).padStart(2, '0'));
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
});
</script>
<?php } ?>