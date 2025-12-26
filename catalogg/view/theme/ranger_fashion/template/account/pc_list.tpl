<?php echo $header; ?>
<section class="after-header">
    <div class="container">
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ul>
    </div>
</section>
<div class="container account-page">
    <?php echo $column_left; ?>
    <div id="content" class="content left-wrapper">
        <div class="my-info">
        <h1><?php echo $heading_title; ?></h1>
        <?php if ($pcs) { ?>
        <div class="table-responsive">
            <table class="table table-bpced table-hover">
                <thead>
                <tr>
                    <td class="text-right"><?php echo $column_pc_id; ?></td>
                    <td class="text-right"><?php echo $column_name; ?></td>
                    <td class="text-right"><?php echo $column_description; ?></td>
                    <td class="text-right"><?php echo $column_date_added; ?></td>
                    <td></td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($pcs as $pc) { ?>
                <tr>
                    <td class="text-right">#<?php echo $pc['pc_id']; ?></td>
                    <td class="text-right"><?php echo $pc['name']; ?></td>
                    <td class="text-right"><?php echo $pc['description']; ?></td>
                    <td class="text-right"><?php echo $pc['date_added']; ?></td>
                    <td class="text-right">
                        <a href="<?php echo $pc['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a>
                        <a href="<?php echo $pc['delete']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-info delete"><i class="fa fa-remove"></i></a>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="text-right"><?php echo $pagination; ?></div>
        <?php } else { ?>
        <p><?php echo $text_empty; ?></p>
        <?php } ?>
        </div>
    </div>
</div>
<script>
app.onReady(window, "$", function () {
    $('.btn.delete').on("click", function (e) {
        return confirm("Are your sure about this")
    })
}, 20)
</script>
<?php echo $footer; ?>