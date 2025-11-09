<?php echo $header; ?>
<section class="after-header aaa 22p-tb-10">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</section>
<div class="container body">
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
        <div class="store-header">
            <div class="row">
                <div class="col-6">
                    <h2>Sales Outlets</h2>
                </div>
                <div class="col-6 search-store">
                    <input type="text" placeholder="Search Area" id="input-search"/>
                </div>
            </div>
        </div>
        <div class="stores bt-gray-3p">
            <div class="store dhaka row">
                <?php foreach ($locations as $location) { ?>
                <div class="location">
                    <div class="location-inner">
                        <div class="image">
                            <img src="<?php echo $location['image']; ?>" height="120" width="120" alt="<?php echo $location['name']; ?>">
                        </div>
                        <div class="location-details">
                            <h3><span><?php echo $location['name']; ?></span></h3>
                            <p class="location-line"><?php echo $location['address']; ?></p>
                        </div>
                        <hr>
                        <div class="phone"><i class="fa fa-phone"></i> <?php echo $location['telephone']; ?></div>
                        <hr>
                        <div class="store_footer">
                            <?php if($location['open']){ ?>
                            <div class="closed-day"><?php echo $location['open']; ?></div>
                            <?php } ?>
                            <div class="map-link hide">
                                <a  href="<?php echo $location['geocode']; ?>" target="_blank" rel="noopener">
                                    <span><i class="fa fa-map-marker"></i> Show on Map</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php echo $content_bottom; ?>
    </div>
  </div><?php echo $column_right; ?></div>
</div>
<script type="text/javascript">
    $("#input-search").on("keyup", function() {
        var keyword = this.value.trim().toLowerCase(), addresses = $(".location")
        if(!keyword) {
            addresses.removeClass("hide")
        }
        var keywords = keyword.split(/\s/)
        addresses.each(function () {
            var $this = $(this), text = $this.find("h3").text().toLowerCase();
            text += $this.find("p").text().toLowerCase();
            text += $this.find("meta").attr("content");
            var result = true
            keywords.forEach(function (value) {
                result = result && (text.search(value) !== -1)
            })
            if(result) {
                $this.removeClass("hide")
            } else {
                $this.addClass("hide")
            }
        })
    });
</script>
<?php echo $footer; ?>
