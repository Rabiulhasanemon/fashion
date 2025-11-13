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

    <div class="banner-tab-wrapper" style="margin: 0; padding: 0;">
      <div class="row" style="margin: 0; padding: 0 10px;">
        <?php 
        $banner_count = 0;
        foreach ($banners as $banner) { 
          if ($banner_count >= 3) break; // Limit to exactly 3 banners
          $banner_count++;
        ?>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="padding: 0 10px; margin: 0;">
          <div class="banner-tab-item" style="margin: 0; padding: 0; line-height: 0;">
            <?php if (isset($banner['banner_children']) && !empty($banner['banner_children'])) { ?>
              <?php 
              // Get first banner child for each banner
              $first_child = reset($banner['banner_children']);
              ?>
              <div class="banner-tab-content <?php echo isset($first_child['image_class']) ? $first_child['image_class'] : ''; ?>" style="margin: 0; padding: 0;">
                <?php if (isset($first_child['link']) && $first_child['link']) { ?>
                <a href="<?php echo $first_child['link']; ?>" style="display: block; margin: 0; padding: 0;">
                  <img src="<?php echo $first_child['image']; ?>" alt="<?php echo isset($first_child['title']) ? $first_child['title'] : ''; ?>" class="img-responsive" style="width: 100%; height: auto; display: block; margin: 0; padding: 0;" loading="eager" />
                </a>
                <?php } else { ?>
                <img src="<?php echo $first_child['image']; ?>" alt="<?php echo isset($first_child['title']) ? $first_child['title'] : ''; ?>" class="img-responsive" style="width: 100%; height: auto; display: block; margin: 0; padding: 0;" loading="eager" />
                <?php } ?>
              </div>
            <?php } ?>
          </div>
        </div>
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
<?php } ?>

