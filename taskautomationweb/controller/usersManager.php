<?php

require_once('dbHelper.php');
require_once('config.php');

/**
* 
*/
class UsersManager
{
	
	function __construct($config)
	{
		$this->connect($config);
	}

	//connecting mongodb
    private function connect($config)
    {
    	$this->db = new DBHelper($config);
    }

    public function createUser($username, $password){
        
        $user = array(
            'username' => $username,
            'password' => $password
        );

        if($this->db->userExists($username)) return false;
        $this->db->create('users', $user);
        return true;
    }

    public function logUser($username, $password){
        return $this->db->logUser($username, $password);
    }

    public function updateTwitter($user, $accesstoken, $secrettoken){
        return $this->db->updateTwitterUser($user, $accesstoken, $secrettoken);
    }

    public function getUser($username){
        return $this->db->getUserByUsername($username);
    }
}
?>
