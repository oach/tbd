<?php
$change = set_value('change');
$comments = set_value('comments');
?>
	<div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-9">
                <h2 class="brown">Update Info for: <?php echo $name; ?></h2>
<?php
if (isset($display)) {
?>
				<div class="alert alert-success">
					<p>Your information has been sent to Rich and Scot at Two Beer Dudes.  We will review the submitted informaton and go from there.  Thank you for your input.  Enjoy!</p>
					<p>You will be redirected in <span id="timer" class="label label-info"></span> seconds</p>
				</div>

				<script>
				$(function()
				{
					var counter = 0;
					var start = 5;
					$('#timer').text(start);
					var interval = setInterval(function()
					{
						counter++;
						$('#timer').text((start - counter));
						if (counter == start)
						{
							clearInterval(interval);
							window.location.replace('<?php echo base_url(); ?><?php echo $updateType; ?>/review/<?php echo $id; ?>');
						}
					}, 1000);
				});
				</script>
<?php
}
else {
?>
				<form method="post" action="<?php echo base_url(); ?>page/updateInfo/<?php echo $updateType . '/' . $id; ?>">
<?php
	if (form_error('change'))
	{
?>
        			<div class="text-danger"><?php echo form_error('change'); ?></div>
<?php
	}
?>                
			        <div class="form-group<?php echo (form_error('style') ? ' has-error': '');?>">	
			            <label class="sr-only" for="change"><span class="required">*</span> Requested Change:</label>
<?php
$this->load->view('forms/select', array(
	'change' => set_value('change'),
	'select_data' => $change_types,
	'select_class' => 'change'
));
?>
			            <span class="help-block">Select the change that you are wanting to have made.</span>
			        </div>	
<?php
	if (form_error('comments'))
	{
?>
        			<div class="text-danger"><?php echo form_error('comments'); ?></div>
<?php
	}	
?>
			        <div class="form-group<?php echo (form_error('comments') ? ' has-error': '');?>">	
			            <label class="sr-only" for="comments"><span class="comments">*</span> Reason:</label>
			            <textarea class="form-control" id="comments" name="comments" placeholder="Reason"><?php echo set_value('comments'); ?></textarea>
			            <span class="help-block">A detailed reason for the requested change.  Be specific.</span>
			        </div>
			
					<input type="submit" class="btn btn-primary" id="submit" name="submit" value="Send Update Info">
				</form>
<?php
}
?>
			</div>
			<div class="col-md-3 col-xs-12">
    			<div class="side-info">
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">Keep the Information Fresh</div>
                        <ul class="list-group">
            				<li class="list-group-item">We will do our best to make updates in a timely fashion but we have to make sure the update is accurate, which could take some time.</li>
            				<li class="list-group-item">Don&#39;t submit an update because you don&#39;t agree with something on the site.  Research, double checking to make sure you have a valid reason for the update.</li>
            			</ul>
                    </div>
    			</div>
    		</div>
        </div>
	</div>
	