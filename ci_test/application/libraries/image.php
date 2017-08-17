<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Image
{
    private $_ci;

    public function __construct() {
            $this->_ci =& get_instance();
    }

    public function get_image($info, $type, $logged = false, $background = false, $class = []) {
    	$config = [
		    'picture' => $info->picture,
		    'id' => $info->id
		];
    	
    	switch ($type) {
    		case 'beer':
    			$config['alt'] = $info->beerName . ' - ' . $info->name;
    			break;
    		case 'establishment':
    			$config['alt'] = $info->name;
    			break;
    	}
    	
		if (isset($info->width)) {
			$config['width'] = $info->width;
		}
		if (isset($info->height)) {
			$config['height'] = $info->height;
		}
		if (count($class) > 0) {
			$config['class'] = $class;
		}

		if ($background) {
			return $this->checkForBackgroundImage($config, $logged, $type);
		}
		return $this->checkForAnImage($config, $logged, $type);
    }

    public function checkForAnImage($config, $edit = true, $type = 'beer', $wrap = false) {
		$image = [];
		//$path = $type == 'beer' ? 'beers' : 'establishments';
		$path = $type . 's';
		
		$width = key_exists('width', $config) ? ' width="' . $config['width'] . '"' : '';
		$height = key_exists('height', $config) ? ' height="' . $config['height'] . '"' : '';
		$alt = key_exists('alt', $config) ? ' title="' . $config['alt'] . '" alt="' . $config['alt'] . '"' : '';
		$attributes = $width . $height . $alt;
		$class = key_exists('class', $config) && is_array($config['class']) ? ' class="' . implode(' ', $config['class']) . '"' : '';

		if (!empty($config['picture']) ||
			(!empty($config['picture']) &&
				array_key_exists('approval', $config) &&
				$config['approval'] == 1))
		{		
			$image['source'] = '<img src="/images/' . $path . '/' . $config['picture'] . '"' . $attributes . '>';
		}
		else {
			$holder_image = 'bottle.gif';
			if ($type == 'establishment') {
				$holder_image = 'brewery.jpg';
			}
			elseif ($type == 'avatar') {
				$holder_image = 'nobody.gif';
			}
			$image['source'] = '<img src="/images/' . $path . '/' . $holder_image . '"' . $attributes . $class . '>';

			if ($edit) {
				if (empty($config['picture']) && $this->_ci->session->userdata('userInfo')['uploadImage'] == 1) {
					$extraClass = $type == 'establishment' ? ' nubbin_establishment' : '';
					$image['nub'] = '
					<div id="nubbin_' . $config['id'] . '" class="nubbin' . $extraClass . '">
						<a href="' . base_url() . 'page/uploadImage/' . $path . '/' . $config['id'] . '"><img src="/images/nubbin_editPhoto.jpg" title="edit image" alt="edit image"></a>
					</div>
					';
				}
			}
		}		//echo '<pre>' . print_r($image, true); exit;
		return $image;
		
		if (!empty($config['picture']) ||
			(!empty($config['picture']) &&
				array_key_exists('approval', $config) &&
				$config['approval'] == 1))
		{		
			if ($wrap) {		
				$img = '<div class="' . $type . 'Pic_normal"><img src="/images/' . $path . '/' . $config['picture'] . '"' . $alt . $width . $height . '></div>';
			} else {
				$img = '<img src="/images/' . $path . '/' . $config['picture'] . '"' . $alt . $width . $height . '>';
			}
		}
		else {
			if ($wrap) {
				if ($type == 'beer') {
					$img = '<div class="' . $type . 'Pic_normal"><img src="/images/beers/bottle.gif"' . $width . $height . '></div>';
				}
				elseif ($type == 'establishment') {
					$img = '<div class="' . $type . 'Pic_normal"><img src="/images/establishments/brewery.jpg"' . $width . $height . '></div>';
				}
			}
			else {
				if ($type == 'beer') {
					$img = '<img src="/images/beers/bottle.gif"' . $width . $height . '>';
				}
				elseif($type == 'establishment') {
					$img = '<img src="/images/establisments/brewery.jpg"' . $width . $height . '>';
				}
				elseif ($type == 'avatar') {
					$img = '<img src="/images/avatars/nobody.gif"' . $width . $height . '>';
				}
			}

			if ($edit) {
				$bl = true;
				if (!empty($config['picture']) && array_key_exists('approval', $config) && $config['approval'] == 0) {
					$bl = false;
				}
				
				if($bl) {
					$extraClass = $type == 'establishment' ? ' nubbin_establishment' : '';
					$nub = '
					<div id="nubbin_' . $config['id'] . '" class="nubbin' . $extraClass . '">
						<a href="' . base_url() . 'page/uploadImage/' . $path . '/' . $config['id'] . '"><img src="/images/nubbin_editPhoto.jpg" title="edit image" alt="edit image"></a>
					</div>
					';
				}
			}
		}		
		return $image;
	}

	public function checkForBackgroundImage($config, $edit = false, $type = 'beer') {
		$image = [];
		//$path = $type == 'beer' ? 'beers' : 'establishments';
		$path = $type . 's';

		$width = key_exists('width', $config) ? ' width: ' . $config['width'] . 'px' : '';
		$height = key_exists('height', $config) ? ' height: ' . $config['height'] . 'px' : '';
		
		if (!empty($config['picture']) ||
			(!empty($config['picture']) &&
				array_key_exists('approval', $config) &&
				$config['approval'] == 1))
		{		
			$image['source'] = '/images/' . $path . '/' . $config['picture'];
		}
		else {
			if ($type == 'beer') {
				$image['source'] = '/images/beers/bottle.gif';
			}
			elseif ($type == 'establishment') {
				$image['source'] = '/images/establishments/brewery.jpg';
			}
			elseif ($type == 'avatar') {
				$image['source'] = '/images/avatars/nobody.gif';
			}

			if ($edit) {
				//if (empty($config['picture']) && (array_key_exists('approval', $config) && $config['approval'] != 0)) {
				if (empty($config['picture']) && $this->_ci->session->userdata('userInfo')['uploadImage'] == 1) {
					$extraClass = $type == 'establishment' ? ' nubbin_establishment' : '';
					$image['nub'] = '
					<div id="nubbin_' . $config['id'] . '" class="nubbin' . $extraClass . '">
						<a href="' . base_url() . 'page/uploadImage/' . $path . '/' . $config['id'] . '"><img src="/images/nubbin_editPhoto.jpg" title="edit image" alt="edit image"></a>
					</div>';
				}
			}
		}
		return $image;
	}
}