<?php
class Establishment extends CI_Controller {
	public function __construct() {
		parent::__construct();
        $this->load->helper(['users', 'admin', 'js', 'form', 'url']);
        $this->load->library(['session', 'layout', 'visitor', 'seo', 'quote', 'jscript']);
        $this->load->model('EstablishmentModel', '', true);

		$this->_data['logged'] = $this->visitor->checkLogin();        
        $this->_data['user_info'] = $this->session->userdata('userInfo');
        $this->_data['seo'] = $this->seo->getSEO();
        $this->_data['quote'] = $this->quote->getFooterQuote();
        $this->_data['js'] = $this->jscript->loadRequired();
	}
	
	public function info() {
		$this->load->model('EstablishmentModel', '', true);
		$this->load->model('StateModel', '', true);
		$this->load->model('BreweriesModel', '', true);
		$this->load->model('CategoryModel', '', true);
		$this->load->library('phone');
		
		$numSegments = $this->uri->total_segments();
		
		$action = filter_var($this->uri->segment(3), FILTER_SANITIZE_STRING);
		switch ($action) {
			case 'category':
				$this->_info_category();
				//$this->_data['seo'] = array_slice($array, 0, 3);
				break;
			case 'rating':
				$this->_info_rating();
				break;
			case false:
			default:
				header('Location: ' . base_url() . 'brewery/info');
				exit;
				break;
		}
	}

	private function _info_category() {
		$this->_data['categoryID'] = filter_var($this->uri->segment(4), FILTER_SANITIZE_NUMBER_INT);
		$this->_data['stateID'] = filter_var($this->uri->segment(5), FILTER_SANITIZE_NUMBER_INT);

		if (!$this->_data['stateID'] || !$this->_data['categoryID']) {
			header('Location: ' . base_url() . 'brewery/info');
			exit;
		}

		$this->_data['establishments'] = $this->EstablishmentModel->getEstablishmentsByCategoryState($this->_data['stateID'], $this->_data['categoryID']);
		if (count($this->_data['establishments']) > 0) {
			$seo = new stdClass();
			$seo->category = '';
			$seo->stateFull = '';
			
			foreach ($this->_data['establishments'] as $establishment) {
				$rating = $this->EstablishmentModel->getEstablishmentRating($establishment->establishmentID);
				$establishment->averageRating = !empty($rating->totalRatings) ? $rating->averageRating : 'N/A';
				$establishment->totalRatings = !empty($rating->totalRatings) ? number_format($rating->totalRatings) : 'N/A';
				$establishment->totalRatings = number_format($rating->totalRatings);

				if (in_array($establishment->categoryID, array(1, 4))) {
					$average = $this->BreweriesModel->getTotalEachBeer($establishment->establishmentID);
					$calc_average = $average->totalBeers > 0 ? $average->totalPoints / $average->totalBeers : 0;
					$establishment->beerAverageRating = $calc_average > 0 ? number_format(round($calc_average, 1), 1) : 'N/A';
					$establishment->beerTotalRatings = number_format($average->totalBeers);
				}

				if (empty($seo->category) && empty($seo->stateFull)) {
					$seo->category = $establishment->category;
					$seo->stateFull = $establishment->stateFull;
				}
			}
			$this->_data['seo'] = $this->seo->getDynamicSEO($seo, 'establishment category');
		}

		$this->_data['stateInfo'] = $this->StateModel->getStateByID($this->_data['stateID']);
		$this->_data['category'] = $this->CategoryModel->getCategoryInfoByID($this->_data['categoryID']);
		
		$this->layout->render('establishment/info/category.php', $this->_data, 'two_column.php');
	}

