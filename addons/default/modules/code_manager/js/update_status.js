$(document).ready(function(){
	$('a.button.edit').live('click',function(e){
		var $obj = $(this);
		e.preventDefault();
		if($($obj).data('is_clicked')=='1') return false; 

		$($obj).data('is_clicked','1');
		$.ajax({ url: $(this).attr('href'),
				type: 'POST',
				data :{update:'1'},
				 beforeSend : function(){
				 	
				 },
				 dataType:'json',
				 success : function(res){
				 	if(res.status)
				 	{
				 		$($obj).parent().prev().html(res.str);
				 	}
				 	$($obj).data('is_clicked','0');
				 },
				 error : function()
				 {
				 		$($obj).data('is_clicked','0');
				 }		
		
		});		
	});
});
