<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Tags</h1>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th class="col-lg-2">Id</th>
                        <th class="col-lg-6">Name</th>
                        <th class="col-lg-2">Active</th>
                        <th class="col-lg-2">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($Tags as $tag):?>
                        <tr>
                            <td><?php echo $tag->Id;?></td>
                            <td><?php echo $tag->DisplayName;?></td>
                            <td><?php echo $tag->IsActive;?></td>
                            <td>
                                <a href="<?php echo "/tag/edit/" . $tag->Id;?>" class="btn btn-success">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <a href="/tag/create/" class="btn btn-success btn-md">Create</a>
            </div>
        </div>
    </div>
</div>