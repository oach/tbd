<?php
class Beer extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
        $this->load->helper(['users', 'admin', 'js', 'form', 'url']);
        $this->load->library(['session', 'layout', 'visitor', 'seo', 'quote', 'jscript']);
        $this->load->model('BeerModel', '', true);

		$this->_data['logged'] = $this->visitor->checkLogin();        
        $this->_data['user_info'] = $this->session->userdata('userInfo');
        $this->_data['seo'] = $this->seo->getSEO();
        $this->_data['quote'] = $this->quote->getFooterQuote();
        $this->_data['js'] = $this->jscript->loadRequired();
        $this->_data['search_option_value'] = 'beer';
	}
	
	public function reviews()
	{
		$offset = filter_var($this->uri->segment(4), FILTER_SANITIZE_NUMBER_INT);
		if (empty($offset) || !ctype_digit($offset)) {
			$offset = 0;
		}

		$this->load->model('RatingModel', '', true);
		$this->_data['totalResults'] = $this->RatingModel->getTotalRatingCount();
		$this->_data['items'] = $this->RatingModel->getAllPagination($offset);
		$this->_data['totalNumberBeers'] = $this->BeerModel->getTotalBeersInDB();

		$this->load->library(['pagination', 'rating']);

		$this->layout->render('beer/review_generic.php', $this->_data, 'two_column.php');
	}

	public function review()
	{
		$id = filter_var($this->uri->segment(3), FILTER_SANITIZE_NUMBER_INT);

		$this->_data['beer_info'] = $this->BeerModel->getBeerByID($id);
		//$this->_data['beer_image'] = $this->_get_beer_image($this->_data['beer_info'], true);
		$this->_data['beer_image'] = $this->_get_beer_image($this->_data['beer_info']);
		$this->_data['beers'] = $this->BeerModel->getBeerRatingsByID($id);
		$this->_data['twoBeerDudes'] = $this->BeerModel->tastedTwoBeerDudes($id);
		$this->_data['styleID'] = $this->BeerModel->getStyleIDByBeerID($id);
		$this->_data['similarBeers'] = $this->BeerModel->similarBeerByBeerIDAndStyleID($id, $this->_data['beer_info']->styleID);

		$this->load->model('SwapModel', '', true);
		$this->_data['swaps'] = $this->SwapModel->getInsAndOutsByBeerID($id);

		if (count($this->_data['beers']) > 0) {
			$this->_get_review_data($id);
		}

		$this->_data['seo'] = $this->seo->getDynamicSEO($this->_data['beer_info'], 'beer review');
		array_push($this->_data['js'], 'beer_review.js', 'tooltip.js');
		$this->load->library(array('rating', 'srm', 'url'));

		$this->layout->render('beer/review.php', $this->_data, 'two_column.php');
	}

	private function _get_review_data($id) {
		$ratingInfo = $this->BeerModel->getBeerRating($id);
		$this->_data['ratingAverage'] =  number_format(round($ratingInfo->averagerating, 1), 1);
        $this->_data['ratingTotalTimes'] = $ratingInfo->timesrated;
        
        $this->_data['packageCount'] = $this->BeerModel->getPackageCount($id);
        $this->_data['avgCost'] = $this->BeerModel->getAvgCostPerPackage($id);
        //$have = $haveAnother = $this->ci->BeerModel->getHaveAnotherPercent($id);
        $this->_data['haveAnother'] = $this->BeerModel->getHaveAnotherPercent($id);
        $this->_data['trending'] = $this->BeerModel->getTrendByBeerID($id);
	}

	private function _get_beer_image($beer_info, $background = false) {
		$this->load->library('image');
		$config = [
		    'picture' => $beer_info->picture,
		    'id' => $beer_info->id,
		    'alt' => $beer_info->beerName . ' - ' . $beer_info->name,
		    'class' => ['img-responsive']
		];
		if (isset($beer_info->width)) {
			$config['width'] = $beer_info->width;
		}
		if (isset($beer_info->height)) {
			$config['height'] = $beer_info->height;
		}
		if ($background) {
			return $this->image->checkForBackgroundImage($config, $this->_data['logged']);
		}
		return $this->image->checkForAnImage($config, $this->_data['logged']);
	}
	
	public function createReview() {
		if ($this->_data['logged']) {
			$this->load->model('BeerModel', '', true);
			$this->load->model('RatingModel', '', true);
			$this->load->library('form_validation');

			$this->_data['type'] = filter_var($this->uri->segment(4), FILTER_SANITIZE_STRING); // short review					
			//if ('short' == $this->_data['type']) {
				/*if (!$this->form_validation->run('addShortReviewBeer') == false) {				
					$info = $this->beers->showCreateReview($id, false, $type);
					$this->_data['leftCol'] = $info['str'];
					
					// set the page seo information
					$this->_data['seo'] = array_slice($info, 0, 3);
					// get the information ready for display
					$arr_load = array(
						'pages' => array(				
							'headerFrontEnd' => true
							, 'formMast' => true
							, 'navigation' => true
							, 'createReview' => true
							, 'footerFrontEnd' => true
						)
						, 'data' => $this->_data
					);			
					// load all parts for the view
					$this->doLoad($arr_load);
				} else {
					// successfull information, so store it
					// get the information about the beer
					$beer = $this->BeerModel->getBeerByID($id);
					// create the data array to store the information
					$array = array(
						'establishmentID' => $beer['establishmentID']
						, 'beerID' => $id
						, 'userID' => $userInfo['id']
						, 'dateTasted' => trim($_POST['txt_dateTasted'])
						, 'aroma' => trim($_POST['aroma'])
						, 'taste' => trim($_POST['taste'])
						, 'look' => trim($_POST['look'])
						, 'drinkability' => trim($_POST['drinkability'])
						, 'haveAnother' => trim($_POST['slt_haveAnother'])
					);
					
					// query the ratings table
					$rating = $this->RatingModel->checkForRatingByUserIDBeerID($this->_data['user_info']['id'], $id);
					// holder for the rating id
					$ratingID = '';
					// store that information
					if(empty($rating)) {
						// create a new rating
						$ratingID = $this->RatingModel->createShortRating($array);
					} else {
						// add to the array of values
						$array['id'] = $rating['id'];
						// update a rating
						$this->RatingModel->updateShortRatingByID($array);
						// rating id
						$ratingID = $rating['id'];
					}
					
					// check if creation notice is required
					if(SEND_CREATION_NOTICE === true) {
						// include the mail helper
						$this->load->helper('email');
						// get the information about the beer rating
						$ratingInfo = $this->RatingModel->getRatingByID($ratingID);
						// create the configuration array
						$ratingInfo += array('action' => 'shortbeer', 'userID' => $userInfo['id'], 'username' => $userInfo['username'], 'subject' => 'Short Beer Review Addition');
						// send out an email to the admins
						sendFormMail($ratingInfo);
					}
						
					// take them to the page for the beer
					header('Location: ' . base_url() . 'beer/review/' . $id . '/short');
					exit;
				}*/
			/*}
			else {*/
				$id = filter_var($this->uri->segment(3), FILTER_SANITIZE_NUMBER_INT);
				if (!$this->form_validation->run('addReviewBeer')) {
					if (!$this->input->post()) {
						$this->_data['form_data'] = $this->RatingModel->checkForRatingByUserIDBeerID($this->_data['user_info']['id'], $id);
					}
					
					$this->load->model('PackageModel', '', true);
					$this->_data['packages'] = $this->PackageModel->getAllForDropDown();

					$this->_data['beer'] = $this->BeerModel->getBeerByID($id);
					$this->_data['beer_image'] = $this->_get_beer_image($this->_data['beer'], true);

					//echo '<pre>' . print_r($this->_data, true); exit;

					array_push($this->_data['js'], 'bootstrap-datepicker.js', 'bootstrap-slider.min.js', 'beer_review_create.js');
					$this->layout->render('beer/createReview.php', $this->_data, 'two_column.php');
				}
				else {
					$beer = $this->BeerModel->getBeerByID($id);
					
					$array = array(
						'establishmentID' => $beer->establishmentID,
						'beerID' => $id,
						'userID' => $this->_data['user_info']['id'],
						'packageID' => $this->input->post('package'),
						'dateTasted' => $this->input->post('dateTasted'),
						'color' => $this->input->post('color'),
						'aroma' => $this->input->post('aroma'),
						'taste' => $this->input->post('taste'),
						'look' => $this->input->post('look'),
						'mouthfeel' => $this->input->post('mouthfeel'),
						'overall' => $this->input->post('overall'),
						'comments' => $this->input->post('comments'),
						'haveAnother' => $this->input->post('haveAnother'),
						'price' => $this->input->post('price')
					);
					
					$rating = $this->RatingModel->checkForRatingByUserIDBeerID($this->_data['user_info']['id'], $id);
					if (empty($rating)) {
						$ratingID = $this->RatingModel->createRating($array);
					}
					else {
						$array['id'] = $ratingID = $rating->id;
						$this->RatingModel->updateRatingByID($array);
					}
					
					if (SEND_CREATION_NOTICE) {
						$ratingInfo = $this->RatingModel->getRatingByID($ratingID, 'array');
						$ratingInfo += array(
							'action' => 'beer',
							'userID' => $this->_data['user_info']['id'],
							'username' => $this->_data['user_info']['username'],
							'subject' => 'Beer Review Addition');

						$this->load->library('mail');
						$this->mail->sendFormMail($ratingInfo);
					}
						
					header('Location: ' . base_url() . 'beer/review/' . $id);
					exit;
				}	
			//}
		}
		else {
			$this->load->library('url');
			header('Location: ' . base_url() . $this->url->swap_out_uri(array('_'), '/', substr($this->uri->uri_string(), 1)));
			exit;
		}
	}
	
	public function style() {
		$this->load->model('BeerModel', '', true);
		
		$this->_data['id'] = filter_var($this->uri->segment(3), FILTER_SANITIZE_NUMBER_INT);
		if (!$this->_data['id'] || !is_numeric($this->_data['id'])) {
			$this->_data['styles'] = $this->BeerModel->getAllBeerStyles();
		}
		else {
			$offset = filter_var($this->uri->segment(4), FILTER_SANITIZE_NUMBER_INT);
			if(empty($offset) || !ctype_digit($offset)) {
				$offset = 0;
			}
			
			$this->_data['totalBeers'] = $this->BeerModel->getBeerByStyleCount($this->_data['id']);
			$this->_data['records'] = [];
			if ($this->_data['totalBeers'] > 0) {
				$this->_data['records'] = $this->BeerModel->getBeerStyleByID($this->_data['id'], $offset);
				foreach ($this->_data['records'] as $record) {
					$record->width = 30;
					$record->height = 70;
					$record->image = $this->_get_beer_image($record);
				}
			}

			$this->load->model('StyleModel', '', true);
			$this->_data['style_info'] = $this->StyleModel->getStyleByIDAllInfo($this->_data['id']);

			$this->_data['similarBeers'] = $this->BeerModel->similarBeerByStyleID($this->_data['id']);
			
			$this->_data['seo'] = $this->seo->getDynamicSEO($this->_data['style_info'], 'beer style');
			
			$this->load->library('pagination');

			array_push($this->_data['js'], 'tooltip.js');
		}
		$this->load->library(['rating']);
		$this->layout->render('beer/style.php', $this->_data, 'two_column.php');
	}
	
	public function ratingSystem() {	
		$this->load->model('RatingSystemModel', '', true);
		
		$this->_data['rating_system'] = $this->RatingSystemModel->getRatingSystem();
		$this->load->library(['rating']);
		$this->layout->render('beer/ratingSystem.php', $this->_data, 'two_column.php');
	}
	
	public function srm() {		
		$this->_data['heading'] = 'US Beer Color - Standard Reference Method (SRM)';
		$this->layout->render('beer/srm.php', $this->_data, 'two_column.php');
	}
	
	public function swaps() {
        if ($this->_data['logged']) {
            $this->load->model('BeerModel', '', true);
			$this->load->model('SwapModel', '', true);
			
			$types = ['ins', 'outs'];
			$this->_data['type'] = filter_var($this->uri->segment(3), FILTER_SANITIZE_STRING);
			$this->_data['beerID'] = filter_var($this->uri->segment(4), FILTER_SANITIZE_NUMBER_INT);

			if (!$this->_data['type'] || !in_array($this->_data['type'], $types) || !$this->_data['beerID']) {
				$this->_data['error'] = true;
			}
			else {
				$this->_data['error'] = false;
				
				$this->_data['numSwaps'] = $this->SwapModel->{'numberSwap' . ucfirst($this->_data['type']) . 'ByBeerID'}($this->_data['beerID']);
				$this->_data['swaps'] = $this->SwapModel->{'getSwap' . ucfirst($this->_data['type']) . 'ByBeerID'}($this->_data['beerID']);
				$this->_data['beer'] = $this->BeerModel->getBeerByID($this->_data['beerID']);
				$this->_data['beer']->width = 45;
				$this->_data['beer']->height = 105;
				$this->_data['beer_image'] = $this->_get_beer_image($this->_data['beer']);
			}
			$this->layout->render('beer/swaps.php', $this->_data, 'two_column.php');
		}
		else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}		
	}
	
	public function validMysqlDate($date) {
		$error = 'The %s is not valid. YYYY-MM-DD is the correct form of dates.';
		if (!strstr($date, '-')) {
			$this->form_validation->set_message('validMysqlDate', $error);
			return false;
		}
		
		$parts = explode('-', $date);
		if (count($parts) != 3) {
			$this->form_validation->set_message('validMysqlDate', $error);
			return false;
		}
		
		foreach ($parts as $val) {
			if (!is_numeric($val)) {
				$this->form_validation->set_message('validMysqlDate', $error);
				return false;
			}
		}
		return true;
	}
	
	public function filterWords($comments) {
		$error = 'The %s has some potentially bad words in it.  Please choose your words wisely and appropriately.';
		$badWords = file('/home/twobeerdudes/www/www/list/badWords.txt', FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
		foreach ($badWords as $badWord) {
			if (preg_match('/\b' . $badWord . '\b/i', $comments)) {
				$this->form_validation->set_message('filterWords', $error);
				return false;
			}			
		}
		return true;
	}
	
	public function decimalNumber($decimal) {
		$error = 'The %s is not a valid price.';
		if (!strstr($decimal, '.')) {
			$this->form_validation->set_message('decimalNumber', $error . 1);
			return false;
		}
		
		$parts = explode('.', $decimal);
		if (count($parts) != 2) {
			$this->form_validation->set_message('decimalNumber', $error . 2);
			return false;
		}
		
		for ($i = 0; $i < count($parts); $i++) {
			if (!is_numeric($parts[$i])) {
				$this->form_validation->set_message('decimalNumber', $error . 3);
				return false;
			}
			
			if ($i == 1 && strlen($parts[$i]) != 2) {
				$this->form_validation->set_message('decimalNumber', $error . 4);
				return false;
			}
		}
		return true;
	}
	
	public function integerBetween($integer) {
		$range = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
		if (!in_array($integer, $range)) { 
			$this->form_validation->set_message('integerBetween', '% has to be an integer between 1 and 10');
			return false;
		}
		return true;
	}
	
	public function addBeer() {
		if($this->_data['logged']) {
			$this->load->model('BeerModel', '', true);
			$this->load->model('BreweriesModel', '', true);
			$this->load->model('EstablishmentModel', '', true);
			$this->load->model('RatingModel', '', true);
			$this->load->model('StyleModel', '', true);
			$this->load->library('beers');
			
			$reviewCount = $this->BeerModel->getBeerReviewCount($this->_data['user_info']['id']);
			
			$establishmentID = filter_var($this->uri->segment(3), FILTER_SANITIZE_NUMBER_INT);
			$establishment_check = $this->EstablishmentModel->getEstablishmentExistAndHasBeer($establishmentID);
			$this->_data['establishment'] = $establishment = $this->EstablishmentModel->getEstablishmentByID($establishmentID);
			if (!$establishment_check || !in_array($establishment_check->categoryID, array(1, 4, 6))) {
				$this->_data['action'] = 'no_sell';				
			}
			elseif ($reviewCount >= MIN_REVIEW_COUNT) { // check to see if they have reviewed enough beers								
				$this->load->library('form_validation');
				$this->load->helper('form');							
				
				if (!$this->form_validation->run('addBeer')) {
					$this->_data['action'] = 'show_form';
					$this->_data['other_beers'] = $this->BeerModel->getBeersByEstablishmentID($establishmentID);
					$this->_data['styles'] = $this->StyleModel->getAllForDropDown();
					array_push($this->_data['js'], 'beer_add.js');
				}
				else {
					$this->load->library('slug');
					$slug = $this->slug->url_format($this->input->post('beer')) . '-' . $this->slug->url_format($establishment->name);

					$data = array(
						'id' => 'NULL',
						'establishmentID' => $establishment->establishmentID,
						'beerName' => $this->input->post('beer'),
						'styleID' => $this->input->post('style'),
						'alcoholContent' => $this->input->post('abv'),
                        'beerNotes' => $this->input->post('beerNotes'),
						'malts' => $this->input->post('malts'),
						'hops' => $this->input->post('hops'),
						'yeast' => $this->input->post('yeast'),
						'gravity' => $this->input->post('gravity'),
						'ibu' => $this->input->post('ibu'),
						'food' => $this->input->post('food'),
						'glassware' => $this->input->post('glassware'),
						'seasonal' => $this->input->post('seasonal'),
						'seasonalPeriod' => $this->input->post('seasonalPeriod'),
						'userID' => $this->_data['user_info']['id'],
						'dateAdded' => date('Y-m-d H:i:s'),
						'active' => '1',
						'slug' => $slug
					);
					$id = $this->BeerModel->createBeer($data);					
					
					if (SEND_NEWBEER_NOTICE) {
						$this->load->library('mail');
						$mail_info = array(
							'action' => 'newBeer',
							'beerID' => $id,
							'userID' => $this->_data['user_info']['id'],
							'data' => $data,
							'subject' => 'New Beer Addition',
						);
						$this->mail->sendFormMail($mail_info);
					}
					header('Location: ' . base_url() . 'beer/review/' . $id . '/' . $slug);
					exit;
				}	
			}
			else {
				$this->_data['action'] = 'max_reviews';
			}
		} else {
			$array = array(
				'uri' => substr($this->uri->uri_string(), 0)
				, 'search' => array('_')
				, 'replace' => '/'
			);
			$args = swapOutURI($array);
			header('Location: ' . base_url() . 'user/login/' . $args);
			exit;
		}
		$this->layout->render('beer/add.php', $this->_data, 'two_column.php');
	}
	
	public function ratings() {		
		$type = $this->uri->segment(3);
		$possible_type = array('high', 'low');
		if (!in_array($type, $possible_type)) {
			$type = 'high';
		}
		
		$this->_data['type'] = $type;
		$styleID = filter_var($this->uri->segment(4), FILTER_SANITIZE_NUMBER_INT);
		$styleID = !$styleID ? '' : $this->_data['styleID'] = $styleID;
		
		$this->load->model('BeerModel', '', true);
		$this->_data['beers'] = $this->BeerModel->getBestWorstBeers($this->_data['type']);
		foreach ($this->_data['beers'] as $beer) {
			$beer->width = 30;
			$beer->height = 70;
			$beer->image = $this->_get_beer_image($beer);
		}

		$this->load->library('rating');

		$this->_data['bestStyles'] = $this->BeerModel->getBestWorstStyles($this->_data['type']);
		$this->layout->render('beer/ratings.php', $this->_data, 'two_column.php');
	} 
	
	public function breweryExists($breweryID) {
		$this->load->model('BreweriesModel', '', true);
		$rs = $this->BreweriesModel->getBreweryByID($breweryID);
		
		$boolean = count($rs) > 0 ? true : false;
		if (!$boolean) {
			$this->form_validation->set_message('breweryExists', 'The %s you have chosen doesn\'t exists.  Please choose another.');
		}
		return $boolean;
	}
	
	public function styleExists($styleID) {
		$this->load->model('StyleModel', '', true);
		$rs = $this->StyleModel->getStyleByID($styleID);
		
		$boolean = count($rs) > 0 ? true : false;
		if (!$boolean) {
			$this->form_validation->set_message('styleExists', 'The %s you have chosen doesn\'t exists.  Please choose another.');
		}
		return $boolean;
	}
	
	public function seasonalExists($seasonalID) {
		$array = array(0, 1);
		$boolean = in_array($seasonalID, $array) ? true : false;
		if (!$boolean) {
			$this->form_validation->set_message('seasonalExists', 'The %s you have chosen doesn\'t exists.  Please choose another.');
		}
		return $boolean;
	}
	
	public function addHTMLEntities($str) {
		return htmlentities(($str));
	}
}
?>