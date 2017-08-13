<?php
class Brewery extends CI_Controller
{
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
        $this->_data['js'] = $this->jscript->loadRequired();
	}
	
	public function info() {		
		$this->_data['establishmentID'] = filter_var($this->uri->segment(3), FILTER_SANITIZE_NUMBER_INT);
		
		if ($this->_data['establishmentID']) {
			$this->load->model('BreweriesModel', '', true);
			$this->load->model('BeerModel', '', true);
			
			$this->_data['establishment'] = $this->BreweriesModel->getBreweryInfoByID($this->_data['establishmentID']);
			if (!empty($this->_data['establishment'])) {
				$this->load->library('image');
				$this->_data['establishment_image'] = $this->image->get_image($this->_data['establishment'], 'establishment', $this->_data['logged'], false, ['img-responsive']);
				$this->_data['beers'] = $this->BreweriesModel->getAllRatingsForBreweryByID($this->_data['establishmentID']);
				if (!empty($this->_data['beers'])) {
					$this->_data['beer_active'] = $this->BreweriesModel->getAllActiveRatingsForBreweryByID($this->_data['establishmentID']);
					foreach ($this->_data['beer_active'] as $beer) {
						$beer->width = 30;
						$beer->height = 70;
						$beer->image = $this->image->get_image($beer, 'beer');

						if ($beer->reviews > 0) {
							$beer->average_cost = $this->BeerModel->getAvgCostPerPackage($beer->id);
						}
					}
					$this->_data['beer_retired'] = $this->BreweriesModel->getAllRetiredRatingsForBreweryByID($this->_data['establishmentID']);
					foreach ($this->_data['beer_retired'] as $beer) {
						$beer->width = 30;
						$beer->height = 70;
						$beer->image = $this->image->get_image($beer, 'beer');

						if ($beer->reviews > 0) {
							$beer->average_cost = $this->BeerModel->getAvgCostPerPackage($beer->id);
						}
					}
					$this->_data['beer_average'] = $this->BreweriesModel->getTotalEachBeer($this->_data['establishmentID']);
					$this->_data['beer_distinct_count'] = $this->BreweriesModel->getDistinctBeerCount($this->_data['establishmentID']);
					$this->_data['overalAverageCost'] = $this->BreweriesModel->getOverallAverageCostOfBeerByEstablishmentID($this->_data['establishmentID']);
					$this->_data['haveAnother'] = $this->BreweriesModel->getHaveAnotherPercent($this->_data['establishmentID']);
				}
				$this->load->library(array('phone', 'pricing'));
			}
			$this->_data['highestRatedBreweries'] = $this->BreweriesModel->getHighestRatedBreweries();
			$this->_data['averageCostPerBreweryLow'] = $this->BreweriesModel->getOverallAverageCostOfBeer('asc');
			$this->_data['averageCostPerBreweryHigh'] = $this->BreweriesModel->getOverallAverageCostOfBeer('desc');
			
			$this->layout->render('brewery/info.php', $this->_data, 'two_column.php');
		}
		else {
			$this->load->model('EstablishmentModel', '', true);
			$this->_data['highestRatedEstablishments'] = $this->EstablishmentModel->getHighestRatedEstablishments();
			$this->_data['newest'] = $this->EstablishmentModel->getNewestAdditions();
			$this->_data['reviews'] = $this->EstablishmentModel->getRecentReviews();

			$this->load->model('StateModel', '', true);
			$this->_data['states'] = $this->StateModel->getAllStates();
			
			$this->layout->render('brewery/info_generic.php', $this->_data, 'two_column.php');
		}		
	}
	
	public function hop() {
		$this->load->model('BreweriesModel', '', true);
		$this->_data['allBreweryHops'] = $this->BreweriesModel->getAllBrewreyHops(NUMBER_HOPS_TO_SHOW);
		// load the beer library
		$this->load->library('breweries');
		
		$id = filter_var($this->uri->segment(3), FILTER_SANITIZE_NUMBER_INT);		
		if (!$id) {
			$this->_data['hop'] = $this->BreweriesModel->getAllBrewreyHops(1);
			//$this->_data['seo'] = getSEO();
			$this->layout->render('brewery/hopGeneric.php', $this->_data, 'two_column.php');
		}
		else {		
			//$this->_data['breweryhop'] = $this->breweries->showBreweryHop($id);
			$this->_data['breweryhop'] = $this->BreweriesModel->getBreweryHopByID($id);
			//$this->_data['breweryHop'] = $breweryhop['str'];
			//$this->_data['hops'] = $this->breweries->showAllBreweryHops();
			//$this->_data['seo'] = array_slice($breweryhop, 0, 3);
			$this->layout->render('brewery/hop.php', $this->_data, 'two_column.php');
		}		
	}
	
	public function city() {
		$uri = $this->uri->uri_string();
		$uri = str_replace('brewery', 'establishment', $uri);
		header('Location: ' . base_url() . $uri);
		exit;
		// get the login boolean
		$logged = checkLogin();		
		// user info for logged in user
		$userInfo = $this->session->userdata('userInfo');		
		// create login mast text
		$this->_data['formMast'] = createHeader($logged, $userInfo);
		
		// load the brewery model
		$this->load->model('BreweriesModel', '', true);
		// load the beer library
		$this->load->library('breweries');
		
		// get the id of the state passed
		$state = $this->uri->segment(3);
		// get the name of the city passed
		$city = $this->uri->segment(4);
		
		// check if the city is empty
		// this should not happen
		if($state === false || $city === false) {
			// set the output for the screen
			$this->_data['leftCol'] = '
				<h2 class="brown">Breweries in ' . $city . '</h2>
				<p>There are no records for the city requested.</p>			
			';			
			// set the page information
			$this->_data['seo'] = getSEO();
		} else {		
			// replace values in the city
			$city = str_replace(array('_', '+'), ' ', $city);
			// get the information for the particular brewery hop
			$breweryCities = $this->breweries->showBreweriesCity($state, $city);
			// get the output for the screen
			$this->_data['leftCol'] = $breweryCities['str'];
			
			// set the page seo information
			$this->_data['seo'] = array_slice($breweryCities, 0, 3);
		}
		
		// get the information ready for display
		$arr_load = array(
			'pages' => array(				
				'headerFrontEnd' => true
				, 'formMast' => true
				, 'navigation_front' => true
				, 'city' => true
				, 'footerFrontEnd' => true
			)
			, 'data' => $this->_data
		);			
		// load all parts for the view
		$this->doLoad($arr_load);
	}
	
	public function state() {
		$uri = $this->uri->uri_string();
		$uri = str_replace('brewery', 'establishment', $uri);
		header('Location: ' . base_url() . $uri);
		exit;
		// load the brewery model
		$this->load->model('BreweriesModel', '', true);
		// load the beer library
		$this->load->library('breweries');
		
		// get the id of the state passed
		$state = $this->uri->segment(3);
		
		// check if the city is empty
		// this should not happen
		if($state === false) {
			// set the output for the screen
			$this->_data['output'] = '
				<h2>Breweries</h2>
				<p>There are no records for the state requested.</p>			
			';			
			// set the page information
			$this->_data['seo'] = getSEO();
		} else {		
			// get the information for the particular state
			$breweryStates = $this->breweries->showBreweriesState($state);			
			
			// set the page seo information
			if(is_array($breweryStates)) {
				$this->_data['seo'] = array_slice($breweryStates, 0, 3);
				// set the output for the screen
				$this->_data['output'] = $breweryStates['str'];
			} else {
				// set the page information
				$this->_data['seo'] = getSEO();
				// load the state model
				$this->load->model('StateModel', '', true);
				// get the name of the state
				$array = $this->StateModel->getStateByID($state);
				// set the output for the screen
				$this->_data['output'] = '<p>There are currently no establishments found in ' . $array['stateFull'] . '.</p>';
			}
		}
		
		// get the information ready for display
		$arr_load = array(
			'pages' => array('header' => true, 'state' => true, 'footerFrontEnd' => true)
			, 'data' => $this->_data
		);				
		// load all parts for the view
		$this->doLoad($arr_load);
	}
	
	public function addEstablishment() {
		if ($this->_data['logged']) {
			$this->load->model('BreweriesModel', '', true);
			$this->load->model('BeerModel', '', true);
			$this->load->model('StateModel', '', true);
			
			$this->_data['reviewCount'] = $this->BeerModel->getBeerReviewCount($this->_data['user_info']['id']);
			if ($this->_data['reviewCount'] >= MIN_REVIEW_COUNT_FOR_ESTABLISHMENT) {								
				$this->load->library('form_validation');
				$this->load->helper(array('js', 'form'));
				
				if (!$this->form_validation->run('addEstablishment')) {
					$this->_data['categories'] = $this->BreweriesModel->getAllCategoriesForDropDown();
					$this->_data['states'] = $this->StateModel->getAllForDropDown();
					array_push($this->_data['js'], 'establishment_add.js');
				}
				else {
					$this->load->library('slug');

					$data = array(
						'categoryID' => $this->input->post('category'),
						'name' => $this->input->post('name'),
						'address' => $this->input->post('address'),
						'city' => $this->input->post('city'),
						'state' => $this->input->post('state'),
						'zip' => $this->input->post('zip'),
						'phone' => $this->input->post('txt_phone'),
						'url' => $this->input->post('txt_url'),
						'twitter' => $this->input->post('txt_twitter'),
						'userID' => $this->_data['user_info']['id'],
						'slug' => $this->slug->url_format($this->input->post('name'))
					);
					$id = $this->BreweriesModel->createEstablishment($data);

                    if (SEND_NEWBREWERY_NOTICE) {
						$this->load->library('mail');
						$breweryInfo = array(
							'action' => 'newEstablishment',
							'establishmentID' => $id,
							'userID' => $this->_data['user_info']['id'],
							'data' => $data,
							'subject' => 'New Establishment Addition'
						);
						$this->mail->sendFormMail($breweryInfo);
					}
					header('Location: ' . base_url() . 'establishment/info/rating/' . $id . '/');
					exit;
				}	
			}
		}
		else {
			$this->load->library('url');
			$args = $this->url->swap_out_uri(array('_'), '/', substr($this->uri->uri_string(), 0));
			header('Location: ' . base_url() . 'user/login/' . $args);
			exit;
		}
		$this->layout->render('brewery/add.php', $this->_data, 'two_column.php');
	}
	
	public function categoryExists($categoryID) {
		$this->load->model('BreweriesModel', '', true);
		$rs = $this->BreweriesModel->getCategoryCheck($categoryID);
		$boolean = count($rs) > 0 ? true : false;
		if (!$boolean) {
			$this->form_validation->set_message('categoryExists', 'The %s you have chosen doesn\'t exists.  Please choose another.');
		}
		return $boolean;
	}
	
	public function alphaNumericSpace($str) {
		$boolean = (!preg_match("/^([a-z0-9\s\'&])+$/i", $str)) ? false : true;
		if (!$boolean) {
			$this->form_validation->set_message('alphaNumericSpace', '%s should only contain alpha numerical information and spaces.');
		}
		return $boolean;
	}
    
    public function dropEndSlash($url) {
        $len = strlen($url);
        $boolean = ($url[($len - 1)] == '/') ? false : true;
        if (!$boolean) {
            $this->form_validation->set_message('dropEndSlash', '%s should not end in a slash.');
        }
        return $boolean;    
    }
	
	public function stateExists($stateID) {
		$this->load->model('StateModel', '', true);
		$rs = $this->StateModel->getStateCheck($stateID);
		$boolean = count($rs) > 0 ? true : false;
		if (!$boolean) {
			$this->form_validation->set_message('stateExists', 'The %s you have chosen doesn\'t exists.  Please choose another.');
		}
		return $boolean;
	}
}
?>