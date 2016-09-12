<div class="one_full">
    <section class="title">
        <h4><?= lang('code:password_title'); ?></h4>
    </section>

    <section class="item">
        <div class="content">
            <?= cmc_form_open('frm_change_password', '', 'id="change-password"'); ?>
                <div class="form_inputs">
                    <ul>
                        <li>
                            <label><?= lang('code:old_password_label'); ?> <span>*</span></label>
                            <div class="input">
                                <?= form_password('old_password'); ?>
                            </div>
                        </li>
                        <li>
                            <label><?= lang('code:new_password_label'); ?> <span>*</span></label>
                            <div class="input">
                                <?= form_password('new_password'); ?>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="buttons align-right padding-top">
                    <?php
                    echo $this->load->view('admin/partials/buttons', array(
                        'buttons' => array('save')
                    ));
                    ?>
                </div>
            <?php form_close(); ?>
        </div>
    </section>
</div>
