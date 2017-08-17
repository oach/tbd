<?php
$ab = '';
?>		
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-xs-12">
				<h2 class="brown">Malted Mail Inbox</h2>
				<div class="alert hide" id="alert" role="alert"></div>
				<div id="malted-mail">
<?php $this->load->view('pms/inbox/pms.php', array('pms' => $pms)); ?>
				</div>
			</div>
            
            <div class="col-md-3 col-xs-12">
    			<div class="side-info">
<?php $this->load->view('user/profile/right.php'); ?>
    			</div>
    		</div>
        </div>
	</div>
