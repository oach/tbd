<?php
$options = ['beer', 'establishment'];
if ($logged) {
	array_push($options, 'user');
}

if (!isset($search_option_value)) {
    $search_option_value = 'beer';
}
?>
	<select class="input-sm form-control" id="searchType" name="searchType">
<?php
foreach ($options as $option) {
?> 	
        <option value="<?php echo $option; ?>"<?php echo $search_option_value == $option ? ' selected="selected"' : ''; ?>><?php echo ucfirst($option); ?></option>
<?php
}
?>
    </select>
