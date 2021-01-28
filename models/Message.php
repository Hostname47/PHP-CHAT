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
        $this->message_date = $data["message_date"];
    }

    public function get_message($property, $value) {
        $this->db->query("SELECT * FROM `message` WHERE `$property` = ?", array($value));

        if($this->db->count() > 0) {
            $fetched_message = $this->db->results()[0];

            $this->id = $fetched_message->id;
            $this->message_sender = $fetched_message->message_creator;
            $this->message = $fetched_message->message;
            $this->message_date = $fetched_message->create_date;

            return true;
        }

        return false;
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

        $message_row_inserted = $this->db->error();

        // GET LAST MESSAGE ID TO GIVE IT TO RECIPIENT TABLE
        $last_inserted_message_id = $this->db->pdo()->lastInsertId();

        /* 
            Here notice that we need to check if the receiver user is subscribed to the channel, and If so then we pass the message
            to the channel, otherwise we normally store it in the database.
        */
        $this->db->query("INSERT INTO `channel` 
        (`sender`, `receiver`, `group_recipient_id`, `message_id`) 
        VALUES (?, ?, ?, ?)", array(
            $this->message_sender,
            $this->message_receiver,
            null,
            $last_inserted_message_id
        ));

        $channel_row_inserted = $this->db->error();

        $this->db->query("INSERT INTO `message_recipient` 
        (`receiver_id`, `message_id`, `is_read`) 
        VALUES (?, ?, ?)", array(
            $this->message_receiver,
            $last_inserted_message_id,
            $this->is_read
        ));

        $message_recipient_row_inserted = $this->db->error();

        return $this->db->error() == false ? true : false;
    }

    public static function dump_channel($sender, $receiver) {
        DB::getInstance()->query("DELETE FROM channel WHERE sender = ? AND receiver = ?", array($sender, $receiver));
    }

    public static function getMessages($sender, $receiver) {
        //$this->db->query("SELECT * FROM `message_recipient` WHERE receiver_id = ? AND message.message_creator = ? ");
        DB::getInstance()->query("SELECT * FROM `message_recipient`
        INNER JOIN `message` 
        ON message.id = message_recipient.message_id 
        WHERE message_recipient.receiver_id = ? AND message.message_creator = ?", array(
            $receiver,
            $sender
        ));

        return DB::getInstance()->results();
    }

    public function add_writing_message_notifier() {
        $this->db->query("INSERT INTO `writing_message_notifier` 
        (`message_writer`, `message_waiter`) 
        VALUES (?, ?)", array(
            $this->message_sender,
            $this->message_receiver,
        ));

        return $this->db->error() == false ? true : false;
    }
    public function delete_writing_message_notifier() {
        $this->db->query("DELETE FROM `writing_message_notifier` WHERE `message_writer` = ? AND `message_waiter` = ?"
        , array(
            $this->message_sender,
            $this->message_receiver,
        ));

        return $this->db->error() == false ? true : false;
    }

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}
