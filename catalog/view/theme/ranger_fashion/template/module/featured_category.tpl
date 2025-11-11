<div class="section section-resource-list">
  <div class="section-resource-list__content">
    <div class="group-block group-block--height-fit group-block--width-fill">
      <div class="group-block-content layout-panel-flex layout-panel-flex--column">
        <div class="text-block text-block--align-left">
          <h3 class="cosmetics-module-heading">Shop by collection</h3>
        </div>
<style>
.text-block--align-left { text-align: left !important; }
.cosmetics-module-heading {
  font-size: 28px;
  font-weight: 600;
  color: #1a1a1a;
  line-height: 1.3;
  text-align: left;
  margin: 0;
  padding: 20px 0 16px 0;
  text-transform: none;
  letter-spacing: -0.02em;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  position: relative;
}
.cosmetics-module-heading::after {
  content: '';
  position: absolute;
  bottom: 8px;
  left: 0;
  width: 60px;
  height: 3px;
  background: linear-gradient(90deg, #ff6b9d, #ff8c9f);
  border-radius: 2px;
}
@media (max-width: 992px) {
  .cosmetics-module-heading { font-size: 24px; padding: 18px 0 14px 0; }
  .cosmetics-module-heading::after { width: 50px; height: 2.5px; bottom: 6px; }
}
@media (max-width: 749px) {
  .cosmetics-module-heading { font-size: 22px; padding: 16px 0 12px 0; }
  .cosmetics-module-heading::after { width: 45px; height: 2px; bottom: 5px; }
}
@media (max-width: 576px) {
  .cosmetics-module-heading { font-size: 20px; padding: 14px 0 10px 0; }
  .cosmetics-module-heading::after { width: 40px; height: 2px; bottom: 4px; }
}
</style>
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