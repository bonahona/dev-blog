<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Local user details</h1>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-responsive table-striped">
                    <thead>
                    <tr>
                        <th class="col-lg-1">Id</th>
                        <th class="col-lg-4">Name</th>
                        <th class="col-lg-3">Fetch Date</th>
                        <th class="col-lg-3">&nbsp;</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($LocalUsers as $user):?>
                        <tr>
                            <td>
                                <a href="<?php echo "/users/edit/" . $user->Id;?>">
                                    <?php echo $user->Id;?>
                                </a>
                            </td>
                            <td>
                                <a href="<?php echo "/users/edit/" . $user->Id;?>">
                                    <?php echo $user->Name;?>
                                </a>
                            </td>
                            <td><?php echo $user->Fetched;?></td>
                            <td>
                                <a href="/users/" class="btn btn-md btn-default"><span class="glyphicon glyphicon-refresh"></span></a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>zm