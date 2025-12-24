<?php echo $header; ?>
<section class="ruplexa-blog-hero-section">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <ul class="breadcrumb ruplexa-blog-breadcrumb">
          <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
          <?php } ?>
        </ul>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</section>

<section class="ruplexa-blog-main-section">
  <div class="container">
    <div class="row">
      <?php echo $column_left; ?>
      <?php if ($column_left && $column_right) { ?>
      <?php $class = 'col-sm-6'; ?>
      <?php } elseif ($column_left || $column_right) { ?>
      <?php $class = 'col-sm-9'; ?>
      <?php } else { ?>
      <?php $class = 'col-sm-12'; ?>
      <?php } ?>
      
      <div id="content" class="<?php echo $class; ?>">
        <?php echo $content_top; ?>
        
        <?php if (isset($heading_title)) { ?>
        <div class="ruplexa-blog-page-header">
          <h1 class="ruplexa-blog-title"><?php echo $heading_title; ?></h1>
          <p class="ruplexa-blog-subtitle">Discover beauty tips, trends, and expert advice</p>
        </div>
        <?php } ?>
        
        <?php if ($articles) { ?>
        <div class="ruplexa-blog-grid">
          <div class="row">
            <?php foreach ($articles as $article) { ?>
            <div class="col-md-6 col-lg-4 ruplexa-blog-item-wrapper">
              <article class="ruplexa-blog-card">
                <div class="ruplexa-blog-card-image-wrapper">
                  <a href="<?php echo $article['href']; ?>" class="ruplexa-blog-card-link">
                    <img src="<?php echo $article['thumb']; ?>" alt="<?php echo htmlspecialchars($article['name']); ?>" class="ruplexa-blog-card-image">
                    <?php if (isset($article['video_url']) && $article['video_url']) { ?>
                    <div class="ruplexa-blog-video-overlay">
                      <span class="ruplexa-blog-play-icon">
                        <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <circle cx="32" cy="32" r="30" fill="rgba(255, 255, 255, 0.9)" stroke="rgba(255, 255, 255, 0.5)" stroke-width="2"/>
                          <path d="M26 20L26 44L42 32L26 20Z" fill="#FF6B9D"/>
                        </svg>
                      </span>
                    </div>
                    <?php } ?>
                    <div class="ruplexa-blog-card-overlay"></div>
                  </a>
                </div>
                <div class="ruplexa-blog-card-content">
                  <div class="ruplexa-blog-card-meta">
                    <span class="ruplexa-blog-card-date">
                      <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.0833 1.75H10.5V0.875H9.625V1.75H4.375V0.875H3.5V1.75H2.91667C2.08917 1.75 1.41667 2.4225 1.41667 3.25V12.25C1.41667 13.0775 2.08917 13.75 2.91667 13.75H11.0833C11.9108 13.75 12.5833 13.0775 12.5833 12.25V3.25C12.5833 2.4225 11.9108 1.75 11.0833 1.75ZM11.0833 12.25H2.91667V5.83333H11.0833V12.25Z" fill="currentColor"/>
                      </svg>
                      <?php echo $article['date_added']; ?>
                    </span>
                  </div>
                  <h3 class="ruplexa-blog-card-title">
                    <a href="<?php echo $article['href']; ?>" class="ruplexa-blog-card-title-link"><?php echo htmlspecialchars($article['name']); ?></a>
                  </h3>
                  <?php if (isset($article['intro_text']) && $article['intro_text']) { ?>
                  <div class="ruplexa-blog-card-excerpt">
                    <?php echo strip_tags($article['intro_text']); ?>
                  </div>
                  <?php } ?>
                  <div class="ruplexa-blog-card-footer">
                    <a href="<?php echo $article['href']; ?>" class="ruplexa-blog-read-more">
                      Read More
                      <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 12L10 8L6 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </a>
                  </div>
                </div>
              </article>
            </div>
            <?php } ?>
          </div>
        </div>
        
        <div class="ruplexa-blog-pagination-wrapper">
          <div class="row">
            <div class="col-md-6 col-sm-12">
              <ul class="pagination ruplexa-blog-pagination"><?php echo $pagination; ?></ul>
            </div>
            <div class="col-md-6 col-sm-12 text-right ruplexa-blog-results">
              <p class="ruplexa-blog-results-text"><?php echo $results; ?></p>
            </div>
          </div>
        </div>
        <?php } else { ?>
        <div class="ruplexa-blog-empty">
          <div class="ruplexa-blog-empty-icon">
            <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="40" cy="40" r="38" stroke="#E0E0E0" stroke-width="2"/>
              <path d="M25 40L35 50L55 30" stroke="#E0E0E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <p class="ruplexa-blog-empty-text"><?php echo $text_empty; ?></p>
        </div>
        <?php } ?>
        
        <?php echo $content_bottom; ?>
      </div>
      <?php echo $column_right; ?>
    </div>
  </div>
</section>

