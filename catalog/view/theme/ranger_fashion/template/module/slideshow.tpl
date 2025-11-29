<style>
/* Enhanced Premium Slideshow Styles */
.fullscreen-slideshow {
    position: relative;
    width: 100%;
    height: 650px; /* Increased height for better impact */
    overflow: hidden;
    margin: 0;
    padding: 0;
    background: #000; /* Fallback background */
}

.fullscreen-slideshow .slide-item {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    visibility: hidden;
    transition: opacity 1s cubic-bezier(0.4, 0, 0.2, 1), visibility 1s linear;
    display: flex;
    align-items: center;
}

.fullscreen-slideshow .slide-item.active {
    opacity: 1;
    visibility: visible;
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
    transform: scale(1.1);
    transition: transform 6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.fullscreen-slideshow .slide-item.active .slide-image {
    transform: scale(1);
}

.fullscreen-slideshow .slide-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to right, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0) 100%);
    z-index: 1;
}

.fullscreen-slideshow .slide-content {
    position: relative;
    z-index: 2;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 40px;
    width: 100%;
    display: flex;
    align-items: center;
    height: 100%;
}

.fullscreen-slideshow .content-left {
    flex: 0 0 50%;
    max-width: 600px;
    color: #fff;
    padding-right: 60px;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    transition-delay: 0.3s;
}

.fullscreen-slideshow .slide-item.active .content-left {
    opacity: 1;
    transform: translateY(0);
}

.fullscreen-slideshow .badge {
    display: inline-block;
    padding: 6px 12px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 20px;
    color: #fff;
}

