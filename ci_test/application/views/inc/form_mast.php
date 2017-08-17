
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-5" id="loginInfo">
<?php
if ($logged) {
?>
                    <p>
<?php
    if (get_number_malted_mail_unread() > 0) {
?>
                        <span>
                            <a href="/pms/view"><img src="/images/chat_bubble.gif" alt="new private message(s)"></a>
                        </span>
<?php
    }
?>                    
                        <a class="btn btn-success btn-sm" href="/user/profile/<?php echo $user_info['id']; ?>" role="button"><?php echo $user_info['username']; ?></a> 
                        <a class="btn btn-warning btn-sm" href="/user/logout" role="button">logout</a>
                    </p>
        		
<?php
}
else {
?>
                    <a href="/user/createAccount" class="btn btn-small btn-success btn-sm" id="joinButton">Join Now!</a>
                    <a href="/user/login" class="btn btn-small btn-info btn-sm" id="loginButton">Login</a>
<?php
}
?>
                </div>
<?php
$this->load->view('forms/search', $data);
?>    
                
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div id="login-mast" style="background-image: url(<?php echo base_url(); ?>images/header_<?php echo rand(1, HEADER_IMAGE_COUNT); ?>.jpg);">
                        <div id="tag-line-wrapper">
                			<div id="tag-line-wrapper-opacity"></div>
                			<p id="tag-line">Two regular dudes who happen to be huge fans of American craft beer.</p>
                		</div>
                    </div>
                </div>
            </div>
        </div>
