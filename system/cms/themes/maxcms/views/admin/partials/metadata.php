<?php
Asset::js('jquery/jquery.js');
Asset::js_inline('jQuery.noConflict();');
Asset::js('jquery/jquery-ui.min.js', 'jquery/jquery-ui.min.js');
Asset::js('jquery/jquery.colorbox.js');
Asset::js('jquery/jquery.cooki.js');
Asset::js('jquery/jquery.slugify.js');

Asset::js(array('codemirror/codemirror.js',
	'codemirror/mode/css/css.js',
	'codemirror/mode/htmlmixed/htmlmixed.js',
	'codemirror/mode/javascript/javascript.js',
	'codemirror/mode/markdown/markdown.js',
	'plugins.js',
	'scripts.js'
)); ?>

<?php if (isset($analytic_visits) OR isset($analytic_views)): ?>
	<?php Asset::js('jquery/jquery.excanvas.min.js'); ?>
	<?php Asset::js('jquery/jquery.flot.js'); ?>
<?php endif; ?>

<script type="text/javascript">
	max = { 'lang' : {} };
	var APPPATH_URI					= "<?php echo APPPATH_URI;?>";
	var SITE_URL					= "<?php echo rtrim(site_url(), '/').'/';?>";
	var BASE_URL					= "<?php echo BASE_URL;?>";
	var BASE_URI					= "<?php echo BASE_URI;?>";
	var ADMIN_URL					= "<?php echo ADMIN_URL;?>";
	var UPLOAD_PATH					= "<?php echo UPLOAD_PATH;?>";
	var DEFAULT_TITLE				= "<?php echo addslashes($this->settings->site_name); ?>";
	max.admin_theme_url			= "<?php echo BASE_URL . $this->admin_theme->path; ?>";
	max.apppath_uri				= "<?php echo APPPATH_URI; ?>";
	max.base_uri					= "<?php echo BASE_URI; ?>";
	max.lang.remove				= "<?php echo lang('global:remove'); ?>";
	max.lang.dialog_message 		= "<?php echo lang('global:dialog:delete_message'); ?>";
	max.csrf_cookie_name			= "<?php echo config_item('cookie_prefix').config_item('csrf_cookie_name'); ?>";
	var tokens ={<?php echo '\''.$this->security->get_csrf_token_name().'\':\''.$this->security->get_csrf_hash().'\''; ?>};
</script>

<?php Asset::css(array('plugins.css', 'jquery/colorbox.css', 'codemirror.css', 'animate/animate.css')); ?>

<?php echo Asset::render(); ?>

<!--[if lt IE 9]>
<?php echo Asset::css('ie8.css', null, 'ie8'); ?>
<?php echo Asset::render_css('ie8'); ?>
<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<?php if ($module_details['sections']): ?>
<style>section#content {margin-top: 170px!important;}</style>
<?php endif; ?>

<?php echo $template['metadata']; ?>
