<div id="bs-module-wrapper" class="bs-module-section">
  <div class="container">
    <div class="bs-module-header">
      <div class="block-title">
        <strong><?php echo $heading_title; ?></strong>
      </div>
    </div>
    
    <div class="bs-module-content">
      <ul class="bs-products-grid">
        <?php foreach ($products as $product) { ?>
        <li class="bs-product-item">
          <div class="bs-product-card">
            <div class="bs-product-image-box">
              <a href="<?php echo $product['href']; ?>" class="bs-product-link">
                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="bs-product-image" loading="lazy">
              </a>
              <div class="bs-product-buttons">
                <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');" class="bs-btn-icon" title="<?php echo $button_cart; ?>">
                  <i class="fa fa-shopping-cart"></i>
                </button>
                <button type="button" onclick="wishlist.add('<?php echo $product['product_id']; ?>');" class="bs-btn-icon" title="<?php echo $button_wishlist; ?>">
                  <i class="fa fa-heart"></i>
                </button>
                <button type="button" onclick="compare.add('<?php echo $product['product_id']; ?>');" class="bs-btn-icon" title="<?php echo $button_compare; ?>">
                  <i class="fa fa-exchange"></i>
                </button>
              </div>
            </div>
            
            <div class="bs-product-info">
              <h3 class="bs-product-name">
                <a href="<?php echo $product['href']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
              </h3>
              
              <?php if ($product['rating']) { ?>
              <div class="bs-product-rating">
                <?php for ($i = 1; $i <= 5; $i++) { ?>
                <i class="fa fa-star <?php echo ($product['rating'] >= $i) ? 'bs-star-filled' : ''; ?>"></i>
                <?php } ?>
              </div>
              <?php } ?>
              
              <div class="bs-product-price-box">
                <?php if ($product['price']) { ?>
                  <?php if ($product['special']) { ?>
                    <span class="bs-price-sale"><?php echo $product['special']; ?></span>
                    <span class="bs-price-original"><?php echo $product['price']; ?></span>
                  <?php } else { ?>
                    <span class="bs-price-normal"><?php echo $product['price']; ?></span>
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
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</div>

<style>
/* Simple Premium Bestseller Module - Unique bs- Classes */
#bs-module-wrapper.bs-module-section {
    padding: 0;
    background: #fff;
}

.bs-module-container {
    max-width: 80%;
    margin: 0 auto;
    padding: 0 20px;
}

.bs-module-header {
    margin-bottom: 30px;
}

.bs-module-title {
    font-size: 28px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
    padding-bottom: 15px;
    position: relative;
    letter-spacing: -0.02em;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.bs-module-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #ff6b9d, #ff8c9f);
    border-radius: 2px;
}

.bs-module-content {
    width: 100%;
}

.bs-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.bs-product-item {
    list-style: none;
}

.bs-product-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.bs-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.08);
}

.bs-product-image-box {
    position: relative;
    padding-top: 100%;
    overflow: hidden;
    background: #f8f9fa;
}

.bs-product-link {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.bs-product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.bs-product-card:hover .bs-product-image {
    transform: scale(1.05);
}

.bs-product-buttons {
    position: absolute;
    bottom: -50px;
    left: 0;
    width: 100%;
    display: flex;
    justify-content: center;
    gap: 10px;
    padding: 10px;
    transition: bottom 0.3s ease;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(5px);
}

.bs-product-card:hover .bs-product-buttons {
    bottom: 0;
}

.bs-btn-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: #fff;
    color: #333;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    font-size: 14px;
}

.bs-btn-icon:hover {
    background: #ff6b9d;
    color: #fff;
    transform: scale(1.1);
}

.bs-product-info {
    padding: 18px;
    text-align: center;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.bs-product-name {
    font-size: 15px;
    font-weight: 500;
    margin: 0 0 10px 0;
    line-height: 1.4;
    min-height: 42px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.bs-product-name a {
    color: #333;
    text-decoration: none;
    transition: color 0.2s;
}

.bs-product-name a:hover {
    color: #ff6b9d;
}

.bs-product-rating {
    margin-bottom: 10px;
}

.bs-product-rating i {
    color: #e0e0e0;
    font-size: 12px;
    margin-right: 2px;
}

.bs-product-rating i.bs-star-filled {
    color: #ffc107;
}

.bs-product-price-box {
    margin-top: auto;
    padding-top: 10px;
}

.bs-price-sale {
    font-size: 20px;
    font-weight: 700;
    color: #ff6b9d;
    margin-right: 8px;
}

.bs-price-original {
    font-size: 15px;
    color: #999;
    text-decoration: line-through;
    font-weight: 400;
}

.bs-price-normal {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a1a;
}

/* Responsive */
@media (max-width: 992px) {
    .bs-module-title {
        font-size: 24px;
    }
    
    .bs-module-title::after {
        width: 50px;
        height: 2.5px;
    }
}

@media (max-width: 768px) {
    #bs-module-wrapper.bs-module-section {
        padding: 30px 0;
    }
    
    .bs-module-container {
        padding: 0 15px;
    }
    
    .bs-module-title {
        font-size: 22px;
        padding-bottom: 12px;
    }
    
    .bs-module-title::after {
        width: 45px;
        height: 2px;
    }
    
    .bs-products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .bs-product-card:hover {
        transform: none;
    }
    
    .bs-product-buttons {
        bottom: 0;
        background: transparent;
        position: absolute;
        top: 10px;
        right: 10px;
        left: auto;
        flex-direction: column;
        width: auto;
        padding: 0;
    }
    
    .bs-btn-icon {
        width: 32px;
        height: 32px;
        font-size: 12px;
        margin-bottom: 6px;
    }
    
    .bs-product-info {
        padding: 15px;
    }
    
    .bs-product-name {
        font-size: 14px;
        min-height: 40px;
    }
    
    .bs-price-sale,
    .bs-price-normal {
        font-size: 18px;
    }
}

@media (max-width: 480px) {
    .bs-module-title {
        font-size: 20px;
    }
    
    .bs-product-name {
        font-size: 13px;
        min-height: 36px;
    }
    
    .bs-price-sale,
    .bs-price-normal {
        font-size: 16px;
    }
}
</style>