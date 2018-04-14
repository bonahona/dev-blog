<h1 class="mt-4"><?php echo $Post->Title;?></h1>

<hr/>
<p><?php echo $Post->PublishDate;?></p>
<?php if($Post->EditDate != $Post->PublishDate):?>
    <p>Edited <?php echo $Post->EditDate;?></p>
<?php endif;?>

<?php if(!$Post->IsPublished):?>
    <p><?php echo $Post->PostStatus->DisplayName;?></p>
<?php endif;?>
<hr/>

<?php if($Post->MastHEadImageUrl != ""):?>
<img class="img-fluid rounded" src="<?php echo $Post->MastHEadImageUrl;?>" alt="">
<hr/>
<?php endif;?>

<?php foreach($Post->PostContents->Where(['IsDeleted' => 0])->OrderBy('SortOrder') as $postContent):?>
    <?php echo $postContent->Content;?>
<?php endforeach;?>

<hr/>

<div class="fb-comments" data-href="<?php echo $this->RequestUrl;?>" data-colorscheme="light" data-width="730" data-numposts="10"></div>
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/sv_SE/sdk.js#xfbml=1&version=v2.12&appId=420800708371856&autoLogAppEvents=1';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
