
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-sm-9 col-xs-12">
<?php
if ($id) {

}
else {
	$this->load->view('establishment/info/info_generic.php');
}
?>			
				<h2 class="brown">Create User Account</h2>
			</div>
			<div class="col-md-3 col-sm-3 col-xs-12">
    			<div class="side-info">
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">Why Two Beer Dudes?</div>
                        <ul class="list-group">
            				<li class="list-group-item">Learn how to truly enjoy beer</li>
            				<li class="list-group-item">Keep track of your beer history</li>
            				<li class="list-group-item">Enjoy American craft beer</li>
            				<li class="list-group-item">Midwest centric but not completely</li>
            				<li class="list-group-item">Trying to get the little guys on the map</li>
            				<li class="list-group-item">Share your thoughts with the community</li>
            			</ul>
                    </div>
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">Agreements</div>
                        <div class="panel-body">
                            <ul>
                				<li><a href="<?php echo base_url(); ?>page/agreement">User Agreement</a></li>
                                <li><a href="<?php echo base_url(); ?>page/privacy">Terms and Conditions</a></li>
                			</ul>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">Already A Member?</div>
                        <div class="panel-body">
                            <ul>
                				<li><a href="<?php echo base_url(); ?>user/login">Login</a></li>
                			</ul>
                        </div>
                    </div>    
    			</div>
    		</div>
        </div>
	</div>