	private function _info_rating() {
		$this->_data['establishmentID'] = filter_var($this->uri->segment(4), FILTER_SANITIZE_NUMBER_INT);                
        if (!$this->_data['establishmentID']) {
			header('Location: ' . base_url() . 'brewery/info');
			exit;
		}
		
		$this->_data['establishment'] = $this->EstablishmentModel->getEstablishmentInfoByID($this->_data['establishmentID']);
		if (!empty($this->_data['establishment'])) {
			$this->_data['rating_establishment'] = $this->EstablishmentModel->getRatingsForEstablishmentByID($this->_data['establishmentID']);
			$this->_data['twobeerdudes'] = $this->EstablishmentModel->getRatingsForEstablishmentByIDTwoBeerDudes($this->_data['establishmentID']);
			$this->_data['beers'] = $this->BreweriesModel->getAllRatingsForBreweryByID($this->_data['establishmentID']);
			$this->load->library('image');
			//$this->_data['establishment_image'] = $this->image->get_image($this->_data['establishment'], 'establishment', $this->_data['logged'], true);
			$this->_data['establishment_image'] = $this->image->get_image($this->_data['establishment'], 'establishment', $this->_data['logged'], false, ['img-responsive']);
			$this->_data['ratings'] = $this->EstablishmentModel->getEstblishmentRatingsByID($this->_data['establishmentID']);

			if (!empty($this->_data['beers'])) {
				$this->_data['beer_average'] = $this->BreweriesModel->getTotalEachBeer($this->_data['establishmentID']);
				$this->_data['beer_distinct_count'] = $this->BreweriesModel->getDistinctBeerCount($this->_data['establishmentID']);
				$this->_data['beer_average_cost'] = $this->BreweriesModel->getAvgCostPerPackage($this->_data['establishmentID']);
				$this->_data['overalAverageCost'] = $this->BreweriesModel->getOverallAverageCostOfBeerByEstablishmentID($this->_data['establishmentID']);
				$this->_data['haveAnother'] = $this->BreweriesModel->getHaveAnotherPercent($this->_data['establishmentID']);
			}
			$this->load->library(array('phone', 'pricing'));

			$this->_data['seo'] = $this->seo->getDynamicSEO($this->_data['establishment'], 'establishment rating');
		}
		
		$this->layout->render('establishment/info/rating.php', $this->_data, 'two_column.php');	
	}
	
	public function city() {		
		$this->load->model('EstablishmentModel', '', true);
		$this->load->model('StateModel', '', true);
		$this->load->library('phone');
		
		$this->_data['stateID'] = filter_var($this->uri->segment(3), FILTER_SANITIZE_NUMBER_INT);
		$this->_data['city'] = filter_var(urldecode($this->uri->segment(4)), FILTER_SANITIZE_STRING);

		$this->_data['stateInfo'] = $this->StateModel->getStateByID($this->_data['stateID']);
		$this->_data['establishments'] = $this->EstablishmentModel->getEstablishmentsByCity($this->_data['stateID'], $this->_data['city']);

		if (count($this->_data['establishments']) > 0) {
			foreach ($this->_data['establishments'] as $establishment) {
				$rating = $this->EstablishmentModel->getEstablishmentRating($establishment->establishmentID);
				if (!empty($rating)) {
					$establishment->averageRating = $rating->averageRating;
					$establishment->totalRatings = $rating->totalRatings;
				}
			}
		}

		$this->_data['stateInfo']->city = $this->_data['city'];
		$this->_data['seo'] = $this->seo->getDynamicSEO($this->_data['stateInfo'], 'establishment city');

		$this->layout->render('establishment/city.php', $this->_data, 'two_column.php');
	}
	
	public function state() {
		$this->load->model('EstablishmentModel', '', true);
		$this->load->model('StateModel', '', true);
		
		$stateID = filter_var($this->uri->segment(3), FILTER_SANITIZE_NUMBER_INT);

		$this->_data['establishments'] = $this->EstablishmentModel->getEstablishmentsByState($stateID);
		$this->_data['stateInfo'] = $this->StateModel->getStateByID($stateID);
		$this->_data['categories'] = $this->EstablishmentModel->getEstablishmentsByCategory($stateID);

		$this->_data['seo'] = $this->seo->getDynamicSEO($this->_data['stateInfo'], 'estabishment state');

		$this->layout->render('establishment/state.php', $this->_data, 'two_column.php');
	}
	
