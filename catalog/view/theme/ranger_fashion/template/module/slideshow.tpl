<style>
/* Full Screen Slideshow Styles */
.fullscreen-slideshow {
    position: relative;
    width: 100%;
    height: 600px;
    overflow: hidden;
    margin: 0;
    padding: 0;
}

.fullscreen-slideshow .slide-item {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 0.8s ease-in-out;
    display: flex;
    align-items: center;
}

.fullscreen-slideshow .slide-item.active {
    opacity: 1;
    z-index: 1;
}

.fullscreen-slideshow .slide-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
}

.fullscreen-slideshow .slide-content {
    position: relative;
    z-index: 2;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 40px;
    width: 100%;
    display: flex;
    align-items: center;
    height: 100%;
}

.fullscreen-slideshow .content-left {
    flex: 0 0 45%;
    max-width: 500px;
    color: #fff;
    padding-right: 40px;
}

.fullscreen-slideshow .content-right {
    flex: 1;
    position: relative;
    height: 100%;
}

.fullscreen-slideshow .main-title {
    font-size: 48px;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 20px;
    color: #fff;
    font-family: 'Jost', sans-serif;
}

.fullscreen-slideshow .subtitle {
    font-size: 18px;
    line-height: 1.6;
    margin-bottom: 30px;
    color: #fff;
    opacity: 0.95;
}

.fullscreen-slideshow .promo-section {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 30px;
}

.fullscreen-slideshow .promo-text {
    font-size: 16px;
    color: #fff;
    margin-bottom: 15px;
    font-weight: 500;
}

.fullscreen-slideshow .promo-code-wrapper {
    display: flex;
    gap: 10px;
    align-items: center;
}

.fullscreen-slideshow .promo-input {
    flex: 1;
    padding: 12px 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.9);
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

.fullscreen-slideshow .copy-btn {
    padding: 12px 24px;
    background: #fff;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    color: #333;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.fullscreen-slideshow .copy-btn:hover {
    background: #f0f0f0;
    transform: translateY(-2px);
}

.fullscreen-slideshow .copy-btn i {
    font-size: 18px;
}

.fullscreen-slideshow .shop-btn {
    padding: 16px 40px;
    background: #61baf3;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-block;
}

.fullscreen-slideshow .shop-btn:hover {
    background: #4fa8d8;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(97, 186, 243, 0.4);
}

/* Navigation Controls */
.fullscreen-slideshow .slide-nav {
    position: absolute;
    bottom: 30px;
    right: 40px;
    z-index: 10;
    display: flex;
    align-items: center;
    gap: 15px;
}

.fullscreen-slideshow .slide-dots {
    display: flex;
    gap: 8px;
    align-items: center;
}

.fullscreen-slideshow .slide-dot {
    width: 40px;
    height: 3px;
    background: rgba(255, 255, 255, 0.4);
    border-radius: 2px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.fullscreen-slideshow .slide-dot.active {
    width: 50px;
    background: #fff;
    height: 4px;
}

.fullscreen-slideshow .nav-btn {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 1px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.9);
    color: #333;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.fullscreen-slideshow .nav-btn:hover {
    background: #fff;
    border-color: #fff;
    transform: scale(1.1);
}

