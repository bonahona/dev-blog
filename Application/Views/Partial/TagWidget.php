<div class="panel panel-default">
    <div class="panel-heading">
        <h5 class="panel-title">Display by tags</h5>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-6">
                <ul class="list-unstyled mb-0">
                    <?php foreach($DisplayTags['left'] as $tag):?>
                        <li>
                            <a href="<?php echo "/search?tag=" . $tag->DisplayName;?>"><?php echo $tag->DisplayName;?></a>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>
            <div class="col-lg-6">
                <ul class="list-unstyled mb-0">
                    <?php foreach($DisplayTags['right'] as $tag):?>
                        <li>
                            <a href="<?php echo "/search?tag=" . $tag->DisplayName;?>"><?php echo $tag->DisplayName;?></a>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
    </div>
</div>