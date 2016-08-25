<script type="text/javascript" src="<?php echo BASE_URL?>system/cms/themes/maxcms/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL?>system/cms/themes/maxcms/js/ckeditor/adapters/jquery.js"></script>
<script type="text/javascript">

	var instance;

	function update_instance()
	{
		instance = CKEDITOR.currentInstance;
	}

	(function($) {
		$(function(){

			max.init_ckeditor = function(){
				<?php echo $this->parser->parse_string(Settings::get('ckeditor_config'), $this, TRUE); ?>
				max.init_ckeditor_maximize();
			};
			max.init_ckeditor();

		});
	})(jQuery);
</script>