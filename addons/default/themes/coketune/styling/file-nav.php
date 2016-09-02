<div id="file-nav">
	PAGE
	<a href="javascript:void(0)" id="file-nav-toggle">></a>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#file-nav-toggle').on('click', function(){
				if($('#file-nav').hasClass('active')){
					$('#file-nav').removeClass('active');
				}else{
					$('#file-nav').addClass('active');
				}
			})
		})
	</script>
	<div>
        <?php
	if ($handle = opendir('.')) {
	    while (false !== ($file = readdir($handle))) {
	    	if( $file!='file-nav.php' && $file!='metadata.php' && $file!='javascript.php' && substr($file, 0, 6)!='header'  && $file!='footer.php'&&  substr($file, -4)=='.php' || substr($file, -5)=='.html')
	        	echo '<a href="'.$file.'">'.$file.'</a>';
	    }
	    closedir($handle);
	}
?>

    </div>
</div>    