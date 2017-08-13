<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Pricing
{
	private $_ci;
	
	private $_priceLingo = [
		1 => 'pricey',
		2 => 'little more than expected',
		3 => 'right about right',
		4 => 'little less than expected',
		5 => 'bargain'
	];

	public function __construct() {
		$this->ci =& get_instance();
	}

	public function getPriceLingo($key) {
		if (array_key_exists($key, $this->_priceLingo)) {
			return $this->_priceLingo[$key];
		}
		return '';
	}

	public function getAveragePriceLingo($record) {
		$string = '';
		if ($record->reviews > 0 && count($record->average_cost) > 0) {
			foreach ($record->average_cost as $cost) {
				$string .= '$' . number_format($cost->averagePrice, 2) . ', ' . $cost->totalServings . ' serving' . ($cost->totalServings > 0 ? 's' : '') . ', ' . $cost->package . '<br>';
			}
		}
		else {
			$string = 'No cost data';
		}
		return $string;
	}
}