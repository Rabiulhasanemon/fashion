<?php if (!empty($banners)) { ?>
<div class="banner-tab-section banner-fullscreen <?php echo (isset($is_category_page) && $is_category_page) ? 'category-page-banner' : 'home-page-banner'; ?>">
  <div class="container" style="<?php echo (isset($is_category_page) && $is_category_page) ? 'width: 95vw; margin-left: calc(-47.5vw + 50%); margin-right: calc(-47.5vw + 50%); padding: 0 20px; max-width: 100%;' : ''; ?>">
<style>
/* Full Screen Banners for Mobile and Tablet - 100% Width */
@media (max-width: 991px) {
  .banner-tab-section {
    width: 100vw !important;
    max-width: 100vw !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    padding: 0 !important;
    overflow: hidden !important;
  }
  
  .banner-tab-section .container {
    width: 100% !important;
    max-width: 100% !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    padding: 0 !important;
  }
  
  .banner-tab-wrapper {
    padding: 0 !important;
    margin: 0 !important;
    width: 100% !important;
  }
  
  .banner-tab-wrapper .row {
    margin: 0 !important;
    padding: 0 !important;
    width: 100% !important;
  }
  
  .banner-tab-wrapper .col-lg-4,
  .banner-tab-wrapper .col-md-4,
  .banner-tab-wrapper .col-sm-12,
  .banner-tab-wrapper .col-xs-12 {
    padding: 0 !important;
    margin: 0 !important;
    width: 100% !important;
    max-width: 100% !important;
  }
  
  .promotional-banner,
  .simple-banner {
    border-radius: 0 !important;
    margin: 0 !important;
    width: 100% !important;
    max-width: 100% !important;
  }
  
  .promotional-banner img,
  .simple-banner img {
    border-radius: 0 !important;
    width: 100% !important;
    max-width: 100% !important;
    height: auto !important;
    display: block !important;
    object-fit: cover !important;
  }
  
  .banner-background {
    width: 100% !important;
  }
  
  .banner-background img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
  }
}
</style>
    <?php if (isset($name) && $name && isset($is_category_page) && $is_category_page) { ?>
    <div class="section-head" style="padding: 20px 15px 0;">
      <h3 class="title"><?php echo $name; ?></h3>
    </div>
    <?php } ?>

    <div class="banner-tab-wrapper" style="margin: 0; padding: 10px;">
      <div class="row" style="margin: 0; padding: 0;">
        <?php 
        $banner_count = 0;
        foreach ($banners as $banner) { 
          if ($banner_count >= 3) break; // Limit to exactly 3 banners
          $banner_count++;
        ?>
        <?php if (isset($banner['banner_children']) && !empty($banner['banner_children'])) { ?>
          <?php 
          // Get first banner child for each banner
          $first_child = reset($banner['banner_children']);
          $banner_tag = isset($banner['name']) ? $banner['name'] : ''; // Banner name as tag
          $banner_title = isset($first_child['title']) ? $first_child['title'] : '';
          $banner_blurb = isset($first_child['blurb']) ? $first_child['blurb'] : '';
          $banner_link = isset($first_child['link']) ? $first_child['link'] : '#';
          $banner_image = isset($first_child['image']) ? $first_child['image'] : '';
          ?>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="padding: 0 10px; margin-bottom: 20px;">
          <?php if (isset($is_category_page) && $is_category_page) { ?>
          <!-- Promotional Banner Style (Category Pages Only) -->
          <div class="promotional-banner" style="position: relative; overflow: hidden; border-radius: 8px; min-height: 300px; background-size: cover; background-position: center; display: flex; flex-direction: column; justify-content: space-between; padding: 20px;">
            <!-- Background Image -->
            <div class="banner-background" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 1;">
              <img src="<?php echo $banner_image; ?>" alt="<?php echo htmlspecialchars($banner_title); ?>" style="width: 100%; height: 100%; object-fit: cover;" />
            </div>
            
            <!-- Overlay for better text readability -->
            <div class="banner-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.3) 100%); z-index: 2;"></div>
            
            <!-- Content -->
            <div class="banner-content" style="position: relative; z-index: 3; height: 100%; display: flex; flex-direction: column; justify-content: space-between; min-height: 260px;">
              <!-- Top Section - Tag (Banner Name) -->
              <?php if ($banner_tag) { ?>
              <div class="banner-tag" style="margin-bottom: 10px;">
                <span style="background: rgba(255, 255, 255, 0.95); color: #333; padding: 5px 14px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block;">
                  <?php echo htmlspecialchars($banner_tag); ?>
                </span>
              </div>
              <?php } ?>
              
              <!-- Middle Section - Title and Description -->
              <div class="banner-text" style="flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 10px 0;">
                <?php if ($banner_title) { ?>
                <h2 class="banner-headline" style="color: #fff; font-size: 24px; font-weight: 700; margin: 0 0 10px 0; line-height: 1.3; text-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                  <?php echo htmlspecialchars($banner_title); ?>
                </h2>
                <?php } ?>
                
                <?php if ($banner_blurb) { ?>
                <p class="banner-description" style="color: #fff; font-size: 14px; line-height: 1.4; margin: 0 0 15px 0; text-shadow: 0 1px 4px rgba(0,0,0,0.3); max-width: 95%;">
                  <?php echo htmlspecialchars($banner_blurb); ?>
                </p>
                <?php } ?>
              </div>
              
              <!-- Bottom Section - Shop Now Button -->
              <div class="banner-action" style="margin-top: auto;">
                <a href="<?php echo htmlspecialchars($banner_link); ?>" class="shop-now-btn" style="display: inline-block; background: #377dff; color: #fff; padding: 10px 24px; border-radius: 4px; text-decoration: none; font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(55, 125, 255, 0.3);">
                  Shop Now
                </a>
              </div>
            </div>
          </div>
          <?php } else { ?>
          <!-- Simple Banner Style (Home Page) -->
          <div class="simple-banner" style="position: relative; overflow: hidden; border-radius: 8px;">
            <?php if ($banner_link && $banner_link != '#') { ?>
            <a href="<?php echo htmlspecialchars($banner_link); ?>" style="display: block;">
            <?php } ?>
            <img src="<?php echo $banner_image; ?>" alt="<?php echo htmlspecialchars($banner_title ? $banner_title : $banner_tag); ?>" style="width: 100%; height: auto; display: block; border-radius: 8px; transition: transform 0.3s ease;" />
            <?php if ($banner_link && $banner_link != '#') { ?>
            </a>
            <?php } ?>
          </div>
          <?php } ?>
        </div>
        <?php } ?>
        <?php } ?>
      </div>
    </div>

    <?php if (isset($blurb) && $blurb && isset($is_category_page) && $is_category_page) { ?>
    <p class="banner-tab-note" style="padding: 15px;">
      <?php echo $blurb; ?>
    </p>
    <?php } ?>
  </div>
