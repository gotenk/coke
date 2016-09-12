<div class="one_full">
    <section class="title">
        <h4><?= lang('code:select_pemenang_title'); ?></h4>
    </section>

    <section class="item">
        <div class="content">
            <?php if ($total_rows > 0) { ?>
                <?= $this->load->view('admin/pemenang/partials/filters_form'); ?>

                <div id="filter-stage">
                    <?= $this->load->view('admin/pemenang/tables/table_form'); ?>
                </div>
            <?php } else { ?>
                <div class="no_data"><?= lang('code:no_data'); ?></div>
            <?php } ?>
        </div>
    </section>
</div>
