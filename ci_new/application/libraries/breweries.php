<?php
if(!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Breweries {
	private $ci;
	private $title = 'Breweries';

	public function __construct() {
		$this->ci =& get_instance();
	}

	public function getAllBreweries() {
		// get all the breweries
		$breweries = $this->ci->BreweriesModel->getAll();

		// start the output
		$str = '';
		// iterte through the list
		foreach($breweries as $brewery) {
			// format the phone, if it exists
			$phone = !empty($brewery['phone']) ? formatPhone($brewery['phone']) : '';
			// continue the output for the screen
			$str .= '
			<div id="item_' . $brewery['id'] . '" class="item">
				<div id="item_list_container_' . $brewery['id'] . '" class="list_itemContainer">
					<ul id="item_list_' . $brewery['id'] . '" class="list_item">
						<li>' . $brewery['name'] . '</li>
						<li>' . $brewery['address'] . '</li>
						<li>' . $brewery['city'] . ', ' . $brewery['stateAbbr'] . ' ' . $brewery['zip'] . '</li>
						<li>' . $phone . '</li>
						<li><a href="' . $brewery['url'] . '" target="_blank">' . $brewery['url'] . '</a></li>
					</ul>
				</div>
				<ul id="list_links_' . $brewery['id'] . '" class="list_horizontalLinks">
					<li><a href="#" id="edit_' . $brewery['id'] . '" onclick="new Ajax.Request(\'' . base_url() . 'ajax/edit/brewery/' . $brewery['id'] . '\', {asynchronous: true, evalScripts: true, method: \'post\', onLoading: function() {showSpinner(\'item_list_container_' . $brewery['id'] . '\');}, onComplete: function(response) {$(\'item_list_container_' . $brewery['id'] . '\').update(response.responseText); $(\'edit_' . $brewery['id'] . '\').style.display=\'none\'; $(\'cancel_' . $brewery['id'] . '\').style.display=\'block\';}}); return false;">Edit</a></li>
					<li><a href="#" id="cancel_' . $brewery['id'] . '" onclick="new Ajax.Request(\'' . base_url() . 'ajax/cancel/brewery/' . $brewery['id'] . '\', {asynchronous: true, evalScripts: true, method: \'get\', onLoading: function() {showSpinner(\'item_list_container_' . $brewery['id'] . '\');}, onComplete: function(response) {$(\'item_list_container_' . $brewery['id'] . '\').update(response.responseText); $(\'cancel_' . $brewery['id'] . '\').style.display=\'none\'; $(\'edit_' . $brewery['id'] . '\').style.display = \'block\';}}); return false;" style="display: none;">Cancel</a></li>
				</ul>
				<br class="both" />
			</div>
			';
		}
		// return the output
		return $str;
	}

	public function getBreweryByID($id) {
		// get the specific brewery
		$brewery = $this->ci->BreweriesModel->getBreweryByID($id);
		// format the phone, if it exists
		$phone = !empty($brewery['phone']) ? formatPhone($brewery['phone']) : '';
		$str = '
					<ul id="item_list_' . $brewery['id'] . '" class="list_item">
						<li>' . $brewery['name'] . '</li>
						<li>' . $brewery['address'] . '</li>
						<li>' . $brewery['city'] . ', ' . $brewery['stateAbbr'] . ' ' . $brewery['zip'] . '</li>
						<li>' . $phone . '</li>
						<li><a href="' . $brewery['url'] . '" target="_blank">' . $brewery['url'] . '</a></li>
					</ul>
		';
		return $str;
	}

	public function createForm($config) {
		$brewery = array(
		'abc' => ''
		, 'id' => ''
		, 'name' => ''
		, 'address' => ''
		, 'city' => ''
		, 'stateID' => ''
		, 'zip' => ''
		, 'phone' => ''
		, 'url' => ''
		, 'btnValue' => 'Add'
		, 'action' => 'action="' . base_url() . 'ajax/addData/brewery/"'
		, 'onsubmit' => 'onsubmit="new Ajax.Request(\'' . base_url() . 'ajax/addData/brewery\', {asynchronous: true, evalScripts: true, method: \'post\', parameters: Form.serialize(this), onComplete: function(response) {$(\'contents\').update(response.responseText);}}); return false;"'
		);

		if(key_exists('id', $config)) {
			$brewery = $this->ci->BreweriesModel->getBreweryByID($config['id']);
			$brewery['btnValue'] = 'Update';
			$brewery['action'] = 'action="' . base_url() . 'ajax/editData/brewery/' . $config['id'] . '"';
			$brewery['onsubmit'] = 'onsubmit="new Ajax.Request(\'' . base_url() . 'ajax/editData/brewery/' . $config['id'] . '\', {asynchronous: true, evalScripts: true, method: \'post\', parameters: Form.serialize(this), onLoading: function() {showSpinner(\'item_list_container_' . $brewery['id'] . '\');}, onComplete: function(response) {$(\'item_list_container_' . $config['id'] . '\').update(response.responseText); $(\'cancel_' . $config['id'] . '\').style.display=\'none\'; $(\'edit_' . $config['id'] . '\').style.display = \'block\';}}); return false;"';
		}

		$data = array(
		'data' => $config['states']
		, 'id' => 'slt_state'
		, 'name' => 'slt_state'
		, 'selected' => $brewery['stateID']
		);
		$stateDropDown = createDropDown($data);

		$str = '
			<form class="edit" method="post" ' . $brewery['action'] . ' ' . $brewery['onsubmit'] . '>
				<label for="txt_name">Name:</label>
				<input type="text" id="txt_name" name="txt_name" value="' . $brewery['name'] . '" />
				
				<label for="txt_address">Address:</label>
				<input type="text" id="txt_address" name="txt_address" value="' . $brewery['address'] . '" />
				
				<label for="txt_city">City:</label>
				<input type="text" id="txt_city" name="txt_city" value="' . $brewery['city'] . '" />
				
				<label for="slt_state">State:</label>
				' . $stateDropDown . '
				
				<label for="txt_zip">Zip:</label>
				<input type="text" id="txt_zip" name="txt_zip" value="' . $brewery['zip'] . '" />
				
				<label for="txt_phone">Phone:</label>
				<input type="text" id="txt_phone" name="txt_phone" value="' . $brewery['phone'] . '" />
				
				<label for="txt_url">URL:</label>
				<input type="text" id="txt_url" name="txt_url" value="' . $brewery['url'] . '" />
				
				<input type="submit" id="btn_submit" name="btn_submit" value="' . $brewery['btnValue'] . '" />
		';
		$str .= key_exists('hidden', $config) && $config['hidden'] == 'rating' ? '<input type="hidden" id="hdn_step" name="hdn_step" value="beer" />' : '';
		$str .= '
			</form>
		';
		return $str;
	}

	public function showBreweryInfoGeneric($id, $logged = false) {
		// holder for left column output
		$str = '';
		// get the state information
		$states = $this->ci->StateModel->getAllStates();
		// check to make sure we have states
		if(!empty($states)) {
			// counter
			$cnt = 0;
			// iterate through the results
			foreach($states as $state) {
				// get the remainder
				$mod = $cnt % 17;
				if($mod == 0 && $cnt > 0) {
					$str .= '</ul><ul class="stateList">';
				}
				if($mod == 0 && $cnt == 0) {
					$str .= '<ul class="stateList">';
				}
				// add the state to the list
				$str .= '<li><a href="' . base_url() . 'establishment/state/' . $state['id'] . '">' . $state['stateFull'] . '</a></li>';
				// increment the counter
				$cnt++;
			}
			$str .= '</ul><br class="left" />';
		}
					
		// holder for right column text
		$rightCol = '';
		// get the highest rated breweries
		$highestRatedEstablishments = $this->ci->EstablishmentModel->getHighestRatedEstablishments();
		// check if there were any results
		if(!empty($highestRatedEstablishments)) {
			$rightCol = '
				<h4><span>Highest Rated Establishments</span></h4>
				<ul>
			';
			// iterate over the results
			foreach($highestRatedEstablishments as $highRating) {
				// get the wording
				$brs = $highRating['totalRatings'] == 1 ? ' rating' : ' ratings';
				// add another item
				$rightCol .= '
					<li>
						<p><a href="' . base_url() . 'establishment/info/rating/' . $highRating['id'] . '">' . $highRating['name'] . '</a> in <a href="' . base_url() . 'establishment/city/' . $highRating['stateID'] . '/' . urlencode($highRating['city']) . '">' . $highRating['city'] . '</a>, <a href="' . base_url() . 'establishment/state/' . $highRating['stateID'] . '">' . $highRating['stateAbbr'] . '</a></p>
						<p class="rightBreweryLink"><span class="bold">' . number_format($highRating['avgRating'], 1) . '</span> for <span class="bold">' . $highRating['totalRatings'] . '</span>' . $brs . '</p>
					</li>';
			}
			// finish off the text
			$rightCol .= '
				</ul>
			';
		}
		/*
		// get the establishment types
		$establishmentTypes = $this->ci->EstablishmentModel->getEstablishmentTypes();
		// check that there were results
		if(!empty($establishmentTypes)) {
			$rightCol .= '
				<h4><span>Establishments Types</span></h4>
				<dl class="dl_nomargin">
			';
			//iterate over the results
			foreach($establishmentTypes as $eTypes) {
				// add the item
				$rightCol .= '
					<dt>' . ucwords($eTypes['name']) . '</dt>
					<dd>' . $eTypes['description'] . '</dd>
				';
			}
			// finish off the text
			$rightCol .= '
				</dl>
			';
		}*/
		
		// get the newest additions
		$newest = $this->ci->EstablishmentModel->getNewestAdditions();
		// check that there were results
		if(!empty($newest)) {
			$rightCol .= '
				<h4><span>Recent Additions</span></h4>
				<ul>
			';
			// iterate over the results
			foreach($newest as $item) {
				// add the item
				$rightCol .= '
					<li>
						<p><a href="' . base_url() . 'establishment/info/rating/' . $item['id'] . '">' . $item['name'] . '</a></p>
						<p class="rightBreweryLink"> in <a href="' . base_url() . 'establishment/city/' . $item['stateID'] . '/' . urlencode($item['city']) . '">' . $item['city'] . '</a>, <a href="' . base_url() . 'establishment/state/' . $item['stateID'] . '">' . $item['stateFull'] . '</a></p>
					</li>
				';
			}
			// finish off the text
			$rightCol .= '
				</ul>
			';
		}
		
		// get the newest review additions
		$reviews = $this->ci->EstablishmentModel->getRecentReviews();
		// check that there were results
		if(!empty($reviews)) {
			$rightCol .= '
				<h4><span>Recent Reviews</span></h4>
				<ul>
			';
			// iterate over the results
			foreach($reviews as $item) {
				// add the item
				$rightCol .= '
					<li>
						<div class="bottleCap"><p>' . number_format($item['rating'], 1) . '</p></div>
						<div class="rightSimilar">
							<p><a href="' . base_url() . 'establishment/info/rating/' . $item['id'] . '">' . $item['name'] . '</a></p>
							<p class="rightBreweryLink">in <a href="' . base_url() . 'establishment/city/' . $item['stateID'] . '/' . urlencode($item['city']) . '">' . $item['city'] . '</a>, <a href="' . base_url() . 'establishment/state/' . $item['stateID'] . '">' . $item['stateFull'] . '</a></p>
						</div>
						<br class="left" />
					</li>
				';
			}
			// finish off the text
			$rightCol .= '
				</ul>
			';
		}

		// set the page information
		$array = array('leftCol' => $str, 'rightCol' => $rightCol);
		// return the array of results
		return $array;
	}
	
	public function showBreweriesCity($state, $city) {
		// get the brewery information
		$breweryCity = $this->ci->BreweriesModel->getBreweryByCity($state, $city);
		//echo '<pre>'; print_r($breweryCity); echo '</pre>';exit;
		
		// holder for the output
		$array = array();
		// check to see if there were any results
		if(empty($breweryCity)) {
			// there were no results for the given city
			$array = '<p>There were no breweries matching the request.</p>';
		} else {			
			// there are some breweries			
			// holder for the class depending on if there is an image
			$str = '
				<h2 class="brown">Breweries in ' . $breweryCity[0]['city'] . ', ' . $breweryCity[0]['stateAbbr'] . '</h2>
				<div id="beerTable">
				<table class="marginTop_8">
					<tr class="gray2">
						<th>Name</th>
						<th>Overall Rating</th>
						<th>Have Another</th>
					</tr>
			';
			// counter to determine the color of the row
			$cnt = 0;
			// iterate through the results
			foreach($breweryCity as $establishment) {//echo '<pre>'; print_r($establishment); exit;
				// get the percentage of people who would have another
				$haveAnother = $this->ci->BreweriesModel->getHaveAnotherPercentByEstablishment($establishment['establishmentID']);
				// get the total number of beers and the average rating
				// based on brewery id
				$avg = $this->ci->BreweriesModel->getTotalEachBeer($establishment['establishmentID']);
				// determine the average score for the entire group
				$average = $avg['totalBeers'] > 0 ? round((float) ($avg['totalPoints'] / $avg['totalBeers']), 1) : '0.0';
				// determine the percent of have another
				$str_ha = '0 beers rated';
				if(key_exists('totalBeers', $haveAnother)) {
					$str_be = $haveAnother['totalBeers'] > 1 ? $haveAnother['totalBeers'] . ' beers' : $haveAnother['totalBeers'] . ' beer';
					$str_td = $haveAnother['totalDrank'] > 1 ? $haveAnother['totalDrank'] . ' times' : $haveAnother['totalDrank'] . ' time';
					$str_ha = ($haveAnother['percentHaveAnother'] * 100) . '% on ' . $str_be . ' rated ' . $str_td;
				}
				// get the class of the row
				$class = ($cnt % 2 == 0) ? '' : ' class="gray"';
				$str .= '
					<tr' . $class . '>
						<td>
							<a href="' . base_url() . 'brewery/info/' . $establishment['establishmentID'] . '">' . $establishment['name'] . '</a><br />
							' . $establishment['address'] . '<br />
							<a href="' . base_url() . 'establishment/city/' . $establishment['stateID'] . '/' . urlencode($establishment['city']) . '">' . $establishment['city'] . '</a>, <a href="' . base_url() . 'establishment/state/' . $establishment['stateID'] . '">' . $establishment['stateAbbr'] . '</a> ' . $establishment['zip'] . '<br />
							' . formatPhone($establishment['phone']) . '
						</td>
						<td>' . number_format($average, 1) . '</td>
						<td>' . $str_ha . '</td>
					</tr>
				';
				/*if(!empty($establishment['picture']) && $establishment['pictureApproval'] == 1) {
					$str .= '
						<img src="' . base_url() . 'images/establishments/' . $establishment['picture'] . '" title="' . $establishment['name'] . ', ' . $establishment['city'] . ', ' . $establishment['stateAbbr'] . '" alt="' . $establishment['name'] . ', ' . $establishment['city'] . ', ' . $establishment['stateAbbr'] . '" />
					';
					$class = ' class="brewery_float_right"';
				}*/
				// increment the counter
				$cnt++;
			}
			// finish off the text
			$str .= '
				</table>
				</div>
			';
			
			// get configuration values for creating the seo
			$config = array(
				'breweryName' => $establishment['name']
				, 'breweryCity' => $establishment['city']
				, 'breweryState' => $establishment['stateFull']
				, 'seoType' => 'establishmentByCity'
			);
			// set the page information
			$seo = getDynamicSEO($config);
			$array = $seo + array('str' => $str);
		}
		// return the output
		return $array;
	}
	
	public function showBreweriesState($state) {
		// get the brewery information
		$breweryState = $this->ci->BreweriesModel->getBreweryByState($state);
				
		// holder for the output
		$array = array();
		// check to see if there were any results
		if(empty($breweryState)) {
			// there were no results for the given city
			$array = false;
		} else {			
			// there are some breweries			
			// holder for the class depending on if there is an image
			$str = '
				<h2>Breweries in ' . $breweryState[0]['stateFull'] . '</h2>
				<table>
					<tr>
						<th>Name</th>
						<th>Address</th>
						<th>City</th>
						<th>Phone</th>
						<th>Overall Rating</th>
						<th>Have Another</th>
					</tr>
			';
			// iterate through the results
			foreach($breweryState as $establishment) {
				// get the percentage of people who would have another
				$haveAnother = $this->ci->BreweriesModel->getHaveAnotherPercentByEstablishment($establishment['establishmentID']);
				// get the total number of beers and the average rating
				// based on brewery id
				$avg = $this->ci->BreweriesModel->getTotalEachBeer($establishment['establishmentID']);
				// determine the average score for the entire group
				$average = $avg['totalBeers'] > 0 ? round((float) ($avg['totalPoints'] / $avg['totalBeers']), 1) : '0.0';
				// determine the percent of have another
				$str_ha = '0 beers rated';
				if(key_exists('totalBeers', $haveAnother)) {
					$str_be = $haveAnother['totalBeers'] > 1 ? $haveAnother['totalBeers'] . ' beers' : $haveAnother['totalBeers'] . ' beer';
					$str_td = $haveAnother['totalDrank'] > 1 ? $haveAnother['totalDrank'] . ' times' : $haveAnother['totalDrank'] . ' time';
					$str_ha = ($haveAnother['percentHaveAnother'] * 100) . '% on ' . $str_be . ' rated ' . $str_td;
				}				
				
				$str .= '
					<tr>
						<td><a href="' . base_url() . 'brewery/info/' . $establishment['establishmentID'] . '">' . $establishment['name'] . '</a></td>
						<td>' . $establishment['address'] . '</td>
						<td><a href="' . base_url() . 'establishment/city/' . $establishment['stateID'] . '/' . urlencode($establishment['city']) . '">' . $establishment['city'] . '</a></td>
						<td>' . formatPhone($establishment['phone']) . '</td>
						<td>' . number_format($average, 1) . '</td>
						<td>' . $str_ha . '</td>
					</tr>
				';
			}
			// finish off the text
			$str .= '
				</table>
			';
			
			// get configuration values for creating the seo
			$config = array(
				'breweryName' => $establishment['name']
				, 'breweryCity' => $establishment['city']
				, 'breweryState' => $establishment['stateFull']
				, 'seoType' => 'establishmentByState'
			);
			// set the page information
			$seo = getDynamicSEO($config);
			$array = $seo + array('str' => $str);
		}
		// return the output
		return $array;
	}
	
	public function getTitle() {
		return $this->title;
	}
}
?>