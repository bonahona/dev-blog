<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Posts</h1>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-responsive table-striped">
                    <thead>
                        <tr>
                            <th class="col-lg-1">Id</th>
                            <th class="col-lg-4">Title</th>
                            <th class="col-lg-3">Create Date</th>
                            <th class="col-lg-2">Status</th>
                            <th class="col-lg-2">Preview</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($Posts as $post):?>
                            <tr>
                                <td>
                                    <a href="<?php echo "/post/edit/" . $post->Id;?>">
                                        <?php echo $post->Id;?>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo "/post/edit/" . $post->Id;?>">
                                        <?php echo $post->Title;?>
                                    </a>
                                </td>
                                <td><?php echo $post->CreateDate;?></td>
                                <td><?php echo $post->PostStatus->DisplayName;?></td>
                                <td>
                                    <a target="_blank" href="<?php echo "/post/preview/" . $post->Id;?>">Preview</a>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>