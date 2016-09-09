<table cellspacing="0">
    <thead>
        <tr>
            <th width="5%"><?= lang('code:number_label'); ?></th>
            <th width="10%"><?= lang('code:name_label'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; ?>
        <?php foreach ($data as $value) { ?>
            <tr>
                <td><?= $no; ?></td>
                <td><?= $value['name']; ?></td>
            </tr>
            <?php $no++; ?>
        <?php } ?>
    </tbody>
</table>

<?= $this->load->view('admin/partials/pagination'); ?>
