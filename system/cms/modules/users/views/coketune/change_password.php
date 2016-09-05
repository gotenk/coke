


<?php echo form_error('password');?>
<?php echo form_error('re-password');?>

<?php echo form_open()?>
<?php echo form_password('password', set_value('password'))?>
<?php echo form_password('re-password', set_value('re-password'))?>
<?php echo form_submit('f_ganti', 'Ganti Password')?>
<?php echo form_close()?>