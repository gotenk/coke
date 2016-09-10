<fieldset id="filters">
    <legend><?= lang('global:filters'); ?></legend>

    <?= cmc_form_open('index_alfamart', '', '', array('f_module' => $module_details['slug'])); ?>
        <ul>
            <li class="" style="vertical-align: top; width: 160px;">
                <label for="f_unique_code" style="padding-bottom: 5px;"><?= lang('code:unique_code_label'); ?></label>
                <?= form_input('f_unique_code'); ?>
            </li>
            <li class="" style="vertical-align: top; width: 160px; margin-left: 15px;">
                <label for="f_transaction_code" style="padding-bottom: 5px;"><?= lang('code:transaction_code_label'); ?></label>
                <?= form_input('f_transaction_code'); ?>
            </li>
            <!-- <li class="" style="top:24px;float:right;">
                <a href="javascript:void(0);" title="Export Data" class="btn blue" id="export-data">Export Data CSV</a>
            </li> -->
        </ul>
    <?= form_close(); ?>
</fieldset>
