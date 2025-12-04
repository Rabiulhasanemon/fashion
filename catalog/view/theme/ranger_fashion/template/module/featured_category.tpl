<?php if (isset($categories) && !empty($categories)) { ?>
<section class="category style-7 section-padding fc-module-section" id="fc-module-<?php echo isset($module_id) ? $module_id : time(); ?>">
    <div class="container">
        <div class="fc-modern-header">
            <div class="fc-header-left">
                <div class="premium-module-heading">
                    <h3 class="fc-modern-title"><?php echo isset($name) ? htmlspecialchars($name) : 'Featured Categories'; ?></h3>
                </div>
            </div>
        </div>
        <div class="fc-category-grid">
            <?php foreach ($categories as $category) { ?>
            <div class="fc-category-item">
                <a href="<?php echo $category['href']; ?>">
                    <div class="category-card">
                        <div class="category-info">
                            <p><?php echo $category['name']; ?></p>
                        </div>
                        <div class="category-img">
                            <img src="<?php echo $category['icon']; ?>" alt="<?php echo htmlspecialchars($category['name']); ?>">
                        </div>
                    </div>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<style>
/* Featured Category Module - Responsive Grid (No Sliding) */
.fc-module-section {
    padding: 40px 0;
    background: #fff;
}

.fc-modern-header {
    margin-bottom: 30px;
    padding: 0;
}

.fc-header-left {
    width: 100%;
}

/* Premium Heading Style */
.premium-module-heading {
    position: relative;
    display: inline-block;
}

.fc-modern-title {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
    padding: 0 0 12px 0;
    text-transform: none;
    letter-spacing: -0.02em;
    line-height: 1.2;
    position: relative;
    font-family: 'Jost', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.fc-modern-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 70px;
    height: 4px;
    background: linear-gradient(90deg, #ff505a 0%, #ff6b9d 50%, #ff505a 100%);
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(255, 80, 90, 0.3);
}

/* Category Grid - Responsive Layout */
.fc-category-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.fc-category-item {
    width: 100%;
}

.category-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 180px;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(255, 80, 90, 0.15);
    border-color: #ff505a;
}

.category-info {
    margin-bottom: 15px;
}

.category-info p {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 0;
    text-transform: capitalize;
}

.category-img {
    width: 100%;
    max-width: 100px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.category-img img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.category-card:hover .category-img img {
    transform: scale(1.1);
}

/* Responsive Grid */
@media (min-width: 1400px) {
    .fc-category-grid {
        grid-template-columns: repeat(8, 1fr);
    }
}

@media (min-width: 1200px) and (max-width: 1399px) {
    .fc-category-grid {
        grid-template-columns: repeat(7, 1fr);
    }
}

@media (min-width: 992px) and (max-width: 1199px) {
    .fc-category-grid {
        grid-template-columns: repeat(6, 1fr);
    }
}

@media (min-width: 768px) and (max-width: 991px) {
    .fc-category-grid {
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;
    }
    
    .fc-modern-title {
        font-size: 28px;
        padding-bottom: 10px;
    }
    
    .fc-modern-title::after {
        width: 60px;
        height: 3px;
    }
}

@media (min-width: 576px) and (max-width: 767px) {
    .fc-category-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }
    
    .fc-modern-title {
        font-size: 24px;
        padding-bottom: 8px;
    }
    
    .fc-modern-title::after {
        width: 50px;
        height: 3px;
    }
    
    .category-card {
        min-height: 160px;
        padding: 15px;
    }
    
    .category-img {
        max-width: 80px;
        height: 80px;
    }
}

@media (max-width: 575px) {
    .fc-category-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    
    .fc-modern-title {
        font-size: 20px;
        padding-bottom: 6px;
    }
    
    .fc-modern-title::after {
        width: 40px;
        height: 2px;
    }
    
    .category-card {
        min-height: 140px;
        padding: 12px;
    }
    
    .category-info p {
        font-size: 14px;
    }
    
    .category-img {
        max-width: 60px;
        height: 60px;
    }
}

@media (max-width: 400px) {
    .fc-category-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    
    .category-card {
        min-height: 130px;
        padding: 10px;
    }
}
</style>
<?php } ?>