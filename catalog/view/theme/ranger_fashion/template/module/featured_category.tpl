<?php if (isset($categories) && !empty($categories)) { ?>
<section class="category style-7 section-padding fc-module-section" id="fc-module-<?php echo isset($module_id) ? $module_id : time(); ?>">
    <div class="container">
        <div class="fc-modern-header">
            <div class="fc-header-left">
                <h3 class="fc-modern-title"><?php echo isset($name) ? htmlspecialchars($name) : 'Featured Categories'; ?></h3>
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
        <div class="fc-category-carousel owl-carousel" id="fc-carousel-<?php echo isset($module_id) ? $module_id : time(); ?>">
            <?php foreach ($categories as $category) { ?>
            <div class="fc-category-item">
                <a href="<?php echo $category['href']; ?>">
                    <div class="category-card">
                        <div class="category-info">
                            <p><?php echo $category['name']; ?></p>
                        </div>
                        <div class="category-img">
                            <img src="<?php echo $category['icon']; ?>" alt="<?php echo htmlspecialchars($category['name']); ?>">
                        </div>
                    </div>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<style>
/* Featured Category Modern Header - New Classes (No Conflicts) */
.fc-module-section {
    padding: 40px 0;
    background: #fff;
}

.fc-modern-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 30px;
    padding: 15px 0;
    background: #f5f5f5;
    border-bottom: 1px solid #e0e0e0;
    position: relative;
}

.fc-header-left {
    flex: 1;
    padding-left: 20px;
}

.fc-modern-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.fc-header-right {
    flex-shrink: 0;
    padding-right: 20px;
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
    border-radius: 4px;
    font-size: 14px;
}

.fc-nav-btn:hover {
    background: #ff505a;
    color: #fff;
    border-color: #ff505a;
}

.fc-nav-btn:active {
    transform: scale(0.95);
}

/* Category Carousel */
.fc-category-carousel {
    margin-top: 20px;
}

.fc-category-item {
    padding: 0 10px;
}

/* Responsive */
@media (max-width: 768px) {
    .fc-modern-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
        padding: 15px;
    }
    
    .fc-header-left {
        padding-left: 0;
        width: 100%;
    }
    
    .fc-header-right {
        padding-right: 0;
        width: 100%;
        display: flex;
        justify-content: flex-end;
    }
    
    .fc-modern-title {
        font-size: 16px;
    }
    
    .fc-nav-btn {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    var carouselId = '#fc-carousel-<?php echo isset($module_id) ? $module_id : time(); ?>';
    var $carousel = $(carouselId);
    var $prevBtn = $('.fc-nav-prev');
    var $nextBtn = $('.fc-nav-next');
    
    if ($carousel.length && typeof $.fn.owlCarousel !== 'undefined') {
        // Initialize Owl Carousel with AUTOMATIC SLIDING
        $carousel.owlCarousel({
            loop: true,
            margin: 15,
            nav: false, // Use custom navigation
            dots: false,
            autoplay: true, // ENABLE AUTOMATIC SLIDING
            autoplayTimeout: 4000, // 4 seconds between slides
            autoplayHoverPause: true, // Pause on hover
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
        
        // Connect custom navigation buttons
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