<div class="one_full">
    <section class="title">
        <h4><?= lang('code:code_list'); ?></h4>
    </section>

    <section class="item">
        <div class="content">
            <?php if ($total_rows > 0) { ?>
                <?= $this->load->view('admin/alfamart/partials/filters'); ?>

                <?= cmc_form_open('frm_alfamart', ADMIN_URL.'/code/alfamart/action'); ?>
                    <div id="filter-stage">
                        <?= $this->load->view('admin/alfamart/tables/code'); ?>
                    </div>
                <?= form_close(); ?>
            <?php } else { ?>
                <div class="no_data"><?= lang('code:no_data'); ?></div>
            <?php } ?>
        </div>
    </section>
</div>
