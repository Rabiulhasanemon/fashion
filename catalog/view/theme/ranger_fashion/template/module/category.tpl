<div id="cat-mod-wrapper" class="cat-mod-section">
  <div class="cat-mod-container">
    <div class="cat-mod-header">
      <h3 class="cat-mod-title"><?php echo isset($heading_title) ? $heading_title : 'Categories'; ?></h3>
    </div>
    
    <div class="cat-mod-content">
      <ul class="cat-mod-list">
        <?php foreach ($categories as $category) { ?>
        <li class="cat-mod-item <?php echo ($category['category_id'] == $category_id) ? 'cat-mod-active' : ''; ?>">
          <a href="<?php echo $category['href']; ?>" class="cat-mod-link">
            <span class="cat-mod-name"><?php echo htmlspecialchars($category['name']); ?></span>
            <?php if ($category['children']) { ?>
              <i class="fa fa-chevron-down cat-mod-arrow"></i>
            <?php } ?>
          </a>
          
          <?php if ($category['children']) { ?>
          <ul class="cat-mod-submenu <?php echo ($category['category_id'] == $category_id) ? 'cat-mod-open' : ''; ?>">
            <?php foreach ($category['children'] as $child) { ?>
            <li class="cat-mod-subitem <?php echo (isset($child['category_id']) && $child['category_id'] == $child_id) ? 'cat-mod-subactive' : ''; ?>">
              <a href="<?php echo $child['href']; ?>" class="cat-mod-sublink">
                <span class="cat-mod-subname"><?php echo htmlspecialchars($child['name']); ?></span>
                <?php if(isset($child['children']) && $child['children']) { ?>
                  <i class="fa fa-chevron-down cat-mod-subarrow"></i>
                <?php } ?>
              </a>
              
              <?php if(isset($child['children']) && $child['children']) { ?>
              <ul class="cat-mod-childmenu">
                <?php foreach ($child['children'] as $child2) { ?>
                <li class="cat-mod-childitem <?php echo (isset($child2['category_id']) && $child2['category_id'] == $child_id_2) ? 'cat-mod-childactive' : ''; ?>">
                  <a href="<?php echo $child2['href']; ?>" class="cat-mod-childlink">
                    <?php echo htmlspecialchars($child2['name']); ?>
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
</div>

<style>
/* Premium Category Module - Unique cat-mod- Classes */
#cat-mod-wrapper.cat-mod-section {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    margin-bottom: 30px;
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
    transition: box-shadow 0.3s ease;
}

#cat-mod-wrapper.cat-mod-section:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.cat-mod-container {
    width: 100%;
}

.cat-mod-header {
    padding: 20px 25px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    background: linear-gradient(135deg, #fff 0%, #fafafa 100%);
}

.cat-mod-title {
    font-size: 20px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
    padding-bottom: 12px;
    position: relative;
    letter-spacing: -0.02em;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.cat-mod-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 3px;
    background: linear-gradient(90deg, #ff6b9d, #ff8c9f);
    border-radius: 2px;
}

.cat-mod-content {
    padding: 10px 0;
}

.cat-mod-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.cat-mod-item {
    border-bottom: 1px solid rgba(0,0,0,0.03);
    position: relative;
}

.cat-mod-item:last-child {
    border-bottom: none;
}

.cat-mod-link {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 25px;
    color: #333;
    text-decoration: none;
    font-weight: 500;
    font-size: 15px;
    transition: all 0.3s ease;
    position: relative;
    background: #fff;
}

.cat-mod-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 0;
    background: linear-gradient(90deg, #ff6b9d, #ff8c9f);
    transition: width 0.3s ease;
}

.cat-mod-link:hover,
.cat-mod-item.cat-mod-active > .cat-mod-link {
    color: #ff6b9d;
    background: linear-gradient(90deg, #fff9fa 0%, #fff 100%);
    padding-left: 35px;
}

.cat-mod-link:hover::before,
.cat-mod-item.cat-mod-active > .cat-mod-link::before {
    width: 4px;
}

.cat-mod-name {
    flex: 1;
    transition: transform 0.2s ease;
}

.cat-mod-link:hover .cat-mod-name {
    transform: translateX(3px);
}

.cat-mod-arrow {
    font-size: 11px;
    color: #ccc;
    transition: all 0.3s ease;
    margin-left: 10px;
}

.cat-mod-item.cat-mod-active > .cat-mod-link .cat-mod-arrow {
    transform: rotate(180deg);
    color: #ff6b9d;
}

.cat-mod-link:hover .cat-mod-arrow {
    color: #ff6b9d;
}

/* Submenu Styles */
.cat-mod-submenu {
    list-style: none;
    padding: 0;
    margin: 0;
    background: #fafafa;
    display: none;
    border-top: 1px solid rgba(0,0,0,0.03);
}

.cat-mod-submenu.cat-mod-open {
    display: block;
    animation: catModSlideDown 0.3s ease;
}

@keyframes catModSlideDown {
    from {
        opacity: 0;
        max-height: 0;
    }
    to {
        opacity: 1;
        max-height: 1000px;
    }
}

.cat-mod-subitem {
    border-bottom: 1px solid rgba(0,0,0,0.02);
    position: relative;
}

.cat-mod-subitem:last-child {
    border-bottom: none;
}

.cat-mod-sublink {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 25px 12px 45px;
    color: #666;
    text-decoration: none;
    font-size: 14px;
    font-weight: 400;
    transition: all 0.3s ease;
    position: relative;
}

.cat-mod-sublink::before {
    content: '';
    position: absolute;
    left: 30px;
    top: 50%;
    transform: translateY(-50%);
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #ddd;
    transition: all 0.3s ease;
}

.cat-mod-sublink:hover,
.cat-mod-subitem.cat-mod-subactive > .cat-mod-sublink {
    color: #ff6b9d;
    background: #fff;
    padding-left: 50px;
}

.cat-mod-sublink:hover::before,
.cat-mod-subitem.cat-mod-subactive > .cat-mod-sublink::before {
    background: #ff6b9d;
    width: 8px;
    height: 8px;
    left: 35px;
}

.cat-mod-subname {
    flex: 1;
    transition: transform 0.2s ease;
}

.cat-mod-sublink:hover .cat-mod-subname {
    transform: translateX(3px);
}

.cat-mod-subarrow {
    font-size: 10px;
    color: #ccc;
    transition: all 0.3s ease;
    margin-left: 10px;
}

.cat-mod-subitem.cat-mod-subactive > .cat-mod-sublink .cat-mod-subarrow {
    transform: rotate(180deg);
    color: #ff6b9d;
}

.cat-mod-sublink:hover .cat-mod-subarrow {
    color: #ff6b9d;
}

/* Child Menu Styles */
.cat-mod-childmenu {
    list-style: none;
    padding: 0;
    margin: 0;
    background: #f5f5f5;
    display: none;
    border-top: 1px solid rgba(0,0,0,0.02);
}

.cat-mod-subitem.cat-mod-subactive > .cat-mod-childmenu {
    display: block;
    animation: catModSlideDown 0.3s ease;
}

.cat-mod-childitem {
    border-bottom: 1px solid rgba(0,0,0,0.01);
}

.cat-mod-childitem:last-child {
    border-bottom: none;
}

.cat-mod-childlink {
    display: block;
    padding: 10px 25px 10px 65px;
    color: #888;
    text-decoration: none;
    font-size: 13px;
    font-weight: 400;
    transition: all 0.3s ease;
    position: relative;
}

.cat-mod-childlink::before {
    content: '→';
    position: absolute;
    left: 50px;
    color: #ccc;
    font-size: 12px;
    transition: all 0.3s ease;
}

.cat-mod-childlink:hover,
.cat-mod-childitem.cat-mod-childactive > .cat-mod-childlink {
    color: #ff6b9d;
    background: #fff;
    padding-left: 70px;
}

.cat-mod-childlink:hover::before,
.cat-mod-childitem.cat-mod-childactive > .cat-mod-childlink::before {
    color: #ff6b9d;
    transform: translateX(3px);
}

/* Responsive Design */
@media (max-width: 992px) {
    .cat-mod-header {
        padding: 18px 20px;
    }
    
    .cat-mod-title {
        font-size: 18px;
        padding-bottom: 10px;
    }
    
    .cat-mod-title::after {
        width: 45px;
        height: 2.5px;
    }
    
    .cat-mod-link {
        padding: 12px 20px;
        font-size: 14px;
    }
    
    .cat-mod-link:hover,
    .cat-mod-item.cat-mod-active > .cat-mod-link {
        padding-left: 28px;
    }
    
    .cat-mod-sublink {
        padding: 10px 20px 10px 40px;
        font-size: 13px;
    }
    
    .cat-mod-sublink:hover,
    .cat-mod-subitem.cat-mod-subactive > .cat-mod-sublink {
        padding-left: 45px;
    }
    
    .cat-mod-sublink::before {
        left: 25px;
    }
    
    .cat-mod-sublink:hover::before,
    .cat-mod-subitem.cat-mod-subactive > .cat-mod-sublink::before {
        left: 30px;
    }
    
    .cat-mod-childlink {
        padding: 8px 20px 8px 55px;
        font-size: 12px;
    }
    
    .cat-mod-childlink:hover,
    .cat-mod-childitem.cat-mod-childactive > .cat-mod-childlink {
        padding-left: 60px;
    }
    
    .cat-mod-childlink::before {
        left: 40px;
    }
}

@media (max-width: 768px) {
    #cat-mod-wrapper.cat-mod-section {
        margin-bottom: 20px;
        border-radius: 10px;
    }
    
    .cat-mod-header {
        padding: 16px 18px;
    }
    
    .cat-mod-title {
        font-size: 17px;
        padding-bottom: 8px;
    }
    
    .cat-mod-title::after {
        width: 40px;
        height: 2px;
    }
    
    .cat-mod-content {
        padding: 8px 0;
    }
    
    .cat-mod-link {
        padding: 12px 18px;
        font-size: 14px;
    }
    
    .cat-mod-link:hover,
    .cat-mod-item.cat-mod-active > .cat-mod-link {
        padding-left: 25px;
    }
    
    .cat-mod-sublink {
        padding: 10px 18px 10px 38px;
        font-size: 13px;
    }
    
    .cat-mod-sublink:hover,
    .cat-mod-subitem.cat-mod-subactive > .cat-mod-sublink {
        padding-left: 42px;
    }
    
    .cat-mod-sublink::before {
        left: 23px;
    }
    
    .cat-mod-sublink:hover::before,
    .cat-mod-subitem.cat-mod-subactive > .cat-mod-sublink::before {
        left: 28px;
    }
    
    .cat-mod-childlink {
        padding: 8px 18px 8px 50px;
        font-size: 12px;
    }
    
    .cat-mod-childlink:hover,
    .cat-mod-childitem.cat-mod-childactive > .cat-mod-childlink {
        padding-left: 55px;
    }
    
    .cat-mod-childlink::before {
        left: 38px;
    }
}

@media (max-width: 480px) {
    .cat-mod-header {
        padding: 14px 15px;
    }
    
    .cat-mod-title {
        font-size: 16px;
    }
    
    .cat-mod-link {
        padding: 11px 15px;
        font-size: 13px;
    }
    
    .cat-mod-link:hover,
    .cat-mod-item.cat-mod-active > .cat-mod-link {
        padding-left: 22px;
    }
    
    .cat-mod-sublink {
        padding: 9px 15px 9px 35px;
        font-size: 12px;
    }
    
    .cat-mod-sublink:hover,
    .cat-mod-subitem.cat-mod-subactive > .cat-mod-sublink {
        padding-left: 38px;
    }
    
    .cat-mod-sublink::before {
        left: 20px;
    }
    
    .cat-mod-sublink:hover::before,
    .cat-mod-subitem.cat-mod-subactive > .cat-mod-sublink::before {
        left: 25px;
    }
    
    .cat-mod-childlink {
        padding: 7px 15px 7px 45px;
        font-size: 11px;
    }
    
    .cat-mod-childlink:hover,
    .cat-mod-childitem.cat-mod-childactive > .cat-mod-childlink {
        padding-left: 50px;
    }
    
    .cat-mod-childlink::before {
        left: 33px;
    }
}

/* Smooth transitions for all interactive elements */
.cat-mod-link,
.cat-mod-sublink,
.cat-mod-childlink {
    -webkit-tap-highlight-color: transparent;
}

/* Accessibility improvements */
.cat-mod-link:focus,
.cat-mod-sublink:focus,
.cat-mod-childlink:focus {
    outline: 2px solid #ff6b9d;
    outline-offset: -2px;
}

/* Print styles */
@media print {
    #cat-mod-wrapper.cat-mod-section {
        box-shadow: none;
        border: 1px solid #ddd;
    }
    
    .cat-mod-submenu,
    .cat-mod-childmenu {
        display: block !important;
    }
}
</style>

<script>
(function() {
    // Add click handlers for mobile submenu toggle
    var categoryModule = document.getElementById('cat-mod-wrapper');
    if (!categoryModule) return;
    
    var categoryItems = categoryModule.querySelectorAll('.cat-mod-item');
    
    categoryItems.forEach(function(item) {
        var link = item.querySelector('.cat-mod-link');
        var submenu = item.querySelector('.cat-mod-submenu');
        
        if (link && submenu) {
            link.addEventListener('click', function(e) {
                // Only toggle on mobile or if not navigating
                if (window.innerWidth <= 768) {
                    var isActive = item.classList.contains('cat-mod-active');
                    
                    // Close all other submenus
                    categoryItems.forEach(function(otherItem) {
                        if (otherItem !== item) {
                            otherItem.classList.remove('cat-mod-active');
                            var otherSubmenu = otherItem.querySelector('.cat-mod-submenu');
                            if (otherSubmenu) {
                                otherSubmenu.classList.remove('cat-mod-open');
                            }
                        }
                    });
                    
                    // Toggle current item
                    if (isActive) {
                        item.classList.remove('cat-mod-active');
                        submenu.classList.remove('cat-mod-open');
                    } else {
                        item.classList.add('cat-mod-active');
                        submenu.classList.add('cat-mod-open');
                        e.preventDefault();
                    }
                }
            });
        }
        
        // Handle submenu items with children
        var subitems = item.querySelectorAll('.cat-mod-subitem');
        subitems.forEach(function(subitem) {
            var sublink = subitem.querySelector('.cat-mod-sublink');
            var childmenu = subitem.querySelector('.cat-mod-childmenu');
            
            if (sublink && childmenu) {
                sublink.addEventListener('click', function(e) {
                    if (window.innerWidth <= 768) {
                        var isSubActive = subitem.classList.contains('cat-mod-subactive');
                        
                        // Close all other child menus in this submenu
                        subitems.forEach(function(otherSubitem) {
                            if (otherSubitem !== subitem) {
                                otherSubitem.classList.remove('cat-mod-subactive');
                                var otherChildmenu = otherSubitem.querySelector('.cat-mod-childmenu');
                                if (otherChildmenu) {
                                    otherChildmenu.classList.remove('cat-mod-open');
                                }
                            }
                        });
                        
                        // Toggle current subitem
                        if (isSubActive) {
                            subitem.classList.remove('cat-mod-subactive');
                            childmenu.classList.remove('cat-mod-open');
                        } else {
                            subitem.classList.add('cat-mod-subactive');
                            childmenu.classList.add('cat-mod-open');
                            e.preventDefault();
                        }
                    }
                });
            }
        });
    });
})();
</script>