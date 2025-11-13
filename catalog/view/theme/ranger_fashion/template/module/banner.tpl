<?php if ($banners) { ?>
  <div class="customer-banner-area banner-fullscreen" style="width: 95vw; margin-left: calc(-47.5vw + 50%); margin-right: calc(-47.5vw + 50%); padding: 0; margin-top: 0; margin-bottom: 0;">
    <div class="container" style="padding: 0; max-width: 80%;">
<style>
@media (max-width: 767px) {
  .customer-banner-area .container {
    max-width: 100% !important;
    padding: 0 !important;
  }
}
</style>
      <?php foreach ($banners as $banner) { ?>
        <?php if (isset($banner['group_class'])) { ?>
          <div class="<?php echo $banner['group_class'] ?>">
            <?php foreach ($banner['banners'] as $banner_child) { ?>
              <div class="<?php echo $banner_child['image_class'] ?>" style="margin: 0; padding: 0;">
                <div class="banner-type <?php echo $class; ?>" style="margin: 0; padding: 0; line-height: 0;">
                  <?php if ($banner_child['link']) { ?>
                    <a href="<?php echo $banner_child['link']; ?>" style="display: block; margin: 0; padding: 0;">
                      <img class="img-fluid" src="<?php echo $banner_child['image']; ?>" title="<?php echo isset($banner_child['title']) ? $banner_child['title'] : ''; ?>" alt="<?php echo isset($banner_child['title']) ? $banner_child['title'] : ''; ?>" style="width: 100%; height: auto; display: block; margin: 0; padding: 0; image-rendering: -webkit-optimize-contrast; image-rendering: high-quality;" loading="eager" />
                    </a>
                  <?php } else { ?>
                    <div class="effect" style="margin: 0; padding: 0; line-height: 0;">
                      <img class="img-fluid" src="<?php echo $banner_child['image']; ?>" title="<?php echo isset($banner_child['title']) ? $banner_child['title'] : ''; ?>" alt="<?php echo isset($banner_child['title']) ? $banner_child['title'] : ''; ?>" style="width: 100%; height: auto; display: block; margin: 0; padding: 0; image-rendering: -webkit-optimize-contrast; image-rendering: high-quality;" loading="eager" />
                    </div>
                  <?php } ?>
                </div>
              </div>
            <?php } ?>
          </div>
        <?php } else { ?>
          <div class="<?php echo $banner['image_class'] ?>" style="margin: 0; padding: 0;">
            <div class="banner-type <?php echo $class; ?>" style="margin: 0; padding: 0; line-height: 0;">
              <?php if ($banner['link']) { ?>
                <a href="<?php echo $banner['link']; ?>" style="display: block; margin: 0; padding: 0;">
                  <img class="img-fluid" src="<?php echo $banner['image']; ?>" title="<?php echo isset($banner['title']) ? $banner['title'] : ''; ?>" alt="<?php echo isset($banner['title']) ? $banner['title'] : ''; ?>" style="width: 100%; height: auto; display: block; margin: 0; padding: 0; image-rendering: -webkit-optimize-contrast; image-rendering: high-quality;" loading="eager" />
                </a>
              <?php } else { ?>
                <div class="effect" style="margin: 0; padding: 0; line-height: 0;">
                  <img class="img-fluid" src="<?php echo $banner['image']; ?>" title="<?php echo isset($banner['title']) ? $banner['title'] : ''; ?>" alt="<?php echo isset($banner['title']) ? $banner['title'] : ''; ?>" style="width: 100%; height: auto; display: block; margin: 0; padding: 0; image-rendering: -webkit-optimize-contrast; image-rendering: high-quality;" loading="eager" />
                </div>
              <?php } ?>
            </div>
          </div>
        <?php } ?>
      <?php } ?>
    </div>
  </div>
<?php } ?>