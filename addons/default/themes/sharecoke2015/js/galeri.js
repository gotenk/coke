var msnry,sceneAjax,scene = [];
var currentPage = 1;

function setCenter(){
	if($(this.target).hasClass('w')){
    	_w = $(this.target).attr('w')
    	$(this.target).css('margin-left',(_w-$(this.target).width())/2)
	}else{
		_h = $(this.target).attr('h')
    	$(this.target).css('padding-top',(_h-$(this.target).height())/2)
	}
};
function setZero(){
	if($(this.target).hasClass('w')){
    	$(this.target).css('margin-left',0)
	}else{
		$(this.target).css('padding-top',0)
	}
}
function resizeGallery(){
	$('.item-wrapper,#gallery-list .picture img').css({'width':'','height':'','margin-left':'','padding-top':''});
	for(var i in scene){
		controller.removeScene(scene[i][0]);
		controller.removeScene(scene[i][1]);
	}
	scene = []
	controller.update()
	msnry.layout();
	msnry.once( 'layoutComplete', function(){
		$('#gallery-list .picture img').each(function(){
			w = $(this).parents('.item').width();
			h = $(this).parents('.item').height();
			$(this).attr('w',w);
			$(this).attr('h',h);
			//$(this).parents('.item-wrapper').css({'width':w,'height':h});
			if($(this).hasClass('w')){
				_w1 = '0px';
				_w2 = w;
				_h1 = h;
				_h2 = h;
			}else{
				_h1 = '0px';
				_h2 = h;
				_w1 = w;
				_w2 = w;
			}
			_tween1 = TweenMax.fromTo(this, 
				.4,
				{width:_w1,height:_h1}, 
				{width:_w2,height:_h2,onUpdate:setCenter,onComplete:setZero, ease: Power4.easeOut});
			
			_scene1 = new ScrollMagic.Scene({triggerElement: this, triggerHook: "onEnter", offset: 0})
				.setTween(_tween1)
				.addTo(controller);

			_tween2 = TweenMax.fromTo(this, 
				.4,
				{width:_w2,height:_h2}, 
				{width:_w1,height:_h1,onUpdate:setCenter,onComplete:setZero, ease: Power4.easeIn});
			
			_scene2 = new ScrollMagic.Scene({triggerElement: this, triggerHook: "onLeave", offset: _h2/2})
				.setTween(_tween2)
				.addTo(controller);
			scene.push([_scene1,_scene2]);
			controller.update();
		})
	});
}
$(window).resize(
	$.debounce(250,resizeGallery)
);
function init(){
	$('#gallery-list .picture img').each(function(){
		_img = new Image();
		_img.ctx = this;
		_img.onload = function(){
			$(this.ctx).attr('w',$(this.ctx).width());
			$(this.ctx).attr('h',$(this.ctx).height());
			$(this.ctx).parents('.item-wrapper').css('width',$(this.ctx).width());
			$(this.ctx).parents('.item-wrapper').css('height',$(this.ctx).height());
			if(Math.floor(Math.random()*2)>0){
				_w1 = '0px';
				_w2 = $(this.ctx).width();
				_h1 = $(this.ctx).height();
				_h2 = $(this.ctx).height();
				$(this.ctx).addClass('w');
			}else{
				_h1 = '0px';
				_h2 = $(this.ctx).height();
				_w1 = $(this.ctx).width();
				_w2 = $(this.ctx).width();
				$(this.ctx).addClass('h');
				$(this.ctx).css('padding-top',$(this.ctx).height());
			}

			_tween1 = TweenMax.fromTo(this.ctx, 
				.4,
				{width:_w1,height:_h1}, 
				{width:_w2,height:_h2,onUpdate:setCenter,onComplete:setZero, ease: Power4.easeOut});
			
			_scene1 = new ScrollMagic.Scene({triggerElement: this.ctx, triggerHook: "onEnter", offset: 0})
				.setTween(_tween1)
				.addTo(controller);

			_tween2 = TweenMax.fromTo(this.ctx, 
				.4,
				{width:_w2,height:_h2}, 
				{width:_w1,height:_h1,onUpdate:setCenter,onComplete:setZero, ease: Power4.easeIn});
			
			_scene2 = new ScrollMagic.Scene({triggerElement: this.ctx, triggerHook: "onLeave", offset: _h2/2})
				.setTween(_tween2)
				.addTo(controller);
			scene.push([_scene1,_scene2]);
			controller.update();
		}
		_img.src = $(this).attr('src')
	})
	sceneAjax = new ScrollMagic.Scene({triggerElement:'#loader',triggerHook:'onEnter'})
		.addTo(controller)
		.on("enter", function (e) {
			
		if(currentPage + 1 <= totalPages)
		{
			$.post(SITE_URL+'galeri/'+(currentPage+1),$.extend({'search_field':$('input[name="search_field"]').val()},tokens),function(data){
				currentPage++;
				onCompleteAjax($(data));
				if(currentPage + 1 > totalPages)
				{
					$('#loader').hide();
				}
			})
		}
		else {
			$('#loader').hide();
		}
			
			//setTimeout(onCompleteAjax, 1000, 9); // ini contoh saja
		});
}

