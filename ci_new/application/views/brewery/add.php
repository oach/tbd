
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-sm-9 col-xs-12">
				<h2 class="brown">Add Establishment</h2>
<?php
if ($reviewCount < MIN_REVIEW_COUNT_FOR_ESTABLISHMENT) {
?>
				<div class="alert alert-danger">
					You haven't reviewed enough beers (you need <?php echo MIN_REVIEW_COUNT_FOR_ESTABLISHMENT; ?> reviews) to add a new beer.
				</div>
<?php
}
else {
	$category[] = set_value('category[]');
	$name = set_value('name');
	$address = set_value('address');
	$city = set_value('city');
	$state = set_value('state');
	$zip = set_value('zip');
	$phone = set_value('phone');
	$url = set_value('url');
	$twitter = set_value('twitter');
?>
	<form id="addEstablishment" class="edit" method="post" action="<?php echo base_url(); ?>brewery/addEstablishment">
<?php
	if (form_error('category[]')) {
?>
		<div class="text-danger"><?php echo form_error('category[]'); ?></div>		
<?php
	}
	$select = $this->load->view('forms/select_multi.php', array(
		'category' => $this->input->post('category'),
		'select_data' => $categories,
		'select_class' => 'category',
		'select_name' => 'category[]'
	), false);
?>	
		<div class="form-group">	
            <label class="sr-only" for="category"><span class="required">*</span> Type of Establishment:</label>
            <?php echo $select; ?>
            <span class="help-block">The type of establishment to be added.</span>
        </div>
<?php
	if (form_error('name')) {
?>
		<div class="text-danger"><?php echo form_error('name'); ?></div>		
<?php
	}
?>	
		<div class="form-group">	
            <label class="sr-only" for="name"><span class="required">*</span> Name:</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="<?php echo $name; ?>">
            <span class="help-block">The name of the establishment.  Please search the site before adding new.</span>
        </div>
<?php
	if (form_error('address')) {
?>
		<div class="text-danger"><?php echo form_error('address'); ?></div>		
<?php
	}
?>	
		<div class="form-group">	
            <label class="sr-only" for="address"><span class="required">*</span> Address:</label>
            <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="<?php echo $address; ?>">
            <span class="help-block">The street address or PO Box of the establishment.</span>
        </div>
<?php
	if (form_error('city')) {
?>
		<div class="text-danger"><?php echo form_error('city'); ?></div>		
<?php
	}
?>	
		<div class="form-group">	
            <label class="sr-only" for="city"><span class="required">*</span> City:</label>
            <input type="text" class="form-control" id="city" name="city" placeholder="City" value="<?php echo $city; ?>">
            <span class="help-block">The city that the establishment is located in.</span>
        </div>
<?php
	if (form_error('state')) {
?>
		<div class="text-danger"><?php echo form_error('state'); ?></div>		
<?php
	}
	$select = $this->load->view('forms/select.php', array(
		'state' => $state,
		'select_data' => $states,
		'select_class' => 'state',
		'multiple' => false
	), false);
?>	
		<div class="form-group">	
            <label class="sr-only" for="state"><span class="required">*</span> State:</label>
            <?php echo $select; ?>
            <span class="help-block">American craft brew site, so only the 50 states.</span>
        </div>
<?php
	if (form_error('zip')) {
?>
		<div class="text-danger"><?php echo form_error('zip'); ?></div>		
<?php
	}
?>	
		<div class="form-group">	
            <label class="sr-only" for="zip"><span class="required">*</span> Zip:</label>
            <input type="text" class="form-control" id="zip" name="zip" placeholder="Zip" value="<?php echo $zip; ?>">
            <span class="help-block">Appropriate zip code of the establishment.</span>
        </div>
<?php
	if (form_error('phone')) {
?>
		<div class="text-danger"><?php echo form_error('phone'); ?></div>		
<?php
	}
?>	
		<div class="form-group">	
            <label class="sr-only" for="phone"><span class="required">*</span> Phone:</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" data-maxlength="10" value="<?php echo $phone; ?>">
            <span class="help-block">
            	Phone number including area code.  Should only be 10 digits: ex. 1234567890.  No spaces, hyphens, etc needed.
            	<span id="chars-remaining" class="label label-success"></span>
            </span>
        </div>
<?php
	if (form_error('url')) {
?>
		<div class="text-danger"><?php echo form_error('url'); ?></div>		
<?php
	}
?>	
		<div class="form-group">	
            <label class="sr-only" for="url"><span class="required">*</span> URL:</label>
            <input type="text" class="form-control" id="url" name="url" placeholder="URL" value="<?php echo $url; ?>">
            <span class="help-block">Web site address for the establishment.  Don't use a slash at the end: ex. http://www.site.com</span>
        </div>
<?php
	if (form_error('twitter')) {
?>
		<div class="text-danger"><?php echo form_error('twitter'); ?></div>		
<?php
	}
?>	
		<div class="form-group">	
            <label class="sr-only" for="twitter"><span class="required">*</span> Twitter:</label>
            <input type="text" class="form-control" id="twitter" name="twitter" placeholder="Twitter" value="<?php echo $twitter; ?>">
            <span class="help-block">Twitter account/username for the establishment.  Don't use @.</span>
        </div>

        <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Add Establishment">
	</form>
<?php
}
?>
			</div>
			<div class="col-md-3 col-sm-3 col-xs-12">
    			<div class="side-info">
    				<div class="panel panel-default">
                        <div class="green bold panel-heading">Create Carefully</div>
                        <ul class="list-group">
		                    <li class="list-group-item">Two Beer Dudes is about American craft beer, please make sure to add American establishments.</li>
		                    <li class="list-group-item">Please check that the establishment doesn&#39;t exist already.  Duplicates create confussion.</li>
                        </ul>
                    </div>
    			</div>
    		</div>
		</div>
	</div>
