<?php
if (count($records) > 0) {
?>
    <table class="table table-striped table-bordered table-hover table-condensed">
        <thead>
            <tr>
                <th>Establishment</th>
                <th class="text-center">Rating</th>
                <th class="text-center hidden-xs" data-toggle="tooltip" data-placement="top" title="Date visited">Date</th>
                <th class="text-center">Comments</th>
            </tr>
        </thead>
        <tbody>
<?php        
    foreach ($records as $record) {
        $comments = '';
        if (!empty($record->comments)) {
            $comments = '<a href="#" class="comments" data-rating-id="' . $record->id . '"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>';
        }
?>
            <tr>
                <td>
                    <small><a href="<?php echo base_url(); ?>brewery/info/<?php echo $record->establishmentID; ?>/<?php echo $record->slug_establishment; ?>"><?php echo $record->name; ?></a></small>
                </td>
                <td class="text-center"><?php echo $record->establishment_rating; ?></td>
                <td class="text-center hidden-xs"><?php echo $record->dateVisited; ?></td>
                <td class="text-center"><?php echo $comments; ?></td>
            </tr>
<?php
    }
}
?>
        </tbody>
    </table>