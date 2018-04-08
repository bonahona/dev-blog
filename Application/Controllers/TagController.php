<?php
require_once('AdminController.php');
class TagController extends AdminController
{
    public function Index()
    {
        return $this->View();
    }
}