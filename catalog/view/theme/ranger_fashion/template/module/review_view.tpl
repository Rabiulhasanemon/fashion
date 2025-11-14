<?php if (!empty($reviews)) { ?>
<section class="rv-testimonial-section" style="max-width: 80%; margin: 0 auto; padding: 40px 0;">
  <?php if ($title) { ?>
  <div class="rv-module-heading" style="margin-bottom: 30px; text-align: center;">
    <h2 class="rv-title"><?php echo $title; ?></h2>
  </div>
  <?php } ?>
  
   <div class="rv-container-wrapper">
     <div class="swiper rv-swiper-container" id="rv-testimonial-swiper-<?php echo $module; ?>">
       <div class="swiper-wrapper rv-swiper-wrapper">
         <?php foreach ($reviews as $review) { ?>
         <div class="swiper-slide rv-swiper-slide">
          <div class="rv-testimonial-card">
            <div class="rv-testimonial-content">
              <p class="rv-testimonial-text">
                <?php echo $review['text']; ?>
              </p>
            </div>
            <div class="rv-testimonial-footer">
              <div class="rv-author-avatar">
                <?php if (!empty($review['author_image'])) { ?>
                <img src="<?php echo $review['author_image']; ?>" alt="<?php echo htmlspecialchars($review['author']); ?>">
                <?php } else { ?>
                <div class="rv-avatar-placeholder">
                  <?php echo strtoupper(substr($review['author'], 0, 1)); ?>
                </div>
                <?php } ?>
              </div>
              <div class="rv-author-info">
                <h6 class="rv-author-name"><?php echo htmlspecialchars($review['author']); ?></h6>
                <?php if (!empty($review['designation'])) { ?>
                <span class="rv-author-role"><?php echo htmlspecialchars($review['designation']); ?></span>
                <?php } elseif ($show_product && !empty($review['product_name'])) { ?>
                <span class="rv-author-role"><?php echo htmlspecialchars($review['product_name']); ?></span>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
       <div class="swiper-button-prev rv-swiper-nav rv-nav-prev"></div>
       <div class="swiper-button-next rv-swiper-nav rv-nav-next"></div>
       <div class="swiper-pagination rv-swiper-pagination"></div>
    </div>
  </div>
</section>

<style>
/* Review View Testimonial - Unique Classes to Avoid Conflicts */
.rv-testimonial-section {
  padding: 40px 0;
  position: relative;
}

.rv-container-wrapper {
  position: relative;
  padding: 0 60px;
}

.rv-swiper-container {
  position: relative;
  overflow: hidden;
  padding-bottom: 50px;
}

.rv-swiper-container.swiper {
  position: relative;
  overflow: hidden;
  padding-bottom: 50px;
}

.rv-swiper-wrapper.swiper-wrapper {
  display: flex;
  transition-timing-function: cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.rv-swiper-slide {
  flex-shrink: 0;
  width: 100%;
  padding: 0 10px;
  box-sizing: border-box;
}

.rv-testimonial-card {
  background: #ffffff;
  border: 1px solid #e8e8e8;
  border-radius: 12px;
  padding: 30px;
  height: 100%;
  display: flex;
  flex-direction: column;
  box-shadow: 0 2px 10px rgba(0,0,0,0.06);
  transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.rv-testimonial-card:hover {
  box-shadow: 0 8px 24px rgba(0,0,0,0.12);
  transform: translateY(-4px);
  border-color: #d0d0d0;
}

.rv-testimonial-content {
  flex: 1;
  margin-bottom: 20px;
}

.rv-testimonial-text {
  font-size: 14px;
  line-height: 1.8;
  color: #555;
  margin: 0;
  display: -webkit-box;
  -webkit-line-clamp: 4;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.rv-testimonial-footer {
  display: flex;
  align-items: center;
  gap: 15px;
  padding-top: 20px;
  border-top: 1px solid #f0f0f0;
}

.rv-author-avatar {
  flex-shrink: 0;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  overflow: hidden;
  border: 3px solid #f5f5f5;
  transition: all 0.3s ease;
}

.rv-testimonial-card:hover .rv-author-avatar {
  border-color: var(--primary-color, #007bff);
  transform: scale(1.05);
}

.rv-author-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.rv-avatar-placeholder {
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 24px;
  font-weight: 600;
}

.rv-author-info {
  flex: 1;
}

.rv-author-name {
  margin: 0 0 5px 0;
  font-size: 15px;
  font-weight: 600;
  color: #222;
  line-height: 1.2;
}

.rv-author-role {
  display: block;
  font-size: 12px;
  color: #888;
  font-weight: 400;
}

/* Navigation Arrows */
.rv-swiper-nav.swiper-button-prev,
.rv-swiper-nav.swiper-button-next {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 44px;
  height: 44px;
  background: #ffffff;
  border: 2px solid #e1e1e1;
  border-radius: 50%;
  cursor: pointer;
  z-index: 10;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
  margin-top: 0;
}

.rv-swiper-nav.swiper-button-prev::after,
.rv-swiper-nav.swiper-button-next::after {
  font-family: 'swiper-icons';
  font-size: 18px;
  font-weight: bold;
  color: var(--primary-color, #007bff);
  transition: all 0.3s ease;
}

.rv-nav-prev.swiper-button-prev {
  left: 0;
}

.rv-nav-next.swiper-button-next {
  right: 0;
}

.rv-swiper-nav:hover {
  background: var(--primary-color, #007bff);
  border-color: var(--primary-color, #007bff);
  box-shadow: 0 4px 12px rgba(0,123,255,0.3);
  transform: translateY(-50%) scale(1.1);
}

.rv-swiper-nav:hover::after {
  color: #ffffff;
}

.rv-swiper-nav.swiper-button-disabled {
  opacity: 0.3;
  cursor: not-allowed;
}

/* Pagination */
.rv-swiper-pagination.swiper-pagination {
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  z-index: 10;
  width: auto;
}

.rv-swiper-pagination .swiper-pagination-bullet {
  width: 10px;
  height: 10px;
  background: #ddd;
  border-radius: 50%;
  opacity: 1;
  cursor: pointer;
  transition: all 0.3s ease;
  margin: 0 5px !important;
}

.rv-swiper-pagination .swiper-pagination-bullet-active {
  background: var(--primary-color, #007bff);
  width: 30px;
  border-radius: 5px;
}

/* Responsive */
@media (min-width: 992px) {
  .rv-swiper-slide {
    width: 33.333%;
  }
}

@media (min-width: 768px) and (max-width: 991px) {
  .rv-swiper-slide {
    width: 50%;
  }
  .rv-container-wrapper {
    padding: 0 50px;
  }
}

@media (max-width: 767px) {
  .rv-testimonial-section {
    max-width: 100% !important;
    padding: 20px 15px;
  }
  
  .rv-container-wrapper {
    padding: 0 45px;
  }
  
  .rv-swiper-slide {
    width: 100%;
  }
  
  .rv-swiper-nav {
    width: 36px;
    height: 36px;
  }
  
  .rv-swiper-nav::before {
    width: 6px;
    height: 6px;
  }
  
  .rv-testimonial-card {
    padding: 20px;
  }
  
  .rv-testimonial-text {
    -webkit-line-clamp: 3;
  }
}
</style>

<script>
(function() {
  var moduleId = 'rv-testimonial-swiper-<?php echo $module; ?>';
  var swiperEl = document.getElementById(moduleId);
  
  if (!swiperEl) {
    console.log('RV Slider: Element not found');
    return;
  }
  
  var initCount = 0;
  var maxInitAttempts = 100;
  
  function initRVSlider() {
    initCount++;
    
    // Check if Swiper is loaded
    if (typeof Swiper === 'undefined') {
      if (initCount < maxInitAttempts) {
        setTimeout(initRVSlider, 100);
      } else {
        console.error('RV Slider: Swiper library not found');
      }
      return;
    }
    
    try {
      var reviewCount = <?php echo count($reviews); ?>;
      var enableLoop = reviewCount > 3;
      
      // Initialize Swiper
      var rvSwiper = new Swiper('#' + moduleId, {
        slidesPerView: 1,
        slidesPerGroup: 1,
        spaceBetween: 20,
        loop: enableLoop,
        speed: 600,
        autoplay: {
          delay: 3000,
          disableOnInteraction: false,
          pauseOnMouseEnter: false,
          stopOnLastSlide: false,
        },
        navigation: {
          nextEl: swiperEl.querySelector('.rv-nav-next'),
          prevEl: swiperEl.querySelector('.rv-nav-prev'),
        },
        pagination: {
          el: swiperEl.querySelector('.rv-swiper-pagination'),
          clickable: true,
          dynamicBullets: true,
          dynamicMainBullets: 3,
        },
        breakpoints: {
          576: {
            slidesPerView: 2,
            slidesPerGroup: 1,
            spaceBetween: 20,
          },
          768: {
            slidesPerView: 2,
            slidesPerGroup: 1,
            spaceBetween: 20,
          },
          992: {
            slidesPerView: 3,
            slidesPerGroup: 1,
            spaceBetween: 20,
          },
          1200: {
            slidesPerView: 3,
            slidesPerGroup: 1,
            spaceBetween: 20,
          }
        },
        on: {
          init: function() {
            console.log('RV Slider initialized');
            // Force start autoplay
            if (this.autoplay && this.autoplay.running === false) {
              this.autoplay.start();
            }
          },
          autoplayStart: function() {
            console.log('RV Slider autoplay started');
          }
        }
      });
      
      // Multiple attempts to ensure autoplay starts
      var startAutoplay = function() {
        if (rvSwiper && rvSwiper.autoplay) {
          if (!rvSwiper.autoplay.running) {
            rvSwiper.autoplay.start();
          }
        }
      };
      
      // Try to start autoplay multiple times
      setTimeout(startAutoplay, 100);
      setTimeout(startAutoplay, 500);
      setTimeout(startAutoplay, 1000);
      setTimeout(startAutoplay, 2000);
      
      // Store instance globally for debugging
      window['rvSwiperInstance_' + moduleId] = rvSwiper;
      
    } catch (error) {
      console.error('RV Slider initialization error:', error);
    }
  }
  
  // Wait for DOM and Swiper to be ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
      setTimeout(initRVSlider, 200);
    });
  } else {
    setTimeout(initRVSlider, 200);
  }
  
  // Also try on window load as fallback
  window.addEventListener('load', function() {
    setTimeout(initRVSlider, 300);
  });
})();
</script>
<?php } ?>
