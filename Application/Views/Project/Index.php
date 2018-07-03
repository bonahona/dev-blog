<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Projects</h1>
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
                    <?php foreach($Projects as $project):?>
                        <tr>
                            <td><?php echo $project->Id;?></td>
                            <td><?php echo $project->Name;?></td>
                            <td><?php echo $project->IsActive;?></td>
                            <td>
                                <a href="<?php echo "/project/edit/" . $project->Id;?>" class="btn btn-success">
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
                <a href="/project/create/" class="btn btn-success btn-md">Create</a>
            </div>
        </div>
    </div>
</div>