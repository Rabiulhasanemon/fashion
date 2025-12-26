<div id="collage-<?php echo $module; ?>" class="collage <?php echo $class;  ?>">
  <?php foreach ($banners as $banner) { ?>
    <?php if(isset($banner['group_class'])) { ?>
        <div class="<?php echo $banner['group_class']?>">
          <?php foreach ($banner['banners'] as $banner_child) { ?>
            <div class="item <?php echo $banner_child['image_class']?>">
              <?php if ($banner_child['link']) { ?>
              <a href="<?php echo $banner_child['link']; ?>"><img src="<?php echo $banner_child['image']; ?>" alt="<?php echo $banner_child['title']; ?>" class="img-responsive" /></a>
              <?php } else { ?>
              <img src="<?php echo $banner_child['image']; ?>" alt="<?php echo $banner_child['title']; ?>" class="img-responsive" />
              <?php } ?>
            </div>
          <?php } ?>
        </div>
    <?php } else { ?>
      <div class="item <?php echo $banner['image_class']?>">
        <?php if ($banner['link']) { ?>
        <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" /></a>
        <?php } else { ?>
        <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" />
        <?php } ?>
      </div>
    <?php } ?>
  <?php } ?>
</div>