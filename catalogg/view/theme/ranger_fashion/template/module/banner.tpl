<?php if ($banners) { ?>
<div class="banner-module-section">
  <div class="container">
    <div class="banner-grid">
      <?php foreach ($banners as $banner) { ?>
        <?php if (isset($banner['group_class'])) { ?>
          <?php foreach ($banner['banners'] as $banner_child) { ?>
            <div class="banner-item">
              <?php if ($banner_child['link']) { ?>
              <a href="<?php echo $banner_child['link']; ?>" class="banner-link">
                <img src="<?php echo $banner_child['image']; ?>" alt="<?php echo isset($banner_child['title']) ? $banner_child['title'] : ''; ?>" loading="lazy" class="banner-img">
                <div class="banner-overlay">
                  <span class="banner-btn">Shop Now</span>
                </div>
              </a>
              <?php } else { ?>
              <div class="banner-content">
                <img src="<?php echo $banner_child['image']; ?>" alt="<?php echo isset($banner_child['title']) ? $banner_child['title'] : ''; ?>" loading="lazy" class="banner-img">
              </div>
              <?php } ?>
            </div>
          <?php } ?>
        <?php } else { ?>
          <div class="banner-item">
            <?php if ($banner['link']) { ?>
            <a href="<?php echo $banner['link']; ?>" class="banner-link">
              <img src="<?php echo $banner['image']; ?>" alt="<?php echo isset($banner['title']) ? $banner['title'] : ''; ?>" loading="lazy" class="banner-img">
              <div class="banner-overlay">
                <span class="banner-btn">Shop Now</span>
              </div>
            </a>
            <?php } else { ?>
            <div class="banner-content">
              <img src="<?php echo $banner['image']; ?>" alt="<?php echo isset($banner['title']) ? $banner['title'] : ''; ?>" loading="lazy" class="banner-img">
            </div>
            <?php } ?>
          </div>
        <?php } ?>
      <?php } ?>
    </div>
  </div>
</div>

<style>
/* Premium Banner Module */
.banner-module-section {
    margin-bottom: 0;
    padding: 20px 0;
}

.banner-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.banner-item {
    position: relative;
    border-radius: 0;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.banner-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.banner-link, .banner-content {
    display: block;
    position: relative;
    overflow: hidden;
}

.banner-img {
    width: 100%;
    height: auto;
    display: block;
    transition: transform 0.5s ease;
    object-fit: cover;
}

.banner-link:hover .banner-img {
    transform: scale(1.05);
}

.banner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.banner-link:hover .banner-overlay {
    opacity: 1;
}

.banner-btn {
    background: #fff;
    color: #333;
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: 600;
    transform: translateY(20px);
    transition: transform 0.3s ease;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    text-transform: uppercase;
    font-size: 14px;
    letter-spacing: 1px;
}

.banner-link:hover .banner-btn {
    transform: translateY(0);
}

/* Desktop/Laptop - No border radius */
@media (min-width: 769px) {
    .banner-item {
        border-radius: 0 !important;
    }
}

/* Mobile - Fix gaps and spacing */
@media (max-width: 768px) {
    .banner-module-section {
        padding: 10px 0 0 0 !important;
        margin-bottom: 0 !important;
    }
    
    .banner-module-section .container {
        padding: 0 !important;
        margin: 0 !important;
    }
    
    .banner-grid {
        grid-template-columns: 1fr;
        gap: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .banner-item {
        border-radius: 0 !important;
        margin: 0 !important;
        margin-bottom: 0 !important;
        box-shadow: none !important;
    }
    
    .banner-item:last-child {
        margin-bottom: 0 !important;
    }
    
    .banner-link, .banner-content {
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .banner-img {
        width: 100% !important;
        display: block !important;
        margin: 0 !important;
        padding: 0 !important;
        border-radius: 0 !important;
    }
    
    .banner-btn {
        padding: 10px 20px;
        font-size: 12px;
    }
}

/* Small Mobile - Remove height constraints */
@media (max-width: 575px) {
    .banner-content {
        min-height: auto !important;
        height: auto !important;
        max-height: none !important;
    }
    
    .banner-item {
        height: auto !important;
        min-height: auto !important;
    }
    
    .banner-img {
        height: auto !important;
        min-height: auto !important;
        max-height: none !important;
    }
}
</style>
<?php } ?>