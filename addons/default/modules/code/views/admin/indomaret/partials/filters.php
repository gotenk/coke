<fieldset id="filters">
    <legend><?= lang('global:filters'); ?></legend>

    <?= form_open('', '', array('f_module' => $module_details['slug'])); ?>
        <ul>
            <li class="" style="vertical-align: top; width: 160px;">
                <label for="f_code" style="padding-bottom: 5px;"><?= lang('code:code_label'); ?></label>
                <?= form_input('f_code'); ?>
            </li>
        </ul>
        <ul>
            <li class="">
                <label for="f_is_used"><?= lang('code:is_used_label'); ?></label>
                <?= form_dropdown('f_is_used', array(
                    'all' => lang('global:select-all'),
                    'yes' => lang('code:yes_label'),
                    'no'  => lang('code:no_label'),
                )); ?>
            </li>
        </ul>
    <?= form_close(); ?>
</fieldset>
