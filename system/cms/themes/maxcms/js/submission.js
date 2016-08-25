/*
 * jQuery throttle / debounce - v1.1 - 3/7/2010
 * http://benalman.com/projects/jquery-throttle-debounce-plugin/
 *
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function(b,c){var $=b.jQuery||b.Cowboy||(b.Cowboy={}),a;$.throttle=a=function(e,f,j,i){var h,d=0;if(typeof f!=="boolean"){i=j;j=f;f=c}function g(){var o=this,m=+new Date()-d,n=arguments;function l(){d=+new Date();j.apply(o,n)}function k(){h=c}if(i&&!h){l()}h&&clearTimeout(h);if(i===c&&m>e){l()}else{if(f!==true){h=setTimeout(i?k:l,i===c?e-m:e)}}}if($.guid){g.guid=j.guid=j.guid||$.guid++}return g};$.debounce=function(d,e,f){return f===c?a(d,e,false):a(d,f,e!==false)}})(this);

//prototype array splice
// check if it is IE and it's version is 8 or older  
	if (document.documentMode && document.documentMode < 9) {  

		// save original function of splice  
		var originalSplice = Array.prototype.splice;  

		// provide a new implementation  
		Array.prototype.splice = function() {  

			// since we can't modify 'arguments' array,   
			// let's create a new one and copy all elements of 'arguments' into it  
			var arr = [],  
				i = 0,  
				max = arguments.length;  

			for (; i < max; i++){  
				arr.push(arguments[i]);  
			}  

			// if this function had only one argument  
			// compute 'deleteCount' and push it into arr  
			if (arr.length==1) {  
				arr.push(this.length - arr[0]);  
			}  

			// invoke original splice() with our new arguments array  
			return originalSplice.apply(this, arr);  
		};  
	}  

var default_settings_article =null;
var default_settings_photo =null;
var totalUpload = [];
var uploader_child = [];
var currIndex = 0;
var uploader_article = null;
var uploader_photo = null;
var isXHR = true;
var parentID = 0;

function resetAfterSubmit()
{
	// $('input,textarea','body').each(function(){
	// 	if($(this).attr('type')=='submit')
	// 	{
	// 		return;
	// 	}
	// 	if($(this).attr('name')=='submission_type')
	// 	{
	// 		return;
	// 	}
	// 	if($(this).attr('name')=='recaptcha_challenge_field')
	// 	{
	// 		return;
	// 	}
	// 	$(this).val('');
	// 	if($(this).attr('name') == 'caption' || $(this).attr('name') == 'description' )
	// 	{
	// 		$(this).code('');
	// 	}
	// });
	currIndex = 0;
	parentID = 0;
	uploader_article._queue = [];
	if(typeof(uploader_article._opts.data.filename)!='undefined')
	{
		uploader_article._opts.data.filename = '';
		delete uploader_article._opts.data.filename;
	}
	uploader_photo._queue = [];
	if(typeof(uploader_article._opts.data.filename)!='undefined')
	{
		uploader_photo._opts.data.filename = '';
		delete uploader_photo._opts.data.filename;
	}
	uploader_child = [];
	totalUpload = [];
	$('.cropFrame').remove();
	$('.edit-btn').hide();
	$('.input.def_form:gt(0)').each(function(){
		$(this).remove();
	});

	$('#image-sample').hide();
	$('.tab-content.article .char-count').text('4500');
	$('.tab-content.photo .input.def_form #upload-photo').hide();
	$('.tab-content.photo .char-count').text('500');
	$('.tab-content.video .char-count').text('500');
	$('.tag-list a').removeClass('active');

	$('.tag-list').each(function(){
		$(this).data('cells',new Array());
	});

}

function checkIsEmpty($tab_class)
{
	$is_empty=true;
	//console.log($('.tab-content.'+$tab_class+'.active'));
	$('.tab-content.'+$tab_class+'.active').find('input,textarea').each(function(){
		if($(this).attr('type')=='submit')
		{
			return;
		}
		if($(this).attr('name')=='submission_type')
		{
			return;
		} 

		if($(this).attr('name')=='recaptcha_challenge_field')
		{
			return;
		}

		if($(this).attr('name') == 'caption' || $(this).attr('name') == 'description' )
		{
			if($.trim($('<div>'+$(this).code()+'</div>').text()).length >0)
			{
				$is_empty = false;
			}
		}
		else
		{
			if($.trim($(this).val()).length > 0 )
			{
				$is_empty =false;
			}
		}
	});

	if(!$is_empty)
	{
		return $is_empty;
	}

	switch($tab_class)
	{
		case 'article':
				if(uploader_article._queue.length>0)
				{
					return false;
				}
				if(typeof(uploader_article._opts.data.filename)!='undefined')
				{
					return false;
				}
				break;
		case 'photo' :
				if(uploader_child.length > 0 )
				{
					for(var kl in uploader_child)
					{
						if(uploader_child[kl]._queue.length > 0)
						{
							return false;
						}
						if(typeof(uploader_child[kl]._opts.data.filename)!='undefined')
						{
							return false;
						}
					}

				}

				if(totalUpload.length > 0 )
				{
					for(var kl in totalUpload)
					{
						if(totalUpload[kl]._queue.length > 0)
						{
							return false;
						}
						if(typeof(totalUpload[kl]._opts.data.filename)!='undefined')
						{
							return false;
						}
					}

				}

				if(typeof(uploader_article._opts.data.filename)!='undefined')
				{
					return false;
				}
				break;

	}


	return true;



}
function resetElement()
{
	// $('input,textarea','body').each(function(){
	// 	if($(this).attr('type')=='submit')
	// 	{
	// 		return;
	// 	}
	// 	if($(this).attr('name')=='submission_type')
	// 	{
	// 		return;
	// 	}
	// 	$(this).val('');
	// });
	$('#image-sample').hide();
	$('#image-sample img').hide();
}
function pushSubmit(){

	currIndex ++;
	if(currIndex < totalUpload.length)
	{	
		totalUpload[currIndex].submit();
	}


}

function reqAjax($callback)
{
	currIndex ++;
	if(currIndex <= totalUpload.length)
	{	
		$.ajax({url:totalUpload[currIndex-1]._opts.url,
			data :totalUpload[currIndex-1]._opts.data,
			type:'POST',
			dataType:'json',
			success: function(response){
				//console.log('response success :'+response);
				$callback(true,response);

			},
			error:function(jqXHR,textstatus,error){
				currIndex --;
				//$callback(jqXHR.responseText);
				var response = jQuery.parseJSON(jqXHR.responseText);
				$callback(false,response);

			}
		});
	}
}

/* Article JS LOAD */