	public function createReview()
	{		
		if ($this->_data['logged']) {
			$this->load->model('EstablishmentModel', '', true);
			$this->load->model('RatingModel', '', true);
			$this->load->library('establishments');
			
			$this->_data['establishmentID'] = filter_var($this->uri->segment(3), FILTER_SANITIZE_NUMBER_INT);
			$this->_data['danger'] = false;
			
			$this->_data['reviewCount'] = $this->EstablishmentModel->getEstablishmentReviewCount($this->_data['user_info']['id']);						
			if ($this->_data['reviewCount'] >= MIN_REVIEW_COUNT_FOR_ESTABLISHMENT) {
				$this->_data['establishment'] = $this->EstablishmentModel->getEstablishmentByID($this->_data['establishmentID']);
				if (!empty($this->_data['establishment'])) {
					$this->load->library('form_validation');
					$this->load->helper(array('js', 'form'));							
					
					if (!$this->form_validation->run('addReviewEstablishment')) {
						$this->_data['rating'] = $this->EstablishmentModel->checkForRatingByUserIDEstablishmentID($this->_data['user_info']['id'], $this->_data['establishmentID']);
						//$this->_data['seo'] = array_slice($info, 0, 3);
						array_push($this->_data['js'], 'bootstrap-datepicker.js', 'bootstrap-slider.min.js', 'establishment_review.js', 'tooltip.js');
					} else {
						$establishment = $this->EstablishmentModel->getEstablishmentByID($this->_data['establishmentID']);
						$array = array(
							'establishmentID' => $this->_data['establishmentID'],
							'userID' => $this->_data['user_info']['id'],
							'dateVisited' => $this->input->post('dateVisited'),
							'drink' => $this->input->post('drink'),
	                        'service' => $this->input->post('service'),
	                        'atmosphere' => $this->input->post('atmosphere'),
	                        'pricing' => $this->input->post('pricing'),
	                        'accessibility' => $this->input->post('accessibility'),
							'comments' => $this->input->post('comments'),
							'visitAgain' => $this->input->post('visitAgain')
						);
						
						$rating = $this->EstablishmentModel->checkForRatingByUserIDEstablishmentID($this->_data['user_info']['id'], $this->_data['establishmentID']);
						$ratingID = '';
						if (empty($rating)) {
							$ratingID = $this->EstablishmentModel->createRating($array);
						} else {
							$array['id'] = $rating->id;
							$this->EstablishmentModel->updateRatingByID($array);
							$ratingID = $rating->id;
						}
						
						if (SEND_CREATION_NOTICE) {
							$this->load->library('mail');
							$ratingInfo = $this->EstablishmentModel->getEstblishmentRatingsByRatingsID($ratingID);
							$ratingInfo += array(
	                        	'action' => 'establishmentReview',
	                        	'userID' => $this->_data['user_info']['id'],
	                        	'username' => $this->_data['user_info']['username'],
	                        	'subject' => 'Establishment Review Addition');
	                        $this->mail->sendFormMail($ratingInfo);
						}
							
						header('Location: ' . base_url() . 'establishment/info/rating/' . $establishment->establishmentID . '/' . $establishment->slug_establishment);
						exit;
					}
				} else {
					header('Location: ' . base_url());
					exit;
				}
			}
		} else {
			$this->load->library('url');
			$args = $this->url->swap_out_uri(array('_'), '/', substr($this->uri->uri_string(), 0));
			header('Location: ' . base_url() . 'user/login/' . $args);
			exit;
		}
		$this->layout->render('establishment/createReview.php', $this->_data, 'two_column.php');
	}
	
