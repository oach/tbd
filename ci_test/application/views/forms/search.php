
                <div class="col-xs-12 col-md-7">
                    <form id="edit_beer_form" class="form-inline pull-xs-left pull-sm-left pull-md-right pull-lg-right edit" method="post" action="<?php echo base_url(); ?>page/search/">
                        <div class="form-group">
                            <select class="input-sm form-control" id="searchType" name="searchType">
                                <option value="beer">Beer</option>
                                <option value="establishment">Establishment</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                
                        <div class="form-group">
                            <label class="sr-only" for="search">Search</label>
                            <input type="text" class="form-control input-sm" id="search" name="search" placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">Search</button>
                    </form>
                </div>