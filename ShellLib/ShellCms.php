<?php
if(file_exists('./vendor/autoload.php')){
    require_once('./vendor/autoload.php');
}

//Acts as a proxy to include the Shell CMS core
require_once('./ShellLib/Core/Core.php');