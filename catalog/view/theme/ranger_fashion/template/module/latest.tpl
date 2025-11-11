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
      <ul class="list-unstyled latest-products__list content grid--6">
        <?php foreach ($products as $product) { ?>
        <li class="latest-products__item column-animation cart-content-center animate">
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
    </div>
  </div>
</div>
