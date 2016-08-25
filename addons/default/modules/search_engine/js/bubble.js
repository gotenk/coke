(function($){
	$(function(){
		
		// var sizeBox = document.getElementById('sizeBox'), // container for file size info
    	// progress = document.getElementById('progress'); // the element we're using for a progress bar
       var crop = {x:0,y:0,w:0,h:0};
       var process = false;
       var has_uploaded = 0;
       var default_settings =  {
              button: 'upload-file', // file upload button
              url: SITE_URL+'bubble/posting_cerita', // server side handler
              name: 'uploadphoto', // upload parameter name        
              autoSubmit :false,
             // progressUrl: SITE_URL+'ujian/bahasa', // enables cross-browser progress support (more info below)
              responseType: 'json',
              allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
              maxSize: 512, // kilobytes
              hoverClass: 'ui-state-hover',
              focusClass: 'ui-state-focus',
              disabledClass: 'ui-state-disabled',
              multipart:true,
              data:tokens,
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
               			if ( size && this._opts.maxSize !== false && size > this._opts.maxSize ) {
					    	//$('.notification-exceed-limit').parents('.popup').show();
					    	//alert('Gambar kegedean');
					    	$('#error-text-popup').html('Ukuran file terlalu besar');
					    	$('#gagal').show();
					    	return false;
					    }
					    
					   
               		}
               		
               		//tambah flow
				    //if($.trim($('input[name="title"]').val()) == '') {
				    if($.trim($('#cerita').val()) == '') {
			        	//$('.notification-title-required').parents('.popup').show();
			        	//alert('cerita masih kosong')
			        	$('#error-text-popup').html('Cerita masih kosong.');
					    $('#gagal').show();
			        	return false;
			        }
			        if($.trim($('#recaptcha_response_field').val()) == '') {
			        	//$('.notification-title-required').parents('.popup').show();
			        	//alert('captha masih kosong')
			        	//return false;
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
					      	alert( 'File extension not permitted');
					        //console.log( 'File extension not permitted' );
					       	return false;
					      }
					    }
               			//$('div.text').text(filename);
               			
					if(!XhrOk)
					{
						if (IS_LOGGED_IN)
               			{
							alert('not XhrOk');							
							var byThis = this;
							process = true;
							$.ajax({url: SITE_URL+'bubble/bahasa/check-upload_!Xhr',
			               				data : tokens,
										dataType: 'json',
										type:'GET',
										beforeSend : function(){
											
										},
										success : function(resp12){
											process =false;
											if(resp12.status == 'not_yet')
											{
												byThis.setData($.extend({'xhr':'0'},byThis._opts.data));
												setTimeout(function(){
													//alert('submit');
													$('div.loading').show();
													byThis.submit();
												},250);
											}
											else
											{
												has_uploaded = 1;
												$('.notification-only-one').parents('.popup').show();
												 //alert('upload photo hanya sekali saja');
											}
										}	
									});
						}
						else
						{
							var byThis = this;
							this.setData($.extend({'xhr':'0'},this._opts.data));
							/*
							fbForceLogin(function(resp){
								
								$.ajax({url: SITE_URL+'fb-login',
									data : tokens,
									dataType: 'json',
									type:'POST',
									beforeSend : function(){
										
									},
									success : function(response){
										if(response.url)
										{
												process= true;
												$.ajax({url: SITE_URL+'ujian/bahasa/check-upload_lolo',
							               				data : tokens,
														dataType: 'json',
														type:'GET',
														beforeSend : function(){
															
														},
														success : function(resp12){
															process= false;
															if(resp12.status == 'not_yet')
															{
																byThis.setData($.extend({'xhr':'0'},byThis._opts.data));
																setTimeout(function(){
																	//alert('submit');
																	$('div.loading').show();
																	byThis.submit();
																},250);
															}
															else
															{
																has_uploaded = 1;
																$('.notification-only-one').parents('.popup').show();
																 //alert('upload photo hanya sekali saja');
															}
														}
												});
										}
									},
									error : function(){
										
									}
							
								});
								
							});
							*/
						}
					}
					else
					{
						if (IS_LOGGED_IN)
               			{
               				var byThis = this;
               				process = true;
               				
               				reader = new FileReader();
               				reader.onload = function (event) 
               				{
			 					$('img.cropimage').attr('src',event.target.result);
			  	 					//480x320
			  	 					//260x173
			  	 					//0.54166666667
			  	 				 if($(document).width()>1024){
				  	 					$( '.cropimage' ).each( function () {
								        var image = $(this),
								            cropwidth = image.attr('cropwidth'),
								            cropheight = image.attr('cropheight');
								        
										
								          image.cropbox( {width: cropwidth, height: cropheight, showControls: 'auto'} )
								            .on('cropbox', function( event, results, img ) {
								            	//console.log(results);
								            	crop.x = results.cropX;
								            	crop.y =results.cropY;
								            	crop.w = results.cropW;
								            	crop.h =results.cropH ;
								             
								            });
								      } );
							     }else{
							     	$( '.cropimage' ).each( function () {
								        var image = $(this),
								            cropwidth = image.attr('cropwidth')*0.54166666667,
								            cropheight = image.attr('cropheight')*0.54166666667;
								          image.cropbox( {width: cropwidth, height: cropheight, showControls: 'auto'} )
								            .on('cropbox', function( event, results, img ) {
								            	//console.log(results,image.attr('cropwidth')/results.cropW)
								            	ratio = results.cropW*1.8461538461538463/results.cropW
								            	crop.x = results.cropX
								            	crop.y =results.cropY
								            	crop.w = results.cropW
								            	crop.h =results.cropH
								             
								            });
								      } );
							     }
               									      
               					 $('.crop-popup').parent().show();               										
               				}
               				
       						if(byThis._input.files.length == 1)
       						{
       							reader.readAsDataURL(byThis._input.files[0]);
       						}
       						else
       						{
       							if(byThis._queue.length>1)
       							{
       								byThis._queue.splice(0,byThis._queue.length-1);
       							}	
       							
       							reader.readAsDataURL(byThis._queue[byThis._queue.length-1].file);
       						}
               					
               				
						}
						else
						{
						   /*
							var byThis = this;
							fbForceLogin(function(resp){
								
								$.ajax({url: SITE_URL+'fb-login',
									data : tokens,
									dataType: 'json',
									type:'POST',
									beforeSend : function(){
										
									},
									success : function(response){
										if(response.url)
										{
											IS_LOGGED_IN = 1;
											process = true;
											$.ajax({url: SITE_URL+'bubble',
				               				data : tokens,
											dataType: 'json',
											type:'GET',
											beforeSend : function(){
												
											},
											success : function(resp12){
												process = false;
													if(resp12.status == 'not_yet')
													{
														reader = new FileReader();
													  	reader.onload = function (event) 
													  	 				{
													  	 					$('img.cropimage').attr('src',event.target.result);
													  	 					//480x320
													  	 					//260x173
													  	 					//0.54166666667
													  	 					if($(document).width()>1024){
														  	 					$( '.cropimage' ).each( function () {
																		        var image = $(this),
																		            cropwidth = image.attr('cropwidth'),
																		            cropheight = image.attr('cropheight');
																		        
																				
																		          image.cropbox( {width: cropwidth, height: cropheight, showControls: 'auto'} )
																		            .on('cropbox', function( event, results, img ) {
																		            	//console.log(results);
																		            	crop.x = results.cropX;
																		            	crop.y =results.cropY;
																		            	crop.w = results.cropW;
																		            	crop.h =results.cropH ;
																		             
																		            });
																		      } );
																	     }else{
																	     	$( '.cropimage' ).each( function () {
																		        var image = $(this),
																		            cropwidth = image.attr('cropwidth')*0.54166666667,
																		            cropheight = image.attr('cropheight')*0.54166666667;
																		          image.cropbox( {width: cropwidth, height: cropheight, showControls: 'auto'} )
																		            .on('cropbox', function( event, results, img ) {
																		            	//console.log(results,image.attr('cropwidth')/results.cropW)
																		            	ratio = results.cropW*1.8461538461538463/results.cropW
																		            	crop.x = results.cropX
																		            	crop.y =results.cropY
																		            	crop.w = results.cropW
																		            	crop.h =results.cropH
																		             
																		            });
																		      } );
																	     }
																	      
																	      $('.crop-popup').parent().show();
																		
																		}
														if(byThis._input.files.length == 1)
														{
															reader.readAsDataURL(byThis._input.files[0]);
														}
														else
														{
															if(byThis._queue.length>1)
															{
																byThis._queue.splice(0,byThis._queue.length-1);
															}
															reader.readAsDataURL(byThis._queue[0].file);
														}
													 }
													 else
													 {
													 	has_uploaded = 1;
													 	$('.notification-only-one').parents('.popup').show();
													 	// alert('upload photo hanya sekali saja');
													 }
													
													}
												});				
											
										}
									},
									error : function(){
										
									}
							
								});
								
							});
							*/	

						}
						
					}
	           },        
	          onComplete: function(filename, response) {
	          	 process = false;
	              if (!response) {
	                  alert(filename + 'upload failed');
	                  //$('.notification-upload-failed').parents('.popup').show();
	                  return false;            
	              }
	             // alert('file1 : '+filename);
	            // alert(JSON.stringify(response))
	             // alert('type :'+ typeof(response.realpath));
	             //  alert('dsad :'+response.realpath)
	              // do something with response...
	              if(typeof(response.z) =='string')
	          		{
	          			$('div.loading').hide();
	          			this.setData($.extend(this._opts.data,{'filename':response.filename}));
	          			$('img.cropimage').attr('src',response.realpath);
					  	 					//480x320
					  	 					//260x173
					  	 					//0.54166666667
					  	 					if($(document).width()>1024){
						  	 					$( '.cropimage' ).each( function () {
										        var image = $(this),
										            cropwidth = image.attr('cropwidth'),
										            cropheight = image.attr('cropheight');
										        
										          image.cropbox( {width: cropwidth, height: cropheight, showControls: 'auto'} )
										            .on('cropbox', function( event, results, img ) {
										            	//console.log(results);
										            	crop.x = results.cropX;
										            	crop.y =results.cropY;
										            	crop.w = results.cropW;
										            	crop.h =results.cropH ;
										             
										            });
										      } );
									     }else{
									     	$( '.cropimage' ).each( function () {
										        var image = $(this),
										            cropwidth = image.attr('cropwidth')*0.54166666667,
										            cropheight = image.attr('cropheight')*0.54166666667;
										          image.cropbox( {width: cropwidth, height: cropheight, showControls: 'auto'} )
										            .on('cropbox', function( event, results, img ) {
										            	//console.log(results,image.attr('cropwidth')/results.cropW)
										            	ratio = results.cropW*1.8461538461538463/results.cropW
										            	crop.x = results.cropX
										            	crop.y =results.cropY
										            	crop.w = results.cropW
										            	crop.h =results.cropH
										             
										            });
										      } );
									     }
									      
									      $('.crop-popup').parent().show();
						
						      
	          		}
	          		else if(typeof(response.success) != 'undefined')
	          		{
	          			if(typeof(response.slug) != 'undefined' && response.slug =='only_one')
	          			{
	          				$('.notification-only-one').parents('.popup').show();
	          			}
	          			else
	          			{
	          				alert(response.msg);
	          			}
	          			
	          		}
	          		else
	          		{
	          			$('div.loading').hide();
	          			//$('#cerita').val('');
        				//$('.text').text('');
        				//window.location.reload();
        				//console.log(response);
        				$('.crop-popup').parent().hide();
	       				$('#photo-preview').attr('src', response.realpath);
	       				$('#photo-cerita').val(response.photo);
        				$('#thumb-photo-cerita').val(response.thumb);
        				//$('#div-preview').show();
        				//$(response.popup).show();
        				close_sukses_posting();
        				$('.notification-popup-submit').parents('.popup').show();
	          		}
	           },
	           onError : function( filename, errorType, status, statusText, response, uploadBtn ){
	           	 process = false;
           		//$('#cerita').val('');
    			//$('.text').text('');
	           }
        };
        
        var uploader = new ss.SimpleUpload(default_settings); 
        $('.kirim').click(function(res){
        	//console.log(uploader._opts.data);
        	//console.log();
        	//$('#photo-preview').attr('src', response.realpath);
        	//$('#div-preview').show();
        	$('#info-foto').html(uploader._queue[0].file.name);
        	$('#info-foto').show();
        	$('.crop-popup').parent().hide();
        });
        $('.posting').click(function(){
        	//_gaq.push(['_trackEvent', 'Button', 'Submit Photo', 'Click']);
        	if(IS_LOGGED_IN==true){

	        	if(has_uploaded == 1) { //alert('upload photo hanya sekali saja'); 
	        		$('.notification-only-one').parents('.popup').show();
	        		return false;
	        	}

	        	if($('#agreement').prop('checked') ){
	        		//-- DO NOTHING
	        	}else{
	        		//alert('Anda belum menyetujui agreement');
	        		$('#error-text-popup').html('Klik kotak syarat dan ketentuan terlebih dahulu.');
	        		$('#gagal').show();
	        		return false;
	        	}
	        	
	        	//--- RECAPTHA CHECK
	        	if($.trim($('#recaptcha_response_field').val()) == '') 
	        	{
	        		//alert('Captha masih kosong');
	        		$('#error-text-popup').html('Captha masih kosong.');
	        		$('#gagal').show();
	        		return false;
	        	}else{
	        		var chalenge = $('#recaptcha_challenge_field').val();
		        	var answer = $('#recaptcha_response_field').val();
		        	var ceked = true;
	        		$.ajax({
						type: "POST",
						url: SITE_URL+'bubble/cek_recaptha',
						data: $.extend(tokens,{chalenge:chalenge, answer:answer}),
						async: false,
						success: function(ret2){
							if(ret2==false){
								ceked = ret2;
							}
						},
						//dataType: dataType
					});

					//console.log(ceked);
					if(!ceked){
						//alert('Salah');
						$('#error-text-popup').html('Recaptha tidak valid.');
	        			$('#gagal').show();
						return false;
					}
		        	
	        	}        	
	        	
	        	//alert('process');
	        	//if(process) return false;
	        	if($.trim($('#cerita').val()) == '') 
	        	{
	        		//alert('Cerita masih kosong');
	        		$('#error-text-popup').html('Cerita masih kosong.');
	        		$('#gagal').show();
	        		return false;
	        	}
	        	  
	        	//-- IF USR NOT UPLOADING PHOTO
	        	if(uploader._queue.length==0){    	
	        		uploader.setData($.extend(uploader._opts.data, {'xhr':'2'}));     	
		    	}
		    	

		    	$('div.loading').show();
		    	if(uploader._queue.length>1)
				{
					uploader._queue.splice(0,uploader._queue.length-1);
				}	

	        	//uploader.setData($.extend(uploader._opts.data, crop));
	        	uploader.setData($.extend(uploader._opts.data, crop, {cerita:$('#cerita').val(), video:$('#link-youtube').val()}));
	        	
	        	if(uploader._opts.data.xhr)
	        	{
	        		process = true;
	        		$.post(uploader._opts.url,uploader._opts.data,function(response){
	        			process = false;
	        			$('div.loading').hide();
	        			//$('#cerita').val('');
	        			//$('.text').text('');
	        			response = JSON.parse(response);
	        			//$(response.popup).show();
	        			//alert(response);
	        			if(response.konek_by=='fb'){
	        				//alert('sddsf')
	        				post_to_feed(response.realpath, response.video, $('#cerita').val(), function(){
	        					close_sukses_posting();
	        				});
	        			}
	        			if(response.konek_by=='tw'){
	        				//post_tweet();
	        				close_sukses_posting();
	        			}	        			
	        			//close_sukses_posting();
	        		});
	        	}
	        	else
	        	{
	        		if(uploader._queue.length < 1)
			    	 {
			    	 	alert('Kosong');
			    	 	$('.notification-file-required').parents('.popup').show();
			    	 	return false;
			    	 }
	        		/*alert(uploader._opts.data.y);
	        		alert(uploader._opts.data.w);
	        		alert(uploader._opts.data.h);*/
	        		uploader.submit();
	        	}
	        }else{
	        	//alert('Anda belum login');
	        	$('#error-text-popup').html('Anda belum login.');
	        	$('#gagal').show();
	        }
        	//console.log(uploader._opts.data);
        });
        
        
       //bahasa click 
	var current_index = 1;
	
	//bahasa_click(); 
        
	});
		 
})(jQuery);
