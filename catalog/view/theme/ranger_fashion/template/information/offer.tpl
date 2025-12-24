<?php echo $header; ?>
<section class="ruplexa-offer-hero-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <ul class="breadcrumb ruplexa-offer-breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</section>

<section class="ruplexa-offer-main-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="ruplexa-offer-page-header">
                    <h1 class="ruplexa-offer-page-title"><?php echo $heading_title; ?></h1>
                    <p class="ruplexa-offer-page-subtitle">Exclusive beauty deals and limited-time offers</p>
                </div>
            </div>
        </div>
        
        <?php if (!empty($offers)) { ?>
        <div class="ruplexa-offers-grid">
            <div class="row">
                <?php foreach ($offers as $offer) { ?>
                <div class="col-lg-6 col-md-12 ruplexa-offer-item-wrapper">
                    <div class="ruplexa-offer-card" data-offer-id="<?php echo $offer['offer_id']; ?>">
                        <div class="ruplexa-offer-card-image-wrapper">
                            <a href="<?php echo $offer['href']; ?>" class="ruplexa-offer-card-link">
                                <img src="<?php echo $offer['image']; ?>" alt="<?php echo htmlspecialchars($offer['title']); ?>" class="ruplexa-offer-card-image">
                                <div class="ruplexa-offer-card-overlay">
                                    <div class="ruplexa-offer-badge">Limited Time</div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="ruplexa-offer-card-content">
                            <?php if (!empty($offer['branch'])) { ?>
                            <div class="ruplexa-offer-branch">
                                <i class="fas fa-store"></i>
                                <span><?php echo htmlspecialchars($offer['branch']); ?></span>
                            </div>
                            <?php } ?>
                            
                            <h3 class="ruplexa-offer-card-title">
                                <a href="<?php echo $offer['href']; ?>"><?php echo htmlspecialchars($offer['title']); ?></a>
                            </h3>
                            
                            <?php if (!empty($offer['short_description'])) { ?>
                            <p class="ruplexa-offer-card-description"><?php echo htmlspecialchars($offer['short_description']); ?></p>
                            <?php } ?>
                            
                            <div class="ruplexa-offer-card-meta">
                                <div class="ruplexa-offer-dates">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Valid: <?php echo $offer['date_start']; ?> - <?php echo $offer['date_end']; ?></span>
                                </div>
                            </div>
                            
                            <?php if (isset($offer['date_end_timestamp']) && $offer['date_end_timestamp']) { ?>
                            <div class="ruplexa-offer-timer-wrapper">
                                <div class="ruplexa-offer-timer-label">Offer Ends In:</div>
                                <div class="ruplexa-offer-timer-display" data-end-time="<?php echo $offer['date_end_timestamp']; ?>">
                                    <div class="ruplexa-timer-box">
                                        <span class="ruplexa-timer-number" id="ruplexa-offer-days-<?php echo $offer['offer_id']; ?>">00</span>
                                        <span class="ruplexa-timer-unit">Days</span>
                                    </div>
                                    <span class="ruplexa-timer-colon">:</span>
                                    <div class="ruplexa-timer-box">
                                        <span class="ruplexa-timer-number" id="ruplexa-offer-hours-<?php echo $offer['offer_id']; ?>">00</span>
                                        <span class="ruplexa-timer-unit">Hours</span>
                                    </div>
                                    <span class="ruplexa-timer-colon">:</span>
                                    <div class="ruplexa-timer-box">
                                        <span class="ruplexa-timer-number" id="ruplexa-offer-minutes-<?php echo $offer['offer_id']; ?>">00</span>
                                        <span class="ruplexa-timer-unit">Min</span>
                                    </div>
                                    <span class="ruplexa-timer-colon">:</span>
                                    <div class="ruplexa-timer-box">
                                        <span class="ruplexa-timer-number" id="ruplexa-offer-seconds-<?php echo $offer['offer_id']; ?>">00</span>
                                        <span class="ruplexa-timer-unit">Sec</span>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            
                            <div class="ruplexa-offer-card-footer">
                                <a href="<?php echo $offer['href']; ?>" class="ruplexa-offer-view-btn">
                                    View Details
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php } else { ?>
        <div class="ruplexa-offer-empty">
            <div class="ruplexa-offer-empty-icon">
                <i class="fas fa-tag"></i>
            </div>
            <h3 class="ruplexa-offer-empty-title">No Active Offers</h3>
            <p class="ruplexa-offer-empty-text">Check back soon for exciting beauty deals!</p>
        </div>
        <?php } ?>
    </div>
</section>

<style>
/* Ruplexa Premium Offer Page - Cosmetics Theme */
.ruplexa-offer-hero-section {
    background: linear-gradient(135deg, #FF6B9D 0%, #FF8E9B 100%);
    padding: 30px 0 20px;
    margin-bottom: 0;
}

.ruplexa-offer-breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
}

.ruplexa-offer-breadcrumb li a {
    color: rgba(255, 255, 255, 0.9);
    font-size: 14px;
    transition: color 0.3s ease;
}

.ruplexa-offer-breadcrumb li a:hover {
    color: #ffffff;
    text-decoration: none;
}

.ruplexa-offer-breadcrumb li:last-child a {
    color: #ffffff;
    font-weight: 600;
}

.ruplexa-offer-breadcrumb li + li:before {
    content: "/";
    color: rgba(255, 255, 255, 0.7);
    padding: 0 10px;
}

.ruplexa-offer-main-section {
    background: #F8F9FA;
    padding: 50px 0;
    min-height: 60vh;
}

.ruplexa-offer-page-header {
    text-align: center;
    margin-bottom: 50px;
    padding-bottom: 30px;
    border-bottom: 2px solid #E9ECEF;
}

.ruplexa-offer-page-title {
    font-size: 42px;
    font-weight: 700;
    color: #2C3E50;
    margin-bottom: 15px;
    letter-spacing: -0.5px;
    background: linear-gradient(135deg, #FF6B9D 0%, #FF8E9B 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.ruplexa-offer-page-subtitle {
    font-size: 18px;
    color: #6C757D;
    margin: 0;
    font-weight: 300;
}

.ruplexa-offers-grid {
    margin-top: 40px;
}

.ruplexa-offer-item-wrapper {
    margin-bottom: 30px;
}

.ruplexa-offer-card {
    background: #FFFFFF;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid rgba(255, 107, 157, 0.1);
}

.ruplexa-offer-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 28px rgba(255, 107, 157, 0.2);
    border-color: rgba(255, 107, 157, 0.3);
}

.ruplexa-offer-card-image-wrapper {
    position: relative;
    overflow: hidden;
    padding-top: 60%;
    background: #F0F0F0;
}

.ruplexa-offer-card-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.ruplexa-offer-card:hover .ruplexa-offer-card-image {
    transform: scale(1.08);
}

.ruplexa-offer-card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.4) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: flex-start;
    justify-content: flex-end;
    padding: 15px;
}

