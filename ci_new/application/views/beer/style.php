
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-9">
<?php
if (isset($styles)) {
	$this->load->view('beer/style/all.php');
}
elseif (isset($id) && $id > 0) {
	$this->load->view('beer/style/by_id.php');
}
?>
			</div>
            
            <div class="col-xs-12 col-sm-12 col-md-3">
    			<div class="side-info">
<?php
if (isset($styles)) {
	$this->load->view('beer/style/all_right.php');
}
elseif (isset($id) && $id > 0) {
	$this->load->view('beer/style/by_id_right.php');
}
?>					    
    			</div>
    		</div>
        </div>
	</div>
