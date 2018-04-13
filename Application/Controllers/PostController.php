<?php
require_once ('AdminController.php');
class PostController extends AdminController
{
    public function Index()
    {
        $this->Title = "View Posts";

        $posts = $this->Models->Post->All()->OrderBy('CreateDate');
        $this->Set('Posts', $posts);

        return $this->View();
    }

    public function Create()
    {
        $post = $this->Models->Post->Create([
            'PostStatusId' => 1,
            'PublishedById' => $this->GetCurrentUser()['LocalUser'],
            'CreateDate' => date('Y-m-d H:i:s')
        ])->Save();

        return $this->Redirect('/post/edit/' . $post->Id);
    }

    public function Edit($id = null)
    {
        if($id == null){
            return $this->HttpNotFound();
        }

        $post = $this->Models->Post->Find($id);
        if($post == null){
            return $this->HttpNotFound();
        }

        $this->EnqueueCssFiles(['summernote.css']);
        $this->EnqueueJavascript([
            'summernote.js',
            'handlebars-v4.0.11.js',
            'editor.js'
        ]);

        $this->Title = 'Edit Post';

        $this->Set('Post', $post);
        $this->Set('PostStatuses', $this->Models->PostStatus->All());

        $tags = $this->Models->Tag->All()->MapToArray('Id');
        foreach($tags as $tag){
            $tag->IsUsed = false;
            $tag->IdName = "tag-" . $tag->DisplayName;
        }

        foreach($post->PostTags->Map(function($item){ return $item->Tag; }) as $usedTag){
            $tags[$usedTag->Id]->IsUsed = true;
        }

        $this->Set('Tags', $tags);
        return $this->View();
    }
}