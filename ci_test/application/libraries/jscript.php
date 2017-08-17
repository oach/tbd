<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Jscript
{
	private $_ci;
	private $_required = [
		'form_auto_search.js'
	];

	public function __construct() {
		$this->_ci =& get_instance();
	}

	public function loadRequired() {
		return $this->_getRequired();
	}

	private function _getRequired() {
		return $this->_required;
	}
}
?>