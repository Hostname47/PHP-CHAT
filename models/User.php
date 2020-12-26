<?php

namespace models;

use classes\Hash;
use classes\Config;
use classes\Session;
use classes\DB;

class User {
    private $db,
        $sessionName,

        $id,
        $username,
        $password,
        $salt,
        $firstname,
        $lastname,
        $joined,
        $user_type,

        $isLoggedIn;

    // Everytime we instantiate a user object we need to check if the session is already set to determine wethere we login or not
    public function __construct() {
        $this->db = DB::getInstance();
        $this->sessionName = Session::get('session/session_name');

        if(Session::exists($this->sessionName)) {
            $dt = Session::get($this->sessionName);
            
            if($this->fetchUser("id", $dt)) {
                $this->isLoggedIn = true;
            } else {
                // Process logout
            }
        }
    }

    public function getPropertyValue($propertyName) {
        return $this->$propertyName;
    }
    
    public function setPropertyValue($propertyName, $propertyValue) {
        $this->$propertyName = $propertyValue;
    }
    
    public function fetchUser($field_name, $field_value) {
        $this->db->query("SELECT * FROM user_info WHERE $field_name = ?", array($field_value));

        // Here we need to check first if we get a user with the given id before starting assigning values to its properties
        if($this->db->count() > 0) {
            $fetchedUser = $this->db->results()[0];

            $this->id = $fetchedUser->id;
            $this->username = $fetchedUser->username;
            $this->password = $fetchedUser->password;
            $this->salt = $fetchedUser->salt;
            $this->firstname = $fetchedUser->firstname;
            $this->lastname = $fetchedUser->lastname;
            $this->joined = $fetchedUser->joined;
            $this->user_type = $fetchedUser->user_type;

            return true;
        }

        return false;
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

        return ($this->db->error()) ? false : true;
    }

    public function delete() {
        $this->db->query("DELETE FROM user_info WHERE id = ?", array($this->id));

        return ($this->db->error()) ? false : true;
    }

    /*
    This function basically accepts two arguments, first the username and then the password in plaintext. First we check if
    the username exists in database, if so we need we fetch this user and compare the password of that user with the plain text passes by adding salt
    to the password and hash it and compare it with password in database
    */
    public function login($username='', $password='') {
        
        if($this->fetchUser("username", $username)) {
            if($this->password === Hash::make($password, $this->salt)) {
                Session::put($this->sessionName, $this->id);
                return true;
            }
        }

        return false;
    }

    public function isLoggedIn() {
        return $this->isLoggedIn;
    }

    public function toString() {
        return "User with:
            id: $this->id,
            firstname: $this->firstname,
            lastname: $this->lastname,
            username: $this->username,
            password: $this->password,
            salt: $this->salt,
            joined: $this->joined,
            user_type: $this->user_type,";
    }
}