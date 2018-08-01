<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Björn Fyrvall">
    <?php echo $this->Html->Favicon('fyrvall-favicon.png');?>

    <title><?php echo $this->Title;?></title>

    <?php foreach($this->CssFiles as $cssFile):?>
        <?php echo $this->Html->Css($cssFile);?>
    <?php endforeach;?>

    <meta property="og:title" content="<?php if(empty($OgTitle)){ echo $this->Title;}else{ echo $OgTitle;}?>" />
    <meta property="og:description" content="<?php if(empty($OgDescription)){ echo "A blog about game development and programming"; }else{ echo $OgDescription;}?>" />
    <meta property="og:url" content="<?php echo $this->FullUri;?>" />
    <meta property="og:site_name" content="Bona's Dev blog" />

    <?php if(isset($OgImageUrl) && !empty($OgImageUrl)):?>
        <meta property="og:image" content="<?php echo $OgImageUrl;?>" />
    <?php endif;?>

</head>

<body>

    <?php echo $this->PartialView('HeaderNavBar', ['SearchQuery' => $SearchQuery]);?>

    <div class="container">
        <div class="row">
            <div class="col-md-9">

                <?php echo $view;?>

            </div>
            <div class="col-md-3">
                <div class="row">
                    <?php echo $this->PartialView('LastestPostsWidget', ['LatestPosts' => $LatestPosts]);?>
                </div>
                <div class="row">
                    <?php echo $this->PartialView('ProjectWidget', ['DisplayProjects' => $DisplayProjects]);?>
                </div>
                <div class="row">
                    <?php echo $this->PartialView('TagWidget', ['DisplayTags' => $DisplayTags]);?>
                </div>
                <div class="row">
                    <?php if($this->IsLoggedIn()):?>
                        <?php echo $this->PartialView('AdminWidget');?>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>

    <?php foreach($this->JavascriptFiles as $javascriptFile):?>
        <?php echo $this->Html->Js($javascriptFile);?>
    <?php endforeach;?>

</body>

</html>