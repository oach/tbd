
        <button id="search-toggle" type="button" class="btn btn-sm btn-primary" data-toggle="dropdown" aria-expanded="false"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>

        <div id="search-menu" class="select-menu dropdown-menu-right" role="menu">
            <form id="edit_beer_form" method="post" action="<?php echo base_url(); ?>page/search/" role="search">
                <div class="form-group">
<?php $this->load->view('forms/search_type_select', $data); ?>
                </div>

                <div class="form-group">
                    <label class="sr-only" for="search">Search</label>
                    <input type="text" class="form-control input-sm" id="search-menu-text" name="search-menu-text" placeholder="Search">
                </div>

                <button type="submit" class="btn btn-sm btn-primary">Search <span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>

                <button type="button" id="search-toggle-close" class="btn btn-sm btn-default"><span class="sr-only">Close Search</span><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
            </form>
        </div>
