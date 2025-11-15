<?php if (!empty($reviews)) { ?>
<section class="rv-testimonial-section" style="max-width: 80%; margin: 0 auto; padding: 0;">
  <?php if ($title) { ?>
  <div class="rv-module-heading" style="margin-bottom: 30px; text-align: center;">
    <h2 class="rv-title"><?php echo $title; ?></h2>
  </div>
  <?php } ?>
  
   <div class="rv-container-wrapper">
     <div class="rv-owl-carousel owl-carousel" id="rv-testimonial-owl-<?php echo $module; ?>">
         <?php foreach ($reviews as $review) { ?>
         <div class="rv-owl-item">
           <div class="rv-testimonial-card">
             <div class="rv-testimonial-content">
               <p class="rv-testimonial-text">
                 <?php echo $review['text']; ?>
               </p>
             </div>
             <div class="rv-testimonial-footer">
               <div class="rv-author-avatar">
                 <?php if (!empty($review['author_image'])) { ?>
                 <img src="<?php echo $review['author_image']; ?>" alt="<?php echo htmlspecialchars($review['author']); ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                 <div class="rv-avatar-placeholder" style="display: none;">
                   <?php echo strtoupper(substr($review['author'], 0, 1)); ?>
                 </div>
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
  </div>
</section>

<style>
/* Review View Testimonial - Unique Classes to Avoid Conflicts */
.rv-testimonial-section {
  padding: 0;
  position: relative;
  background: transparent !important;
}

/* Remove any unwanted background text or pseudo-elements */
.rv-testimonial-section::before,
.rv-testimonial-section::after,
.rv-container-wrapper::before,
.rv-container-wrapper::after,
.rv-owl-carousel::before,
.rv-owl-carousel::after,
.rv-testimonial-card::before,
.rv-testimonial-card::after {
  display: none !important;
  content: none !important;
}

.rv-container-wrapper {
  position: relative;
  padding: 0;
  background: transparent !important;
  overflow: visible;
}

.rv-owl-carousel {
  position: relative;
  overflow: hidden;
  padding-bottom: 50px;
  background: transparent !important;
}

.rv-owl-item {
  padding: 0 10px;
  box-sizing: border-box;
  height: 100%;
  display: flex;
}

.rv-testimonial-card {
  background: #ffffff;
  border: 1px solid #e8e8e8;
  border-radius: 12px;
  padding: 30px;
  height: 100%;
  min-height: 280px;
  display: flex;
  flex-direction: column;
  box-shadow: 0 2px 10px rgba(0,0,0,0.06);
  transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  position: relative;
  z-index: 1;
  overflow: hidden;
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

/* Navigation Arrows - Owl Carousel */
.rv-owl-carousel .owl-nav {
  display: block !important;
}

.rv-owl-carousel .owl-nav button {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 40px;
  height: 40px;
  background: #FF6A00 !important;
  color: #ffffff !important;
  border-radius: 50% !important;
  cursor: pointer !important;
  z-index: 10 !important;
  opacity: 1 !important;
  visibility: visible !important;
  border: none !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  transition: all 0.3s ease;
}

.rv-owl-carousel .owl-nav button i {
  font-size: 20px !important;
  color: #ffffff !important;
  line-height: 1;
}

.rv-owl-carousel .owl-nav .owl-prev {
  left: 10px;
}

.rv-owl-carousel .owl-nav .owl-next {
  right: 10px;
}

.rv-owl-carousel .owl-nav button:hover {
  background: #ff8c00 !important;
  box-shadow: 0 4px 12px rgba(255, 106, 0, 0.3) !important;
  transform: translateY(-50%) scale(1.1);
}

.rv-owl-carousel .owl-nav button.disabled {
  opacity: 0.3;
  cursor: not-allowed;
  pointer-events: none;
}

/* Pagination - Owl Carousel */
.rv-owl-carousel .owl-dots {
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  z-index: 10;
  text-align: center;
}

.rv-owl-carousel .owl-dots .owl-dot {
  width: 10px;
  height: 10px;
  background: #ddd;
  border-radius: 50%;
  opacity: 1;
  cursor: pointer;
  transition: all 0.3s ease;
  margin: 0 5px;
  display: inline-block;
}

.rv-owl-carousel .owl-dots .owl-dot.active {
  background: #FF6A00;
  width: 30px;
  border-radius: 5px;
}

/* Responsive */
@media (max-width: 767px) {
  .rv-testimonial-section {
    max-width: 100% !important;
    padding: 0 15px;
  }
  
  .rv-container-wrapper {
    padding: 0;
  }
  
  .rv-owl-carousel .owl-nav button {
    width: 36px;
    height: 36px;
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
jQuery(document).ready(function($) {
  var moduleId = 'rv-testimonial-owl-<?php echo $module; ?>';
  var $carousel = $('#' + moduleId);
  
  if (!$carousel.length || typeof $.fn.owlCarousel === 'undefined') {
    return;
  }
  
  var reviewCount = <?php echo count($reviews); ?>;
  var enableLoop = reviewCount > 3;
  
  // Destroy existing carousel if it exists
  if ($carousel.data('owl.carousel')) {
    $carousel.trigger('destroy.owl.carousel').removeClass('owl-carousel owl-loaded');
  }
  
  // Initialize Owl Carousel
  $carousel.addClass('owl-carousel').owlCarousel({
    loop: enableLoop,
    margin: 20,
    nav: true,
    dots: true,
    autoplay: true,
    autoplayTimeout: 3000,
    autoplayHoverPause: false,
    smartSpeed: 600,
    navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
    responsive: {
      0: {
        items: 1,
        margin: 15
      },
      576: {
        items: 2,
        margin: 20
      },
      768: {
        items: 2,
        margin: 20
      },
      992: {
        items: 3,
        margin: 20
      },
      1200: {
        items: 3,
        margin: 20
      }
    }
  });
  
  // Ensure autoplay continues
  $carousel.on('mouseleave', function() {
    $carousel.trigger('play.owl.autoplay', [3000]);
  });
});
</script>
<?php } ?>
