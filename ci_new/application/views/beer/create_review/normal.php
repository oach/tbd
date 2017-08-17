<?php
if (!empty($beer)) {
?>
	<div class="hidden-xs col-sm-3 col-md-3">
		<p style="background-image: url(<?php echo $beer_image['source']; ?>); background-position: left 20px; background-repeat: no-repeat; min-height: 370px;"></p>
	</div>
	<div class="col-xs-12 col-sm-9 col-md-9">
		<h2 class="brown"><?php echo $beer->beerName; ?> Beer Review</h2>
<?php $this->load->view('beer/create_review/normal_form.php'); ?>	
	</div>
<?php
}
/*								
				
			// holder for the edit text
			//$edit = '';
			// only included for high counts
			if($lowCount == false) {
				// query the ratings table
				$rating = $this->ci->RatingModel->checkForRatingByUserIDBeerID($userInfo['id'], $id);
				//echo '<pre>'; print_r($rating); echo '</pre>';exit;
				// holder for the edit text
				$edit = !empty($rating) ? 'Edit' : '';
			
				if(empty($_POST) && !empty($rating)) {
					// get the form
					// check which type to get
					if($rating['shortrating'] == "1") {
						// they did a short rating
						$form = form_beershortReview(array('id' => $id, 'rating' => $rating));
					} else {
						// did a normal rating
						$form = form_beerReview(array('id' => $id, 'rating' => $rating));
					}					
				} else {
					// get the form
					// check which type to get
					if($type == 'short') {
						// they did a short rating
						$form = form_beerhortReview(array('id' => $id));
					} else {
						$form = form_beerReview(array('id' => $id));
					}					
				}
			}

			$str .= $lowCount == false ? $form . '</div>' : '</div>';
			
			// set the page information
			$seo = getDynamicSEO($config);
			$array = $seo + array('str' => $str);
		} else {
			// the beer is not in the db
			// set the page information
			$seo = getSEO();
			// create screen display error
			$str = '<p>The beer that you are trying to review couldn\'t be found.  Sober up and pay attention!</p>';
			// put the two arrays of information together
			$array = $seo + array('str' => $str);
		}
	}*/
?>