<div class="category-sidebar-module">
  <h3 class="category-sidebar__title unified-module-heading cosmetics-module-heading">Categories</h3>
  <div class="category-sidebar__content">
    <ul class="category-sidebar__list">
      <?php foreach ($categories as $category) { ?>
      <li class="category-sidebar__item <?php echo ($category['category_id'] == $category_id) ? 'active' : ''; ?>">
        <a href="<?php echo $category['href']; ?>" class="category-sidebar__link">
          <?php echo $category['name']; ?>
          <?php if ($category['children']) { ?>
            <i class="fa fa-chevron-right arrow-icon"></i>
          <?php } ?>
        </a>
        
        <?php if ($category['children']) { ?>
        <ul class="category-sidebar__submenu <?php echo ($category['category_id'] == $category_id) ? 'open' : ''; ?>">
          <?php foreach ($category['children'] as $child) { ?>
          <li class="category-sidebar__subitem <?php echo (isset($child['category_id']) && $child['category_id'] == $child_id) ? 'active' : ''; ?>">
            <a href="<?php echo $child['href']; ?>" class="category-sidebar__sublink">
              <?php echo $child['name']; ?>
            </a>
            
            <?php if(isset($child['children']) && $child['children']) { ?>
            <ul class="category-sidebar__childmenu">
              <?php foreach ($child['children'] as $child2) { ?>
              <li class="category-sidebar__childitem <?php echo (isset($child2['category_id']) && $child2['category_id'] == $child_id_2) ? 'active' : ''; ?>">
                <a href="<?php echo $child2['href']; ?>" class="category-sidebar__childlink">
                  <?php echo $child2['name']; ?>
                </a>
              </li>
              <?php } ?>
            </ul>
            <?php } ?>
          </li>
          <?php } ?>
        </ul>
        <?php } ?>
      </li>
      <?php } ?>
    </ul>
  </div>
</div>

<style>
/* Premium Category Sidebar */
.category-sidebar-module {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    margin-bottom: 30px;
    overflow: hidden;
    border: 1px solid #f0f0f0;
}

.category-sidebar__title {
    margin: 0;
    padding: 20px 25px;
    font-size: 18px;
    font-weight: 600;
    border-bottom: 1px solid #f0f0f0;
    background: #fff;
}

/* Reuse consistent heading style */
.cosmetics-module-heading {
    font-size: 18px !important;
    padding: 15px 20px !important;
    margin-bottom: 0 !important;
}
.cosmetics-module-heading::after {
    bottom: 0 !important;
    left: 20px !important;
    width: 40px !important;
}

.category-sidebar__list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.category-sidebar__item {
    border-bottom: 1px solid #f9f9f9;
}

.category-sidebar__item:last-child {
    border-bottom: none;
}

.category-sidebar__link {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 25px;
    color: #333;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
    font-size: 15px;
}

.category-sidebar__link:hover,
.category-sidebar__item.active > .category-sidebar__link {
    color: #ff6b9d;
    background: #fff9fa;
    padding-left: 30px;
}

.category-sidebar__link .arrow-icon {
    font-size: 10px;
    color: #ccc;
    transition: transform 0.3s ease;
}

.category-sidebar__item.active > .category-sidebar__link .arrow-icon {
    transform: rotate(90deg);
    color: #ff6b9d;
}

/* Submenu */
.category-sidebar__submenu {
    list-style: none;
    padding: 0;
    margin: 0;
    background: #fcfcfc;
    display: none;
}

.category-sidebar__submenu.open {
    display: block;
}

.category-sidebar__sublink {
    display: block;
    padding: 10px 25px 10px 40px;
    color: #666;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.2s ease;
}

.category-sidebar__sublink:hover,
.category-sidebar__subitem.active > .category-sidebar__sublink {
    color: #ff6b9d;
}

/* Child Menu */
.category-sidebar__childmenu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.category-sidebar__childlink {
    display: block;
    padding: 8px 25px 8px 55px;
    color: #888;
    text-decoration: none;
    font-size: 13px;
    position: relative;
}

.category-sidebar__childlink::before {
    content: '-';
    position: absolute;
    left: 45px;
    color: #ccc;
}

.category-sidebar__childlink:hover,
.category-sidebar__childitem.active > .category-sidebar__childlink {
    color: #ff6b9d;
}
</style>