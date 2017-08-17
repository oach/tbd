
    <div class="container">
        <div class="row">
            <!--<div class="col-xs-12 col-md-12">-->
            <div class="col-xs-12 col-md-8">
                <nav class="navbar navbar-default" role="navigation">

                    

                    <div class="collapse navbar-collapse" id="bs-navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li><a href="<?php echo base_url(); ?>"><span class="sr-only">Home </span><span class="glyphicon glyphicon-home"></span></a></li>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" area-haspopup="true" area-expanded="false" href="<?php echo base_url(); ?>beer/review">
                                    Beer <span class="glyphicon glyphicon-menu-down"></span><!--<span class="caret"></span>-->
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo base_url(); ?>beer/reviews">Beer Reviews</a></li>
                                    <li><a href="<?php echo base_url(); ?>beer/style">Beer Styles</a></li>
                                    <li><a href="<?php echo base_url(); ?>beer/srm">Beer Colors</a></li>
                                    <li><a href="<?php echo base_url(); ?>beer/ratingSystem">Beer Rating System</a></li>
                                    <li><a href="<?php echo base_url(); ?>beer/ratings">Highest Rated Beers</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" area-haspopup="true" area-expanded="false" href="<?php echo base_url(); ?>brewery/info">
                                    Beer Places <span class="glyphicon glyphicon-menu-down"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo base_url(); ?>brewery/info">Establishments</a></li>
                                    <li><a href="<?php echo base_url(); ?>brewery/hop">Brewery Hops</a></li>
                                    <li><a href="<?php echo base_url(); ?>brewery/addEstablishment">Add a Place</a></li>
                                </ul>
                            </li>
                            <li><a href="http://blog.twobeerdudes.com">Sips</a></li>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" area-haspopup="true" area-expanded="false" href="#">
                                    Who da Dudes <span class="glyphicon glyphicon-menu-down"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo base_url(); ?>page/aboutUs">About Us</a></li>
                                    <li><a href="<?php echo base_url(); ?>page/contactUs">Contact Us</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <div class="navbar-header">
                        <button type="button btn-xs btn-small" class="navbar-toggle collapsed pull-left" data-toggle="collapse" data-target="#bs-navbar-collapse" aria-expanded="false">
                            <span class="sr-only">Menu</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                </nav>
            </div>
        <!--</div>
    </div>-->
