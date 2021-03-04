<?php

namespace models;
use classes\{DB};

class Like {
    private $db,
    $id,
    $post_id,
    $user_id,
    $like_date='';

    public function __construct() {
        $this->db = DB::getInstance();
        $this->like_date = date("Y/m/d H:i:s");
    }

    public function get_property($propertyName) {
        return $this->$propertyName;
    }
    
    public function set_property($propertyName, $propertyValue) {
        $this->$propertyName = $propertyValue;
    }

    public function setData($data = array()) {
        $this->post_id = $data["post_id"];
        $this->user_id = $data["user_id"];
    }

    public function exists() {
        $this->db->query("SELECT * FROM `like` 
        WHERE `post_id` = ? AND `user_id` = ?", array(
            $this->post_id,
            $this->user_id
        ));

        return $this->db->count() > 0 ? true : false;
    }

    public function add() {
        if($this->exists()) {
            return -1;
        }

        $this->db->query("INSERT INTO `like` 
        (`post_id`, `user_id`, `like_date`) 
        VALUES (?, ?, ?)", array(
            $this->post_id,
            $this->user_id,
            $this->like_date,
        ));

        return $this->db->error() == false ? 1 : false;
    }

    public function delete() {
        $this->db->query("DELETE FROM `like` WHERE `post_id` = ? AND `user_id` = ?",
            array(
                $this->post_id,
                $this->user_id
            )
        );

        return $this->db->error() == false ? true : false;
    }

    public static function delete_post_likes($post_id) {
        DB::getInstance()->query("DELETE FROM `like` WHERE `post_id` = ?",
            array(
                $post_id
            )
        );

        return DB::getInstance()->error() == false ? true : false;
    }

    public function get_post_users_likes_by_post($post_id) {
        $this->db->query("SELECT * FROM `like` WHERE post_id = ?", array($post_id));
        $users = array();
        if($this->db->count() > 0) {
            $fetched_like_users = $this->db->results();
            foreach($fetched_like_users as $user) {
                $u = new User();
                $u->fetchUser("id", $user->id);

                $users[] = $u;
            }
        }

        return $users;
    }

    public function toString() {
        return 'Post with id: ' . $this->post_id . " and owner of id: " . $this->post_owner . " published at: " . $this->post_date . "<br>";
    }
}