.ruplexa-offer-card:hover .ruplexa-offer-card-overlay {
    opacity: 1;
}

.ruplexa-offer-badge {
    background: linear-gradient(135deg, #FF6B9D 0%, #FF8E9B 100%);
    color: #ffffff;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 12px rgba(255, 107, 157, 0.4);
}

.ruplexa-offer-card-content {
    padding: 25px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.ruplexa-offer-branch {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #FF6B9D;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
    padding: 4px 12px;
    background: rgba(255, 107, 157, 0.1);
    border-radius: 12px;
    width: fit-content;
}

.ruplexa-offer-branch i {
    font-size: 11px;
}

.ruplexa-offer-card-title {
    margin: 0 0 12px 0;
    font-size: 24px;
    font-weight: 700;
    line-height: 1.3;
    color: #2C3E50;
}

.ruplexa-offer-card-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
}

.ruplexa-offer-card-title a:hover {
    color: #FF6B9D;
    text-decoration: none;
}

.ruplexa-offer-card-description {
    color: #6C757D;
    font-size: 15px;
    line-height: 1.6;
    margin-bottom: 20px;
    flex: 1;
}

.ruplexa-offer-card-meta {
    margin-bottom: 20px;
    padding-top: 15px;
    border-top: 1px solid #E9ECEF;
}

.ruplexa-offer-dates {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #6C757D;
    font-weight: 500;
}

.ruplexa-offer-dates i {
    color: #FF6B9D;
    font-size: 14px;
}

/* Premium Timer Counter */
.ruplexa-offer-timer-wrapper {
    background: linear-gradient(135deg, rgba(255, 107, 157, 0.1) 0%, rgba(255, 142, 155, 0.1) 100%);
    border-radius: 12px;
    padding: 18px;
    margin-bottom: 20px;
    border: 2px solid rgba(255, 107, 157, 0.2);
}

.ruplexa-offer-timer-label {
    font-size: 11px;
    color: #FF6B9D;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 700;
    margin-bottom: 12px;
    text-align: center;
}

.ruplexa-offer-timer-display {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.ruplexa-timer-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #FFFFFF;
    border-radius: 10px;
    padding: 10px 12px;
    min-width: 60px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 107, 157, 0.2);
}

