<?php
//echo '<pre>' . print_r($searchRS, true); exit;
$count = 0;
if (isset($searchRS) && !empty($searchRS)) {
    $count = count($searchRS);
}
?>
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-xs-12">
                <h2 class="brown">Search Results<?php echo $count > 0 ? ' <span class="label label-default">' . $count . '</span>': ''; ?></h2>
<?php
$this->load->view('forms/search_full.php');

if (isset($original_search_string)) {
?>
                <p class="searchString">
                    Search Term: <span class="bold"><?php echo $original_search_string; ?></span> in 
                    <span class="bold"><?php echo $type; ?></span>
                </p>
				<p>Words actually searched: <span class="bold"><?php echo implode(' ', $final_search_string); ?></span></p>

<?php
    if ($searchRS) {
?>        
                <table class="table table-striped table-hover table-condensed">
                    <thead>
                        <tr>
                            <td>Result(s)</td>
                        </tr>
                    </thead>
                    <tbody>
<?php               
    	foreach ($searchRS as $item) {
    		switch($type) {
    			case 'user':
?>
                        <tr>
                            <td>
                                <span class="bold"><a href="<?php echo base_url(); ?>user/profile/<?php echo $item->id; ?>"><?php echo $item->name; ?></a></span>
                            </td>
                        </tr>                            
<?php
    				break;
    			case 'establishment':
?>
                        <tr>
                            <td>
                                <span class="bold"><a href="<?php echo base_url(); ?>establishment/info/rating/<?php echo $item->id; ?>/<?php echo $item->slug_establishment; ?>"><?php echo $item->name; ?></a></span><br>
                                <a href="<?php echo base_url(); ?>establishment/city/<?php echo $item->stateID; ?>/<?php echo urlencode($item->city); ?>"><?php echo $item->city; ?></a>, <a href="<?php echo base_url(); ?>establishment/state/<?php echo $item->stateID; ?>"><?php echo $item->stateFull; ?></a>
                            </td>
                        </tr>
<?php
    				break;
    			case 'beer':
    			default:
    				$retired = $item->retired == '1' ? ' <span class="text-danger">(Retired, no longer in production.)</span>' : '';
?>
                        <tr>
                            <td>
                                <a class="bold" href="<?php echo base_url(); ?>beer/review/<?php echo $item->id; ?>/<?php echo $item->slug_beer; ?>"><?php echo $item->beerName; ?></a><?php echo $retired; ?><br>
                                <a href="<?php echo base_url(); ?>brewery/info/<?php echo $item->establishmentID; ?>/<?php echo $item->slug_establishment; ?>"><?php echo $item->name; ?></a> - <a href="<?php echo base_url(); ?>establishment/city/<?php echo $item->stateID; ?>/<?php echo urlencode($item->city); ?>"><?php echo $item->city; ?></a>, <a href="<?php echo base_url(); ?>establishment/state/<?php echo $item->stateID; ?>"><?php echo $item->stateFull; ?></a>
                            </td>
                        </tr>
<?php
    				break;
    		}
    	}
?>
                    </tbody>
                </table>
<?php                
    }
    else {
?>
                <div class="alert alert-danger" role="alert">No results were found fitting the search criteria.</div>
<?php                
    }
}
else {
?>
                <div class="alert alert-danger" role="alert">Nothing to search.</div>
<?php
}
?>


			</div>
    		
            <div class="col-md-3 col-xs-12">
    			<div class="side-info">
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">Search Pointers</div>
                        <div class="panel-body">
                            <ul>
                                <li>Certain words are discarded and not searched as they are considered &#34;common.&#34;</li>
                                <li>Make sure you are spelling words correctly</li>
                            </ul>
                        </div>
                    </div>
                </div>
    		</div>
        </div>
	</div>
	