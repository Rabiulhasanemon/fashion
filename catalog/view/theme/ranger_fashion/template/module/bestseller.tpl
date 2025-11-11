<div class="popular-products color-background-1 background section-template--bestseller-padding section-animation" data-columns="6" data-mobile-column="1">
  <div class="popular-products__top container">
    <div class="popular-products__top-main">
      <div class="popular-products__top-text">
        <h2 class="popular-products__title section-title h3 unified-module-heading"><?php echo $heading_title; ?></h2>
<style>
.popular-products__title.unified-module-heading,
.section-title.unified-module-heading {
  font-size: 24px !important;
  font-weight: 600 !important;
  color: #333 !important;
  text-align: center !important;
  padding: 24px 0 !important;
}
@media (max-width: 992px) {
  .popular-products__title.unified-module-heading { font-size: 22px !important; padding: 20px 0 !important; }
}
@media (max-width: 749px) {
  .popular-products__title.unified-module-heading { font-size: 20px !important; padding: 18px 0 !important; }
}
@media (max-width: 576px) {
  .popular-products__title.unified-module-heading { font-size: 18px !important; padding: 15px 0 !important; }
}
</style>
      </div>
      <div class="popular-products__top-right">
        <a href="<?php echo $shop_all_url; ?>" class="popular-products__button link--underline_arrow">
          <span>Shop All</span>
          <svg class="icon icon-arrow" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M13.6333 12.5L8 6.86673L8.86673 6L15.3667 12.5L8.86673 19L8 18.1333L13.6333 12.5Z" fill="currentColor" stroke="currentColor" stroke-width="0.3"></path>
          </svg>
        </a>
      </div>
    </div>
  </div>
  <div class="popular-products__wrapper container content">
    <div class="popular-products__layout">
      <ul class="list-unstyled popular-products__list content grid--6 popular-products__grid popular-products__grid_small">
        <?php foreach ($products as $product) { ?>
        <li class="popular-products__item column-animation cart-content-center animate">
          <div class="card-horizontal-wrapper color-background-1 js-color-swatches-wrapper" data-product="<?php echo $product['product_id']; ?>">
            <span class="visually-hidden"><?php echo $product['name']; ?></span>
            <a href="<?php echo $product['href']; ?>" class="link link--overlay card-wrapper__link--overlay js-color-swatches-link focus-inset" aria-label="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>"></a>
            <div class="card-horizontal-wrapper__inner">
              <div class="card-horizontal__image" tabindex="-1">
                <div class="media" style="padding-bottom: 133.3%; --object-fit: cover;">
                  <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" width="200" height="267" loading="lazy" sizes="calc(200px * 1)" class="motion-reduce media--first">
                </div>
              </div>
              <div class="card-horizontal__information">
                <div class="card-horizontal__text">
                  <h3 class="card-horizontal__title">
                    <span class="link--hover-underline"><?php echo $product['name']; ?></span>
                  </h3>
                  <div class="price <?php if ($product['special']) { ?>price--on-sale<?php } ?>">
                    <dl>
                      <div class="price__regular">
                        <dt class="visually-hidden">
                          <span class="visually-hidden visually-hidden--inline">Regular price</span>
                        </dt>
                        <dd>
                          <span class="price-item price-item--regular"><?php echo $product['price']; ?></span>
                        </dd>
                      </div>
                      <?php if ($product['special']) { ?>
                      <div class="price__sale">
                        <dt class="visually-hidden">
                          <span class="visually-hidden visually-hidden--inline">Sale price</span>
                        </dt>
                        <dd>
                          <span class="price-item price-item--sale"><?php echo $product['special']; ?></span>
                        </dd>
                        <dt class="price__compare visually-hidden">
                          <span class="visually-hidden visually-hidden--inline">Regular price</span>
                        </dt>
                        <dd class="price__compare">
                          <s class="price-item price-item--regular"><?php echo $product['price']; ?></s>
                        </dd>
                      </div>
                      <?php } ?>
                    </dl>
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
