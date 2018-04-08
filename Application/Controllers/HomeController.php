<?php
require_once('BaseController.php');
class HomeController extends BaseController
{
    public function Index()
    {
        $this->Title = "Test";

        return $this->View();
    }

    public function Post()
    {
        $this->Title = "Test";

        return $this->View();
    }

    public function NotFound()
    {
        return $this->View();
    }
}