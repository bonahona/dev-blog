<form id="metadata" name="metadata">
    <input type="hidden" name="Id" value="<?php echo $Post->Id;?>"/>
    <button class="btn btn-success btn-md submit my-2" role="button">Save</button>
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

    <div class="row my-2">
        <div class="col-lg-6">
            <div id="tags" class="input-group">

                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Tags
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
                        <?php foreach($Tags as $tag):?>
                            <li class="ms-2 dark-grey-text">
                                <input id="<?php echo $tag->IdName;?>" type="checkbox" name="<?php echo $tag->DisplayName?>" value="<?php echo $tag->Id;?>" <?php if($tag->IsUsed) echo 'checked="true"';?>/>
                                <label for="<?php echo $tag->IdName;?>"><?php echo $tag->DisplayName;?></label>
                            </li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
        </div>
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

    <button class="btn btn-success btn-md submit" role="button">Save</button>
</form>
