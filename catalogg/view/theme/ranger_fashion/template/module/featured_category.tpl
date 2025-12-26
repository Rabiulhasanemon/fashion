<?php if (isset($categories) && !empty($categories)) { ?>
<section class="shop-cat-module-section" id="shop-cat-module-<?php echo isset($module_id) ? $module_id : time(); ?>">
    <div class="container product-slider">
        <div class="shop-cat-header">
            <h2 class="shop-cat-title"><?php echo isset($name) ? htmlspecialchars($name) : 'Shop By Category'; ?></h2>
            <?php if (isset($show_see_all) && $show_see_all && isset($see_all_url)) { ?>
            <a href="<?php echo $see_all_url; ?>" class="shop-cat-see-all">
                <span class="shop-cat-see-all-text">See All</span>
                <span class="shop-cat-see-all-arrow">→</span>
            </a>
            <?php } ?>
        </div>
        <div class="shop-cat-card-grid" id="shop-cat-grid-<?php echo isset($module_id) ? $module_id : time(); ?>">
            <?php foreach ($categories as $index => $category) { 
                $is_hidden_mobile = ($index >= 4) ? 'ruplexa-cat-hidden-mobile' : '';
            ?>
            <div class="shop-cat-item-wrapper <?php echo $is_hidden_mobile; ?>" data-cat-index="<?php echo $index; ?>">
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
        <?php if (count($categories) > 4) { ?>
        <div class="shop-cat-show-more-wrapper" id="shop-cat-show-more-<?php echo isset($module_id) ? $module_id : time(); ?>">
            <button class="shop-cat-show-more-btn" onclick="ruplexaShowMoreCategories('<?php echo isset($module_id) ? $module_id : time(); ?>')">
                <span class="show-more-text">Show More</span>
                <span class="show-more-icon">▼</span>
            </button>
        </div>
        <?php } ?>
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

/* See All Button */
.shop-cat-see-all {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #dc2626;
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 8px 0;
}

.shop-cat-see-all:hover {
    color: #b91c1c;
    gap: 12px;
}

.shop-cat-see-all-text {
    display: inline-block;
}

.shop-cat-see-all-arrow {
    display: inline-block;
    font-size: 20px;
    line-height: 1;
    transition: transform 0.3s ease;
}

.shop-cat-see-all:hover .shop-cat-see-all-arrow {
    transform: translateX(4px);
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

/* Show More Button - Hidden on desktop/laptop, shown on mobile */
.shop-cat-show-more-wrapper {
    text-align: center;
    margin-top: 20px;
    display: none;
}

/* Ensure all items are visible on desktop/laptop (min-width: 768px) */
@media (min-width: 768px) {
    .ruplexa-cat-hidden-mobile {
        display: flex !important;
    }
    
    .shop-cat-show-more-wrapper {
        display: none !important;
    }
}

.shop-cat-show-more-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #10503D 0%, #A68A6A 100%);
    color: #ffffff;
    border: none;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(16, 80, 61, 0.3);
}

.shop-cat-show-more-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 80, 61, 0.4);
}

.shop-cat-show-more-btn .show-more-icon {
    transition: transform 0.3s ease;
    font-size: 12px;
}

.shop-cat-show-more-btn.expanded .show-more-icon {
    transform: rotate(180deg);
}

.shop-cat-show-more-btn.expanded .show-more-text {
    content: 'Show Less';
}

/* Hidden categories on mobile - Only hide on mobile, show on desktop/laptop */
.ruplexa-cat-hidden-mobile {
    display: flex; /* Show by default on desktop/laptop */
}

/* Only hide on mobile */
@media (max-width: 767px) {
    .ruplexa-cat-hidden-mobile {
        display: none;
    }
}

/* Mobile Optimizations */
@media (max-width: 767px) {
    .shop-cat-module-section {
        padding: 20px 0 !important;
    }
    
    .shop-cat-module-section .container {
        max-width: 100% !important;
        padding: 0 10px !important;
        margin: 0 auto !important;
        box-sizing: border-box !important;
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
    
    .shop-cat-see-all {
        font-size: 14px;
        padding: 6px 0;
    }
    
    .shop-cat-see-all-arrow {
        font-size: 18px;
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

    /* Show More Button - Only on Mobile */
    .shop-cat-show-more-wrapper {
        display: block;
    }

    /* Hide categories beyond 4 on mobile initially */
    .ruplexa-cat-hidden-mobile {
        display: none !important;
    }

    /* Show all when expanded */
    .shop-cat-card-grid.show-all .ruplexa-cat-hidden-mobile {
        display: flex !important;
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

<script>
function ruplexaShowMoreCategories(moduleId) {
    const grid = document.getElementById('shop-cat-grid-' + moduleId);
    const btn = document.querySelector('#shop-cat-show-more-' + moduleId + ' .shop-cat-show-more-btn');
    const text = btn.querySelector('.show-more-text');
    
    if (grid.classList.contains('show-all')) {
        // Hide categories beyond 4
        grid.classList.remove('show-all');
        text.textContent = 'Show More';
        btn.classList.remove('expanded');
    } else {
        // Show all categories
        grid.classList.add('show-all');
        text.textContent = 'Show Less';
        btn.classList.add('expanded');
    }
}
</script>

<?php } ?>