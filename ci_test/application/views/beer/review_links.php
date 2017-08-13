<?php
if ($user_info && $beer_info->closed != 1)
{
?>
    <p class="bold">
		<a href="<?php echo base_url(); ?>beer/createReview/<?php echo $beer_info->id; ?>">Add a Beer Review</a>
		or <a href="<?php echo base_url(); ?>beer/createReview/<?php echo $beer_info->id; ?>/short">Add a Short Beer Review</a>
    </p>
<?php
}
else
{
	
    // substr($this->ci->uri->uri_string(), 1);
    $array = array(
	    'uri' => $this->uri->uri_string()
	    , 'search' => array('/')
	    , 'replace' => '_'
    );
    //$args = swapOutURI($array);
    $args = '';
    if ($beer_info->closed != 1)
    {
?>
    <p class=" bold">
            <a href="<?php echo base_url(); ?>user/login/<?php echo $args; ?>">Add a Beer Review</a>
            or <a href="<?php echo base_url(); ?>user/login/<?php echo $args; ?>">Add a Short Beer Review</a>
    </p>
<?php
	}
}
?>