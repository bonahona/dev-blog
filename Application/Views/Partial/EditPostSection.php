<?php if($IsTemplate):?>
    <script id="postContentTemplate" type="text/x-handlebars-template">
<?php endif;?>

<div class="row postContent">
    <div class="col-lg-12">
        <form class="postSection" data-id="<?php if($IsTemplate)echo "{{Id}}"; else echo $PostContent->Id;?>">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="editableContent">
                                <?php if($IsTemplate)echo "{{Content}}"; else echo $PostContent->Content;?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-primary btn-sm editContent"><span class="glyphicon glyphicon-pencil"></span></button>
                    <button class="btn btn-primary btn-sm stopContent"><span class="glyphicon glyphicon-align-left"></span></button>
                    <button class="btn btn-primary btn-sm saveContent"><span class="glyphicon glyphicon-ok"></span></button>
                    &nbsp;
                    <button class="btn btn-primary btn-sm deleteContent"><span class="glyphicon glyphicon-trash"></span></button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php if($IsTemplate):?>
    </script>
<?php endif;?>