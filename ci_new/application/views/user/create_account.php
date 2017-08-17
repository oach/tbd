
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-xs-12">
				<h2 class="brown">Create User Account</h2>
<?php
if ($logged)
{
?>
				<div class="alert alert-warning" role="alert">
                    <p>You are currently logged and obviously have an account, so you don&#39;t need another.  Stop saucing so much so
                    you realize what you are doing.</p>
                </div>
<?php
}
else
{
    $username = set_value('username');
	$password1 = set_value('password1');
	$password2 = set_value('password2');
	$email = set_value('email');
	$city = set_value('city');
	$state = set_value('state');
	$captcha = set_value('captcha');
?>
                <p>Creating a Two Beer Dudes account is free.  Become a member and share your thoughts, reviews, and 
                ratings of American craft beer with other enthusists.</p>
                <p>Be sure that we will never share your information with any third party providers.  We dislike that
                crap also!</p>
				
                <form action="<?php echo base_url(); ?>user/createAccount" method="post">
<?php
if (form_error('username'))
{
?>
                    <div class="text-danger"><?php echo form_error('username'); ?></div>
<?php
}
?>                
                    <div class="form-group<?php echo (form_error('username') ? 'has-error': '');?>">	
                        <label class="sr-only" for="username"><span class="required">*</span> User Name:</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="User name" value="<?php echo set_value('username'); ?>">
                        <span class="help-block">This will be your name of association on the site.  This is permanent.</span>
                    </div>
<?php
if (form_error('email'))
{
?>
                    <div class="text-danger"><?php echo form_error('email'); ?></div>
<?php
}
?>  
				    <div class="form-group<?php echo (form_error('email') ? 'has-error': '');?>">
                        <label class="sr-only" for="email"><span class="required">*</span> Email Address:</label>
        				<input type="text" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo set_value('email'); ?>">
        				<span class="help-block">Your email address will be used to verify your account, login and use your account.</span>
                    </div>
<?php
if (form_error('password1'))
{
?>
                    <div class="text-danger"><?php echo form_error('password1'); ?></div>
<?php
}
?>
                    <div class="form-group<?php echo (form_error('password1') ? 'has-error': '');?>">
        				<label class="sr-only" for="password1"><span class="required">*</span> Password:</label>
        				<input class="form-control" type="password" id="password1" name="password1" placeholder="Password" value="set_value('password1')">
        				<span class="help-block">Your password needs to be at least six characters in length but not exceed 12.</span>
        			</div>
<?php
if (form_error('password2'))
{
?>
                    <div class="text-danger"><?php echo form_error('password2'); ?></div>
<?php
}
?>
				    <div class="form-group<?php echo (form_error('password2') ? 'has-error': '');?>">
                        <label class="sr-only" for="password2"><span class="required">*</span> Verify Password:</label>
        				<input class="form-control" type="password" id="password2" name="password2" placeholder="Verify Password" value="set_value('password2')">
        				<span class="help-block">Verification of your password from above.</span>
        			</div>
<?php
if (form_error('city'))
{
?>
                    <div class="text-danger"><?php echo form_error('city'); ?></div>
<?php
}
?>
                    <div class="form-group<?php echo (form_error('city') ? 'has-error': '');?>">
                        <label class="sr-only" for="city"><span class="required">*</span> City:</label>
                        <input class="form-control" type="text" id="city" name="city" placeholder="City" value="<?php echo set_value('city'); ?>">
                        <span class="help-block">City in which you live.</span>
                    </div>                    
<?php
if (form_error('state'))
{
?>
                    <div class="text-danger"><?php echo form_error('state'); ?></div>
<?php
}
?>
                    <div class="form-group<?php echo (form_error('state') ? 'has-error': '');?>">
        				<label class="sr-only" for="state"><span class="required">*</span> State:</label>
<?php
$this->load->view('forms/select.php', array('state' => set_value('state')));
?>
                        <span class="help-block">State in which you live.</span>
        			</div>

<?php	
	// captcha
	$vals = array(
		'img_path' => './captcha/'
		, 'img_url' => base_url() . 'captcha/'
		, 'font_path'	 => './font/verdana.ttf',
	);	
	$cap = create_captcha($vals);

	$data = array(
		'captcha_id' => '',
		'captcha_time' => $cap['time'],
		'ip_address' => $this->input->ip_address(),
		'word' => $cap['word']
	);
	
	// run the captcha query
	$this->CaptchaModel->insertCaptcha($data);	

if (form_error('captcha'))
{
?>
                    <div class="text-danger"><?php echo form_error('captcha'); ?></div>
<?php
}
?>

                    <div class="form-group<?php echo (form_error('captcha') ? 'has-error': '');?>">
        				<label class="sr-only" for="captcha"><span class="required">*</span> Security Code:</label>
        				<?php echo $cap['image']; ?>
        				<input class="form-control" type="text" id="captcha" name="captcha" placeholder="Security Code" value="">
        				<span class="help-block">Helps us to keep out bots and other unwanted bits and bytes.</span>
        			</div>
			
                    <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Create Account">
        		</form>
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