<style>
/* Ruplexa Premium Blog Styles - New Classes to Avoid Conflicts */
.ruplexa-blog-hero-section {
  background: linear-gradient(135deg, #FF6B9D 0%, #FF8E9B 100%);
  padding: 30px 0 20px;
  margin-bottom: 0;
}

.ruplexa-blog-breadcrumb {
  background: none;
  padding: 0;
  margin: 0;
}

.ruplexa-blog-breadcrumb li a {
  color: rgba(255, 255, 255, 0.9);
  font-size: 14px;
  transition: color 0.3s ease;
}

.ruplexa-blog-breadcrumb li a:hover {
  color: #ffffff;
  text-decoration: none;
}

.ruplexa-blog-breadcrumb li:last-child a {
  color: #ffffff;
  font-weight: 600;
}

.ruplexa-blog-breadcrumb li + li:before {
  content: "/";
  color: rgba(255, 255, 255, 0.7);
  padding: 0 10px;
}

.ruplexa-blog-main-section {
  background: #F8F9FA;
  padding: 50px 0;
  min-height: 60vh;
}

.ruplexa-blog-page-header {
  text-align: center;
  margin-bottom: 50px;
  padding-bottom: 30px;
  border-bottom: 2px solid #E9ECEF;
}

.ruplexa-blog-title {
  font-size: 42px;
  font-weight: 700;
  color: #2C3E50;
  margin-bottom: 15px;
  letter-spacing: -0.5px;
}

.ruplexa-blog-subtitle {
  font-size: 18px;
  color: #6C757D;
  margin: 0;
  font-weight: 300;
}

.ruplexa-blog-grid {
  margin-bottom: 50px;
}

.ruplexa-blog-item-wrapper {
  margin-bottom: 30px;
}

.ruplexa-blog-card {
  background: #FFFFFF;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  height: 100%;
  display: flex;
  flex-direction: column;
}

.ruplexa-blog-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.ruplexa-blog-card-image-wrapper {
  position: relative;
  overflow: hidden;
  padding-top: 65%;
  background: #F0F0F0;
}

.ruplexa-blog-card-image {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.ruplexa-blog-card:hover .ruplexa-blog-card-image {
  transform: scale(1.1);
}

.ruplexa-blog-card-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.3) 100%);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.ruplexa-blog-card:hover .ruplexa-blog-card-overlay {
  opacity: 1;
}

.ruplexa-blog-video-overlay {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 2;
  opacity: 0.9;
  transition: transform 0.3s ease, opacity 0.3s ease;
}

.ruplexa-blog-card:hover .ruplexa-blog-video-overlay {
  transform: translate(-50%, -50%) scale(1.1);
  opacity: 1;
}

.ruplexa-blog-play-icon {
  display: block;
  filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
}

.ruplexa-blog-card-content {
  padding: 25px;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.ruplexa-blog-card-meta {
  margin-bottom: 12px;
}

.ruplexa-blog-card-date {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
  color: #6C757D;
  font-weight: 500;
}

.ruplexa-blog-card-date svg {
  width: 14px;
  height: 14px;
}

.ruplexa-blog-card-title {
  margin: 0 0 15px 0;
  font-size: 22px;
  font-weight: 700;
  line-height: 1.4;
  color: #2C3E50;
}

.ruplexa-blog-card-title-link {
  color: inherit;
  text-decoration: none;
  transition: color 0.3s ease;
}

.ruplexa-blog-card-title-link:hover {
  color: #FF6B9D;
  text-decoration: none;
}

.ruplexa-blog-card-excerpt {
  color: #6C757D;
  font-size: 15px;
  line-height: 1.6;
  margin-bottom: 20px;
  flex: 1;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.ruplexa-blog-card-footer {
  margin-top: auto;
  padding-top: 15px;
  border-top: 1px solid #E9ECEF;
}

.ruplexa-blog-read-more {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: #FF6B9D;
  font-size: 15px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s ease;
}

.ruplexa-blog-read-more:hover {
  color: #FF8E9B;
  gap: 12px;
  text-decoration: none;
}

.ruplexa-blog-read-more svg {
  transition: transform 0.3s ease;
}

.ruplexa-blog-read-more:hover svg {
  transform: translateX(4px);
}

.ruplexa-blog-pagination-wrapper {
  margin-top: 50px;
  padding-top: 30px;
  border-top: 2px solid #E9ECEF;
}

.ruplexa-blog-pagination {
  margin: 0;
}

.ruplexa-blog-pagination li a,
.ruplexa-blog-pagination li span {
  padding: 10px 15px;
  margin: 0 4px;
  border-radius: 8px;
  border: 1px solid #E9ECEF;
  color: #6C757D;
  transition: all 0.3s ease;
}

.ruplexa-blog-pagination li.active span,
.ruplexa-blog-pagination li a:hover {
  background: #FF6B9D;
  border-color: #FF6B9D;
  color: #FFFFFF;
}

.ruplexa-blog-results {
  display: flex;
  align-items: center;
  justify-content: flex-end;
}

.ruplexa-blog-results-text {
  margin: 0;
  color: #6C757D;
  font-size: 14px;
}

.ruplexa-blog-empty {
  text-align: center;
  padding: 80px 20px;
}

.ruplexa-blog-empty-icon {
  margin-bottom: 30px;
  opacity: 0.5;
}

.ruplexa-blog-empty-text {
  font-size: 18px;
  color: #6C757D;
  margin: 0;
}

/* Responsive Design */
@media (max-width: 991px) {
  .ruplexa-blog-title {
    font-size: 32px;
  }
  
  .ruplexa-blog-subtitle {
    font-size: 16px;
  }
  
  .ruplexa-blog-card-title {
    font-size: 20px;
  }
}

@media (max-width: 767px) {
  .ruplexa-blog-hero-section {
    padding: 20px 0 15px;
  }
  
  .ruplexa-blog-main-section {
    padding: 30px 0;
  }
  
  .ruplexa-blog-page-header {
    margin-bottom: 30px;
    padding-bottom: 20px;
  }
  
  .ruplexa-blog-title {
    font-size: 28px;
  }
  
  .ruplexa-blog-subtitle {
    font-size: 15px;
  }
  
  .ruplexa-blog-item-wrapper {
    margin-bottom: 25px;
  }
  
  .ruplexa-blog-card-content {
    padding: 20px;
  }
  
  .ruplexa-blog-card-title {
    font-size: 18px;
  }
  
  .ruplexa-blog-results {
    margin-top: 20px;
    justify-content: flex-start;
  }
}
</style>

<?php echo $footer; ?>
