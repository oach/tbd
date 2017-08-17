<?php
$email = set_value('email');
?>	
				<form action="<?php echo base_url(); ?>user/reset/" method="post">
<?php
if (!empty($error))
{
		echo $error;
}

if (form_error('email'))
{
?>
					<div class="text-danger"><?php echo form_error('email'); ?></div>
<?php		
}
?>
					<div class="form-group<?php echo (form_error('email') ? 'has-error': '');?>">	
                        <label class="sr-only" for="email"><span class="required">*</span> Email Address:</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Email Address" value="<?php echo $email; ?>">
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
$this->CaptchaModel->insertCaptcha($data);	
		
if (form_error('captcha'))
{
?>
                    <div class="text-danger"><?php echo form_error('captcha'); ?></div>
<?php
}
?>
					<div class="form-group<?php echo (form_error('captcha') ? 'has-error': '');?>">
        				<label class="sr-only" for="txt_captcha"><span class="required">*</span> Security Code:</label>
        				<?php echo $cap['image']; ?>
        				<input class="form-control" type="text" id="txt_captcha" name="captcha" placeholder="Security Code" value="">
        				<span class="help-block">Helps us to keep out bots and other unwanted bits and bytes.</span>
        			</div>
			
                    <input type="submit" class="btn btn-primary" id="btn_submit" name="submit" value="Reset Password">
        		</form>
