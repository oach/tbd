<?php
if($id > 0 && $id != $user_info['id']) {
?>
	<div class="panel panel-default">
        <div class="green bold panel-heading">User Actions</div>
        <ul class="list-group">
			<li class="list-group-item">
				<img 
					src="<?php echo base_url(); ?>images/person_icon.png" 
					alt="two beer dudes add dude <?php echo $id; ?>">
				<a href="#" id="add_dude" data-id="<?php echo $id; ?>">Add to dude list</a>
			</li>
            <li class="list-group-item">
            	<img
            		src="<?php echo base_url(); ?>images/email_icon.jpg" alt="send two beer dudes malted mail to <?php echo $id; ?>">
            	<a href="<?php echo base_url(); ?>pms/create/<?php echo urlencode($id); ?>">Send malted mail</a>
            </li>
		</ul>
    </div>
<?php
}

if($id > 0 && $id == $user_info['id']) {		
?>

	<div class="panel panel-default">
        <div class="green bold panel-heading">My Profile</div>
        <ul class="list-group">
			<li class="list-group-item"><a href="<?php echo base_url(); ?>user/profile/<?php echo $user_info['id']; ?>">View Profile</a></li>
            <li class="list-group-item"><a href="<?php echo base_url(); ?>user/updateProfile">Update Profile</a></li>
            <li class="list-group-item"><a href="<?php echo base_url(); ?>user/updatePass/<?php echo $user_info['id']; ?>">Update Password</a></li>
		</ul>
    </div>
<?php
}

if (PMS_ACTIVE && $id > 0 && $id == $user_info['id']) {
?>
    <div class="panel panel-default">
        <div class="green bold panel-heading">Malted Mail</div>
        <ul class="list-group">
			<li class="list-group-item"><a href="<?php echo base_url(); ?>pms/view">List Malted Mail</a></li>
            <li class="list-group-item"><a href="<?php echo base_url(); ?>pms/create">Create Malted Mail</a></li>
        </ul>
    </div>
<?php
}

if (SWAPLIST_ACTIVE) {
?>
    <div class="panel panel-default">
        <div class="green bold panel-heading">Beer Swapping</div>
        <ul class="list-group">
			<li class="list-group-item"><a href="<?php echo base_url(); ?>swaplist/show/ins/<?php echo $id > 0 && $id != $user_info['id'] ? $id : $user_info['id']; ?>">Swap Ins</a></li>
            <li class="list-group-item"><a href="<?php echo base_url(); ?>swaplist/show/outs/<?php echo $id > 0 && $id != $user_info['id'] ? $id : $user_info['id']; ?>">Swap Outs</a></li>
        </ul>
    </div>
<?php
}
?>
