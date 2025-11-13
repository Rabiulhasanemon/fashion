<?php if ($products) { ?>
<div class="flash-deal-section banner-fullscreen" style="width: 95vw; margin-left: calc(-47.5vw + 50%); margin-right: calc(-47.5vw + 50%); padding: 0; margin-top: 0; margin-bottom: 0;">
  <div class="flash-deal-header">
    <h2 class="flash-deal-title"><?php echo $heading_title; ?></h2>
  </div>

  <div class="flash-deal-container" style="max-width: 80%; padding: 0 20px;">
    <div class="flash-deal-slider-wrapper">
      <div class="flash-deal-slider">
        <div class="flash-deal-track">
          <?php for ($i = 0; $i < count($products); $i += 2) { ?>
          <div class="flash-deal-row">
            <!-- Navigation Buttons Inside Row -->
            <button type="button" class="flash-nav-btn prev-btn" aria-label="Previous">
              <i class="fa fa-chevron-left"></i>
            </button>
            <button type="button" class="flash-nav-btn next-btn" aria-label="Next">
              <i class="fa fa-chevron-right"></i>
            </button>
            
            <?php for ($j = $i; $j < min($i + 2, count($products)); $j++) { ?>
            <?php $product = $products[$j]; ?>
            <div class="flash-deal-product">
              <!-- LEFT SIDE: Product Image -->
              <div class="product-left">
                <div class="product-image-wrapper">
                  <a href="<?php echo $product['href']; ?>">
                    <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
                  </a>
                  <?php if ($product['discount']) { ?>
                  <span class="discount-badge">-<?php echo (int)$product['discount']; ?>%</span>
                  <?php } ?>
                </div>
                <div class="product-actions">
                  <a href="#" class="action-btn wishlist" data-toggle="tooltip" title="Add to Wish List">
                    <i class="fa fa-heart"></i>
                  </a>
                  <a href="#" class="action-btn compare" data-toggle="tooltip" title="Compare this Product">
                    <i class="fa fa-exchange"></i>
                  </a>
                  <button type="button" class="action-btn cart" onclick="cart.add('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" title="Add to Cart">
                    <i class="fa fa-shopping-cart"></i>
                  </button>
                </div>
              </div>
              
              <!-- RIGHT SIDE: Product Info -->
              <div class="product-right">
                <div class="product-info">
                  <?php if ($product['category_name']) { ?>
                  <p class="product-category"><?php echo $product['category_name']; ?></p>
                  <?php } ?>
                  
                  <h3 class="product-title">
                    <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                  </h3>
                  
                  <div class="product-rating">
                    <?php for ($k = 1; $k <= 5; $k++) { ?>
                    <span class="star<?php echo $k <= $product['rating'] ? ' filled' : ''; ?>"></span>
                    <?php } ?>
                  </div>
                  
                  <div class="product-price">
                    <?php if ($product['special']) { ?>
                    <span class="price-new"><?php echo $product['special']; ?></span>
                    <span class="price-old"><?php echo $product['price']; ?></span>
                    <?php } else { ?>
                    <span class="price-current"><?php echo $product['price']; ?></span>
                    <?php } ?>
                  </div>
                  
                  <?php if (!empty($product['end_date'])) { ?>
                  <div class="countdown-timer" data-end-date="<?php echo htmlspecialchars($product['end_date']); ?>">
                    <div class="countdown-item">
                      <span class="countdown-value" data-days>00</span>
                      <span class="countdown-label">Days</span>
                    </div>
                    <div class="countdown-item">
                      <span class="countdown-value" data-hrs>00</span>
                      <span class="countdown-label">Hrs</span>
                    </div>
                    <div class="countdown-item">
                      <span class="countdown-value" data-min>00</span>
                      <span class="countdown-label">Min</span>
                    </div>
                    <div class="countdown-item">
                      <span class="countdown-value" data-sec>00</span>
                      <span class="countdown-label">Sec</span>
                    </div>
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <?php } ?>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.flash-deal-section {
  padding: 12px 0;
  background: #fff;
  width: 100%;
  margin: 0;
}

.flash-deal-header {
  text-align: left;
  margin-bottom: 0;
  padding: 0 20px;
}

.flash-deal-title,
.flash-deal-title.unified-module-heading,
.flash-deal-title.cosmetics-module-heading {
  margin: 0;
  padding: 20px 0 16px 0;
  font-size: 28px;
  font-weight: 600;
  color: #1a1a1a;
  line-height: 1.3;
  text-align: left;
  text-transform: none;
  letter-spacing: -0.02em;
  position: relative;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}
.flash-deal-title::after {
  content: '';
  position: absolute;
  bottom: 8px;
  left: 0;
  width: 60px;
  height: 3px;
  background: linear-gradient(90deg, #ff6b9d, #ff8c9f);
  border-radius: 2px;
}

.title-underline {
  width: 50px;
  height: 2px;
  background: linear-gradient(90deg, #ff6b00, #ff8c42);
  margin: 6px auto 0;
  border-radius: 2px;
}

.flash-deal-container {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 15px;
  box-sizing: border-box;
}

.flash-deal-slider-wrapper {
  position: relative;
  width: 100%;
}

.flash-deal-slider {
  width: 100%;
  overflow: hidden;
  position: relative;
}

.flash-nav-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 28px;
  height: 28px;
  border: 1px solid #e0e0e0;
  background: rgba(255, 255, 255, 0.98);
  border-radius: 50%;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 10px;
  color: #666;
  z-index: 20;
  box-shadow: 0 2px 4px rgba(0,0,0,0.06);
  backdrop-filter: blur(10px);
}

.flash-nav-btn.prev-btn {
  left: 8px;
}

.flash-nav-btn.next-btn {
  right: 8px;
}

.flash-nav-btn:hover {
  background: #ff6b00;
  border-color: #ff6b00;
  color: #fff;
  transform: translateY(-50%) scale(1.05);
  box-shadow: 0 4px 12px rgba(255, 107, 0, 0.25);
}

.flash-nav-btn:disabled {
  opacity: 0.4;
  cursor: not-allowed;
  pointer-events: none;
}

.flash-deal-track {
  display: flex;
  transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.flash-deal-row {
  display: flex;
  gap: 8px;
  flex: 0 0 100%;
  min-width: 100%;
  position: relative;
  padding: 0;
  margin: 0;
}

.flash-deal-row:not(.active) .flash-nav-btn {
  display: none;
}

.flash-deal-row.active .flash-nav-btn {
  display: flex;
}

.flash-deal-product {
  flex: 0 0 calc(50% - 4px);
  background: #fff;
  border: 1px solid #f0f0f0;
  border-radius: 8px;
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  min-height: 180px;
  display: flex;
  flex-direction: row;
  padding: 0;
  margin: 0;
  gap: 0;
  box-shadow: 0 1px 2px rgba(0,0,0,0.03);
}

.flash-deal-product:hover {
  box-shadow: 0 6px 16px rgba(0,0,0,0.08);
  transform: translateY(-3px);
  border-color: #ff6b00;
}

.product-left {
  flex: 0 0 28%;
  display: flex;
  flex-direction: column;
  position: relative;
  background: #fafafa;
  padding: 0;
  margin: 0;
  overflow: hidden;
}

.product-image-wrapper {
  position: relative;
  width: 100%;
  padding-top: 100%;
  overflow: hidden;
  background: #fff;
  margin: 0;
  padding-left: 0;
  padding-right: 0;
  padding-bottom: 0;
  border-radius: 0;
}

.product-right {
  flex: 0 0 72%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 10px 12px;
}

.product-image-wrapper img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.flash-deal-product:hover .product-image-wrapper img {
  transform: scale(1.05);
}

.discount-badge {
  position: absolute;
  top: 8px;
  right: 8px;
  background: linear-gradient(135deg, #ff6b00, #ff8c42);
  color: #fff;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 11px;
  font-weight: 700;
  z-index: 2;
  box-shadow: 0 2px 4px rgba(255, 107, 0, 0.3);
  letter-spacing: 0.3px;
}

.product-actions {
  position: absolute;
  bottom: 8px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 4px;
  opacity: 0;
  transition: all 0.3s ease;
}

.flash-deal-product:hover .product-actions {
  opacity: 1;
  transform: translateX(-50%);
}

.action-btn {
  width: 26px;
  height: 26px;
  background: rgba(255, 255, 255, 0.95);
  border: 1px solid #e0e0e0;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  color: #666;
  font-size: 10px;
  backdrop-filter: blur(10px);
}

.action-btn:hover {
  background: #ff6b00;
  border-color: #ff6b00;
  color: #fff;
  transform: scale(1.1);
}

.product-info {
  padding: 0;
  display: flex;
  flex-direction: column;
  height: 100%;
  justify-content: center;
  gap: 4px;
}

.product-category {
  font-size: 8px;
  color: #999;
  margin-bottom: 2px;
  text-transform: uppercase;
  letter-spacing: 0.3px;
  font-weight: 500;
}

.product-title {
  margin: 0 0 8px 0;
  font-size: 17px;
  font-weight: 600;
  line-height: 1.4;
  min-height: 44px;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

.product-title a {
  color: #1a1a1a;
  text-decoration: none;
  transition: color 0.3s ease;
}

.product-title a:hover {
  color: #ff6b00;
}

.product-rating {
  display: flex;
  gap: 2px;
  margin-bottom: 5px;
}

.product-rating .star {
  color: #ddd;
  font-size: 10px;
  width: 10px;
}

.product-rating .star.filled {
  color: #ffa500;
}

.product-rating .star.partial {
  color: #ffa500;
  background: linear-gradient(to right, #ffa500 20%, #ddd 20%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.product-rating .star:before {
  content: "★";
}

.product-price {
  display: flex;
  align-items: baseline;
  gap: 10px;
  margin-bottom: 10px;
  flex-wrap: wrap;
}

.price-new {
  font-size: 20px;
  font-weight: 700;
  color: rgb(231, 0, 114);
}

.price-old {
  font-size: 14px;
  color: #999;
  text-decoration: line-through;
}

.price-current {
  font-size: 20px;
  font-weight: 700;
  color: rgb(231, 0, 114);
}

.countdown-timer {
  display: flex;
  gap: 5px;
  margin-top: 8px;
  justify-content: flex-start;
}

.countdown-item {
  background: linear-gradient(135deg, #ff6b00, #ff8c42);
  border-radius: 5px;
  padding: 6px 8px;
  text-align: center;
  min-width: 42px;
  box-shadow: 0 1px 3px rgba(255, 107, 0, 0.2);
}

.countdown-value {
  display: block;
  font-size: 16px;
  font-weight: 700;
  color: #fff;
  line-height: 1.3;
  margin-bottom: 2px;
}

.countdown-label {
  display: block;
  font-size: 10px;
  color: rgba(255, 255, 255, 0.9);
  text-transform: uppercase;
  letter-spacing: 0.3px;
  font-weight: 500;
}

@media (max-width: 992px) {
  .flash-deal-section {
    padding: 12px 0;
  }
  
  .flash-deal-header {
    margin-bottom: 10px;
  }
  
  .flash-deal-title {
    font-size: 22px;
    padding: 0 0 14px 0;
  }
  
  .flash-deal-product {
    flex: 0 0 calc(50% - 4px);
    min-height: 190px;
  }
  
  .product-left {
    flex: 0 0 28%;
  }
  
  .product-right {
    flex: 0 0 72%;
    padding: 10px 12px;
  }
  
  .product-image-wrapper {
    padding-top: 100%;
  }
  
  .product-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .product-title {
    font-size: 16px;
    min-height: 42px;
    margin-bottom: 7px;
  }
  
  .price-new,
  .price-current {
    font-size: 19px;
  }
  
  .price-old {
    font-size: 13px;
  }
  
  .countdown-item {
    min-width: 40px;
    padding: 5px 7px;
  }
  
  .countdown-value {
    font-size: 15px;
  }
  
  .countdown-label {
    font-size: 9px;
  }
  
  .countdown-timer {
    gap: 4px;
    margin-top: 7px;
  }
  
  .flash-nav-btn {
    width: 26px;
    height: 26px;
    font-size: 9px;
  }
  
  .flash-nav-btn.prev-btn {
    left: 5px;
  }
  
  .flash-nav-btn.next-btn {
    right: 5px;
  }
}

@media (max-width: 768px) {
  .flash-deal-section {
    padding: 10px 0;
  }
  
  .flash-deal-header {
    margin-bottom: 10px;
  }
  
  .flash-deal-title {
    font-size: 20px;
    padding: 0 0 12px 0;
  }
  
  .title-underline {
    width: 45px;
    height: 2px;
    margin: 5px auto 0;
  }
  
  .flash-deal-container {
    padding: 0 12px;
  }
  
  .flash-deal-row {
    gap: 8px;
  }
  
  .flash-deal-product {
    flex: 0 0 calc(50% - 4px);
    min-height: 240px;
    flex-direction: column;
  }
  
  .product-left,
  .product-right {
    flex: 0 0 100%;
  }
  
  .product-image-wrapper {
    padding-top: 60%;
  }
  
  .product-right {
    padding: 12px 14px;
  }
  
  .product-left {
    flex: 0 0 28%;
  }
  
  .product-right {
    flex: 0 0 72%;
  }
  
  .product-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .product-title {
    font-size: 16px;
    min-height: 40px;
    margin-bottom: 7px;
  }
  
  .price-new,
  .price-current {
    font-size: 19px;
  }
  
  .price-old {
    font-size: 13px;
  }
  
  .countdown-item {
    min-width: 40px;
    padding: 5px 7px;
  }
  
  .countdown-value {
    font-size: 15px;
  }
  
  .countdown-label {
    font-size: 9px;
  }
  
  .countdown-timer {
    gap: 4px;
    margin-top: 7px;
  }
  
  .flash-nav-btn {
    width: 24px;
    height: 24px;
    font-size: 9px;
  }
  
  .flash-nav-btn.prev-btn {
    left: 4px;
  }
  
  .flash-nav-btn.next-btn {
    right: 4px;
  }
  
  .countdown-item {
    min-width: 30px;
    padding: 3px 5px;
  }
  
  .countdown-value {
    font-size: 10px;
  }
  
  .countdown-label {
    font-size: 6px;
  }
}

@media (max-width: 576px) {
  .flash-deal-section {
    padding: 10px 0;
  }
  
  .flash-deal-header {
    margin-bottom: 8px;
  }
  
  .flash-deal-title {
    font-size: 18px;
    padding: 0 0 10px 0;
  }
  
  .flash-deal-container {
    padding: 0 10px;
  }
  
  .flash-deal-row {
    gap: 6px;
  }
  
  .flash-deal-product {
    flex: 0 0 calc(50% - 3px);
    min-height: 220px;
    border-radius: 8px;
  }
  
  .product-image-wrapper {
    padding-top: 55%;
  }
  
  .product-right {
    padding: 10px 12px;
  }
  
  .product-left {
    flex: 0 0 28%;
  }
  
  .product-right {
    flex: 0 0 72%;
  }
  
  .product-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .product-title {
    font-size: 15px;
    min-height: 38px;
    margin-bottom: 6px;
  }
  
  .price-new {
    font-size: 18px;
  }
  
  .price-old {
    font-size: 13px;
  }
  
  .price-current {
    font-size: 18px;
  }
  
  .countdown-item {
    min-width: 38px;
    padding: 5px 6px;
  }
  
  .countdown-value {
    font-size: 14px;
  }
  
  .countdown-label {
    font-size: 9px;
  }
  
  .countdown-timer {
    gap: 4px;
    margin-top: 6px;
  }
  
  .flash-nav-btn {
    width: 22px;
    height: 22px;
    font-size: 8px;
  }
  
  .flash-nav-btn.prev-btn {
    left: 3px;
  }
  
  .flash-nav-btn.next-btn {
    right: 3px;
  }
  
  .countdown-item {
    min-width: 28px;
    padding: 3px 4px;
  }
  
  .countdown-timer {
    gap: 2px;
    margin-top: 4px;
  }
  
  .countdown-value {
    font-size: 9px;
  }
  
  .countdown-label {
    font-size: 5px;
  }
  
  .discount-badge {
    top: 4px;
    right: 4px;
    padding: 2px 5px;
    font-size: 8px;
  }
  
  .action-btn {
    width: 22px;
    height: 22px;
    font-size: 9px;
  }
}
</style>

<script>
(function() {
  var slider = document.querySelector('.flash-deal-track');
  if (!slider) return;
  
  var rows = slider.querySelectorAll('.flash-deal-row');
  var totalRows = rows.length;
  var currentIndex = 0;
  
  // Add active class to first row
  if (rows.length > 0) {
    rows[0].classList.add('active');
  }
  
  function updateButtons() {
    if (totalRows <= 1) {
      // Hide all buttons if only one row
      document.querySelectorAll('.flash-nav-btn').forEach(function(btn) {
        btn.style.display = 'none';
      });
      return;
    }
    
    // Update active row
    rows.forEach(function(row, index) {
      if (index === currentIndex) {
        row.classList.add('active');
      } else {
        row.classList.remove('active');
      }
    });
    
    // Update button states
    document.querySelectorAll('.prev-btn').forEach(function(btn) {
      btn.disabled = currentIndex <= 0;
    });
    
    document.querySelectorAll('.next-btn').forEach(function(btn) {
      btn.disabled = currentIndex >= totalRows - 1;
    });
  }
  
  function slideTo(index) {
    currentIndex = Math.max(0, Math.min(index, totalRows - 1));
    slider.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';
    updateButtons();
  }
  
  // Add event listeners to all buttons
  document.querySelectorAll('.prev-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      slideTo(currentIndex - 1);
    });
  });
  
  document.querySelectorAll('.next-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      slideTo(currentIndex + 1);
    });
  });
  
  // Initialize
  updateButtons();
  
  // Handle window resize
  window.addEventListener('resize', function() {
    slider.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';
  });
  
  // Initialize countdown timers
  var timers = document.querySelectorAll('.countdown-timer');
  timers.forEach(function(timer) {
    var endDate = timer.getAttribute('data-end-date');
    if (!endDate || endDate === '') return;
    
    // Parse the date properly - handle MySQL datetime format
    var end;
    try {
      // MySQL format is 'YYYY-MM-DD HH:MM:SS', convert to JavaScript Date
      var dateParts = endDate.match(/(\d{4})-(\d{2})-(\d{2})\s+(\d{2}):(\d{2}):(\d{2})/);
      if (dateParts) {
        // Create date using Date constructor with year, month (0-indexed), day, hour, minute, second
        end = new Date(parseInt(dateParts[1]), parseInt(dateParts[2]) - 1, parseInt(dateParts[3]), 
                       parseInt(dateParts[4]), parseInt(dateParts[5]), parseInt(dateParts[6])).getTime();
      } else {
        // Fallback to standard Date parsing
        end = new Date(endDate).getTime();
      }
    } catch (e) {
      console.error('Invalid date format:', endDate);
      return;
    }
    
    // Check if date is valid
    if (isNaN(end)) {
      console.error('Invalid date value for:', endDate);
      return;
    }
    
    var days = timer.querySelector('[data-days]');
    var hrs = timer.querySelector('[data-hrs]');
    var min = timer.querySelector('[data-min]');
    var sec = timer.querySelector('[data-sec]');
    
    if (!days || !hrs || !min || !sec) return;
    
    function updateCountdown() {
      var now = new Date().getTime();
      var distance = end - now;
      
      if (distance < 0) {
        if (days) days.textContent = '00';
        if (hrs) hrs.textContent = '00';
        if (min) min.textContent = '00';
        if (sec) sec.textContent = '00';
        return;
      }
      
      var daysVal = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hoursVal = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutesVal = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var secondsVal = Math.floor((distance % (1000 * 60)) / 1000);
      
      if (days) days.textContent = String(daysVal).padStart(2, '0');
      if (hrs) hrs.textContent = String(hoursVal).padStart(2, '0');
      if (min) min.textContent = String(minutesVal).padStart(2, '0');
      if (sec) sec.textContent = String(secondsVal).padStart(2, '0');
    }
    
    updateCountdown();
    setInterval(updateCountdown, 1000);
  });
})();
</script>
<?php } ?>
