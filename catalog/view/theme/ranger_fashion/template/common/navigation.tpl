<div class="main-nav">
    <nav class="nav" id="main-nav">
        <ul class="responsive-menu">
            <?php foreach ($categories as $category) { ?>
            <?php if ($category['children']) { ?>
            <li class="drop-open c-1 padding-right">
                <a class="padding-right"  href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?><i class="fa fa-arrow-down"></i></a>
                <ul class="drop-down drop-menu-1">
                    <?php foreach ($category['children'] as $child) { ?><?php if ($child['children']) { ?>
                    <li class="drop-open">
                        <a class="sub-parent" href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a>
                        <ul class="drop-down drop-menu-2">
                            <?php foreach ($child['children'] as $child_level_2) { ?>
                            <li>
                                <a href="<?php echo $child_level_2['href']; ?>"><?php echo $child_level_2['name']; ?></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } else { ?>
                    <li>
                        <a class="sub-parent" href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a>
                    </li>
                    <?php } ?><?php } ?>
                    
                </ul>
            </li>
            <?php } else { ?>
            <li class="drop-open c-1 padding-right">
                <a href="<?php echo $category['href'] ; ?>"><?php echo $category['name']; ?></a>
            </li>
            <?php } ?><?php } ?>
            <li><a class="btn new-arrive" href="product-new-arrivals">New Arrivals</a></li>
        </ul>
        <div class="overlay"></div>
    </nav>
</div>