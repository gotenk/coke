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
            <th width="10%"><?= lang('code:code_label'); ?></th>
            <th width="10%"><?= lang('code:is_used_label'); ?></th>
            <th width="10%"><?= lang('code:used_by_label'); ?></th>
            <th width="10%"><?= lang('code:date_used_label'); ?></th>
            <th width="10%"><?= lang('code:date_created_label'); ?></th>
            <th width="15%" style="text-align: center;"><?= lang('global:actions'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $value) { ?>
            <tr class="item" id="<?= $value['user_id']; ?>">
                <td>
                    <?php if ($value['user_id'] && $value['pemenang_id'] === null) { ?>
                        <?= form_checkbox('action_to[]', $value['user_id']); ?>
                    <?php } ?>
                </td>
                <td><?= $value['code']; ?></td>
                <td><?= $value['is_used']; ?></td>
                <td><?= $value['user']; ?></td>
                <td><?= $value['date_used']; ?></td>
                <td><?= $value['date_created']; ?></td>
                <td style="padding-top:10px; text-align: center;">
                    <?php if ($value['user_id'] && $value['pemenang_id'] === null) { ?>
                        <a href="<?= site_url(ADMIN_URL.'/code/winner/'.$value['user_id']); ?>" title="<?= lang('code:winner'); ?>" class="btn green" onclick="return confirm('Are you sure?');"><?= lang('code:winner'); ?></a>
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
