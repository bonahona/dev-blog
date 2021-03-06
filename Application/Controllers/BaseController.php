<?php
class BaseController extends Controller
{
    public function BeforeAction()
    {
        $this->LogVisit();

        $this->EnqueuBasicCss();
        $this->EnqueuBasicJavascript();

        $this->SetLinks();
        $this->SetNewestPosts();
        $this->SetProjects();
        $this->SetTags();
    }

    protected function LogVisit()
    {
        $this->Models->Visit->Create([
            'SessionId' => session_id(),
            'RequestUrl' => $this->RequestUri,
            'TimeStamp' => date('Y-m-d H:i:s')
        ])->Save();
    }

    protected  function EnqueuBasicCss()
    {
        $this->EnqueueCssFiles([
            'bootstrap-dark.min.css',
            'dashboard.css'
        ]);
    }

    protected function EnqueuBasicJavascript()
    {
        $this->EnqueueJavascript([
            'jquery.min.js',
            'bootstrap.min.js',
            'dashboard.js',
            'sh_main.min.js',
            'sh_cpp.min.js',
            'sh_csharp.min.js',
            'sh_php.min.js'
        ]);
    }

    protected function SetLinks()
    {
        $this->Set('ApplicationLinks', $this->Helpers->ShellAuth->GetApplicationLinks()['data']);
    }

    protected  function SetNewestPosts()
    {
        $publishedStatus = $this->Models->PostStatus->Where(['DisplayName' => 'Published'])->First();
        if($publishedStatus == null){
            // Returns an empty array. This way, nothing should crash even though no data will be displayed.
            return array();
        }

        $posts = $this->Models->Post->Where(['PostStatusId' => $publishedStatus->Id])->OrderByDescending('PublishDate')->Take(10);
        $this->Set('LatestPosts', $posts);
    }

    protected function SetProjects()
    {
        $result = array(
            'left' => array(),
            'right' => array()
        );

        $projects = $this->Models->Project->Where(['IsDeleted' => 0, 'IsActive' => 1])->OrderBy('Name');

        $count = 0;
        foreach($projects as $project){
            if($count % 2 == 0){
                $result['left'][] = $project;
            }else{
                $result['right'][] = $project;
            }
            $count++;
        }
        $this->Set('DisplayProjects', $result);
    }

    protected function SetTags()
    {
        $result = array(
            'left' => array(),
            'right' => array()
        );


        $tags = $this->Models->Tag->Where(['IsActive' => 1])->OrderBy('DisplayName');

        $count = 0;
        foreach($tags as $tag){
            if($count % 2 == 0){
                $result['left'][] = $tag;
            }else{
                $result['right'][] = $tag;
            }

            $count++;
        }

        $this->Set('DisplayTags', $result);
    }

    protected function GetRemoteUser($id)
    {
        $localUserDetails = $this->Models->LocalUserDetails->Where(['ShellUserId' => $id])->First();

        if($localUserDetails != null){
            return;
        }

        $localUserName = $this->Helpers->ShellAuth->GetUserName($id);
        $localUserDetails = $this->Models->LocalUserDetails->Create(['ShellUserId' => $id, 'Name' => $localUserName, 'Fetched' => date('Y-m-d H:i:s')]);
        $localUserDetails->Save();
    }
}