	public function categoryExists($categoryID) {
		// load the user model
		$this->load->model('BreweriesModel', '', true);
		// get the brewery information
		$rs = $this->BreweriesModel->getCategoryCheck($categoryID);
		// check if it really exists
		$boolean = count($rs) > 0 ? true : false;
		// check the boolean
		if($boolean === false) {
			$this->form_validation->set_message('categoryExists', 'The %s you have chosen doesn\'t exists.  Please choose another.');
		}
		return $boolean;
	}
	
	public function alphaNumericSpace($str) {
		$boolean = (!preg_match("/^([a-z0-9\s])+$/i", $str)) ? false : true;
		
		// check the boolean
		if($boolean === false) {
			$this->form_validation->set_message('alphaNumericSpace', '%s should only contain alpha numerical information and spaces.');
		}
		return $boolean;
	}
	
	public function alphaNumericSpaceAndOthers($str) {
		$boolean = (!preg_match("/^([a-z0-9\s/./\])+$/i", $str)) ? false : true;
		
		// check the boolean
		if($boolean === false) {
			$this->form_validation->set_message('alphaNumericSpaceAndOthers', '%s should only contain alpha numerical information and spaces.');
		}
		return $boolean;
	}
	
	public function stateExists($stateID) {
		// load the user model
		$this->load->model('StateModel', '', true);
		// get the brewery information
		$rs = $this->StateModel->getStateCheck($stateID);
		// check if it really exists
		$boolean = count($rs) > 0 ? true : false;
		// check the boolean
		if($boolean === false) {
			$this->form_validation->set_message('stateExists', 'The %s you have chosen doesn\'t exists.  Please choose another.');
		}
		return $boolean;
	}
    
    public function ajax_google_map_lat_lng() {
        $establishment_id = $this->input->post('id');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        
        if (!empty($establishment_id) && $latitude && $longitude) {
            $this->load->model('EstablishmentModel', '', true);
            
            $array = array(
				'id' => $establishment_id,
				'lat' => $latitude,
				'long' => $longitude
			);
			$this->EstablishmentModel->setLatitudeAndLongitude($array);
        }
    }
    
    public function google_map_processor() {
        $establishment_id = $this->input->post('e_id');
        $address = $this->input->post('address');
        if ($establishment_id && $address) {
            $this->_data['establishment_id'] = $establishment_id;
            $this->_data['address'] = $address;
            $this->load->view('establishment/google_map_processor', $this->_data);            
        }        
    }

    private function _processGoogleCoords(&$establishment) {
    	if (empty($establishment->latitude) || empty($establishment->longitude)) {
			$this->load->library('google');
			$array = array(
				'id' => $establishment->establishmentID
				, 'address' => $establishment->address . ', ' . $establishment->city . ', ' . $establishment->stateFull
			);
			$this->google->init($array);
			$this->google->determineLatitudeAndLongitudeViaGoogle();
			echo '<pre>' . print_r($this->google, true); exit;
			$establishment->latitude = $this->google->getLatitude();
			$establishment->longitude = $this->google->getLongitude();
		}
    }
	
	public function googleMaps() {
		$establishmentID = filter_var($this->uri->segment(3), FILTER_SANITIZE_NUMBER_INT);
		if (!$establishmentID) {
			header('Location: ' . base_url());
			exit;
		}

		$this->load->model('EstablishmentModel', '', true);
		$establishment = $this->_data['establishment'] = $this->EstablishmentModel->getEstablishmentByID($establishmentID);
		if (!empty($establishment)) {
			$this->_processGoogleCoords($establishment);	            
            $this->_data['closeBy'] = $this->EstablishmentModel->determineDistance($establishment);
        }
        $this->load->library('phone');

        $google_js = [
        	'http://maps.google.com/maps/api/js?sensor=false',
        	'http://www.google.com/jsapi?key=' . GOOGLEAPI
        ];
        array_push($this->_data['js'], array('full_uri' => $google_js), 'google_maps.js');

		$this->layout->render('establishment/googleMaps.php', $this->_data, 'two_column.php');
	}
}
?>