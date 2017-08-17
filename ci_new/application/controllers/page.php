<?php
class Page extends CI_Controller
{
	private $_upload = array(
		'avatars' => array(
			'width' => 100,
			'height' => 100
		),
		'beers' => array(
			'width' => 150,
			'height' => 350
		),
		'establishments' => array(
			'width' => 240,
			'height' => 160
		)
	);

	private $_image_types = ['avatars', 'establishments', 'beers'];
	private $_search_types = ['beer', 'establishment', 'user'];
	
	public function __construct() {
		parent::__construct();
		$this->load->helper(['users', 'admin', 'js', 'email', 'form']);
		$this->load->library(['session', 'layout', 'visitor', 'seo', 'date', 'quote', 'jscript']);
		
		$this->_data['logged'] = $this->visitor->checkLogin();
		$this->_data['user_info'] = $this->session->userdata('userInfo');
		$this->_data['seo'] = $this->seo->getSEO();
		$this->_data['quote'] = $this->quote->getFooterQuote();
		$this->_data['js'] = $this->jscript->loadRequired();
	}
	
	public function index() {
		$this->load->model('RatingModel', '', true);
		$this->_data['beer_reviews'] = $this->RatingModel->getAll(BEER_REVIEW_HOME);
		
		$this->load->model('BreweriesModel', '', true);
		$this->_data['brewery_hop'] = $this->BreweriesModel->getAllBrewreyHops(1);
		
		$this->load->model('SeasonModel', '', true);
        $this->load->helper('date');
		$this->_data['season'] = $this->SeasonModel->getSeasonalForFrontPage();

		array_push($this->_data['js'], 'twitter.js', 'blog_rss.js');
		$this->load->library(['rating']);

        $this->layout->render('page/index.php', $this->_data, 'two_column.php');
	}
	
	public function search() {
		// Checked if user is logged.  Non logged cannot search users
		$type = $this->_data['type'] = strtolower(filter_var($this->input->post('searchType'), FILTER_SANITIZE_STRING));
		if ('user' == $type && !$this->_data['logged']) {
			$this->_data['not_logged_user_search'] = true;
		} else {
			$this->load->model('SearchModel', '', true);

			$original_search_string = $this->input->post('search-menu-text', true);
			if (!$original_search_string) {
				$original_search_string = $this->input->post('search', true);
			}
			$original_search_string_for_search = preg_replace('/[^\w\s-]/', '', $original_search_string);
			$original_search_string = trim(filter_var($original_search_string, FILTER_SANITIZE_STRING));
			$original_search_string_for_search = filter_var($original_search_string_for_search, FILTER_SANITIZE_STRING);

			$search_string = explode(' ', $original_search_string_for_search);
			$final_search_string = [];
			if (strlen($original_search_string_for_search) > 2) {
				if (!empty($search_string) && is_array($search_string)) {
					foreach ($search_string as $string) {
						if (strlen($string) > 0) {
							$final_search_string[] = $string;
						}
					}		
				
					if (!empty($final_search_string)) {
						$search['original'] = $this->_data['original_search_string'] = $original_search_string;
						$search['wildCards'] = $this->_data['final_search_string'] = $final_search_string;

						$this->_data['searchRS'] = $this->SearchModel->{$this->_data['type'] . '_search'}($original_search_string_for_search);
						if (!$this->_data['searchRS'] && $this->_data['type'] != 'user') {
							$this->_data['searchRS'] = $this->SearchModel->{$this->_data['type'] . '_search'}($final_search_string);
						}
			        }
			    }
			}
		}
		$this->_data['search_types'] = $this->_search_types;
        array_push($this->_data['js'], 'search_form.js');
        $this->layout->render('page/search.php', $this->_data, 'two_column.php');
	}

