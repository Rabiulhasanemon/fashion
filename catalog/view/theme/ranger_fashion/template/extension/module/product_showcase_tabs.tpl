<div class="pst-section" id="<?php echo $module_uid; ?>">
  <div class="pst-container">
    <?php if ($tabs) { ?>
    <!-- Tab Navigation -->
    <div class="pst-tab-nav">
      <ul class="pst-tab-list">
        <?php $first = true; ?>
        <?php foreach ($tabs as $tab) { ?>
        <li class="pst-tab-item <?php echo $first ? 'active' : ''; ?>" data-tab-id="<?php echo $tab['id']; ?>">
          <?php echo $tab['title']; ?>
        </li>
        <?php $first = false; ?>
        <?php } ?>
      </ul>
    </div>

    <!-- Tab Content -->
    <div class="pst-tab-content">
      <div class="pst-loading" style="display: none;">
        <i class="fa fa-spinner fa-spin"></i> Loading products...
      </div>
      <div class="pst-products-wrapper">
        <button type="button" class="pst-nav-btn pst-prev" style="display: none;">
          <i class="fa fa-chevron-left"></i>
        </button>
        <div class="pst-products-container">
          <div class="pst-products-grid"></div>
        </div>
        <button type="button" class="pst-nav-btn pst-next" style="display: none;">
          <i class="fa fa-chevron-right"></i>
        </button>
      </div>
      <div class="pst-no-products" style="display: none;">
        <p>No products found in this category.</p>
      </div>
    </div>
    <?php } ?>
  </div>
</div>

<style>
.pst-section {
  padding: 30px 0;
  background-color: #ffffff;
  width: 100%;
  margin: 0;
}

.pst-container {
  max-width: 80%;
  margin: 0 auto;
  padding: 0 20px;
  width: 100%;
  box-sizing: border-box;
}

/* Mobile: Full width */
@media (max-width: 767px) {
  .pst-container {
    max-width: 100% !important;
    padding: 0 15px !important;
  }
}

/* Tab Navigation */
.pst-tab-nav {
  margin-bottom: 20px;
  border-bottom: 2px solid #e0e0e0;
}

.pst-tab-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-wrap: wrap;
  gap: 0;
}

.pst-tab-item {
  padding: 12px 25px;
  font-size: 15px;
  font-weight: 600;
  color: #666;
  cursor: pointer;
  border-bottom: 3px solid transparent;
  transition: all 0.3s ease;
  position: relative;
  bottom: -2px;
}

.pst-tab-item:hover {
  color: #ff6b00;
}

.pst-tab-item.active {
  color: #ff6b00;
  border-bottom-color: #ff6b00;
}

/* Tab Content */
.pst-tab-content {
  position: relative;
  min-height: 300px;
}

.pst-loading {
  text-align: center;
  padding: 60px 20px;
  font-size: 18px;
  color: #666;
}

.pst-loading i {
  font-size: 32px;
  margin-bottom: 10px;
  display: block;
}

.pst-no-products {
  text-align: center;
  padding: 60px 20px;
  font-size: 16px;
  color: #999;
}

/* Products Wrapper with Navigation */
.pst-products-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  gap: 15px;
}

.pst-nav-btn {
  flex-shrink: 0;
  width: 40px;
  height: 40px;
  border: 1px solid #ddd;
  background: #fff;
  border-radius: 50%;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  color: #666;
  z-index: 10;
}

.pst-nav-btn:hover {
  background: #ff6b00;
  border-color: #ff6b00;
  color: #fff;
}

.pst-nav-btn:disabled {
  opacity: 0.3;
  cursor: not-allowed;
  background: #f5f5f5;
}

.pst-nav-btn:disabled:hover {
  background: #f5f5f5;
  border-color: #ddd;
  color: #666;
}

/* Products Container */
.pst-products-container {
  flex: 1;
  overflow: hidden;
  position: relative;
}

