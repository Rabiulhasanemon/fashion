<?php if ($products) { ?>
<div class="flash-sell-new-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2 class="h3"><?php echo isset($heading_title) ? $heading_title : 'Flash Deal'; ?></h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-content">
                    <div class="flash-deal-slider owl-carousel">
                        <?php foreach ($products as $product) { ?>
                        <div class="slider-item">
                            <div class="product-card">
                                <div class="product-thumb">
                                    <?php if ($product['discount']) { ?>
                                    <div class="product-badge product-badge2 bg-info">-<?php echo (int)$product['discount']; ?>%</div>
                                    <?php } ?>
                                    <img class="lazy" alt="<?php echo htmlspecialchars($product['name']); ?>" src="<?php echo $product['thumb']; ?>">
                                    <div class="product-button-group">
                                        <a class="product-button wishlist_store" href="javascript:;" title="Wishlist" onclick="wishlist.add('<?php echo $product['product_id']; ?>');">
                                            <i class="icon-heart"></i>
                                        </a>
                                        <a data-target="javascript:;" class="product-button product_compare" href="javascript:;" title="Compare" onclick="compare.add('<?php echo $product['product_id']; ?>');">
                                            <i class="icon-repeat"></i>
                                        </a>
                                        <a class="product-button add_to_single_cart" data-target="<?php echo $product['product_id']; ?>" href="javascript:;" title="To Cart" onclick="cart.add('<?php echo $product['product_id']; ?>');">
                                            <i class="icon-shopping-cart"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="product-card-inner">
                                    <div class="product-card-body">
                                        <?php if ($product['category_name']) { ?>
                                        <div class="product-category">
                                            <a href="javascript:;"><?php echo htmlspecialchars($product['category_name']); ?></a>
                                        </div>
                                        <?php } ?>
                                        <h3 class="product-title">
                                            <a href="<?php echo $product['href']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                                        </h3>
                                        <div class="rating-stars">
                                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                            <i class="fas fa-star<?php echo ($i <= $product['rating']) ? '' : '-o'; ?>"></i>
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
                                        <?php if (!empty($product['end_date'])) { ?>
                                        <div class="countdown countdown-alt mb-3" data-date-time="<?php echo htmlspecialchars($product['end_date']); ?>">
                                            <span>00<small>Days</small></span>
                                            <span>00<small>Hrs</small></span>
                                            <span>00<small>Min</small></span>
                                            <span>00<small>Sec</small></span>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.mt-50 {
    margin-top: 50px !important;
}

.flash-sell-new-section {
    padding: 0;
    background: transparent;
}

.flash-sell-new-section .container {
    max-width: 80%;
    margin: 0 auto;
    padding: 0 20px;
}

@media (max-width: 767px) {
    .flash-sell-new-section .container {
        max-width: 100%;
        padding: 0 15px;
    }
}

.flash-sell-new-section .section-title {
    border-bottom: 2px solid rgba(0, 0, 0, 0.06);
    padding-bottom: 0;
    margin-bottom: 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.flash-sell-new-section .section-title h2.h3 {
    padding-bottom: 12px;
    margin-bottom: 0;
    font-weight: 600;
    font-size: 24px;
    position: relative;
    color: #232323;
}

.flash-sell-new-section .section-title h2.h3::before {
    position: absolute;
    content: "";
    height: 2px;
    width: 100%;
    bottom: -2px;
    left: 0;
    background: #377dff;
}

.flash-sell-new-section .main-content {
    width: 100%;
}

.flash-sell-new-section .flash-deal-slider {
    position: relative;
}

.flash-sell-new-section .product-card {
    display: flex;
    position: relative;
    width: 100%;
    height: 250px;
    border-radius: 10px;
    background-color: #fff;
    overflow: visible;
    border: 1px solid #f0f0f0;
    transition: all 0.3s ease;
    margin-bottom: 0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.flash-sell-new-section .product-card:hover {
    border-color: #FF6A00;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    transform: translateY(-4px);
}

.flash-sell-new-section .product-card .product-thumb {
    width: 250px;
    height: 250px;
    flex-shrink: 0;
    display: block;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    overflow: hidden;
    position: relative;
}

.flash-sell-new-section .product-card .product-thumb > img {
    display: block;
    width: 100%;
    height: 34%;
    object-fit: contain !important;
    object-position: center;
    background: #fff;
    transform: scale(1);
    transition: 0.3s linear;
}

/* Responsive image height for different screen sizes */
@media (min-width: 1200px) {
    .flash-sell-new-section .product-card .product-thumb > img {
        height: 34%;
    }
}

@media (min-width: 992px) and (max-width: 1199px) {
    .flash-sell-new-section .product-card .product-thumb > img {
        height: 35%;
    }
}

@media (min-width: 768px) and (max-width: 991px) {
    .flash-sell-new-section .product-card .product-thumb > img {
        height: 40%;
    }
}

@media (min-width: 576px) and (max-width: 767px) {
    .flash-sell-new-section .product-card .product-thumb > img {
        height: 45%;
    }
}

@media (max-width: 575px) {
    .flash-sell-new-section .product-card .product-thumb > img {
        height: 50%;
    }
}

.flash-sell-new-section .product-card:hover .product-thumb > img {
    transform: scale(1.1);
}

.flash-sell-new-section .product-card .product-card-inner {
    flex: 1;
    display: flex;
    align-items: center;
}

.flash-sell-new-section .product-card .product-card-body {
    padding: 15px 15px 10px;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.flash-sell-new-section .product-card .product-category {
    width: 100%;
    margin-bottom: 6px;
    font-size: 13px;
}

.flash-sell-new-section .product-card .product-category > a {
    transition: color 0.2s;
    color: #999;
    text-decoration: none;
}

.flash-sell-new-section .product-card .product-title {
    margin-bottom: 5px;
    font-size: 16px;
    font-weight: 400;
    line-height: 1.3;
}

.flash-sell-new-section .product-card .product-title > a {
    transition: color 0.3s;
    color: #232323;
    text-decoration: none;
    font-size: 16px;
    line-height: 20px;
    height: auto;
    max-height: 40px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    font-weight: 500;
}

.flash-sell-new-section .product-card .product-title > a:hover {
    color: #FF6A00;
}

.flash-sell-new-section .product-card .rating-stars {
    display: block;
    margin-bottom: 5px;
}

.flash-sell-new-section .product-card .rating-stars > i {
    display: inline-block;
    margin-right: 2px;
    color: #c7c7c7;
    font-size: 12px;
}

.flash-sell-new-section .product-card .rating-stars > i.fa-star {
    color: #ffa500;
}

.flash-sell-new-section .product-card .product-price {
    display: inline-block;
    margin-bottom: 8px;
    font-size: 15px;
    font-weight: 600;
    text-align: left;
    color: #377dff;
    line-height: 1.2;
}

.flash-sell-new-section .product-card .product-price > del {
    margin-right: 5px;
    color: #999;
    font-weight: 400;
    font-size: 14px;
}

.flash-sell-new-section .product-card .product-badge {
    position: absolute;
    top: 15px;
    left: 0;
    height: 24px;
    padding: 0 12px 0 10px;
    border-radius: 0 9px 30px 0;
    color: #fff;
    font-size: 12px;
    font-weight: 400;
    line-height: 24px;
    z-index: 9;
    background: #377dff;
}

.flash-sell-new-section .product-card .product-badge.product-badge2 {
    left: auto;
    right: 0;
    border-radius: 9px 0 0 30px;
    padding: 0 10px 0 12px;
    background: #daa520 !important;
}

.flash-sell-new-section .product-card .product-button-group {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    bottom: 15px;
    width: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    opacity: 0;
    visibility: hidden;
    z-index: 15;
    transition: all 0.3s ease;
    pointer-events: none;
}

.flash-sell-new-section .product-card:hover .product-button-group {
    bottom: 15px;
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}

.flash-sell-new-section .product-card .product-button-group .product-button {
    height: 40px;
    width: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    padding: 0;
    text-align: center;
    text-decoration: none;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    margin: 0;
    background: #FF6A00 !important;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    z-index: 16;
}

.flash-sell-new-section .product-card .product-button-group .product-button:hover {
    background: #ff8c00 !important;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(255, 106, 0, 0.4);
}

.flash-sell-new-section .product-card .product-button-group .product-button > i {
    font-size: 16px;
    color: #ffffff;
    line-height: 1;
    display: block;
}

.flash-sell-new-section .product-card .countdown {
    display: block;
    margin-top: 5px;
    margin-bottom: 0;
}

.flash-sell-new-section .product-card .countdown span {
    display: inline-block;
    min-width: 50px;
    border-radius: 4px;
    margin-right: 8px;
    background: #377dff;
    color: #fff;
    text-align: center;
    padding: 3px 0 0;
    box-shadow: 0 0 7px 0 #00000012;
}

.flash-sell-new-section .product-card .countdown span small {
    display: block;
    background: #fff;
    color: #232323;
    margin-top: 1px;
    padding: 3px 0;
    font-size: 11px;
}

.flash-sell-new-section .product-card .countdown span {
    font-size: 14px;
    font-weight: 600;
}

/* Owl Carousel Navigation */
.flash-sell-new-section .flash-deal-slider .owl-nav {
    position: absolute;
    top: -18px;
    right: 0;
    display: flex;
    gap: 5px;
}

.flash-sell-new-section .flash-deal-slider .owl-nav div {
    width: 26px;
    height: 26px;
    line-height: 26px;
    border: 0;
    border-radius: 50px;
    box-shadow: 1px 1px 4px 0 rgba(0, 0, 0, 0.13);
    background: #fff;
    color: #505050;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s linear;
    opacity: 1 !important;
}

.flash-sell-new-section .flash-deal-slider .owl-nav div.owl-prev {
    right: 33px;
    left: auto;
}

.flash-sell-new-section .flash-deal-slider .owl-nav div.owl-next {
    right: 0;
}

.flash-sell-new-section .flash-deal-slider .owl-nav div:hover {
    background: #FF6A00;
    color: #fff;
}

.flash-sell-new-section .flash-deal-slider .owl-nav div.disabled {
    background: 0 0;
    box-shadow: unset;
    display: none;
}

/* Responsive - Updated to work with percentage height */
/* Premium Tablet Design */
@media (max-width: 991px) {
    .flash-sell-new-section .product-card {
        height: 200px;
        padding: 8px;
        border-radius: 8px;
    }
    
    .flash-sell-new-section .product-card .product-thumb {
        width: 180px;
        height: 200px;
    }
    
    .flash-sell-new-section .product-card .product-thumb > img {
        height: 38%;
    }
    
    .flash-sell-new-section .product-card .product-card-body {
        padding: 10px 8px;
    }
    
    .flash-sell-new-section .product-card .product-title > a {
        font-size: 13px;
        line-height: 1.3;
        height: auto;
        min-height: 32px;
        -webkit-line-clamp: 2;
    }
    
    .flash-sell-new-section .product-card .product-price {
        font-size: 14px;
        margin-bottom: 5px;
    }
    
    .flash-sell-new-section .product-card .product-category {
        font-size: 11px;
        margin-bottom: 4px;
    }
    
    .flash-sell-new-section .product-card .countdown span {
        min-width: 35px;
        font-size: 11px;
        padding: 4px 6px;
    }
    
    .flash-sell-new-section .product-card .countdown span small {
        font-size: 9px;
    }
}

/* Premium Mobile Design - Full Image Display & Perfect Alignment */
@media (max-width: 767px) {
    .flash-sell-new-section {
        padding: 10px 8px !important;
    }
    
    .flash-sell-new-section .container {
        padding: 0 8px !important;
    }
    
    .flash-sell-new-section .product-card {
        flex-direction: column;
        height: auto;
        min-height: 260px;
        padding: 8px;
        border-radius: 8px;
        margin: 0 4px;
        display: flex;
        flex-direction: column;
        align-items: stretch;
    }
    
    .flash-sell-new-section .product-card .product-thumb {
        width: 100%;
        height: 140px;
        min-height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border-radius: 6px;
        overflow: hidden;
        margin-bottom: 8px;
    }
    
    .flash-sell-new-section .product-card .product-thumb > img {
        width: 100%;
        height: 100%;
        object-fit: contain !important;
        object-position: center;
        max-width: 100%;
        max-height: 100%;
        display: block;
    }
    
    .flash-sell-new-section .product-card .product-card-inner {
        width: 100%;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    
    .flash-sell-new-section .product-card .product-card-body {
        padding: 0;
        display: flex;
        flex-direction: column;
        flex: 1;
        justify-content: space-between;
    }
    
    .flash-sell-new-section .product-card .product-category {
        font-size: 10px;
        margin-bottom: 4px;
        text-align: center;
        color: #666;
    }
    
    .flash-sell-new-section .product-card .product-title {
        margin-bottom: 6px;
        text-align: center;
    }
    
    .flash-sell-new-section .product-card .product-title > a {
        font-size: 12px;
        line-height: 1.3;
        height: auto;
        min-height: 32px;
        -webkit-line-clamp: 2;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-align: center;
        color: #333;
        font-weight: 500;
    }
    
    .flash-sell-new-section .product-card .product-price {
        font-size: 15px;
        margin-bottom: 8px;
        text-align: center;
        font-weight: 600;
        color: #FF6A00;
    }
    
    .flash-sell-new-section .product-card .product-price del {
        font-size: 12px;
        color: #999;
        margin-right: 6px;
    }
    
    .flash-sell-new-section .product-card .countdown {
        margin-top: 0;
        margin-bottom: 8px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px;
        flex-wrap: wrap;
    }
    
    .flash-sell-new-section .product-card .countdown span {
        min-width: 38px;
        font-size: 10px;
        padding: 5px 6px;
        background: #f5f5f5;
        border-radius: 4px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #333;
    }
    
    .flash-sell-new-section .product-card .countdown span small {
        font-size: 8px;
        margin-top: 2px;
        color: #666;
        font-weight: 400;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .flash-sell-new-section .product-card .product-button-group {
        position: static;
        transform: none;
        bottom: auto;
        opacity: 1;
        visibility: visible;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
        padding-top: 8px;
        border-top: 1px solid #f0f0f0;
    }
    
    .flash-sell-new-section .product-card .product-button-group .product-button {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .flash-sell-new-section .product-card .product-button-group .product-button i {
        font-size: 14px;
    }
    
    .flash-sell-new-section .flash-deal-slider .owl-nav {
        top: auto;
        bottom: 50%;
        transform: translateY(50%);
    }
    
    .flash-sell-new-section .flash-deal-slider .owl-nav div.owl-prev {
        left: -10px;
        right: auto;
    }
    
    .flash-sell-new-section .flash-deal-slider .owl-nav div.owl-next {
        right: -10px;
    }
}

@media (max-width: 575px) {
    .flash-sell-new-section .section-title h2.h3 {
        font-size: 18px;
        margin-bottom: 12px;
    }
    
    .flash-sell-new-section .product-card {
        min-height: 240px;
        padding: 6px;
        margin: 0 3px;
    }
    
    .flash-sell-new-section .product-card .product-thumb {
        height: 130px;
        min-height: 130px;
        margin-bottom: 6px;
    }
    
    .flash-sell-new-section .product-card .product-title > a {
        font-size: 11px;
        min-height: 28px;
    }
    
    .flash-sell-new-section .product-card .product-price {
        font-size: 14px;
        margin-bottom: 6px;
    }
    
    .flash-sell-new-section .product-card .countdown {
        margin-bottom: 6px;
        gap: 4px;
    }
    
    .flash-sell-new-section .product-card .countdown span {
        min-width: 35px;
        font-size: 9px;
        padding: 4px 5px;
    }
    
    .flash-sell-new-section .product-card .countdown span small {
        font-size: 7px;
    }
    
    .flash-sell-new-section .product-card .product-button-group {
        margin-top: 4px;
        padding-top: 6px;
        gap: 5px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    if (typeof $.fn.owlCarousel !== 'undefined') {
        $('.flash-sell-new-section .flash-deal-slider').owlCarousel({
            loop: false,
            margin: 15,
            nav: false,
            dots: false,
            responsive: {
                0: {
                    items: 1
                },
                576: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 2
                },
                1200: {
                    items: 2
                }
            },
            navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>']
        });
    }
    
    // Initialize countdown timers
    $('.flash-sell-new-section .countdown').each(function() {
        var $countdown = $(this);
        var dateTime = $countdown.data('date-time');
        if (!dateTime) return;
        
        // Parse date
        var endDate = new Date(dateTime);
        if (isNaN(endDate.getTime())) return;
        
        var $spans = $countdown.find('span');
        if ($spans.length !== 4) return;
        
        function updateCountdown() {
            var now = new Date().getTime();
            var distance = endDate.getTime() - now;
            
            if (distance < 0) {
                $spans.eq(0).html('00<small>Days</small>');
                $spans.eq(1).html('00<small>Hrs</small>');
                $spans.eq(2).html('00<small>Min</small>');
                $spans.eq(3).html('00<small>Sec</small>');
                return;
            }
            
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            $spans.eq(0).html(String(days).padStart(2, '0') + '<small>Days</small>');
            $spans.eq(1).html(String(hours).padStart(2, '0') + '<small>Hrs</small>');
            $spans.eq(2).html(String(minutes).padStart(2, '0') + '<small>Min</small>');
            $spans.eq(3).html(String(seconds).padStart(2, '0') + '<small>Sec</small>');
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
});
</script>
<?php } ?>
