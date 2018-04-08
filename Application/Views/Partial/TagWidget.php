<div class="card my-4 card-8">
    <h5 class="card-header">Tags</h5>
    <div class="card-body">
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