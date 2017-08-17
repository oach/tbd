<?php
if (!empty($allBreweryHops)) {
?>
	<div class="panel panel-default">
        <div class="green bold panel-heading">Recent Brewery Hops</div>
        <ul class="list-group">
<?php
    foreach ($allBreweryHops as $hop) {
?>
            <li class="list-group-item">
                <small>
                	<a href="<?php echo base_url(); ?>brewery/hop/<?php echo $hop->id; ?>"><?php echo $hop->name; ?></a> on <?php echo $hop->hopDate; ?>
                </small>
            </li>
<?php
    }
?>    
        </ul>
    </div>
<?php
}
?>
