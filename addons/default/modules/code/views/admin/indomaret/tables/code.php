<div class="table_action_buttons">
    <button class="btn red" value="delete" name="btnAction" type="submit" disabled="">
        <span>Delete</span>
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
            <tr class="item" id="<?= $value['code_id']; ?>">
                <td><?= form_checkbox('action_to[]', $value['code_id']); ?></td>
                <td><?= $value['code']; ?></td>
                <td><?= $value['is_used']; ?></td>
                <td><?= $value['user']; ?></td>
                <td><?= $value['date_used']; ?></td>
                <td><?= $value['date_created']; ?></td>
                <td style="padding-top:10px; text-align: center;">
                    <a href="<?= site_url(ADMIN_URL.'/code/indomaret/delete/'.$value['code_id']); ?>" title="<?= lang('global:delete'); ?>" class="btn red" onclick="return confirm('Are you sure?');"><?= lang('global:delete'); ?></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?= $this->load->view('admin/partials/pagination'); ?>

<br>

<div class="table_action_buttons">
    <button class="btn red" value="delete" name="btnAction" type="submit" disabled="">
        <span>Delete</span>
    </button>
</div>
