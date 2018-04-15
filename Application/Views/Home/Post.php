<h1 class="mt-4"><?php echo $Post->Title;?></h1>

<?php if($Post->MastHeadImageUrl != ""):?>
<div class="row my-2">
    <div class="col-lg-12">
        <img class="img-fluid rounded full" src="<?php echo $Post->MastHeadImageUrl;?>" alt="">
    </div>
</div>
<?php endif;?>

<h4 class="light-grey">Published <?php echo $Post->PublishDate;?> <br/>by Björn Fyrvall</h4>
<?php if($Post->EditDate != $Post->PublishDate):?>
    <h6 class="light-grey">Edited <?php echo $Post->EditDate;?></h6>
<?php endif;?>

<?php if(!$Post->IsPublished):?>
    <p><?php echo $Post->PostStatus->DisplayName;?></p>
<?php endif;?>

<?php foreach($Post->PostContents->Where(['IsDeleted' => 0])->OrderBy('SortOrder') as $postContent):?>
    <?php echo $postContent->Content;?>
<?php endforeach;?>

<hr/>

<div class="fb-comments" data-href="<?php echo $this->RequestUrl;?>" data-colorscheme="dark" data-width="847" data-numposts="10"></div>
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/sv_SE/sdk.js#xfbml=1&version=v2.12&appId=420800708371856&autoLogAppEvents=1';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
