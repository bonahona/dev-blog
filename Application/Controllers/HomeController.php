<?php
require_once('BaseController.php');
class HomeController extends BaseController
{
    public function Index()
    {
        $this->Layout = "Home";

        $this->Title = "Test";

        return $this->View();
    }

    public function Post()
    {
        $this->Layout = "Post";

        $this->Title = "Test";

        return $this->View();
    }

    public function NotFound()
    {
        return $this->View();
    }
}