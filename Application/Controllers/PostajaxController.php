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

        $publishStatus = $this->Models->PostStatus->Where(['DisplayName' => 'Published'])->First();
        if($publishStatus == null){
            return $this->HttpStatus(500, 'Failed to lookup publish status');
        }

        if($post->PostStatusId == $publishStatus->Id && $post->PublishDate == ""){
            $post->PublishDate = date('Y-m-d H:i:s');
        }

        $tagValue = array_values($data['tags']);
        foreach($post->PostTags as $tag){
            if(!in_array($tag->TagId, $tagValue)){
                $tag->IsDeleted = 1;
                $tag->Save();
            }
        }

        foreach($data['tags'] as $key => $value){
            $tag = $post->PostTags->Where(['TagId' => $value])->First();
            if($tag == null){
                $tag = $this->Models->PostTag->Create(['TagId' => $value, 'PostId' => $post->Id, 'IsDeleted' => 0]);
            }

            $tag->IsDeleted = 0;
            $tag->Save();
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
            'AsDeleted' => 1,
            'content' => ''
        ]);

        $postContent->Save();

        return $this->Json($postContent->Object());
    }

    public function SaveContent()
    {
        $data = json_decode($this->GetBody(), true);
        $id = $data['Id'];

        $postContent = $this->Models->PostContent->Find($id);
        if($postContent == null){
            return $this->HttpNotFound();
        }

        foreach($data as $key => $value){
            $postContent->$key = $value;
        }

        $postContent->Save();

        return $this->Json(['Status' => 'ok']);
    }

    public function DeleteContent()
    {
        $data = json_decode($this->GetBody(), true);
        $id = $data['Id'];

        $postContent = $this->Models->PostContent->Find($id);
        if($postContent == null){
            return $this->HttpNotFound();
        }

        $postContent->IsDeleted = 1;
        $postContent->Save();

        return $this->Json(['Status' => 'ok']);
    }
}