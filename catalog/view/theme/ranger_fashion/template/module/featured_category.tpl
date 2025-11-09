<div class="section section-resource-list">
  <div class="section-resource-list__content">
    <div class="group-block group-block--height-fit group-block--width-fill">
      <div class="group-block-content layout-panel-flex layout-panel-flex--column">
        <div class="text-block text-block--align-center">
          <h3>Shop by collection</h3>
        </div>
      </div>
    </div>
    
    <div class="category-wrapper resource-list resource-list--grid">
      <?php foreach ($categories as $category) { ?>
      <div class="category-item collection-card">
        <a class="collection-card__link" href="<?php echo $category['href']; ?>">
          <span class="visually-hidden"><?php echo $category['name']; ?></span>
        </a>
        <div class="collection-card__inner">
          <div class="collection-card__image">
            <img src="<?php echo $category['icon']; ?>" alt="<?php echo $category['name']; ?>" class="image-block__image" loading="lazy"/>
          </div>
          <div class="collection-card__content">
            <div class="text-block">
              <p><?php echo $category['name']; ?></p>
            </div>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>