<?php
function form_beerShortReview($config) {
	$dateTasted = key_exists('rating', $config) ? $config['rating']['dateTasted'] : set_value('txt_dateTasted');
	$aroma = key_exists('rating', $config) ? $config['rating']['aroma'] : set_value('aroma');
	$taste = key_exists('rating', $config) ? $config['rating']['taste'] : set_value('taste');
	$look = key_exists('rating', $config) ? $config['rating']['look'] : set_value('look');
	$drinkability = key_exists('rating', $config) ? $config['rating']['drinkability'] : set_value('drinkability');
	$haveAnother = key_exists('rating', $config) ? $config['rating']['haveAnother'] : set_value('slt_haveAnother');
	
	// get the code igniter instance
	$ci =& get_instance();

	// create the have another dropdown
	$array = array(
		'data' => array(
			array('id' => '0', 'name' => 'No')
			, array('id' => '1', 'name' => 'Yes')
		)
		, 'id' => 'slt_haveAnother'
		, 'name' => 'slt_haveAnother'
		, 'selected' => $haveAnother
	);
	$haveAnotherDropDown = '<label for="slt_haveAnother"><span class="required">*</span> Have Another:</label>' . createDropDown($array);	
	
	$form = '
		<form id="beer_review_form" class="edit" method="post" action="' . base_url() . 'beer/createReview/' . $config['id'] . '/short">
			<div class="formBlock">
	';
	if(form_error('txt_dateTasted')) {
		$form .= '<div class="formError">' . form_error('txt_dateTasted') . '</div>';
	}
	$form .= '
			
				<label for="txt_dateTasted"><span class="required">*</span> Date Tasted:</label>
				<input type="text" id="txt_dateTasted" name="txt_dateTasted" value="' . $dateTasted . '" />
				<div class="explanation">Date is in yyyy-mm-dd format.  Please use calendar to auto select, it will format appropriately.</div>
			</div>
			
			<div class="formBlock">
	';
	if(form_error('txt_aroma')) {
		$form .= '<div class="formError">' . form_error('txt_aroma') . '</div>';
	}
	$form .= '
				<label for="txt_aroma"><span class="required">*</span> Aroma: <span id="span_aroma"></span></label>
				<div id="slider_aroma" class="slider"><div class="handle"></div></div>
				<input type="hidden" id="txt_aroma" name="aroma" value="' . $aroma . '" />
				<div class="explanation">Select a value between 1 and 10.  This will make up ' . PERCENT_AROMA . '% of the overall score.</div>
			</div>	
			
			<div class="formBlock">
	';
	if(form_error('txt_taste')) {
		$form .= '<div class="formError">' . form_error('txt_taste') . '</div>';
	}
	$form .= '
				<label for="txt_taste"><span class="required">*</span> Taste: <span id="span_taste"></span></label>
				<div id="slider_taste" class="slider"><div class="handle"></div></div>
				<input type="hidden" id="txt_taste" name="taste" value="' . $taste. '" />
				<div class="explanation">Select a value between 1 and 10.  This will make up ' . PERCENT_TASTE . '% of the overall score.</div>
			</div>	
			
			<div class="formBlock">
	';
	if(form_error('txt_look')) {
		$form .= '<div class="formError">' . form_error('txt_look') . '</div>';
	}
	$form .= '
				<label for="txt_look"><span class="required">*</span> Look: <span id="span_look"></span></label>
				<div id="slider_look" class="slider"><div class="handle"></div></div>
				<input type="hidden" id="txt_look" name="look" value="' . $look . '" />
				<div class="explanation">Select a value between 1 and 10.  This will make up ' . PERCENT_LOOK . '% of the overall score.</div>
			</div>	
			
			<div class="formBlock">			
	';
	if(form_error('txt_drinkability')) {
		$form .= '<div class="formError">' . form_error('txt_drinkability') . '</div>';
	}
	$form .= '
				<label for="txt_drinkability"><span class="required">*</span> Drinkability: <span id="span_drinkability"></span></label>
				<div id="slider_drinkability" class="slider"><div class="handle"></div></div>
				<input type="hidden" id="txt_drinkability" name="drinkability" value="' . $drinkability . '" />
				<div class="explanation">Select a value between 1 and 10.  This will make up ' . PERCENT_DRINKABILITY . '% of the overall score.</div>
			</div>
			
			<div class="formBlock">
				<div>
					<p class="bold" style="width: 100%;">Overall Rating: <span id="overallRating" class="required bold" style="text-align: right;"></span></p>
				</div>
			</div>
			
			<div class="formBlock">			
	';			
	if(form_error('slt_haveAnother')) {
		$form .= '<div class="formError">' . form_error('slt_haveAnother') . '</div>';
	}
	$form .= '			
				' . $haveAnotherDropDown . '
				<div class="explanation">Quite simply: would you have another one if presented with the chance.</div>
			</div>
			
			<input type="submit" id="btn_submit" name="btn_submit" value="Submit Short Beer Review" />
		</form>
		<script type="text/javascript">
		/*<![CDATA[*/
		Calendar.setup({
			dateField : \'txt_dateTasted\',
			triggerElement : \'txt_dateTasted\'
		})
		
		var slider_aroma = $(\'slider_aroma\');
		var aroma = $(\'txt_aroma\');
		var span_aroma = $(\'span_aroma\');
		var start_aroma = aroma.getValue() == \'\' ? 1 : aroma.getValue();
		var slider_taste = $(\'slider_taste\');
		var taste = $(\'txt_taste\');
		var span_taste = $(\'span_taste\');
		var start_taste = taste.getValue() == \'\' ? 1 : taste.getValue();
		var slider_look = $(\'slider_look\');
		var look = $(\'txt_look\');
		var span_look = $(\'span_look\');
		var start_look = look.getValue() == \'\' ? 1 : look.getValue();
		var slider_drinkability = $(\'slider_drinkability\');
		var drinkability = $(\'txt_drinkability\');
		var span_drinkability = $(\'span_drinkability\');
		var start_drinkability = drinkability.getValue() == \'\' ? 1 : drinkability.getValue();
		(function() {
			new Control.Slider(slider_aroma.down(\'.handle\'), slider_aroma, {
				axis: \'horizontal\'
				, range: $R(1, 10)
				, minimum: 0
				, alignX: 1
				, increment: 13
				, sliderValue: start_aroma
				, values: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
				, onSlide: function(value) {
					aroma.setValue(value);
					span_aroma.update(\'(\' + value + \')\');
					overallAverage();
				}
				, onChange: function(value) {
					aroma.setValue(value);
					overallAverage();
				}
			});
			
			new Control.Slider(slider_taste.down(\'.handle\'), slider_taste, {
				axis: \'horizontal\'
				, range: $R(1, 10)
				, minimum: 0
				, alignX: 1
				, increment: 13
				, sliderValue: start_taste
				, values: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
				, onSlide: function(value) {
					taste.setValue(value);
					span_taste.update(\'(\' + value + \')\');
					overallAverage();
				}
				, onChange: function(value) {
					taste.setValue(value);
					overallAverage();
				}
			});
			
			new Control.Slider(slider_look.down(\'.handle\'), slider_look, {
				axis: \'horizontal\'
				, range: $R(1, 10)
				, minimum: 0
				, alignX: 1
				, increment: 13
				, sliderValue: start_look
				, values: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
				, onSlide: function(value) {
					look.setValue(value);
					span_look.update(\'(\' + value + \')\');
					overallAverage();
				}
				, onChange: function(value) {
					look.setValue(value);
					overallAverage();
				}
			});
			
			new Control.Slider(slider_drinkability.down(\'.handle\'), slider_drinkability, {
				axis: \'horizontal\'
				, range: $R(1, 10)
				, minimum: 0
				, alignX: 1
				, increment: 13
				, sliderValue: start_drinkability
				, values: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
				, onSlide: function(value) {
					drinkability.setValue(value);
					span_drinkability.update(\'(\' + value + \')\');
					overallAverage();
				}
				, onChange: function(value) {
					drinkability.setValue(value);
					overallAverage();
				}
			});
		})();
		
		Event.observe(window, \'load\', updateFields);
		function updateFields() {
			span_aroma.update(\'(\' + setValue(aroma) + \')\');
			//var tmp = taste.getValue() == \'\' ? 1 : taste.getValue();
			span_taste.update(\'(\' + setValue(taste) + \')\');
			span_look.update(\'(\' + setValue(look) + \')\');
			span_drinkability.update(\'(\' + setValue(drinkability) + \')\');
			
			overallAverage();
		}
		
		function overallAverage() {
			var mth = (aroma.getValue() * (' . PERCENT_AROMA . '/100)) + (taste.getValue() * (' . PERCENT_TASTE . '/100)) + (look.getValue() * (' . PERCENT_LOOK . '/100)) + (drinkability.getValue() * (' . PERCENT_DRINKABILITY . '/100));
			var avg = roundNumber(mth, 1).toFixed(1);
			$(\'overallRating\').update(avg);
		}
		
		function roundNumber(num, dec) {
			var result = Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec);
			return result;
		}
		
		function setValue(el) {
			var tmp = 0;
			if(el.getValue() == \'\') {
				tmp = 1;
				el.value = tmp;
			} else {
				tmp = el.getValue()
			}
			return tmp;
		}
		/*]]>*/
		</script>
                
        <script type="text/javascript">
		$j(document).ready(function() {
            $j(\'#btn_submit\').click(function() {
                $j(this).attr(\'disabled\', \'disabled\').val(\'Processing...\');
                $j(\'#beer_review_form\').submit();
            });
        });
        </script>
	';
	return $form;
}

function form_search() {
    $form = '
        <form id="editBeerForm" class="edit" method="post" action="' . base_url() . 'page/search">
            <select id="slt_searchType" name="slt_searchType">
                <option value="beer">Beer</option>
                <option value="establishment">Establishment</option>
                <option value="user">User</option>
            </select>

            <input type="text" id="txt_search" name="txt_search" />

            <!--<input type="button" id="btn_submit" name="btn_submit" value="" />-->
            <!--<img src="' . base_url() . 'images/search.png" width="60" height="21" alt="search button" />-->
            <input id="img_search" type="image" src="' . base_url() . 'images/search.png" />
        </form>
        <script type="text/javascript">
        $j(document).ready(function() {             
            $j(function() {
                $j(\'#txt_search\').autocomplete({
                    minLength: 1,
                    source: function(request, response) {
                        $j.ajax({
                            url: \'' . base_url() . 'ajax/search_auto_complete/\',
                            data: {
                                term: $j(\'#txt_search\').val()
                                , search_type: $j(\'#slt_searchType\').val()
                            },
                            type: \'POST\',
                            dataType: \'json\',
                            /*success: function(data) {
                                response(data);
                            }*/
                            success: function(data) {
                                response($j.map(data, function(item) {
                                    return {
                                        value: item.name,
                                        url: item.url
                                    }
                                }))
                            }
                        });
                    },
                    select: function(event, ui) {
                        window.location.href = ui.item.url;
                    },                        
                });
            });
        });
        </script>
    ';
    // return the form
    return $form;
}

function indentPreviousMessage($message, $class = '') {
	// check if class is empty
	if(empty($class)) {
		// set it to a generic value
		$class = 'pms_indent';
	}
	return '<div class="' . $class . '">' . $message . '</div>';
}

function createThreadForm($config) {
    $id = $config['id'];
    $type = $config['type'];
    $topic_id = $config['topic_id'];
    $topic_name = $config['topic_name'];
    $sub_topic_name = $config['sub_topic_name'];
    $desc = $config['description'];
    $thread_id = $config['thread_id'];
    $thread_subject = $config['thread_subject'];
    $subject = key_exists('subject', $config) ? $config['subject'] : set_value('subject');
    $message = key_exists('message', $config) ? $config['message'] : set_value('message');
    
    // get the code igniter instance
    $ci =& get_instance();
    
    // holder for h2 header content
    $h2 = '';
    // holder for button text
    $button = '';
    // holder for the uri portion of the url
    $uri = '';
    // determine the h2 header content and button text    
    if($type == 'new_thread') {
        $h2 = '            
            <h2 class="brown">Create New Thread</h2>
            <p class="marginTop_8"><span class="bold"><a href="' . base_url() . 'forum#' . $topic_id . '">' . $topic_name . '</a> -&gt; <a href="' . base_url() . 'forum/dst/' . $id . '">' . $sub_topic_name . '</a> -&gt; ' . $desc . '</span></p>
            <p>A new thread will be created in the forum above.  Are you sure that\'s where you want it?</p>
        ';
        $button = 'Create New Thread';
    } else {
        $h2 = '
            <h2 class="brown">Reply To Thread</h2>
            <p class="marginTop_8"><span class="bold"><a href="' . base_url() . 'forum#' . $topic_id . '">' . $topic_name . '</a> -&gt; <a href="' . base_url() . 'forum/dst/' . $id . '">' . $sub_topic_name . '</a> -&gt; <a href="' . base_url() . 'forum/st/' . $id . '/' . $thread_id . '">' . $thread_subject . '</a></span></p>
            <p>A new reply will be created to the thread above.  Are you sure that\'s where you want it?</p>
        ';
        $button = 'Reply To Thread';
    }
    
    $form = $h2 . '
    <form class="edit" method="post" action="' . base_url() . $ci->uri->uri_string() . '" class="marginTop_8">
        <div class="formBlock">
    ';
    // make sure that subject needs to be shown
    if($type == 'new_thread') {
        if(form_error('txt_subject')) {
            $form .= '<div class="formError">' . form_error('txt_subject') . '</div>';
        }
        $form .= '
                <label for="txt_subject"><span class="required">*</span> Subject:</label>
                <input type="text" id="txt_subject" name="txt_subject" value="' . $subject . '" />
                <div class="explanation">The subject of the thread.</div>
            </div>	

            <div class="formBlock">
            ';
    }
    if(form_error('ttr_message')) {
        $form .= '<div class="formError">' . form_error('ttr_message') . '</div>';
    }
    $form .= '
            <label for="ttr_message"><span class="required">*</span> Message:</label>
            <textarea id="ttr_message" name="ttr_message">' . $message . '</textarea>
            <div class="explanation">What you want to say.</div>
        </div>	

        <input type="submit" id="btn_submit" name="btn_submit" value="' . $button . '" class="marginTop_8" />
    </form>
    ';   
    
    // send back the form
    return $form;
}
?>