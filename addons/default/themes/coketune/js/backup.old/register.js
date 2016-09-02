$(document).ready(function ($) { 
	
	$( window ).resize(function() {
		
		var height_wrapgal =$(document).height() -$("#wrap-registrasi").offset().top;
		$('#wrap-registrasi').css({"height": height_wrapgal});
		$(".nano").nanoScroller();
		
	});
	$(window).load(function(){
		$(window).trigger('resize');
	})
	
	
	  $('.sosmed-icon-reg').on('click',function(){
		 $('.sosmed-icon-reg').each(function(){ $(this).removeClass('sosmed-selected'); });
		 $('input[name="social_media"]').val($(this).attr('data-sosmed'));
		 if($(this).attr('data-sosmed') == 'vine')
		 {
			  $('.vine-wrap').show();
		 }
		 else {
		 	  $('.vine-wrap').hide();
		 }
		 $(this).addClass('sosmed-selected');
	  });
	  
	  $('select.reg').on('change',function(){
		
		  $myData = {'day':$('#day').val(),'month':$('#month').val(),'year':$('#year').val() }
		  $.ajax({'async':false,'url':SITE_URL+'check-parent-email',data:$.extend( $myData,tokens),type:'post',dataType:'json',success:function(resp){
			  if(resp.status == 1)
			 {
				 $('.parent-email-wrap').show();
			 }
			 else {
			 	 $('.parent-email-wrap').hide();
			 }
		  }
	  	});
		 
	  });
	  $('select.reg').trigger('change');
	  //check selected
	  if($('input[name="social_media"]').val())
	  {
		  $('a[data-sosmed="'+$('input[name="social_media"]').val()+'"]').addClass('sosmed-selected');
		  
		  if($('input[name="social_media"]').val()== 'vine')
		 {
			  $('.vine-wrap').show();
		 }
		 else {
		 	  $('.vine-wrap').hide();
		  }
	  }
	  
	  if($('input[name="social_media"]').val() == 'vine')
	  {
		  $('.vine-section').show();
	  }
		//$().dateSelectBoxes($('#birthMonth'),$('#birthDay'),$('#birthYear'));
	  // $().dateSelectBoxes($('#month'),$('#day'),$('#year'),true);
	 // $('select.fancy-select').fancySelect();
	  $('.fancy-select#month').each(function(index,$obj){
	      
	       $().dateSelectBoxes($('.fancy-select#month:eq('+index+')'),$('.fancy-select#day:eq('+index+')'),$('.fancy-select#year:eq('+index+')'),true,false);
        $('.fancy-select.month:eq('+index+')').siblings('.trigger').css('width','90px'); 
        $('.fancy-select.month:eq('+index+')').siblings('.options').css('min-width','90px'); 
        $('.fancy-select.month:eq('+index+')').parents('.fancy-select').css('width','90px'); 
        $('.fancy-select.day:eq('+index+')').parents('.fancy-select').css('width','65px'); 
  
        $('.fancy-select#month:eq('+index+'),.fancy-select#day:eq('+index+'),.fancy-select#year:eq('+index+')').on('change',function(){
           // console.log($('.fancy-select'));
            $('.fancy-select#month:eq('+index+')').trigger('update');
            $('.fancy-select#month:eq('+index+')').trigger('update.fs');
             $('.fancy-select#day:eq('+index+')').trigger('update');
            $('.fancy-select#day:eq('+index+')').trigger('update.fs');
             $('.fancy-select#year:eq('+index+')').trigger('update');
            $('.fancy-select#year:eq('+index+')').trigger('update.fs');
        });
	      
	  });
	  

});