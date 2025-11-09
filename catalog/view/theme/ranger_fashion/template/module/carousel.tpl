<div class="home-section featured-deals banner-fullscreen" style="width: 95vw; margin-left: calc(-47.5vw + 50%); margin-right: calc(-47.5vw + 50%); padding: 0; margin-top: 0; margin-bottom: 0;">
  <div class="container" style="max-width: 100%; padding: 0 20px; box-sizing: border-box;">
    <p class="home-section-blurb">We believe in using only the finest and 100% natural ingredients,  making new stuff the old fashioned way.</p>
    <div class="f-items-wrapper">
    <?php foreach ($banners as $banner) { ?>
    <div class="f-item">
      <div class="f-item-img">

        <?php if ($banner['link']) { ?>
        <a href="<?php echo $banner['link']; ?>">
          <img class="mask" src="catalog/view/theme/ribana/images/category-mask.svg?v=1">

          <img src="<?php echo $banner['image']; ?>" height="200" width="200" alt="<?php echo $banner['title']; ?>" class="cat-image" />
        </a>
        <?php } else { ?>
          <img class="mask" src="catalog/view/theme/ribana/images/category-mask.svg">
          <img src="<?php echo $banner['image']; ?>" height="200" width="200" alt="<?php echo $banner['title']; ?>" class="cat-image" />
        <?php } ?>
      </div>
    </div>
    <?php } ?>
    </div>
  </div>
</div>