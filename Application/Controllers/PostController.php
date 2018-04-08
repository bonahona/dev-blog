<?php
require_once ('AdminController.php');
class PostController extends AdminController
{
    public function Index()
    {
        $this->Title = "View Posts";

        $posts = $this->Models->Post->All();
        $this->Set('Posts', $posts);

        return $this->View();
    }

    public function Create()
    {
        $post = $this->Models->Post->Create([
            'PostStatusId' => 1,
            'PublishedById' => $this->GetCurrentUser()['LocalUser']
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
        $this->EnqueueJavascript(['summernote.js', 'editor.js']);

        $this->Title = 'Edit Post';

        $this->Set('Post', $post);
        $this->Set('PostStatuses', $this->Models->PostStatus->All());

        return $this->View();
    }
}