
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-xs-12">
				<h2 class="brown">Reset Password</h2>
				<div class="alert alert-danger">
					<p>The system experiened a problem trying to create an email for you. Perhaps it is passed the four
					hour grace period to create a new password.  Try <a href="<?php echo base_url(); ?>user/reset">resetting your password</a> again.</p>
				</div>
			</div>

			<div class="col-md-3 col-xs-12">
    			<div class="side-info">
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">Reason for Problems:</div>
                        <div class="panel-body">
                            <ul>
                				<li>The URL sent to your email address is only good for four hours.  Once that time has passed you will have to request another <a href="<?php echo base_url(); ?>user/reset">reset</a>.</li>
                				<li>Maybe the information sent to you was incorrect because of a system error, in that case request another <a href="<?php echo base_url(); ?>user/reset">reset</a>.</li>
                			</ul>
                        </div>
                    </div> 
    			</div>
    		</div>

			<!--<div class="col-md-3 col-xs-12">
				<div class="side-info">
					<div class="green bold panel-heading">Reason for Problems:</div>
                    <ul class="list-group">
        				<li class="list-group-item">The URL sent to your email address is only good for four hours.  Once that time has passed you will have to request another <a href="<?php echo base_url(); ?>user/reset">reset</a>.</li>
        				<li class="list-group-item">Maybe the information sent to you was incorrect because of a system error, in that case request another <a href="<?php echo base_url(); ?>user/reset">reset</a>.</li>
        			</ul>
				</div>
			</div>-->
		</div>
	</div>
