<div class="brandloop24_section">
  <div class="brandloop24_inner">
    <div class="brandloop24_track">
      <?php foreach ($manufacturers as $manufacturer) { ?>
      <a class="brandloop24_card" href="<?php echo $manufacturer['href']; ?>" title="<?php echo $manufacturer['name']; ?>">
        <img src="<?php echo $manufacturer['thumb']; ?>" alt="<?php echo $manufacturer['name']; ?>" loading="lazy" />
      </a>
      <?php } ?>
      <?php foreach ($manufacturers as $manufacturer) { ?>
      <a class="brandloop24_card" href="<?php echo $manufacturer['href']; ?>" title="<?php echo $manufacturer['name']; ?>">
        <img src="<?php echo $manufacturer['thumb']; ?>" alt="<?php echo $manufacturer['name']; ?>" loading="lazy" />
      </a>
      <?php } ?>
    </div>
  </div>
</div>

<style>
.brandloop24_section {
  padding: 40px 0;
  background: #111;
  overflow: hidden;
}
.brandloop24_inner {
  max-width: 1200px;
  margin: 0 auto;
  position: relative;
}
.brandloop24_track {
  display: flex;
  gap: 30px;
  animation: brandloop24_scroll 18s linear infinite;
}
.brandloop24_card {
  width: 140px;
  height: 70px;
  border-radius: 14px;
  background: rgba(255,255,255,0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 10px 14px;
  transition: transform 0.3s ease, background 0.3s ease;
}
.brandloop24_card img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
  filter: grayscale(100%) brightness(1.2);
  opacity: 0.7;
  transition: opacity 0.3s ease, filter 0.3s ease;
}
.brandloop24_card:hover {
  transform: translateY(-4px);
  background: rgba(255,255,255,0.15);
}
.brandloop24_card:hover img {
  filter: grayscale(0%);
  opacity: 1;
}
@keyframes brandloop24_scroll {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
@media (max-width: 768px) {
  .brandloop24_track {
    animation-duration: 28s;
    gap: 16px;
  }
  .brandloop24_card {
    width: 110px;
    height: 60px;
  }
}
</style>