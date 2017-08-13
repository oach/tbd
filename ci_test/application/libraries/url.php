<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Url
{
	private $_ci;

	public function __construct()
	{
		$this->_ci =& get_instance();
	}

	/**
	 * Check to see if there is a URL that the user should be sent back to
	 * once they have arrived at current page.  Example: login
	 * 
	 * @return string
	 */
	public function manipURIPassback()
	{
		$totalSegs = $this->_ci->uri->total_segments();
		if ($totalSegs > 3) {
			$arr = $this->_ci->uri->segment_array();
			$uri = '';
			for ($i = 3; $i <= count($arr); $i++)
			{
				$uri .= empty($uri) ? $arr[$i] : '/' . $arr[$i];
			}
			return $uri;
		} elseif ($this->_ci->uri->segment(3) !== false) {
			return $this->_ci->uri->segment(3);
	    }
		return '';
	}

	public function swap_out_uri($search, $replace, $uri) {
		return str_replace($search, $replace, $uri);
	}
}
?>