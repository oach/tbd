
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-xs-12">
				
				<h2 class="brown">Member Login</h2>
				<p>Login to your account using your email address and password you supplied while creating your account.</p>
<?php
$email = set_value('email');
$password = set_value('password');
?>
			
                
                <form action="<?php echo base_url(); ?>user/login/<?php echo $uri; ?>" method="post">
<?php
$has_error_email = '';
$has_error_password = '';
if (!empty($error) && $error === true)
{
    $has_error_email = ' has-error';
    $has_error_password = ' has-error';
?>
                    <div class="alert alert-danger">The email address and/or password you provided are incorrect.</div>
<?php
}

if (form_error('email'))
{
    $has_error_email = ' has-error';
?>
                    <div class="text-danger"><?php echo form_error('email'); ?></div>
<?php
}
?>
                    <div class="form-group<?php echo $has_error_email; ?>">	
                        <label class="sr-only" for="txt_email"><span class="required">*</span> Email Address:</label>
                        <input type="text" class="form-control" id="txt_email" name="email" placeholder="Email address" value="<?php echo $email; ?>">
                    </div>

<?php	
if (form_error('password'))
{
    $has_error_password = ' has-error';
?>
                    <div class="text-danger"><?php echo form_error('password'); ?></div>
<?php
}
?>
                    <div class="form-group<?php echo $has_error_password; ?>">	
                        <label class="sr-only" for="pwd_password"><span class="required">*</span> Password:</label>
                        <input type="password" class="form-control" id="pwd_password" name="password" placeholder="Password" value="' . $password . '" />
				        <p class="help-block"><a href="<?php echo base_url(); ?>user/reset">Forgot your password?</a></p>
                    </div>
				
                    <input type="submit" class="btn btn-primary" id="btn_submit" name="submit" value="Login">
                </form>
            </div>

    		<div class="col-md-3 col-xs-12">
    			<div class="side-info">
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">Not A Member?</div>
                        <div class="panel-body">
                            <ul>
                				<li><a href="<?php echo base_url(); ?>user/createAccount">Create Account</a></li>
                			</ul>
                        </div>
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
    			</div>
    		</div>
        </div>
	</div>
