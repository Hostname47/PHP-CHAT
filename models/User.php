<?php

namespace models;

class User {
    private $conn;
    private $id;
    private $username;
    private $password;
    private $salt;
    private $firstname;
    private $lastname;
    private $joined;
    private $user_type;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function setData($username, $password, $salt="", $firstname="", $lastname="", $joined, $user_type=1) {
        $this->username = $username;
        $this->password = $password;
        $this->salt = $salt;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->joined = isset($joined) ? $joined : date("Y/m/d h:i:s");
        $this->user_type = $user_type;
    }

    public function add() {
        echo "insert user_type: " . $this->user_type;
        $this->conn->query("INSERT INTO user_info 
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
        return $this->conn->error() == false ? true : false;
    }
}