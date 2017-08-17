<?php
if(!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Beers {
	private $ci;
	private $title = 'Beers';
	
	public function __construct() {
		$this->ci =& get_instance();
	}
	
	public function getAllBeers() {
		// get all the beers
		$beers = $this->ci->BeerModel->getAll();
		// start the output
		$str = '';
		// iterte through the list
		foreach($beers as $beer) {
			$alcoholContent = empty($beer['alcoholContent']) ? '&nbsp;' : $beer['alcoholContent'] . '%';
			$seasonal = empty($beer['seasonal']) ? 'No' : 'Yes';
			$img = checkForImage(array('picture' => $beer['picture'], 'id' => $beer['id']));
			$str .= '
					<div id="item_' . $beer['id'] . '" class="item">
						<div id="item_list_container_' . $beer['id'] . '" class="list_itemContainer">					
							' . $img . '
							<dl class="dl_tableDisplay">
								<dt>Beer:</dt><dd>' . $beer['beerName'] . '</dd>
								<dt>Brewery:</dt><dd><a href="' . $beer['url'] . '" target="_blank">' . $beer['name'] . '</a></dd>
								<dt>Style:</dt><dd>' . $beer['style'] . '</dd>
								<dt>ABV:</dt><dd>' . $alcoholContent . '</dd>
			';
			$str .= empty($beer['malts']) ? '' : '<dt>Malts:</dt><dd>' . $beer['malts'] . '</dd>';
			$str .= empty($beer['hops']) ? '' : '<dt>Hops:</dt><dd>' . $beer['hops'] . '</dd>';
			$str .= empty($beer['yeast']) ? '' : '<dt>Yeast:</dt><dd>' . $beer['yeast'] . '</dd>';
			$str .= empty($beer['gravity']) ? '' : '<dt>Gravity:</dt><dd>' . $beer['gravity'] . '</dd>';
			$str .= empty($beer['ibu']) ? '' : '<dt>IBU:</dt><dd>' . $beer['ibu'] . '</dd>';
			$str .= empty($beer['food']) ? '' : '<dt>Food:</dt><dd>' . $beer['food'] . '</dd>';
			$str .= empty($beer['glassware']) ? '' : '<dt>Glassware:</dt><dd>' . $beer['glassware'] . '</dd>';
			$str .= '
								<dt>Seasonal:</dt><dd>' . $seasonal
			;
			$str .= $seasonal == 'No' ? '' : ' - ' . $beer['seasonalPeriod'];
			$str .= '
								</dd>
							</dl>
							<br class="both" />
						</div>
						<ul id="list_links_' . $beer['id'] . '" class="list_horizontalLinks">
							<li><a href="#" id="edit_' . $beer['id'] . '" onclick="new Ajax.Request(\'' . base_url() . 'ajax/edit/beer/' . $beer['id'] . '\', {asynchronous: true, evalScripts: true, method: \'post\', onLoading: function() {showSpinner(\'item_list_container_' . $beer['id'] . '\');}, onComplete: function(response) {$(\'item_list_container_' . $beer['id'] . '\').update(response.responseText); $(\'edit_' . $beer['id'] . '\').style.display=\'none\'; $(\'cancel_' . $beer['id'] . '\').style.display=\'block\';}}); return false;">Edit</a></li>
							<li><a href="#" id="cancel_' . $beer['id'] . '" onclick="new Ajax.Request(\'' . base_url() . 'ajax/cancel/beer/' . $beer['id'] . '\', {asynchronous: true, evalScripts: true, method: \'get\', onLoading: function() {showSpinner(\'item_list_container_' . $beer['id'] . '\');}, onComplete: function(response) {$(\'item_list_container_' . $beer['id'] . '\').update(response.responseText); $(\'cancel_' . $beer['id'] . '\').style.display=\'none\'; $(\'edit_' . $beer['id'] . '\').style.display = \'block\';}}); return false;" style="display: none;">Cancel</a></li>
						</ul>
						<br class="both" />
					</div>
			';
		}
		// return the output
		return $str;
	}
	
	public function getBeerByID($id) {
		// get the specific brewery
		$beer = $this->ci->BeerModel->getBeerByID($id);
		$alcoholContent = empty($beer['alcoholContent']) ? '&nbsp;' : $beer['alcoholContent'] . '%';
		$seasonal = empty($beer['seasonal']) ? 'No' : 'Yes';
		$img = checkForImage(array('picture' => $beer['picture'], 'id' => $id));
		$str = $img . '
					<dl class="dl_tableDisplay">
						<dt>Beer:</dt><dd>' . $beer['beerName'] . '</dd>
						<dt>Brewery:</dt><dd><a href="' . $beer['url'] . '" target="_blank">' . $beer['name'] . '</a></dd>
						<dt>Style:</dt><dd>' . $beer['style'] . '</dd>
						<dt>ABV:</dt><dd>' . $alcoholContent . '</dd>
		';
		$str .= empty($beer['malts']) ? '' : '<dt>Malts:</dt><dd>' . $beer['malts'] . '</dd>';
		$str .= empty($beer['hops']) ? '' : '<dt>Hops:</dt><dd>' . $beer['hops'] . '</dd>';
		$str .= empty($beer['yeast']) ? '' : '<dt>Yeast:</dt><dd>' . $beer['yeast'] . '</dd>';
		$str .= empty($beer['gravity']) ? '' : '<dt>Gravity:</dt><dd>' . $beer['gravity'] . '</dd>';
		$str .= empty($beer['ibu']) ? '' : '<dt>IBU:</dt><dd>' . $beer['ibu'] . '</dd>';
		$str .= empty($beer['food']) ? '' : '<dt>Food:</dt><dd>' . $beer['food'] . '</dd>';
		$str .= empty($beer['glassware']) ? '' : '<dt>Glassware:</dt><dd>' . $beer['glassware'] . '</dd>';
		$str .= '
						<dt>Seasonal:</dt><dd>' . $seasonal . '
		';
		$str .= $seasonal == 'No' ? '' : ' - ' . $beer['seasonalPeriod'];
		$str .= '
						</dd>
					</dl>
					<br class="left" />
		';
		return $str;
	}
	
	public function createForm($config) {
		$beer = array(
			'beerName' => ''
			, 'id' => ''
			, 'alcoholContent' => ''
			, 'malts' => ''
			, 'hops' => ''
			, 'yeast' => ''
			, 'gravity' => ''
			, 'ibu' => ''
			, 'food' => ''
			, 'glassware' => ''
			, 'picture' => ''
			, 'seasonal' => ''
			, 'seasonalPeriod' => ''
			, 'establishmentID' => ''
			, 'styleID' => ''
			, 'btnValue' => 'Add'
			, 'action' => 'action="' . base_url() . 'ajax/addData/brewery/"'
			, 'onsubmit' => 'onsubmit="new Ajax.Request(\'' . base_url() . 'ajax/addData/beer\', {asynchronous: true, evalScripts: true, method: \'post\', parameters: Form.serialize(this), onLoading: function() {showSpinner(\'contents\');}, onComplete: function(response) {$(\'contents\').update(response.responseText);}}); return false;"'
		);
	
		if(key_exists('establishmentID', $config)) {
			$beer['onsubmit'] = 'onsubmit="new Ajax.Request(\'' . base_url() . 'ajax/addData/beer/' . $config['establishmentID'] . '\', {asynchronous: true, evalScripts: true, method: \'post\', parameters: Form.serialize(this), onLoading: function() {showSpinner(\'contents\');}, onComplete: function(response) {$(\'contents\').update(response.responseText);}}); return false;"';
		}
			
		if(key_exists('id', $config)) {
			$beer = $this->ci->BeerModel->getBeerByID($config['id']);
			$beer['btnValue'] = 'Update';
			$beer['action'] = 'action="' . base_url() . 'ajax/editData/beer/' . $config['id'] . '"';
			$beer['onsubmit'] = 'onsubmit="new Ajax.Request(\'' . base_url() . 'ajax/editData/beer/' . $config['id'] . '\', {asynchronous: true, evalScripts: true, method: \'post\', parameters: Form.serialize(this), onLoading: function() {showSpinner(\'item_list_container_' . $beer['id'] . '\');}, onComplete: function(response) {$(\'item_list_container_' . $config['id'] . '\').update(response.responseText); $(\'cancel_' . $config['id'] . '\').style.display=\'none\'; $(\'edit_' . $config['id'] . '\').style.display = \'block\';}}); return false;"';
		}

		$breweryDropDown = '';
		if(key_exists('breweries', $config)) {
			$array = array(
				'data' => $config['breweries']
			, 'id' => 'slt_brewery'
			, 'name' => 'slt_brewery'
			, 'selected' => $beer['establishmentID']
			);
			$breweryDropDown = '<label for="slt_brewery">Brewery:</label>' . createDropDown($array);
		}

		$styleDropDown = '';
		if(key_exists('styles', $config)) {
			unset($array);
			$array = array(
				'data' => $config['styles']
			, 'id' => 'slt_style'
			, 'name' => 'slt_style'
			, 'selected' => $beer['styleID']
			);
			$styleDropDown = '<label for="slt_style">Style:</label>' . createDropDown($array);
		}

		$seasonalDropDown = '';
		//if(key_exists('seasonal', $config)) {
		unset($array);
		$array = array(
			'data' => array(array('id' => '0', 'name' => 'No'), array('id' => '1', 'name' => 'Yes'))
			, 'id' => 'slt_seasonal'
			, 'name' => 'slt_seasonal'
			, 'selected' => $beer['seasonal']
			, 'onchange' => 'onchange="hideShowBasedOnAnother(this, $(\'sp\'));"'
		);
		$seasonalDropDown = '<label for="slt_seasonal">Seasonal:</label>' . createDropDown($array);
		// determine if to show the seaonalPeriod input
		$showSeasonPeriod = $beer['seasonal'] == 0 ? ' style="display: none;"' : '';

		$str = '
			<form id="editBeerForm" class="edit" method="post" ' . $beer['action'] . ' ' . $beer['onsubmit'] . '>
				<label for="txt_beer">Beer:</label>
				<input type="text" id="txt_beer" name="txt_beer" value="' . $beer['beerName'] . '" />				
				
				' . $breweryDropDown . '				
				
				' . $styleDropDown . '
				
				<label for="txt_abv">ABV:</label>
				<input type="text" id="txt_abv" name="txt_abv" value="' . $beer['alcoholContent'] . '" />
				
				<label for="txt_malts">Malts:</label>
				<input type="text" id="txt_malts" name="txt_malts" value="' . $beer['malts'] . '" />
				
				<label for="txt_hops">Hops:</label>
				<input type="text" id="txt_hops" name="txt_hops" value="' . $beer['hops'] . '" />
				
				<label for="txt_yeast">Yeast:</label>
				<input type="text" id="txt_yeast" name="txt_yeast" value="' . $beer['yeast'] . '" />
				
				<label for="txt_gravity">Gravity:</label>
				<input type="text" id="txt_gravity" name="txt_gravity" value="' . $beer['gravity'] . '" />
				
				<label for="txt_ibu">IBU:</label>
				<input type="text" id="txt_ibu" name="txt_ibu" value="' . $beer['ibu'] . '" />
				
				<label for="txt_food">Food:</label>
				<input type="text" id="txt_food" name="txt_food" value="' . $beer['food'] . '" />
				
				<label for="txt_glassware">Glassware:</label>
				<input type="text" id="txt_glassware" name="txt_glassware" value="' . $beer['glassware'] . '" />
				
				' . $seasonalDropDown . '
				
				<span id="sp"' . $showSeasonPeriod . '> 
					<label for="txt_seasonalPeriod">Seasonal Period:</label>
					<input type="text" id="txt_seasonalPeriod" name="txt_seasonalPeriod" value="' . $beer['seasonalPeriod'] . '" />
				</span>
				
				<input type="submit" id="btn_submit" name="btn_submit" value="' . $beer['btnValue'] . '" />
		';
		$str .= key_exists('hidden', $config) && $config['hidden'] == 'rating' ? '<input type="hidden" id="hdn_step" name="hdn_step" value="rate" />' : '';
		$str .= '
			</form>
		';	
		return $str;
	}  
	
	public function uploadImage($item) {
	$str = '
				<form id="editBeerForm" class="edit" method="post" action="' . base_url() . 'admin/cropImage/' . $item['id'] . '" enctype="multipart/form-data">
					<input type="hidden" id="hdn_brewery" name="hdn_brewery" value="' . $item['name'] . '" />
					<input type="hidden" id="hdn_beer" name="hdn_beer" value="' . $item['beerName'] . '" />
					
					<span id="frm_picture_container">
						<label for="fl_picture">Add Image:</label>
						<input type="file" id="fl_picture" name="fl_picture" />	
					</span>
					<span id="spn_spinner"></span>
					<span id="spn_picture_name"></span>
					<input type="hidden" id="hdn_picture" name="hdn_picture" value="" />
					
					<input type="submit" id="btn_submit" name="btn_submit" value="Continue - Crop Image" style="display: none;" />
				</form>
				
				<script type="text/javascript">
				/*<![CDATA[*/
				var button = $(\'fl_picture\');
				document.observe("dom:loaded", function() {
					new Ajax_upload(button,{
						action: \'' . base_url() . 'ajax/uploadFile/beerPic\',
						name: \'beerImage\',
						onSubmit : function(file, ext){
							showSpinner(\'spn_spinner\');
						},
						onComplete: function(file, response){
							// check if this was successful
							if(response.indexOf(\'gif\') != -1 || response.indexOf(\'jpg\') != -1 || response.indexOf(\'png\') != -1) {
								// an image name was returned
								// hide the image
								$(\'spn_spinner\').hide();
								$(\'frm_picture_container\').hide();
								// set the holder with the name of the image that was uploaded
								$(\'spn_picture_name\').show();
								$(\'spn_picture_name\').update(response);
								// set the name of the image to be uploaded in the hidden form field
								$(\'hdn_picture\').value = file;
								// show the submit button
								$(\'btn_submit\').show();
							} else {
								// error was returned
								$(\'spn_spinner\').hide();						
								// set the holder with an error string
								$(\'spn_picture_name\').update(response);
								// set the hidden form field value to an empty string
								$(\'hdn_picture\').value = \'\';
								// place the form element back and give them a chance to upload again
								//$(\'spn_picture\').update(\'<input type="file" id="fl_picture" name="fl_picture" />\');
							}
						}
					});
				});
				/*]]>*/
				</script>	
			';
	return $str;
	}
	
	public function cropImage($item) {
	$str = '
				<img src="' . base_url() . 'images/beers/tmp/' . $item['fileName'] . '" id="cropThisImage" />
							
				<form method="post" action="' . base_url() . 'admin/cropImage/' . $item['id'] . '">
					<input type="hidden" id="x1" name="x1" value="" />
					<input type="hidden" id="x2" name="x2" value="" />
					<input type="hidden" id="y1" name="y1" value="" />
					<input type="hidden" id="y2" name="y2" value="" />
					<input type="hidden" id="width" name="width" value="" />
					<input type="hidden" id="height" name="height" value="" />
					<input type="hidden" id="hdn_fileName" name="hdn_fileName" value="' . $item['fileName'] . '" />
					<input type="submit" id="btn_crop" name="btn_crop" value="Crop Image" disabled="disabled" />
				</form>
				
				<script type="text/javascript">
				/*<![CDATA[*/
				document.observe("dom:loaded", function() {
					new Cropper.Img(
						\'cropThisImage\', {
							ratioDim: {
								x: 150,
								y: 350
							},
							displayOnInit: true,
							onEndCrop: endCrop
						}
					);
				});
				
				function endCrop(coords, dimensions) {
					$(\'x1\').value = coords.x1;
					$(\'x2\').value = coords.x2;
					$(\'y1\').value = coords.y1;
					$(\'y2\').value = coords.y2;
					$(\'width\').value = dimensions.width;
					$(\'height\').value = dimensions.height;
					
					if(dimensions.width == 0 || dimensions.height == 0) {
						$(\'btn_crop\').disabled = true;
					} else {
						$(\'btn_crop\').disabled = false;
					}
				}
				/*]]>*/
				</script>
			';
	
	return $str;
	}
	
	public function showSRM() {
		$str = '';
		// send back the output
		return $str;
	}
	
	public function getTitle() {
		return $this->title;
	}
}
?>