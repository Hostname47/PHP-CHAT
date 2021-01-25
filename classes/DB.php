<?php

namespace classes;
/* 
   DB Class follow Singleton pattern.
   We'll use the following class as following: $users = DB::getInstance()->query("SELECT * FROM users");
   DB::getInstance()->get("user", array('username', '=', 'mouad'))
                          1^^^^         2^^^^^^^^    ^   3^^^^^
    1: table,
    2, 3: where username equals (=) mouad
    I think i will not use this method, i don't know we'll see !
*/


class DB {
    // This will store database isntance if it is available
    private static $_instance = null;
    private $_pdo,
            $_error = false,
            $_query,
            $_results,
            $_count = 0;

    private function __construct() {
        $this->_pdo = new \PDO("mysql:host=" . Config::get('mysql/host') . ";dbname=" . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
    }

    public static function getInstance() {
        /* 
        This function will first check if we've already instaiated an instance it will simply return th instance,
        otherwise, we're going to instatiate it by creating a DB object;
        Hint: In case we use getInstance function twice on a page it will simply return the instance in the second 
              call because it will be instantiated in the first call
        Hint: Notice that DB class cannot be instatiated outiside the class because the constructor is private
        */

        if(!isset(self::$_instance)) {
            self::$_instance = new DB();
        }

        return self::$_instance;
    }
    
    /*
    DB::getInstance()->query("SELECT * FROM user_info WHERE username = ?", array('MOUAD')); HERE MOUAD shuld be replaced
    in the first ? mark, if we have two parameters in query string, we need array of two values to replace each value to
    its correspondent place
    */
    public function query($sql, $params = array()) {
        /* Here we set error to false in order to not return error of some previous query */
        $this->error = false;

        // Check if the query has been prepared successfully
        // Here we assign and at the sametime check if a prepared statement has been set
        if($this->_query = $this->_pdo->prepare($sql)) {
            //Check if parameters exists (one at least should be exist) in case we need to bind them to the prepared statement
            if(count($params)) {
                $count = 1;
                foreach($params as $param) {

                    /* FROM Documentation: 
                    Parameter identifier. For a prepared statement using named placeholders, this will be a parameter name of the form :name. For a prepared statement using question mark placeholders, this will be the 1-indexed position of the parameter.
                    
                    We are using quesion mark in our queries

                    if query = "SELECT * FROM user_info WHERE username = ? AND password = ?;
                                                                         ^                ^
                                                                   count:1          count:2
                    count1 will point to username value which will be filled by first array element, and the same thing occur to the second part 
                    */
                    $this->_query->bindValue($count, $param);
                    $count++;
                }
            }

            // We're going to execute the query anyway regardless whether the query has params or not
            if($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(\PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }

        // This allows you to chain everything with query function
        return $this;
    }

    public function pdo() {
        return $this->_pdo;
    }

    public function error() {
        return $this->_error;
    }

    public function results() {
        return $this->_results;
    }

    public function count() {
        return $this->_count;
    }
}


