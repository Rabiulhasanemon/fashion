<?php echo $header; ?>
<div class="container">

    <div class="main-content-wrapper account-page login-page login-phone">
        <div class="after-top-bar">
            <div class="wrapper-container p-top-5">
                <div  class="breadcrumb">
                    <ul itemscope itemtype="http://schema.org/BreadcrumbList">
                        <?php foreach ($breadcrumbs as $i => $breadcrumb) { if($i < 1) { ?>
                        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                        <?php } else { ?>
                        <li  itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemtype="http://schema.org/Thing" itemprop="item" href="<?php echo $breadcrumb['href']; ?>"><span itemprop="name"><?php echo $breadcrumb['text']; ?></span></a><meta itemprop="position" content="<?php echo $i; ?>" /></li>
                        <?php }} ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="wrapper-container ">
            <?php if ($success) { ?>
            <div class="alert" data-type="success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
            <?php } ?>
            <?php if ($error_warning) { ?>
            <div class="alert alert-danger" data-type="error"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
            <?php } ?>
            <div class="panel">
                <h3><?php echo $heading_title; ?></h3>
                <form action="<?php echo $action; ?>" method="post" class="contact-from-info">
                    <p class="text-center"><?php echo $text_pin; ?></p>
                    <?php if ($redirect) { ?>
                    <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
                    <?php } ?>
                    <div class="option">
                        <label for="input-pin"><?php echo $entry_pin; ?></label>
                        <div><input id="input-pin" type="text" name="pin" placeholder="<?php echo $entry_pin; ?>" autofocus></div>
                        <?php if ($error_pin) { ?>
                        <div class="text-danger"><?php echo $error_pin; ?></div>
                        <?php } ?>
                    </div>
                    <div class="button-wrap">
                        <button class="submit" type="submit"><?php echo $button_login; ?></button>
                        <button disabled class="submit sent-otp" type="button"><span><?php echo $button_resend_pin; ?></span><span class="counter">(30)</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
    app.onReady(window, "$", function () {
        var btn = $(".sent-otp");
        function updateCounter(count) {
            count--;
            if(count === 0) {
                btn.removeAttr("disabled")
                btn.find(".counter").text("")
            } else {
                btn.attr("disabled", "")
                btn.find(".counter").text("(" + count + ")")
                setTimeout(function () {
                    updateCounter(count)
                }, 1000)
            }
        }
        updateCounter(30);

        btn.on("click", function () {
            btn.button("loading", "Sending...")
            $.ajax({
                url: "account/login/send_pin",
                method: "POST",
                dataType: 'json',
                data: {telephone: $("[name=telephone]").val()},
                complete: function () {
                    btn.button("reset");
                    btn.append("<span class='counter'></span>")
                },
                success: function ($json) {
                    updateCounter(31)
                }
            })
        });
        document.getElementById("input-pin").focus()
    }, 10)
</script>