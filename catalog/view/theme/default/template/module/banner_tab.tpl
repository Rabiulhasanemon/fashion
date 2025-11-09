<div class="col-lg-4">
    <div class="leaderboard-section">
        <div class="section-head">
            <h3 class="title">
                <img src="catalog/view/theme/on_field/image/trophy.svg" alt="Trophy"> <?php echo $name; ?>
            </h3>

        </div>

        <div class="leaderboard-tab-list">
            <ul class="nav nav-tabs">
                <?php foreach ($banners as $banner) { ?>
                <li class="tab-links" onclick="openTab(event, '<?php echo $banner['name']; ?>')"><?php echo $banner['name']; ?></li>
                <?php } ?>

            </ul>

        </div>

        <?php foreach ($banners as $banner) { ?>
        <div class="leaderboard-tab-details" id="<?php echo $banner['name']; ?>">
            <div class="leaderboard-list-wrapper">
                <?php if ($banner['banner_children']) { ?>
                <?php foreach ($banner['banner_children'] as $child) { ?>
                <div class="single-leaderboard-item <?php echo $child['image_class']; ?>">
                    <div class="leaderboard-img">
                        <img src="<?php echo $child['image']; ?>" alt="<?php echo $child['title']; ?>">
                    </div>

                    <div class="leaderboard-info">
                        <div class="tag"><?php echo $child['link']; ?></div>

                        <h5 class="name"><?php echo $child['title']; ?></h5>

                        <p class="points"><?php echo $child['blurb']; ?></p>

                    </div>

                </div>
                <?php } ?>
                <?php } ?>
            </div>

        </div>
        <?php } ?>

        <p class="nb">
            <?php echo $blurb; ?>
        </p>

    </div>

</div>



<script>
    // Javascript Code
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("leaderboard-tab-details");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tab-links");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    tablinks = document.getElementsByClassName("tab-links");
    acitve_tab = tablinks[0].setAttribute("id", "defaultOpen");
    document.getElementById("defaultOpen").click();

</script>