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
        $data = json_decode($this->GetBody(), true);

        $id = $data['Id'];
        $post = $this->Models->Post->Find($id);

        if($post == null){
            return $this->HttpNotFound();
        }

        foreach($data as $key => $value){
            $post->$key = $value;
        }

        $post->EditDate = date('Y-m-d H:i:s');
        $post->Save();

        return $this->Json(['Status' => 'ok']);
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

        $post->EditDate = date('Y-m-d H:i:s');
        $post->Save();

        return $this->Json(['Status' => 'ok']);
    }

    public function AddContent()
    {
        $data = json_decode($this->GetBody(), true);
        $id = $data['Id'];

        $post = $this->Models->Post->Find($id);
        if($post == null){
            return $this->HttpNotFound();
        }

        $postContent = $this->Models->PostContent->Create([
            'PostId' => $id,
            'SortOrder' => 0,
            'AsActive' => 1,
            'content' => ''
        ]);

        $postContent->Save();

        return $this->Json(['Status' => 'ok']);
    }

    public function SaveContent()
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

        var_dump($data);
        var_dump($post->Object());

        $post->Save();

        return $this->Json(['Status' => 'ok']);
    }
}