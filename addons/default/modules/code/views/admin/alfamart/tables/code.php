<div class="table_action_buttons">
    <button class="btn green" value="winner" name="btnAction" type="submit" disabled="">
        <span>Set As Winner</span>
    </button>
</div>

<br>

<table cellspacing="0">
    <thead>
        <tr>
            <th width="5%"><?= form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
            <th width="10%"><?= lang('code:unique_code_label'); ?></th>
            <th width="10%"><?= lang('code:transaction_code_label'); ?></th>
            <th width="10%"><?= lang('code:used_by_label'); ?></th>
            <th width="10%"><?= lang('code:date_created_label'); ?></th>
            <th width="15%" style="text-align: center;"><?= lang('global:actions'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $value) { ?>
            <tr class="item" id="<?= $value['user_id']; ?>">
                <td>
                    <?php if ($value['pemenang_id'] === null) { ?>
                        <?php if ($value['pemenang_temp_id'] === null) { ?>
                            <?= form_checkbox('action_to[]', $value['code_id']); ?>
                        <?php } ?>
                    <?php } ?>
                </td>
                <td><?= $value['unique_code']; ?></td>
                <td><?= $value['transaction_code']; ?></td>
                <td><?= $value['user']; ?></td>
                <td><?= $value['date_created']; ?></td>
                <td style="padding-top:10px; text-align: center;">
                    <?php if ($value['pemenang_id'] === null) { ?>
                        <?php if ($value['pemenang_temp_id'] === null) { ?>
                            <a href="<?= site_url(ADMIN_URL.'/code/alfamart/winner/'.$value['code_id']); ?>" title="<?= lang('code:winner'); ?>" class="btn green" onclick="return confirm('Are you sure?');"><?= lang('code:winner'); ?></a>
                        <?php } ?>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?= $this->load->view('admin/partials/pagination'); ?>

<br>

<div class="table_action_buttons">
    <button class="btn green" value="winner" name="btnAction" type="submit" disabled="">
        <span>Set As Winner</span>
    </button>
</div>
