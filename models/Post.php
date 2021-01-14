<?php

namespace models;

use classes\{DB};

class Post {
    private $db,
    $post_id,
    $post_owner,
    $post_visibility=1,
    $post_place=1,
    $post_date='',
    $post_edit_date='',
    $text_content='',
    $picture_media='',
    $video_media='';

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function get_property($propertyName) {
        return $this->$propertyName;
    }
    
    public function set_property($propertyName, $propertyValue) {
        $this->$propertyName = $propertyValue;
    }

    public function setData($data = array()) {
        $this->post_owner = $data["post_owner"];
        $this->post_visibility = $data["post_visibility"];
        $this->post_visibility = $data["post_place"];
        $this->post_edit_date = null;
        $this->post_date = empty("post_date") ? date("Y/m/d h:i:s") : $data["post_date"];
        $this->text_content = $data["text_content"];
        $this->picture_media = $data["picture_media"];
        $this->video_media = $data["video_media"];
    }

    public function add() {
        $this->db->query("INSERT INTO post 
        (post_owner, post_visibility, post_place, post_date, text_content, picture_media, video_media) 
        VALUES (?, ?, ?, ?, ?, ?, ?)", array(
            $this->post_owner,
            $this->post_visibility,
            $this->post_place,
            $this->post_date,
            $this->text_content,
            $this->picture_media,
            $this->video_media
        ));

        return $this->db->error() == false ? true : false;
    }

    // Remember to use this function only 
    public function fetchPost($id) {
        $this->db->query("SELECT * FROM post WHERE id = ?", array($id));

        // Here we need to check first if we get a user with the given id before starting assigning values to its properties
        if($this->db->count() > 0) {
            $fetchedPost = $this->db->results()[0];

            $this->post_id = $fetchedPost->id;
            $this->post_owner = $fetchedPost->post_owner;
            $this->post_visibility = $fetchedPost->post_visibility;
            $this->post_place = $fetchedPost->post_place;
            $this->post_date = $fetchedPost->post_date;
            $this->text_content = $fetchedPost->text_content;
            $this->picture_media = $fetchedPost->picture_media;
            $this->video_media = $fetchedPost->video_media;

            return true;
        }

        return false;
    }

    public static function get($field_name, $field_value) {
        DB::getInstance()->query("SELECT * FROM post WHERE $field_name = ?", array($field_value));

        // Here we will store posts fetched by query method
        $posts = array();

        if(DB::getInstance()->count() > 0) {
            $fetched_posts = DB::getInstance()->results();

            foreach($fetched_posts as $post) {
                $f_post = new Post();

                $f_post->post_id = $post->id;
                $f_post->post_owner = $post->post_owner;
                $f_post->post_visibility = $post->post_visibility;
                $f_post->post_place = $post->post_place;
                $f_post->post_date = $post->post_date;
                $f_post->text_content = $post->text_content;
                $f_post->picture_media = $post->picture_media;
                $f_post->video_media = $post->video_media;

                $posts[] = $f_post;
            }
        }

        return $posts;
    }

    public static function get_posts_number($user_id) {
        DB::getInstance()->query("SELECT * FROM post WHERE post_owner = ?", array($user_id));

        return DB::getInstance()->count();
    }

    public function toString() {
        return 'Post with id: ' . $this->post_id . " and owner of id: " . $this->post_owner . " published at: " . $this->post_date . "<br>";
    }
}
