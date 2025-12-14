<?php echo $header; ?>

<section class="after-header">
    <div class="container">
        <ul class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            <?php foreach ($breadcrumbs as $i => $breadcrumb) { if($i < 1) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } else { ?>
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemtype="http://schema.org/Thing" itemprop="item" href="<?php echo $breadcrumb['href']; ?>">
                    <span itemprop="name"><?php echo $breadcrumb['text']; ?></span>
                </a>
                <meta itemprop="position" content="<?php echo $i; ?>" />
            </li>
            <?php }} ?>
        </ul>
    </div>
</section>

<div id="mfr-list-wrapper" class="mfr-list-container">
    <div class="mfr-list-header">
        <h1 class="mfr-list-title"><?php echo isset($heading_title) ? $heading_title : 'All Brands'; ?></h1>
    </div>
    
    <div class="mfr-list-content">
        <?php if (isset($categories) && $categories) { ?>
        <div class="mfr-brands-grid">
            <?php 
            $pastel_colors = array('#E6E6FA', '#FFB6C1', '#B0E0E6', '#F0E68C', '#DDA0DD', '#98D8C8', '#F7DC6F', '#F8BBD0');
            $color_index = 0;
            foreach ($categories as $category) { ?>
            <?php if (isset($category['manufacturer']) && $category['manufacturer']) { ?>
            <?php foreach ($category['manufacturer'] as $manufacturer) { 
                $bg_color = $pastel_colors[$color_index % count($pastel_colors)];
                $color_index++;
            ?>
            <div class="mfr-brand-card">
                <a href="<?php echo isset($manufacturer['href']) ? $manufacturer['href'] : '#'; ?>" class="mfr-brand-link">
                    <div class="mfr-card-container">
                        <!-- Top Section - Logo Area with Pastel Background -->
                        <div class="mfr-top-section" style="background-color: <?php echo $bg_color; ?>;">
                            <?php if (isset($manufacturer['image']) && $manufacturer['image']) { ?>
                            <div class="mfr-logo-container">
                                <img src="<?php echo htmlspecialchars($manufacturer['image'], ENT_QUOTES, 'UTF-8'); ?>" 
                                     alt="<?php echo isset($manufacturer['name']) ? htmlspecialchars($manufacturer['name']) : ''; ?>" 
                                     class="mfr-logo-image" 
                                     loading="lazy"
                                     onload="this.style.opacity='1';"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
                                <div class="mfr-logo-fallback-text" style="display: none;">
                                    <span class="mfr-fallback-name"><?php echo isset($manufacturer['name']) ? htmlspecialchars($manufacturer['name']) : ''; ?></span>
                                </div>
                            </div>
                            <?php } else { ?>
                            <div class="mfr-logo-fallback-text">
                                <span class="mfr-fallback-name"><?php echo isset($manufacturer['name']) ? htmlspecialchars($manufacturer['name']) : ''; ?></span>
                            </div>
                            <?php } ?>
                        </div>
                        
                        <!-- Bottom Section - Brand Name -->
                        <div class="mfr-bottom-section">
                            <div class="mfr-name-text">
                                <?php echo isset($manufacturer['name']) ? htmlspecialchars($manufacturer['name']) : ''; ?>
                            </div>
                            <?php if (isset($manufacturer['product_count']) && $manufacturer['product_count'] > 0) { ?>
                            <div class="mfr-product-count">
                                <?php echo $manufacturer['product_count']; ?> product<?php echo $manufacturer['product_count'] > 1 ? 's' : ''; ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </a>
            </div>
            <?php } ?>
            <?php } ?>
            <?php } ?>
        </div>
        <?php } else { ?>
        <div class="mfr-empty-state">
            <div class="mfr-empty-content">
                <i class="fa fa-box-open mfr-empty-icon"></i>
                <h3 class="mfr-empty-title"><?php echo isset($text_empty) ? $text_empty : 'No Brands Found'; ?></h3>
                <p class="mfr-empty-text">There are no brands available at the moment.</p>
                <?php if (isset($continue)) { ?>
                <a href="<?php echo $continue; ?>" class="mfr-continue-btn">
                    <?php echo isset($button_continue) ? $button_continue : 'Continue Shopping'; ?>
                </a>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<style>
/* Manufacturer List Page Styles - Matching BRC Brand Card Style */
#mfr-list-wrapper.mfr-list-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 40px 20px;
    background: #ffffff;
}

.mfr-list-header {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    margin-bottom: 30px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.mfr-list-title {
    font-size: 32px;
    font-weight: 700;
    color: #333;
    margin: 0;
    position: relative;
    display: inline-block;
    padding-bottom: 8px;
}

.mfr-list-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: #ff8c00;
    border-radius: 2px;
}

.mfr-list-content {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Brands Grid */
.mfr-brands-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 20px;
}

/* Brand Card - Matching BRC Style */
.mfr-brand-card {
    background: transparent;
    border-radius: 0;
    overflow: visible;
    transition: all 0.3s ease;
}

.mfr-brand-card:hover {
    transform: translateY(-5px);
}

.mfr-brand-link {
    display: block;
    text-decoration: none;
    color: inherit;
    height: 100%;
}

.mfr-card-container {
    background: #ffffff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.mfr-brand-card:hover .mfr-card-container {
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.12);
}

/* Top Section - Logo Area with Pastel Background */
.mfr-top-section {
    padding: 20px 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 90px;
    position: relative;
}

.mfr-logo-container {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.mfr-logo-image {
    max-width: 100%;
    max-height: 60px;
    width: auto;
    height: auto;
    object-fit: contain;
    opacity: 1;
    transition: transform 0.3s ease;
}

.mfr-brand-card:hover .mfr-logo-image {
    transform: scale(1.08);
}

.mfr-logo-fallback-text {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.mfr-fallback-name {
    font-size: 14px;
    font-weight: 600;
    color: #000000;
    text-align: center;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
}

/* Bottom Section - Brand Name */
.mfr-bottom-section {
    padding: 12px 15px;
    background: #ffffff;
    text-align: center;
}

.mfr-name-text {
    font-size: 13px;
    font-weight: 700;
    color: #000000;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
    line-height: 1.2;
    margin-bottom: 4px;
}

.mfr-product-count {
    font-size: 11px;
    font-weight: 500;
    color: #666;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
    line-height: 1.2;
    margin-top: 4px;
}

/* Empty State */
.mfr-empty-state {
    text-align: center;
    padding: 60px 20px;
}

.mfr-empty-content {
    max-width: 400px;
    margin: 0 auto;
}

.mfr-empty-icon {
    font-size: 56px;
    color: #ddd;
    margin-bottom: 15px;
}

.mfr-empty-title {
    font-size: 22px;
    font-weight: 600;
    color: #666;
    margin: 0 0 8px 0;
}

.mfr-empty-text {
    color: #999;
    font-size: 14px;
    margin: 0 0 20px 0;
}

.mfr-continue-btn {
    display: inline-block;
    padding: 12px 30px;
    background: #6c5ce7;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.2s ease;
}

.mfr-continue-btn:hover {
    background: #5f4fd1;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 92, 231, 0.3);
}

/* Responsive */
@media (max-width: 1200px) {
    .mfr-brands-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 18px;
    }
    
    .mfr-top-section {
        min-height: 85px;
        padding: 18px 12px;
    }
    
    .mfr-logo-image {
        max-height: 55px;
    }
}

@media (max-width: 992px) {
    .mfr-brands-grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 15px;
    }
    
    .mfr-list-title {
        font-size: 28px;
    }
    
    .mfr-top-section {
        min-height: 80px;
        padding: 15px 10px;
    }
    
    .mfr-logo-image {
        max-height: 50px;
    }
}

@media (max-width: 768px) {
    #mfr-list-wrapper.mfr-list-container {
        padding: 30px 15px;
    }
    
    .mfr-list-header {
        padding: 20px;
    }
    
    .mfr-list-content {
        padding: 20px;
    }
    
    .mfr-brands-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 12px;
    }
    
    .mfr-top-section {
        min-height: 75px;
        padding: 12px 8px;
    }
    
    .mfr-logo-image {
        max-height: 45px;
    }
    
    .mfr-name-text {
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .mfr-list-title {
        font-size: 24px;
    }
    
    .mfr-brands-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    
    .mfr-top-section {
        min-height: 70px;
        padding: 10px 6px;
    }
    
    .mfr-logo-image {
        max-height: 40px;
    }
    
    .mfr-fallback-name {
        font-size: 12px;
    }
    
    .mfr-name-text {
        font-size: 11px;
    }
}
</style>

<?php echo $footer; ?>