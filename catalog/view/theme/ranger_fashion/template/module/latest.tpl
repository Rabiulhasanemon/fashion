<div class="latest-products-section color-background-1 background section-animation" data-columns="6" data-mobile-column="1">
  <div class="latest-products__top">
    <div class="latest-products__top-main">
      <div class="latest-products__top-text">
        <h2 class="latest-products__title section-title h3 unified-module-heading cosmetics-module-heading"><?php echo $name; ?></h2>
<style>
.latest-products__title.unified-module-heading.cosmetics-module-heading,
.section-title.unified-module-heading.cosmetics-module-heading {
  font-size: 28px !important;
  font-weight: 600 !important;
  color: #1a1a1a !important;
  text-align: left !important;
  padding: 20px 0 16px 0 !important;
  letter-spacing: -0.02em !important;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
  position: relative !important;
  margin: 0 !important;
}
.latest-products__title.unified-module-heading.cosmetics-module-heading::after,
.section-title.unified-module-heading.cosmetics-module-heading::after {
  content: '' !important;
  position: absolute !important;
  bottom: 8px !important;
  left: 0 !important;
  width: 60px !important;
  height: 3px !important;
  background: linear-gradient(90deg, #ff6b9d, #ff8c9f) !important;
  border-radius: 2px !important;
}
@media (max-width: 992px) {
  .latest-products__title.unified-module-heading.cosmetics-module-heading { font-size: 24px !important; padding: 18px 0 14px 0 !important; }
  .latest-products__title.unified-module-heading.cosmetics-module-heading::after { width: 50px !important; height: 2.5px !important; bottom: 6px !important; }
}
@media (max-width: 749px) {
  .latest-products__title.unified-module-heading.cosmetics-module-heading { font-size: 22px !important; padding: 16px 0 12px 0 !important; }
  .latest-products__title.unified-module-heading.cosmetics-module-heading::after { width: 45px !important; height: 2px !important; bottom: 5px !important; }
}
@media (max-width: 576px) {
  .latest-products__title.unified-module-heading.cosmetics-module-heading { font-size: 20px !important; padding: 14px 0 10px 0 !important; }
  .latest-products__title.unified-module-heading.cosmetics-module-heading::after { width: 40px !important; height: 2px !important; bottom: 4px !important; }
}
</style>
      </div>
      <div class="latest-products__top-right">
        <a href="<?php echo $shop_all_url; ?>" class="latest-products__button link--underline_arrow">
          <span>Shop All</span>
          <svg class="icon icon-arrow" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M13.6333 12.5L8 6.86673L8.86673 6L15.3667 12.5L8.86673 19L8 18.1333L13.6333 12.5Z" fill="currentColor" stroke="currentColor" stroke-width="0.3"></path>
          </svg>
        </a>
      </div>
    </div>
  </div>
  <div class="latest-products__wrapper content">
    <div class="latest-products__layout">
      <?php $latest_module_id = 'latest-' . uniqid(); ?>
      <ul class="list-unstyled latest-products__list content grid--6" id="latest-products-list-<?php echo $latest_module_id; ?>">
        <?php 
        $product_count = 0;
        foreach ($products as $product) { 
          $product_count++;
          $is_hidden = ($product_count > 4) ? 'latest-product-hidden' : '';
        ?>
        <li class="latest-products__item column-animation cart-content-center animate <?php echo $is_hidden; ?>" data-product-index="<?php echo $product_count; ?>">
          <div class="latest-card-wrapper color-background-1" data-product="<?php echo $product['product_id']; ?>">
            <span class="visually-hidden"><?php echo $product['name']; ?></span>
            <a href="<?php echo $product['href']; ?>" class="link link--overlay latest-card__link--overlay focus-inset" aria-label="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>"></a>
            <div class="latest-card-wrapper__inner">
              <div class="latest-card__image-wrapper">
                <div class="latest-card__image" tabindex="-1">
                  <div class="media" style="padding-bottom: 133.3%; --object-fit: cover;">
                    <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" width="200" height="267" loading="lazy" sizes="calc(200px * 1)" class="motion-reduce media--first latest-product-img">
                  </div>
                </div>
              </div>
              <div class="latest-card__information">
                <div class="latest-card__text">
                  <?php if (!empty($product['manufacturer'])) { ?>
                  <div class="latest-card__vendor"><?php echo $product['manufacturer']; ?></div>
                  <?php } ?>
                  <h3 class="latest-card__title">
                    <span class="link--hover-underline"><?php echo $product['name']; ?></span>
                  </h3>
                  <div class="latest-price <?php if ($product['special']) { ?>latest-price--on-sale<?php } ?>">
                    <?php if ($product['special']) { ?>
                    <span class="latest-price-item latest-price-item--sale"><?php echo $product['special']; ?></span>
                    <span class="latest-price-item latest-price-item--regular"><s><?php echo $product['price']; ?></s></span>
                    <?php } else { ?>
                    <span class="latest-price-item latest-price-item--regular"><?php echo $product['price']; ?></span>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </li>
        <?php } ?>
      </ul>
      
      <?php if (count($products) > 4) { ?>
      <div class="latest-products__show-more-wrapper" id="latest-show-more-<?php echo $latest_module_id; ?>" style="text-align: center; margin-top: 30px; display: none;">
        <button class="latest-products__show-more-btn" style="background: #A68A6A; color: #fff; border: none; padding: 12px 32px; border-radius: 6px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(166, 138, 106, 0.3);">
          Show More Products
        </button>
      </div>
      
      <div class="latest-products__show-less-wrapper" id="latest-show-less-<?php echo $latest_module_id; ?>" style="text-align: center; margin-top: 20px; display: none;">
        <button class="latest-products__show-less-btn" style="background: #666; color: #fff; border: none; padding: 12px 32px; border-radius: 6px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);">
          Show Less Products
        </button>
      </div>
      
      <div class="latest-products__slider-wrapper" id="latest-slider-<?php echo $latest_module_id; ?>" style="display: none; margin-top: 20px;">
        <div class="latest-products-slider owl-carousel">
          <!-- Remaining products will be loaded here -->
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>

<style>
/* Mobile and Tablet - Hide products beyond 4 */
@media (max-width: 991px) {
  .latest-products-section {
    padding: 0 5px !important;
  }
  
  .latest-products__wrapper {
    padding: 0 !important;
  }
  
  .latest-products__layout {
    padding: 0 !important;
  }
  
  .latest-products__list {
    margin: 0 -2px !important;
    padding: 0 !important;
    display: flex !important;
    flex-wrap: wrap !important;
    justify-content: space-between !important;
  }
  
  .latest-product-hidden {
    display: none !important;
  }
  
  .latest-products__show-more-wrapper {
    display: block !important;
    width: 100%;
    padding: 0 10px;
  }
  
  .latest-products__show-less-wrapper {
    width: 100%;
    padding: 0 10px;
  }
  
  /* Premium small design for mobile/tablet */
  .latest-products__item {
    padding: 3px !important;
    width: calc(50% - 6px) !important;
    margin: 0 !important;
    flex: 0 0 calc(50% - 6px) !important;
  }
  
  .latest-card-wrapper {
    padding: 6px !important;
    border-radius: 6px !important;
    margin: 0 !important;
  }
  
  .latest-card__image-wrapper {
    margin-bottom: 6px !important;
  }
  
  .latest-card__information {
    padding: 6px 3px !important;
  }
  
  .latest-card__vendor {
    font-size: 10px !important;
    margin-bottom: 3px !important;
  }
  
  .latest-card__title {
    font-size: 12px !important;
    line-height: 1.2 !important;
    margin-bottom: 4px !important;
  }
  
  .latest-price-item {
    font-size: 12px !important;
  }
  
  .latest-price-item--regular {
    font-size: 11px !important;
  }
  
  .latest-products__slider-wrapper {
    padding: 0 5px !important;
    width: 100% !important;
  }
  
  .latest-products-slider .latest-products__item {
    width: 100% !important;
    flex: 0 0 auto !important;
  }
}

/* Desktop - Show all products */
@media (min-width: 992px) {
  .latest-product-hidden {
    display: block !important;
  }
  
  .latest-products__show-more-wrapper {
    display: none !important;
  }
  
  .latest-products__slider-wrapper {
    display: none !important;
  }
}
</style>

<script>
jQuery(document).ready(function($) {
  var moduleId = '<?php echo $latest_module_id; ?>';
  var $list = $('#latest-products-list-' + moduleId);
  var $showMoreBtn = $('#latest-show-more-' + moduleId);
  var $showLessBtn = $('#latest-show-less-' + moduleId);
  var $sliderWrapper = $('#latest-slider-' + moduleId);
  var $slider = $sliderWrapper.find('.latest-products-slider');
  var hiddenProducts = [];
  var sliderInitialized = false;
  var owlCarouselInstance = null;
  
  // Collect hidden products
  $list.find('.latest-product-hidden').each(function() {
    hiddenProducts.push($(this).clone().removeClass('latest-product-hidden'));
  });
  
  // Show More button click
  $showMoreBtn.find('.latest-products__show-more-btn').on('click', function() {
    if (hiddenProducts.length === 0) return;
    
    // Hide the Show More button
    $showMoreBtn.fadeOut(300);
    
    // Show slider wrapper
    $sliderWrapper.fadeIn(300, function() {
      // Show Show Less button after slider is visible
      $showLessBtn.fadeIn(300);
    });
    
    // Initialize Owl Carousel if not already done
    if (!sliderInitialized && typeof $.fn.owlCarousel !== 'undefined') {
      // Clear any existing content
      $slider.empty();
      
      // Add hidden products to slider
      hiddenProducts.forEach(function($product) {
        $slider.append($product);
      });
      
      // Initialize carousel
      owlCarouselInstance = $slider.addClass('owl-carousel').owlCarousel({
        loop: false,
        margin: 8,
        nav: true,
        dots: false,
        autoplay: false,
        responsive: {
          0: {
            items: 2,
            margin: 6,
            slideBy: 2
          },
          576: {
            items: 2,
            margin: 8,
            slideBy: 2
          },
          768: {
            items: 4,
            margin: 10,
            slideBy: 2
          },
          992: {
            items: 4,
            margin: 12
          }
        },
        navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>']
      });
      
      sliderInitialized = true;
    }
  });
  
  // Show Less button click
  $showLessBtn.find('.latest-products__show-less-btn').on('click', function() {
    // Hide slider wrapper
    $sliderWrapper.fadeOut(300);
    
    // Hide Show Less button
    $showLessBtn.fadeOut(300, function() {
      // Show Show More button after slider is hidden
      $showMoreBtn.fadeIn(300);
    });
    
    // Destroy carousel if initialized
    if (sliderInitialized && owlCarouselInstance) {
      try {
        owlCarouselInstance.trigger('destroy.owl.carousel');
        $slider.removeClass('owl-carousel owl-loaded');
        $slider.empty();
        sliderInitialized = false;
        owlCarouselInstance = null;
      } catch(e) {
        console.log('Carousel already destroyed');
      }
    }
  });
});
</script>
