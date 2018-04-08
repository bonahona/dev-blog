<?php
require_once ('BaseController.php');
class AdminController extends BaseController
{
    public function BeforeAction()
    {
        if(!$this->IsLoggedIn()){
            return $this->Redirect('/admin', ['ref' => $this->RequestUri]);
        }

        parent::BeforeAction();
    }
}