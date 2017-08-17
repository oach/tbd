
				<h2 class="brown">Establishments</h2>
                <p>
                    Two Beer Dudes one stop for finding beer establishments throughout the United States.  This portion of the site
                    will be largerly driven by the users of the site.  Most of our visits and experiences will be midwest centric.  Below
                    is a list is a list of the United States:
                </p>                
<?php
if (!empty($states)) {
?>
                <div class="row">
<?php    
    $cnt = 0;
    foreach ($states as $state) {
        $mod = $cnt % 17;
        if ($mod == 0 && $cnt > 0) {
?>
                    </div><div class="col-xs-12 col-sm-4 col-md-4">
<?php                
        }
        if ($mod == 0 && $cnt == 0) {
?>            
                    <div class="col-xs-12 col-sm-4 col-md-4">
<?php                
        }
?>
                    <a href="<?php echo base_url(); ?>establishment/state/<?php echo $state['id']; ?>"><?php echo $state['stateFull']; ?></a><br>
<?php
        $cnt++;
    }
?>
                    </div>
                </div>
<?php
}                
?>
			</div>
			<div class="col-md-3 col-xs-12">
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

    public function showBreweryInfoGeneric($id, $logged = false) {
                    
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