<?php

namespace models;

use classes\{Hash, Config, Session, DB, Cookie};

class User {
    private $db,
        $sessionName,
        $cookieName,

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
        $this->sessionName = Config::get('session/session_name');
        $this->cookieName = Config::get('remember/cookie_name');

        if(Session::exists($this->sessionName)) {
            $dt = Session::get($this->sessionName);
            
            if($this->fetchUser("id", $dt)) {
                $this->isLoggedIn = true;
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

    login first check if this user actually exists by verifying it's id; if id is set then this user is exists, in this case we don't have to fetch its data
    we simply make a session by this id and set isLoggedIn to true and return true;
    */
    public function login($username='', $password='', $remember=false) {
        if($this->id) {
            Session::put($this->sessionName, $this->id);
            $this->isLoggedIn = true;
            return true;
        } else {
            if($this->fetchUser("username", $username)) {
                if($this->password === Hash::make($password, $this->salt)) {
                    Session::put($this->sessionName, $this->id);
                    
                    /* 
                    This will only executed if user's credentials are good and he checks remember me: We generate a hash, 
                    check if the hash is not  already exists in user_session table and insert that hash into the database;
                    What happens is the user store a cookie with id and a hash and also the app store these infos in database
                    Next time the user visit the app we need to check if he has a cookie that identifies it, if so we compare the hash of it with hash in db
                    */
                    if($remember) {
                        $this->db->query("SELECT * FROM users_session WHERE user_id = ?",
                            array($this->id));
                        
                        // If this user is not exists in users_sesion table
                        if(!$this->db->count()) {
                            $hash = Hash::unique();
                            $this->db->query('INSERT INTO users_session (user_id, hash) VALUES (?, ?)', 
                                array($this->id, $hash));
                        } else {
                            // If the user does exist we 
                            $hash = $this->db->results()[0]->hash;
                        }
    
                        Cookie::put($this->cookieName, $hash, Config::get("remember/cookie_expiry"));
                    }
                    return true;
                }
            }
        }

        return false;
    }

    // When we logout, we actually have to delete the session and cookie hash, and also delete session from database;
    public function logout() {

        $this->db->query("DELETE FROM users_session WHERE user_id = ?", array($this->id));

        Session::delete($this->sessionName);
        Cookie::delete($this->cookieName);
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