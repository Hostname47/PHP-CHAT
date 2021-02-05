<?php

namespace models;

use classes\{Hash, Config, Session, DB, Cookie};

class User implements \JsonSerializable {
    private $db,
        $sessionName,
        $cookieName,

        $id,
        $username='',
        $email='',
        $password='',
        $salt='',
        $firstname='',
        $lastname='',
        $joined='',
        $user_type=1,
        $bio='',
        $cover='',
        $picture='',
        $private=-1,
        $last_active_update='',

        $isLoggedIn=false;

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

    public function get_metadata($label="") {
        $metadata = array();
        $values = array($this->id);
        $query = "SELECT * FROM user_metadata WHERE `user_id` = ?";

        // If the user qpecify label then we need to fetch the specific metadata with that label provided
        if(!empty($label)) {
            $query .= " AND `label` = ?";
            $values[] = $label;
        }

        $this->db->query($query, $values);

        return $this->db->results();
    }

    public function get_metadata_items_number() {
        $this->db->query("SELECT COUNT(*) as number_of_labels FROM user_metadata WHERE `user_id` = ?", array($this->id));

        // If there's a row found, then we return the count alias (number_of_labels)
        if(count($this->db->results()) > 0) {
            return $this->db->results()[0]->number_of_labels;
        }
        return array();
    }

    public function metadata_exists($label) {
        $this->db->query("SELECT COUNT(*) as number_of_labels FROM user_metadata WHERE `label`=?  AND `user_id`=?", array(
            $label,
            $this->id
        ));

        // If there's a row found, then we return true
        if($this->db->results()[0]->number_of_labels != 0) {
            return true;
        }
        return false;
    }

    public function add_metadata($label, $content) {
        if($this->get_metadata_items_number() < 6 && $content != "") {
            $this->db->query("INSERT INTO user_metadata (`label`, `content`, `user_id`) values(?, ?, ?);", array(
                $label,
                $content,
                $this->id
            ));

            return true;
        }

        return false;
    }

    public function update_metadata($label, $content) {
        $this->db->query("UPDATE user_metadata SET `content`=? WHERE `label`=? AND `user_id`=?", array(
            $content,
            $label,
            $this->id
        ));

        return $this->db->error() == false ? true : false;
    }

    public function set_metadata($metadata) {
        foreach($metadata as $mdata) {
            if($this->metadata_exists($mdata["label"])) {
                $this->update_metadata($mdata["label"], $mdata["content"]);
            } else {
                $this->add_metadata($mdata["label"], $mdata["content"]);
            }
        }
    }

