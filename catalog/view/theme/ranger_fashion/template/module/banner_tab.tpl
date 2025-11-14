<?php if (!empty($banners)) { ?>
<div class="banner-tab-section banner-fullscreen" style="width: 95vw; margin-left: calc(-47.5vw + 50%); margin-right: calc(-47.5vw + 50%); padding: 0; margin-top: 0; margin-bottom: 0;">
  <div class="container" style="padding: 0; max-width: 80%;">
<style>
@media (max-width: 767px) {
  .banner-tab-section .container {
    max-width: 100% !important;
    padding: 0 !important;
  }
}
</style>
    <?php if (isset($name) && $name) { ?>
    <div class="section-head" style="padding: 20px 15px 0;">
      <h3 class="title"><?php echo $name; ?></h3>
    </div>
    <?php } ?>

    <div class="banner-tab-wrapper" style="margin: 0; padding: 20px 10px;">
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
          $banner_title = isset($first_child['title']) ? $first_child['title'] : '';
          $banner_blurb = isset($first_child['blurb']) ? $first_child['blurb'] : '';
          $banner_link = isset($first_child['link']) ? $first_child['link'] : '#';
          $banner_image = isset($first_child['image']) ? $first_child['image'] : '';
          ?>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="padding: 0 10px; margin-bottom: 20px;">
          <div class="promotional-banner" style="position: relative; overflow: hidden; border-radius: 8px; height: 500px; background-size: cover; background-position: center; display: flex; flex-direction: column; justify-content: space-between; padding: 30px 25px;">
            <!-- Background Image -->
            <div class="banner-background" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 1;">
              <img src="<?php echo $banner_image; ?>" alt="<?php echo htmlspecialchars($banner_title); ?>" style="width: 100%; height: 100%; object-fit: cover;" />
            </div>
            
            <!-- Overlay for better text readability -->
            <div class="banner-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.3) 100%); z-index: 2;"></div>
            
            <!-- Content -->
            <div class="banner-content" style="position: relative; z-index: 3; height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
              <!-- Top Section - Tag (optional) -->
              <?php if ($banner_title) { ?>
              <div class="banner-tag" style="margin-bottom: 15px;">
                <span style="background: rgba(255, 255, 255, 0.95); color: #333; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block;">
                  <?php echo htmlspecialchars($banner_title); ?>
                </span>
              </div>
              <?php } ?>
              
              <!-- Middle Section - Title and Description -->
              <div class="banner-text" style="flex: 1; display: flex; flex-direction: column; justify-content: center;">
                <?php if ($banner_title) { ?>
                <h2 class="banner-headline" style="color: #fff; font-size: 32px; font-weight: 700; margin: 0 0 15px 0; line-height: 1.2; text-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                  <?php echo htmlspecialchars($banner_title); ?>
                </h2>
                <?php } ?>
                
                <?php if ($banner_blurb) { ?>
                <p class="banner-description" style="color: #fff; font-size: 16px; line-height: 1.5; margin: 0 0 25px 0; text-shadow: 0 1px 4px rgba(0,0,0,0.3); max-width: 90%;">
                  <?php echo htmlspecialchars($banner_blurb); ?>
                </p>
                <?php } ?>
              </div>
              
              <!-- Bottom Section - Shop Now Button -->
              <div class="banner-action" style="margin-top: auto;">
                <a href="<?php echo htmlspecialchars($banner_link); ?>" class="shop-now-btn" style="display: inline-block; background: #377dff; color: #fff; padding: 12px 30px; border-radius: 4px; text-decoration: none; font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(55, 125, 255, 0.3);">
                  Shop Now
                </a>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
        <?php } ?>
      </div>
    </div>

    <?php if (isset($blurb) && $blurb) { ?>
    <p class="banner-tab-note" style="padding: 15px;">
      <?php echo $blurb; ?>
    </p>
    <?php } ?>
  </div>
</div>

<style>
/* Promotional Banner Styles */
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

/* Responsive Styles */
@media (max-width: 991px) {
  .promotional-banner {
    height: 450px !important;
    padding: 25px 20px !important;
  }
  
  .banner-headline {
    font-size: 28px !important;
  }
  
  .banner-description {
    font-size: 15px !important;
  }
}

@media (max-width: 767px) {
  .promotional-banner {
    height: 400px !important;
    padding: 20px 15px !important;
    margin-bottom: 15px !important;
  }
  
  .banner-headline {
    font-size: 24px !important;
    margin-bottom: 12px !important;
  }
  
  .banner-description {
    font-size: 14px !important;
    margin-bottom: 20px !important;
  }
  
  .shop-now-btn {
    padding: 10px 24px !important;
    font-size: 13px !important;
  }
}

@media (max-width: 575px) {
  .promotional-banner {
    height: 350px !important;
  }
  
  .banner-headline {
    font-size: 20px !important;
  }
  
  .banner-description {
    font-size: 13px !important;
  }
}
</style>
<?php } ?>

