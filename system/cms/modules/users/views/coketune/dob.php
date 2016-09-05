<?php echo form_open();?>
<?php echo form_dropdown('dd', $dob_day)?>
<?php echo form_dropdown('mm', $dob_month)?>
<?php echo form_dropdown('yy', $dob_year)?>
<?php echo form_submit('f_lanjut', 'Submit Post!');?>
<?php echo form_close();?>

<?php pre($error);?>