.fullscreen-slideshow .main-title {
    font-size: 56px;
    font-weight: 700;
    line-height: 1.1;
    margin-bottom: 20px;
    color: #fff;
    font-family: 'Jost', sans-serif;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.fullscreen-slideshow .subtitle {
    font-size: 20px;
    line-height: 1.6;
    margin-bottom: 40px;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 300;
    max-width: 90%;
}

.fullscreen-slideshow .promo-section {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 25px;
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    margin-bottom: 35px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.fullscreen-slideshow .promo-text {
    font-size: 16px;
    color: #fff;
    margin-bottom: 15px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
}

.fullscreen-slideshow .promo-text i {
    color: #ffd700;
}

.fullscreen-slideshow .promo-code-wrapper {
    display: flex;
    gap: 10px;
    align-items: center;
}

.fullscreen-slideshow .promo-input {
    flex: 1;
    padding: 14px 20px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 10px;
    background: rgba(0, 0, 0, 0.3);
    font-size: 16px;
    font-weight: 600;
    color: #fff;
    letter-spacing: 1px;
    text-align: center;
    font-family: monospace;
}

.fullscreen-slideshow .copy-btn {
    padding: 14px 28px;
    background: #fff;
    border: none;
    border-radius: 10px;
    color: #333;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.fullscreen-slideshow .copy-btn:hover {
    background: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.fullscreen-slideshow .shop-btn {
    padding: 18px 45px;
    background: linear-gradient(45deg, #ff6b6b, #ff8e8e);
    color: #fff;
    border: none;
    border-radius: 50px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
}

.fullscreen-slideshow .shop-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(255, 107, 107, 0.5);
    background: linear-gradient(45deg, #ff5252, #ff7676);
}

.fullscreen-slideshow .shop-btn i {
    transition: transform 0.3s ease;
}

.fullscreen-slideshow .shop-btn:hover i {
    transform: translateX(5px);
}

/* Navigation Controls */
.fullscreen-slideshow .slide-nav {
    position: absolute;
    bottom: 40px;
    right: 60px;
    z-index: 10;
    display: flex;
    align-items: center;
    gap: 20px;
}

.fullscreen-slideshow .slide-dots {
    display: flex;
    gap: 10px;
    align-items: center;
}

.fullscreen-slideshow .slide-dot {
    width: 12px;
    height: 12px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.fullscreen-slideshow .slide-dot.active {
    background: #fff;
    transform: scale(1.2);
    border-color: rgba(255,255,255,0.5);
}

.fullscreen-slideshow .nav-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(5px);
    color: #fff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    transition: all 0.3s ease;
}

.fullscreen-slideshow .nav-btn:hover {
    background: #fff;
    color: #333;
    transform: scale(1.1);
}

/* Mobile Styles */
@media (max-width: 992px) {
    .fullscreen-slideshow .main-title {
        font-size: 42px;
    }
    .fullscreen-slideshow .content-left {
        flex: 0 0 70%;
        max-width: 100%;
    }
}

@media (max-width: 768px) {
    /* Mobile-friendly slideshow - simplified version */
    .fullscreen-slideshow {
        height: 300px;
        margin: 0;
        padding: 0;
    }
    
    .fullscreen-slideshow .slide-content {
        padding: 0 20px;
        justify-content: center;
        text-align: center;
    }
    
    .fullscreen-slideshow .content-left {
        flex: 1;
        padding-right: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        max-width: 100%;
    }
    
    .fullscreen-slideshow .badge {
        margin-bottom: 10px;
        font-size: 10px;
        padding: 4px 8px;
    }
    
    .fullscreen-slideshow .main-title {
        font-size: 24px;
        margin-bottom: 10px;
        line-height: 1.2;
    }
    
    .fullscreen-slideshow .subtitle {
        font-size: 13px;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .fullscreen-slideshow .promo-section {
        padding: 12px;
        margin-bottom: 15px;
        width: 100%;
        max-width: 100%;
    }
    
    .fullscreen-slideshow .promo-text {
        font-size: 12px;
        margin-bottom: 10px;
    }
    
    .fullscreen-slideshow .promo-code-wrapper {
        flex-direction: column;
        width: 100%;
        gap: 8px;
    }
    
    .fullscreen-slideshow .promo-input,
    .fullscreen-slideshow .copy-btn {
        width: 100%;
        padding: 10px;
        font-size: 13px;
    }
    
    .fullscreen-slideshow .shop-btn {
        width: auto;
        padding: 12px 24px;
        font-size: 14px;
    }
    
    .fullscreen-slideshow .slide-nav {
        bottom: 15px;
        right: 50%;
        transform: translateX(50%);
        width: 100%;
        justify-content: center;
    }
    
    .fullscreen-slideshow .nav-btn {
        display: none; /* Hide arrows on mobile */
    }
    
    .fullscreen-slideshow .slide-dots {
        gap: 8px;
    }
    
    .fullscreen-slideshow .slide-dot {
        width: 10px;
        height: 10px;
    }
}

@media (max-width: 480px) {
    .fullscreen-slideshow {
        height: 250px;
    }
    
    .fullscreen-slideshow .main-title {
        font-size: 20px;
    }
    
    .fullscreen-slideshow .subtitle {
        font-size: 12px;
        -webkit-line-clamp: 1;
    }
    
    .fullscreen-slideshow .promo-section {
        padding: 10px;
        margin-bottom: 12px;
    }
    
    .fullscreen-slideshow .shop-btn {
        padding: 10px 20px;
        font-size: 13px;
    }
    
    .fullscreen-slideshow .slide-overlay {
        background: rgba(0,0,0,0.5);
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
        <div class="slide-overlay"></div>
        <div class="slide-content">
            <div class="content-left">
                <div class="badge">Premium Collection</div>
                <h1 class="main-title"><?php echo !empty($banner['title']) ? htmlspecialchars($banner['title']) : 'Your Moment of Calm, Your Skin\'s Renewal'; ?></h1>
                <p class="subtitle"><?php echo !empty($banner['description']) ? htmlspecialchars($banner['description']) : 'A soothing touch of moisture to restore, protect, and enhance your skin\'s natural glow.'; ?></p>
                
                <div class="promo-section">
                    <p class="promo-text"><i class="fas fa-tag"></i> Use Promo Code MEGA50 & Get an Extra 10% Off!</p>
                    <div class="promo-code-wrapper">
                        <input type="text" class="promo-input" value="MEGA50" readonly id="promo-code-<?php echo $index; ?>">
                        <button type="button" class="copy-btn" onclick="copyPromoCode('promo-code-<?php echo $index; ?>', this)">
                            <i class="fas fa-copy"></i>
                            <span>Copy</span>
                        </button>
                    </div>
                </div>
                
                <?php if ($banner['link']) { ?>
                <a href="<?php echo $banner['link']; ?>" class="shop-btn">Shop Now <i class="fas fa-arrow-right"></i></a>
                <?php } else { ?>
                <button type="button" class="shop-btn" onclick="window.location.href='<?php echo isset($base) && $base ? $base : 'index.php?route=common/home'; ?>'">Shop Now <i class="fas fa-arrow-right"></i></button>
                <?php } ?>
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
