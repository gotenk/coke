<?php echo form_open();?>
<?php echo form_dropdown('dd', $dob_day, (isset($sekarang[2])) ? ( $sekarang[2]) : '' )?>
<?php echo form_dropdown('mm', $dob_month, (isset($sekarang[1])) ? ( $sekarang[1]) : '')?>
<?php echo form_dropdown('yy', $dob_year, (isset($sekarang[0])) ? ( $sekarang[0]) : '')?>
<?php echo form_submit('f_lanjut', 'Submit Post!');?>
<?php echo form_close();?>

<?php pre($error);?>
