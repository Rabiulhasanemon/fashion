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
        <div class="ruplexa-offers-grid-compact">
            <div class="row">
                <?php foreach ($offers as $offer) { ?>
                <div class="col-lg-4 col-md-6 col-sm-12 ruplexa-offer-item-compact">
                    <div class="ruplexa-offer-card-compact" data-offer-id="<?php echo $offer['offer_id']; ?>">
                        <div class="ruplexa-offer-card-image-compact">
                            <a href="<?php echo $offer['href']; ?>" class="ruplexa-offer-card-link-compact">
                                <img src="<?php echo $offer['image']; ?>" alt="<?php echo htmlspecialchars($offer['title']); ?>" class="ruplexa-offer-image-compact">
                                <div class="ruplexa-offer-badge-compact">Limited Time</div>
                            </a>
                        </div>
                        
                        <div class="ruplexa-offer-card-content-compact">
                            <?php if (!empty($offer['branch'])) { ?>
                            <div class="ruplexa-offer-branch-compact">
                                <i class="fas fa-store"></i>
                                <span><?php echo htmlspecialchars($offer['branch']); ?></span>
                            </div>
                            <?php } ?>
                            
                            <h3 class="ruplexa-offer-title-compact">
                                <a href="<?php echo $offer['href']; ?>"><?php echo htmlspecialchars($offer['title']); ?></a>
                            </h3>
                            
                            <?php if (!empty($offer['short_description'])) { ?>
                            <p class="ruplexa-offer-desc-compact"><?php echo htmlspecialchars($offer['short_description']); ?></p>
                            <?php } ?>
                            
                            <div class="ruplexa-offer-meta-compact">
                                <div class="ruplexa-offer-dates-compact">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span><?php echo $offer['date_start']; ?> - <?php echo $offer['date_end']; ?></span>
                                </div>
                            </div>
                            
                            <?php if (isset($offer['date_end_timestamp']) && $offer['date_end_timestamp']) { ?>
                            <div class="ruplexa-offer-timer-compact-wrapper">
                                <div class="ruplexa-offer-timer-label-compact">Ends In:</div>
                                <div class="ruplexa-offer-timer-compact" data-end-time="<?php echo $offer['date_end_timestamp']; ?>">
                                    <span class="ruplexa-timer-box-compact">
                                        <span class="ruplexa-timer-num-compact" id="ruplexa-offer-days-<?php echo $offer['offer_id']; ?>">00</span>
                                        <span class="ruplexa-timer-unit-compact">D</span>
                                    </span>
                                    <span class="ruplexa-timer-colon-compact">:</span>
                                    <span class="ruplexa-timer-box-compact">
                                        <span class="ruplexa-timer-num-compact" id="ruplexa-offer-hours-<?php echo $offer['offer_id']; ?>">00</span>
                                        <span class="ruplexa-timer-unit-compact">H</span>
                                    </span>
                                    <span class="ruplexa-timer-colon-compact">:</span>
                                    <span class="ruplexa-timer-box-compact">
                                        <span class="ruplexa-timer-num-compact" id="ruplexa-offer-minutes-<?php echo $offer['offer_id']; ?>">00</span>
                                        <span class="ruplexa-timer-unit-compact">M</span>
                                    </span>
                                    <span class="ruplexa-timer-colon-compact">:</span>
                                    <span class="ruplexa-timer-box-compact">
                                        <span class="ruplexa-timer-num-compact" id="ruplexa-offer-seconds-<?php echo $offer['offer_id']; ?>">00</span>
                                        <span class="ruplexa-timer-unit-compact">S</span>
                                    </span>
                                </div>
                            </div>
                            <?php } ?>
                            
                            <div class="ruplexa-offer-footer-compact">
                                <a href="<?php echo $offer['href']; ?>" class="ruplexa-offer-btn-compact">
                                    View Details <i class="fas fa-arrow-right"></i>
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
    padding: 40px 0;
    min-height: 60vh;
}

.ruplexa-offer-page-header {
    text-align: center;
    margin-bottom: 35px;
    padding-bottom: 25px;
    border-bottom: 2px solid #E9ECEF;
}

.ruplexa-offer-page-title {
    font-size: 36px;
    font-weight: 700;
    color: #2C3E50;
    margin-bottom: 12px;
    letter-spacing: -0.5px;
    background: linear-gradient(135deg, #FF6B9D 0%, #FF8E9B 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.ruplexa-offer-page-subtitle {
    font-size: 16px;
    color: #6C757D;
    margin: 0;
    font-weight: 300;
}

/* Compact Premium Offer Cards */
.ruplexa-offers-grid-compact {
    margin-top: 30px;
}

.ruplexa-offer-item-compact {
    margin-bottom: 25px;
}

.ruplexa-offer-card-compact {
    background: #FFFFFF;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid rgba(255, 107, 157, 0.1);
    overflow: hidden;
}

.ruplexa-offer-card-compact:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 24px rgba(255, 107, 157, 0.2);
    border-color: rgba(255, 107, 157, 0.3);
}

.ruplexa-offer-card-image-compact {
    position: relative;
    overflow: hidden;
    padding-top: 55%;
    background: #F0F0F0;
}

