<form id="metadata" name="metadata">
    <input type="hidden" name="Id" value="<?php echo $Post->Id;?>"/>
    <div class="form-group">
        <label>Title</label>
        <input type="text" name="Title" value="<?php echo $Post->Title;?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Navigation Title</label>
        <input type="text" name="NavigationTitle" value="<?php echo $Post->NavigationTitle;?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Masthead Image Url</label>
        <input type="text" name="MastHeadImageUrl" value="<?php echo $Post->MastHeadImageUrl;?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Home page text</label>
        <textarea name="HomePageText" rows="30" class="summernote">
            <?php echo $Post->HomePageText;?>
        </textarea>
    </div>
    <div class="form-group">
        <label>Status</label>
        <select name="PostStatusId" class="form-control">
            <?php foreach($PostStatuses as $postStatus):?>
                <option value="<?php echo $postStatus->Id;?>" <?php if($Post->PostStatusId == $postStatus->Id) echo ' selected="selected"';?>> <?php echo $postStatus->DisplayName;?></option>
            <?php endforeach;?>
        </select>
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
            <div class="input-group tags">

                <?php echo $this->Form->Select('TagId', $Tags, array('key' => 'Id', 'value' => 'DisplayName', 'attributes' => array('class' => 'form-control')));?>
                <span class="input-group-btn">
                    <button id="addTag" data-target="/post/addtag" class="btn btn-success btn-md" id="addTag">Add</button>
                </span>
            </div>
        </div>
    </div>
    <button class="btn btn-success btn-md submit" role="button">Save</button>
</form>