(function($){
	$(function(){
		resetElement();
				// var sizeBox = document.getElementById('sizeBox'), // container for file size info
	 //   progress = document.getElementById('progress'); // the element we're using for a progress bar
	   var crop = {x:0,y:0,w:0,h:0};
	   var process = false;
	   var has_uploaded = 0;
	   default_settings_article =  {
			  button: 'image-sample', // file upload button
			  url: SITE_URL+'submission'+((typeof(IS_CHALLENGE) !='undefined' && IS_CHALLENGE ==true)? '/challenge': ''), // server side handler
			  name: 'uploadphoto', // upload parameter name        
			  autoSubmit :false,
			  // enables cross-browser progress support (more info below)
			  responseType: 'json',
			  allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
			  maxSize: 1024*5, // kilobytes
			  hoverClass: 'ui-state-hover',
			  focusClass: 'ui-state-focus',
			  disabledClass: 'ui-state-disabled',
			  multipart:true,
			  data:$.extend({'entity':'parent'},tokens),
			  debug: true,
			  onSubmit: function(filename, extension) {
			  	 process = true;
			  	 //this.setData($.extend(tokens,$('input[name="uploadphoto[]"]').val()));
				  //this.setFileSizeBox(sizeBox); // designate this element as file size container
				  //this.setProgressBar(progress); // designate as progress bar
				}, 
			   onChange : function( filename, extension, uploadBtn )
			   {

			   	if(this._opts.data.filename)
			   	{
			   		delete this._opts.data.filename;
			   	}
			   	XhrOk = ( 'multiple' in this._input &&
									typeof File !== 'undefined' &&
									typeof ( new XMLHttpRequest() ).upload !== 'undefined' );

			   		if(XhrOk)
			   		{
			   			size = Math.round( this._input.files[0].size / 1024 );
			   			 if ( size &&
							this._opts.maxSize !== false &&
							size > this._opts.maxSize )
						{
						 //$('.notification-exceed-limit').parents('.popup').show();
						 $('#error-popup-submission .text.error-text').html('Maksimum ukuran gambar 5 MB');
						 $('#error-popup-submission').show();
						// alert( filename + ' exceeds ' + this._opts.maxSize + 'K limit');
						  //console.log( filename + ' exceeds ' + this._opts.maxSize + 'K limit' );
							return false;
						}


			   		}

			   		rExt = /.*[.]/;
			   		ext = (-1 !== filename.indexOf('.')) ? filename.replace(rExt, '') : '';
			   		 var allowed = this._opts.allowedExtensions,
					 i = allowed.length,
					 extOk = false;

						// Only file extension if allowedExtensions is set
						if ( i > 0 ) {
						  ext = ext.toLowerCase();

						  while ( i-- ) {
							if ( allowed[i].toLowerCase() == ext ) {
							  extOk = true;
							  break;
							}
						  }

						  if ( !extOk ) {
							  //$('.notification-format-not-valid').parents('.popup').show();
							  $('#error-popup-submission .text.error-text').html('Format gambar tidak valid');
							  $('#error-popup-submission').show();
						  	 //alert( 'File extension not permitted');
							//console.log( 'File extension not permitted' );
						   	return false;
						  }
						}
						if(filename.length>22)
						{
							filename = filename.substring(0,21)+'...';
							//Screen Shot 2014-05-26
						}

			   			$('span.file-wrapper .text').text(filename);

					if(!XhrOk)
					{

						var byThis = this;
						isXHR =false;
						byThis.setData($.extend(byThis._opts.data,{'xhr':'0'}));
						setTimeout(function(){
							//alert('submit');
							$('div.loading').show();
							byThis.submit();
						},250);

					}
					else
					{
						$(uploadBtn).siblings('.edit-btn').show();
		   				var byThis = this;
		   				process = true;	
						reader = new FileReader();
						reader.onload = function (event) 
	  	 				{
	  	 					//$('img.cropimage').attr('src',event.target.result);

	  	 					//$(uploadBtn).find('img').attr({width:1400,height:615,'src':event.target.result});

	  	 					$(uploadBtn).after($('<img>',{'class':'cropimage','cropwidth':$('.input').width(),'cropheight':Math.round(parseFloat(0.437*parseInt($('.input').width()))),'src':event.target.result}));


		  	 					$(uploadBtn).siblings('.cropimage').each( function () {
								var image = $(this),
									cropwidth = image.attr('cropwidth'),
									cropheight = image.attr('cropheight');
								  //console.log(cropheight);

								   image.cropbox( {width: cropwidth, height: cropheight,useMouseWheel :false} )
									.on('cropbox', function( event, results, img ) {
																				//console.log(img);
										byThis._opts.data.x = results.cropX;
										byThis._opts.data.y =results.cropY;
										byThis._opts.data.w = results.cropW;
										byThis._opts.data.h =results.cropH ;
									 	//,crop);
									 	//console.log(byThis._opts.data);
									});



							  } );

							  $(window).on('resize',$.debounce(100,function(){

							  		$(uploadBtn).next().find( '.cropimage' ).each( function () {
							  		  var image = $(this),
							  			  cropwidth = $('.input').width(),
							  			  cropheight = parseFloat(0.437*parseInt($('.input').width()));

							  			// console.log(cropheight);
							  			image.cropbox( {width: cropwidth, height: cropheight,useMouseWheel :false} )
							  			  .on('cropbox', function( event, results, img ) {
							  			  //	console.log('resize',results);
							  			  	byThis._opts.data.x = results.cropX;
							  			  	byThis._opts.data.y =results.cropY;
							  			  	byThis._opts.data.w = results.cropW;
							  			  	byThis._opts.data.h =results.cropH ;

							  			  });

							  			  $(  image).data('cropbox').fit();
							  		} );
							  }));

							  $('div.loading').hide();
							 // $(window).trigger('resize');


						}

							if(byThis._input.files.length == 1)
							{
								$('div.loading').show();
								$(uploadBtn).hide();
								reader.readAsDataURL(byThis._input.files[0]);
							}


					}
			   },        
			  onComplete: function(filename, response,uploadBtn) {
			  	 process = false;
			  	 var byThis = this;
				  if (!response) {
					  //alert(filename + 'upload failed');
					 // $('.notification-upload-failed').parents('.popup').show();
					 $('#error-popup-submission .text.error-text').html('Unggah gagal. Cobalah beberapa saat lagi');
					 $('#error-popup-submission').show();
					 Recaptcha.reload();
					  return false;            
				  }
				   //console.log('complete',response);
				 // alert('file1 : '+filename);
				// alert(JSON.stringify(response))
				 // alert('type :'+ typeof(response.realpath));
				 //  alert('dsad :'+response.realpath)
				  // do something with response...
				  if(typeof(response.realpath) =='string')
			  		{
			  			$('div.loading').hide();
			  			this.setData($.extend(this._opts.data,{'filename':response.filename}));

			  			//console.log(this._opts.data.filename);
			  			//console.log(bla);
			  			  /* Make images fill their parent's space. Solves IE8. */
			  			  /* Add !important if needed. */
			  			  Date.now = Date.now || function() { return +new Date; }; 
			  			  $(uploadBtn).after($('<img>',{'class':'cropimage','width':$('.input').width()+'px','cropwidth':$('.input').width(),'cropheight':Math.round(parseFloat(0.437*parseInt($('.input').width()))),'src':response.realpath+'?v='+(parseInt(Math.random()*1000)+Date.now())}));
			  			$(uploadBtn).hide();
			  			$(uploadBtn).siblings('.edit-btn').show();
			  			//$('img.cropimage').attr('src',response.realpath);
					  	 					//480x320
					  	 					//260x173
					  	 					//0.54166666667
					  	 					//if($(document).width()>1024){
					  	 					//console.log($(uploadBtn).next());

						  	 					$(uploadBtn).siblings('.cropimage').each( function () {

						  	 					  var image = $(this),
						  	 						  cropwidth = image.attr('cropwidth'),
						  	 						  cropheight = image.attr('cropheight');

						  	 						//console.log($(this).width());
						  	 						image.cropbox( {width: cropwidth, height: cropheight,useMouseWheel :false} )
						  	 						  .on('cropbox', function( event, results, img ) {
						  	 						  //	console.log(results);
						  	 						  	byThis._opts.data.x = results.cropX;
						  	 						  	byThis._opts.data.y =results.cropY;
						  	 						  	byThis._opts.data.w = results.cropW;
						  	 						  	byThis._opts.data.h =results.cropH ;
						  	 						  });

						  	 						// console.log( $(this).position().left);
						  	 						// console.log( $(this).position().top);
						  	 					} );

						  	 					$(window).on('resize',$.debounce(100,function(){
						  	 							$(uploadBtn).siblings('.cropimage').each( function () {
						  	 							  var image = $(this),
						  	 								  cropwidth = $('.input').width(),
						  	 								  cropheight = parseFloat(0.437*parseInt($('.input').width()));

						  	 								 // console.log( $(this).position().left);
						  	 								 // console.log( $(this).position().top);
						  	 								 // $(this).css({width:cropwidth});
						  	 								 // $(this).attr('width',cropwidth);
						  	 							  //console.log($(this).width());
						  	 							 // console.log('aa');
						  	 							 // console.log($(window).width());
						  	 							  image.cropbox( {width: cropwidth, height: cropheight,useMouseWheel :false} )
						  	 								  .on('cropbox', function( event, results, img ) {
						  	 								  //	console.log(results);
						  	 								  	byThis._opts.data.x = results.cropX;
						  	 								  	byThis._opts.data.y =results.cropY;
						  	 								  	byThis._opts.data.w = results.cropW;
						  	 								  	byThis._opts.data.h =results.cropH ;
						  	 								  });

						  	 								  $(image).data('cropbox').fit();
						  	 							} );
						  	 					}));

										// }else{
										 	/*$( '.cropimage' ).each( function () {
												var image = $(this),
													cropwidth = image.attr('cropwidth')*0.46,
													cropheight = image.attr('cropheight')*0.46;
												  image.cropbox( {width: cropwidth, height: cropheight, showControls: 'auto'} )
													.on('cropbox', function( event, results, img ) {
														//console.log(results,image.attr('cropwidth')/results.cropW)
														//ratio = results.cropW*1.8461538461538463/results.cropW
														crop.x = results.cropX
														crop.y =results.cropY
														crop.w = results.cropW
														crop.h =results.cropH

													});
											  } );*/
										// }

										 // $('.crop-popup').parent().show();


			  		}
			  		else if(typeof(response.status) != 'undefined' && response.status == true)
			  		{			  			
			  			//$('.popup-email').show();
			  			$('div.loading').hide();
			  			//$('#submission-success').show();
			  			$('.tab-content.article').find('.complete').show();
			  			$('.tab-content.article').addClass('success');
			  			resetAfterSubmit();




			  		}
			  		else
			  		{ 
				  		//$('#error-popup-submission .text.error-text').html('Format gambar tidak valid'));
				  		$('#error-popup-submission').hide();



			  		}

			   },
			   onError : function( filename, errorType, status, statusText, response, uploadBtn ){

				   if(typeof(response.status) != 'undefined' && response.status ==false)
				   {
					   if(typeof(response.action)!= 'undefined')
					   {

						   eval(response.action);
					   }
					   else
					   {

						   	//goto($('.def_form').eq(currIndex),700);
						   	//$('.def_form').eq(currIndex).find('.error-message').html(response.message);
						   	//$('.def_form .error').eq(currIndex).show();
						   	$('#error-popup-submission .text.error-text').html(response.message);
						   	$('#error-popup-submission').show();

						   	$('div.loading').hide();


					   }


				   }
				   Recaptcha.reload();
			   	$('div.loading').hide();
			   	 process = false;
		   		//$('input[name="title"]').val('');
				//$('span.file-wrapper .text').text('');
			   }
		};

		uploader_article = new ss.SimpleUpload(default_settings_article); 

	   $('.tab-content.article').on('click','a.edit-btn',function(){

			uploader_article._queue = [];
			if(typeof(uploader_article._opts.data.filename)!='undefined')
			{
				delete uploader_article._opts.data.filename;
			}
	   		$(this).siblings('img').remove();
	   		if($(this).siblings('.cropFrame').length )
	   		{
	   			$(this).siblings('.cropFrame').remove();	
	   		}

	   		$(this).siblings('#image-sample').show();
	   		$(this).hide();
	   		$(this).siblings('#image-sample').find('img').show();
	   }); 

	 })

})(jQuery);


