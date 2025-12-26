<?php echo $header; ?>

<style>
/* Big Offer Page Styles */
.big-offer-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    position: relative;
    overflow: hidden;
}

.big-offer-page::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    pointer-events: none;
}

.offer-hero {
    position: relative;
    z-index: 2;
    padding: 80px 0;
    text-align: center;
    color: white;
}

.offer-hero h1 {
    font-size: 4rem;
    font-weight: 800;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    animation: fadeInUp 1s ease-out;
}

.offer-hero .subtitle {
    font-size: 1.5rem;
    margin-bottom: 40px;
    opacity: 0.9;
    animation: fadeInUp 1s ease-out 0.2s both;
}

.countdown-section {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 40px;
    margin: 40px auto;
    max-width: 800px;
    border: 1px solid rgba(255,255,255,0.2);
    animation: fadeInUp 1s ease-out 0.4s both;
}

.countdown-title {
    font-size: 2rem;
    margin-bottom: 30px;
    font-weight: 600;
}

.countdown-timer {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.countdown-timer .time-unit {
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    color: white;
    padding: 25px 20px;
    border-radius: 15px;
    min-width: 120px;
    text-align: center;
    border: 1px solid rgba(255,255,255,0.3);
    transition: transform 0.3s ease;
}

.countdown-timer .time-unit:hover {
    transform: translateY(-5px);
    background: rgba(255,255,255,0.3);
}

.countdown-timer .time-value {
    display: block;
    font-size: 3rem;
    font-weight: bold;
    line-height: 1;
    margin-bottom: 8px;
}

.countdown-timer .time-label {
    display: block;
    font-size: 1rem;
    text-transform: uppercase;
    opacity: 0.9;
    letter-spacing: 2px;
}

.offer-content {
    background: white;
    margin-top: 60px;
    padding: 80px 0;
    position: relative;
}

.offer-description {
    max-width: 800px;
    margin: 0 auto 60px;
    text-align: center;
    font-size: 1.2rem;
    line-height: 1.8;
    color: #555;
}

.products-section {
    padding: 60px 0;
    background: #f8f9fa;
}

.products-title {
    text-align: center;
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 50px;
    color: #333;
    position: relative;
}

.products-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 4px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 2px;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.product-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.4s ease;
    position: relative;
}

.product-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.product-image {
    position: relative;
    overflow: hidden;
    height: 250px;
    background: #f5f5f5;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.1);
}

.product-info {
    padding: 25px;
    text-align: center;
}

.product-name {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 15px;
    color: #333;
    line-height: 1.4;
}

.product-name a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
}

.product-name a:hover {
    color: #667eea;
}

.product-price {
    margin-bottom: 20px;
}

.price-old {
    text-decoration: line-through;
    color: #999;
    font-size: 1.1rem;
    margin-right: 10px;
}

.price-new {
    color: #e74c3c;
    font-size: 1.5rem;
    font-weight: bold;
}

.price-current {
    color: #2ecc71;
    font-size: 1.5rem;
    font-weight: bold;
}

.product-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.product-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

.banner-section {
    margin: 40px 0;
}

