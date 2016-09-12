<div class="one_full">
    <section class="title">
        <h4><?= lang('code:code_list'); ?></h4>
    </section>

    <section class="item">
        <div class="content">
            <?php if ($total_rows > 0) { ?>
                <?= $this->load->view('admin/indomaret/partials/filters'); ?>

                <div id="filter-stage">
                    <?= $this->load->view('admin/indomaret/tables/code'); ?>
                </div>
            <?php } else { ?>
                <div class="no_data"><?= lang('code:no_data'); ?></div>
            <?php } ?>
        </div>
    </section>
</div>
