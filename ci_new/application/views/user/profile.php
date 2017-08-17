
	<div class="container">
		<div class="row">
			<div class="col-md-9">
                <h2 class="brown">User Profile<?php echo !$exist ? ': ' . $user_profile['username'] : ''; ?></h2>
<?php
if ($exist) {
?>
                <div class="alert alert-danger" role="alert"><p>The user you are looking for does not exist.<p></div>
<?php
}
else {
    $this->load->view('user/avatar.php', array('nubbin' => 1));
?>
                <div class="userInfo">
                    <ul>
<?php
    if (!empty($user_profile['firstname']) && !empty($user_profile['lastname'])) {
?>        
                        <li>Name: <span class="bold"><?php echo $user_profile['firstname'] . ' ' . $user_profile['lastname']; ?></span></li>
<?php
    }

    if (!empty($user_profile['city']) && !empty($user_profile['state'])) {
?>                        
                        <li>Location: <span class="bold"><?php echo $user_profile['city'] . ', ' . $user_profile['state']; ?></span></li>
<?php
}
?>
                        <li>Joined: <span class="bold"><?php echo $user_profile['joinDate']; ?></span></li>
                        <li>Last Login: <span class="bold"><?php echo $user_profile['last_activity']; ?></span></li>
                    </ul>
                </div>
<?php
    if (!empty($userProfile['notes'])) {
?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h3 class="brown">Notes</h3>
                        <p><?php echo nl2br($userProfile['notes']); ?></p>
                    </div>
                </div>
<?php
    }

    if (!empty($beer_ratings) || !empty($establishment_ratings)) {
        $width = !empty($beer_ratings) && !empty($establishment_ratings) ? '6' : '12';
?>        
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <h3 class="brown">Malted Measurements</h3>
                    
<?php            
        if (!empty($beer_ratings)) {
?>
                        <div class="col-md-<?php echo $width; ?> col-xs-12 table-responsive">
                            <table class="table table-condensed table-hover">
                                <tr class="green gray-background">
                                    <th colspan="2">American Craft Beers</th>
                                </tr>
                                <tr>
                                    <td>Beers:</td>
                                    <td><a href="<?php echo base_url(); ?>user/beer/<?php echo $user_profile['username']; ?>"><?php echo number_format($beer_ratings[0]['rated_beers']); ?></a></td>
                                </tr>
                                <tr class="gray">
                                    <td>Styles:</td>
                                    <td><a href="<?php echo base_url(); ?>user/style/<?php echo $user_profile['username']; ?>"><?php echo $beer_ratings[0]['rated_styles']; ?></a></td>
                                </tr>
                                <tr>
                                    <td>Average Rating:</td>
                                    <td><?php echo number_format(round($beer_ratings[0]['rated_beer_average'], 1), 1); ?></td>
                                </tr>
                                <tr class="gray">
                                    <td>Maximum Rating:</td>
                                    <td><?php echo number_format(round($beer_ratings[0]['rated_beer_max'], 1), 1); ?></td>
                                </tr>
                                <tr>
                                    <td>Minimum Rating:</td>
                                    <td><?php echo number_format(round($beer_ratings[0]['rated_beer_min'], 1), 1); ?></td>
                                </tr>
                            </table>
                        </div>
<?php
        }

        if (!empty($establishment_ratings)) {
            $rated_establishments = 0;
            $rated_establishment_max = 0;
            $rated_establishment_min = 0;
            $total = 0;
            $str_category = '';
            foreach ($establishment_ratings as $ratings) {
                $rated_establishments += $ratings['rated_establishments'];
                $total += ($ratings['rated_establishments'] * $ratings['rated_establishment_average']);

                if ($ratings['rated_establishment_max'] > $rated_establishment_max) {
                    $rated_establishment_max = $ratings['rated_establishment_max'];
                }

                if ($ratings['rated_establishment_min'] < $rated_establishment_min) {
                    $rated_establishment_min = $ratings['rated_establishment_min'];
                }

                $str_category .= '
            <tr>
                <td><span style="padding-left: 8px;">' . ucwords($ratings['name']) . ' (' . $ratings['rated_establishments'] . ')</span></td>
                <td>' . number_format(round($ratings['rated_establishment_average'], 1), 1) . '</td>
            </tr>
                ';
            }
?>
                        <div class="col-md-<?php echo $width; ?> col-xs-12 table-responsive">
                            <table class="table table-condensed table-hover">
                                <tr class="green gray-background">
                                    <th colspan="2">American Establishments</th>
                                </tr>
                                <tr>
                                    <td>Total Establishments<br><span class="small-text">(may span multiple categories)</span></td>
                                    <td><a href="<?php echo base_url(); ?>user/establishment/<?php echo $user_profile['id']; ?>"><?php echo number_format($total_establishment_ratings); ?></a></td>
                                </tr>
                                <tr>
                                    <td>Average Rating</td>
                                    <td><?php echo number_format(round(($total / $rated_establishments), 1), 1); ?></td>
                                </tr>
                                <?php echo $str_category; ?>
                            </table>
                        </div>
<?php
        }
?>
                    </div>
                </div>
<?php
    }
    else {
?>
                <div class="alert alert-warning" role="alert">Pathetically, <strong><?php echo $user_profile['username']; ?></strong> has not rated any beers or establishments.</div>
<?php
    }
}
?>
                    
			</div>

    		<div class="col-md-3 cols-xs-12">
    			<div class="side-info">
<?php
$this->load->view('user/profile/right.php');

if ($exist) {
?>
                    <div class="panel panel-default">
                        <div class="green bold panel-heading">Problem Finding User Account</div>
                        <ul class="list-group">
            				<li class="list-group-item">If the account is yours and it isn&#39;t working please contact the <strong><em>
                            <script type="text/javascript">document.write('webmaster' + ' at ' + 'twobeerdudes' + ' dot ' + 'com');</script>
                            </em></strong> with your account information.</li>
                            <li class="list-group-item">Otherwise the account might not exist, might have been inactivated, banned, etc.</li>
            			</ul>
                    </div>
<?php
}
?>
    			</div>
    		</div>
        </div>
	</div>
