<?php

/**
* 
*/
class DBHelper
{

	private $db;

	function __construct($config)
	{
		$this->connect($config);
	}

	//connecting mongodb
    private function connect($config)
    {
    	try{
			if ( !class_exists('Mongo')){
	            echo ("The MongoDB PECL extension has not been installed or enabled");
	            return false;
	        }
	        $connection = new MongoClient($config['connection_string'], array('username'=>$config['username'],'password'=>$config['password']));
            return $this->db = $connection->selectDB($config['dbname']);
		}catch(Exception $e) {
            echo $e;
			return false;
		}
    }

    //get all data
    public function get($collection)
    {
    	$table = $this->db->selectCollection($collection);
    	$cursor = $table->find();

    	return $cursor;
    }

    public function getEventsByChannel($channelTitle){

        $table = $this->db->selectCollection('events');

        $query = array('channelTitle' => $channelTitle);
        $cursor = $table->find($query);

        return $cursor;
    }

    public function getActionsByChannel($channelTitle){

        $table = $this->db->selectCollection('actions');

        $query = array('channelTitle' => $channelTitle);
        $cursor = $table->find($query);

        return $cursor;
    }

    public function getRuleByEventAndChannel($channelTitle, $event){
        $table = $this->db->selectCollection('events');

        $query = array('channelTitle' => $channelTitle, 'title' => $event);
        $cursor = $table->find($query);

        return $cursor;
    }

    public function getRuleByActionAndChannel($channelTitle, $action){
        $table = $this->db->selectCollection('actions');

        $query = array('channelTitle' => $channelTitle, 'title' => $action);
        $cursor = $table->find($query);

        return $cursor;
    }
    public function removeActionChoosen(){
        $this->db->selectCollection('eventsactionschoosen')->remove(array('action' =>  array('$ne' => '')), array("justOne" => false));
    }

    public function removeEventChoosen(){
        $this->db->selectCollection('eventsactionschoosen')->remove(array('event' =>  array('$ne' => '')), array("justOne" => false));
    }

    public function getRulesByPlace($place)
    {
        $table = $this->db->selectCollection('rules');
        $query = array('place' => $place);
        $cursor = $table->find($query);

        return $cursor;
    }

    public function getRulesByUser($user)
    {
        $table = $this->db->selectCollection('rules_users');
        $query = array('user' => $user);
        $cursor = $table->find($query);
        $tableRules = $this->db->selectCollection('rules');
        $rules = array();
        foreach ($cursor as $rule) {
            $queryRules = array('title' => $rule['title']);
            $rule = $tableRules->findOne($queryRules);
            array_push($rules, $rule);
        }
        return $rules;
    }

    public function getChannelByTitle($title)
    {
        $table = $this->db->selectCollection('channels');
        $query = array('title' => $title);
        $cursor = $table->find($query);

        return $cursor;
    }
    //get one data by id
    public function getById($id, $collection)
    {
    }

    //create article
    public function create($collection, $article)
    {
    	$table = $this->db->selectCollection($collection);
    	$table->insert($article);
    }

    public function removeChannelByTitle($title){
        $table = $this->db->selectCollection('channels');
        $table->remove(array('title' => $title));
        $this->removeEventsAndActionsByChannelTitle($title, 'actions');
        $this->removeEventsAndActionsByChannelTitle($title, 'events');
    }
    
    public function removeRuleByTitle($title){
        $table = $this->db->selectCollection('rules');
        $table->remove(array('title' => $title));
	$table = $this->db->selectCollection('rules_users');
        $table->remove(array('title' => $title));
    }

    public function removeRuleByChannelTitle($title){
        $table = $this->db->selectCollection('rules');
        $table->remove(array('channelOne' => $title));
        $table->remove(array('channelTwo' => $title));
    }

    public function removeEventsAndActionsByChannelTitle($title, $collection){
        $table = $this->db->selectCollection($collection);
        $table->remove(array('channelTitle' => $title));
    }

    public function userExists($username){
        $table = $this->db->selectCollection('users');
        $query = array('username' => $username);
        $cursor = $table->findOne($query);
    	if(empty($cursor)) return false;
    	else return true;
    }
    
    public function logUser($username, $password){
        $table = $this->db->selectCollection('users');
        $query = array('username' => $username, 'password' => $password);
        $cursor = $table->findOne($query);
    	if(empty($cursor)) return false;
        return true;
    }

    public function noImport($title, $user){
        $table = $this->db->selectCollection('rules_users');
        $table->remove(array('title' => $title, 'user' => $user));
    }

    public function alreadyImported($title, $user){
        $table = $this->db->selectCollection('rules_users');
        $query = array('title' => $title, 'user' => $user);
        $cursor = $table->findOne($query);
        if(empty($cursor)) return false;
        return true;
    }

    public function updateTwitterUser($user, $accesstoken, $secrettoken){
        $table = $this->db->selectCollection('users');
        $query = array('username' => $user);
        $cursor = $table->update($query, array('$set' => array('twitteraccesstoken' => $accesstoken, 'twittersecrettoken'=>$secrettoken)));
        return $cursor;
    }

    public function getUserByUsername($username){
        $table = $this->db->selectCollection('users');
        $query = array('username' => $username);
        $cursor = $table->findOne($query);
        return $cursor;
    }
}



?>
