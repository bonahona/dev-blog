<div class="panel panel-default">
    <div class="panel-heading">
        <h5 class="panel-title">Display by project</h5>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-6">
                <ul class="list-unstyled mb-0">
                    <?php foreach($DisplayProjects['left'] as $project):?>
                        <li>
                            <a href="<?php echo "/search?project=" . $project->Name;?>"><?php echo $project->Name;?></a>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>
            <div class="col-lg-6">
                <ul class="list-unstyled mb-0">
                    <?php foreach($DisplayProjects['right'] as $project):?>
                        <li>
                            <a href="<?php echo "/search?project=" . $project->Name;?>"><?php echo $project->Name;?></a>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
    </div>
</div>