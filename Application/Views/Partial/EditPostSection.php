<div class="row">
    <div class="col-lg-12">
        <form class="postSection" data-id="<?php echo $PostContent->Id;?>">
            <div class="row">
                <div class="col-lg-12">
                    <div class="editableContent">
                        <?php echo $PostContent->Content;?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <button class="btn btn-primary btn-md editContent"><span class="glyphicon glyphicon-pencil"></span></button>
                    <button class="btn btn-primary btn-md stopContent"><span class="glyphicon glyphicon-align-left"></span></button>
                    <button class="btn btn-primary btn-md saveContent"><span class="glyphicon glyphicon-ok"></span></button>
                </div>
            </div>
        </form>
    </div>
</div>