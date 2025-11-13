<div class="manufacturer-module-section banner-fullscreen" style="width: 95vw; margin-left: calc(-47.5vw + 50%); margin-right: calc(-47.5vw + 50%); padding: 0; margin-top: 0; margin-bottom: 0;">
  <div class="manufacturer-module-container" style="max-width: 80%;">
<style>
@media (max-width: 767px) {
  .manufacturer-module-container {
    max-width: 100% !important;
  }
}
</style>
    <div class="brands">
      <?php foreach ($manufacturers as $manufacturer) { ?>
      <a href="<?php echo $manufacturer['href']; ?>"><img src="<?php echo $manufacturer['thumb']; ?>" alt="<?php echo $manufacturer['name']; ?>" title="<?php echo $manufacturer['name']; ?>" ></a>
      <?php } ?>
    </div>
  </div>
</div>

<style>
.manufacturer-module-section {
  padding: 60px 0;
  background: #fff;
}

.manufacturer-module-container {
  padding: 0 20px;
  box-sizing: border-box;
}

.brands {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
  align-items: center;
}

.brands a {
  display: inline-block;
  transition: transform 0.3s ease;
}

.brands a:hover {
  transform: scale(1.1);
}

.brands img {
  max-width: 150px;
  height: 80px; /* Standard height */
  width: auto; /* Maintain aspect ratio */
  object-fit: contain; /* Ensure logo fits without cropping */
  filter: grayscale(100%);
  opacity: 0.7;
  transition: all 0.3s ease;
}

.brands a:hover img {
  filter: grayscale(0%);
  opacity: 1;
}

@media (max-width: 768px) {
  .manufacturer-module-section {
    padding: 40px 0;
  }
  
  .manufacturer-module-container {
    padding: 0 15px;
  }
  
  .brands {
    gap: 15px;
  }
  
  .brands img {
    max-width: 120px;
  }
}
</style>