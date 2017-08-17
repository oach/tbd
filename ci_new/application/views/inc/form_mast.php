
        <!--<div class="container">
            <div class="row">-->
                <div class="col-xs-12 col-md-4" id="loginInfo">
                    <div class="pull-xs-left pull-sm-left pull-md-right pull-lg-right">
<?php
if ($logged) {
    if (get_number_malted_mail_unread() > 0) {
?>
                        <a class="btn btn-primary btn-sm" href="/pms/view" data-toggle="tooltip" data-placement="right" title="New private message."><span class="sr-only">Private Messages </span><span class="glyphicon glyphicon-comment"></span></a>
<?php
    }
?>                    
                        <a class="btn btn-success btn-sm" href="/user/profile/<?php echo $user_info['id']; ?>" role="button" data-toggle="tooltip" data-placement="right" title="Go to <?php echo $user_info['username']; ?>'s account."><span class="hidden-xs"><?php echo $user_info['username']; ?> </span><span class="glyphicon glyphicon-user" aria-hidden="true"></a>
        		
<?php
} else {
?>
                        <a href="/user/login" class="btn btn-small btn-primary btn-sm" id="loginButton">Log in <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span></a>
                        <a href="/user/createAccount" class="btn btn-small btn-success btn-sm" id="joinButton">Join now <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>                    
<?php
}
$this->load->view('forms/search', $data);
?>    
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="hidden-xs col-md-12">
                    <div id="login-mast" style="background-image: url(<?php echo base_url(); ?>images/header_<?php echo rand(1, HEADER_IMAGE_COUNT); ?>.jpg);">
                        <div id="tag-line-wrapper">
                			<div id="tag-line-wrapper-opacity"></div>
                			<p id="tag-line">Two regular dudes who happen to be huge fans of American craft beer.</p>
                		</div>
                    </div>
                </div>
            </div>
        </div>
