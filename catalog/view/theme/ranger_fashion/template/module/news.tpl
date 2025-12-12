<div class="nws-module-wrapper">
    <div class="container">
        <div class="nws-content-box">
            <?php if (isset($articles) && !empty($articles)) { ?>
            <!-- Headline Articles Mode -->
            <div class="nws-slider-container">
                <div class="nws-slider-track">
                    <?php foreach ($articles as $article) { ?>
                    <div class="nws-slide-item">
                        <a href="<?php echo isset($article['href']) ? $article['href'] : '#'; ?>" class="nws-article-link">
                            <span class="nws-badge">NEWS</span>
                            <span class="nws-text"><?php echo isset($article['name']) ? htmlspecialchars($article['name']) : ''; ?></span>
                        </a>
                    </div>
                    <?php } ?>
                    <!-- Duplicate for seamless loop -->
                    <?php foreach ($articles as $article) { ?>
                    <div class="nws-slide-item">
                        <a href="<?php echo isset($article['href']) ? $article['href'] : '#'; ?>" class="nws-article-link">
                            <span class="nws-badge">NEWS</span>
                            <span class="nws-text"><?php echo isset($article['name']) ? htmlspecialchars($article['name']) : ''; ?></span>
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php } else if (isset($news) && !empty($news)) { ?>
            <!-- Simple Text Mode -->
            <div class="nws-slider-container">
                <div class="nws-slider-track">
                    <div class="nws-slide-item">
                        <div class="nws-text-content">
                            <span class="nws-badge">NEWS</span>
                            <span class="nws-text"><?php echo $news; ?></span>
                        </div>
                    </div>
                    <!-- Duplicate for seamless loop -->
                    <div class="nws-slide-item">
                        <div class="nws-text-content">
                            <span class="nws-badge">NEWS</span>
                            <span class="nws-text"><?php echo $news; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<style>
/* =================================================
   NEWS MODULE - NWS Prefix (New Classes)
   Animated Sliding News Ticker
   ================================================= */

.nws-module-wrapper {
    background: linear-gradient(135deg, #a68a6a 0%, #10503d 100%);
    padding: 12px 0;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(16, 80, 61, 0.2);
}

.nws-content-box {
    position: relative;
    overflow: hidden;
}

.nws-slider-container {
    position: relative;
    overflow: hidden;
    width: 100%;
}

.nws-slider-track {
    display: flex;
    gap: 40px;
    width: max-content;
    animation: nwsSlide 30s linear infinite;
    will-change: transform;
}

.nws-slide-item {
    flex-shrink: 0;
    white-space: nowrap;
}

.nws-article-link,
.nws-text-content {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: #ffffff;
    font-size: 14px;
    font-weight: 500;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
    transition: opacity 0.3s ease;
}

.nws-article-link:hover {
    opacity: 0.8;
}

.nws-badge {
    background: rgba(255, 255, 255, 0.25);
    color: #ffffff;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.nws-text {
    color: #ffffff;
    line-height: 1.5;
}

/* Animation */
@keyframes nwsSlide {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

/* Pause on hover */
.nws-module-wrapper:hover .nws-slider-track {
    animation-play-state: paused;
}

/* Responsive */
@media (max-width: 768px) {
    .nws-module-wrapper {
        padding: 10px 0;
    }
    
    .nws-article-link,
    .nws-text-content {
        font-size: 13px;
        gap: 10px;
    }
    
    .nws-badge {
        font-size: 10px;
        padding: 3px 8px;
    }
    
    .nws-slider-track {
        gap: 30px;
        animation-duration: 25s;
    }
}

@media (max-width: 480px) {
    .nws-module-wrapper {
        padding: 8px 0;
    }
    
    .nws-article-link,
    .nws-text-content {
        font-size: 12px;
        gap: 8px;
    }
    
    .nws-badge {
        font-size: 9px;
        padding: 2px 6px;
    }
    
    .nws-slider-track {
        gap: 25px;
        animation-duration: 20s;
    }
}
</style>

<script>
// Ensure seamless animation on page load
document.addEventListener('DOMContentLoaded', function() {
    const tracks = document.querySelectorAll('.nws-slider-track');
    tracks.forEach(function(track) {
        // Reset animation to ensure smooth loop
        track.style.animation = 'none';
        setTimeout(function() {
            track.style.animation = '';
        }, 10);
    });
});
</script>
