$(document).ready(function ($) { 
	
	$('.answer-confirmed').on('click',function(){
		
		
	});
	$('.answer-confirmation').on('click',function(){
		
		$data = $(this).attr('data-answer');
		$('input[name="answer"]').val($data);
		if($data == 'yes')
		{
			$('#hapus-account-form').fadeIn();
		}
		else {
			
			$('#ok-account-form').fadeIn();
		}
		
		
	});
	
	$('.button-confirm-parent').on('click',function(){
		$('#email-confirmation-form').trigger('submit');
	});
	  

});