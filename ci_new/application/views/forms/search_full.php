	
	<form class="form-inline" method="post" action="<?php echo base_url(); ?>page/search/" style="margin-bottom: 10px;">
        <div class="form-group">
            <select id="search-form-search-type" class="form-control" name="searchType">
<?php
if (isset($search_types) && is_array($search_types) && count($search_types) > 0) {
	foreach ($search_types as $st) {
		$selected = '';
		if (isset($type)) {
			 $selected = $type == $st ? ' selected="selected"' : '';
		}
?>            
                <option value="<?php echo $st; ?>"<?php echo $selected; ?>><?php echo ucfirst($st); ?></option>
<?php
	}
}
?>                
            </select>
        </div>

        <div class="form-group">
            <label class="sr-only" for="search">Search</label>
            <input type="text" id="search-form-search" class="form-control" name="search" placeholder="Search" value="<?php echo isset($original_search_string) ? $original_search_string : ''; ?>">
        </div>
        <button type="submit" id="search-form-search-button" class="btn btn-primary">Search</button>
    </form>
