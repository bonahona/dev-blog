<div class="row">
    <div class="col-lg-12 my-3">
        <a href="#" class="btn btn-primary btn-md"><span class="glyphicon glyphicon-plus"></span> Add section</a>
    </div>
</div>

<?php foreach($Post->PostContent as $postContent):?>
    <?php echo $this->PartialView('EditPostSection', ['PostContent' => $postContent]);?>
<?php endforeach;?>

<div class="row">
    <div class="col-lg-12 my-3">
        <a href="#" class="btn btn-primary btn-md"><span class="glyphicon glyphicon-plus"></span> Add section</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 my-3">
        <a href="#" class="btn btn-sucess btn-md">Save all</a>
    </div>
</div>
