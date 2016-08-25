var msnry;
$(document).ready(function(){
	var container = document.querySelector('#gallery-list');
	  msnry = new Masonry(container, {
	columnWidth: ".grid-sizer",
	itemSelector: '.item'
});
    if($('#gallery-list').length>0){
		$('#gallery-list').imagesLoaded( function() {
			msnry.layout();
		});
	}
})