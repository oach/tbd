<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class SRM {
	private $_ci;
	private $_string_hex = [
		'pale straw' => ['hex' => '#ffff45', 'color' => '#000'],
		'straw' => ['hex' => '#ffe93e', 'color' => '#000'],
		'pale gold' => ['hex' => '#fed849', 'color' => '#000'],
		'light gold' => ['hex' => '#fed849', 'color' => '#000'],
		'gold' => ['hex' => '#ffd700', 'color' => '#000'],
		'deep gold' => ['hex' => '#ffa846', 'color' => '#000'],
		'light deep gold' => ['hex' => '#ffa846', 'color' => '#000'],
		'pale amber' => ['hex' => '#f49f44', 'color' => '#000'],
		'medium amber' => ['hex' => '#d77f59', 'color' => '#000'],
		'amber' => ['hex' => '#d77f59', 'color' => '#000'],
		'deep amber' => ['hex' => '#94523a', 'color' => '#fff'],
		'amber-brown' => ['hex' => '#804541', 'color' => '#fff'],
		'brown' => ['hex' => '#5b342f', 'color' => '#fff'],
		'ruby brown' => ['hex' => '#4c3b2b', 'color' => '#fff'],
		'deep brown' => ['hex' => '#38302e', 'color' => '#fff'],
		'dark brown' => ['hex' => '#38302e', 'color' => '#fff'],
		'black' => ['hex' => '#000', 'color' => '#fff'],
		'orange' => ['hex' => '#ffa500', 'color' => '#000'],
		'orange amber' => ['hex' => '#ffbf00', 'color' => '#000'],
		'red' => ['hex' => '#c00', 'color' => '#fff'],
		'default' => ['hex' => '#fff', 'color' => '#000']
	];

	private $_numerical_hex = [
		2 => ['hex' => '#ffff45', 'color' => '#000'],
		3 => ['hex' => '#ffe93e', 'color' => '#000'],
		4 => ['hex' => '#fed849', 'color' => '#000'],
		5 => ['hex' => '#ffd700', 'color' => '#000'],
		6 => ['hex' => '#ffa846', 'color' => '#000'],
		9 => ['hex' => '#f49f44', 'color' => '#000'],
		12 => ['hex' => '#d77f59', 'color' => '#000'],
		15 => ['hex' => '#94523a', 'color' => '#fff'],
		18 => ['hex' => '#804541', 'color' => '#fff'],
		20 => ['hex' => '#5b342f', 'color' => '#fff'],
		24 => ['hex' => '#4c3b2b', 'color' => '#fff'],
		30 => ['hex' => '#38302e', 'color' => '#fff'],
		40 => ['hex' => '#000', 'color' => '#fff']
	];
	
	public function __construct()
	{
		$this->_ci =& get_instance();
	}

	public function convert_string_to_hex($str)
	{
		$str = strtolower($str);

		if (empty($str) || (!array_key_exists($str, $this->_string_hex) && !ctype_digit($str))) {
			return $this->_string_hex['default'];
		} 

		if (ctype_digit($str)) {
			return $this->_numerical_compare((integer) $str);
		} else {
			return $this->_string_hex[$str];
		}
	}

	private function _numerical_compare($integer) {
		if (array_key_exists($integer, $this->_numerical_hex)) {
			return $this->_numerical_hex[$integer];
		}

		$keys = array_keys($this->_numerical_hex);
		sort($keys);
		
		foreach ($keys as $key => $val) {
			if ($integer < $val) {
				return $this->_numerical_hex[$val];
			}
		}
	} 
}