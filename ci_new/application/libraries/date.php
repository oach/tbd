<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Date
{
	private $_ci;	

	public function __construct() {
		$this->_ci =& get_instance();
	}

	public function get_month_names($months) {
        $str = '';
		
        if (!empty($months)) {
            $parts = explode(',', $months);
    		
            foreach ($parts as $month) {
    			$month = (int) $month;
    			$str .= !empty($str) ? ', ' : '';
    			$str .= date('M', mktime(0, 0, 0, $month, 1, 2009));
    		}
        }
		
        return $str;
	}
}
?>