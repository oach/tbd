
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-9 col-xs-12">
                <h2 class="brown">American Establishments Visited by <?php echo $user->username; ?></h2>
<?php
if ($total < 1) {
?>                
                <div class="alert alert-warning" role="alert"><?php echo $user_name; ?> has not reviewed any establishments.</div>
<?php
}
else {
?>
                <p class="green"><span class="bold"><?php echo number_format($total); ?></span> American Establishments Reviewed</p>
                <div id="accordion">
<?php
    if (count($records) > 0) {
        
        foreach ($records as $record) {
?>
                    <h3 data-category-id="<?php echo $record->categoryID; ?>" data-user-id="<?php echo $user->id; ?>"><?php echo $record->name; ?> <span class="label label-default"><?php echo $record->rated_establishments; ?></span></h3>
                    <div>stuff</div>
<?php
        }
    }
}
?>
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="side-info">
<?php
if ($user_info['id'] != $user->id) {
?>
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">More of <?php echo $user->username; ?>...</div>
                        <ul class="list-group">
                            <li class="list-group-item"><a href="<?php echo base_url(); ?>user/profile/<?php echo $user->id; ?>">Profile</a></li>
                            <li class="list-group-item"><a href="<?php echo base_url(); ?>user/swaplist/ins/<?php echo $user->id; ?>">Swap Ins</a></li>
                            <li class="list-group-item"><a href="<?php echo base_url(); ?>user/swaplist/outs/<?php echo $user->id; ?>">Swap Outs</a></li>
                            <li class="list-group-item"><a href="<?php echo base_url(); ?>user/pms/create/<?php echo $user->id; ?>"><img alt="send two beer dudes malted mail to <?php echo $user_name; ?>" src="/images/email_icon.jpg"> Send Malted Mail</a></li>
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
/*
$config = array(
    'user_name' => $user_name
    , 'seoType' => 'user_beer'
);
$seo = getDynamicSEO($config);
*/
?>
