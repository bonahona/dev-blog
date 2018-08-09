<?php
class Post extends Model
{
    public $TableName = 'post';

    public function GetAuthor()
    {
        $localUser = $this->Models->LocalUserDetails->Where(['ShellUserId' => $this->PublishedById])->First();

        if($localUser != null){
            return $localUser;
        }

        $localUserName = $this->Helpers->ShellAuth->GetUserName($this->PublishedById);
        $localUserDetails = $this->Models->LocalUserDetails->Create(['ShellUserId' => $this->PublishedById, 'Name' => $localUserName, 'Fetched' => date('Y-m-d H:i:s')]);
        $localUserDetails->Save();

        return $localUserDetails;
    }

    public function GetPublishDate()
    {
        return date('Y-m-d', strtotime($this->PublishDate));
    }

    public function GetModifiedDate()
    {
        if(!empty($this->EditDate)){
            return date('Y-m-d', strtotime($this->PublishDate));
        }else{
            return "";
        }
    }

    public function GetPublishDateIso()
    {
        return date(DATE_ISO8601, strtotime($this->PublishDate));
    }

    public function GetModifiedDateIso()
    {
        return date(DATE_ISO8601, strtotime($this->EditDate));
    }
}