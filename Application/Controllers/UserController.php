<?php
class UserController extends Controller
{
    public function BeforeAction()
    {
        parent::BeforeAction();

        if(!$this->IsLoggedIn() && !$this->Action == "Login"){
            $this->Redirect('/User/Login', array('ref' => $this->RequestUri));
        }

        $this->ClearCss();

        $this->EnqueueCssFiles([
            'bootstrap.min.css',
            'dashboard.css',
            'font-awesome.css'
        ]);
    }

    public function Login($ref = null)
    {
        $this->Title = 'Login';
        $this->Layout = 'Login';

        if($this->IsPost()) {
            $user = $this->Data->RawParse('User');

            $response = $this->Helpers->ShellAuth->Login($user['Username'], $user['Password']);

            if(isset($response['errors'])){
                foreach($response['errors'] as $error){
                    $this->ModelValidation->AddError('User', 'Password', $error);
                }
            }

            $ref = $this->Get['ref'];
            if($this->ModelValidation->Valid()) {

                $shellUserId = $response['data']['Login']['ShellUserPrivilege']['ShellUser']['Id'];

                if($ref == null || $ref == ""){
                    return $this->Redirect('/');
                }else{
                    return $this->Redirect($ref);
                }
            }

            $this->Set('User', $user);
            return $this->View();
        }else{
            return $this->View();
        }
    }

    public function Logout()
    {
        $this->Helpers->ShellAuth->Logout();
        return $this->Redirect('/');
    }
}