<div class="panel panel-default">
    <div class="panel-heading">
        <h5 class="panel-title">Latest posts</h5>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <?php foreach($LatestPosts as $post):?>
                    <div class="row">
                        <div class="col-lg-7">
                            <a href="<?php echo "/" . $post->NavigationTitle;?>"><?php echo $post->Title;?></a>
                        </div>
                        <div class="col-lg-5">
                            <span class="light-grey ms-2"> <?php echo date('Y-m-d', strtotime($post->PublishDate));?></span>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <a href="/history">All posts</a>
            </div>
        </div>
    </div>
</div>