<style>
/* Clean Image-Only Slideshow - Mobile Responsive */
.fullscreen-slideshow {
    position: relative;
    width: 100%;
    height: 600px;
    overflow: hidden;
    margin: 0;
    padding: 0;
    background: #000;
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
}

.fullscreen-slideshow .slide-item.active {
    opacity: 1;
    visibility: visible;
    z-index: 1;
}

.fullscreen-slideshow .slide-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transform: scale(1.05);
    transition: transform 6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.fullscreen-slideshow .slide-item.active .slide-image {
    transform: scale(1);
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

/* Mobile Responsive Styles */
@media (max-width: 992px) {
    .fullscreen-slideshow {
        height: 500px;
    }
}

@media (max-width: 768px) {
    .fullscreen-slideshow {
        height: 400px;
    }
    
    .fullscreen-slideshow .slide-nav {
        bottom: 20px;
        right: 50%;
        transform: translateX(50%);
        width: auto;
        justify-content: center;
    }
    
    .fullscreen-slideshow .nav-btn {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    .fullscreen-slideshow .slide-dot {
        width: 10px;
        height: 10px;
    }
}

@media (max-width: 480px) {
    .fullscreen-slideshow {
        height: 300px;
    }
    
    .fullscreen-slideshow .slide-nav {
        bottom: 15px;
    }
    
    .fullscreen-slideshow .nav-btn {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }
    
    .fullscreen-slideshow .slide-dot {
        width: 8px;
        height: 8px;
    }
}
</style>

<div class="fullscreen-slideshow" id="fullscreen-slideshow">
    <?php foreach ($banners as $index => $banner) { ?>
    <div class="slide-item <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>">
        <?php if ($banner['link']) { ?>
        <a href="<?php echo $banner['link']; ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2; display: block;"></a>
        <?php } ?>
        <img src="<?php echo $banner['image']; ?>" alt="<?php echo isset($banner['title']) ? htmlspecialchars($banner['title']) : 'Slide'; ?>" class="slide-image" />
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
</script>
