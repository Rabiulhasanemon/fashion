<?php echo $header; ?>
<section id="luxe-special-page" class="luxe-special-products-page">
    <div class="luxe-special-container">
        <!-- Breadcrumb -->
        <nav class="luxe-special-breadcrumb">
            <ul>
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </nav>

        <div class="luxe-special-layout">
            <!-- Sidebar -->
            <?php if ($column_left) { ?>
            <aside class="luxe-special-sidebar">
                <?php echo $column_left; ?>
            </aside>
            <?php } ?>

            <!-- Main Content -->
            <main class="luxe-special-main <?php echo !$column_left ? 'luxe-special-main--full' : ''; ?>">
                <?php echo $content_top; ?>
                
                <header class="luxe-special-header">
                    <h1 class="luxe-special-page-title"><?php echo $heading_title; ?></h1>
                </header>

                <?php if ($products) { ?>
                <!-- Toolbar -->
                <div class="luxe-special-toolbar">
                    <div class="luxe-special-toolbar-left">
                        <p class="luxe-special-compare">
                            <a href="<?php echo $compare; ?>" id="compare-total">
                                <i class="fa fa-exchange"></i> <?php echo $text_compare; ?>
                            </a>
                        </p>
                    </div>
                    <div class="luxe-special-toolbar-right">
                        <div class="luxe-special-control">
                            <label><?php echo $text_sort; ?></label>
                            <select class="luxe-special-select" onchange="location = this.value;">
                                <?php foreach ($sorts as $sorts) { ?>
                                <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
                                <option value="<?php echo $sorts['href']; ?>" selected><?php echo $sorts['text']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="luxe-special-control">
                            <label><?php echo $text_limit; ?></label>
                            <select class="luxe-special-select" onchange="location = this.value;">
                                <?php foreach ($limits as $limits) { ?>
                                <?php if ($limits['value'] == $limit) { ?>
                                <option value="<?php echo $limits['href']; ?>" selected><?php echo $limits['text']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="luxe-special-products-grid">
                    <?php foreach ($products as $product) { ?>
                    <article class="luxe-special-product-card">
                        <?php if ($product['special']) { ?>
                        <?php
                          $price_num = floatval(str_replace(['৳', ','], '', $product['price']));
                          $special_num = floatval(str_replace(['৳', ','], '', $product['special']));
                          if ($price_num > 0) {
                              $discount_pct = round((($price_num - $special_num) / $price_num) * 100);
                          } else {
                              $discount_pct = 0;
                          }
                        ?>
                        <div class="luxe-special-badge">-<?php echo $discount_pct; ?>%</div>
                        <?php } ?>
                        
                        <div class="luxe-special-image-wrapper">
                            <a href="<?php echo $product['href']; ?>">
                                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" class="luxe-special-product-image">
                            </a>
                            <div class="luxe-special-quick-actions">
                                <button type="button" class="luxe-special-action-btn" onclick="wishlist.add('<?php echo $product['product_id']; ?>');" title="<?php echo $button_wishlist; ?>">
                                    <i class="fa fa-heart"></i>
                                </button>
                                <button type="button" class="luxe-special-action-btn" onclick="compare.add('<?php echo $product['product_id']; ?>');" title="<?php echo $button_compare; ?>">
                                    <i class="fa fa-exchange"></i>
                                </button>
                            </div>
                        </div>

                        <div class="luxe-special-product-info">
                            <h3 class="luxe-special-product-title">
                                <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                            </h3>
                            
                            <?php if ($product['rating']) { ?>
                            <div class="luxe-special-rating">
                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                <i class="fa fa-star <?php echo ($product['rating'] >= $i) ? 'active' : ''; ?>"></i>
                                <?php } ?>
                            </div>
                            <?php } ?>
                            
                            <div class="luxe-special-price">
                                <?php if ($product['special']) { ?>
                                <span class="luxe-price-sale"><?php echo $product['special']; ?></span>
                                <span class="luxe-price-regular"><?php echo $product['price']; ?></span>
                                <?php } else { ?>
                                <span class="luxe-price-sale"><?php echo $product['price']; ?></span>
                                <?php } ?>
                            </div>
                            
                            <button type="button" class="luxe-special-add-cart" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');">
                                <i class="fa fa-shopping-cart"></i>
                                <span><?php echo $button_cart; ?></span>
                            </button>
                        </div>
                    </article>
                    <?php } ?>
                </div>

                <!-- Pagination -->
                <footer class="luxe-special-footer">
                    <div class="luxe-special-pagination"><?php echo $pagination; ?></div>
                    <div class="luxe-special-results"><?php echo $results; ?></div>
                </footer>
                
                <?php } else { ?>
                <div class="luxe-special-empty">
                    <div class="luxe-empty-icon"><i class="fa fa-shopping-bag"></i></div>
                    <h3><?php echo $text_empty; ?></h3>
                    <a href="<?php echo $continue; ?>" class="luxe-special-continue-btn"><?php echo $button_continue; ?></a>
                </div>
                <?php } ?>
                
                <?php echo $content_bottom; ?>
            </main>

            <!-- Right Sidebar -->
            <?php if ($column_right) { ?>
            <aside class="luxe-special-sidebar-right">
                <?php echo $column_right; ?>
            </aside>
            <?php } ?>
        </div>
    </div>
</section>

<style>
/* Premium Special Products Page */
#luxe-special-page.luxe-special-products-page {
    background: #fff;
    padding: 30px 0 50px 0;
}

.luxe-special-container {
    max-width: 100%;
    margin: 0 auto;
    padding: 0 20px;
}

/* Breadcrumb */
.luxe-special-breadcrumb ul {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0 0 30px 0;
    flex-wrap: wrap;
    gap: 8px;
}

.luxe-special-breadcrumb li {
    color: #999;
    font-size: 14px;
}

.luxe-special-breadcrumb li:not(:last-child)::after {
    content: '/';
    margin-left: 8px;
    color: #ccc;
}

.luxe-special-breadcrumb a {
    color: #666;
    text-decoration: none;
    transition: color 0.2s;
}

.luxe-special-breadcrumb a:hover {
    color: #ff6b9d;
}

/* Layout */
.luxe-special-layout {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: 30px;
}

.luxe-special-main--full {
    grid-column: 1 / -1;
}

/* Header */
.luxe-special-header {
    margin-bottom: 30px;
}

.luxe-special-page-title {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
    padding-bottom: 15px;
    border-bottom: 3px solid #ff6b9d;
    display: inline-block;
}

/* Toolbar */
.luxe-special-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
    flex-wrap: wrap;
    gap: 15px;
}

