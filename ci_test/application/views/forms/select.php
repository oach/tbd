
	<select name="<?php echo $select_class; ?>" id="<?php echo $select_class; ?>" class="form-control">
<?php
$i = 0;
$arr_styles = [];
foreach ($select_data as $data)
{
	if (isset($type) && $type == 'style') {
		if (!in_array($data['origin'] . '_' . $data['styleType'], $arr_styles) && $i > 0) {
?>
		</optgroup>
<?php
		}

		if (!in_array($data['origin'] . '_' . $data['styleType'], $arr_styles)) {
			$arr_styles[] = $data['origin'] . '_' . $data['styleType'];
?>
			<optgroup label="<?php echo $data['origin'] . ' ' . $data['styleType']; ?>">
<?php
		}
	} 
    	$select = $$select_class == $data['id'] ? ' selected="selected"' : '';
		$name = (isset($upper_case)) ? ucwords($data['name']) : $data['name'];
?>
		<option value="<?php echo $data['id']; ?>"<?php echo $select; ?>><?php echo $name; ?></option>
<?php
	$i++;
}
?>
	</select>
