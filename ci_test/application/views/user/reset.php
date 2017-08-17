
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-xs-12">
				<h2 class="brown">Reset Password</h2>
				<p>Request an email with instructions on how to change your password.</p>
<?php $this->load->view('user/reset_form.php'); ?>
			</div>

			<div class="col-md-3 col-xs-12">
    			<div class="side-info">
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">Steps to Resetting:</div>
                        <div class="panel-body">
                            <p class="bold">Security is of the utmost importance to us, therefore we do not store your passwords in plain text.  A new one has to be created and a process followed to insure the security.</p>
							<ol style="padding: 0; padding-left: 14px;">
								<li>Enter your email address for your account and the validation code.</li>
								<li>An email will be sent out to the supplied email address, if it exists*, with instructions on how to change your password.  You will only have four hours to act on this email.</li>
								<li>Follow the instructions to get your new password.</li>
								<li>Login into the system with your new password, you can change it once logged in.</li>
							</ol>
							<p class="small">*If the account doesn&#39;t exist, account email changed, or the account is banned, there will be no email sent out.</p>
                        </div>
                    </div>        			 
    			</div>
    		</div>

		</div>
	</div>