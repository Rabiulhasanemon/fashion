<?php if ($reviews) { ?>
<?php foreach ($reviews as $review) { ?>
<div class="each-review">
  <div class="review-heading">
    <div class="rating">
      <?php for ($i = 1; $i <= 5; $i++) { ?>
      <?php if ($review['rating'] < $i) { ?>
      <span class="material-icons">star_border</span>
      <?php } else { ?>
      <span class="material-icons">star</span>
      <?php } ?>
      <?php } ?>
    </div>
    <div class="author"><span class="name"><?php echo $review['author']; ?></span> on <span class="date"><?php echo $review['date_added']; ?></span></div>
  </div>
  <div class="review-comment">
    <span class="fa fa-quote-left"></span>
    <span><?php echo $review['text']; ?></span>
  </div>
</div>
<?php } ?>
<div class="text-right"><?php echo $pagination; ?></div>
<?php } else { ?>
<div class="empty-content">
  <span class="icon material-icons">assignment</span>
  <div class="empty-text">This product has no reviews yet. Be the first one to write a review.</div>
</div>
<?php } ?>
