(function ($) {
	$(function () {
		$("body").on("change",".default_selected",function(e){
			if($(this).is(':checked'))
			{
				$obj_val = $('select[name="f_group"]').val();
				$.post(SITE_URL + ADMIN_URL+'/article_manager/ajax_update_default',$.extend( {group_id :$obj_val,video_id: $(this).val() },tokens) );
			}
			
		});
		$("body").on("change",'#filter-stage',function(e){
			//window.location.replace('');
			$obj_val = $('select[name="f_group"]').val();
			//window.location.hash = 'something';
			var parents = 'table.table-list';
			var current_color ;
			$(parents +' tbody').sortable({
				handle: 'span.move-handle',
				start: function(event, ui){
		          
		          $(parents).find('thead tr th').each(function($index,$val){
		          		current_color =  $(ui.item).find('td').eq($index).css('background-color');
		          		 $(ui.item).find('td').eq($index).css('background-color','#E1FDDD');
		          	if($(this).attr('width'))
		          	{
						
						$new_width = $(this).attr('width').replace('%','');
						$new_width = $new_width * $(ui.item).width() /100;						
		          		$(ui.item).find('td').eq($index).attr('width',$new_width);
						 
		          	}
		          	
		          })
		         /* $(ui.item).find('td').each(function(){
		          	console.log($(this).attr('width'));
		          });*/
		      },
		      stop : function(event,ui){
		      	$(ui.item).find('td').css('background-color',current_color);
				$(parents).find('thead tr th').each(function($index,$val){
		          	current_color =  $(ui.item).find('td').eq($index).css('background-color');
		          	if($(this).attr('width'))
		          	{		
		          		$(ui.item).find('td').eq($index).attr('width',$(this).attr('width'));
						 
		          	}
		          	
				})
		      },
				update: function() {
					$(parents +' tbody tr').removeClass('even');
					$(parents +' tbody tr:nth-child(even)').addClass('even');
					order = new Array();
					$(parents +' tbody tr').each(function(){
						order.push( this.id );
					});
					order = order.join(',');
					$obj_val = $('select[name="f_group"]').val();
					$status = $('select[name="f_active"]').val();
					$.post(SITE_URL + ADMIN_URL+'/article_manager/ajax_update_order', $.extend( {group_id :$obj_val,status:$status, order: order },tokens) );
				}
			
			});
			
			
		});
		/*$("body").on("keyup", "#youtube_form" ,function(e) {
        	mycms.generate_slug($(this).find('input[name="title"]'), $(this).find('input[name="slug"]'));
        });*/
		
	});
	
})(jQuery);