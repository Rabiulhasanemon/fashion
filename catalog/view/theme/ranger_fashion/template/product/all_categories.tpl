<?php echo $header; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
</div>

<?php if (isset($categories) && !empty($categories)) { ?>
<section class="shop-cat-module-section" id="shop-cat-module-all-categories">
    <div class="container product-slider">
        <div class="shop-cat-header">
            <h2 class="shop-cat-title"><?php echo isset($heading_title) ? htmlspecialchars($heading_title) : 'Shop by Featured Categories'; ?></h2>
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
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
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
    background-color: #fce4ec;
    border-radius: 12px;
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.shop-cat-card-link:hover .shop-cat-image-container {
    border-color: rgba(0, 0, 0, 0.1);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.shop-cat-image-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 140px;
    padding: 5px;
    min-height: 140px;
}

.shop-cat-image {
    width: 100%;
    height: 100%;
    max-width: 100%;
    max-height: 100%;
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
        grid-gap: 8px;
    }
    
    .shop-cat-title {
        font-size: 28px;
    }
    
    .shop-cat-image-wrapper {
        padding: 4px;
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
        grid-template-columns: repeat(6, 1fr);
        grid-gap: 8px;
    }
}

@media (min-width: 1280px) {
    .shop-cat-card-grid {
        grid-template-columns: repeat(7, 1fr);
        grid-gap: 8px;
    }
}

@media (min-width: 1400px) {
    .shop-cat-card-grid {
        grid-template-columns: repeat(8, 1fr);
        grid-gap: 10px;
    }
}

@media (min-width: 1600px) {
    .shop-cat-card-grid {
        grid-template-columns: repeat(8, 1fr);
        grid-gap: 12px;
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
        padding: 4px;
    }
    
    .shop-cat-image {
        width: 100%;
        height: 100%;
        max-width: 100%;
        max-height: 100%;
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
        padding: 3px;
    }
    
    .shop-cat-image {
        width: 100%;
        height: 100%;
        max-width: 100%;
        max-height: 100%;
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

<?php echo $footer; ?>

