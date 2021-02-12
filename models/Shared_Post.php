<?php

namespace models;
use classes\{DB, Config, Common};

class Shared_Post {
    private $db,
    $id,
    $poster_id,
    $post_id,
    $post_visibility=1,
    $post_place=1,
    $shared_post_date,
    $shared_post_edit_date=null;

    public function __construct() {
        $this->db = DB::getInstance();
        $this->shared_post_date = date("Y/m/d H:i:s");
    }

    public function get_property($propertyName) {
        return $this->$propertyName;
    }
    
    public function set_property($propertyName, $propertyValue) {
        $this->$propertyName = $propertyValue;
    }

    public function setData($data = array()) {
        $this->poster_id = $data["poster_id"];
        $this->post_id = $data["post_id"];
        $this->post_visibility = isset($data["post_visibility"]) ? $data["post_visibility"] : $this->post_visibility;
        $this->post_place = isset($data["post_place"]) ? $data["post_place"] : $this->post_place;
    }

    public function add() {
        $this->db->query("INSERT INTO `shared_post` 
        (poster_id, post_id, post_visibility, post_place, shared_post_date) 
        VALUES (?, ?, ?, ?, ?)", array(
            $this->poster_id,
            $this->post_id,
            $this->post_visibility,
            $this->post_place,
            $this->shared_post_date
        ));

        return $this->db->error() == false ? $this : false;
    }

    public static function get_shared_post_owner($post_id) {
        DB::getInstance()->query("SELECT * FROM `shared_post` WHERE id = ?", array($post_id));

        if(DB::getInstance()->count() > 0) {
            return DB::getInstance()->results()[0];
        }

        return false;
    }

    public static function get_post_share_numbers($post_id) {
        DB::getInstance()->query("SELECT * FROM shared_post WHERE post_id = ?", array($post_id));

        return DB::getInstance()->count();
    }

    public function toString() {
        return 'Post with id: ' . $this->id . " and owner of id: " . $this->poster_id . " published at: " . $this->shared_post_date . "<br>";
    }
}
