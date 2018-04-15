<div class="panel panel-default">
    <div class="panel-heading">
        <h5 class="panel-title">Latest posts</h5>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-unstyled mb-0">
                    <?php foreach($LatestPosts as $post):?>
                        <li>
                            <a href="<?php echo "/" . $post->NavigationTitle;?>"><?php echo $post->Title;?></a> <span class="light-grey ms-2"> <?php echo date('Y-m-d', strtotime($post->PublishDate));?></span>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <a href="/history">All posts</a>
            </div>
        </div>
    </div>
</div>