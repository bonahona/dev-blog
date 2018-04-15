<nav class="navbar navbar-inverse navbar-fixed-top dark-grey">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand light-grey" href="/">Dev Blog</a>
            <span class="navbar-brand light-grey">|</span>
            <a class="navbar-brand light-grey" href="http://fyrvall.com">Fyrvall.com</a>
        </div>

        <form method="get" action="/search" class="navbar-form navbar-right">
            <?php if(isset($SearchQuery)):?>
                <input type="text" name="keywords" class="form-control" placeholder="Search..."/ value="<?php echo $SearchQuery;?>">
            <?php else:?>
                <input type="text" name="keywords" class="form-control" placeholder="Search..."/>
            <?php endif;?>
        </form>

    </div>
</nav>