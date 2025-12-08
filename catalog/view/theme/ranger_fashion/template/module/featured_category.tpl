<?php if (isset($categories) && !empty($categories)) { ?>
<section class="shop-cat-module-section" id="shop-cat-module-<?php echo isset($module_id) ? $module_id : time(); ?>">
    <div class="container product-slider">
        <div class="shop-cat-header">
            <h2 class="shop-cat-title"><?php echo isset($name) ? htmlspecialchars($name) : 'Shop By Category'; ?></h2>
        </div>
        <div class="shop-cat-card-grid">
            <?php foreach ($categories as $index => $category) { ?>
            <div class="shop-cat-item-wrapper">
                <div class="shop-cat-item">
                    <div class="shop-cat-card">
                        <a href="<?php echo $category['href']; ?>" class="shop-cat-card-link">
                            <div class="shop-cat-image-container">
                                <div class="shop-cat-image-wrapper">
                                    <img src="<?php echo $category['icon']; ?>" alt="<?php echo htmlspecialchars($category['name']); ?>" width="125" height="125" class="shop-cat-image">
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="shop-cat-content">
                        <a href="<?php echo $category['href']; ?>" class="shop-cat-name-link"><?php echo $category['name']; ?></a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<style>
/* Shop By Category Module - New Style (No Conflicts) */
.shop-cat-module-section {
    padding: 40px 0;
    background: #fff;
}

.shop-cat-header {
    margin-bottom: 30px;
}

.shop-cat-title {
    font-size: 30px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
    padding-bottom: 8px;
    position: relative;
    display: inline-block;
}

.shop-cat-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: #ff8c00;
    border-radius: 2px;
}

/* Category Grid Layout */
.shop-cat-card-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-gap: 10px;
}

/* Category Item Wrapper */
.shop-cat-item-wrapper {
    display: flex;
    flex-direction: column;
}

.shop-cat-item {
    display: flex;
    flex-direction: column;
    width: 100%;
}

/* Category Card */
.shop-cat-card {
    border-radius: 12px;
    overflow: hidden;
}

.shop-cat-card-link {
    display: block;
    text-decoration: none;
    border-radius: 12px;
}

.shop-cat-image-container {
    background-color: #f4e6e7;
    border-radius: 12px;
    border: 1px solid transparent;
    transition: all 0.3s ease;
}

.shop-cat-card-link:hover .shop-cat-image-container {
    border-color: #ff8c00;
}

.shop-cat-image-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 151px;
    padding: 10px;
}

.shop-cat-image {
    width: 125px;
    height: 125px;
    object-fit: contain;
    display: block;
}

/* Category Content */
.shop-cat-content {
    padding: 12px 0;
    text-align: center;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.shop-cat-name-link {
    font-size: 14px;
    font-weight: 500;
    color: #4a5568;
    text-decoration: none;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    transition: color 0.3s ease;
}

.shop-cat-name-link:hover {
    color: #ff8c00;
}

/* Responsive Design */
@media (min-width: 576px) {
    .shop-cat-card-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (min-width: 768px) {
    .shop-cat-card-grid {
        grid-template-columns: repeat(4, 1fr);
        grid-gap: 5px;
    }
    
    .shop-cat-title {
        font-size: 28px;
    }
}

@media (min-width: 800px) {
    .shop-cat-card-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (min-width: 1024px) {
    .shop-cat-card-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (min-width: 1100px) {
    .shop-cat-card-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (min-width: 1200px) {
    .shop-cat-card-grid {
        grid-template-columns: repeat(5, 1fr);
        grid-gap: 5px;
    }
}

@media (min-width: 1280px) {
    .shop-cat-card-grid {
        grid-template-columns: repeat(5, 1fr);
        grid-gap: 5px;
    }
}

@media (min-width: 1400px) {
    .shop-cat-card-grid {
        grid-template-columns: repeat(6, 1fr);
    }
}

@media (min-width: 1600px) {
    .shop-cat-card-grid {
        grid-template-columns: repeat(8, 1fr);
    }
}

/* Mobile Optimizations */
@media (max-width: 767px) {
    .shop-cat-module-section {
        padding: 30px 0;
    }
    
    .shop-cat-header {
        margin-bottom: 20px;
    }
    
    .shop-cat-title {
        font-size: 24px;
    }
    
    .shop-cat-title::after {
        width: 50px;
        height: 2.5px;
    }
    
    .shop-cat-image-wrapper {
        height: 120px;
    }
    
    .shop-cat-image {
        width: 100px;
        height: 100px;
    }
    
    .shop-cat-content {
        height: 38px;
        padding: 10px 0;
    }
    
    .shop-cat-name-link {
        font-size: 13px;
    }
}

@media (max-width: 480px) {
    .shop-cat-title {
        font-size: 22px;
    }
    
    .shop-cat-image-wrapper {
        height: 100px;
    }
    
    .shop-cat-image {
        width: 80px;
        height: 80px;
    }
    
    .shop-cat-content {
        height: 36px;
        padding: 8px 0;
    }
    
    .shop-cat-name-link {
        font-size: 12px;
    }
}
</style>

<?php } ?>