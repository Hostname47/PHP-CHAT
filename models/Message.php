<?php

namespace models;

use classes\{DB};

class Message {
    private $db,
    $id,
    $message_sender,
    $message_receiver,
    $message,
    $message_date = '',
    $recipient_id,
    $is_read = false;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function get_property($propertyName) {
        return $this->$propertyName;
    }
    
    public function set_property($propertyName, $propertyValue) {
        $this->$propertyName = $propertyValue;
    }

    public function set_data($data = array()) {
        $this->message_sender = $data["sender"];
        $this->message_receiver = $data["receiver"];
        $this->message = $data["message"];
        //$this->message_date = !array_key_exists("message_date", $data) ? date("Y/m/d h:i:s") : $this->message_date;
        $this->message_date = $data["message_date"];

    }

    public function add() {
        /*
            When we add a message to message table we need also to insert a row to recipient table to specify the receiver
            of that message
        */

        $this->db->query("INSERT INTO `message` 
        (`message_creator`, `message`, `create_date`) 
        VALUES (?, ?, ?)", array(
            $this->message_sender,
            $this->message,
            $this->message_date
        ));

        // GET LAST MESSAGE ID TO GIVE IT TO RECIPIENT TABLE
        $last_inserted_message_id = $this->db->pdo()->lastInsertId();

        $this->db->query("INSERT INTO `message_recipient` 
        (`receiver_id`, `message_id`, `is_read`) 
        VALUES (?, ?, ?)", array(
            $this->message_receiver,
            $last_inserted_message_id,
            $this->is_read
        ));

        return $this->db->error() == false ? true : false;
    }
}
