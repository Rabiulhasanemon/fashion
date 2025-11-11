<?php if ($is_running || $is_starting || $is_ended) { ?>
<div class="big-offer-module section-padding">
    <div class="container">
        <?php if (!empty($banner_images)) { ?>
        <div class="offer-banner-area banner-fullscreen" style="width: 95vw; margin-left: calc(-47.5vw + 50%); margin-right: calc(-47.5vw + 50%); padding: 0; margin-top: 0; margin-bottom: 20px;">
            <div class="container" style="padding: 0; max-width: 100%;">
                <div class="offer-banner" style="margin-bottom: 0;">
                    <?php foreach ($banner_images as $bi) { ?>
                        <?php if (!empty($bi['link'])) { ?><a href="<?php echo $bi['link']; ?>" style="display: block; margin: 0; padding: 0;"><?php } ?>
                        <img src="<?php echo $bi['image']; ?>" alt="<?php echo $bi['title']; ?>" class="img-responsive" style="width:100%; border-radius: 10px; margin: 0; padding: 0;" />
                        <?php if (!empty($bi['link'])) { ?></a><?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php if ($title || $description) { ?>
        <div class="offer-header text-center" style="margin: 30px 0;">
            <?php if ($title) { ?>
            <h2 class="offer-title cosmetics-module-heading" style="font-size: 28px; font-weight: 600; margin-bottom: 15px; color: #1a1a1a; text-align: left; padding: 20px 0 16px 0; letter-spacing: -0.02em; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; position: relative;">
                <?php echo html_entity_decode($title, ENT_QUOTES, 'UTF-8'); ?>
            </h2>
<style>
.big-offer-module .cosmetics-module-heading::after {
  content: '';
  position: absolute;
  bottom: 8px;
  left: 0;
  width: 60px;
  height: 3px;
  background: linear-gradient(90deg, #ff6b9d, #ff8c9f);
  border-radius: 2px;
}
.big-offer-module .offer-header {
  text-align: left !important;
  padding: 0 20px;
}
@media (max-width: 992px) {
  .big-offer-module .cosmetics-module-heading { font-size: 24px !important; padding: 18px 0 14px 0 !important; }
  .big-offer-module .cosmetics-module-heading::after { width: 50px !important; height: 2.5px !important; bottom: 6px !important; }
}
@media (max-width: 749px) {
  .big-offer-module .cosmetics-module-heading { font-size: 22px !important; padding: 16px 0 12px 0 !important; }
  .big-offer-module .cosmetics-module-heading::after { width: 45px !important; height: 2px !important; bottom: 5px !important; }
}
@media (max-width: 576px) {
  .big-offer-module .cosmetics-module-heading { font-size: 20px !important; padding: 14px 0 10px 0 !important; }
  .big-offer-module .cosmetics-module-heading::after { width: 40px !important; height: 2px !important; bottom: 4px !important; }
}
</style>
            <?php } ?>
            
            <?php if ($description) { ?>
            <div class="offer-description" style="max-width: 800px; margin: 0 auto 20px;">
                <?php echo html_entity_decode($description, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <?php } ?>
        </div>
        <?php } ?>
        
        <?php if ($is_starting) { ?>
        <div class="offer-status text-center" style="padding: 20px;">
            <p style="font-size: 20px; margin-bottom: 15px;">Offer starts soon!</p>
            <?php if (!empty($start)) { ?>
            <div id="offer-countdown" data-deadline="<?php echo $start; ?>" class="countdown-timer"></div>
            <?php } ?>
        </div>
        <?php } elseif ($is_ended) { ?>
        <div class="offer-status text-center" style="padding: 20px;">
            <p style="font-size: 24px; color: #e63946;">Offer has ended.</p>
        </div>
        <?php } else { ?>
            <?php if (!empty($end)) { ?>
            <div class="countdown-wrapper text-center" style="margin: 30px 0;">
                <h3 style="margin-bottom: 20px; font-size: 24px; color: #555;">Offer Ends In:</h3>
                <div id="offer-countdown" data-deadline="<?php echo $end; ?>" class="countdown-timer"></div>
            </div>
            <?php } ?>
            
            <?php if (!empty($products)) { ?>
            <div class="offer-products" style="margin-top: 40px;">
                <h3 class="cosmetics-module-heading" style="margin-bottom: 30px; font-size: 28px; color: #1a1a1a; text-align: left; padding: 20px 0 16px 0; letter-spacing: -0.02em; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; position: relative; font-weight: 600;">Featured Products</h3>
<style>
.big-offer-module .offer-products .cosmetics-module-heading::after {
  content: '';
  position: absolute;
  bottom: 8px;
  left: 0;
  width: 60px;
  height: 3px;
  background: linear-gradient(90deg, #ff6b9d, #ff8c9f);
  border-radius: 2px;
}
</style>
                <div class="row">
                    <?php foreach ($products as $product) { ?>
                    <div class="col-sm-6 col-md-3" style="margin-bottom: 30px;">
                        <div class="product-item" style="border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; background: white; transition: all 0.3s;">
                            <div class="product-image" style="position: relative; overflow: hidden; background: #f5f5f5;">
                                <a href="<?php echo $product['href']; ?>">
                                    <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" class="img-responsive" style="width: 100%; height: auto; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" />
                                </a>
                            </div>
                            <div class="product-info" style="padding: 15px; text-align: center;">
                                <h4 style="font-size: 16px; margin-bottom: 10px; min-height: 40px; overflow: hidden;">
                                    <a href="<?php echo $product['href']; ?>" style="color: #333; text-decoration: none;">
                                        <?php echo $product['name']; ?>
                                    </a>
                                </h4>
                                <div class="price" style="margin-bottom: 15px;">
                                    <?php if ($product['special']) { ?>
                                    <div>
                                        <span class="price-old" style="text-decoration: line-through; color: #888; margin-right: 8px; font-size: 14px;">
                                            <?php echo $product['price']; ?>
                                        </span>
                                    </div>
                                    <span class="price-new" style="color: #e63946; font-size: 20px; font-weight: bold;">
                                        <?php echo $product['special']; ?>
                                    </span>
                                    <?php } else { ?>
                                    <span class="price-current" style="font-size: 20px; font-weight: bold; color: #2a9d8f;">
                                        <?php echo $product['price']; ?>
                                    </span>
                                    <?php } ?>
                                </div>
                                <a href="<?php echo $product['href']; ?>" class="btn btn-primary" style="width: 100%; padding: 10px; border-radius: 5px; background: #2a9d8f; border: none; color: white; text-decoration: none; display: inline-block;">
                                    View Product
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<style>
.countdown-timer {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
}
.countdown-timer .time-unit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px 15px;
    border-radius: 10px;
    min-width: 90px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    text-align: center;
}
.countdown-timer .time-value {
    display: block;
    font-size: 40px;
    font-weight: bold;
    line-height: 1;
    margin-bottom: 5px;
}
.countdown-timer .time-label {
    display: block;
    font-size: 13px;
    text-transform: uppercase;
    opacity: 0.9;
    letter-spacing: 1px;
}
.product-item {
    transition: all 0.3s ease;
}
.product-item:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transform: translateY(-5px);
}
</style>

<script>
(function(){
  var el = document.getElementById('offer-countdown');
  if(!el) return;
  var deadline = el.getAttribute('data-deadline');
  if(!deadline) return;
  
  function update(){
    var diff = (new Date(deadline)) - (new Date());
    if(diff <= 0){ 
      el.innerHTML = '<div class="time-unit"><span class="time-value">EXPIRED</span></div>'; 
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
<?php } ?>
