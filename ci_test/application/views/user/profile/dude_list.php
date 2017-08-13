<?php
if (false !== $dudes && count($dudes) > 0) {
?>
    <div class="panel panel-default" id="dudeList" display="none">
        <div class="green bold panel-heading">Dude List</div>
        <ul class="list-group" id="dude-list-group">
<?php
    foreach ($dudes as $row) {
?>
            <li class="list-group-item">
                <div class="dude-item-container" id="dudeListItemContainer_<?php echo $row['id']; ?>">
                    <a href="<?php echo base_url(); ?>user/pms/create/<?php echo urlencode($row['id']); ?>">
                        <img src="<?php echo base_url(); ?>images/email_icon.jpg" alt="send two beer dudes malted mail to <?php echo $row['username']; ?>">
                    </a>
                    <a href="<?php echo base_url(); ?>user/profile/<?php echo $row['id']; ?>"><?php echo $row['username']; ?></a>
                    <!--<div class="remove-dude" style="display: none;"><button class="btn btn-danger btn-xs" data-id="<?php echo $row['id']; ?>">remove</button></div>-->
                    <button class="btn btn-danger btn-xs remove-dude pull-right" data-id="<?php echo $row['id']; ?>" style="display: none;">
                        <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
                    </button>                    
                </div>
            </li>
<?php
    }
?>   
    	</ul>
    </div>
<?php
}
?>
        