<div class="featured-product-area section-padding banner-fullscreen" style="width: 95vw; margin-left: calc(-47.5vw + 50%); margin-right: calc(-47.5vw + 50%); padding: 30px 0 !important; margin-top: 0; margin-bottom: 0;">
  <div class="container" style="max-width: 80%; padding: 0 20px; box-sizing: border-box;">
<style>
@media (max-width: 767px) {
  .featured-product-area .container {
    max-width: 100% !important;
    padding: 0 15px !important;
  }
}
</style>
    <div class="row">
      <div class="col-lg-12">
        <div class="section-head" style="margin-bottom: 20px !important; padding-bottom: 15px !important;">
          <h3 class="title cosmetics-module-heading" style="font-size: 28px !important; margin: 0; text-align: left !important; padding: 20px 0 16px 0 !important; position: relative !important;"><?php echo $name; ?></h3>
<style>
.featured-product-area .cosmetics-module-heading {
  font-size: 28px !important;
  font-weight: 600 !important;
  color: #1a1a1a !important;
  text-align: left !important;
  margin: 0 !important;
  padding: 20px 0 16px 0 !important;
  letter-spacing: -0.02em !important;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
  position: relative !important;
}
.featured-product-area .cosmetics-module-heading::after {
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
  .featured-product-area .cosmetics-module-heading { font-size: 24px !important; padding: 18px 0 14px 0 !important; }
  .featured-product-area .cosmetics-module-heading::after { width: 50px !important; height: 2.5px !important; bottom: 6px !important; }
}
@media (max-width: 749px) {
  .featured-product-area .cosmetics-module-heading { font-size: 22px !important; padding: 16px 0 12px 0 !important; }
  .featured-product-area .cosmetics-module-heading::after { width: 45px !important; height: 2px !important; bottom: 5px !important; }
}
@media (max-width: 576px) {
  .featured-product-area .cosmetics-module-heading { font-size: 20px !important; padding: 14px 0 10px 0 !important; }
  .featured-product-area .cosmetics-module-heading::after { width: 40px !important; height: 2px !important; bottom: 4px !important; }
}
</style>
          <a href="<?php echo $see_all; ?>" class="btn"
          >View All Collection
            <span class="material-icons">arrow_forward</span></a
          >
        </div>
      </div>
    </div>
    <div class="featured-product-wrapper resource-list resource-list--grid" style="--resource-list-column-gap-desktop: 8px; --resource-list-row-gap-desktop: 24px; --resource-list-columns: repeat(6, 1fr); --resource-list-columns-mobile: repeat(2, 1fr);">
      <?php foreach ($products as $product) { ?>
      <div class="product-item resource-list__item">

        <?php if ($product['special']) { ?>
        <?php
          $price = floatval(str_replace(['৳', ',', ' '], '', $product['price']));
          $special = floatval(str_replace(['৳', ',', ' '], '', $product['special']));
          $discountAmount = $price - $special;
          $discountPercent = ($price > 0) ? round(($discountAmount / $price) * 100) : 0;
        ?>
        <div class="unified-discount-badge">
          <i class="fa fa-bolt discount-badge-icon"></i>
          <span class="discount-badge-text"><?php echo $discountPercent; ?>% OFF</span>
        </div>
        <?php } ?>

        <a href="<?php echo $product['href']; ?>">
          <div class="product-img card-gallery" style="--gallery-aspect-ratio: 1.0; padding-top: 100% !important; aspect-ratio: 1.0 !important;">
            <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain; image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges; image-rendering: high-quality;" />
          </div>
        </a>
        <div class="product-info" style="padding-top: 12px !important; margin-top: 12px !important; gap: 4px !important;">
          <a href="<?php echo $product['href']; ?>">
            <h4 class="name" style="font-size: 16px !important; line-height: 22px !important;"><?php echo $product['name']; ?></h4>
          </a>
          <div class="product-price-wrap" style="gap: 4px !important;">
            <?php if ($product['special']) { ?>
            <span class="price"><?php echo $product['special']; ?></span>
            <span class="price old"><?php echo $product['price']; ?></span>
            <?php } else { ?>
            <span class="price"><?php echo $product['price']; ?></span>
            <?php } ?>

          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>

<style>
.featured-product-wrapper.resource-list--grid {
    display: grid;
    gap: var(--resource-list-row-gap-desktop) var(--resource-list-column-gap-desktop);
    grid-template-columns: var(--resource-list-columns-mobile);
    width: 100%;
}
@media screen and (min-width: 750px) {
    .featured-product-wrapper.resource-list--grid {
        grid-template-columns: var(--resource-list-columns);
    }
}
.featured-product-area .product-img {
    --gallery-aspect-ratio: 1.0;
    padding-top: 100% !important;
    aspect-ratio: 1.0 !important;
    background: #fff;
    border-radius: 0;
    overflow: hidden;
    position: relative;
    display: flex;
    width: 100%;
}
.featured-product-area .product-item:hover .product-img {
    transform: scale(1.03);
    transition: transform 0.25s ease-out;
}
.featured-product-area .product-img img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
    image-rendering: -webkit-optimize-contrast;
    image-rendering: crisp-edges;
    image-rendering: high-quality;
    aspect-ratio: inherit;
}
.featured-product-area .product-info {
    padding-top: 12px !important;
    margin-top: 12px !important;
    gap: 4px !important;
}
.featured-product-area .product-info .name {
    font-size: 16px !important;
    line-height: 22px !important;
}
.featured-product-area .section-head {
    margin-bottom: 20px !important;
    padding-bottom: 15px !important;
}
.featured-product-area .section-head .title {
    font-size: 28px !important;
    font-weight: 600 !important;
    color: #1a1a1a !important;
    line-height: 1.3 !important;
    text-align: left !important;
    padding: 20px 0 16px 0 !important;
    letter-spacing: -0.02em !important;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
    position: relative !important;
}