/* END Article */
/* PHOTO MULTIPLE UPLOAD LOAD */
(function($){
	$(function(){
				// var sizeBox = document.getElementById('sizeBox'), // container for file size info
	 //   progress = document.getElementById('progress'); // the element we're using for a progress bar
	   var crop = {x:0,y:0,w:0,h:0};
	   var process = false;
	   var has_uploaded = 0;
	   default_settings_photo =  {
			  button: $('.tab-content.photo .input.def_form').first().find('#upload-photo'), // file upload button
			  url: SITE_URL+'submission'+((typeof(IS_CHALLENGE) !='undefined' && IS_CHALLENGE ==true)? '/challenge': ''), // server side handler
			  name: 'uploadphoto', // upload parameter name        
			  autoSubmit :false,
			  // enables cross-browser progress support (more info below)
			  responseType: 'json',
			  allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
			  maxSize: 1024*5, // kilobytes
			  hoverClass: 'ui-state-hover',
			  focusClass: 'ui-state-focus',
			  disabledClass: 'ui-state-disabled',
			  multipart:true,
			  data:$.extend({'entity':'parent'},tokens),
			  debug: true,
			  onSubmit: function(filename, extension) {
			  	 process = true;
			  	 //this.setData($.extend(tokens,$('input[name="uploadphoto[]"]').val()));
				  //this.setFileSizeBox(sizeBox); // designate this element as file size container
				  //this.setProgressBar(progress); // designate as progress bar
				}, 
			   onChange : function( filename, extension, uploadBtn )
			   {

			   	if(this._opts.data.filename)
			   	{
			   		delete this._opts.data.filename;
			   	}
			   	XhrOk = ( 'multiple' in this._input &&
									typeof File !== 'undefined' &&
									typeof ( new XMLHttpRequest() ).upload !== 'undefined' );

			   		if(XhrOk)
			   		{
			   			size = Math.round( this._input.files[0].size / 1024 );
			   			 if ( size &&
							this._opts.maxSize !== false &&
							size > this._opts.maxSize )
						{
						 //$('.notification-exceed-limit').parents('.popup').show();
						 $('#error-popup-submission .text.error-text').html('Maksimum ukuran gambar 5 MB');
						 $('#error-popup-submission').show();
						// alert( filename + ' exceeds ' + this._opts.maxSize + 'K limit');
						  //console.log( filename + ' exceeds ' + this._opts.maxSize + 'K limit' );
							return false;
						}


			   		}

			   		rExt = /.*[.]/;
			   		ext = (-1 !== filename.indexOf('.')) ? filename.replace(rExt, '') : '';
			   		 var allowed = this._opts.allowedExtensions,
					 i = allowed.length,
					 extOk = false;

						// Only file extension if allowedExtensions is set
						if ( i > 0 ) {
						  ext = ext.toLowerCase();

						  while ( i-- ) {
							if ( allowed[i].toLowerCase() == ext ) {
							  extOk = true;
							  break;
							}
						  }

						  if ( !extOk ) {
							  $('#error-popup-submission .text.error-text').html('Maksimum ukuran gambar 5 MB');
							  $('#error-popup-submission').show();
							  //$('.notification-format-not-valid').parents('.popup').show();
						  	 //alert( 'File extension not permitted');
							//console.log( 'File extension not permitted' );
						   	return false;
						  }
						}
						if(filename.length>22)
						{
							filename = filename.substring(0,21)+'...';
							//Screen Shot 2014-05-26
						}

			   			$('span.file-wrapper .text').text(filename);

					if(!XhrOk)
					{

						var byThis = this;
						isXHR =false;
						byThis.setData($.extend(byThis._opts.data,{'xhr':'0'}));
						setTimeout(function(){
							//alert('submit');
							$('div.loading').show();
							byThis.submit();
						},250);

					}
					else
					{
						$(uploadBtn).siblings('.edit-btn').show();
		   				var byThis = this;
		   				process = true;	
						reader = new FileReader();
						reader.onload = function (event) 
	  	 				{
	  	 					//$('img.cropimage').attr('src',event.target.result);

	  	 					//$(uploadBtn).find('img').attr({width:1400,height:615,'src':event.target.result});

	  	 					$(uploadBtn).after($('<img>',{'class':'cropimage','cropwidth':$('.def_form').width(),'cropheight':Math.round(parseFloat(0.437*parseInt($('.def_form').width()))),'src':event.target.result}));


	  	 					$(uploadBtn).siblings('.cropimage').each( function () {
	  	 					var image = $(this),
	  	 						cropwidth = image.attr('cropwidth'),
	  	 						cropheight = image.attr('cropheight');
	  	 					  //console.log(cropheight);

	  	 					   image.cropbox( {width: cropwidth, height: cropheight,useMouseWheel :false} )
	  	 						.on('cropbox', function( event, results, img ) {
	  	 							//console.log(results);
	  	 							//console.log(img);
	  	 							byThis._opts.data.x = results.cropX;
	  	 							byThis._opts.data.y =results.cropY;
	  	 							byThis._opts.data.w = results.cropW;
	  	 							byThis._opts.data.h =results.cropH ;
	  	 							//,crop);
	  	 							//console.log(byThis._opts.data);
	  	 						});



	  	 				  } );

	  	 				  $(window).on('resize',$.debounce(100,function(){

	  	 						$(uploadBtn).next().find( '.cropimage' ).each( function () {
	  	 						  var image = $(this),
	  	 							  cropwidth = $('.def_form').width(),
	  	 							  cropheight = parseFloat(0.437*parseInt($('.def_form').width()));

	  	 							// console.log(cropheight);
	  	 							image.cropbox( {width: cropwidth, height: cropheight,useMouseWheel :false} )
	  	 							  .on('cropbox', function( event, results, img ) {
	  	 							  //	console.log('resize',results);
	  	 								byThis._opts.data.x = results.cropX;
	  	 								byThis._opts.data.y =results.cropY;
	  	 								byThis._opts.data.w = results.cropW;
	  	 								byThis._opts.data.h =results.cropH ;

	  	 							  });

	  	 							  $(  image).data('cropbox').fit();
	  	 						} );
	  	 				  }));

							  $('div.loading').hide();
							 // $(window).trigger('resize');


						}

							if(byThis._input.files.length == 1)
							{
								$('div.loading').show();
								$(uploadBtn).hide();
								reader.readAsDataURL(byThis._input.files[0]);
							}


					}
			   },        
			  onComplete: function(filename, response,uploadBtn) {
			  	 process = false;
			  	 var byThis = this;
				  if (!response) {
					  //alert(filename + 'upload failed');
					  $('#error-popup-submission .text.error-text').html('Unggah gagal cobalah beberapa saat lagi');
					  $('#error-popup-submission').show();
					  return false;            
				  }
				   //console.log('complete',response);
				 // alert('file1 : '+filename);
				// alert(JSON.stringify(response))
				 // alert('type :'+ typeof(response.realpath));
				 //  alert('dsad :'+response.realpath)
				  // do something with response...
				  if(typeof(response.realpath) =='string')
			  		{
			  			$('div.loading').hide();
			  			this.setData($.extend(this._opts.data,{'filename':response.filename}));
			  			//console.log(this._opts.data.filename);
			  			//console.log(bla);
			  			  /* Make images fill their parent's space. Solves IE8. */
			  			  /* Add !important if needed. */
			  			  Date.now = Date.now || function() { return +new Date; }; 
			  			  $(uploadBtn).after($('<img>',{'class':'cropimage','width':$('.def_form').width()+'px','cropwidth':$('.def_form').width(),'cropheight':Math.round(parseFloat(0.437*parseInt($('.def_form').width()))),'src':response.realpath+'?v='+(parseInt(Math.random()*1000)+Date.now())}));
			  			$(uploadBtn).hide();
			  			$(uploadBtn).siblings('.edit-btn').show();
			  			//$('img.cropimage').attr('src',response.realpath);
					  	 					//480x320
					  	 					//260x173
					  	 					//0.54166666667
					  	 					//if($(document).width()>1024){
					  	 					//console.log($(uploadBtn).next());
						  	 					$(uploadBtn).siblings( '.cropimage' ).each( function () {
						  	 					  var image = $(this),
						  	 						  cropwidth = image.attr('cropwidth'),
						  	 						  cropheight = image.attr('cropheight');

						  	 						//console.log($(this).width());
						  	 						image.cropbox( {width: cropwidth, height: cropheight,useMouseWheel :false} )
						  	 						  .on('cropbox', function( event, results, img ) {
						  	 						  //	console.log(results);
						  	 						  	byThis._opts.data.x = results.cropX;
						  	 						  	byThis._opts.data.y =results.cropY;
						  	 						  	byThis._opts.data.w = results.cropW;
						  	 						  	byThis._opts.data.h =results.cropH ;
						  	 						  });

						  	 						// console.log( $(this).position().left);
						  	 						// console.log( $(this).position().top);
						  	 					} );

						  	 					$(window).on('resize',$.debounce(100,function(){
						  	 							$(uploadBtn).next().find( '.cropimage' ).each( function () {
						  	 							  var image = $(this),
						  	 								  cropwidth = $('.def_form').width(),
						  	 								  cropheight = parseFloat(0.437*parseInt($('.def_form').width()));

						  	 								 // console.log( $(this).position().left);
						  	 								 // console.log( $(this).position().top);
						  	 								 // $(this).css({width:cropwidth});
						  	 								 // $(this).attr('width',cropwidth);
						  	 							  //console.log($(this).width());
						  	 							 // console.log('aa');
						  	 							 // console.log($(window).width());
						  	 							  image.cropbox( {width: cropwidth, height: cropheight, useMouseWheel :false} )
						  	 								  .on('cropbox', function( event, results, img ) {
						  	 								  //	console.log(results);
						  	 								  	byThis._opts.data.x = results.cropX;
						  	 								  	byThis._opts.data.y =results.cropY;
						  	 								  	byThis._opts.data.w = results.cropW;
						  	 								  	byThis._opts.data.h =results.cropH ;

						  	 								  });

						  	 								  $(image).data('cropbox').fit();
						  	 							} );
						  	 					}));

										// }else{
										 	/*$( '.cropimage' ).each( function () {
												var image = $(this),
													cropwidth = image.attr('cropwidth')*0.46,
													cropheight = image.attr('cropheight')*0.46;
												  image.cropbox( {width: cropwidth, height: cropheight, showControls: 'auto'} )
													.on('cropbox', function( event, results, img ) {
														//console.log(results,image.attr('cropwidth')/results.cropW)
														//ratio = results.cropW*1.8461538461538463/results.cropW
														crop.x = results.cropX
														crop.y =results.cropY
														crop.w = results.cropW
														crop.h =results.cropH

													});
											  } );*/
										// }

										 // $('.crop-popup').parent().show();


			  		}
			  		else if(typeof(response.status) != 'undefined' && response.status == true)
			  		{
			  			//$('div.loading').hide();
			  			$('.errors').hide();

						//update photo to childern
						if(typeof(response.parent_id) != 'undefined')
						{
							for(var $ck =currIndex ; $ck < totalUpload.length;$ck++)
							{
								totalUpload[$ck]._opts.data.parent_id = response.parent_id;
								parentID = response.parent_id;

							}
						}

			  			//serialize submit
			  			pushSubmit();


			  			if (currIndex >= totalUpload.length)
			  			{
			  				//$('.popup-email').show();
			  				$('div.loading').hide();
			  				//$('#submission-success').show();

			  				resetAfterSubmit();
			  				$('.tab-content.photo').find('.complete').show();
			  				$('.tab-content.photo').addClass('success');

			  			}

			  		}
			  		else
			  		{ 
				  		//$('#error-popup-submission .text.error-text').html('Unggah gagal cobalah beberapa saat lagi'));
				  		$('#error-popup-submission').hide();



			  		}

			   },
			   onError : function( filename, errorType, status, statusText, response, uploadBtn ){

				   if(typeof(response.status) != 'undefined' && response.status ==false)
				   {
					   if(typeof(response.action)!= 'undefined')
					   {

						   eval(response.action);
					   }
					   else
					   {

						   	goto($('.def_form').eq(currIndex),700);
						   	$('#error-popup-submission .text.error-text').html(response.message);
						   	$('#error-popup-submission').show();
						   	//$('#error-popup-submission .text.error-text').html(response.message);
						   	//$('#error-popup-submission').show();
						   	//$('.def_form .error').eq(currIndex).show();

						   	$('div.loading').hide();


					   }


				   }
				   Recaptcha.reload();
			   	$('div.loading').hide();
			   	 process = false;
		   		//$('input[name="title"]').val('');
				//$('span.file-wrapper .text').text('');
			   }
		};

		uploader_photo = new ss.SimpleUpload(default_settings_photo); 


	   $('.tab-content.photo').on('click','a.edit-btn',function(){

			currForm = $('.tab-content.photo .def_form').index($(this).parents('.def_form'));
			console.log(currForm);
			if(currForm == 0)
			{
				uploader_photo._queue=[];
				if(typeof(uploader_photo._opts.data.filename)!='undefined')
				{
					delete uploader_photo._opts.data.filename;
				}
			}
			else if(typeof(uploader_child[currForm-1]) !='undefined')
			{
				uploader_child[currForm-1]._queue=[];
				if(typeof(uploader_child[currForm-1]._opts.data.filename)!='undefined')
				{
					delete uploader_child[currForm-1]._opts.data.filename;
				}
			}

			if(totalUpload.length > 0 && typeof(totalUpload[currForm]) !='undefined' )
			{
				totalUpload[currForm]._queue =[]; 
				if(typeof(totalUpload[currForm]._opts.data.filename)!='undefined')
				{
					delete totalUpload[currForm]._opts.data.filename;
				}
			}

	   		$(this).siblings('img').remove();
	   		if($(this).siblings('.cropFrame').length )
	   		{
	   			$(this).siblings('.cropFrame').remove();	
	   		}
	   		$(this).siblings('#upload-photo').show();
	   		//$(this).siblings('#upload-file').show();
	   		//$(this).hide();
	   		//$(this).siblings('#upload-button').find('img').show();
	   		$(this).siblings('#upload-photo').find('img').show();
	   });

	   $('.tab-content.photo').on('click','a.remove-btn',function(){
		   		$current = $('.def_form').index($(this).parents('.input'));
		   		//console.log($current);
		   		if($current>0)
		   		{
			   		uploader_child.splice($current-1,1);
			   		if(currIndex > 0)
			   		{
				   		currIndex--;
			   		}

		   		}
				$(this).parents('.input').remove();
				if($('.tab-content.photo .input.def_form').length < 5)
				{
					$('#addMorePhoto').parent().show();
				}
		});

	   var raw =$('#rawData').html();
	   $('#addMorePhoto').on('click', function(){
			if($('.tab-content.photo .input.def_form').length < 5){ //max 5 form

				$('.tab-content.photo .input.def_form').last().after($('<div>',{'class':'input def_form'}).append($(raw)));
				default_settings_photo.button = $('.tab-content.photo .input.def_form').last().find('#upload-photo');
				new_data_clone ={};
				 $.extend(true,new_data_clone,default_settings_photo);
				 $.extend(new_data_clone,default_settings_photo.data,{'entity':'child','order_id':$('.tab-content.photo .input.def_form').length});
				if(parentID != 0)
				{
					new_data_clone.data.parent_id = parentID;

				}
				new_data_clone.data.entity='child';
				new_data_clone.data.order_id=$('.tab-content.photo .input.def_form').length;
				//console.log(new_data_clone);
				uploader_child.push(new ss.SimpleUpload(new_data_clone)); 
				$('.tab-content.photo .input.def_form').last().find('.input-textarea').summernote(default_summernote_setting_short);

				if($('.tab-content.photo .input.def_form').length == 5)
				{
					$(this).parent().hide();
				}
			}
			else {
				$(this).parent().hide();
			}
		})

	 })//on ready
})(jQuery);