	public function searchAutoComplete() {
        $term = filter_var($this->input->post('term'), FILTER_SANITIZE_STRING);
        $search_type = filter_var($this->input->post('search_type'), FILTER_SANITIZE_STRING);
        
        if (in_array($search_type, $this->_search_types)) {
        	$model = '';
	        $url = '';
	        $image_path = '';
	        $width = '';
	        $height = '';
	        switch ($search_type) {
	            case 'beer':
	                $model = 'BeerModel';
	                $url = 'beer/review/';
	                $image_path = 'beers';
	                $image_type = 'beer';
	                $width = 30;
	                $height = 70;
	                break;
	            case 'establishment':
	                $model = 'EstablishmentModel';
	                $url = 'brewery/info/';
	                $image_path = 'establishments';
	                $image_type = 'establishment';
	                $width = 72;
	                $height = 48;
	                break;
	            case 'user':
	                $model = 'UserModel';
	                $url = 'user/profile/';
	                $image_path = 'avatars';
	                $image_type = 'avatar';
	                $width = 50;
	                $height = 50;
	                break;
	        }
	        
	        $this->load->model($model, '', true);
	        $records = $this->$model->autoCompleteSearch($term);
	        
	        $this->load->library('image');

	        $array = [];	        
	        if (!empty($records) && is_array($records)) {
	            foreach ($records as $record) {
	            	$config = [
	                	'picture' => $record->picture,
	                	'id' => $record->id,
						'width' => $width,
						'height' => $height
					];
					$image = $this->image->checkForAnImage($config, false, $image_type);
					
					$array[] = array(
	                    'name' => $record->name,
	                    'url' => base_url() . $url . $record->id . (isset($record->slug) ? '/' . $record->slug : ''),
	                    'image' => $image['source']
	                );	                
	            }
	        }
	        
	        echo json_encode($array);
	    }
    }

