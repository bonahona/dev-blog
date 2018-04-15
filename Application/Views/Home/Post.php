<h1 class="mt-4"><?php echo $Post->Title;?></h1>

<?php if($Post->MastHeadImageUrl != ""):?>
<div class="row my-2">
    <div class="col-lg-12">
        <img class="img-fluid rounded full" src="<?php echo $Post->MastHeadImageUrl;?>" alt="">
    </div>
</div>
<?php endif;?>

<h4 class="light-grey">
    <?php if($Post->PublishDate != ""):?>
        Published <?php echo date('Y-m-d', strtotime($Post->PublishDate));?> <br/>
    <?php else:?>
        Not yet published <br/>
    <?php endif;?>
    <span class="small">by Björn Fyrvall</span>
</h4>
<?php if($Post->EditDate != $Post->PublishDate):?>
    <h6 class="light-grey">Edited <?php echo date('Y-m-d', strtotime($Post->EditDate));?></h6>
<?php endif;?>

<?php if(!$Post->IsPublished):?>
    <p><?php echo $Post->PostStatus->DisplayName;?></p>
<?php endif;?>

<div class="row my-3">
    <div class="col-lg-12">
        <?php foreach($Post->PostTags as $postTag):?>
            <span class="label label-info"><?php echo $postTag->Tag->DisplayName;?></span>
        <?php endforeach;?>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <?php foreach($Post->PostContents->Where(['IsDeleted' => 0])->OrderBy('SortOrder') as $postContent):?>
            <?php echo $postContent->Content;?>
        <?php endforeach;?>

    </div>
</div>

<hr/>

<div class="fb-comments" data-href="<?php echo $this->RequestUrl;?>" data-colorscheme="dark" data-width="847" data-numposts="10"></div>
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12&appId=420800708371856&autoLogAppEvents=1';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
