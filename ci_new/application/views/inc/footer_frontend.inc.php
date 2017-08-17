
	<div class="container" id="footer">
		<div class="row" id="footer-container">
            <div class="col-xs-12 col-md-9">
                <div class="row">
        			<div class="footer-links col-xs-12 col-md-4">
        				<h4>Beer</h4>
        				<ul>
        					<li><a href="<?php echo base_url(); ?>beer/reviews">Beer Reviews</a></li>
        					<li><a href="<?php echo base_url(); ?>beer/style">Beer Styles</a></li>
        					<li><a href="<?php echo base_url(); ?>beer/srm">Beer Colors</a></li>
        					<li><a href="<?php echo base_url(); ?>beer/ratingSystem">Beer Rating System</a></li>
        					<li><a href="#">Beer U</a></li>
        					<li><a href="<?php echo base_url(); ?>beer/ratings">Highest Rated Beers</a></li>
        				</ul>
        			</div>
        			
        			<div class="footer-links col-xs-12 col-md-4">
        				<h4>Beer Places</h4>
        				<ul>
        					<li><a href="<?php echo base_url(); ?>brewery/info">Establishments</a></li>
        					<li><a href="#">Establishment Rating System</a></li>
        					<li><a href="<?php echo base_url(); ?>brewery/hop">Brewery Hops</a></li>					
        					<li><a href="<?php echo base_url(); ?>brewery/addEstablishment">Add A Place</a></li>
        				</ul>
        			</div>
        			
        			<div class="footer-links col-xs-12 col-md-4">
        				<h4>Other</h4>
        				<ul>
        					<li><a href="http://blog.twobeerdudes.com">Sips Blog</a></li>
        					<li><a href="<?php echo base_url(); ?>page/aboutUs">About Us</a></li>
        					<li><a href="<?php echo base_url(); ?>page/contactUs">Contact Us</a></li>
        					<li><a href="<?php echo base_url(); ?>">Home</a></li>					
        				</ul>
        			</div>
                </div>
           
<?php
if (isset($quote)) {	
?>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <p class="quote"><span>&quot;<?php echo $quote['quote']; ?>&quot;</span> - <?php echo $quote['person']; ?></p>
                    </div>
                </div>
<?php
}
// get the date range for the copyright
$copyright = START_YEAR;
if (date('Y') > START_YEAR) {
	$copyright .= ' - ' . date('Y');
}
?>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <p class="footer_small">&copy; <?php echo $copyright; ?> twobeerdudes.com All rights reserved</p>
                    </div>
                </div>			
            </div>	
		</div>
	</div>

<?php
$controller = $this->uri->segment(1) == false ? 'page' : $this->uri->segment(1);
$method = $this->uri->segment(2) == false ? 'index' : $this->uri->segment(2);
if ($controller == 'page' && $method == 'index') {
?>

<?php
}
?>
        <script src="/<?php echo JS_PATH; ?>/jquery-ui.min-1.21.1.custom.js"></script>
        <script src="/<?php echo JS_PATH; ?>/bootstrap.min.js"></script>
        <!--[if IE]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
<?php
if (isset($js) && is_array($js) && count($js)) {
    foreach ($js as $javascript) {
        if (is_array($javascript) && isset($javascript['full_uri'])) {
            foreach ($javascript['full_uri'] as $full_uri) {
?>
        <script src="<?php echo $full_uri; ?>"></script>
<?php
            }
        }
        else {
?>
        <script src="/<?php echo JS_PATH; ?>/<?php echo $javascript; ?>"></script>
<?php
        }
    }
}
?>        
        <!-- Google Analytics -->
        <script>
        window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
        ga('create', 'UA-737507-2', 'auto');
        ga('send', 'pageview');
        </script>
        <script async src='https://www.google-analytics.com/analytics.js'></script>
        <!-- End Google Analytics -->    
    </body>
</html>