	public function saveUploadImage()
	{
		if (!$this->_data['logged']) {
			echo json_encode(array('danger' => 'loggedout'));
		} else {
			$type = filter_var($this->input->post('type'), FILTER_SANITIZE_STRING);
			$id = filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT);
			$decoded = $this->input->post('image-data');
			if (!$type || !in_array($type, $this->_image_types) || !$id || !$decoded) {
				echo json_encode(array('danger' => 'baddata'));
			} else {
				$pieces = explode(',', $decoded);
		    	$base64 = array_pop($pieces);
		    	$image_data = base64_decode($base64);

		    	$root = $_SERVER['DOCUMENT_ROOT'];
				$file_path = $root . '/images/';
				$extension = 'jpg';
				$slug = '';
				$uri = '';
		    	switch ($type) {
		    		case 'establishments':		    			
						$this->load->model('EstablishmentModel', '', true);
						$brewery = $this->EstablishmentModel->getEstablishmentByID($id);
						$slug = $brewery->slug_establishment;
						$file_name = str_replace('-', '_', $slug);
						$full_file_path_tmp = $root . '/images/' . $type . '/tmp/' . $file_name;
						$full_file_path = $root . '/images/' . $type . '/' . $file_name . '.' . $extension;
						$uri = 'establishment/info/rating/' . $id . '/' . $slug;
		    			break;
		    		case 'beers':
		    			$this->load->model('BeerModel', '', true);
						$beer = $this->BeerModel->getBeerByID($id);
						$slug = $beer->slug_beer . '-' . $beer->slug_establishment;
		    			$file_name = str_replace('-', '_', $slug);
		    			$full_file_path_tmp = $root . '/images/' . $type . '/tmp/' . $file_name;
						$full_file_path = $root . '/images/' . $type . '/' . $file_name . '.' . $extension;
		    			$uri = 'beer/review/' . $id . '/' . $slug;
		    			break;
		    		case 'avatars':
		    			$file_name = str_replace('-', '_', $this->_data['user_info']['username']) . '_' . $this->_data['user_info']['id'];
		    			$full_file_path_tmp = $root . '/images/' . $type . '/tmp/' . $file_name;
						$full_file_path = $root . '/images/' . $type . '/' . $file_name . '.' . $extension;
		    			$uri = 'user/profile/' . $id;
		    			break;
		    	}

		    	file_put_contents($full_file_path_tmp, $image_data);
		    	$mime = mime_content_type($full_file_path_tmp);

		    	if ('image/jpeg' == $mime) {
		    		if (rename($full_file_path_tmp, $full_file_path)) {
		    			switch ($type) {
				    		case 'establishments':
				    			$this->EstablishmentModel->updateImageByID($id, $file_name . '.' . $extension);
				    			break;
				    		case 'beers':
				    			$this->BeerModel->updateImageByID($id, $file_name . '.' . $extension);
				    			break;
				    		case 'avatars':
				    			$this->load->model('UserModel', '', true);
								$this->UserModel->updateAvatar($id, $file_name . '.' . $extension);
								
								$this->_data['user_info']['avatar'] = 1;
								$this->session->set_userdata(array('userInfo' => $this->_data['user_info']));
				    			break;
				    	}				    	

			    		$json = [
			    			'danger' => 0,
							'uri' => $uri
						];
						usleep(500000);
						echo json_encode($json);
					} else {
						echo json_encode(array('danger' => 'File move did not work.'));
					}
		    	} else {
		    		echo json_encode(array('danger' => 'File had the wrong file extension.'));
		    	}
			}
		}
	}
	
	public function uploadImage()
	{
		if (!$this->_data['logged']) {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
		
		$type = $this->_data['type'] = filter_var($this->uri->segment(3), FILTER_SANITIZE_STRING);
		$id = $this->_data['id'] = filter_var($this->uri->segment(4), FILTER_SANITIZE_NUMBER_INT);
		if (!$type || !in_array($type, $this->_image_types) || !$id) {
			header('Location: ' . base_url());
			exit;
		}

		if ('avatars' == $type && $id != $this->_data['user_info']['id']) {		
			$this->_data['error'] = 'You are not allowed to upload an avatar for someone else.';
		} elseif ($this->_data['user_info']['uploadImage'] == "0") {
			$this->_data['error'] = 'You are banned from uploading images.';
		} else {
			if ('establishments' == $type) {
				$this->load->model('EstablishmentModel', '', true);
				$this->_data['info'] = $this->EstablishmentModel->getEstablishmentByID($id);
			} elseif ('beers' == $type) {
				$this->load->model('BeerModel', '', true);
				$this->_data['info'] = $this->BeerModel->getBeerByID($id);
			}
		}
		$this->_data['css'] = ['cropper.min.css', 'cropper.tbd.css', 'cropper.' . $type . '.css'];
		array_push($this->_data['js'], 'jquery.cropit.0.5.1.js', 'crop_image.js');
    	$this->layout->render('page/upload.php', $this->_data, 'two_column.php');
	}
	
	private function _getImageUploadInfo($id, $type, $word = 'Upload') {
		$str = '';
		// check the type of the information that is needed
		switch($type) {
			case 'avatars':	
				// user info for logged in user
				$userInfo = $this->session->userdata('userInfo');
				// create the right header
				$str = '<h2 class="brown">' . $word . ' Image: New Avatar for ' . $userInfo['username'] . '</h2>';
				// get out of here			
				break;
			case 'beers':
				// load the beer model
				$this->load->model('BeerModel', '', true);
				// get the info for this beer
				$info = $this->BeerModel->getBeerByID($id);
				// create the right header
				$str = '<h2 class="brown">' . $word . ' Image: ' . $info['beerName'] . ' by ' . $info['name'] . '</h2>';
				// get out of here
				break;
			case 'establishments':
				$this->load->model('EstablishmentModel', '', true);
				$info = $this->EstablishmentModel->getEstablishmentByID($id);
				$str = '<h2 class="brown">' . $word . ' Image: ' . $info->name . '</h2>';
				break;
		}
		return $str;
	}	
	
	public function createImage() {
		$this->load->helper('upload');
		
		$id = filter_var($this->uri->segment(3), FILTER_SANITIZE_NUMBER_INT);
		$type = filter_var($this->uri->segment(4), FILTER_SANITIZE_STRING);
				
		if ($id && $type) {
			// holder for setup for sizing image
			$array = array();
			// check to see which type of image is being manipulated
			switch($type) {
				case 'beer':
					// load the beer model
					$this->load->model('BeerModel', '', true);
					// get the name of the image
					$arr_image = $this->BeerModel->getImageByID($id);
					// holder for the name of the image
					$image = '';
					//echo '<pre>'; print_r($arr_image); echo '</pre>';
					// check if there is an image
					//if(empty($arr_image)) {
					if(!key_exists('picture', $arr_image) || empty($arr_image['picture'])) {
						$image = 'bottle.gif';
					} else {
						$image = $arr_image['picture'];
					}
					
					// check if the uri contains resize info
					$type = $this->uri->segment(5);
					switch($type) {
						case 'mini':
							$widthMultiplier = .2;
							$heightMultiplier = .2;
							break;
						default: 
							$widthMultiplier = .5;
							$heightMultiplier = .5;
							break;
					}
					
					$array = array(
						'path' => './images/beers/'
						, 'image' => $image
						, 'alt' => $arr_image['beerName'] . ' by ' . $arr_image['name']
						, 'widthMultiplier' => $widthMultiplier
						, 'heightMultiplier' => $heightMultiplier
					);
					break;
			}
			resizeImageOnFly($array);
		} else {
			
		}
	}
	
	public function gallery() {
		// get the login boolean
		$logged = checkLogin();

		// user info for logged in user
		$userInfo = $this->session->userdata('userInfo');
		
		// create login mast text
		$this->_data['formMast'] = createHeader($logged, $userInfo);
		
		// load the event model
		$this->load->model('EventModel', '', true);
		// load the events library
		$this->load->library('events');
		
		// get the event id 
		$eventID = $this->uri->segment(3);
		
		// check if the segment is empty
		if($eventID == false) {
			$eventID = 1;
		}
		
		$info = $this->events->showGallery($eventID);
		//echo '<pre>'; print_r($array); echo '</pre>'; exit;
		$this->_data['events'] = $info['str'];
		$this->_data['head'] = $info['head'];
		
		// set the page seo information
		$this->_data['seo'] = array_slice($info, 0, 3);		
		
		// array of views and data
		$arr_load = array(
			'pages' => array('noproto' => true, 'gallery' => true, 'footerFrontEnd' => true)
			, 'data' => $this->_data
		);
		$arr_load = array(
			'pages' => array(
				'headerFrontEnd' => true
				, 'formMast' => true
				, 'navigation' => true
				, 'gallery' => true
				, 'footerFrontEnd' => true
			)
			, 'data' => $this->_data
		);
		// load all parts for the view
		$this->doLoad($arr_load);
	}
	
	/**
    * About us section of the main site.  This is static information that can be changed wihtin the aboutUs view
    * 
    * @access public
    * @return void
    */
    public function aboutUs() {
        $this->layout->render('page/aboutUs.php', $this->_data, 'two_column.php');
    }
	
    /**
    * Contact us form.  A user can send a comment to the team.  Captcha on the page is deter bots.
    * 
    * @access public
    * @return void
    */
	public function contactUs() {
		$this->load->library(array('form_validation', 'mail'));
		$this->load->helper('captcha_pi');
		$this->load->model('CaptchaModel', '', true);
		
		if ($this->form_validation->run('contactUs')) {		
        	$data = array(
				'action' => 'contactUs',
				'to' => EMAIL_CONTACT_US,
				'name' => filter_var($this->input->post('name'), FILTER_SANITIZE_STRING),
				'email' => filter_var($this->input->post('email'), FILTER_SANITIZE_EMAIL),
				'comments' => filter_var($this->input->post('comments'), FILTER_SANITIZE_STRING)
			);
			$this->mail->sendFormMail($data);
			
            header('Location:' . base_url() . 'page/contactUsSuccess/' . urlencode(base64_encode('beer')));
            exit;
		}
        else {
			$this->layout->render('page/contactUs.php', $this->_data, 'two_column.php');
		} 
	}

	public function contactUsSuccess() {
		$message = filter_var($this->uri->segment(3), FILTER_SANITIZE_STRING);
		if (!empty($message)) {
			if ('beer' == base64_decode(urldecode($message))) {
				$this->layout->render('page/contact_us_success.php', $this->_data, 'two_column.php');
			}
			else {
				header('Location:' . base_url());
			}
		}
		else {
			header('Location:' . base_url());
			exit;
		}
	}
	
	public function updateInfo() {
		if (!$this->_data['logged']) {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}

		$this->_data['updateType'] = $this->uri->segment(3);
		$this->_data['id'] = filter_var($this->uri->segment(4), FILTER_SANITIZE_NUMBER_INT);		
		if (!$this->_data['updateType'] || !$this->_data['id'] || !$this->_verify_update_type($this->_data['updateType'])) {
			header('Location: ' . base_url());
			exit;
        }

		$itemInfo = '';
		switch($this->_data['updateType']) {
			case 'beer':
				$this->load->model('BeerModel', '', true);
				$itemInfo = $this->BeerModel->getBeerByID($this->_data['id']);
				break;
			case 'establishment':
				$this->load->model('EstablishmentModel', '', true);
				$itemInfo = $this->EstablishmentModel->getEstablishmentByID($this->_data['id']);
				break;
		}
		$this->_data['name'] = isset($itemInfo->beerName) ? $itemInfo->beerName : $itemInfo->name;			

		$this->load->library('form_validation');
		if (!$this->form_validation->run('updateInfo')) {
			$this->load->model('ChangetypeModel', '', true);
			$this->_data['change_types'] = $this->ChangetypeModel->selectForDropdown();	
		}
        else {
			$data = array(
				'action' => 'updateInfo',
				'to' => EMAIL_CONTACT_US,
				'itemInfo' => $itemInfo,
				'userInfo' => $this->_data['user_info'],
				'change' => $this->ChangetypeModel->getByID($this->input->post('change')),
				'comments' => $this->input->post('comments')
			);
			$this->load->library('mail');
			$this->mail->sendFormMail($data);
			
			$this->_data['display'] = true;			
		}
		$this->layout->render('page/updateInfo.php', $this->_data, 'two_column.php');
	}

	private function _verify_update_type($update_type) {
		$update_types = ['beer', 'establishment'];
		if (!in_array($update_type, $update_types)) {
			return false;
		}
		return true;
	}
	
	public function changeTypeExists($changeTypeID) {
		$this->load->model('ChangetypeModel', '', true);
		$boolean = $this->ChangetypeModel->checkExistsByID($changeTypeID);
		if (!$boolean) {
			$this->form_validation->set_message('changeTypeExists', 'The %s you have chosen doesn\'t exists.  Please choose another.');
		}
		return $boolean;
	}
    
    /**
    * Privacy policy for the website.  Includes downloads in plain text and pdf style.
    * 
    * @access public
    * @return void
    */
    public function privacy() {
		$this->layout->render('page/privacy.php', $this->_data, 'two_column.php');
    }
    
    /**
    * End user agreement.  Includes downloads in plain text and pdf style.
    * 
    * @access public
    * @return void
    */
    public function agreement() {
        $this->layout->render('page/agreement.php', $this->_data, 'two_column.php');
    }

    public function blogRSS() {
		try {
			$this->load->model('WordpressModel', '', true);
			$this->_data['xml'] = $this->WordpressModel->getRss();
            echo $this->load->view('wp/rss', $this->_data, true);
		}
        catch(Exception $e) {
			echo $e->getMessage();
		}
	}
}
?>
