<?php
    $ins = $outs = ' href="' . base_url() . 'user/login"';
    if ($user_info) {
        $ins = $outs = ' href="#" data-id="' . $beer_info->id . '"';
        //$outs = ' href="#" data-id="' . $beer_info->id . '"';
    }
// onclick="new Ajax.Request(\'' . base_url() . 'ajax/swapadd/ins/' . $id . '\', {asynchronous: true, evalScripts: true, method: \'get\', onLoading: function() {showSpinner(\'swapsInfo\');}, onComplete: function(response) {$(\'swapsInfo\').update(response.responseText);}}); return false;"    
// onclick="new Ajax.Request(\'' . base_url() . 'ajax/swapadd/outs/' . $id . '\', {asynchronous: true, evalScripts: true, method: \'get\', onLoading: function() {showSpinner(\'swapsInfo\');}, onComplete: function(response) {$(\'swapsInfo\').update(response.responseText);}}); return false;"// 
?>
    <li class="list-group-item">
        <a class="btn btn-primary btn-xs swap" data-type="ins"<?php echo $ins; ?>><span class="glyphicon glyphicon-plus"></span> swap ins</a>
        <a class="btn btn-primary btn-xs swap" data-type="outs"<?php echo $outs; ?>><span class="glyphicon glyphicon-plus"></span> swap outs</a>
    </li>

    <li class="list-group-item">
        <a class="btn btn-default btn-xs" href="<?php echo base_url(); ?>beer/swaps/ins/<?php echo $beer_info->id; ?>"><span class="badge"><?php echo $swaps['ins']; ?></span> ins</a> and
        <a class="btn btn-default btn-xs" href="<?php echo base_url(); ?>beer/swaps/outs/<?php echo $beer_info->id; ?>"><span class="badge"><?php echo $swaps['outs']; ?></span> outs</a>
    </li>