/* Mobile Styles */
@media (max-width: 768px) {
    .fullscreen-slideshow {
        height: 500px;
    }
    
    .fullscreen-slideshow .slide-content {
        padding: 0 20px;
        flex-direction: column;
        justify-content: center;
    }
    
    .fullscreen-slideshow .content-left {
        flex: 1;
        max-width: 100%;
        padding-right: 0;
        text-align: center;
    }
    
    .fullscreen-slideshow .content-right {
        display: none;
    }
    
    .fullscreen-slideshow .main-title {
        font-size: 28px;
        margin-bottom: 15px;
    }
    
    .fullscreen-slideshow .subtitle {
        font-size: 14px;
        margin-bottom: 20px;
    }
    
    .fullscreen-slideshow .promo-section {
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .fullscreen-slideshow .promo-text {
        font-size: 14px;
        margin-bottom: 12px;
    }
    
    .fullscreen-slideshow .promo-code-wrapper {
        flex-direction: column;
        gap: 10px;
    }
    
    .fullscreen-slideshow .promo-input {
        width: 100%;
    }
    
    .fullscreen-slideshow .copy-btn {
        width: 100%;
        justify-content: center;
    }
    
    .fullscreen-slideshow .shop-btn {
        width: 100%;
        padding: 14px 30px;
        font-size: 16px;
    }
    
    .fullscreen-slideshow .slide-nav {
        bottom: 20px;
        right: 20px;
        gap: 10px;
    }
    
    .fullscreen-slideshow .slide-dot {
        width: 30px;
        height: 2px;
    }
    
    .fullscreen-slideshow .slide-dot.active {
        width: 35px;
        height: 3px;
    }
    
    .fullscreen-slideshow .nav-btn {
        width: 36px;
        height: 36px;
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .fullscreen-slideshow {
        height: 450px;
    }
    
    .fullscreen-slideshow .main-title {
        font-size: 24px;
    }
    
    .fullscreen-slideshow .subtitle {
        font-size: 13px;
    }
}
</style>

<div class="fullscreen-slideshow" id="fullscreen-slideshow">
    <?php foreach ($banners as $index => $banner) { ?>
    <div class="slide-item <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>">
        <?php if ($banner['link']) { ?>
        <a href="<?php echo $banner['link']; ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;"></a>
        <?php } ?>
        <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="slide-image" />
        <div class="slide-content">
            <div class="content-left">
                <h1 class="main-title"><?php echo !empty($banner['title']) ? htmlspecialchars($banner['title']) : 'Your Moment of Calm, Your Skin\'s Renewal'; ?></h1>
                <p class="subtitle"><?php echo !empty($banner['description']) ? htmlspecialchars($banner['description']) : 'A soothing touch of moisture to restore, protect, and enhance your skin\'s natural glow.'; ?></p>
                
                <div class="promo-section">
                    <p class="promo-text">Use Promo Code MEGA50 & Get an Extra 10% Off Your First Order!</p>
                    <div class="promo-code-wrapper">
                        <input type="text" class="promo-input" value="MEGA50" readonly id="promo-code-<?php echo $index; ?>">
                        <button type="button" class="copy-btn" onclick="copyPromoCode('promo-code-<?php echo $index; ?>', this)">
                            <i class="fas fa-copy"></i>
                            <span>Copy Code</span>
                        </button>
                    </div>
                </div>
                
                <?php if ($banner['link']) { ?>
                <a href="<?php echo $banner['link']; ?>" class="shop-btn">Shop Now</a>
                <?php } else { ?>
                <button type="button" class="shop-btn" onclick="window.location.href='<?php echo isset($base) ? $base : 'index.php?route=common/home'; ?>'">Shop Now</button>
                <?php } ?>
            </div>
            <div class="content-right">
                <!-- Image is in background -->
            </div>
        </div>
    </div>
    <?php } ?>
    
    <!-- Navigation Controls -->
    <div class="slide-nav">
        <div class="slide-dots">
            <?php foreach ($banners as $index => $banner) { ?>
            <span class="slide-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>"></span>
            <?php } ?>
        </div>
        <button type="button" class="nav-btn prev-btn" aria-label="Previous">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button type="button" class="nav-btn next-btn" aria-label="Next">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>

<script>
(function() {
    const slideshow = document.getElementById('fullscreen-slideshow');
    if (!slideshow) return;
    
    const slides = slideshow.querySelectorAll('.slide-item');
    const dots = slideshow.querySelectorAll('.slide-dot');
    const prevBtn = slideshow.querySelector('.prev-btn');
    const nextBtn = slideshow.querySelector('.next-btn');
    
    let currentSlide = 0;
    let slideInterval = null;
    
    function showSlide(index) {
        // Remove active class from all slides and dots
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Add active class to current slide and dot
        if (slides[index]) {
            slides[index].classList.add('active');
        }
        if (dots[index]) {
            dots[index].classList.add('active');
        }
        
        currentSlide = index;
    }
    
    function nextSlide() {
        const next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }
    
    function prevSlide() {
        const prev = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(prev);
    }
    
    function startAutoSlide() {
        stopAutoSlide();
        slideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
    }
    
    function stopAutoSlide() {
        if (slideInterval) {
            clearInterval(slideInterval);
            slideInterval = null;
        }
    }
    
    // Event listeners
    if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            stopAutoSlide();
            nextSlide();
            startAutoSlide();
        });
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            stopAutoSlide();
            prevSlide();
            startAutoSlide();
        });
    }
    
    dots.forEach((dot, index) => {
        dot.addEventListener('click', function(e) {
            e.stopPropagation();
            stopAutoSlide();
            showSlide(index);
            startAutoSlide();
        });
    });
    
    // Pause on hover
    slideshow.addEventListener('mouseenter', stopAutoSlide);
    slideshow.addEventListener('mouseleave', startAutoSlide);
    
    // Touch swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    slideshow.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    
    slideshow.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                // Swipe left - next slide
                stopAutoSlide();
                nextSlide();
                startAutoSlide();
            } else {
                // Swipe right - previous slide
                stopAutoSlide();
                prevSlide();
                startAutoSlide();
            }
        }
    }
    
    // Start auto-slide
    startAutoSlide();
})();

// Copy promo code function
function copyPromoCode(inputId, button) {
    const input = document.getElementById(inputId);
    if (input) {
        input.select();
        input.setSelectionRange(0, 99999);
        
        try {
            document.execCommand('copy');
            const originalText = button.querySelector('span').textContent;
            button.querySelector('span').textContent = 'Copied!';
            button.style.background = '#4caf50';
            button.style.color = '#fff';
            
            setTimeout(function() {
                button.querySelector('span').textContent = originalText;
                button.style.background = '';
                button.style.color = '';
            }, 2000);
        } catch (err) {
            console.error('Failed to copy:', err);
        }
    }
}
</script>
