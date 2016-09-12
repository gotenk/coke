<table cellspacing="0">
    <thead>
        <tr>
            <th width="10%"><?= lang('code:code_label'); ?></th>
            <th width="10%"><?= lang('code:is_used_label'); ?></th>
            <th width="10%"><?= lang('code:used_by_label'); ?></th>
            <th width="10%"><?= lang('code:date_used_label'); ?></th>
            <th width="10%"><?= lang('code:date_created_label'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $value) { ?>
            <tr class="item" id="<?= $value['user_id']; ?>">
                <td><?= $value['code']; ?></td>
                <td><?= $value['is_used']; ?></td>
                <td><?= $value['user']; ?></td>
                <td><?= $value['date_used']; ?></td>
                <td><?= $value['date_created']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php if ($total_rows == Settings::get('records_per_page')) { ?>
    <div class="paginate">
        <div class="pagination">
            <ul>
                <li class="first">
                    <a href="<?= site_url(ADMIN_URL.'/code/indomaret/index/'.$prev_page.'?'); ?>">&lt;&lt;</a>
                </li>
                <li class="last">
                    <a href="<?= site_url(ADMIN_URL.'/code/indomaret/index/'.$next_page.'?'); ?>">&gt;&gt;</a>
                </li>
            </ul>
        </div>
    </div>
<?php } ?>
