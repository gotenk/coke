<?php defined('BASEPATH') OR exit('No direct script access allowed.');

/**
 * MaxCMS Text Helpers
 *
 * This overrides CodeIgniter's helpers/text_helper.php file.
 * 
 * @package    MaxCMS\Core\Helpers
 * @author     MaxCMS Dev Team
 * @copyright  Copyright (c) 2012, MaxCMS LLC
 */
if ( ! function_exists('nl2p'))
{
	/**
	 * Replaces new lines with <p> HTML element.
	 * 
	 * @param string $str The input string.
	 * @return string The HTML string.
	 */
	function nl2p($str)
	{
		return str_replace('<p></p>', '', '<p>'
			.nl2br(preg_replace('#(\r?\n){2,}#', '</p><p>', $str))
			.'</p>');
	}

}

if ( ! function_exists('escape_tags'))
{
	/**
	 * Replaces the { and } with their HTML character code equivalents. This will take
	 * care of double {{ }} MaxCMS tags as well as the single ones which are used
	 * to pass plugin results as plugin parameters.
	 *
	 * @param string $string The string with tags.
	 * @return string The string with the tags escaped
	 */
	function escape_tags($string)
	{
		return str_replace(array('{', '}'), array('&#123;', '&#125;'), $string);
	}

}

if ( ! function_exists('process_data_jmr1'))
{

	// Set PCRE recursion limit to sane value = STACKSIZE / 500 (256KB stack. Win32 Apache or  8MB stack. *nix)
	ini_set('pcre.recursion_limit', (strtolower(substr(PHP_OS, 0, 3)) === 'win' ? '524' : '16777'));

	/**
	 * Process data JMR1
	 *
	 * Minifying final HTML output
	 *
	 * @param string $text The HTML output
	 * @return string  The HTML without white spaces or the input text if its is too big to your SO proccess.
	 * @author Alan Moore, ridgerunner
	 * @author Marcos Coelho <marcos@marcoscoelho.com>
	 * @see http://stackoverflow.com/q/5312349
	 */
	function process_data_jmr1($text = '')
	{
		$re = '%                            # Collapse whitespace everywhere but in blacklisted elements.
        (?>                                 # Match all whitespans other than single space.
          [^\S]\s*                          # Either one [\t\r\n\f\v] and zero or more ws,
          |\s{2,}                           # or two or more consecutive-any-whitespace.
        )				                    # Note: The remaining regex consumes no text at all...
        (?=                                 # Ensure we are not in a blacklist tag.
          [^<]*+                            # Either zero or more non-"<" {normal*}
          (?:                               # Begin {(special normal*)*} construct
            <                               # or a < starting a non-blacklist tag.
            (?!/?(?:textarea|pre|script)\b)
            [^<]*+                          # more non-"<" {normal*}
          )*+                               # Finish "unrolling-the-loop"
          (?:                               # Begin alternation group.
            <                               # Either a blacklist start tag.
            (?>textarea|pre|script)\b
            |\z                             # or end of file.
          )                                 # End alternation group.
        )                                   # If we made it here, we are not in a blacklist tag.
        %Six';

		if (($data = preg_replace($re, ' ', $text)) === null)
		{
			log_message('error', 'PCRE Error! Output of the page "'.uri_string().'" is too big.');

			return $text;
		}

		return $data;
	}

}

if ( ! function_exists('character_limiter_custom'))
{
	function character_limit_custom($str)
	{
		$data_to_find = explode('<div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>',$str);
		
		return character_limiter($data_to_find[0],40);
			
	}
}

if ( ! function_exists('show_teaser_custom'))
{
	function show_teaser_custom($str)
	{
		$data_to_find = explode('<div style="page-break-after:always;"><span style="display:none;"> </span></div>',$str);
		
		if(count($data_to_find)> 0)
		{
			return $data_to_find[0];
		}
		else {
			require_once APPPATH.'libraries/htmlpurifier/HTMLPurifier.auto.php';
			
			$config = HTMLPurifier_Config::createDefault();
			$config->set('Core.Encoding', 'UTF-8');
			$config->set('Core.CollectErrors', true);
			$config->set('HTML.TidyLevel', 'medium');
			$config->set('HTML.Doctype', 'HTML 4.01 Transitional');
			$config->set('AutoFormat.RemoveEmpty.RemoveNbsp',false);
			$config->set('HTML.Allowed','br,span[style],div[style],ul,li,ol,strong,em');
			$config->set('CSS.AllowTricky',true);
			$config->set('URI.DisableExternalResources', false);
			//$config->set('Cache.DefinitionImpl', null);
			$config->set('CSS.Proprietary',true);
			$config->set('CSS.AllowedProperties',array('page-break-after'=>true,'display'=>true));
		
			/*$config = HTMLPurifier_Config::createDefault();
			$config->set('Core.Encoding', 'UTF-8');
			$config->set('Core.CollectErrors', true);
			$config->set('HTML.TidyLevel', 'medium');
			$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
			$config->set('HTML.Allowed','br,span[style],div[style],ul,li,ol,strong,em');
			$config->set('URI.DisableExternalResources', false);*/
			//$config->set('CSS.AllowedProperties',array('page-break-after'=>'always','display'=>'none'));
			
			
			
			$purifier = new HTMLPurifier($config); 
			$clean_html =$purifier->purify(substr($data_to_find[0],206));
			return $clean_html;
		}
		
			
	}
}

if ( ! function_exists('random_text'))
{
	$myglobalvar="";
	function random_text($str=array())
	{
		GLOBAL $myglobalvar;
		$data_search = array_search($myglobalvar,$str);
		if(isset($str[$data_search]))
		{
			unset($str[$data_search]);
		}
				
		$data_rand = array_rand ( $str,1 );
		
		/*if (! isset($str[$data_rand[0]]))
		{
			var_dump($data_rand);
		}*/
		$myglobalvar = $str[$data_rand];
		return  $str[$data_rand] ;
			
	}
}
