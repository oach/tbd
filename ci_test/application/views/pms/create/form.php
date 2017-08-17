<?php
$to = (isset($nameToSendTo) && empty($this->input->post())) ? $nameToSendTo : set_value('to');
$subject = (isset($subjectText) && empty($this->input->post())) ? $subject : set_value('subject');
$message = (isset($messageText) && empty($this->input->post())) ? $messageText : set_value('message');
?>
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-xs-12">
				<h2 class="brown">Create Malt Mail</h2>

				<form class="edit" method="post" action="<?php echo base_url(); ?>pms/create">
<?php
if (form_error('to'))
{
?>
					<div class="text-danger"><?php echo form_error('to'); ?></div>		
<?php
}
?>	
					<div class="form-group<?php echo (form_error('to') ? ' has-error': '');?>">	
                        <label class="sr-only" for="to"><span class="required">*</span> First Name:</label>
                        <input type="text" class="form-control" id="to" name="to" placeholder="To" value="<?php echo $to; ?>">
                        <span class="help-block">User name of the member you want to send Malted Mail.</span>
                    </div>
<?php
if (form_error('subject'))
{
?>
					<div class="text-danger"><?php echo form_error('subject'); ?></div>		
<?php
}
?>	
					<div class="form-group<?php echo (form_error('subject') ? ' has-error': '');?>">	
                        <label class="sr-only" for="subject"><span class="required">*</span> Subject:</label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" value="<?php echo $subject; ?>">
                        <span class="help-block">What the Malted Mail is about.</span>
                    </div> 
<?php
if (form_error('message'))
{
?>
					<div class="text-danger"><?php echo form_error('message'); ?></div>		
<?php
}
?>	
					<div class="form-group<?php echo (form_error('message') ? ' has-error': '');?>">	
                        <label class="sr-only" for="message"><span class="required">*</span>Message:</label>
                        <textarea class="form-control" id="message" name="message" placeholder="Message" rows="3" maxlength="<?php echo PRIVATE_MESSAGE_MAX_LENGTH; ?>"><?php echo $message; ?></textarea>
                        <span class="help-block">The body of your message.  Maximum length: <?php echo PRIVATE_MESSAGE_MAX_LENGTH; ?>.  <span id="chars-remaining"></span>.</span>
                    </div>

                    <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Send Malted Mail">
				</form>
            </div>
            
            <div class="col-md-3 col-xs-12">
    			<div class="side-info">
<?php $this->load->view('user/profile/right.php'); ?>
    			</div>
    		</div>
        </div>
	</div>