.banner-image {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.banner-image img {
    width: 100%;
    height: auto;
    display: block;
}

.status-message {
    text-align: center;
    padding: 40px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    margin: 40px auto;
    max-width: 600px;
    border: 1px solid rgba(255,255,255,0.2);
}

.status-message h3 {
    font-size: 2rem;
    margin-bottom: 15px;
    color: white;
}

.status-message p {
    font-size: 1.2rem;
    color: rgba(255,255,255,0.9);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .offer-hero h1 {
        font-size: 2.5rem;
    }
    
    .countdown-timer {
        gap: 15px;
    }
    
    .countdown-timer .time-unit {
        min-width: 80px;
        padding: 20px 15px;
    }
    
    .countdown-timer .time-value {
        font-size: 2rem;
    }
    
    .products-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
}
</style>

<div class="big-offer-page">
    <div class="offer-hero">
        <div class="container">
            <?php if ($title) { ?>
            <h1><?php echo $title; ?></h1>
            <?php } else { ?>
            <h1>Special Offer</h1>
            <?php } ?>
            
            <p class="subtitle">Don't miss out on this amazing deal!</p>
            
            <?php if ($is_starting) { ?>
            <div class="status-message">
                <h3>🚀 Coming Soon!</h3>
                <p>This amazing offer will start soon. Get ready!</p>
                <?php if (!empty($start)) { ?>
                <div id="offer-countdown" data-deadline="<?php echo $start; ?>" class="countdown-timer"></div>
                <?php } ?>
            </div>
            <?php } elseif ($is_ended) { ?>
            <div class="status-message">
                <h3>⏰ Offer Ended</h3>
                <p>This offer has ended. Stay tuned for more amazing deals!</p>
            </div>
            <?php } else { ?>
            <div class="countdown-section">
                <h2 class="countdown-title">⏰ Limited Time Offer</h2>
                <?php if (!empty($end)) { ?>
                <div id="offer-countdown" data-deadline="<?php echo $end; ?>" class="countdown-timer"></div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
    </div>
    
    <div class="offer-content">
        <div class="container">
            <?php if (!empty($banner_images)) { ?>
            <div class="banner-section">
                <?php foreach ($banner_images as $bi) { ?>
                <div class="banner-image">
                    <?php if (!empty($bi['link'])) { ?><a href="<?php echo $bi['link']; ?>"><?php } ?>
                    <img src="<?php echo $bi['image']; ?>" alt="<?php echo $bi['title']; ?>" />
                    <?php if (!empty($bi['link'])) { ?></a><?php } ?>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
            
            <?php if ($description) { ?>
            <div class="offer-description">
                <?php echo $description; ?>
            </div>
            <?php } ?>
            
            <?php if (!empty($products)) { ?>
            <div class="products-section">
                <h2 class="products-title">Featured Products</h2>
                <div class="products-grid">
                    <?php foreach ($products as $product) { ?>
                    <div class="product-card">
                        <div class="product-image">
                            <a href="<?php echo $product['href']; ?>">
                                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
                            </a>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">
                                <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                            </h3>
                            <div class="product-price">
                                <?php if ($product['special']) { ?>
                                <span class="price-old"><?php echo $product['price']; ?></span>
                                <span class="price-new"><?php echo $product['special']; ?></span>
                                <?php } else { ?>
                                <span class="price-current"><?php echo $product['price']; ?></span>
                                <?php } ?>
                            </div>
                            <a href="<?php echo $product['href']; ?>" class="product-btn">View Product</a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<script>
(function(){
  var el = document.getElementById('offer-countdown');
  if(!el) return;
  var deadline = el.getAttribute('data-deadline');
  if(!deadline) return;
  
  function update(){
    var diff = (new Date(deadline)) - (new Date());
    if(diff <= 0){ 
      el.innerHTML = '<div class="time-unit"><span class="time-value">EXPIRED</span><span class="time-label">Offer Ended</span></div>'; 
      return; 
    }
    var days = Math.floor(diff/86400000);
    var hours = Math.floor((diff%86400000)/3600000);
    var minutes = Math.floor((diff%3600000)/60000);
    var seconds = Math.floor((diff%60000)/1000);
    
    el.innerHTML = 
      '<div class="time-unit"><span class="time-value">' + days + '</span><span class="time-label">Days</span></div>' +
      '<div class="time-unit"><span class="time-value">' + (hours<10?'0':'')+hours + '</span><span class="time-label">Hours</span></div>' +
      '<div class="time-unit"><span class="time-value">' + (minutes<10?'0':'')+minutes + '</span><span class="time-label">Minutes</span></div>' +
      '<div class="time-unit"><span class="time-value">' + (seconds<10?'0':'')+seconds + '</span><span class="time-label">Seconds</span></div>';
    
    setTimeout(update, 1000);
  }
  update();
})();
</script>

<?php echo $footer; ?>