<table cellspacing="0">
    <thead>
        <tr>
            <th width="10%"><?= lang('code:name_label'); ?></th>
            <th width="10%"><?= lang('code:code_count_label'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $value) { ?>
            <tr>
                <td><?= $value['name']; ?></td>
                <td><?= $value['count']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?= $this->load->view('admin/partials/pagination'); ?>
