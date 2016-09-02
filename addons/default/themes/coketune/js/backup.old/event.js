var isClick = false;
$(document).ready(function(){
	$('a.more').click(function(){
		if(isClick) return;
		isClick =true;
		if(currentPage + 1 <= totalPages)
		{
			$.post(SITE_URL+'galeri/'+(currentPage+1),tokens,function(data){
				currentPage++;
				$('.article-list > ul').append(data);
				isClick= false;
			})
		}
		else {
			$('div.more').hide();
			isClick= false;
		}
		
	})
});