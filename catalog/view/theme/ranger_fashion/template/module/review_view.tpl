<?php if (!empty($reviews)) { ?>
<section class="testimonial testimonial-style-1 section-padding" style="max-width: 80%; margin: 0 auto; padding: 40px 0;">
  <?php if ($title) { ?>
  <div class="module-heading-wrapper" style="margin-bottom: 30px;">
    <h2 class="module-heading-title"><?php echo $title; ?></h2>
  </div>
  <?php } ?>
  
  <div class="container" style="max-width: 100%; padding: 0;">
    <div class="swiper slider-active overflow-hide testimonial-slider-1">
      <div class="swiper-wrapper">
        <?php foreach ($reviews as $review) { ?>
        <div class="swiper-slide testimonial-card">
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
        <?php } ?>
      </div>
      <div class="swiper-pagination testimonial-pagination-1"></div>
    </div>
  </div>
</section>

<style>
/* Testimonial Style 1 */
.testimonial-style-1 {
  padding: 40px 0;
}

.testimonial-style-1 .testimonial-card {
  border: 1px solid var(--border-color, #e1e1e1);
  border-radius: 16px;
  padding: 24px;
  min-height: 224px;
  margin-top: 1px;
  background: #fff;
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
}

.testimonial-style-1 .testimonial-author {
  display: flex;
  align-items: center;
  gap: 8px;
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
}

.testimonial-style-1 .testimonial-info {
  margin: 0;
  flex: 1;
}

.testimonial-style-1 .testimonial-info h5 {
  margin: 0;
  font-weight: 500;
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

/* Swiper Styles */
.testimonial-slider-1 {
  padding-bottom: 24px;
  overflow: hidden;
}

.testimonial-slider-1 .swiper-wrapper {
  display: flex;
  transition-timing-function: ease-in-out;
}

.testimonial-slider-1 .swiper-pagination {
  bottom: 0;
  position: absolute;
  line-height: 0;
  left: 50%;
  transform: translate(-50%, 0);
  margin: 0;
  z-index: 10;
}

.testimonial-slider-1 .swiper-pagination-bullet {
  width: 8px;
  height: 8px;
  background: #ddd;
  opacity: 1;
  margin: 0 4px;
  transition: all 0.3s ease;
}

.testimonial-slider-1 .swiper-pagination-bullet-active {
  background: var(--primary-color, #007bff);
  width: 12px;
  border-radius: 4px;
}

.testimonial-slider-1 .swiper-slide {
  height: auto;
  flex-shrink: 0;
}

@media (max-width: 767px) {
  .testimonial-style-1 {
    max-width: 100% !important;
    padding: 20px 15px;
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
document.addEventListener('DOMContentLoaded', function() {
  var testimonialSlider = document.querySelector('.testimonial-slider-1');
  if (testimonialSlider && typeof Swiper !== 'undefined') {
    new Swiper('.testimonial-slider-1', {
      slidesPerView: 1,
      slidesPerGroup: 1,
      spaceBetween: 20,
      loop: true,
      speed: 600,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      pagination: {
        el: '.testimonial-pagination-1',
        clickable: true,
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
      }
    });
  }
});
</script>
<?php } ?>