.ruplexa-timer-number {
    font-size: 24px;
    font-weight: 700;
    color: #FF6B9D;
    line-height: 1.2;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    min-height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ruplexa-timer-unit {
    font-size: 10px;
    color: #6C757D;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 4px;
    font-weight: 600;
}

.ruplexa-timer-colon {
    color: #FF6B9D;
    font-size: 20px;
    font-weight: 700;
    margin: 0 4px;
    animation: ruplexa-timer-colon-blink 1s infinite;
    line-height: 1;
}

@keyframes ruplexa-timer-colon-blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

.ruplexa-offer-card-footer {
    margin-top: auto;
    padding-top: 20px;
    border-top: 1px solid #E9ECEF;
}

.ruplexa-offer-view-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    background: linear-gradient(135deg, #FF6B9D 0%, #FF8E9B 100%);
    color: #ffffff;
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    border-radius: 25px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(255, 107, 157, 0.3);
    width: 100%;
    justify-content: center;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.ruplexa-offer-view-btn:hover {
    background: linear-gradient(135deg, #FF8E9B 0%, #FF6B9D 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 107, 157, 0.4);
    color: #ffffff;
    text-decoration: none;
    gap: 14px;
}

.ruplexa-offer-view-btn i {
    transition: transform 0.3s ease;
}

.ruplexa-offer-view-btn:hover i {
    transform: translateX(4px);
}

/* Empty State */
.ruplexa-offer-empty {
    text-align: center;
    padding: 80px 20px;
    background: #FFFFFF;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.ruplexa-offer-empty-icon {
    font-size: 80px;
    color: #E0E0E0;
    margin-bottom: 30px;
}

.ruplexa-offer-empty-title {
    font-size: 28px;
    font-weight: 700;
    color: #2C3E50;
    margin-bottom: 15px;
}

.ruplexa-offer-empty-text {
    font-size: 16px;
    color: #6C757D;
    margin: 0;
}

/* Responsive Design */
@media (max-width: 991px) {
    .ruplexa-offer-page-title {
        font-size: 32px;
    }
    
    .ruplexa-offer-page-subtitle {
        font-size: 16px;
    }
    
    .ruplexa-offer-card-title {
        font-size: 22px;
    }
    
    .ruplexa-timer-box {
        min-width: 50px;
        padding: 8px 10px;
    }
    
    .ruplexa-timer-number {
        font-size: 20px;
    }
}

@media (max-width: 767px) {
    .ruplexa-offer-hero-section {
        padding: 20px 0 15px;
    }
    
    .ruplexa-offer-main-section {
        padding: 30px 0;
    }
    
    .ruplexa-offer-page-header {
        margin-bottom: 30px;
        padding-bottom: 20px;
    }
    
    .ruplexa-offer-page-title {
        font-size: 28px;
    }
    
    .ruplexa-offer-page-subtitle {
        font-size: 15px;
    }
    
    .ruplexa-offer-item-wrapper {
        margin-bottom: 25px;
    }
    
    .ruplexa-offer-card-content {
        padding: 20px;
    }
    
    .ruplexa-offer-card-title {
        font-size: 20px;
    }
    
    .ruplexa-offer-timer-display {
        gap: 6px;
        flex-wrap: wrap;
    }
    
    .ruplexa-timer-box {
        min-width: 45px;
        padding: 6px 8px;
    }
    
    .ruplexa-timer-number {
        font-size: 18px;
        min-height: 24px;
    }
    
    .ruplexa-timer-unit {
        font-size: 9px;
    }
    
    .ruplexa-timer-colon {
        font-size: 16px;
    }
    
    .ruplexa-offer-view-btn {
        padding: 12px 24px;
        font-size: 14px;
    }
}
</style>

<script>
// Ruplexa Premium Offer Timer Counter
(function() {
    function initOfferTimers() {
        const timerDisplays = document.querySelectorAll('.ruplexa-offer-timer-display[data-end-time]');
        
        timerDisplays.forEach(function(timerDisplay) {
            const endTime = parseInt(timerDisplay.getAttribute('data-end-time'));
            if (!endTime) return;
            
            const offerCard = timerDisplay.closest('.ruplexa-offer-card');
            const offerId = offerCard ? offerCard.getAttribute('data-offer-id') : '';
            
            function updateTimer() {
                const now = Math.floor(Date.now() / 1000);
                const timeLeft = endTime - now;
                
                if (timeLeft <= 0) {
                    // Timer expired
                    timerDisplay.innerHTML = '<div style="text-align: center; color: #FF6B9D; font-weight: 700; font-size: 14px; padding: 10px;">Offer Expired</div>';
                    if (offerCard) {
                        offerCard.style.opacity = '0.7';
                    }
                    return;
                }
                
                const days = Math.floor(timeLeft / 86400);
                const hours = Math.floor((timeLeft % 86400) / 3600);
                const minutes = Math.floor((timeLeft % 3600) / 60);
                const seconds = timeLeft % 60;
                
                const daysEl = document.getElementById('ruplexa-offer-days-' + offerId);
                const hoursEl = document.getElementById('ruplexa-offer-hours-' + offerId);
                const minutesEl = document.getElementById('ruplexa-offer-minutes-' + offerId);
                const secondsEl = document.getElementById('ruplexa-offer-seconds-' + offerId);
                
                if (daysEl) daysEl.textContent = String(days).padStart(2, '0');
                if (hoursEl) hoursEl.textContent = String(hours).padStart(2, '0');
                if (minutesEl) minutesEl.textContent = String(minutes).padStart(2, '0');
                if (secondsEl) secondsEl.textContent = String(seconds).padStart(2, '0');
            }
            
            // Update immediately
            updateTimer();
            
            // Update every second
            setInterval(updateTimer, 1000);
        });
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initOfferTimers);
    } else {
        initOfferTimers();
    }
})();
</script>

<?php echo $footer; ?>
