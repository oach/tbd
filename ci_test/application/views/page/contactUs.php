
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-xs-12">
                <h2 class="brown">Contact The Two Beer Dudes</h2>
<?php
$name = set_value('name');
$email = set_value('txt_email');
$comments = set_value('ttr_comments');
?> 
                <form method="post" action="<?php echo base_url(); ?>page/contactUs">
<?php
if (form_error('name'))
{
?>        
                    <div class="text-danger"><?php echo form_error('name'); ?></div>
<?php
}    
?>
                    <div class="form-group<?php echo (form_error('name') ? 'has-error': '');?>">    
                        <label class="sr-only" for="name"><span class="required">*</span> User Name:</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="<?php echo $name; ?>">
                        <span class="help-block">Your name.</span>
                    </div>
<?php
if (form_error('email'))
{
?>    
                    <div class="text-danger"><?php echo form_error('email'); ?></div>
<?php
}    
?>
                    <div class="form-group<?php echo (form_error('email') ? 'has-error': '');?>">    
                        <label class="sr-only" for="email"><span class="required">*</span> User Name:</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Email address" value="<?php echo $email; ?>">
                        <span class="help-block">Your email address so we can send some beer goodness back.</span>
                    </div>
<?php
if (form_error('comments'))
{
?>
                    <div class="text-danger"><?php echo form_error('comments'); ?></div>
<?php
}
?>
                    <div class="form-group<?php echo (form_error('comments') ? 'has-error': '');?>">    
                        <label class="sr-only" for="comments"><span class="required">*</span> User Name:</label>
                        <textarea id="comments" name="comments" class="form-control" placeholder="Comments"><?php echo $comments; ?></textarea>
                        <span class="help-block">What&#39;s up?</span>
                    </div>
<?php
$vals = array(
    'img_path' => './captcha/'
    , 'img_url' => base_url() . 'captcha/'
    , 'font_path'    => './font/verdana.ttf',
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
            
                    <input type="submit" class="btn btn-primary" id="btn_submit" name="submit" value="Contact Us">
                </form>
    		</div>

<?php $this->load->view('page/contact_us_right.php'); ?>

		</div>
	</div>
