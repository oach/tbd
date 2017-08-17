
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-9">
				<h2 class="brown">Beer Rating System</h2>
				
				<p>Our beer rating system and rating scale is based on overall feel for the beer and the necessity or lack there of, to have another one of the beers in hand. The scale should be similar to other rating scales that you have seen and used. This scale should be pliable to the point in which a you might insinuate your own descriptions for the values, after all, the site is about your personality and subjective views of the beer. So the 10 point scale was born.</p>
				<p>The 10 point scale is just from 1 to 10, with 1 being the lowest rating and 10 being the highest. As mentioned before: something you are familiar with from other areas of life.</p>
<?php
if (!empty($rating_system)) {
?>
				<table class="table">
					<thead>
						<tr>
							<th>Rating</th>
							<th>Description</th>
						</tr>
					</thead>
					<tbody>
<?php
	foreach ($rating_system as $key) {
?>
						<tr>
							<td class="ratingValue"><?php echo $key->ratingValue; ?></td>
							<td><?php echo $key->description; ?></td>
						</tr>
<?php					
	}
?>
					</tbody>
				</table>
<?php			
}
?>				
				<p>Remember when you write a beer review, more than likely, you are taking your first taste of a beer, possibly only, and that there is a lot that factors into the reason you felt the way you did when you wrote a review. Some factors: mood, feelings, like or dislike of beer style, food, other beers drank before, etc. These are the reasons that Two Beer Dudes will more than likely never put a review on the site below a 5. We will try and give the beer another chance on another day.</p>
				<p>One last thing: when tasting beer only have about 2 to 3 ounces and drink beers of the same beer style. Split a 12 ounce bottle between friends, formulate your own opinion and then share with each other. Each of you may have found something different within the confines of the glass container. Really try to enjoy the beer for the style that it is and appreciate the effort that the brewer put into making the beer. Trying to be unbiased will give the best overall chance and rating for a beer. Enjoy!</p>
			</div>
            
            <div class="col-xs-12 col-sm-12 col-md-3">
    			<div class="side-info">
    				<div class="panel panel-default">
				        <div class="green bold panel-heading">More Beer Information</div>
				        <ul class="list-group">
							<li class="list-group-item"><a href="<?php echo base_url(); ?>beer/style">Beer Styles</a></li>
							<li class="list-group-item"><a href="<?php echo base_url(); ?>beer/srm">Beer Colors</a></li>
						</ul>
					</div>
    			</div>
    		</div>
        </div>
	</div>