.luxe-special-toolbar-right {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.luxe-special-control {
    display: flex;
    align-items: center;
    gap: 10px;
}

.luxe-special-control label {
    font-size: 14px;
    color: #666;
    font-weight: 500;
}

.luxe-special-select {
    padding: 8px 35px 8px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #fff;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg fill="%23333" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 8px center;
}

.luxe-special-select:hover {
    border-color: #ff6b9d;
}

.luxe-special-compare a {
    color: #666;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.2s;
}

.luxe-special-compare a:hover {
    color: #ff6b9d;
}

/* Products Grid */
.luxe-special-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.luxe-special-product-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    position: relative;
}

.luxe-special-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.luxe-special-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
    color: #fff;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 13px;
    z-index: 10;
    box-shadow: 0 3px 8px rgba(255,107,107,0.3);
}

.luxe-special-image-wrapper {
    position: relative;
    padding-top: 100%;
    overflow: hidden;
    background: #f8f9fa;
}

.luxe-special-image-wrapper a {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.luxe-special-product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.luxe-special-product-card:hover .luxe-special-product-image {
    transform: scale(1.1);
}

.luxe-special-quick-actions {
    position: absolute;
    top: 15px;
    left: 15px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    opacity: 0;
    transform: translateX(-20px);
    transition: all 0.3s ease;
}

.luxe-special-product-card:hover .luxe-special-quick-actions {
    opacity: 1;
    transform: translateX(0);
}

.luxe-special-action-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(255,255,255,0.95);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.luxe-special-action-btn:hover {
    background: #ff6b9d;
    color: #fff;
    transform: scale(1.1);
}

.luxe-special-product-info {
    padding: 20px;
}

.luxe-special-product-title {
    font-size: 16px;
    font-weight: 600;
    margin: 0 0 10px 0;
    line-height: 1.4;
    min-height: 44px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.luxe-special-product-title a {
    color: #333;
    text-decoration: none;
    transition: color 0.2s;
}

.luxe-special-product-title a:hover {
    color: #ff6b9d;
}

.luxe-special-rating {
    margin-bottom: 10px;
}

.luxe-special-rating i {
    color: #e0e0e0;
    font-size: 13px;
    margin-right: 2px;
}

.luxe-special-rating i.active {
    color: #ffc107;
}

.luxe-special-price {
    margin-bottom: 15px;
}

.luxe-price-sale {
    font-size: 22px;
    font-weight: 700;
    color: #ff6b9d;
    margin-right: 8px;
}

.luxe-price-regular {
    font-size: 16px;
    color: #999;
    text-decoration: line-through;
}

.luxe-special-add-cart {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #ff6b9d 0%, #ff8c9f 100%);
    border: none;
    border-radius: 8px;
    color: #fff;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.luxe-special-add-cart:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255,107,157,0.3);
}

/* Footer */
.luxe-special-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 30px 0;
    border-top: 1px solid #eee;
    margin-top: 40px;
    flex-wrap: wrap;
    gap: 15px;
}

.luxe-special-pagination {
    flex: 1;
}

.luxe-special-results {
    color: #666;
    font-size: 14px;
}

/* Empty State */
.luxe-special-empty {
    text-align: center;
    padding: 80px 20px;
}

.luxe-empty-icon {
    font-size: 80px;
    color: #ddd;
    margin-bottom: 20px;
}

.luxe-special-empty h3 {
    font-size: 24px;
    color: #666;
    margin-bottom: 20px;
}

.luxe-special-continue-btn {
    display: inline-block;
    padding: 12px 30px;
    background: #ff6b9d;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.luxe-special-continue-btn:hover {
    background: #ff5c8d;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 992px) {
    .luxe-special-layout {
        grid-template-columns: 1fr;
    }
    
    .luxe-special-sidebar {
        order: 2;
    }
    
    .luxe-special-main {
        order: 1;
    }
}

@media (max-width: 768px) {
    .luxe-special-page-title {
        font-size: 26px;
    }
    
    .luxe-special-toolbar {
        flex-direction: column;
        align-items: stretch;
    }
    
    .luxe-special-toolbar-right {
        width: 100%;
        justify-content: space-between;
    }
    
    .luxe-special-products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .luxe-special-product-card:hover {
        transform: none;
    }
    
    .luxe-special-quick-actions {
        opacity: 1;
        transform: translateX(0);
        top: 10px;
        right: 10px;
        left: auto;
        flex-direction: row;
    }
    
    .luxe-special-action-btn {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }
    
    .luxe-special-footer {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .luxe-special-page-title {
        font-size: 22px;
    }
    
    .luxe-special-control {
        flex: 1;
        min-width: 120px;
    }
    
    .luxe-special-control label {
        font-size: 12px;
    }
    
    .luxe-special-select {
        font-size: 12px;
        padding: 6px 30px 6px 10px;
    }
}
</style>

<?php echo $footer; ?>