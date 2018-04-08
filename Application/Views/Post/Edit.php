<div class="card my-4">
    <div class="card-body">
        <h1>Edit post</h1>

        <div class="row">
            <div class="col-lg-12">
                <div class="bs-component">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active show" data-toggle="tab" href="#content">Content</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active show" data-toggle="tab" href="#metadata">Meta Data</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active show" data-toggle="tab" href="#facebook">Facebook</a>
                        </li>
                    </ul>
                    <div id="postTabContent" class="tab-content">
                        <div id="content" class="tab-pane fade active show">
                            Content
                        </div>
                        <div id="metadata" class="tab-pane fade show">
                            <?php echo $this->Form->Start('PostMetaData');?>
                            <?php echo $this->Form->Hidden('Id');?>
                            <div class="form-group">
                                <label>Navigation Title</label>
                                <?php echo $this->Form->Input('NavigationTitle', array('attributes' => array('class' => 'form-control')));?>
                            </div>
                            <div class="form-group">
                                <label>Masthead Image Url</label>
                                <?php echo $this->Form->Input('MastHeadImageUrl', array('attributes' => array('class' => 'form-control')));?>
                            </div>
                            <div class="form-group">
                                <label>Home page text</label>
                                <?php echo $this->Form->Area('HomePageText', array('attributes' => array('class' => 'form-control summernote', 'rows' => '30')));?>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <?php echo $this->Form->Select('PostStatusId', $PostStatuses, array('key' => 'Id', 'value' => 'DisplayName', 'attributes' => array('class' => 'form-control')));?>
                            </div>

                            <h5>Tags</h5>
                            <div class="row ms-0 my-2">
                                <div class="btn btn-primary btn-md ms-2">Programming <span class="fa fa-times-circle-o"></span></div>
                                <div class="btn btn-primary btn-md ms-2">Design <span class="fa fa-times-circle-o"></span></div>
                                <div class="btn btn-primary btn-md ms-2">Editor <span class="fa fa-times-circle-o"></span></div>
                                <div class="btn btn-primary btn-md ms-2">Textures <span class="fa fa-times-circle-o"></span></div>
                            </div>

                            <div class="form-group row ms-0">
                                <?php echo $this->Form->Select('TagId', $Tags, array('key' => 'Id', 'value' => 'DisplayName', 'attributes' => array('class' => 'form-control col-lg-4')));?>
                                <a data-target="/post/addtag" class="btn btn-success col-lg-2" id="addTag">Add</a>
                            </div>
                            <?php echo $this->Form->Submit('Save', array('attributes' => array('class' => 'btn btn-md btn-success')));?>
                            <?php echo $this->Form->End();?>
                        </div>
                        <div id="facebook" class="tab-pane fade show">
                            <?php echo $this->Form->Start('PostFacebook');?>
                            <?php echo $this->Form->Hidden('Id');?>
                            <div class="form-group">
                                <label>Opengraph Title</label>
                                <?php echo $this->Form->Input('OgTitle', array('attributes' => array('class' => 'form-control')));?>
                            </div>
                            <div class="form-group">
                                <label>Opengraph Description</label>
                                <?php echo $this->Form->Input('OgDescription', array('attributes' => array('class' => 'form-control')));?>
                            </div>
                            <?php echo $this->Form->Submit('Save', array('attributes' => array('class' => 'btn btn-md btn-success')));?>
                            <?php echo $this->Form->End();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>