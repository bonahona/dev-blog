<meta property="og:title" content="<?php if(empty($OgData->OgTitle)){ echo $this->Title;}else{ echo $OgData->OgTitle;}?>" />
<meta property="og:description" content="<?php if(empty($OgData->OgDescription)){ echo "A blog about game development and programming. Done by a group of game developer hobbyists and software developers."; }else{ echo $OgData->OgDescription;}?>" />
<meta property="og:url" content="<?php echo $this->FullUri;?>" />
<meta property="og:site_name" content="Bona's Dev blog" />

<?php if(isset($OgData->OgType)):?>
    <meta property="og:type" content="<?php echo $OgData->OgType;?>" />

    <?php if($OgData->OgType == 'article'):?>
        <?php if(isset($OgData->OgAuthorId)):?>
            <meta property="article:author" content="<?php echo $OgData->OgAuthorId;?>" />
        <?php endif;?>
        <?php if(isset($OgData->OgAuthorFirstName)):?>
            <meta property="article:author:first_name" content="<?php echo $OgData->OgAuthorFirstName;?>" />
        <?php endif;?>
        <?php if(isset($OgData->OgAuthorLastName)):?>
            <meta property="article:author:last_name" content="<?php echo $OgData->OgAuthorLastName;?>" />
        <?php endif;?>
        <?php if(isset($OgData->OgArticlePublishDate)):?>
            <meta property="article:published_date" content="<?php echo $OgData->OgArticlePublishDate;?>" />
        <?php endif;?>
        <?php if(isset($OgData->OgArticleModifiedDate)):?>
            <meta property="article:modified_date" content="<?php echo $OgData->OgArticleModifiedDate;?>" />
        <?php endif;?>
    <?php endif;?>
<?php endif;?>


<?php if(isset($OgData->OgImageUrl) && !empty($OgData->OgImageUrl)):?>
    <meta property="og:image" content="<?php echo $OgData->OgImageUrl;?>" />
<?php endif;?>