.pst-products-grid {
  display: flex;
  gap: 8px;
  transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Product Card */
.pst-product-card {
  flex: 0 0 calc(16.666% - 6.67px); /* 6 items: 100% / 6 = 16.666%. Gap: 8px * 5 gaps / 6 = 6.67px */
  background: #fff;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease-out;
  display: flex;
  flex-direction: column;
  border: 1px solid #f0f0f0;
}

.pst-product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

.pst-product-image {
  --gallery-aspect-ratio: 1.0;
  position: relative;
  overflow: hidden;
  background: #fff;
  padding-top: 100%;
  aspect-ratio: 1.0;
  border-radius: 8px 8px 0 0;
  width: 100%;
  transition: transform 0.3s ease-out;
}
.pst-product-card:hover .pst-product-image {
  transform: scale(1.02);
}

.pst-product-image img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: contain;
  image-rendering: -webkit-optimize-contrast;
  image-rendering: crisp-edges;
  image-rendering: high-quality;
  aspect-ratio: inherit;
}

.pst-discount-badge {
  position: absolute;
  top: 10px;
  left: 10px;
  background: #ff6b00;
  color: #fff;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 600;
  z-index: 2;
}

.pst-product-info {
  padding: 12px;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.pst-category-name {
  font-size: 12px;
  color: #999;
  margin-bottom: 5px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.pst-product-name {
  font-size: 13px;
  font-weight: 500;
  color: #333;
  margin-bottom: 6px;
  text-decoration: none;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  line-height: 1.3;
  min-height: 34px;
}

.pst-product-name:hover {
  color: #ff6b00;
}

.pst-rating {
  display: flex;
  gap: 2px;
  margin-bottom: 6px;
}

.pst-rating .star {
  color: #ddd;
  font-size: 12px;
}

.pst-rating .star.filled {
  color: #ffa500;
}

.pst-rating .star:before {
  content: "★";
}

.pst-product-price {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: auto;
}

.pst-price-new {
  font-size: 16px;
  font-weight: 700;
  color: #ff6b00;
}

.pst-price-old {
  font-size: 13px;
  color: #999;
  text-decoration: line-through;
}

.pst-price-current {
  font-size: 16px;
  font-weight: 700;
  color: #333;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .pst-product-card {
    flex: 0 0 calc(25% - 6px);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
  }
  .pst-product-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
  }
  .pst-products-grid {
    gap: 8px;
  }
}

@media (max-width: 992px) {
  .pst-section {
    padding: 25px 0;
  }
  .pst-product-card {
    flex: 0 0 calc(33.333% - 5.33px);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    border-radius: 6px;
  }
  .pst-product-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
  }
  .pst-products-grid {
    gap: 8px;
  }
  .pst-tab-nav {
    margin-bottom: 15px;
  }
  .pst-tab-item {
    padding: 10px 18px;
    font-size: 14px;
  }
  .pst-product-image {
    padding-top: 100%;
    aspect-ratio: 1.0;
    border-radius: 6px 6px 0 0;
  }
}

@media (max-width: 749px) {
  .pst-section {
    padding: 20px 0;
  }
  .pst-product-card {
    flex: 0 0 calc(50% - 4px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
    border-radius: 6px;
  }
  .pst-product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
  }
  .pst-products-grid {
    gap: 8px;
  }
  .pst-tab-nav {
    margin-bottom: 12px;
  }
  .pst-tab-item {
    padding: 8px 12px;
    font-size: 13px;
  }
  .pst-product-image {
    padding-top: 100%;
    aspect-ratio: 1.0;
    border-radius: 6px 6px 0 0;
  }
  .pst-product-info {
    padding: 10px;
  }
  .pst-nav-btn {
    width: 35px;
    height: 35px;
    font-size: 14px;
  }
}

@media (max-width: 576px) {
  .pst-section {
    padding: 15px 0;
  }
  
  .pst-product-card {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    border-radius: 5px;
  }
  
  .pst-product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.12);
  }
  
  .pst-tab-nav {
    margin-bottom: 15px;
  }
  
  .pst-tab-item {
    padding: 6px 10px;
    font-size: 12px;
  }
  
  .pst-products-wrapper {
    gap: 6px;
  }
  
  .pst-products-grid {
    gap: 6px;
  }
  
  .pst-product-image {
    padding-top: 100%;
    aspect-ratio: 1.0;
    border-radius: 5px 5px 0 0;
  }
  
  .pst-product-info {
    padding: 8px;
  }
  
  .pst-product-name {
    font-size: 12px;
    min-height: 30px;
    margin-bottom: 4px;
  }
  
  .pst-price-new,
  .pst-price-current {
    font-size: 14px;
  }
  
  .pst-nav-btn {
    width: 30px;
    height: 30px;
    font-size: 12px;
  }
}
</style>

