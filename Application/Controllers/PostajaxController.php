<?php
class PostajaxController extends Controller
{
    public function BeforeAction()
    {
        if(!$this->IsLoggedIn()){
            return $this->HttpNotAuthorized();
        }
    }

    public function UpdateMetaData()
    {
        print_r($this->GetBody());
    }

    public function UpdateFacebook()
    {
        $data = json_decode($this->GetBody(), true);

        $id = $data['Id'];
        $post = $this->Models->Post->Find($id);

        if($post == null){
            return $this->HttpNotFound();
        }

        foreach($data as $key => $value){
            $post->$key = $value;
        }

        $post->Save();

        return $this->Json(['Status' => 'ok']);
    }
}