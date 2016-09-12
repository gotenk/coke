<table cellspacing="0">
    <thead>
        <tr>
            <th width="10%"><?= lang('code:unique_code_label'); ?></th>
            <th width="10%"><?= lang('code:transaction_code_label'); ?></th>
            <th width="10%"><?= lang('code:used_by_label'); ?></th>
            <th width="10%"><?= lang('code:date_created_label'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $value) { ?>
            <tr class="item" id="<?= $value['user_id']; ?>">
                </td>
                <td><?= $value['unique_code']; ?></td>
                <td><?= $value['transaction_code']; ?></td>
                <td><?= $value['user']; ?></td>
                <td><?= $value['date_created']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?= $this->load->view('admin/partials/pagination'); ?>