    public static function user_exists($field, $value) {
        DB::getInstance()->query("SELECT * FROM user_info WHERE $field = ?", array($value));

        if(DB::getInstance()->count() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function fetchUser($field_name, $field_value) {
        $this->db->query("SELECT * FROM user_info WHERE $field_name = ?", array($field_value));

        // Here we need to check first if we get a user with the given id before starting assigning values to its properties
        if($this->db->count() > 0) {
            $fetchedUser = $this->db->results()[0];

            $this->id = $fetchedUser->id;
            $this->username = $fetchedUser->username;
            $this->email = $fetchedUser->email;
            $this->password = $fetchedUser->password;
            $this->salt = $fetchedUser->salt;
            $this->firstname = $fetchedUser->firstname;
            $this->lastname = $fetchedUser->lastname;
            $this->joined = $fetchedUser->joined;
            $this->user_type = $fetchedUser->user_type;
            $this->bio = $fetchedUser->bio;
            $this->cover = $fetchedUser->cover;
            $this->picture = $fetchedUser->picture;
            $this->private = $fetchedUser->private;
            $this->last_active_update = $fetchedUser->last_active_update;

            return $this;
        }

        return false;
    }

    public function setData($data = array()) {
        $this->id = $data["id"];
        $this->username = $data["username"];
        $this->email = $data["email"];
        $this->password = $data["password"];
        $this->salt = $data["salt"];
        $this->firstname = $data["firstname"];
        $this->lastname = $data["lastname"];
        $this->joined = isset($data["joined"]) ? $data["joined"] : date("Y/m/d h:i:s");
        $this->user_type = $data["user_type"];
        $this->bio = $data["bio"];
        $this->cover = $data["cover"];
        $this->picture = $data["picture"];
        $this->private = $data["private"];
    }

    /* 
    Note that if you want to add new user by specifying id, you can actually fetch the last user and add 1 to its id,
    then class add function by adding id to add query
    */
    public function add() {
        $this->db->query("INSERT INTO user_info 
        (username, email, password, salt, firstname, lastname, joined, user_type) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)", array(
            $this->username,
            $this->email,
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
        $this->db->query("UPDATE user_info SET username=?, email=?, password=?, salt=?, firstname=?, lastname=?, joined=?, user_type=?, bio=?, cover=?, picture=?, private=? WHERE id=?",
        array(
            $this->username,
            $this->email,
            $this->password,
            $this->salt,
            $this->firstname,
            $this->lastname,
            $this->joined,
            $this->user_type,
            $this->bio,
            $this->cover,
            $this->picture,
            $this->private,
            $this->id
        ));

        return ($this->db->error()) ? false : true;
    }

    public function update_property($property, $new_value) {
        $this->db->query("UPDATE user_info SET $property=? WHERE id=?",
        array(
            $new_value,
            $this->id
        ));

        return ($this->db->error()) ? false : true;
    }

    public function delete() {
        $this->db->query("DELETE FROM user_info WHERE id = ?", array($this->id));

        return ($this->db->error()) ? false : true;
    }

    public static function search($keyword) {
        if(empty($keyword)) {
            return array();
        }

        $keywords = strtolower($keyword);
        $keywords = htmlspecialchars($keywords);
        $keywords = trim($keywords);

        /*
        keyword could be multiple keywords separated by spaces.
        keep in mind that if the keyword is empty, explode will return an array with one empty element
        meaning you need to handle the situation where the first element is empty
        */
        $keywords = explode(' ', $keyword);

        if($keywords[0] == '') {
            // Handle situation where $keyword passed is empty
            $query = "";
        } else {
            $query = "SELECT * FROM user_info ";
            for($i=0;$i<count($keywords);$i++) {
                $k = $keywords[$i];
                if($i==0)
                    $query .= "WHERE username LIKE '%$k%' OR firstname LIKE '%$k%' OR lastname LIKE '%$k%' ";
                else
                    $query .= "OR username LIKE '%$k%' OR firstname LIKE '%$k%' OR lastname LIKE '%$k%' ";
            }
        }

        /*
        We set WHERE false because if the $keywords is empty we don't appear anything and we display a message to the user
        informing him to fill in the search box to find friends or posts ..
        */

        DB::getInstance()->query($query);
        return DB::getInstance()->results();
    }

    public static function search_by_username($username) {
        if(empty($username)) {
            return array();
        }

        $keyword = strtolower($username);
        $keyword = htmlspecialchars($username);
        $keyword = trim($username);

        DB::getInstance()->query("SELECT * FROM user_info WHERE username LIKE '$keyword%'");

        return DB::getInstance()->results();
    }

    /*
    This function basically accepts two arguments, first the username and then the password in plaintext. First we check if
    the username exists in database, if so we need we fetch this user and compare the password of that user with the plain text passes by adding salt
    to the password and hash it and compare it with password in database

    login first check if this user actually exists by verifying it's id; if id is set then this user is exists, in this case we don't have to fetch its data
    we simply make a session by this id and set isLoggedIn to true and return true;
    */
    public function login($email_or_username='', $password='', $remember=false) {
        if($this->id) {
            Session::put($this->sessionName, $this->id);
            $this->isLoggedIn = true;
            return true;
        } else {
            $fetchBy = "username";
            if(strpos($email_or_username, "@")) {
                $fetchBy = "email";
            }

            if($this->fetchUser($fetchBy, $email_or_username)) {
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
        Session::delete(Config::get("session/tokens/logout"));
        Cookie::delete($this->cookieName);
    }

    public function update_active() {
        $this->db->query("UPDATE user_info SET last_active_update=? WHERE id=?",
        array(
            date("Y/m/d h:i:s A"),
            $this->id
        ));

        return ($this->db->error()) ? false : true;
    }

    public function isLoggedIn() {
        return $this->isLoggedIn;
    }

    public function jsonSerialize()
    {
        //$vars = get_object_vars($this);
        $vars = array(
            "id"=>$this->id,
            "username"=>$this->username
        );
        return $vars;
    }
}