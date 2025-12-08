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
            <?php foreach ($categories as $category) { ?>
            <?php if (isset($category['manufacturer']) && $category['manufacturer']) { ?>
            <?php foreach ($category['manufacturer'] as $manufacturer) { ?>
            <div class="mfr-brand-card">
                <a href="<?php echo isset($manufacturer['href']) ? $manufacturer['href'] : '#'; ?>" class="mfr-brand-link">
                    <div class="mfr-brand-image-wrapper">
                        <img src="<?php echo isset($manufacturer['image']) ? $manufacturer['image'] : ''; ?>" 
                             alt="<?php echo isset($manufacturer['name']) ? htmlspecialchars($manufacturer['name']) : ''; ?>" 
                             class="mfr-brand-image" 
                             loading="lazy" />
                    </div>
                    <div class="mfr-brand-name">
                        <?php echo isset($manufacturer['name']) ? htmlspecialchars($manufacturer['name']) : ''; ?>
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
/* Manufacturer List Page Styles */
#mfr-list-wrapper.mfr-list-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 40px 20px;
    background: #f5f5f5;
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
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 25px;
}

/* Brand Card */
.mfr-brand-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e8e8e8;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.mfr-brand-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    border-color: #ff8c00;
}

.mfr-brand-link {
    display: block;
    text-decoration: none;
    padding: 20px;
}

.mfr-brand-image-wrapper {
    width: 100%;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fafafa;
    border-radius: 8px;
    margin-bottom: 15px;
    padding: 15px;
}

.mfr-brand-image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.mfr-brand-card:hover .mfr-brand-image {
    transform: scale(1.1);
}

.mfr-brand-name {
    text-align: center;
    font-size: 15px;
    font-weight: 600;
    color: #333;
    margin-top: 10px;
    line-height: 1.4;
}

.mfr-brand-link:hover .mfr-brand-name {
    color: #ff8c00;
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
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 20px;
    }
}

@media (max-width: 992px) {
    .mfr-brands-grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 18px;
    }
    
    .mfr-list-title {
        font-size: 28px;
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
        gap: 15px;
    }
    
    .mfr-brand-image-wrapper {
        height: 100px;
    }
    
    .mfr-brand-name {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .mfr-list-title {
        font-size: 24px;
    }
    
    .mfr-brands-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .mfr-brand-image-wrapper {
        height: 80px;
    }
    
    .mfr-brand-name {
        font-size: 13px;
    }
}
</style>

<?php echo $footer; ?>