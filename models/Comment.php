<?php

namespace models;
use classes\{DB};

class Comment {
    private $db,
    $id,
    $comment_owner,
    $post_id,
    $comment_date='',
    $comment_edit_date=null,
    $comment_text='';

    public function __construct() {
        $this->db = DB::getInstance();
        $this->comment_date = date("Y/m/d H:i:s");
    }

    public function get_property($propertyName) {
        return $this->$propertyName;
    }
    
    public function set_property($propertyName, $propertyValue) {
        $this->$propertyName = $propertyValue;
    }

    public function setData($data = array()) {
        $this->comment_owner = $data["comment_owner"];
        $this->post_id = $data["post_id"];
        $this->comment_text = $data["comment_text"];
    }

    public function add() {
        $this->db->query("INSERT INTO `comment` 
        (`comment_owner`, `post_id`, `comment_date`, `comment_edit_date`, `comment_text`) 
        VALUES (?, ?, ?, ?, ?)", array(
            $this->comment_owner,
            $this->post_id,
            $this->comment_date,
            $this->comment_edit_date,
            $this->comment_text
        ));

        return $this->db->error() == false ? $this : false;
    }

    public function update() {
        $this->db->query("UPDATE comment SET `comment_owner` = ?, 
            `post_id` = ?, `comment_date` = ?, `comment_edit_date` = ?, `comment_text` = ? WHERE `id` = ?
        ", array(
            $this->comment_owner,
            $this->post_id,
            $this->comment_date,
            // Notice we set the edit time to current time because we only call update when we want to edit a comment
            date("Y/m/d H:i:s"),
            $this->comment_text,
            $this->id,
        ));

        $i = $this->id;

        return $this->db->error() == false ? $this : false;
    }

    public function delete() {
        $this->db->query("DELETE FROM `comment` WHERE id = ?",
            array($this->id));

        return $this->db->error() == false ? true : false;
    }

    public function fetch_comment($value, $property="id") {
        $this->db->query("SELECT * FROM comment WHERE $property = ?", array($value));
        if($this->db->count() > 0) {
            $fetched_comment = $this->db->results()[0];

            $this->id = $fetched_comment->id;
            $this->comment_owner = $fetched_comment->comment_owner;
            $this->post_id = $fetched_comment->post_id;
            $this->comment_date = $fetched_comment->comment_date;
            $this->comment_edit_date = $fetched_comment->comment_edit_date;
            $this->comment_text = $fetched_comment->comment_text;

            return true;
        }

        return false;
    }

    public static function fetch_post_comments($post_id) {
        DB::getInstance()->query("SELECT * FROM comment WHERE post_id = ?", array($post_id));
        if(DB::getInstance()->count() > 0) {
            return DB::getInstance()->results();
        }

        return array();
    }

    public static function get($field_name, $field_value) {
        DB::getInstance()->query("SELECT * FROM post WHERE $field_name = ?", array($field_value));

        if(DB::getInstance()->count() > 0) {
            return DB::getInstance()->results();
        }

        return array();
    }

    public static function delete_post_comments($post_id) {
        DB::getInstance()->query("DELETE FROM `comment` WHERE post_id = ?",
            array($post_id)
        );

        return DB::getInstance()->error() == false ? true : false;
    }

    public function toString() {
        return 'Post with id: ' . $this->post_id . " and owner of id: " . $this->post_owner . " published at: " . $this->post_date . "<br>";
    }
}
