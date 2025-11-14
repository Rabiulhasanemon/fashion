<?php if (!empty($reviews)) { ?>
<div class="review-view-module review-view-<?php echo $layout; ?>" style="max-width: 80%; margin: 0 auto;">
  <?php if ($title) { ?>
  <div class="module-heading-wrapper">
    <h2 class="module-heading-title"><?php echo $title; ?></h2>
  </div>
  <?php } ?>
  
  <style>
  .review-view-module {
    padding: 20px 0;
  }
  
  .review-view-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
  }
  
  .review-view-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  
  .review-card {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .review-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
  }
  
  .review-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    gap: 15px;
  }
  
  .review-author {
    font-weight: 600;
    font-size: 16px;
    color: #333;
  }
  
  .review-rating {
    display: flex;
    gap: 3px;
  }
  
  .review-rating .star {
    color: #ffc107;
    font-size: 18px;
  }
  
  .review-rating .star.empty {
    color: #ddd;
  }
  
  .review-text {
    color: #666;
    line-height: 1.6;
    margin-bottom: 15px;
    font-size: 14px;
  }
  
  .review-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid #eee;
    font-size: 13px;
    color: #999;
  }
  
  .review-product {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  
  .review-product img {
    width: 40px;
    height: 40px;
    border-radius: 4px;
    object-fit: cover;
  }
  
  .review-product a {
    color: #666;
    text-decoration: none;
    transition: color 0.3s ease;
  }
  
  .review-product a:hover {
    color: var(--primary-color, #007bff);
  }
  
  .review-view-slider .review-card {
    margin: 0 10px;
  }
  
  @media (max-width: 767px) {
    .review-view-module {
      max-width: 100% !important;
      padding: 15px;
    }
    
    .review-view-grid {
      grid-template-columns: 1fr;
    }
  }
  </style>
  
  <?php if ($layout == 'slider') { ?>
  <div class="review-slider owl-carousel">
    <?php foreach ($reviews as $review) { ?>
    <div class="review-card">
      <div class="review-header">
        <div class="review-author"><?php echo $review['author']; ?></div>
        <?php if ($show_rating) { ?>
        <div class="review-rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <span class="star <?php echo $i <= $review['rating'] ? '' : 'empty'; ?>">★</span>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
      <div class="review-text"><?php echo $review['text']; ?></div>
      <div class="review-meta">
        <?php if ($show_product && $review['product_href']) { ?>
        <div class="review-product">
          <?php if ($review['product_image']) { ?>
          <img src="<?php echo $review['product_image']; ?>" alt="<?php echo $review['product_name']; ?>" />
          <?php } ?>
          <a href="<?php echo $review['product_href']; ?>"><?php echo $review['product_name']; ?></a>
        </div>
        <?php } ?>
        <?php if ($show_date) { ?>
        <div class="review-date"><?php echo $review['date_added']; ?></div>
        <?php } ?>
      </div>
    </div>
    <?php } ?>
  </div>
  
  <script>
  $(document).ready(function() {
    $('.review-slider').owlCarousel({
      items: 3,
      itemsDesktop: [1199, 3],
      itemsDesktopSmall: [979, 2],
      itemsTablet: [768, 2],
      itemsMobile: [479, 1],
      navigation: true,
      navigationText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>'],
      pagination: false,
      autoPlay: false
    });
  });
  </script>
  <?php } else { ?>
  <div class="review-view-<?php echo $layout; ?>">
    <?php foreach ($reviews as $review) { ?>
    <div class="review-card">
      <div class="review-header">
        <div class="review-author"><?php echo $review['author']; ?></div>
        <?php if ($show_rating) { ?>
        <div class="review-rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <span class="star <?php echo $i <= $review['rating'] ? '' : 'empty'; ?>">★</span>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
      <div class="review-text"><?php echo $review['text']; ?></div>
      <div class="review-meta">
        <?php if ($show_product && $review['product_href']) { ?>
        <div class="review-product">
          <?php if ($review['product_image']) { ?>
          <img src="<?php echo $review['product_image']; ?>" alt="<?php echo $review['product_name']; ?>" />
          <?php } ?>
          <a href="<?php echo $review['product_href']; ?>"><?php echo $review['product_name']; ?></a>
        </div>
        <?php } ?>
        <?php if ($show_date) { ?>
        <div class="review-date"><?php echo $review['date_added']; ?></div>
        <?php } ?>
      </div>
    </div>
    <?php } ?>
  </div>
  <?php } ?>
</div>
<?php } ?>

