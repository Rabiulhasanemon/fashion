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
  padding: 60px 0;
  background: #ffffff;
  overflow: hidden;
  position: relative;
}
.brandloop24_section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(180deg, rgba(255,255,255,0) 0%, rgba(250,250,250,0.5) 50%, rgba(255,255,255,0) 100%);
  pointer-events: none;
}
.brandloop24_inner {
  max-width: 1400px;
  margin: 0 auto;
  position: relative;
  padding: 0 40px;
}
.brandloop24_track {
  display: flex;
  gap: 24px;
  animation: brandloop24_scroll 20s linear infinite;
}
.brandloop24_card {
  width: 160px;
  height: 90px;
  border-radius: 16px;
  background: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 16px 20px;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
  border: 1px solid rgba(0, 0, 0, 0.06);
  flex-shrink: 0;
  position: relative;
  overflow: hidden;
}
.brandloop24_card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
  transition: left 0.5s ease;
}
.brandloop24_card:hover::before {
  left: 100%;
}
.brandloop24_card img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
  filter: grayscale(40%) brightness(0.95);
  opacity: 0.8;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  z-index: 1;
}
.brandloop24_card:hover {
  transform: translateY(-8px) scale(1.02);
  background: #ffffff;
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12), 0 4px 12px rgba(0, 0, 0, 0.08);
  border-color: rgba(0, 0, 0, 0.1);
}
.brandloop24_card:hover img {
  filter: grayscale(0%) brightness(1);
  opacity: 1;
  transform: scale(1.05);
}
@keyframes brandloop24_scroll {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
@media (max-width: 768px) {
  .brandloop24_section {
    padding: 40px 0;
  }
  .brandloop24_inner {
    padding: 0 20px;
  }
  .brandloop24_track {
    animation-duration: 25s;
    gap: 16px;
  }
  .brandloop24_card {
    width: 120px;
    height: 70px;
    padding: 12px 16px;
  }
}
@media (max-width: 480px) {
  .brandloop24_card {
    width: 100px;
    height: 60px;
    padding: 10px 12px;
  }
}
</style>