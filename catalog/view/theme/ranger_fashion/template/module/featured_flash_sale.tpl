<div class="flashdeal-section">
	<div class="flashdeal-container">
		<h2 class="flashdeal-title"><?php echo $heading_title; ?></h2>
		<div class="flashdeal-grid">
			<?php foreach ($products as $p) { ?>
			<div class="flashdeal-card">
				<div class="flashdeal-media">
					<a href="<?php echo $p['href']; ?>" class="flashdeal-thumb">
						<img src="<?php echo $p['thumb']; ?>" alt="<?php echo $p['name']; ?>">
					</a>
					<?php if ($p['discount_percent']) { ?>
					<span class="flashdeal-badge">-<?php echo $p['discount_percent']; ?>%</span>
					<?php } ?>
				</div>
				<div class="flashdeal-info">
					<?php if (!empty($p['category_name'])) { ?><div class="flashdeal-cat"><?php echo $p['category_name']; ?></div><?php } ?>
					<a href="<?php echo $p['href']; ?>" class="flashdeal-name"><?php echo $p['name']; ?></a>
					<div class="flashdeal-rating">
						<?php for ($i=1;$i<=5;$i++) { ?><span class="star<?php echo ($p['rating'] && $i <= $p['rating']) ? ' filled' : ''; ?>"></span><?php } ?>
					</div>
					<div class="flashdeal-price">
						<?php if ($p['special']) { ?>
							<span class="price-new"><?php echo $p['special']; ?></span>
							<span class="price-old"><?php echo $p['price']; ?></span>
						<?php } else { ?>
							<span class="price-current"><?php echo $p['price']; ?></span>
						<?php } ?>
					</div>
					<?php if (!empty($date_end)) { ?>
					<div class="flashdeal-countdown" data-deadline="<?php echo $date_end; ?>">
						<div class="cd-item"><span class="num" data-unit="days">00</span><span class="lbl">Days</span></div>
						<div class="cd-item"><span class="num" data-unit="hours">00</span><span class="lbl">Hrs</span></div>
						<div class="cd-item"><span class="num" data-unit="minutes">00</span><span class="lbl">Min</span></div>
						<div class="cd-item"><span class="num" data-unit="seconds">00</span><span class="lbl">Sec</span></div>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
		</div>
		<?php if (!empty($see_all)) { ?><div class="flashdeal-seeall"><a href="<?php echo $see_all; ?>">See all</a></div><?php } ?>
	</div>
</div>

<style>
.flashdeal-section{padding:60px 0;background:#f6f8fa;width:100%;margin:0}
.flashdeal-container{margin:0 auto;padding:0 20px;width:100%;box-sizing:border-box}
.flashdeal-title{font-size:24px;font-weight:700;color:#111;margin:0 0 12px 0;display:flex;align-items:center;gap:12px}
.flashdeal-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px}
.flashdeal-card{display:flex;gap:18px;border:1px solid #ebedf0;border-radius:12px;background:#fff;padding:16px}
.flashdeal-media{position:relative;flex:0 0 38%}
.flashdeal-thumb{
    display:block;
    border-radius:10px;
    overflow:hidden;
    background:#fff;
    padding-top: 100%; /* 1:1 Aspect Ratio */
    position: relative;
}
.flashdeal-thumb img{
    position: absolute;
    top: 0;
    left: 0;
    width:100%;
    height:100%;
    object-fit:contain;
    display:block
}
.flashdeal-badge{position:absolute;top:10px;left:10px;background:#f0b429;color:#fff;border-radius:999px;font-size:12px;font-weight:700;padding:6px 10px}
.flashdeal-info{flex:1}
.flashdeal-cat{color:#7f8c8d;font-size:14px;margin-bottom:6px}
.flashdeal-name{display:block;color:#2c3e50;font-weight:600;text-decoration:none;margin-bottom:8px}
.flashdeal-rating{margin-bottom:8px}
.flashdeal-rating .star{display:inline-block;color:#d1d5db;font-size:14px}
.flashdeal-rating .star.filled{color:#f59e0b}
.flashdeal-price{display:flex;align-items:center;gap:10px;margin:8px 0 12px 0}
.price-old{text-decoration:line-through;color:#9aa0a6}
.price-new{color:#e74c3c;font-weight:700}
.price-current{color:#111;font-weight:700}
.flashdeal-countdown{display:flex;gap:10px}
.cd-item{background:#ffefe3;border:1px solid #ffd8bf;border-radius:8px;padding:8px 10px;text-align:center;min-width:56px}
.cd-item .num{display:block;color:#ff6a00;font-weight:700}
.cd-item .lbl{display:block;font-size:12px;color:#6b7280}
.flashdeal-seeall{text-align:right;margin-top:10px}
.flashdeal-seeall a{color:#ff6a00;text-decoration:none}
@media (max-width:1400px){.flashdeal-container{max-width:1200px;padding:0 15px}}
@media (max-width:1200px){.flashdeal-container{max-width:960px;padding:0 15px}}
@media (max-width:992px){
  .flashdeal-section{padding:40px 0}
  .flashdeal-container{padding:0 15px}
  .flashdeal-grid{grid-template-columns:1fr}
  .flashdeal-thumb img{height:200px}
}
@media (max-width:768px){
  .flashdeal-section{padding:30px 0}
  .flashdeal-container{padding:0 15px}
}
@media (max-width:576px){
  .flashdeal-section{padding:20px 0}
  .flashdeal-container{padding:0 10px}
  .flashdeal-card{padding:12px;gap:12px}
  .flashdeal-thumb img{height:160px}
  .cd-item{min-width:48px;padding:6px 8px}
}
</style>

<script>
(function(){
	function initCountdown(root){
		if(!root) return;
		var deadlineStr = root.getAttribute('data-deadline');
		if(!deadlineStr) return;
		var deadline = new Date(deadlineStr).getTime();
		function tick(){
			var now = Date.now();
			var diff = Math.max(0, deadline - now);
			var days = Math.floor(diff / (1000*60*60*24));
			var hours = Math.floor((diff / (1000*60*60)) % 24);
			var minutes = Math.floor((diff / (1000*60)) % 60);
			var seconds = Math.floor((diff / 1000) % 60);
			root.querySelector('[data-unit="days"]').textContent = String(days).padStart(2,'0');
			root.querySelector('[data-unit="hours"]').textContent = String(hours).padStart(2,'0');
			root.querySelector('[data-unit="minutes"]').textContent = String(minutes).padStart(2,'0');
			root.querySelector('[data-unit="seconds"]').textContent = String(seconds).padStart(2,'0');
		}
		tick();
		return setInterval(tick, 1000);
	}
	var sections = document.querySelectorAll('.flashdeal-countdown');
	sections.forEach(initCountdown);
})();
</script>