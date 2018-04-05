<nav class="navbar navbar-inverse navbar-fixed-top dark-grey">
    <div class="container-fluid">
        <div class="navbar-header">
            <ul class="nav navbar-nav navbar-left">
                <li class="dropdown">
                    <a href="/" class="dropdown-toggle navbar-brand light-grey" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Documentation
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php foreach($ApplicationLinks['PublicApplications'] as $applicationLink):?>
                            <li class="navbar-brand">
                                <a href="<?php echo "http://" . $applicationLink['Url'];?>">
                                    <?php echo $applicationLink['MenuName'];?>
                                </a>
                            </li>
                        <?php endforeach;?>
                    </ul>
                </li>
            </ul>
            <span class="navbar-brand light-grey">|</span>
            <a class="navbar-brand light-grey" href="http://fyrvall.com">Fyrvall.com</a>
        </div>
        <form method="get" action="/Projects/Search" class="navbar-form navbar-right">
            <div class="input-group">
                <div class="input-group-addon">
                    <span class="fas fa-search">Go</span>
                </div>
                <?php if(isset($SearchQuery)):?>
                    <input type="text" name="keywords" class="form-control" placeholder="Search..."/ value="<?php echo $SearchQuery;?>">
                <?php else:?>
                    <input type="text" name="keywords" class="form-control" placeholder="Search..."/>
                <?php endif;?>
            </div>
        </form>
    </div>
</nav>