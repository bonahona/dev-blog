<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $this->Title;?></title>

    <?php foreach($this->CssFiles as $cssFile):?>
        <?php echo $this->Html->Css($cssFile);?>
    <?php endforeach;?>

</head>

<body>

    <?php echo $this->PartialView('HeaderNavBar');?>

    <div class="container">
        <div class="row">
            <div class="col-md-8">

                <?php echo $view;?>

            </div>
            <div class="col-md-4">
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