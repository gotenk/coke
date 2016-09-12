<?= cmc_form_open('frm_select_winner', site_url(ADMIN_URL.'/code/process_pemenang'), ''); ?>
    <div class="form_inputs">
        <ul>
            <li>
                <label><?= lang('code:select_pemenang_text'); ?> <span>*</span></label>
                <br>
                <?= form_password('password'); ?>
                <?= form_submit('submit', 'Submit'); ?>
            </li>
            <?php if ($temp_winner) { ?>
                <li>
                    <h3>
                        The winner is:
                        <a href="<?= site_url(ADMIN_URL.'/code/winner/'.$temp_winner->user_id); ?>" title="<?= lang('code:winner'); ?>" onclick="return confirm('Confirm winner?');"><?= $temp_winner->name; ?></a>
                    </h3>
                </li>
            <?php } ?>
        </ul>
    </div>
<?= form_close(); ?>

<fieldset id="filters">
    <legend><?= lang('global:filters'); ?></legend>

    <?= cmc_form_open('index_pilih_pemenang', '', '', array('f_module' => $module_details['slug'])); ?>
        <ul>
            <li class="" style="vertical-align: top; width: 160px;">
                <label for="f_name" style="padding-bottom: 5px;"><?= lang('code:name_label'); ?></label>
                <?= form_input('f_name'); ?>
            </li>
        </ul>
    <?= form_close(); ?>
</fieldset>