public function showBreweryInfo($id, $logged = false) {
		// get the brewery information
		$brewery = $this->ci->BreweriesModel->getBreweryInfoByID($id);
        // holder for return information
		$array = array();
		// make sure that information was found - this will only happen
		// if the id isn't found in the brewery table
		if(empty($brewery)) {
			// set the words for the page
			$str = '<h2 class="brown">Establishments</h2><p>Sorry, but we couldn\'t find any information for the requested brewery.</p>';
			// create the seo information
			$seo = array(
				'pagetitle' => 'Brewery Information - Two Beer Dudes'
				, 'metadescription' => 'Two Beer Dudes American craft beer brewery review, brewery rating, brewery hop, brewery'
				, 'metakeywords' => 'brewery review, brewery rating, brewery hop, brewery'
			);
			// set the return array
			$array = $seo + array('str' => $str);
		} else {
			// get the beer and rating information
			$beers = $this->ci->BreweriesModel->getAllRatingsForBreweryByID($id);
			// check to see if there are any beers that have been rated
			if(!empty($beers)) {
				// get the total number of beers and the average rating
				// based on brewery id
				$avg = $this->ci->BreweriesModel->getTotalEachBeer($id);
				// determine the average score for the entire group
				$average = '0.0';
				if($avg['totalBeers'] > 0) {
					$average = round(($avg['totalPoints'] / $avg['totalBeers']), 1);
				}
				// get the total number of distinct beers that have been tried
				// based on brewery id
				$distinct = $this->ci->BreweriesModel->getDistinctBeerCount($id);
				// get the avg cost per package of beer drank for the brewery
				$avgCost = $this->ci->BreweriesModel->getAvgCostPerPackage($id);
                                // get to overall average cost of having a beer at the establishment
				$overalAverageCost = $this->ci->BreweriesModel->getOverallAverageCostOfBeerByEstablishmentID($id);
                                // get the percentage of people who would have another
				$haveAnother = $this->ci->BreweriesModel->getHaveAnotherPercent($id);
			}
            //echo '<pre>'; print_r($brewery[0]); exit;
			// create the output for the screen
			// start w/ the brewery information
			// check for a brewery hop
			$breweryHop = !empty($brewery[0]['breweryhopsID']) ? '<a href="' . base_url() . 'brewery/hop/' . $brewery[0]['breweryhopsID'] . '"><img src="' . base_url() . 'images/cone.gif" alt="brewery hop to ' . $brewery[0]['name'] . '" title="brewery hop to ' . $brewery[0]['name'] . '" /></a>' : '';
			$address = empty($brewery[0]['address']) ? '' : '<p>' . $brewery[0]['address'] . '</p>';
			$city = empty($brewery[0]['city']) ? '' : '<a href="' . base_url() . 'establishment/city/' . $brewery[0]['stateID'] . '/' . urlencode($brewery[0]['city']) . '">' . $brewery[0]['city'] . '</a>, ';
			$zip = empty($brewery[0]['zip']) ? '' : $brewery[0]['zip'];
			$url = empty($brewery[0]['url']) ? '' : '<a href="' . $brewery[0]['url'] . '" target="_blank"><img src="' . base_url() . 'images/web.jpg" alt="' . $brewery[0]['name'] . ' web site" title="' . $brewery[0]['name'] . ' web site" /></a>';
			
			// configuration for the image
			$image = array(
				'picture' => $brewery[0]['picture']
				, 'id' => $id
				, 'alt' => $brewery[0]['name']
				, 'approval' => $brewery[0]['pictureApproval']
			);
			// check if the image exists for this brewery
			$img = checkForAnImage($image, $logged, true, 'establishment');

			$str = '
				<div id="breweryInfo" style="position: relative;">
					<h2 class="brown"><a class="brown" href="' . base_url() . 'establishment/info/rating/' . $brewery[0]['id'] . '">' . $brewery[0]['name'] . '</a> ' . $url . ' ' . $breweryHop . ' <a href="' . base_url() . 'establishment/googleMaps/' . $brewery[0]['id'] . '"><img src="' . base_url() . 'images/google-map.png" alt="map for ' . $brewery[0]['name'] . '" title="map for ' . $brewery[0]['name'] . '" /></a>' . showTwitterForEstablishment($brewery[0]['twitter']) . '</h2>
					<div id="establishmentInfo">						
						<p>' . $address . '</p>
						<p>' . $city . '<a href="' . base_url() . 'establishment/state/' . $brewery[0]['stateID'] . '">' . $brewery[0]['stateAbbr'] . '</a> ' . $zip . '</p>
						<p>' . formatPhone($brewery[0]['phone']) . '</p>
			';
            // checked if the establishment was closed
            $str .= $brewery[0]['closed'] == 1 ? '<p class="closed marginTop_8">Closed for business!</p>' : '';
            // holder for the number of active beers
            $numActive = 0;
            // holder for the number of retired beers
            $numRetired = 0;
			if(!empty($beers)) {
				$totalBeers = $distinct['totalBeers'] > 1 || $distinct['totalBeers'] < 1 ? '<span class="bold">' . $distinct['totalBeers'] . '</span> different beers' : '<span class="bold">' . $distinct['totalBeers'] . '</span> different beer';
				$avgTotalBeers = $avg['totalBeers'] > 1 || $avg['totalBeers'] < 1 ? 'drank <span class="bold">' . $avg['totalBeers'] . '</span> times' : 'drank <span class="bold">' . $avg['totalBeers'] . '</span> time';
				$str .= '
						<ul class="green" style="margin-top: 1.0em">
							<li class="bold" style="text-decoration: underline;">Beer Review Stats:</li>
							<li>' . $totalBeers . '</li>
							<li>' . $avgTotalBeers . '</li>
							<li>with a <span class="bold">' . number_format($average, 1) . '</span> average rating</li>
							<li>and overall average cost of <span class="bold">$' . number_format($overalAverageCost['averagePrice'], 2) . '</span></li>
						</ul>
				';
                // determine how many of each type of beer there is
                foreach($beers as $beer) {
                    if($beer['retired'] == 1) {
                        $numRetired++;
                    } else {
                        $numActive++;
                    }
                }
			}
			$str .= '
					</div>
					' . $img . '
					<br class="left" />
			';
			$str .= '
				<p class="marginTop_8">
					<a href="' . base_url() . 'establishment/info/rating/' . $brewery[0]['id'] . '">Establishment Hops</a>
					 | <a href="' . base_url() . 'brewery/info/' . $brewery[0]['id'] . '">Beer Reviews</a>
				';
			// checked if the establishment was closed
            $str .= $brewery[0]['closed'] != 1 ? '<p class="marginTop_8"><a href="' . base_url() . 'beer/addBeer/' . $id . '">Add a Beer</a></p>' : '';
            $str .= '
				</div>
				<div id="beerTable">
					<table id="activeTable" class="tablesorter">
                        <caption class="gray2">Still Brewing (' . $numActive . ')</caption>
                        <thead>
						    <tr class="gray">
							    <th class="noPointer">&nbsp;</th>
							    <th>Beer</th>
							    <th>Style</th>
							    <th class="center">#Rev</th>
							    <th class="center">RAvg.</th>							
							    <th class="center noPointer">H.A.</th>
							    <th class="noPointer">Avg. Cost</th>
						    </tr>
                        </thead>
                        <tbody>
			';		

			if(empty($beers)) {
				$str .= '
						    <tr><td colspan="7">No beer reviews at this time.</td></tr>
                        </tbody>
					</table>
				</div>
				';
			} else {
				// holder for showing retired beers
				$retired = false;
				// counter for determing background color
				$cnt = 0;
				// iterate through the beers
				foreach($beers as $beer) {
					// see if the beer has an average cost
					//$str_avg = $beer['reviews'] == 0 ? 'N/A' : '';//echo '<pre>'; print_r($avgCost); exit;
                                    $str_avg = '';//echo '<pre>'; print_r($avgCost); exit;
                                    if(!empty($avgCost)) {
                                        foreach($avgCost as $cost) {
                                            if($cost['id'] == $beer['id']) {
                                                if(!empty($cost['averagePrice'])) {
                                                    // there is a match so create the output
                                                    if(!empty($str_avg)) {
                                                        $str_avg .= '<br />';
                                                    }
                                                    $serving = $cost['totalServings'] > 1 || $cost['totalServings'] < 1 ? ' servings' : ' serving';
                                                    if(stristr($str_avg, 'No cost data')) {
                                                        $str_avg = '';
                                                    }
                                                    $str_avg .= '$' . $cost['averagePrice'] . ', ' . $cost['totalServings'] . $serving . ', ' . $cost['package'] . 's';
                                                } else {
                                                    if(empty($str_avg)) {
                                                        $str_avg = 'No cost data';
                                                    }
                                                }
                                            } else {
                                                if(empty($str_avg)) {
                                                    $str_avg = 'No cost data';
                                                }
                                            }
                                        }
                                    } else {
                                        if(empty($str_avg)) {
                                            $str_avg = 'No cost data';
                                        }
                                    }
					// determine the percent of have another
					$str_ha = $beer['reviews'] == 0 ? 'N/A' : '0%';
					foreach($haveAnother as $ha) {
						if($ha['id'] == $beer['id']) { 
							// there is a match so create the output
							$str_ha = ($ha['percentHaveAnother'] * 100) . '%';
						}
					}
					$averagereview = (float) $beer['averagereview'];
					$class = $cnt % 2 == 1 ? ' class="gray"' : '';
					
					// check if retired text is needed
					if($beer['retired'] == '1' && $retired === false) {
						// show the retired row in the table
						$str .= '
                        </tbody>
                    </table>
                    <table id="retiredTable" class="tablesorter">
						<caption class="gray2">Dried Up Suds (' . $numRetired . ')</caption>
                        <thead>
                            <tr class="gray">
                                <th class="noPointer">&nbsp;</th>
                                <th>Beer</th>
                                <th>Style</th>
                                <th class="center">#Rev</th>
                                <th class="center">RAvg.</th>                            
                                <th class="center noPointer">H.A.</th>
                                <th class="noPointer">Avg. Cost</th>
                            </tr>
                        </thead>
                        <tbody>
						';
						// change holder to not show the text again
						$retired = true;
					}
					                          //<tr' . $class . '>
					$str .= '
						    <tr>
							    <td class="td_first"><a href="' . base_url() . 'beer/review/' . $beer['id'] . '"><img src="' . base_url() . 'page/createImage/' . $beer['id'] . '/beer/mini" /></a></td>
							    <td><a href="' . base_url() . 'beer/review/' . $beer['id'] . '">' . $beer['beerName'] . '</a></td>
							    <td><a href="' . base_url() . 'beer/style/' . $beer['styleID'] . '">' . $beer['style'] . '</a></td>
							    <td class="center">' . $beer['reviews'] . '</td>
							    <td class="center">' . number_format($averagereview, 1) . '</td>
							    <td class="center">' . $str_ha . '</td>
							    <td>' . $str_avg . '</td>
						    </tr>				
					';
					// increment counter
					$cnt++;
				}
				$str .= '
                        </tbody>
					</table>
				</div>
				';
			}

			// holder for right column text
			$rightCol = '';
            
            // get twitter if it exists
            // set the configuration values for the method
            $configTwitter = array(
                'establishment' => $brewery[0]
                , 'type' => 'establishmentHome'
            );
            // call the method to get the string of text
            $rightCol = addSocialMedia($configTwitter);            
            
			// get the highest rated breweries
			$highestRatedBreweries = $this->ci->BreweriesModel->getHighestRatedBreweries();
			// check if there were any results
			if(!empty($highestRatedBreweries)) {
				$rightCol .= '
					<h4><span>Highest Rated Breweries</span></h4>
					<ul>
				';
				// iterate over the results
				foreach($highestRatedBreweries as $highRating) {
					// get the wording
					$brs = $highRating['beerTotal'] == 1 ? ' beer rating' : ' beer ratings';
					// add another item
					$rightCol .= '
						<li>
							<p><a href="' . base_url() . 'brewery/info/' . $highRating['id'] . '">' . $highRating['name'] . '</a></p>
							<p class="rightBreweryLink"><span class="bold">' . number_format($highRating['avgRating'], 1) . '</span> for <span class="bold">' . $highRating['beerTotal'] . '</span>' . $brs . '</p>
						</li>';
				}
				// finish off the text
				$rightCol .= '
					</ul>
				';
			}
			// get the overall average cost by cheapest
			$averageCostPerBrewery = $this->ci->BreweriesModel->getOverallAverageCostOfBeer('ASC');
			// check if there were any results
			if(!empty($averageCostPerBrewery)) {
				$rightCol .= '
					<h4><span>Least Expensive Breweries</span></h4>
					<ul>
				';
				// iterate over the results
				foreach($averageCostPerBrewery as $ac) {
					// get the wording
					$ts = $ac['totalServings'] == 1 ? ' total serving' : ' total servings';
					// add another item
					$rightCol .= '
						<li>
							<p><a href="' . base_url() . 'brewery/info/' . $ac['id'] . '">' . $ac['name'] . '</a></p>
							<p class="rightBreweryLink"><span class="bold">$' . number_format($ac['averagePrice'], 2) . '</span> for <span class="bold">' . $ac['totalServings'] . '</span>' . $ts . '</p>
						</li>';
				}
				// finish off the text
				$rightCol .= '
					</ul>
				';
			}
			// get the overall average cost by most expensive
			$averageCostPerBrewery = $this->ci->BreweriesModel->getOverallAverageCostOfBeer('DESC');
			// check if there were any results
			if(!empty($averageCostPerBrewery)) {
				$rightCol .= '
					<h4><span>Most Expensive Breweries</span></h4>
					<ul>
				';
				// iterate over the results
				foreach($averageCostPerBrewery as $ac) {
					// get the wording
					$ts = $ac['totalServings'] == 1 ? ' total serving' : ' total servings';
					// add another item
					$rightCol .= '
						<li>
							<p><a href="' . base_url() . 'brewery/info/' . $ac['id'] . '">' . $ac['name'] . '</a></p>
							<p class="rightBreweryLink"><span class="bold">$' . number_format($ac['averagePrice'], 2) . '</span> for <span class="bold">' . $ac['totalServings'] . '</span>' . $ts . '</p>
						</li>';
				}
				// finish off the text
				$rightCol .= '
					</ul>
				';
			}

			// get configuration values for creating the seo
			$config = array(
			'breweryName' => $brewery[0]['name']
			, 'breweryCity' => $brewery[0]['city']
			, 'breweryState' => $brewery[0]['stateFull']
			);
			// set the page information
			$seo = getDynamicSEO($config);
			$array = $seo + array('leftCol' => $str, 'rightCol' => $rightCol);
		}
		return $array;
	}