function onCompleteAjax($data_items){
	tmp = $data_items;
	
	$('#gallery-list').append( tmp )
	msnry.appended( tmp )
	tmp.find('img').each(function(){
    	_img = new Image();
		_img.ctx = this;
		_img.onload = function(){
			$(this.ctx).attr('w',$(this.ctx).width());
			$(this.ctx).attr('h',$(this.ctx).height());
			$(this.ctx).parents('.item-wrapper').css('width',$(this.ctx).width());
			$(this.ctx).parents('.item-wrapper').css('height',$(this.ctx).height());
			if(Math.floor(Math.random()*2)>0){
				_w1 = '0px';
				_w2 = $(this.ctx).width();
				_h1 = $(this.ctx).height();
				_h2 = $(this.ctx).height();
				$(this.ctx).addClass('w');
			}else{
				_h1 = '0px';
				_h2 = $(this.ctx).height();
				_w1 = $(this.ctx).width();
				_w2 = $(this.ctx).width();
				$(this.ctx).addClass('h');
				$(this.ctx).css('padding-top',$(this.ctx).height());
			}

			_tween1 = TweenMax.fromTo(this.ctx, 
				.4,
				{width:_w1,height:_h1}, 
				{width:_w2,height:_h2,onUpdate:setCenter,onComplete:setZero, ease: Power4.easeOut});
			
			_scene1 = new ScrollMagic.Scene({triggerElement: this.ctx, triggerHook: "onEnter", offset: 0})
				.setTween(_tween1)
				.addTo(controller);

			_tween2 = TweenMax.fromTo(this.ctx, 
				.4,
				{width:_w2,height:_h2}, 
				{width:_w1,height:_h1,onUpdate:setCenter,onComplete:setZero, ease: Power4.easeIn});
			
			_scene2 = new ScrollMagic.Scene({triggerElement: this.ctx, triggerHook: "onLeave", offset: _h2/2})
				.setTween(_tween2)
				.addTo(controller);
			scene.push([_scene1,_scene2]);
			controller.update();
		}
		_img.src = $(this).attr('src')
	})

	sceneAjax.update();
}

function resetDataAjax($data_items){
	
	msnry.destroy();
	$('#gallery-list .item').remove();
	if(!$data_items)
	{
		return false;
	}
	$('#gallery-list').append($data_items);	
	msnry = new Masonry($('#gallery-list')[0], {
		columnWidth: ".grid-sizer",
		itemSelector: '.item'
	});
	

    if($('#gallery-list').length>0){
		$('#gallery-list .item .picture img').imagesLoaded( function() {	
			msnry.layout();
			init();
			//console.log('dd');
		});
	}
}
var raw;
$(document).ready(function(){
	var container = document.querySelector('#gallery-list');
	controller = new ScrollMagic.Controller();
	msnry = new Masonry(container, {
		columnWidth: ".grid-sizer",
		itemSelector: '.item'
	});
	raw = $('#gallery-list .item:lt(8)').clone()

    if($('#gallery-list').length>0){
		$('#gallery-list .item .picture img').imagesLoaded( function() {	
			msnry.layout();
			init();
		});
	}
	
	$('#gallery-list').on('click','div.item',function(){
		
		$('#gallery-detail .picture > img').attr('src',$(this).find('.picture > img').attr('src') );
		$('#gallery-detail').find('.text').attr('class',$(this).find('.text').attr('class'));
		$('#gallery-detail').find('.text > label').text($(this).find('.text > label').text());
		$('#gallery-detail').find('.text > span').text($(this).find('.text > span').text());
		$('#gallery-detail').fadeIn('normal');		
		
	});
	
	 $('#autocomplete').autocomplete({
         serviceUrl: SITE_URL+'galeri/autocomplete',
		 type:'post',
		 params : $.extend({},tokens),
		 minChars:0,
		 noCache:true,
		 ajaxSettings:{dataType:'json',success:function(res){
			 				if(res.data_content)
							{
								resetDataAjax($(res.data_content));
							}
							else {
								resetDataAjax(false);
							}
							
							if(res.total_pages)
							{
								totalPages = res.total_pages;
							}
							
							currentPage= 1;
		 				}
						},
		/* onSearchComplete: function (query, suggestions) {
		 },
		 onSearchStart: function (query) {
			 console.log(query);
		 },*/
         /*lookupFilter: function(suggestion, originalQuery, queryLowerCase) {
			 console.log(suggestion);
           var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
            return re.test(suggestion.value);
        },*/
		/*lookup : function(suggest){
			return suggest;
			console.log(suggest);
		},*/
        onSelect: function(suggestion) {
			$data_query  = suggestion.value;
			$new_query = $.extend({},{query:$data_query ,exact:true},tokens);
			$.post(SITE_URL+'galeri/autocomplete',$.extend({query:suggestion.value,exact:true},tokens),function(res){
							if(res.data_content)
							{
								resetDataAjax($(res.data_content));
							}
							else {
								resetDataAjax(false);
							}
							
							if(res.total_pages)
							{
								totalPages = res.total_pages;
							}
							
							currentPage= 1;	
			},'json');
			//$('#search_query').trigger('submit');
        },
        /*onHint: function (hint) {
            $('#autocomplete-ajax-x').val(hint);
        },
        onInvalidateSelection: function() {
            $('#selction-ajax').html('You selected: none');
        }*/
    });
});