/***END PHOTO UPLOAD LOAD ***/

//All Submit Function
(function($){
	$(function(){

		$('input#post_article.submit').on('click',function(e){
			e.preventDefault();
			alert("test");
			return false;
			//check action
			//ARTICLE
			if( $(this).parents('.tab-content').hasClass('article'))
			{
				var $data_form = {};

				if(typeof(_gaq)!= 'undefined')
				{
					_gaq.push(['_trackEvent', 'Button', 'Submit Article', 'Click']);
				}

				$('div.loading').show();
				//uploader.setData($.extend(uploader._opts.data,crop));
				//$form_error = '';
				$data_form = {};



				if(uploader_article._queue.length>1)
				{
					uploader_article._queue.splice(0,uploader_article._queue.length-1);


					//uploader.setData($.extend(uploader._opts.data,$data_form));
				}
				
				$data_form = {'recaptcha_challenge_field':$(this).parents('.tab-content').find('input[name="recaptcha_challenge_field"]').val(),'recaptcha_response_field':$(this).parents('.tab-content').find('input[name="recaptcha_response_field"]').val(),'submission_type':$(this).parents('.tab-content').find('input[name="submission_type"]:checked').val(),'action':'article','tag':$(this).parents('.tab-content').find('input[name="id_tag"]').val(),'title':$(this).parents('.tab-content').find('input[name="title"]').val(),'description':$(this).parents('.tab-content').find('textarea[name="description"]').code()};
				uploader_article._opts.data = $.extend(uploader_article._opts.data,$data_form);
				//console.log($data_form);	
			//console.log('isXHR',!isXHR);
				if(!isXHR)
				{	
					if(typeof(uploader_article._opts.data.filename) == 'undefined'||(typeof(uploader_article._opts.data.filename) != 'undefined' && uploader_article._opts.data.filename == ''))
					{
						//goto($('.def_form').eq(0),700);
						$('.def_form').eq(0).find('.error-message').html('Lengkapi Semua Field');
						$('.def_form .error').eq(0).show();

						$('div.loading').hide();
						return false;

					}

					process = true;
					$.ajax({url:uploader_article._opts.url,
							data :uploader_article._opts.data,
							type:'POST',
							dataType:'json',
							success: function(response){
								//console.log('response success :'+response);
								if(typeof(response.status) != 'undefined' && response.status == true)
								{
			console.log('success');
									$('div.loading').hide();
									$('.errors').hide();
									//show message success
									$('.tab-content.article').find('.complete').show();
									$('.tab-content.article').addClass('success');
									resetAfterSubmit();

								}
								else {
									//console.log('error');
									//error state
									 $('div.loading').hide();
								}

							},
							error:function(jqXHR,textstatus,error){
								//console.log('error response',jqXHR.responseText);
								var response = jQuery.parseJSON(jqXHR.responseText);
								if(typeof(response.status) != 'undefined' && response.status ==false)
								{
									if(typeof(response.action)!= 'undefined')
									{
										//show message error
										//$('#submission-court').hide();
										eval(response.action);
									}
									else
									{
										$('#error-popup-submission .text.error-text').html(response.message);
										$('#error-popup-submission').show();	

									}


								}
								Recaptcha.reload();
								$('div.loading').hide();
								 process = false;

							}
							 });
					/*$.post(uploader._opts.url,uploader._opts.data,function(response){
						 process = false;
						 $('div.loading').hide();

						$('.crop-popup').parents('.popup').hide();
						$('.notification-popup-submit').parents('.popup').show();
						//window.location.reload();
					});*/
				}
				else
				{

					if(uploader_article._queue.length < 1)
					{
						//goto($('.def_form').eq(0),700);
						$('#error-popup-submission .text.error-text').html('Lengkapi Semua Field');
						$('#error-popup-submission').show();	

						$('div.loading').hide();
						return false;

					}

					uploader_article.submit();


				}
				//console.log(uploader._opts.data);


			}
			else if($(this).parents('.tab-content').hasClass('photo'))
			{
				var $data_form_photo = {};

					if(typeof(_gaq)!= 'undefined')
					{
						_gaq.push(['_trackEvent', 'Button', 'Submit Photo', 'Click']);
					}

					$('div.loading').show();
					//uploader.setData($.extend(uploader._opts.data,crop));
					//$form_error = '';
					$data_form = {};	



					if(uploader_photo._queue.length>1)
					{
						uploader_photo._queue.splice(0,uploader._queue.length-1);


						//uploader.setData($.extend(uploader._opts.data,$data_form));
					}
				//console.log($(this).parents('.tab-content').find('input[name="title"]').val());
					$data_form_photo = {'recaptcha_challenge_field':$(this).parents('.tab-content').find('input[name="recaptcha_challenge_field"]').val(),'recaptcha_response_field':$(this).parents('.tab-content').find('input[name="recaptcha_response_field"]').val(),'submission_type':$(this).parents('.tab-content').find('input[name="submission_type"]:checked').val(),'action':'photo','tag':$(this).parents('.tab-content').find('input[name="id_tag"]').val(),'title':$(this).parents('.tab-content').find('input[name="title"]').val(),'caption':$(this).parents('.tab-content').find('textarea[name="caption"]').eq(0).code(),'entity':'parent'};
					uploader_photo._opts.data = $.extend(uploader_photo._opts.data,$data_form_photo);
					//console.log($data_form);	

					if(uploader_child.length)
					{
						for (var i in uploader_child)
						{
							if(uploader_child[parseInt(i)]._queue.length>1)
							{

								uploader_child[parseInt(i)]._queue.splice(0,uploader_child[parseInt(i)]._queue.length-1);
								//uploader_child[i].setData($.extend(uploader_child[i]._opts.data,$data_form));
							}

							$data_form_photo = {};

							$data_form_photo = {'submission_type':$(this).parents('.tab-content').find('input[name="submission_type"]:checked').val(),'action':'photo','tag':$(this).parents('.tab-content').find('input[name="id_tag"]').val(),'caption':$(this).parents('.tab-content').find('textarea[name="caption"]').eq(parseInt(i)+1).code(),'entity':'child'};

							uploader_child[parseInt(i)]._opts.data = $.extend(uploader_child[parseInt(i)]._opts.data,$data_form_photo);
							// /console.log(uploader_child[i]._opts.data);

						}
					}




					if(!isXHR)
					{
						//image validation
						totalUpload[0] = uploader_photo;

						if(typeof(totalUpload[0]._opts.data.filename) =='undefined' || (typeof(totalUpload[0]._opts.data.filename) != 'undefined' && totalUpload[0]._opts.data.filename =='') )
						 {

							goto($('.def_form').eq(0),700);
							//$('.def_form').eq(0).find('.error-message').html('File Picture Required');
							//$('.def_form .error').eq(0).show();
							$('#error-popup-submission .text.error-text').html('Lengkapi Semua Field');
							$('#error-popup-submission').show();

							$('div.loading').hide();
							return false;

						 }
						//console.log('dsd');
						if(uploader_child.length)
						{

							for (  i=0 ; i< uploader_child.length;i++)
							{
								if(currIndex != 0 && currIndex > (i+1))
								{
									continue;
								}

								//console.log(totalUpload[parseInt(i)+parseInt(totalUpload.length)]._queue);
								if(typeof(uploader_child[i]._opts.data.filename) =='undefined' || (typeof(uploader_child[i]._opts.data.filename) != 'undefined' && uploader_child[i]._opts.data.filename ==''))
								 {

									goto($('.def_form').eq(parseInt(i)+1),200);
									//$('.def_form').eq(parseInt(i)+1).find('.error-message').html('File Picture Required');
									//$('.def_form .error').eq(parseInt(i)+1).show();
									$('#error-popup-submission .text.error-text').html('Lengkapi Semua Field');
									$('#error-popup-submission').show();

									$('div.loading').hide();
									//remove last
									//totalUpload.splice(parseInt(totalUpload.length-1),1);
									//console.log(totalUpload.length);
									return false;

								 }

								 totalUpload[i+1] =  uploader_child[i];

							}
						}
						process = true;
						var loopFunction = function(status,response) 
						{	
							if(status == true)
							{
									if(typeof(response.status) != 'undefined' && response.status == true)
									{
										if(typeof(response.parent_id) != 'undefined')
										{
											for (var k=currIndex; k<totalUpload.length; k++)
											{

												if (typeof(totalUpload[k]) !='undefined')
												{
													//set child as parent image
													totalUpload[k]._opts.data.parent_id =response.parent_id; 
												}

											}
										}

										if(currIndex >=totalUpload.length)
										{
											$('div.loading').hide();
											$('.errors').hide();

											//$('#success-submit').show();
											resetAfterSubmit();
											$('.tab-content.photo').find('.complete').show();
											$('.tab-content.photo').addClass('success');

										}
										else {
											reqAjax(loopFunction);
										}

									}
							}
							else
							{
								if(typeof(response.status) != 'undefined' && response.status ==false)
								{
									if(typeof(response.action)!= 'undefined')
									{

										eval(response.action);
									}
									else
									{
										//show message error;
										//$('.errors','#submission-court').html(response.message);
										$('#error-popup-submission .text.error-text').html(response.message);
										$('#error-popup-submission').show();

									}
								}


							}
							Recaptcha.reload();
							$('div.loading').hide();
							 process = false;


						}
							reqAjax(loopFunction);


					}
					else
					{							
						//image validation
						totalUpload[0] = uploader_photo;

						if(totalUpload[0]._queue.length < 1 && currIndex == 0)
						 {

							goto($('.def_form').eq(0),700);
							//$('.def_form').eq(0).find('.error-message').html('File Picture Required');
							//$('.def_form .error').eq(0).show();
							$('#error-popup-submission .text.error-text').html('Lengkapi Semua Field');
							$('#error-popup-submission').show();

							$('div.loading').hide();
							return false;

						 }
						//console.log('dsd');
						if(uploader_child.length)
						{

							for (  i=0 ; i< uploader_child.length;i++)
							{
								if(currIndex != 0 && currIndex > (i+1))
								{
									continue;
								}
								console.log(currIndex);
							   console.log(i+1);
								//console.log(totalUpload[parseInt(i)+parseInt(totalUpload.length)]._queue);
								if(uploader_child[i]._queue.length < 1)
								 {

									goto($('.def_form').eq(parseInt(i)+1),200);
									//$('.def_form').eq(parseInt(i)+1).find('.error-message').html('File Picture Required');
									//$('.def_form .error').eq(parseInt(i)+1).show();
									$('#error-popup-submission .text.error-text').html('Lengkapi Semua Field');
									$('#error-popup-submission').show();

									$('div.loading').hide();
									//remove last
									//totalUpload.splice(parseInt(totalUpload.length-1),1);
									//console.log(totalUpload.length);
									return false;

								 }

								 totalUpload[i+1] =  uploader_child[i];

							}
						}			

						if(currIndex >0)
						{
							$('div.loading').show();
							totalUpload[currIndex].submit();
						}
						else {
							$('div.loading').show();
							totalUpload[0].submit();
						}

					}
					//console.log(uploader._opts.data);


			}
			else if ($(this).parents('.tab-content').hasClass('video'))
			{
				var okThis = $(this);
				$('div.loading').show();
				$.ajax({url:uploader_article._opts.url,
					data :$.extend(tokens,{'recaptcha_challenge_field':$(this).parents('.tab-content').find('input[name="recaptcha_challenge_field"]').val(),'recaptcha_response_field':$(this).parents('.tab-content').find('input[name="recaptcha_response_field"]').val(),'submission_type':$(this).parents('.tab-content').find('input[name="submission_type"]:checked').val(),'tag':$(this).parents('.tab-content').find('input[name="id_tag"]').val(),'action':'video','title':$(this).parents('.tab-content').find('input[name="title"]').val(),'url_video': $(this).parents('.tab-content').find('input[name="url_video"]').val(),'description':$(this).parents('.tab-content').find('textarea[name="description"]').code()}),
					type:'POST',
					dataType:'json',
					success: function(response){
						//console.log('response success :'+response);
						if(typeof(response.status) != 'undefined' && response.status == true)
						{

							$('div.loading').hide();
							$('.errors').hide();
							$(okThis).parents('.tab-content').find('.complete').show();
							$(okThis).parents('.tab-content').addClass('success');
							//$('#success-submit').show();							
							resetAfterSubmit();

						}
						else {
							 $('div.loading').hide();
						}

					},
					error:function(jqXHR,textstatus,error){
						var response = JSON.parse(jqXHR.responseText);

						if(typeof(response.status) != 'undefined' && response.status ==false)
						{
							if(typeof(response.action)!= 'undefined')
							{
								//$('#submission-court').hide();
								eval(response.action);
							}
							else
							{

								$('#error-popup-submission .text.error-text').html(response.message);
								$('#error-popup-submission').show();
								// $('.errors').show();
								// $('.scroll-form').mCustomScrollbar("scrollTo","top");
							}


						}
						Recaptcha.reload();
						$('div.loading').hide();
						 process = false;

					}
					 });
			}
		});

		//next tab
		var currTab = $.trim($('.form-tab-title div.active').attr('class').replace('active',''));
		var nextTab =null;
		//console.log(currTab);
		$('.form-tab-title div').on('click',function(e){
			$check_class = $.trim($(this).attr('class').replace('active'));
			//console.log(checkIsEmpty(currTab));
			if($check_class != currTab && !checkIsEmpty(currTab))
			{
				e.preventDefault();
				e.stopPropagation();
				e.stopImmediatePropagation();
				$('#tab-change').show();
				nextTab = $check_class;
				return false;
			}
			else
			{

				if(typeof(Recaptcha)!='undefined')
				{

					Recaptcha.reload();
				}

				currTab = $check_class;
			}
		});

		$('#next-tab').on('click',function(e){
			resetAfterSubmit();
			currTab = nextTab;
			$('.form-tab-title div.'+currTab).trigger('click');
			Recaptcha.reload();
			$('#tab-change').hide();

		});

	});//on ready

})(jQuery);

