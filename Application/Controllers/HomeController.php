<?php
require_once('BaseController.php');
class OgData
{
    public $OgTitle;
    public $OgDescription;
    public $OgImageUrl;
    public $OgType;
    public $OgAuthorFirstName;
    public $OgAuthorLastName;
    public $OgAuthorId;
    public $OgArticlePublishDate;
    public $OgArticleModifiedDate;
}

class HomeController extends BaseController
{
    public function Index()
    {
        if(count($this->Parameters) == 0){
            return $this->ShowHomePage();
        }else{
            return $this->FindPost($this->Parameters);
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

    private function FindPost($path)
    {
        $post = $this->Models->Post->Where(['NavigationTitle' => $path[0]])->First();
        if($post != null){
            return $this->ShowPost($post);
        }

        $sharePost = $this->Models->SharePost->Where(['NavigationLink' => $path[0]])->First();
        if($sharePost != null){
            return $this->ShowShare($sharePost->Post);
        }

        return $this->HttpNotFound();
    }

    private function ShowPost($post)
    {
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

        $this->SetOpenGraphData($post);

        $this->Set('Post', $post);
        return $this->View('Post');
    }

    private function ShowShare($post)
    {
        $this->Title = $post->Title;
        $publishStatus = $this->Models->PostStatus->Where(['DisplayName' => 'Published'])->First();
        if($publishStatus == null){
            return $this->HttpNotFound();
        }

        $post->IsPublished = true;

        if($post->PostStatusId != $publishStatus->Id){
            $post->IsPublished = false;
        }

        $this->SetOpenGraphData($post);

        $this->Set('Post', $post);
        return $this->View('Post');
    }

    private function SetOpenGraphData($post)
    {
        $ogData = new OgData();
        $ogData->OgTitle = $post->OgTitle;
        $ogData->OgDescription = $post->OgDescription;

        if($post->OgImageUrl != null && $post->OgImageUrl != ""){
            $ogData->OgImageUrl = $post->OgImageUrl;
        } else {
            $ogData->OgImageUrl = $post->MastHeadImageUrl;
        }

        $ogData->OgType = 'article';
        $ogData->OgAuthorFirstName = $post->GetAuthor()->FirstName;
        $ogData->OgAuthorLastName = $post->GetAuthor()->LastName;
        $ogData->OgAuthorId = $post->GetAuthor()->FacebookId;

        $ogData->OgArticlePublishDate = $post->GetPublishDateIso();
        $ogData->OgArticleModifiedDate = $post->GetModifiedDateIso();

        $this->Set('OgData', $ogData);
    }

    public function Search()
    {
        $this->Title = "Search result";

        if(isset($this->Get['tag'])){
            return $this->SearchTags($this->Get['tag']);
        }else if($this->Get['keywords']){
            return $this->SearchKeywords($this->Get['keywords']);
        }else if($this->Get['project']){
            return $this->SearchProjectName($this->Get['project']);
        }else{
            return $this->InvalidSearchParameter();
        }
    }

    private function SearchTags($tagName)
    {
        $result = new Collection();

        $publishedStatus = $this->Models->PostStatus->Where(['DisplayName' => 'Published'])->First();
        if($publishedStatus == null){
            return $this->HttpStatus('500', 'Published status not found');
        }

        $tag = $this->Models->Tag->Where(['DisplayName' => $tagName, 'IsActive' => 1])->First();
        if($tag != null){
            foreach($tag->PostTags as $postTag){
                $post = $postTag->Post;
                if($post->PostStatusId == $publishedStatus->Id){
                    $result->Add($post);
                }
            }
        }

        $result = $result->OrderByDescending('PublishDate');
        $this->Set('Posts', $result);
        return $this->View('Search');
    }

    private function SearchProjectName($projectName)
    {
        $result = new Collection();

        $publishedStatus = $this->Models->PostStatus->Where(['DisplayName' => 'Published'])->First();
        if($publishedStatus == null){
            return $this->HttpStatus('500', 'Published status not found');
        }

        $project = $this->Models->Project->Where(['Name' => urldecode($projectName), 'IsActive' => 1, 'IsDeleted' => 0])->First();
        if($project != null){
            foreach($project->Posts as $post){
                if($post->PostStatusId == $publishedStatus->Id){
                    $result->Add($post);
                }
            }
        }

        $result = $result->OrderByDescending('PublishDate');
        $this->Set('Posts', $result);
        return $this->View('Search');
    }

    private function SearchKeywords($keywords)
    {
        $result = new Collection();

        $exactPost = $this->Models->Post->Where(array('Title' => $keywords));

        $likePostTitle = $this->Models->Post->Where(LikeCondition('Title',$keywords));
        $likePostText = $this->Models->Post->Where(LikeCondition('HomePageText',$keywords));

        $likePostContent = $this->Models->PostContent->Where(LikeCondition('Content',$keywords))->Map(function($item){ return $item->Post;});

        $result->AddRange($exactPost);
        $result->AddRange($likePostTitle);
        $result->AddRange($likePostText);
        $result->AddRange($likePostContent);

        $result = $result->OrderByDescending('PublishDate')->MapToArray('Id');

        $this->Set('SearchQuery', $keywords);
        $this->Set('Posts', $result);
        return $this->View('Search');
    }

    private function InvalidSearchParameter()
    {
        $this->Set('SearchQuery', '');
        $this->Set('Posts', new Collection());
        return $this->View('Search');
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