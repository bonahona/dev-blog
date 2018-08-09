<?php
require_once('AdminController.php');
class UserDetailsController extends AdminController
{

    public function Index()
    {
        $this->Title = 'Local users';

        $localUsers = $this->Models->LocalUserDetails->All();
        $this->Set('LocalUsers', $localUsers);

        return $this->View();
    }

    public function Refetch($id = null)
    {
        if($id == null){
            return $this->HttpNotFound();
        }

        $localUser = $this->Models->LocalUserDetails->Find($id);
        if($localUser == null){
            return $this->HttpNotFound();
        }

        return $this->Redirect('/users');
    }

    public function Edit($id = null)
    {
        if($id == null){
            return $this->HttpNotFound();
        }

        if($this->IsPost()){
            $localUser = $this->Data->DbParse('LocalUserDetails', $this->Models->LocalUserDetails);
            if($localUser == null){
                return $this->HttpNotFound();
            }

            $localUser->Save();
            return $this->Redirect('/users');
        }else {
            $localUser = $this->Models->LocalUserDetails->Find($id);
            if ($localUser == null) {
                return $this->HttpNotFound();
            }

            $this->Title = 'User ' . $localUser->Name;

            $this->Set('LocalUserDetails', $localUser);
            return $this->View();
        }
    }
}