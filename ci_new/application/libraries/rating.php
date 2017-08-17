<?php
if(!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Rating
{
	public function __construct()
	{
		$this->_ci =& get_instance();
	}
	
	public function getRatingsLabelClass($value)
	{
		if ($value >= 7) {
			return ' label-success';
		} elseif ($value >= 5) {
			return ' label-primary';
		} elseif ($value >= 3) {
			return ' label-warning';
		} elseif ($value >= 0.1) {
			return ' label-danger';
		}
		return ' label-default';
	}
}
?>