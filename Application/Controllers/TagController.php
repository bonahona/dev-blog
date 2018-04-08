<?php
require_once('AdminController.php');
class TagController extends AdminController
{
    public function Index()
    {
        $this->Title = 'Tags';

        $tags = $this->Models->Tag->All()->OrderBy('DisplayName');
        $this->Set('Tags', $tags);

        return $this->View();
    }

    public function Create()
    {
        $this->Title = 'Create tag';

        if($this->IsPost()){
            $tag = $this->Data->Parse('Tag', $this->Models->Tag);
            $tag->Save();

            return $this->Redirect('/tag');
        }else {
            $tag = $this->Models->Tag->Create(['IsActive' => 1]);
            $this->Set('Tag', $tag);

            return $this->View();
        }
    }

    public function Edit($id = null)
    {
        if($id == null){
            return $this->HttpNotFound();
        }

        $tag = $this->Models->Tag->Find($id);
        if($tag == null){
            return $this->HttpNotFound();
        }

        $this->Title = 'Edit tag';

        if($this->IsPost()){
            $tag = $this->Data->DbParse('Tag', $this->Models->Tag);
            $tag->Save();

            return $this->Redirect('/tag');
        }else{
            $this->Set('Tag', $tag);

            return $this->View();
        }
    }
}