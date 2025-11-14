<?php if (!empty($reviews)) { ?>
<section class="testimonial testimonial-style-1 section-padding" style="max-width: 80%; margin: 0 auto; padding: 40px 0;">
  <?php if ($title) { ?>
  <div class="module-heading-wrapper" style="margin-bottom: 30px;">
    <h2 class="module-heading-title"><?php echo $title; ?></h2>
  </div>
  <?php } ?>
  
  <div class="container" style="max-width: 100%; padding: 0; position: relative;">
    <div class="swiper testimonial-slider-1">
      <div class="swiper-wrapper">
        <?php foreach ($reviews as $review) { ?>
        <div class="swiper-slide">
          <div class="testimonial-card">
            <p class="testimonial-text">
              <?php echo $review['text']; ?>
            </p>
            <div class="testimonial-author">
              <div class="testimonial-author-thumbnail">
                <?php if (!empty($review['author_image'])) { ?>
                <img src="<?php echo $review['author_image']; ?>" alt="<?php echo htmlspecialchars($review['author']); ?>">
                <?php } else { ?>
                <img src="catalog/view/theme/ranger_fashion/image/placeholder-user.png" alt="<?php echo htmlspecialchars($review['author']); ?>" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'60\' height=\'60\'%3E%3Crect fill=\'%23ddd\' width=\'60\' height=\'60\'/%3E%3Ctext fill=\'%23999\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' font-size=\'20\'%3E<?php echo strtoupper(substr($review['author'], 0, 1)); ?>%3C/text%3E%3C/svg%3E'">
                <?php } ?>
              </div>
              <div class="testimonial-info">
                <h5><?php echo htmlspecialchars($review['author']); ?></h5>
                <?php if (!empty($review['designation'])) { ?>
                <span><?php echo htmlspecialchars($review['designation']); ?></span>
                <?php } elseif ($show_product && !empty($review['product_name'])) { ?>
                <span><?php echo htmlspecialchars($review['product_name']); ?></span>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <!-- Navigation arrows -->
      <div class="swiper-button-next testimonial-nav-next"></div>
      <div class="swiper-button-prev testimonial-nav-prev"></div>
      <!-- Pagination -->
      <div class="swiper-pagination testimonial-pagination-1"></div>
    </div>
  </div>
</section>

<style>
/* Testimonial Style 1 - Premium Design */
.testimonial-style-1 {
  padding: 40px 0;
  position: relative;
}

.testimonial-style-1 .testimonial-card {
  border: 1px solid var(--border-color, #e1e1e1);
  border-radius: 16px;
  padding: 24px;
  min-height: 224px;
  background: #fff;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
}

.testimonial-style-1 .testimonial-card:hover {
  box-shadow: 0 4px 16px rgba(0,0,0,0.12);
  transform: translateY(-2px);
}

.testimonial-style-1 .testimonial-text {
  font-size: 14px;
  display: -webkit-box;
  -webkit-line-clamp: 4;
  -webkit-box-orient: vertical;
  overflow: hidden;
  margin-bottom: 20px;
  line-height: 1.6;
  color: #666;
  flex: 1;
}

.testimonial-style-1 .testimonial-author {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-top: auto;
}

.testimonial-style-1 .testimonial-author-thumbnail {
  flex-shrink: 0;
}

.testimonial-style-1 .testimonial-author-thumbnail img {
  width: 60px;
  height: 60px;
  border-radius: 100%;
  object-fit: cover;
  border: 2px solid #f0f0f0;
  transition: all 0.3s ease;
}

.testimonial-style-1 .testimonial-card:hover .testimonial-author-thumbnail img {
  border-color: var(--primary-color, #007bff);
}

.testimonial-style-1 .testimonial-info {
  margin: 0;
  flex: 1;
}

.testimonial-style-1 .testimonial-info h5 {
  margin: 0;
  font-weight: 600;
  font-size: 14px;
  line-height: 120%;
  color: var(--title-color, #222);
}

.testimonial-style-1 .testimonial-info span {
  font-size: 12px;
  font-weight: 400;
  display: block;
  margin-top: 4px;
  color: #999;
}

/* Swiper Premium Styles */
.testimonial-slider-1 {
  padding: 0 50px 50px;
  position: relative;
  overflow: visible;
}

.testimonial-slider-1 .swiper-wrapper {
  display: flex;
  transition-timing-function: cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.testimonial-slider-1 .swiper-slide {
  height: auto;
  flex-shrink: 0;
}

/* Navigation Arrows - Premium Design */
.testimonial-slider-1 .swiper-button-next,
.testimonial-slider-1 .swiper-button-prev {
  width: 40px;
  height: 40px;
  background: #fff;
  border: 2px solid #e1e1e1;
  border-radius: 50%;
  color: var(--primary-color, #007bff);
  font-size: 18px;
  font-weight: bold;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
  top: 50%;
  margin-top: -20px;
  z-index: 10;
}

.testimonial-slider-1 .swiper-button-next::after,
.testimonial-slider-1 .swiper-button-prev::after {
  font-size: 18px;
  font-weight: bold;
}

.testimonial-slider-1 .swiper-button-next:hover,
.testimonial-slider-1 .swiper-button-prev:hover {
  background: var(--primary-color, #007bff);
  color: #fff;
  border-color: var(--primary-color, #007bff);
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  transform: scale(1.1);
}

.testimonial-slider-1 .swiper-button-prev {
  left: 0;
}

.testimonial-slider-1 .swiper-button-next {
  right: 0;
}

.testimonial-slider-1 .swiper-button-disabled {
  opacity: 0.35;
  cursor: not-allowed;
}

/* Pagination - Premium Design */
.testimonial-slider-1 .swiper-pagination {
  bottom: 0;
  position: absolute;
  line-height: 0;
  left: 50%;
  transform: translate(-50%, 0);
  margin: 0;
  z-index: 10;
  width: auto;
}

.testimonial-slider-1 .swiper-pagination-bullet {
  width: 10px;
  height: 10px;
  background: #ddd;
  opacity: 1;
  margin: 0 5px;
  transition: all 0.3s ease;
  border-radius: 50%;
}

.testimonial-slider-1 .swiper-pagination-bullet-active {
  background: var(--primary-color, #007bff);
  width: 24px;
  border-radius: 12px;
}

/* Responsive */
@media (max-width: 767px) {
  .testimonial-style-1 {
    max-width: 100% !important;
    padding: 20px 15px;
  }
  
  .testimonial-slider-1 {
    padding: 0 40px 50px;
  }
  
  .testimonial-slider-1 .swiper-button-next,
  .testimonial-slider-1 .swiper-button-prev {
    width: 32px;
    height: 32px;
    font-size: 14px;
  }
  
  .testimonial-slider-1 .swiper-button-prev {
    left: 5px;
  }
  
  .testimonial-slider-1 .swiper-button-next {
    right: 5px;
  }
  
  .testimonial-style-1 .testimonial-card {
    min-height: auto;
    padding: 20px;
  }
  
  .testimonial-style-1 .testimonial-text {
    -webkit-line-clamp: 3;
  }
}
</style>

<script>
(function() {
  // Wait for Swiper to be available
  function initTestimonialSlider() {
    var testimonialSlider = document.querySelector('.testimonial-slider-1');
    if (!testimonialSlider) return;
    
    if (typeof Swiper !== 'undefined') {
      var swiperInstance = new Swiper('.testimonial-slider-1', {
        slidesPerView: 1,
        slidesPerGroup: 1,
        spaceBetween: 20,
        loop: <?php echo count($reviews) > 3 ? 'true' : 'false'; ?>,
        speed: 800,
        autoplay: {
          delay: 4000,
          disableOnInteraction: false,
          pauseOnMouseEnter: true,
        },
        navigation: {
          nextEl: '.testimonial-nav-next',
          prevEl: '.testimonial-nav-prev',
        },
        pagination: {
          el: '.testimonial-pagination-1',
          clickable: true,
          dynamicBullets: true,
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
            console.log('Testimonial slider initialized');
          }
        }
      });
      
      // Force autoplay start
      if (swiperInstance.autoplay) {
        swiperInstance.autoplay.start();
      }
    } else {
      // Retry if Swiper not loaded yet
      setTimeout(initTestimonialSlider, 100);
    }
  }
  
  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTestimonialSlider);
  } else {
    initTestimonialSlider();
  }
})();
</script>
<?php } ?>
