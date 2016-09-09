<fieldset id="filters">
    <legend><?= lang('global:filters'); ?></legend>

    <?= form_open('', '', array('f_module' => $module_details['slug'])); ?>
        <ul>
            <li class="" style="vertical-align: top; width: 160px;">
                <label for="f_name" style="padding-bottom: 5px;"><?= lang('code:name_label'); ?></label>
                <?= form_input('f_name'); ?>
            </li>
        </ul>
    <?= form_close(); ?>
</fieldset>
