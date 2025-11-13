<section class="category style-7 section-padding">
    <div class="container">
        <div class="section-head">
            <h3 class="title"><?php echo isset($name) ? $name : 'Featured Categories'; ?></h3>
            <a href="<?php echo $this->url->link('product/category'); ?>" class="sec-btn"><span>See all</span><i class="fi-rr-arrow-right"></i></a>
        </div>
        <div class="category-inner">
            <?php foreach ($categories as $category) { ?>
            <a href="<?php echo $category['href']; ?>">
                <div class="category-card">
                    <div class="category-info">
                        <p><?php echo $category['name']; ?></p>
                    </div>
                    <div class="category-img">
                        <img src="<?php echo $category['icon']; ?>" alt="<?php echo $category['name']; ?>">
                    </div>
                </div>
            </a>
            <?php } ?>
        </div>
    </div>
</section>