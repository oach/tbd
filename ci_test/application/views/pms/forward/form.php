<?php
if (isset($message_info) && empty($this->input->post()))
{
    $to = $message_info->username;
    $subject = $this->visitor->adjustMailSubject($message_info->subject, 'fwd');
    $message = $this->visitor->markupPreviousMessage($message_info);
}
else
{
    $to = set_value('to');
    $subject = set_value('subject');
    $message = set_value('message');
}
?>
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-xs-12">
				<h2 class="brown">Forward: <?php echo $message_info->subject; ?></h2>

				<form class="edit" method="post" action="<?php echo base_url(); ?>pms/<?php echo $formType; ?>/<?php echo $message_info->id; ?>">
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
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" value="<?php echo $subject; ?>" readonly="readonly">
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
                        <textarea class="form-control" id="message" name="message" placeholder="Message" rows="6" maxlength="<?php echo PRIVATE_MESSAGE_MAX_LENGTH; ?>"><?php echo $message; ?></textarea>
                        <span class="help-block">The body of your message.  Maximum length: <?php echo PRIVATE_MESSAGE_MAX_LENGTH; ?>. <span id="chars-remaining"></span>.</span>
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

	<script src="/js/malted_mail.js"></script>
    <script>
    $(function()
    {
        $('#message').on('focus', function()
        {
            var data = $(this).val();
            $(this).focus().val('').val(data);
        });
    });
    </script>
