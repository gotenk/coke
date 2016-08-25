<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bitly_cache_m extends MY_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->_table = 'bitly_cache';
	}

}