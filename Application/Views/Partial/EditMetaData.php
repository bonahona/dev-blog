<form name="metaData">
    <input type="hidden" name="data[PostMetaData][Id]" value="<?php echo $Post->Id;?>"/>
    <div class="form-group">
        <label>Navigation Title</label>
        <input type="text" name="data[PostMetaData][NavigationTitle]" value="<?php echo $Post->NavigationTitle;?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Masthead Image Url</label>
        <input type="text" name="data[PostMetaData][MastHeadImageUrl]" value="<?php echo $Post->MastHeadImageUrl;?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Home page text</label>
        <textarea name="data[PostMetaData][HomePageText]" rows="30" class="summernote">
            <?php echo $Post->HomePageText;?>
        </textarea>
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
</form>
