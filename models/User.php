<?php

namespace models;

class User {
    private $db;
    private $id;
    private $username;
    private $password;
    private $salt;
    private $firstname;
    private $lastname;
    private $joined;
    private $user_type;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getPropertyValue($propertyName) {
        return $this->$propertyName;
    }
    
    public function setPropertyValue($propertyName, $propertyValue) {
        $this->$propertyName = $propertyValue;
    }
    
    public function selectById($id) {
        $this->db->query("SELECT * FROM user_info WHERE id = ?", array($id));
        $fetchedUser = $this->db->results()[0];

        $this->id = $fetchedUser->id;
        $this->username = $fetchedUser->username;
        $this->password = $fetchedUser->password;
        $this->salt = $fetchedUser->salt;
        $this->firstname = $fetchedUser->firstname;
        $this->lastname = $fetchedUser->lastname;
        $this->joined = $fetchedUser->joined;
        $this->user_type = $fetchedUser->user_type;
    }

    // This function will be used to set all user's data into th user object so that it makes it ready to be addedd, edit it or delete it
    //public function setData($id=null, $username="", $password="", $salt="", $firstname="", $lastname="", $joined=null, $user_type=1) {
    public function setData($data = array()) {
        $this->id = isset($data["id"]) ? $data["id"] : null;
        $this->username = $data["username"];
        $this->password = $data["password"];
        $this->salt = $data["salt"];
        $this->firstname = $data["firstname"];
        $this->lastname = $data["lastname"];
        $this->joined = $data["joined"];
        $this->user_type = $data["user_type"];
    }

    /* 
    Note that if you want to add new user by specifying id, you can actually fetch the last user and add 1 to its id,
    then class add function by adding id to add query
    */
    public function add() {
        $this->db->query("INSERT INTO user_info 
        (username, password, salt, firstname, lastname, joined, user_type) 
        VALUES (?, ?, ?, ?, ?, ?, ?)", array(
            $this->username,
            $this->password,
            $this->salt,
            $this->firstname,
            $this->lastname,
            $this->joined,
            $this->user_type
        ));

        // This will return true if the query error function in DB generate an error
        // Hint: note that we need to retrun true if there's no errors, returning true in add function means everything goes well
        return $this->db->error() == false ? true : false;
    }

    /*
    After getting the user by id and editing its properties, all you need to do to edit it is to call this function
    and it will do all the work for you
    */
    public function update() {
        $this->db->query("UPDATE user_info SET username=?, password=?, salt=?, firstname=?, lastname=?, joined=?, user_type=? WHERE id=?",
        array(
            $this->username,
            $this->password,
            $this->salt,
            $this->firstname,
            $this->lastname,
            $this->joined,
            $this->user_type,
            $this->id
        ));
    }

    public function delete() {
        $this->db->query("DELETE FROM user_info WHERE id = ?", array($this->id));

        return ($this->db->error()) ? false : true;
    }


}