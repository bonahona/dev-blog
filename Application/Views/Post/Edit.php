<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row ms-4">
            <h1 class="panel-title pull-left">Edit post</h1>
            <div class="pull-right">
                <a target="_blank" href="<?php echo "/post/preview/" . $Post->Id;?>" class="btn btn-md btn-success">Preview</a>
                <a target="_blank" href="<?php echo "/post/share/" . $Post->Id;?>" class="btn btn-md btn-success">Share</a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="bs-component">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item active">
                            <a class="nav-link active" id="content-tab" data-toggle="tab" href="#content" role="tab" aria-controls="content" aria-selected="true">Content</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="metadata-tab" data-toggle="tab" href="#metadata" role="tab" aria-controls="metadata" aria-selected="false">Meta data</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="opengraph-tab" data-toggle="tab" href="#opengraph" role="tab" aria-controls="opengraph" aria-selected="false">OpenGraph</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="post-tab-content">
                        <div class="tab-pane fade active in" id="content" role="tabpanel" aria-labelledby="content-tab">
                            <?php echo $this->PartialView('EditContent', ['Post' => $Post]);?>
                        </div>
                        <div class="tab-pane fade" id="metadata" role="tabpanel" aria-labelledby="metadata-tab">
                            <?php echo $this->PartialView('EditMetaData', ['Post' => $Post, 'PostStatuses' => $PostStatuses, 'ProjectIds' => $ProjectIds, 'UnusedTags' => $UnusedTags, 'Tags' => $Tags]);?>
                        </div>
                        <div class="tab-pane fade" id="opengraph" role="tabpanel" aria-labelledby="opengraph-tab">
                            <?php echo $this->PartialView('EditFacebook', ['Post' => $Post]);?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>