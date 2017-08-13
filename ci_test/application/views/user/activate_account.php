
	<div class="container">
		<div class="row">
			<div class="col-md-9">
				<h2 class="brown">Account Activation</h2>
<?php
// check if there was one affected row
if (1)
{
?>
				<div class="alert alert-success" role="alert">
				<p>Your membership is now active and you have access to everything that Two Beer Dudes has to
					offer.  You need to <a href="<?php echo base_url(); ?>user/login">login</a> to access your account
					profile or start making your own ratings.</p>
				</div>
<?php
}
else
{
?>
				<div class="alert alert-danger" role="alert">
					<p>The system experienced a problem trying to activate your membership.  Please
						try again.  If the problem persists please try creating a new account as activation is only
						allowed within 48 hours of account creation.  Also, try logging in as your account may already
						be active.</p>
				</div>
<?php
}
?>
			</div>

			<div class="col-md-3">
    			<div class="side-info">
                     <div class="panel panel-default">
                        <div class="green bold panel-heading">Thank You For Joining</div>
                        <ul class="list_group">
							<li class="list-group-item">Enjoy the site and we look forward to you becoming a
								dude.</li>
							<li class="list-group-item">Now that you have joined we would like to ask you to
								report any problems you have with the site by sending an email to <em>webmaster
								at twobeerdudes dot com</em> with the url of the page and problem you
								expereinced.</li>
						</ul>
                    </div>
    			</div>
    		</div>
        </div>
	</div>
