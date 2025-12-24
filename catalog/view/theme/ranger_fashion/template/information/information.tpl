<?php echo $header; ?>
<section class="after-header">
  <div class="container">
    <ul class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
      <?php foreach ($breadcrumbs as $i => $breadcrumb) { if($i < 1) { ?>
      <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
      <?php } else { ?>
      <li  itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemtype="http://schema.org/Thing" itemprop="item" href="<?php echo $breadcrumb['href']; ?>"><span itemprop="name"><?php echo $breadcrumb['text']; ?></span></a><meta itemprop="position" content="<?php echo $i; ?>" /></li>
      <?php }} ?>
    </ul>
  </div>
</section>
<div class="container body">
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
        <div class="info-page ruplexa-info-page">
            <?php echo $description; ?><?php echo $content_bottom; ?></div>
        </div>
    <?php echo $column_right; ?></div>
</div>
<style>
/* Ruplexa Information Page Styles - Unique Classes to Avoid Conflicts */
.ruplexa-info-page {
    padding: 30px 0;
}
.ruplexa-info-content {
    max-width: 100%;
    line-height: 1.8;
    color: #333;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}
.ruplexa-info-content h3 {
    font-size: 28px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #10503D;
}
.ruplexa-info-content p {
    font-size: 16px;
    line-height: 1.8;
    margin-bottom: 15px;
    color: #555;
}
.ruplexa-info-content ul,
.ruplexa-info-content ol {
    margin: 15px 0;
    padding-left: 30px;
}
.ruplexa-info-content li {
    margin-bottom: 10px;
    line-height: 1.8;
}
.ruplexa-info-content a {
    color: #10503D;
    text-decoration: none;
    transition: color 0.3s ease;
}
.ruplexa-info-content a:hover {
    color: #ff8c9f;
    text-decoration: underline;
}
/* Responsive */
@media (max-width: 768px) {
    .ruplexa-info-content h3 {
        font-size: 24px;
    }
    .ruplexa-info-content p {
        font-size: 15px;
    }
}
</style>
<?php echo $footer; ?>