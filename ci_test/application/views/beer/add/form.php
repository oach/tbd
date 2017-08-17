<?php
	$beer = set_value('beer');
	$style = set_value('style');
	$seasonal = set_value('seasonal');
	$seasonalPeriod = set_value('seasonalPeriod');
	$alcoholContent = set_value('abv');
	$ibu = set_value('ibu');
    $beerNotes = set_value('beerNotes');
	$malts = set_value('malts');
	$hops = set_value('hops');
	$yeast = set_value('yeast');
	$food = set_value('food');
	$glassware = set_value('glassware');
	$gravity = set_value('gravity');
?>	
	
	<form id="addBeerForm" class="edit" method="post" action="<?php echo base_url(); ?>beer/addBeer/<?php echo $establishment->establishmentID; ?>">
<?php
if (form_error('beer'))
{
?>
        <div class="text-danger"><?php echo form_error('beer'); ?></div>
<?php
}
?>                
        <div class="form-group<?php echo (form_error('beer') ? ' has-error': '');?>">	
            <label class="sr-only" for="beer"><span class="required">*</span> Beer:</label>
            <input type="text" class="form-control" id="beer" name="beer" placeholder="Name of Beer" value="<?php echo set_value('beer'); ?>">
            <span class="help-block">The name of the beer.  Do not add a new beer for vintages (ex. 2010) unless the vintage has changed drastically from the previous year(s).  This is a beer site no mead, cider, etc.</span>
        </div>
<?php
if (form_error('style'))
{
?>
        <div class="text-danger"><?php echo form_error('style'); ?></div>
<?php
}
?>                
        <div class="form-group<?php echo (form_error('style') ? ' has-error': '');?>">	
            <label class="sr-only" for="style"><span class="required">*</span> Style:</label>
<?php
$this->load->view('forms/select', array(
	'style' => set_value('style'),
	'select_data' => $styles,
	'select_class' => 'style',
	'type' => 'style'
));
?>
            <span class="help-block"><a href="http://www.twobeerdudes.com/beer/style">Style</a> of the beer.</span>
        </div>
<?php
if (form_error('seasonal'))
{
?>
        <div class="text-danger"><?php echo form_error('seasonal'); ?></div>
<?php
}
?>                
        <div class="form-group<?php echo (form_error('seasonal') ? ' has-error': '');?>">	
            <label class="sr-only" for="seasonal"><span class="required">*</span> Seasonal:</label>
<?php
$this->load->view('forms/select', array(
	'seasonal' => set_value('seasonal'),
	'select_data' => array(array('id' => '0', 'name' => 'No'), array('id' => '1', 'name' => 'Yes')),
	'select_class' => 'seasonal',
	'type' => ''
));
?>
            <span class="help-block">Whether or not the beer is seasonal.</span>
        </div>

<?php
if (form_error('seasonalPeriod'))
{
?>
        <div class="text-danger"><?php echo form_error('seasonalPeriod'); ?></div>
<?php
}
?>                
        <div class="form-group<?php echo (form_error('seasonalPeriod') ? ' has-error': '');?>"<?php echo ($seasonal == 0 ? ' style="display: none;"' : ''); ?>>	
            <label class="sr-only" for="seasonalPeriod"><span class="required">*</span> Seasonal Period:</label>
            <input type="text" class="form-control" id="seasonalPeriod" name="seasonalPeriod" placeholder="Seasonal Period" value="<?php echo set_value('seasonalPeriod'); ?>">
            <span class="help-block">The actual season of the beer.  Examples: Winter, Special, One-time, Jan - Feb, etc.</span>
        </div>
<?php
if (form_error('abv'))
{
?>
        <div class="text-danger"><?php echo form_error('abv'); ?></div>
<?php
}
?> 
        <div class="form-group<?php echo (form_error('abv') ? ' has-error': '');?>">	
            <label class="sr-only" for="abv"><span class="required">*</span> Alcohol By Volume (ABV):</label>
            <div class="input-group">
            	<input type="text" class="form-control" id="abv" name="abv" placeholder="ABV" value="<?php echo set_value('abv'); ?>">
            	<div class="input-group-addon">%</div>
            </div>
            <span class="help-block">Alcohol By Volume of the beer.  A decimal with one or two places after the decimal.</span>
        </div>
<?php
if (form_error('ibu'))
{
?>
        <div class="text-danger"><?php echo form_error('ibu'); ?></div>
<?php
}
?> 
        <div class="form-group<?php echo (form_error('ibu') ? ' has-error': '');?>">	
            <label class="sr-only" for="ibu"><span class="required">*</span> IBU:</label>
            <input type="text" class="form-control" id="ibu" name="ibu" placeholder="IBU" value="<?php echo set_value('ibu'); ?>">
            <span class="help-block">International Bittering Unit (IBU). Should be an integer value.</span>
        </div>		
               
        <div class="form-group">	
            <label class="sr-only" for="beerNotes"><span class="required">*</span> Beer Notes:</label>
            <textarea class="form-control" id="beerNotes" name="beerNotes" placeholder="Beer Notes"><?php echo set_value('beerNotes'); ?></textarea>
            <span class="help-block">Information about the beer that could help identify, provide more insite, etc.</span>
        </div>

        <div class="form-group">	
            <label class="sr-only" for="malts"><span class="required">*</span> Malts:</label>
            <input type="text" class="form-control" id="malts" name="malts" placeholder="Malts" value="<?php echo set_value('malts'); ?>">
            <span class="help-block">List of the malts used for the beer.  Separate them by a comma.</span>
        </div>

		<div class="form-group">	
            <label class="sr-only" for="hops"><span class="required">*</span> Hops:</label>
            <input type="text" class="form-control" id="hops" name="hops" placeholder="Hops" value="<?php echo set_value('hops'); ?>">
            <span class="help-block">List of the hops used for the beer.  Separate them by a comma.</span>
        </div>

        <div class="form-group">	
            <label class="sr-only" for="yeast"><span class="required">*</span> Yeast:</label>
            <input type="text" class="form-control" id="yeast" name="yeast" placeholder="Yeast" value="<?php echo set_value('yeast'); ?>">
            <span class="help-block">The yeast used for the beer.</span>
        </div>

        <div class="form-group">	
            <label class="sr-only" for="food"><span class="required">*</span> Food:</label>
            <input type="text" class="form-control" id="food" name="food" placeholder="Food" value="<?php echo set_value('food'); ?>">
            <span class="help-block">The yeast used for the beer.</span>
        </div>

        <div class="form-group">	
            <label class="sr-only" for="glassware"><span class="required">*</span> Glassware:</label>
            <input type="text" class="form-control" id="glassware" name="glassware" placeholder="Glassware" value="<?php echo set_value('glassware'); ?>">
            <span class="help-block">Names of glasses that would go best.  Separate them by a comma.</span>
        </div>
<?php
if (form_error('gravity'))
{
?>
        <div class="text-danger"><?php echo form_error('gravity'); ?></div>
<?php
}
?> 
        <div class="form-group<?php echo (form_error('gravity') ? ' has-error': '');?>">	
            <label class="sr-only" for="gravity"><span class="required">*</span> Gravity:</label>
            <input type="text" class="form-control" id="gravity" name="gravity" placeholder="Gravity" value="<?php echo set_value('gravity'); ?>">
            <span class="help-block">Should be a decimal value that is the gravity of the beer.</span>
        </div>
			
		<input type="submit" class="btn btn-primary" id="submit" name="submit" value="Add Beer">
	</form>