.featured-product-area .section-head .title::after {
    content: '' !important;
    position: absolute !important;
    bottom: 8px !important;
    left: 0 !important;
    width: 60px !important;
    height: 3px !important;
    background: linear-gradient(90deg, #ff6b9d, #ff8c9f) !important;
    border-radius: 2px !important;
}
.resource-list__item {
    height: 100%;
    color: var(--color-foreground, #111827);
    text-decoration: none;
}
@media (max-width: 749px) {
    .featured-product-wrapper.resource-list--grid {
        --resource-list-column-gap-desktop: 12px;
        --resource-list-row-gap-desktop: 24px;
    }
    .featured-product-area {
        padding: 25px 0 !important;
    }
    .featured-product-area .section-head {
        margin-bottom: 15px !important;
        padding-bottom: 12px !important;
    }
    .featured-product-area .section-head .title {
        font-size: 22px !important;
        padding: 16px 0 12px 0 !important;
    }
    .featured-product-area .section-head .title::after {
        width: 45px !important;
        height: 2px !important;
        bottom: 5px !important;
    }
}
@media (max-width: 576px) {
    .featured-product-area {
        padding: 20px 0 !important;
    }
    .featured-product-area .product-info {
        padding-top: 10px !important;
        margin-top: 10px !important;
    }
    .featured-product-area .product-info .name {
        font-size: 14px !important;
        line-height: 20px !important;
    }
    .featured-product-area .section-head .title {
        font-size: 20px !important;
        padding: 14px 0 10px 0 !important;
    }
    .featured-product-area .section-head .title::after {
        width: 40px !important;
        height: 2px !important;
        bottom: 4px !important;
    }
}
/* Unified Discount Badge - Red with Yellow Lightning */
.unified-discount-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 10;
    background: #e74c3c;
    border-radius: 6px;
    padding: 6px 10px;
    display: flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}
.discount-badge-icon {
    color: #ffd700;
    font-size: 14px;
    font-weight: bold;
}
.discount-badge-text {
    color: #ffffff;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.3px;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}
.product-item {
    position: relative;
}
</style>