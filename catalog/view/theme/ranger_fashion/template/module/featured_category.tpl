<?php if (isset($categories) && !empty($categories)) { ?>
<section class="category style-7 section-padding featured-category-module" id="featured-category-<?php echo isset($module_id) ? $module_id : 'default'; ?>">
    <div class="container">
        <div class="fc-modern-header">
            <div class="fc-header-left">
                <h3 class="fc-modern-title"><?php echo isset($name) ? strtoupper($name) : 'FEATURED CATEGORIES'; ?></h3>
            </div>
            <div class="fc-header-right">
                <div class="fc-nav-arrows">
                    <button type="button" class="fc-nav-btn fc-nav-prev" aria-label="Previous">
                        <i class="fa fa-chevron-left"></i>
                    </button>
                    <button type="button" class="fc-nav-btn fc-nav-next" aria-label="Next">
                        <i class="fa fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="category-inner fc-carousel owl-carousel" id="fc-carousel-<?php echo isset($module_id) ? $module_id : 'default'; ?>">
            <?php foreach ($categories as $category) { ?>
            <div class="fc-carousel-item">
                <a href="<?php echo $category['href']; ?>">
                    <div class="category-card">
                        <div class="category-info">
                            <p><?php echo $category['name']; ?></p>
                        </div>
                        <div class="category-img">
                            <img src="<?php echo $category['icon']; ?>" alt="<?php echo $category['name']; ?>">
                        </div>
                    </div>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<style>
/* Featured Category Modern Header Style - New Classes (fc- prefix) */
.featured-category-module .fc-modern-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #f5f5f5;
    padding: 15px 20px;
    margin-bottom: 20px;
    border-bottom: 1px solid #e0e0e0;
}

.fc-header-left {
    flex: 1;
}

.fc-modern-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    text-transform: uppercase;
    margin: 0;
    padding: 0;
    letter-spacing: 0.5px;
}

.fc-header-right {
    flex-shrink: 0;
}

.fc-nav-arrows {
    display: flex;
    gap: 8px;
}

.fc-nav-btn {
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
    border-radius: 2px;
    font-size: 14px;
    padding: 0;
}

.fc-nav-btn:hover {
    background: #f0f0f0;
    border-color: #ccc;
}

.fc-nav-btn:active {
    transform: scale(0.95);
}

.fc-carousel-item {
    padding: 0 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .featured-category-module .fc-modern-header {
        padding: 12px 15px;
    }
    
    .fc-modern-title {
        font-size: 14px;
    }
    
    .fc-nav-btn {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .fc-modern-title {
        font-size: 13px;
    }
    
    .fc-nav-btn {
        width: 30px;
        height: 30px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    var carouselId = '#fc-carousel-<?php echo isset($module_id) ? $module_id : 'default'; ?>';
    var $carousel = $(carouselId);
    var $prevBtn = $('.fc-nav-prev');
    var $nextBtn = $('.fc-nav-next');
    
    if ($carousel.length && typeof $.fn.owlCarousel !== 'undefined') {
        // Initialize Owl Carousel with automatic sliding
        $carousel.owlCarousel({
            loop: true,
            margin: 15,
            nav: false,
            dots: false,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            autoplaySpeed: 800,
            smartSpeed: 600,
            responsive: {
                0: {
                    items: 2,
                    margin: 10
                },
                576: {
                    items: 3,
                    margin: 12
                },
                768: {
                    items: 4,
                    margin: 15
                },
                992: {
                    items: 5,
                    margin: 15
                },
                1200: {
                    items: 6,
                    margin: 15
                }
            }
        });
        
        // Connect navigation buttons
        $prevBtn.on('click', function() {
            $carousel.trigger('prev.owl.carousel');
        });
        
        $nextBtn.on('click', function() {
            $carousel.trigger('next.owl.carousel');
        });
    }
});
</script>
<?php } ?>