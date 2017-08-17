<?php
$firstname = isset($user_profile) ? $user_profile['firstname'] : set_value('firstname');
$lastname = isset($user_profile) ? $user_profile['lastname'] : set_value('lastname');
$city = isset($user_profile) ? $user_profile['city'] : set_value('city');
$state = isset($user_profile) ? $user_profile['stateID'] : set_value('state');
$notes = isset($user_profile) ? $user_profile['notes'] : set_value('notes');
?>
	<div class="container">
		<div class="row">
			<div class="col-md-9">
                <h2 class="brown">Update Profile: <?php echo $user_info['username']; ?></h2>

				<form action="<?php echo base_url(); ?>user/updateProfile" method="post">
<?php
if (form_error('firstname'))
{
?>
					<div class="text-danger"><?php echo form_error('firstname'); ?></div>		
<?php
}
?>	
					<div class="form-group<?php echo (form_error('firstname') ? ' has-error': '');?>">	
                        <label class="sr-only" for="firstname"><span class="required">*</span> First Name:</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" value="<?php echo $firstname; ?>">
                        <span class="help-block">Simple: your first name.</span>
                    </div>
<?php
if (form_error('lastname'))
{
?>		
					<div class="text-danger"><?php echo form_error('lastname'); ?></div>
<?php		
}
?>	
					<div class="form-group<?php echo (form_error('lastname') ? ' has-error': '');?>">	
                        <label class="sr-only" for="lastname"><span class="required">*</span> Last Name:</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" value="<?php echo $lastname; ?>">
                        <span class="help-block">Simple: your last name.</span>
                    </div>
<?php
if (form_error('city'))
{
?>		
					<div class="text-danger"><?php echo form_error('city'); ?></div>
<?php		
}
?>	
					<div class="form-group<?php echo (form_error('city') ? ' has-error': '');?>">	
                        <label class="sr-only" for="city"><span class="required">*</span> City:</label>
                        <input type="text" class="form-control" id="city" name="city" placeholder="City" value="<?php echo $city; ?>">
                    </div>
<?php
if (form_error('state'))
{
?>		
					<div class="text-danger"><?php echo form_error('state'); ?></div>
<?php		
}
?>	
					<div class="form-group<?php echo (form_error('state') ? ' has-error': '');?>">  
                        <label class="sr-only" for="state"><span class="required">*</span> State:</label>
<?php
$this->load->view('forms/select.php', array('state' => $state));
?>
                    </div>
<?php
if (form_error('notes'))
{
?>		
					
                    <div class="text-danger"><?php echo form_error('notes'); ?></div>
<?php		
}
?>	
					<div class="form-group<?php echo (form_error('notes') ? ' has-error': '');?>">	
                        <label class="sr-only" for="notes"><span class="required">*</span> Notes:</label>
                        <textarea id="notes" name="notes" class="form-control" rows="3"><?php echo $notes; ?></textarea>
                        <span class="help-block">
                        	Ideas: favorite <a href="<?php echo base_url(); ?>beer/style">style(s)</a>, can\'t miss 
                        	<a href="<?php echo base_url(); ?>brewery/hop">brewery hops</a>, generic beer info, etc. (be creative).
                        </span>
                    </div>
		
					<input type="submit" class="btn btn-primary" id="btn_submit" name="submit" value="Update Profile">
				</form>
			</div>

    		<div class="col-md-3 cols-xs-12">
    			<div class="side-info">
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">Personal Information Privacy</div>
                        <ul class="list-group">
            				<li class="list-group-item">Nothing here is shared without outside vendors.</li>
                            <li class="list-group-item">The information below is only shown to other members on the site when they visit your profile page.</li>
                            <li class="list-group-item">Don't want anything to display, leave it blank.</li>
                            <li class="list-group-item">State is the only required piece of information.</li>
            			</ul>
                    </div>
<?php echo $this->load->view('user/profile/right.php'); ?>                    
    			</div>
    		</div>
        </div>
	</div>
