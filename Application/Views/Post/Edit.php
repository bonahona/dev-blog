<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Edit post</h1>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="bs-component">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="content-tab" data-toggle="tab" href="#content" role="tab" aria-controls="content" aria-selected="true">Content</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="metadata-tab" data-toggle="tab" href="#metadata" role="tab" aria-controls="metadata" aria-selected="false">Meta data</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="facebook-tab" data-toggle="tab" href="#facebook" role="tab" aria-controls="facebook" aria-selected="false">Facebook</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="post-tab-content">
                        <div class="tab-pane fade show active" id="content" role="tabpanel" aria-labelledby="content-tab">
                            Content
                        </div>
                        <div class="tab-pane fade" id="metadata" role="tabpanel" aria-labelledby="metadata-tab">
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

                            <div class="row">
                                <div class="col-lg-12">
                                    <h5>Tags</h5>
                                    <div class="row ms-0 my-2">
                                        <div class="btn btn-primary btn-md">Programming <span class="fa fa-times-circle-o"></span></div>
                                        <div class="btn btn-primary btn-md">Design <span class="fa fa-times-circle-o"></span></div>
                                        <div class="btn btn-primary btn-md">Editor <span class="fa fa-times-circle-o"></span></div>
                                        <div class="btn btn-primary btn-md">Textures <span class="fa fa-times-circle-o"></span></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <?php echo $this->Form->Select('TagId', $Tags, array('key' => 'Id', 'value' => 'DisplayName', 'attributes' => array('class' => 'form-control')));?>
                                        <span class="input-group-btn">
                                            <a data-target="/post/addtag" class="btn btn-success" id="addTag">Add</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php echo $this->Form->Submit('Save', array('attributes' => array('class' => 'btn btn-md btn-success')));?>
                            <?php echo $this->Form->End();?>
                        </div>
                        <div class="tab-pane fade" id="facebook" role="tabpanel" aria-labelledby="facebook-tab">
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