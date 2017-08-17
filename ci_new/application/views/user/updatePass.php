
	<div class="container">
		<div class="row">
			<div class="col-md-9">
                <h2 class="brown">Update Password</h2>
<?php
if (isset($success) || isset($warning))
{
	$type = isset($success) ? 'success' : 'warning';
	$this->load->view('user/update_pass/' . $type . '.php');	
}
else
{
$password1 = set_value('password1');
$password2 = set_value('password2');
?>
				<p>Update your password to a string of letters, characters, and numbers 6 to 12 in length.</p>
				<form action="<?php echo base_url(); ?>user/updatePass/<?php echo $user_info['id']; ?>" method="post">
<?php
	if (form_error('password1'))
	{
?>
					<div class="text-danger"><?php echo form_error('password1'); ?></div>		
<?php
	}
?>	
					<div class="form-group<?php echo (form_error('password1') ? ' has-error': '');?>">	
                        <label class="sr-only" for="password1"><span class="required">*</span> Password:</label>
                        <input type="password" class="form-control" id="password1" name="password1" placeholder="Password" value="">
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

					<div class="form-group<?php echo (form_error('password2') ? ' has-error': '');?>">	
                        <label class="sr-only" for="password2"><span class="required">*</span> Verify Password:</label>
                        <input type="password" class="form-control" id="password2" name="password2" placeholder="Verify Password" value="">
                        <span class="help-block">Verification of your password from above.</span>
                    </div>
			
					<input type="submit" class="btn btn-primary" id="btn_submit" name="submit" value="Update Password">
				</form>
<?php
}
?>
			</div>

    		<div class="col-md-3 cols-xs-12">
    			<div class="side-info">
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">Password Change</div>
                        <ul class="list-group">
            				<li class="list-group-item">This password will be encrypted upon creation for security purposes.</li>
                            <li class="list-group-item">Please store your password in a safe place as it will not be emailed to you or recoverable.</li>
            			</ul>
                    </div>
<?php echo $this->load->view('user/profile/right.php'); ?>                    
    			</div>
    		</div>
        </div>
	</div>
