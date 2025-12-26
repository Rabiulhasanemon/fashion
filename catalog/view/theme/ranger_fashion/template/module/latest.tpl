<div class="latest-products-section color-background-1 background section-animation" data-columns="6" data-mobile-column="1">
  <div class="container">
    <div class="latest-products__top">
      <div class="latest-products__top-main">
        <div class="latest-products__top-text">
          <h2 class="latest-products__title section-title h3 unified-module-heading cosmetics-module-heading"><?php echo $heading_title; ?></h2>
<style>
/* Consistent Premium Module Headings */
.cosmetics-module-heading {
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
.cosmetics-module-heading::after {
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
  .cosmetics-module-heading { font-size: 24px !important; padding: 18px 0 14px 0 !important; }
  .cosmetics-module-heading::after { width: 50px !important; height: 2.5px !important; bottom: 6px !important; }
}
@media (max-width: 749px) {
  .cosmetics-module-heading { font-size: 22px !important; padding: 16px 0 12px 0 !important; }
  .cosmetics-module-heading::after { width: 45px !important; height: 2px !important; bottom: 5px !important; }
}
</style>
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
          $is_hidden = ($product_count > 6) ? 'latest-product-hidden' : '';
        ?>
        <li class="latest-products__item column-animation cart-content-center animate <?php echo $is_hidden; ?>" data-product-index="<?php echo $product_count; ?>">
          <div class="latest-card-wrapper color-background-1" data-product="<?php echo $product['product_id']; ?>">
            <div class="latest-card-wrapper__inner">
              <div class="latest-card__image-wrapper">
                <div class="latest-card__image">
                  <a href="<?php echo $product['href']; ?>">
                    <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" loading="lazy" class="latest-product-img">
                  </a>
                  <div class="latest-card__actions">
                    <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');" class="action-btn" title="<?php echo $button_cart; ?>"><i class="fa fa-shopping-cart"></i></button>
                    <button type="button" onclick="wishlist.add('<?php echo $product['product_id']; ?>');" class="action-btn" title="<?php echo $button_wishlist; ?>"><i class="fa fa-heart"></i></button>
                    <button type="button" onclick="compare.add('<?php echo $product['product_id']; ?>');" class="action-btn" title="<?php echo $button_compare; ?>"><i class="fa fa-exchange"></i></button>
                  </div>
                </div>
              </div>
              <div class="latest-card__information">
                <h3 class="latest-card__title">
                  <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                </h3>
                
                <?php if ($product['rating']) { ?>
                <div class="rating">
                  <?php for ($i = 1; $i <= 5; $i++) { ?>
                  <?php if ($product['rating'] < $i) { ?>
                  <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } else { ?>
                  <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } ?>
                  <?php } ?>
                </div>
                <?php } ?>
                
                <div class="latest-price">
                  <?php if ($product['price']) { ?>
                    <?php if (!$product['special']) { ?>
                      <span class="price-regular"><?php echo $product['price']; ?></span>
                    <?php } else { ?>
                      <span class="price-new"><?php echo $product['special']; ?></span> 
                      <span class="price-old"><?php echo $product['price']; ?></span>
                    <?php } ?>
                  <?php } ?>
                  <?php if (!empty($product['points']) && $product['points'] > 0) { ?>
                  <div class="module-reward-points">
                    <i class="fa fa-gift"></i>
                    <span>Earn <?php echo $product['points']; ?> points</span>
                  </div>
                  <?php } ?>
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
</div>

<style>
/* Premium Product Grid Styles */
.latest-products__list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    margin: 0;
    padding: 0;
}

.latest-products__item {
    list-style: none;
}

.latest-card-wrapper {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.latest-card-wrapper:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.08);
}

.latest-card-wrapper__inner {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.latest-card__image-wrapper {
    position: relative;
    overflow: hidden;
    padding-top: 100%; /* 1:1 Aspect Ratio */
}

.latest-card__image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.latest-product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.latest-card-wrapper:hover .latest-product-img {
    transform: scale(1.05);
}

.latest-card__actions {
    position: absolute;
    bottom: -50px;
    left: 0;
    width: 100%;
    display: flex;
    justify-content: center;
    gap: 10px;
    padding: 10px;
    transition: bottom 0.3s ease;
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(5px);
}

.latest-card-wrapper:hover .latest-card__actions {
    bottom: 0;
}

.action-btn {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: none;
    background: #fff;
    color: #333;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.action-btn:hover {
    background: #ff6b9d;
    color: #fff;
    transform: scale(1.1);
}

.latest-card__information {
    padding: 15px;
    text-align: center;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.latest-card__title {
    font-size: 15px;
    margin: 0 0 8px;
    font-weight: 500;
    line-height: 1.4;
}

.latest-card__title a {
    color: #333;
    text-decoration: none;
    transition: color 0.2s;
}

.latest-card__title a:hover {
    color: #ff6b9d;
}

.latest-price {
    font-weight: 600;
    color: #1a1a1a;
    margin-top: auto;
    padding-top: 10px;
}

.price-new {
    color: #ff6b9d;
    margin-right: 5px;
}

.price-old {
    color: #999;
    text-decoration: line-through;
    font-size: 0.9em;
    font-weight: 400;
}

.rating {
    color: #ffc107;
    font-size: 12px;
    margin-bottom: 5px;
}

/* Responsive */
@media (max-width: 768px) {
    .latest-products__list {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    
    .latest-card-wrapper:hover {
        transform: none;
    }
    
    .latest-card__actions {
        bottom: 0;
        background: transparent;
        position: absolute;
        top: 10px;
        bottom: auto;
        right: 10px;
        left: auto;
        flex-direction: column;
        width: auto;
        padding: 0;
    }
    
    .action-btn {
        width: 30px;
        height: 30px;
        font-size: 12px;
        margin-bottom: 5px;
    }
}
</style>