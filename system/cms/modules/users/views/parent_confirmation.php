<style>
    .konfirmasi{
        position:relative;
        margin:170px auto;
        width:524px;
    }
    .konfirmasi h2{
        font:19px 'gotham-book', Arial, Helvetica, sans-serif;
    }
    .buton{
        position:relative;
        margin:0 auto;
        display:block;
        width:100%;
        height:70px;
    }
    .setuju, .tidak{
        border: 1px solid #fff;
        width: 100px;
        position: relative;
        margin:10px auto;
        text-align: center;
        padding:10px;
        cursor:pointer;
        background-color:#ec1900;
        color:#fff;
        font:14px 'gotham-book', Arial, Helvetica, sans-serif;

    }
    .setuju{
        position:absolute;
        left:22%;
    }
    .tidak{
        position:absolute;
        left:52%;
    }
    .buton a{
        text-decoration: none;
        color:#fff;
        font: 16px/16px 'gotham-light', Arial, Helvetica, sans-serif;
    }

</style>
 <?php echo form_open(site_url(array(uri_string(),$code)),array('id'=>'email-confirmation-form')); ?>
 <?php if(validation_errors()):?>
	 <div class="error"><?php echo validation_errors(); ?></div>
<?php endif; ?>
<div class="konfirmasi">
	    <h2>KONFIRMASI UNTUK MEMBATALKAN PENDAFTARAN</h2>
	    <div class="buton">
	        <div class="setuju answer-confirmation"  data-answer="yes" >Setuju</div>
	        <div class="tidak answer-confirmation"   data-answer="no" >Tidak</div>
	    </div>
</div>
<?php echo form_hidden('answer',''); ?>
<?php echo form_close(); ?>


<script type="text/javascript">
$(document).ready(function(){
	$('#content-wrapper').backstretch("{{ theme:image_url file="bg_gabung.png" }}");
});
</script>