</div>

<style>
/* Promotional Banner Styles (Category Pages Only) */
.promotional-banner {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.promotional-banner:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.shop-now-btn:hover {
  background: #2d6ae0 !important;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(55, 125, 255, 0.4) !important;
}

/* Simple Banner Styles (Home Page) */
.simple-banner:hover img {
  transform: scale(1.05);
}

/* Responsive Styles */
@media (max-width: 991px) {
  .promotional-banner {
    min-height: 280px !important;
    padding: 18px !important;
  }
  
  .banner-content {
    min-height: 240px !important;
  }
  
  .banner-headline {
    font-size: 22px !important;
  }
  
  .banner-description {
    font-size: 13px !important;
  }
}

@media (max-width: 767px) {
  .promotional-banner {
    min-height: 260px !important;
    padding: 15px !important;
    margin-bottom: 15px !important;
  }
  
  .banner-content {
    min-height: 230px !important;
  }
  
  .banner-headline {
    font-size: 20px !important;
    margin-bottom: 8px !important;
  }
  
  .banner-description {
    font-size: 12px !important;
    margin-bottom: 12px !important;
  }
  
  .shop-now-btn {
    padding: 8px 20px !important;
    font-size: 12px !important;
  }
}

@media (max-width: 575px) {
  .promotional-banner {
    min-height: 240px !important;
  }
  
  .banner-content {
    min-height: 210px !important;
  }
  
  .banner-headline {
    font-size: 18px !important;
  }
  
  .banner-description {
    font-size: 11px !important;
  }
}
</style>
<?php } ?>

