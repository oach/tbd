
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-9 col-xs-12">
                <h2 class="brown">American Craft Beers Rated by <?php echo $user_name; ?></h2>
<?php
if ($total < 1) {
?>                
                <div class="alert alert-warning" role="alert"><?php echo $user_name; ?> has not reviewed any beers.</div>
<?php
}
else {
    $pagination = '';
    $num_pages = $total / USER_BEER_REVIEWS_PAGINATION;
    if ($num_pages > 1) {
        $config['base_url'] = base_url() . 'user/beer/' . $user_name;
        $config['total_rows'] = $total;
        $config['per_page'] = USER_BEER_REVIEWS_PAGINATION;
        $config['uri_segment'] = 4;
        $config['num_links'] = 2;
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination pagination-sm pull-right">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['full_tag_open'] = '<ul class="pagination pagination-sm pull-right">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active">';
        $config['cur_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        $pagination = $this->pagination->create_links();
    }
?>
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <p class="green">
                            <span class="bold"><?php echo number_format($total); ?></span> American Craft Beers Reviewed
                        </p>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <?php echo $pagination; ?>
                    </div>
                </div>
<?php
    if (count($records) > 0) {
?>
                <table class="table table-striped table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th data-toggle="tooltip" data-placement="top" title="Have Another">H.A.</th>
                            <th>Beer/Brewery</th>
                            <th>Style</th>
                            <th class="text-center">Rating</th>
                            <th class="text-center" data-toggle="tooltip" data-placement="top" title="Alcohol By Volume">ABV</th>
                            <th class="text-center">IBU</th>
                            <th class="text-center" data-toggle="tooltip" data-placement="top" title="Have Another">Date</th>
                        </tr>
                    </thead>
                    <tbody>
<?php        
        foreach ($records as $record) {
            //echo '<pre>' . print_r($record, true); exit;
            $have_another = $record->haveAnother == "0" ? 'haveanother_no25.jpg' : 'haveanother_yes25.jpg' ;
?>
                        <tr>
                            <td><img src="/images/<?php echo $have_another; ?>"></td>
                            <td>
                                <small><a href="<?php echo base_url(); ?>beer/review/<?php echo $record->beer_id; ?>/<?php echo $record->slug_beer; ?>" class="bold"><?php echo $record->beerName; ?></a></small><br>
                                <small><a href="<?php echo base_url(); ?>brewery/info/<?php echo $record->establishment_id; ?>/<?php echo $record->slug_establishment; ?>"><?php echo $record->name; ?></a></small>
                            </td>
                            <td><a href="<?php echo base_url(); ?>beer/style/<?php echo $record->style_id; ?>"><?php echo $record->style; ?></a></td>
                            <td class="text-center"><?php echo number_format($record->rating, 2); ?></td>
                            <td class="text-center"><?php echo (!empty($record->alcoholContent) ? $record->alcoholContent : '-'); ?></td>
                            <td class="text-center"><?php echo (!empty($record->ibu) ? $record->ibu : '-'); ?></td>
                            <td class="text-center"><?php echo $record->dateTasted; ?></td>
                        </tr>
<?php
        }
    }
}
?>
                    </tbody>
                </table>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12"><?php echo $pagination; ?></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="side-info">
<?php
if ($user_info['id'] != $user_id) {
?>
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">More of <?php echo $user_name; ?>...</div>
                        <ul class="list-group">
                            <li class="list-group-item"><a href="<?php echo base_url(); ?>user/profile/<?php echo $user_id; ?>">Profile</a></li>
                            <li class="list-group-item"><a href="<?php echo base_url(); ?>user/swaplist/ins/<?php echo $user_id; ?>">Swap Ins</a></li>
                            <li class="list-group-item"><a href="<?php echo base_url(); ?>user/swaplist/outs/<?php echo $user_id; ?>">Swap Outs</a></li>
                            <li class="list-group-item"><a href="<?php echo base_url(); ?>user/pms/create/<?php echo $user_id; ?>"><img alt="send two beer dudes malted mail to <?php echo $user_name; ?>" src="/images/email_icon.jpg"> Send Malted Mail</a></li>
                        </ul>
                    </div>
<?php
}
else {
    //$this->load->view('user/profile/right.php');
    echo 'work to do';
}
?>                    
                </div>
            </div>
        </div>
    </div>

    <script src="/js/tooltip.js"></script>
<?php
/*          
$config = array(
    'user_name' => $user_name
    , 'seoType' => 'user_beer'
);
$seo = getDynamicSEO($config);
*/

/*
<script>
$(function() { 
    // call the tablesorter plugin 
    $('#rated_by').tablesorter({
        sortList: [[1, 0]],
        headers: { 
            // assign the secound column (we start counting zero) 
            0: { 
                // disable it by setting the property sorter to false 
                sorter: false 
            }                            
        } 
    }); 
});
</script>
*/
?>        
