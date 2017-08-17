
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-sm-8 col-xs-12">
				<h2 class="hop brown">Establishments</h2>
				<p>Two Beer Dudes one stop for finding beer establishments throughout the United States.  This portion of the site
				will be largerly driven by the users of the site.  Most of our visits and experiences will be midwest centric.  Below
				is a list is a list of the United States:</p>
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
			<div class="col-md-4 col-sm-4 col-xs-12">
    			<div class="side-info">
<?php
if (count($highestRatedEstablishments) > 0) {
?>
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">Highest Rated Establishments</div>
                        <ul class="list-group">
<?php
    foreach ($highestRatedEstablishments as $highRating) {
?>
                            <li class="list-group-item">
                                <p>
                                    <a href="<?php echo base_url(); ?>establishment/info/rating/<?php echo $highRating->id; ?>/<?php echo $highRating->slug_establishment; ?>"><?php echo $highRating->name; ?></a> in
                                    <a href="<?php echo base_url(); ?>establishment/city/<?php echo $highRating->stateID; ?>/<?php echo urlencode($highRating->city); ?>"><?php echo $highRating->city; ?></a>, 
                                    <a href="<?php echo base_url(); ?>establishment/state/<?php echo $highRating->stateID; ?>"><?php echo $highRating->stateAbbr; ?></a>
                                </p>
                                <p class="rightBreweryLink">
                                    <span class="bold"><?php echo number_format($highRating->avgRating, 1); ?></span> for <span class="bold"><?php echo $highRating->totalRatings; ?></span> rating<?php echo ($highRating->totalRatings == 1 ? '' : 's'); ?>
                                </p>
                            </li>
<?php
    }
?>
                        </ul>
                    </div>
<?php
}

if (count($newest) > 0) {
?>
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">Recent Additions</div>
                        <ul class="list-group">
<?php
    foreach ($newest as $item) {
?>
                            <li class="list-group-item">
                                <p><a href="<?php echo base_url(); ?>establishment/info/rating/<?php echo $item->id; ?>/<?php echo $item->slug_establishment; ?>"><?php echo $item->name; ?></a></p>
                                <p class="rightBreweryLink"> in <a href="<?php echo base_url(); ?>establishment/city/<?php echo $item->stateID; ?>/<?php echo urlencode($item->city); ?>"><?php echo $item->city; ?></a>, <a href="<?php echo base_url(); ?>establishment/state/<?php echo $item->stateID; ?>"><?php echo $item->stateFull; ?></a></p>
                            </li>
<?php
    }
?>    
                        </ul>
                    </div>
<?php
}

if( count($reviews) > 0) {
?>    
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">Recent Reviews</div>
                        <ul class="list-group">
<?php
    foreach ($reviews as $item) {
?>
                    <li class="list-group-item">
                        <p>
                            <span class="badge"><?php echo number_format($item->rating, 1); ?></span>
                            <a href="<?php echo base_url(); ?>establishment/info/rating/<?php echo $item->id; ?>"><?php echo $item->name; ?></a>
                        </p>
                        <p class="rightBreweryLink">in <a href="<?php echo base_url(); ?>establishment/city/<?php echo $item->stateID; ?>/<?php echo urlencode($item->city); ?>"><?php echo $item->city; ?></a>, <a href="<?php echo base_url(); ?>establishment/state/<?php echo $item->stateID; ?>"><?php echo $item->stateFull; ?></a>
                        </p>
                    </li>
<?php
    }
?>    
                        </ul>
                    </div>
<?php
}

$this->load->view('establishment/more.php');
?>  
    			</div>
    		</div>
        </div>
	</div>        
