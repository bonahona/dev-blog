<?php
class ShellAuthHelper implements  IHelper
{
    public $ApplicationName;
    public $PublicKey;

    public $ShellAuthServer;
    public $ShellAuthPort;
    public $ShellAuthMethodPath;
    public $LocalApplicationId;

    public $Controller;

    public function Init($config, $controller)
    {
        $this->ApplicationName = $config['ShellApplication']['Name'];
        $this->PublicKey = $config['ShellApplication']['PublicKey'];
        $this->LocalApplicationId = $config['ShellApplication']['LocalId'];

        $this->ShellAuthServer = $config['ShellAuthServer']['Server'];
        $this->ShellAuthPort = $config['ShellAuthServer']['Port'];
        $this->ShellAuthMethodPath = $config['ShellAuthServer']['MethodPath'];

        $this->Controller = $controller;
    }

    public function GetApplicationLinks()
    {
        $payload = "query{
    PublicApplications{
		Id,
		MenuName,
		Url
	}
}";
        return $this->SendToServer($payload);
    }

    public function CreateApplication($application)
    {
        $name = $application['Name'];
        $isActive = (int)isset($application['IsActive']);
        $defaultUserLevel = $application['DefaultUserLevel'];
        $rsaPublicKey = $application['RsaPublicKey'];
        $showInMenu = (int)isset($application['ShowInMenu']);
        $menuName = $application['MenuName'];
        $url = $application['Url'];

        $payload = "mutation{
	ShellApplication(
		Name: \"$name\",
		IsActive: $isActive,
		DefaultUserLevel: $defaultUserLevel,
		RsaPublicKey: \"$rsaPublicKey\",
		ShowInMenu: $showInMenu,
		MenuName: \"$menuName\",
		Url: \"$url\"
	){
		Id,
		Name,
		IsActive,
		DefaultUserLevel,
		RsaPublicKey,
		ShowInMenu,
		MenuName,
		Url
	}
}";

        return $this->SendToServer($payload);
    }

    public function EditApplication($application)
    {
        $id = $application['Id'];
        $name = $application['Name'];
        $isActive = (int)isset($application['IsActive']);
        $defaultUserLevel = $application['DefaultUserLevel'];
        $rsaPublicKey = $application['RsaPublicKey'];
        $showInMenu = (int)isset($application['ShowInMenu']);
        $menuName = $application['MenuName'];
        $url = $application['Url'];

        $payload = "mutation{
	ShellApplication(
		Id: \"$id\",
		Name: \"$name\",
		IsActive: $isActive,
		DefaultUserLevel: $defaultUserLevel,
		RsaPublicKey: \"$rsaPublicKey\",
		ShowInMenu: $showInMenu,
		MenuName: \"$menuName\",
		Url: \"$url\"
	){
		Id,
		Name,
		IsActive,
		DefaultUserLevel,
		RsaPublicKey,
		ShowInMenu,
		MenuName,
		Url
	}
}";

        return $this->SendToServer($payload);
    }

    public function DeleteApplication($id)
    {
        $payLoad = $id;
        $callPath = $this->GetApplicationPath('DeleteApplication');
        return $this->SendToServer($payLoad, $callPath);
    }

    public function GetApplications()
    {
        $payload = "query{
	ShellApplications {
		Id,
		Name,
		IsActive
	}
}";
        return $this->SendToServer($payload);
    }

    public function GetApplication($id)
    {
        $payload = "query{
	ShellApplication(id: \"$id\") {
		Id,
		Name,
		IsActive,
		RsaPublicKey,
		DefaultUserLevel,
		ShowInMenu,
		MenuName,
		Url
	}
}";
        return $this->SendToServer($payload);
    }

    public function CreateUser($shellUser)
    {
        $username = $shellUser['Username'];
        $displayName = $shellUser['DisplayName'];
        $password =$shellUser['Password'];

        $payLoad = "mutation{
	ShellUser(
		Username: \"$username\",
		DisplayName: \"$displayName\",
		Password: \"$password\"
	){
		Id,
		Username,
		DisplayName
	}
}";
        return $this->SendToServer($payLoad);
    }

    public function EditUser($shellUser)
    {
        $id = $shellUser['Id'];
        $username = $shellUser['Username'];
        $displayName = $shellUser['DisplayName'];

        $payLoad = "mutation{
	ShellUser(
		Id: \"$id\",
		Username: \"$username\",
		DisplayName: \"$displayName\"
	){
		Id,
		Username,
		DisplayName
	}
}";

        return $this->SendToServer($payLoad);
    }

    public function ResetPassword($userId, $password)
    {
        $payload = "mutation{
	ShellUser(
		Id: \"$userId\",
		Password: \"$password\"
	){
		Id
	}
}";
        return $this->SendToServer($payload);
    }

    public function Login($username, $password)
    {
        $payLoad = "mutation{
	Login(
		username: \"$username\",
		password: \"$password\",
		application: \"$this->ApplicationName\"
	){
		Guid,
		Expires,
		Issued,
		ShellUserPrivilege{
			ShellUser{
				Id,
				DisplayName,
				Username,
				IsActive
			}
		}
	}
}";

        $response =  $this->SendToServer($payLoad);

        if(count($response['errors']) == 0){
            $this->Controller->Session['SessionToken'] = $response['data']['Login']['Guid'];
            $user = $response['data']['Login']['ShellUserPrivilege']['ShellUser'];


            // Check if a local user exists, and if not, create on
            $userId = $user['Id'];
            $localUser = $this->Controller->Models->LocalUser->Where(['ShellUserId' => $userId])->First();

            if($localUser == null){
                $localUser = $this->Controller->Models->LocalUser->Create(array('ShellUserId' => $userId));
                $localUser->Save();
            }

            $user['LocalUser'] = $localUser->Id;
            $this->Controller->SetLoggedInUser($user);
        }

        return $response;
    }

    public function Logout($accessToken = null)
    {
        $this->Controller->Session->Destroy();
    }

    public function GetUser($id)
    {
        $payload = "query{
	ShellUser(id: \"$id\")
	{
		Id,
		Username,
		DisplayName,
		IsActive,
		Privileges{
			ShellApplication{
				Id,
				Name,
				IsActive
			},
			Id,
			UserLevel
		}
	},
	ShellApplications{
		Id,
		Name,
		IsActive
	}
}";

        return $this->SendToServer($payload);
    }

    public function GetUsers()
    {
        $payload = 'query{
	ShellUsers{
		Id,
		Username,
		DisplayName,
		IsActive
	}
}';
        return $this->SendToServer($payload);
    }

    public function GetLocalUsers()
    {
        $id = $this->LocalApplicationId;

        $payload = "
        query{
	ShellApplication(id: \"$id\"){
		Id,
		Privileges{
		    Id,
			UserLevel
			ShellUser{
				Id,
				Username,
				DisplayName				
			}
		}
	}
}";

        return $this->SendToServer($payload);
    }

    public function SetPrivilegeLevel($privilegeId, $userLevel)
    {
        $payLoad = "
        mutation{
	ShellUserPrivilege(
		Id: \"$privilegeId\",
		UserLevel: $userLevel
	){
		ShellUserId,
		UserLevel
	}
}";

        return $this->SendToServer($payLoad);
    }

    public function CreatePrivilege($applicationId, $userId, $userLevel)
    {
        $payload = "
        mutation{
	ShellUserPrivilege(
		ShellUserId: \"$applicationId\",
		ShellApplicationId: \"$userId\",
		UserLevel: $userLevel
	){
		Id,
		UserLevel
	}
}";
        return $this->SendToServer($payload);
    }

    public function GetUserApplicationPrivileges($userId)
    {
        $payLoad = array(
            'Id' => $userId
        );

        $callPath = $this->GetApplicationPath('GetUserApplicationPrivileges');
        return $this->SendToServer($payLoad, $callPath);
    }

    public function GetUsersById(array $userIds)
    {
        $queries = [];
        $count = 0;
        foreach($userIds as $id){
            $queries[] = "user" . str_pad($count, 2, "0", STR_PAD_LEFT) . ": ShellUser(id: \"$id\"){
                Username
            }";
            $count++;
        }

        $payload = "query{" . implode("\n", $queries) . "}";
        return $this->SendToServer($payload);
    }

    protected function GetApplicationPath()
    {
        $result = 'http://' . $this->ShellAuthServer . ":" . $this->ShellAuthPort . $this->ShellAuthMethodPath;

        return $result;
    }

    protected function SendToServer($payload)
    {
        $callPath = $this->GetApplicationPath();
        $data = ['query' => $payload];

        $data = json_encode($data);

        $headers = [
            'Content-Type: application/json',
            'Authorization: ' . $this->Controller->Session['SessionToken'],
            'Psk: ' . getenv('PRE_SHARED_KEY')
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $callPath);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, "ShellAuthConnector");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        if(!$response = curl_exec($curl)){
            $curlError = curl_error($curl);
            //$curlErrorCode = curl_errno($curl);

            return array(
                'data' => [],
                'errors' => [
                    $curlError
                ]
            );
        }

        curl_close($curl);

        return json_decode($response, true);
    }
}