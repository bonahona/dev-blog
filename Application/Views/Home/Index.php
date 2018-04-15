<div class="row">
    <div class="col-lg-12">
        <h1>Bona's Dev blog.</h1>
    </div>
</div>
<?php foreach($Posts as $post):?>
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
            <p class="light-grey">Posted on <?php echo date('Y-m-d', strtotime($post->PublishDate));?> by Björn Fyrvall</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php foreach($post->PostTags as $postTag):?>
                <span class="label label-info"><?php echo $postTag->Tag->DisplayName;?></span>
            <?php endforeach;?>
        </div>
    </div>
<?php endforeach;?>

<div class="row">
    <div class="col-lg-12">
        <a href="/history" class="btn btn-success btn-md">
            View all posts
        </a>
    </div>
</div>

<!--
<div class="card my-4">
    <img class="card-img-top" src="http://placehold.it/750x300" alt="Card image cap">
    <div class="card-body">
        <a href="/home/post">
            <h2 class="card-title">Post Title</h2>
        </a>
        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis aliquid atque, nulla? Quos cum ex quis soluta, a laboriosam. Dicta expedita corporis animi vero voluptate voluptatibus possimus, veniam magni quis!</p>
        <a href="#" class="btn btn-success">Read More &rarr;</a>
    </div>
    <div class="card-footer text-muted">
        Posted on January 1, 2017 by
        <a href="#">Start Bootstrap</a>
    </div>
</div>

<ul class="pagination justify-content-center mb-4">
    <li class="page-item">
        <a class="page-link" href="#">&larr; Older</a>
    </li>
    <li class="page-item disabled">
        <a class="page-link" href="#">Newer &rarr;</a>
    </li>
</ul>
-->