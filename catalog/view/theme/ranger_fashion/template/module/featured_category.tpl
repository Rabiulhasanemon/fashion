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
        <div class="fc-carousel-wrapper">
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

.fc-carousel-wrapper {
    overflow: hidden;
    width: 100%;
    position: relative;
}

.fc-carousel-item {
    padding: 0 8px;
}

/* Ensure carousel shows limited items - not all visible */
.featured-category-module .fc-carousel {
    overflow: hidden !important;
    width: 100%;
}

.featured-category-module .fc-carousel .owl-stage-outer {
    overflow: hidden !important;
    width: 100%;
}

.featured-category-module .fc-carousel .owl-stage {
    display: flex;
}

.featured-category-module .fc-carousel .owl-item {
    min-width: 0;
}

/* Category card styling to match image */
.featured-category-module .category-card {
    border: 1px solid #8b6f47;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
    height: 100%;
    display: flex;
    flex-direction: row;
    min-height: 120px;
}

.featured-category-module .category-info {
    flex: 2;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
    background: #fff;
}

.featured-category-module .category-info p {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #2d5016;
    text-align: center;
}

.featured-category-module .category-img {
    flex: 1;
    background: #ff6b9d;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    position: relative;
    overflow: hidden;
}

.featured-category-module .category-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    max-width: 100%;
    max-height: 100%;
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
    
    .featured-category-module .category-card {
        min-height: 100px;
    }
    
    .featured-category-module .category-info p {
        font-size: 14px;
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
    
    .featured-category-module .category-card {
        min-height: 90px;
    }
    
    .featured-category-module .category-info p {
        font-size: 12px;
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
                    items: 1.5,  // Show 1.5 items so next is partially visible
                    margin: 10,
                    slideBy: 1
                },
                576: {
                    items: 2,  // Show 2 items, next partially visible
                    margin: 12,
                    slideBy: 1
                },
                768: {
                    items: 2.5,  // Show 2.5 items
                    margin: 15,
                    slideBy: 1
                },
                992: {
                    items: 3,  // Show 3 items, next partially visible
                    margin: 15,
                    slideBy: 1
                },
                1200: {
                    items: 3.5,  // Show 3.5 items - not all visible
                    margin: 15,
                    slideBy: 1
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