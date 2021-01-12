<?php

namespace models;

use classes\{DB};

class Post {
    private $db,
    $post_id,
    $post_owner,
    $post_visibility=0,
    $post_date='',
    $post_edit_date='',
    $text_content='',
    $picture_media='',
    $video_media='';

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function getproperty($propertyName) {
        return $this->$propertyName;
    }
    
    public function setproperty($propertyName, $propertyValue) {
        $this->$propertyName = $propertyValue;
    }

    public function setData($data = array()) {
        $this->post_owner = $data["post_owner"];
        $this->post_visibility = $data["post_visibility"];
        $this->post_edit_date = null;
        $this->post_date = empty("post_date") ? date("Y/m/d h:i:s") : $data["post_date"];
        $this->text_content = $data["text_content"];
        $this->picture_media = $data["picture_media"];
        $this->video_media = $data["video_media"];
    }

    public function add() {
        $this->db->query("INSERT INTO post 
        (post_owner, post_visibility, post_date, text_content, picture_media, video_media) 
        VALUES (?, ?, ?, ?, ?, ?)", array(
            $this->post_owner,
            $this->post_visibility,
            $this->post_date,
            $this->text_content,
            $this->picture_media,
            $this->video_media
        ));

        return $this->db->error() == false ? true : false;
    }
}
