<?php
require_once('BaseController.php');
class HomeController extends BaseController
{
    public function Index()
    {
        if(count($this->Parameters) == 0){
            return $this->ShowHomePage();
        }else{
            return $this->ShowPost($this->Parameters);
        }
    }

    private function ShowHomePage()
    {
        $this->Title = 'Bona\'s Dev blog';

        $publishStatus = $this->Models->PostStatus->Where(['DisplayName' => 'Published'])->First();
        if($publishStatus == null){
            return $this->HttpStatus(500, 'Failed to get publish status');
        }

        $posts = $this->Models->Post->Where(['PostStatusId' => $publishStatus->Id])->OrderByDescending('PublishDate')->Take(5);
        $this->Set('Posts', $posts);

        return $this->View('Index');
    }

    private function ShowPost($path)
    {
        $post = $this->Models->Post->Where(['NavigationTitle' => $path[0]])->First();
        if($post == null){
            return $this->HttpNotFound();
        }

        $this->Title = $post->Title;
        $publishStatus = $this->Models->PostStatus->Where(['DisplayName' => 'Published'])->First();
        if($publishStatus == null){
            return $this->HttpNotFound();
        }

        $post->IsPublished = true;

        if($post->PostStatusId != $publishStatus->Id){
            if($this->IsLoggedIn()){
                $post->IsPublished = false;
            }else {
                return $this->Redirect('/admin', ['ref' => $this->RequestUri]);
            }
        }

        $this->Set('Post', $post);
        return $this->View('Post');
    }

    public function Search()
    {
        return $this->View();
    }

    public function History()
    {
        $this->Title = 'Post history';

        $publishStatus = $this->Models->PostStatus->Where(['DisplayName' => 'Published'])->First();
        if($publishStatus == null){
            return $this->HttpStatus(500, 'Failed to get publish status');
        }

        $posts = $this->Models->Post->Where(['PostStatusId' => $publishStatus->Id])->OrderByDescending('PublishDate');
        $this->Set('Posts', $posts);

        return $this->View();
    }

    public function NotFound()
    {
        return $this->View();
    }
}