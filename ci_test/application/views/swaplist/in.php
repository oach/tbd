
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-9">
				<h2 class="brown">Swap <?php echo ucfirst($swap_type); ?> List</h2>
				<div class="alert hide" id="alert" role="alert"></div>
				<div id="swap-list-container">
<?php $this->load->view('swaplist/data.php'); ?>
				</div>
			</div>
            
            <div class="col-xs-12 col-sm-12 col-md-3">
    			<div class="side-info">
<?php $this->load->view('user/profile/right.php'); ?>
    			</div>
    		</div>
        </div>
	</div>

	<script src="/js/swaplist.js"></script>
 