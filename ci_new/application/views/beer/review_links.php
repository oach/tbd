<?php
if ($user_info && $beer_info->closed != 1) {
?>
    <p class="bold">
		<a class="btn btn-primary" href="<?php echo base_url(); ?>beer/createReview/<?php echo $beer_info->id; ?>" role="button">Add a Beer Review</a>
    </p>
<?php
} else {
    $args = $this->url->swap_out_uri(array('/'), '_', $this->uri->uri_string());
    if ($beer_info->closed != 1) {
?>
    <p class=" bold">
            <a class="btn btn-primary" href="<?php echo base_url(); ?>user/login/<?php echo $args; ?>" role="button">Add a Beer Review</a>
    </p>
<?php
	}
}
?>