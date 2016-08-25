<div id="fb-root"></div>
<script>
var postFeed,postFeedURL;
var fbLoaded = false;
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '{{ settings:fb_app_id }}',                        // App ID from the app dashboard
      status     : true,                                 // Check Facebook Login status
      xfbml      : true,
      cookie: true,
      version    : 'v2.3',                                // Look for social plugins on the page
      channelUrl: '{{ url:base }}channel.php'
    });
    
	 fbLoaded =true;
	 var postfeed =false;
	postFeed = function(data_json){
		$data_json = JSON.parse(data_json);
		if(postfeed)return;
		postfeed = true;
		 FB.ui({
		  method: 'feed',
		  link: $data_json.link,
		  caption :$data_json.caption,
		  name :$data_json.caption ,
		  description: $data_json.description,
		  picture :$data_json.picture
		}, function(response){
			postfeed =false;
		});
	}
	
	var postfeedurl = false
	postFeedURL = function($url){
		if(postfeedurl)return;
		postfeedurl = true;
		 FB.ui({
		  method: 'share',
		  href: $url,
		}, function(response){
			postfeedurl =false;
		});
	}
	
	 
  };  

  // Load the SDK asynchronously
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/all.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));  
   </script>