<?php if (!empty($modules)) { ?>
    <?php if (!empty($home_view_all_enabled)) { ?>
    <div class="hvab24_stack">
        <?php foreach ($modules as $module) { ?>
        <div class="hvab24_card">
            <?php echo $module; ?>
            <div class="hvab24_btnrow">
                <a class="hvab24_btn" href="<?php echo $home_view_all_link; ?>"><?php echo $home_view_all_label; ?></a>
            </div>
        </div>
        <?php } ?>
    </div>
    <style>
    .hvab24_stack {
        background: #ffffff !important;
        display: flex;
        flex-direction: column;
        gap: 0px !important;
    }
    .hvab24_card {
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 18px 50px rgba(15, 23, 42, 0.08);
        padding: 5px !important;
        position: relative;
        overflow: hidden;
    }
    .hvab24_btnrow {
        display: flex;
        justify-content: center;
        margin-top: 18px;
    }
    .hvab24_btn {
        border-radius: 999px;
        border: 1px solid #111;
        padding: 10px 26px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 12px;
        color: #111;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    .hvab24_btn:hover {
        background: #111;
        color: #fff;
    }
    </style>
    <?php } else { ?>
        <?php foreach ($modules as $module) { echo $module; } ?>
    <?php } ?>
<?php } ?>









