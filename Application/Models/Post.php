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
}