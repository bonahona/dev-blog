<div class="row">
    <div class="col-lg-12 my-3">
        <button class="btn btn-primary btn-md addContent" data-id="<?php echo $Post->Id;?>">
            <span class="glyphicon glyphicon-plus"></span> Add section
        </button>
    </div>
</div>

<div class="row">
    <div id="content-wrapper" class="col-lg-12">
        <?php foreach($Post->PostContents as $postContent):?>
            <?php echo $this->PartialView('EditPostSection', ['IsTemplate' => false, 'PostContent' => $postContent]);?>
        <?php endforeach;?>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 my-3">
        <button class="btn btn-primary btn-md addContent" data-id="<?php echo $Post->Id;?>">
            <span class="glyphicon glyphicon-plus"></span> Add section
        </button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 my-3">
        <a href="#" class="btn btn-sucsess btn-md">Save all</a>
    </div>
</div>

<?php echo $this->PartialView('EditPostSection', ['IsTemplate' => true]);?>