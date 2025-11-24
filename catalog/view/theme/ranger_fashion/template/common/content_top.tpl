<?php if (!empty($modules)) { ?>
    <?php if (!empty($home_view_all_enabled)) { ?>
    <div class="hvab24_stack">
        <?php 
        $module_index = 0;
        foreach ($modules as $module) { 
            $has_products = isset($module_has_products[$module_index]) && $module_has_products[$module_index];
            $module_index++;
        ?>
        <div class="hvab24_card">
            <?php 
            // Wrap module output to inject "All Products" button in section headers
            $module_output = $module;
            if ($has_products && !empty($module_output) && is_string($module_output)) {
                // Inject button into section headers that have products
                $button_html = '<div class="lux-view-all-btn-wrapper"><a class="lux-view-all-btn" href="' . htmlspecialchars($home_view_all_link) . '">' . htmlspecialchars($home_view_all_label) . ' <i class="fa fa-arrow-right"></i></a></div>';
                // Try to inject into common section header patterns
                $patterns = array(
                    '/<div class="section-title">(.*?)(<div class="links">)/is' => '<div class="section-title"><div class="lux-section-header-row">$1<div class="lux-header-actions">' . $button_html . '</div></div>$2',
                    '/<div class="section-title">(.*?)(<\/div>\s*<\/div>)/is' => '<div class="section-title"><div class="lux-section-header-row">$1<div class="lux-header-actions">' . $button_html . '</div></div>$2',
                    '/<h2 class="h3">(.*?)<\/h2>/is' => '<div class="lux-section-header-row"><h2 class="h3">$1</h2><div class="lux-header-actions">' . $button_html . '</div></div>',
                );
                foreach ($patterns as $pattern => $replacement) {
                    if (preg_match($pattern, $module_output)) {
                        $module_output = preg_replace($pattern, $replacement, $module_output, 1);
                        break;
                    }
                }
            }
            echo $module_output; 
            ?>
        </div>
        <?php } ?>
    </div>
    <style>
    .hvab24_stack {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }
    .hvab24_card {
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 18px 50px rgba(15,23,42,0.08);
        padding: 24px;
        position: relative;
        overflow: hidden;
    }
    /* Premium "All Products" button in section header */
    .lux-section-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        gap: 20px;
    }
    .lux-header-actions {
        display: flex;
        align-items: center;
        margin-left: auto;
    }
    .lux-view-all-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        border-radius: 999px;
        background: linear-gradient(135deg, #10503d 0%, #0a3d2e 100%);
        color: #fff;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        letter-spacing: 0.02em;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(16, 80, 61, 0.3);
        border: none;
    }
    .lux-view-all-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 80, 61, 0.4);
        background: linear-gradient(135deg, #0a3d2e 0%, #10503d 100%);
    }
    .lux-view-all-btn i {
        font-size: 12px;
        transition: transform 0.3s ease;
    }
    .lux-view-all-btn:hover i {
        transform: translateX(3px);
    }
    @media (max-width: 767px) {
        .lux-section-header-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        .lux-header-actions {
            margin-left: 0;
            width: 100%;
        }
        .lux-view-all-btn {
            width: 100%;
            justify-content: center;
        }
    }
    </style>
    <?php } else { ?>
        <?php foreach ($modules as $module) { echo $module; } ?>
    <?php } ?>
<?php } ?>
