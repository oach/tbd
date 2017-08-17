
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-9 col-xs-12">
                <h2 class="brown">American Craft Beer Styles by <?php echo $user_name; ?></h2>
<?php
if ($total < 1) {
?>                
                <div class="alert alert-warning" role="alert"><strong><?php echo $user_name; ?></strong> has not reviewed any beers and therefore has no style.</div>
<?php
}
else {
?>
                <p class="green"><span class="bold"><?php echo number_format($total); ?></span> Craft Beers Styles Reviewed</p>
                <div id="accordion">
<?php
    if (count($records) > 0) {
        
        foreach ($records as $record) {
?>
                    <h3 data-style-id="<?php echo $record->id; ?>" data-user-id="<?php echo $user_id; ?>"><?php echo $record->style; ?> (<?php echo $record->origin; ?> <?php echo $record->styleType; ?>) <span class="label label-default"><?php echo $record->style_count; ?></span></h3>
                    <div></div>
<?php
        }
    }
?>
                </div>
<?php    
}
?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="side-info">
<?php
if ($user_info['id'] != $user_id) {
?>
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">More of <?php echo $user_name; ?>...</div>
                        <ul class="list-group">
                            <li class="list-group-item"><a href="<?php echo base_url(); ?>user/profile/<?php echo $user_id; ?>">Profile</a></li>
                            <li class="list-group-item"><a href="<?php echo base_url(); ?>user/swaplist/ins/<?php echo $user_id; ?>">Swap Ins</a></li>
                            <li class="list-group-item"><a href="<?php echo base_url(); ?>user/swaplist/outs/<?php echo $user_id; ?>">Swap Outs</a></li>
                            <li class="list-group-item"><a href="<?php echo base_url(); ?>user/pms/create/<?php echo $user_id; ?>"><img alt="send two beer dudes malted mail to <?php echo $user_name; ?>" src="/images/email_icon.jpg"> Send Malted Mail</a></li>
                        </ul>
                    </div>
<?php
}
else {
    //$this->load->view('user/profile/right.php');
    echo 'work to do';
}
?>                    
                </div>
            </div>
        </div>
    </div>

<?php
$this->load->view('user/style_beer_comment_modal.php');
?>