<script>
(function() {
  var root = document.getElementById('<?php echo $module_uid; ?>');
  if (!root || root.dataset.pstInitialized) return;
  root.dataset.pstInitialized = 'true';

  var moduleId = <?php echo $module_id; ?>;
  var ajaxUrl = '<?php echo $ajax_url; ?>';
  var tabs = <?php echo json_encode($tabs); ?>;
  
  var tabItems = root.querySelectorAll('.pst-tab-item');
  var productsGrid = root.querySelector('.pst-products-grid');
  var loadingEl = root.querySelector('.pst-loading');
  var noProductsEl = root.querySelector('.pst-no-products');
  var prevBtn = root.querySelector('.pst-prev');
  var nextBtn = root.querySelector('.pst-next');
  
  var currentTabId = tabs.length > 0 ? tabs[0].id : null;
  var currentScroll = 0;
  var cardWidth = 0;
  var visibleCards = 5;

  function showLoading() {
    loadingEl.style.display = 'block';
    productsGrid.parentElement.parentElement.style.display = 'none';
    noProductsEl.style.display = 'none';
  }

  function hideLoading() {
    loadingEl.style.display = 'none';
  }

  function showProducts() {
    productsGrid.parentElement.parentElement.style.display = 'flex';
    noProductsEl.style.display = 'none';
  }

  function showNoProducts() {
    productsGrid.parentElement.parentElement.style.display = 'none';
    noProductsEl.style.display = 'block';
  }

  function loadTabProducts(tabId) {
    showLoading();
    currentScroll = 0;
    productsGrid.style.transform = 'translateX(0)';

    var xhr = new XMLHttpRequest();
    xhr.open('GET', ajaxUrl + '&tab_id=' + tabId + '&module_id=' + moduleId, true);
    xhr.onload = function() {
      hideLoading();
      if (xhr.status === 200) {
        try {
          var response = JSON.parse(xhr.responseText);
          if (response.success && response.products && response.products.length > 0) {
            renderProducts(response.products);
            showProducts();
            updateNavButtons();
          } else {
            showNoProducts();
          }
        } catch (e) {
          console.error('Error parsing JSON:', e);
          showNoProducts();
        }
      } else {
        showNoProducts();
      }
    };
    xhr.onerror = function() {
      hideLoading();
      showNoProducts();
    };
    xhr.send();
  }

  function renderProducts(products) {
    productsGrid.innerHTML = '';
    
    products.forEach(function(product) {
      var card = document.createElement('div');
      card.className = 'pst-product-card';
      
      var html = '<div class="pst-product-thumb">';
      html += '<a href="' + product.href + '"><img src="' + product.thumb + '" alt="' + product.name + '" /></a>';
      if (product.discount) {
        html += '<span class="pst-discount-badge">-' + product.discount + '%</span>';
      }
      html += '</div>';
      html += '<div class="pst-product-info">';
      if (product.category_name) {
        html += '<span class="pst-category-name">' + product.category_name + '</span>';
      }
      html += '<a href="' + product.href + '" class="pst-product-name">' + product.name + '</a>';
      html += '<div class="pst-rating">';
      for (var i = 1; i <= 5; i++) {
        html += '<span class="star' + (i <= product.rating ? ' filled' : '') + '"></span>';
      }
      html += '</div>';
      html += '<div class="pst-product-price">';
      if (product.special) {
        html += '<span class="pst-price-new">' + product.special + '</span>';
        html += '<span class="pst-price-old">' + product.price + '</span>';
      } else if (product.price) {
        html += '<span class="pst-price-current">' + product.price + '</span>';
      }
      html += '</div>';
      html += '</div>';
      
      card.innerHTML = html;
      productsGrid.appendChild(card);
    });

    // Calculate card width after rendering
    setTimeout(function() {
      var firstCard = productsGrid.querySelector('.pst-product-card');
      if (firstCard) {
        cardWidth = firstCard.offsetWidth + 15; // including gap
        updateNavButtons();
      }
    }, 100);
  }

  function updateNavButtons() {
    var totalCards = productsGrid.children.length;
    var containerWidth = productsGrid.parentElement.offsetWidth;
    var gridWidth = productsGrid.scrollWidth;
    
    if (gridWidth <= containerWidth) {
      prevBtn.style.display = 'none';
      nextBtn.style.display = 'none';
      return;
    }
    
    prevBtn.style.display = 'flex';
    nextBtn.style.display = 'flex';
    
    prevBtn.disabled = currentScroll <= 0;
    nextBtn.disabled = Math.abs(currentScroll) >= (gridWidth - containerWidth);
  }

  var autoScrollInterval = null;

  function scrollProducts(direction) {
    var containerWidth = productsGrid.parentElement.offsetWidth;
    var gridWidth = productsGrid.scrollWidth;
    var scrollAmount = cardWidth * 2; // Scroll 2 cards at a time
    
    if (direction === 'next') {
      currentScroll -= scrollAmount;
      var maxScroll = -(gridWidth - containerWidth);
      if (currentScroll < maxScroll) {
        currentScroll = maxScroll;
      }
    } else {
      currentScroll += scrollAmount;
      if (currentScroll > 0) {
        currentScroll = 0;
      }
    }
    
    productsGrid.style.transform = 'translateX(' + currentScroll + 'px)';
    updateNavButtons();
    
    // Check if we've reached the end, then reset to beginning
    setTimeout(function() {
      if (direction === 'next' && Math.abs(currentScroll) >= (gridWidth - containerWidth - 10)) {
        setTimeout(function() {
          currentScroll = 0;
          productsGrid.style.transform = 'translateX(0)';
          updateNavButtons();
        }, 500);
      }
    }, 600);
  }

  function startAutoScroll() {
    // Clear any existing interval
    if (autoScrollInterval) {
      clearInterval(autoScrollInterval);
    }
    
    // Auto-scroll every 3 seconds
    autoScrollInterval = setInterval(function() {
      scrollProducts('next');
    }, 3000);
  }

  function stopAutoScroll() {
    if (autoScrollInterval) {
      clearInterval(autoScrollInterval);
      autoScrollInterval = null;
    }
  }

  // Tab click handlers
  tabItems.forEach(function(tab) {
    tab.addEventListener('click', function() {
      var tabId = parseInt(this.getAttribute('data-tab-id'));
      
      // Update active tab
      tabItems.forEach(function(t) { t.classList.remove('active'); });
      this.classList.add('active');
      
      // Load products
      currentTabId = tabId;
      loadTabProducts(tabId);
    });
  });

  // Navigation button handlers
  if (prevBtn && nextBtn) {
    prevBtn.addEventListener('click', function() {
      stopAutoScroll();
      scrollProducts('prev');
      // Restart auto-scroll after 5 seconds of inactivity
      setTimeout(startAutoScroll, 5000);
    });
    
    nextBtn.addEventListener('click', function() {
      stopAutoScroll();
      scrollProducts('next');
      // Restart auto-scroll after 5 seconds of inactivity
      setTimeout(startAutoScroll, 5000);
    });
  }

  // Pause auto-scroll on hover
  root.addEventListener('mouseenter', function() {
    stopAutoScroll();
  });

  // Resume auto-scroll when mouse leaves
  root.addEventListener('mouseleave', function() {
    startAutoScroll();
  });

  // Start auto-scrolling on page load
  startAutoScroll();

  // Window resize handler
  var resizeTimer;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
      currentScroll = 0;
      productsGrid.style.transform = 'translateX(0)';
      updateNavButtons();
    }, 250);
  });

  // Load first tab on init
  if (currentTabId) {
    loadTabProducts(currentTabId);
  }
})();
</script>









