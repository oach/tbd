    
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-9">
<?php
if ($error) {
?>
                <h2 class="brown">Beer Swap List</h2>
                <div class="alert alert-warning" role="alert">There was a problem processing the request.</div>
<?php    
} 
else {
?>
                <h2 class="brown">
                    Beer Swap <?php echo ucfirst(strtolower($type)); ?> List
                    <span class="label label-default"><?php echo $numSwaps; ?> swap <?php echo strtolower($type); ?></span>
                </h2>
                <div class="row">
                    <div class="hidden-xs col-sm-2 col-md-2">
                        <!--<p style="background-image: url(<?php echo $beer_image['source']; ?>); background-position: left 20px; background-repeat: no-repeat; min-height: 370px;">
                        </p>-->
                        <?php echo $beer_image['source']; ?>
                    </div>
                    <div class="col-xs-12 col-sm-10 col-md-10">
                        <p><a class="bold" href="<?php echo base_url(); ?>beer/review/<?php echo $beer->id; ?>/<?php echo $beer->slug_beer; ?>"><?php echo $beer->beerName; ?></a></p>
                        <p><a href="<?php echo base_url(); ?>brewery/info/<?php echo $beer->establishmentID; ?>/<?php echo $beer->slug_establishment; ?>"><?php echo $beer->name; ?></a></p>
                        <p><a href="<?php echo base_url(); ?>beer/style/<?php echo $beer->styleID; ?>"><?php echo $beer->style; ?></a></p>
                    </div>
                </div>
<?php
    if ($numSwaps > 0) {
?>
                <table class="table table-striped table-bordered table-hover table-condensed" style="margin-top: 20px;">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>Dude</th>
                            <th>Date Added</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
        $count = 1;
        foreach ($swaps as $swap) {
?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><a href="<?php echo base_url(); ?>user/profile/<?php echo $swap->userID; ?>"><?php echo $swap->username; ?></a></td>
                            <td><?php echo $swap->swapDate; ?></td>
                        </tr>
<?php
            $count++;
        }
?>
                    </tbody>
                </table>
<?php
    }
    else {
?>
                <div class="alert alert-warning" role="alert" style="margin-top: 20px;">No one currently has this beer on their swap <span class="bold"><?php echo strtolower($type); ?></span> list.</div>
<?php    
    }
}
?>                
            </div>
            
            <div class="col-xs-12 col-sm-12 col-md-3">
                <div class="side-info">
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">More...</div>
                        <ul class="list-group">
                            <li class="list-group-item">Beer swapping is done at your own risk.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
