<?php
if (count($records) > 0) {
?>
    <table class="table table-striped table-bordered table-hover table-condensed">
        <thead>
            <tr>
                <th></th>
                <th>Beer/Brewery</th>
                <th class="text-center">Rating</th>
                <th class="text-center hidden-xs" data-toggle="tooltip" data-placement="top" title="Have Another">Date</th>
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
                <td class="text-center"><?php echo $record->image['source']; ?></td>
                <td>
                    <small><a href="<?php echo base_url(); ?>beer/review/<?php echo $record->beer_id; ?>/<?php echo $record->slug_beer; ?>" class="bold"><?php echo $record->beerName; ?></a></small><br>
                    <small><a href="<?php echo base_url(); ?>brewery/info/<?php echo $record->establishment_id; ?>/<?php echo $record->slug_establishment; ?>"><?php echo $record->name; ?></a></small>
                </td>
                <td class="text-center"><?php echo $record->rating; ?></td>
                <td class="text-center hidden-xs"><?php echo $record->dateTasted; ?></td>
                <td class="text-center"><?php echo $comments; ?></td>
            </tr>
<?php
    }
}
?>
        </tbody>
    </table>