.ruplexa-offer-image-compact {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.ruplexa-offer-card-compact:hover .ruplexa-offer-image-compact {
    transform: scale(1.06);
}

.ruplexa-offer-badge-compact {
    position: absolute;
    top: 12px;
    right: 12px;
    background: linear-gradient(135deg, #FF6B9D 0%, #FF8E9B 100%);
    color: #ffffff;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 3px 10px rgba(255, 107, 157, 0.4);
    z-index: 2;
}

.ruplexa-offer-card-content-compact {
    padding: 18px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.ruplexa-offer-branch-compact {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    color: #FF6B9D;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 10px;
    padding: 3px 10px;
    background: rgba(255, 107, 157, 0.1);
    border-radius: 10px;
    width: fit-content;
}

.ruplexa-offer-branch-compact i {
    font-size: 10px;
}

.ruplexa-offer-title-compact {
    margin: 0 0 10px 0;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.3;
    color: #2C3E50;
}

.ruplexa-offer-title-compact a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
}

.ruplexa-offer-title-compact a:hover {
    color: #FF6B9D;
    text-decoration: none;
}

.ruplexa-offer-desc-compact {
    color: #6C757D;
    font-size: 13px;
    line-height: 1.5;
    margin-bottom: 15px;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.ruplexa-offer-meta-compact {
    margin-bottom: 12px;
    padding-top: 12px;
    border-top: 1px solid #E9ECEF;
}

.ruplexa-offer-dates-compact {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #6C757D;
    font-weight: 500;
}

.ruplexa-offer-dates-compact i {
    color: #FF6B9D;
    font-size: 12px;
}

/* Compact Premium Timer */
.ruplexa-offer-timer-compact-wrapper {
    background: linear-gradient(135deg, rgba(255, 107, 157, 0.08) 0%, rgba(255, 142, 155, 0.08) 100%);
    border-radius: 10px;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid rgba(255, 107, 157, 0.15);
}

.ruplexa-offer-timer-label-compact {
    font-size: 10px;
    color: #FF6B9D;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 700;
    margin-bottom: 8px;
    text-align: center;
}

.ruplexa-offer-timer-compact {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
}

.ruplexa-timer-box-compact {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #FFFFFF;
    border-radius: 8px;
    padding: 6px 8px;
    min-width: 42px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(255, 107, 157, 0.2);
}

.ruplexa-timer-num-compact {
    font-size: 18px;
    font-weight: 700;
    color: #FF6B9D;
    line-height: 1.2;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    min-height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ruplexa-timer-unit-compact {
    font-size: 9px;
    color: #6C757D;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-top: 2px;
    font-weight: 600;
}

.ruplexa-timer-colon-compact {
    color: #FF6B9D;
    font-size: 16px;
    font-weight: 700;
    margin: 0 2px;
    animation: ruplexa-timer-colon-blink 1s infinite;
    line-height: 1;
}

@keyframes ruplexa-timer-colon-blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

.ruplexa-offer-footer-compact {
    margin-top: auto;
    padding-top: 15px;
    border-top: 1px solid #E9ECEF;
}

.ruplexa-offer-btn-compact {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(135deg, #FF6B9D 0%, #FF8E9B 100%);
    color: #ffffff;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    border-radius: 20px;
    transition: all 0.3s ease;
    box-shadow: 0 3px 10px rgba(255, 107, 157, 0.3);
    width: 100%;
    justify-content: center;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.ruplexa-offer-btn-compact:hover {
    background: linear-gradient(135deg, #FF8E9B 0%, #FF6B9D 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 16px rgba(255, 107, 157, 0.4);
    color: #ffffff;
    text-decoration: none;
    gap: 10px;
}

.ruplexa-offer-btn-compact i {
    transition: transform 0.3s ease;
    font-size: 12px;
}

.ruplexa-offer-btn-compact:hover i {
    transform: translateX(3px);
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
        font-size: 28px;
    }
    
    .ruplexa-offer-page-subtitle {
        font-size: 14px;
    }
    
    .ruplexa-offer-title-compact {
        font-size: 16px;
    }
    
    .ruplexa-timer-box-compact {
        min-width: 38px;
        padding: 5px 7px;
    }
    
    .ruplexa-timer-num-compact {
        font-size: 16px;
        min-height: 20px;
    }
}

@media (max-width: 767px) {
    .ruplexa-offer-hero-section {
        padding: 20px 0 15px;
    }
    
    .ruplexa-offer-main-section {
        padding: 25px 0;
    }
    
    .ruplexa-offer-page-header {
        margin-bottom: 25px;
        padding-bottom: 18px;
    }
    
    .ruplexa-offer-page-title {
        font-size: 24px;
    }
    
    .ruplexa-offer-page-subtitle {
        font-size: 13px;
    }
    
    .ruplexa-offer-item-compact {
        margin-bottom: 20px;
    }
    
    .ruplexa-offer-card-content-compact {
        padding: 15px;
    }
    
    .ruplexa-offer-title-compact {
        font-size: 16px;
    }
    
    .ruplexa-offer-timer-compact {
        gap: 3px;
        flex-wrap: wrap;
    }
    
    .ruplexa-timer-box-compact {
        min-width: 35px;
        padding: 5px 6px;
    }
    
    .ruplexa-timer-num-compact {
        font-size: 15px;
        min-height: 18px;
    }
    
    .ruplexa-timer-unit-compact {
        font-size: 8px;
    }
    
    .ruplexa-timer-colon-compact {
        font-size: 14px;
    }
    
    .ruplexa-offer-btn-compact {
        padding: 9px 18px;
        font-size: 12px;
    }
}
</style>

<script>
// Ruplexa Premium Offer Timer Counter
(function() {
    function initOfferTimers() {
        const timerDisplays = document.querySelectorAll('.ruplexa-offer-timer-compact[data-end-time]');
        
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
                    timerDisplay.innerHTML = '<div style="text-align: center; color: #FF6B9D; font-weight: 700; font-size: 12px; padding: 8px;">Expired</div>';
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
