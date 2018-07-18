<div class="row">
    <div class="col-lg-12">
        <h1>Bona's Dev blog.</h1>
    </div>
</div>
<?php foreach($Posts as $post):?>
    <div class="row ms-2">
        <div class="col-lg-12 blog-entry">
            <div class="row">
                <div class="col-lg-12">
                    <a href="<?php echo "/" . $post->NavigationTitle;?>">
                        <h2><?php echo $post->Title;?></h2>
                    </a>
                </div>
            </div>
            <?php if($post->MastHeadImageUrl != ""):?>
                <a href="<?php echo "/" . $post->NavigationTitle;?>">
                    <div class="row my-2">
                        <div class="col-lg-12">
                            <img class="img-fluid rounded full" src="<?php echo $post->MastHeadImageUrl;?>" alt="">
                        </div>
                    </div>
                </a>
            <?php endif;?>
            <div class="row">
                <div class="col-lg-12">
                    <p><?php echo $post->HomePageText;?></p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <p class="light-grey">Posted on <?php echo date('Y-m-d', strtotime($post->PublishDate));?> by <?php echo $post->GetAuthor()->Name;?></p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <?php foreach($post->PostTags as $postTag):?>
                        <span class="label label-info"><?php echo $postTag->Tag->DisplayName;?></span>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach;?>

<div class="row my-4">
    <div class="col-lg-12">
        <a href="/history" class="btn btn-success btn-md">
            View all posts
        </a>
    </div>
</div>